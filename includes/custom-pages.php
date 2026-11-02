<?php
/**
 * includes/custom-pages.php
 * Helpers for page.php (admin "custom pages"), location.php (list of those pages)
 * and the policy pages (privacy, terms, refund, shipping).
 * Load AFTER includes/functions.php:   require_once __DIR__ . '/includes/custom-pages.php';
 */

/** HTML tags allowed inside custom page content (everything else is stripped). */
const CUSTOM_PAGE_TAGS = '<p><br><hr><strong><b><em><i><u><small><sub><sup><mark><ul><ol><li><a><h2><h3><h4><h5><table><thead><tbody><tfoot><tr><th><td><blockquote><span><div><figure><figcaption><img>';

/* ---------- custom pages (table custom_pages) ---------- */

function custom_page_card(array $r): array {
    $title = trim((string)$r['breadcrumb_title']) !== '' ? $r['breadcrumb_title'] : $r['page_title'];
    $desc  = trim(strip_tags((string)$r['meta_desc']));
    if (mb_strlen($desc) < 30) $desc = (string)$r['content'];            // a one-word meta description is not useful on a card
    return [
        'title' => $title, 'full_title' => $r['page_title'], 'slug' => $r['slug_url'],
        'desc'  => excerpt($desc, 150), 'url' => 'page/' . rawurlencode($r['slug_url']),
    ];
}

/** Published custom pages for cards and lists. */
function get_custom_pages(int $limit = 200, string $exclude = ''): array {
    $t = ''; $p = [];
    $w = stale_where(['page_title', 'content', 'meta_title'], $t, $p);
    if ($exclude !== '') { $w .= " AND slug_url <> ?"; $t .= 's'; $p[] = $exclude; }
    $rows = db_all("SELECT page_title, breadcrumb_title, slug_url, meta_desc, SUBSTRING(content,1,600) AS content, created_at
                    FROM custom_pages WHERE status = 1 AND slug_url <> '' $w ORDER BY id DESC LIMIT " . (int)$limit, $t, $p);
    return array_map('custom_page_card', $rows);
}

function count_custom_pages(): int {
    static $n = null;
    if ($n === null) $n = (int)(db_all("SELECT COUNT(*) AS n FROM custom_pages WHERE status = 1 AND slug_url <> ''")[0]['n'] ?? 0);
    return $n;
}

/** Cleans editor HTML, builds the table of contents (h2 headings), fixes relative image paths and wraps tables. */
function prepare_html(string $html): array {
    $html = rewrite_links(safe_html($html, CUSTOM_PAGE_TAGS));
    $html = preg_replace_callback('~(<img\b[^>]*?\ssrc=)(["\'])(.*?)\2~i', function ($m) {
        $src = html_entity_decode($m[3]);
        if (preg_match('~^(https?:)?//|^data:|^/~i', $src)) return $m[0];
        return $m[1] . $m[2] . e(find_img($src, 'about') ?: url($src)) . $m[2];
    }, $html);
    $html = preg_replace('~<img\b(?![^>]*\bloading=)~i', '<img loading="lazy"', $html);
    $toc = [];
    $html = preg_replace_callback('~<h2\b[^>]*>(.*?)</h2>~is', function ($m) use (&$toc) {
        $txt = trim(html_entity_decode(strip_tags($m[1]), ENT_QUOTES, 'UTF-8'));
        if ($txt === '') return $m[0];
        $id = 's' . (count($toc) + 1) . '-' . substr(slugify($txt), 0, 40);
        $toc[] = [$id, $txt];
        return '<h2 id="' . e($id) . '">' . $m[1] . '</h2>';
    }, $html);
    $html = preg_replace('~<table\b~i', '<div class="table-wrap"><table', $html);
    $html = str_ireplace('</table>', '</table></div>', $html);
    return [$html, $toc];
}

/** Schema typed into the admin panel: accepted only if it is valid JSON (with or without the script tag). */
function json_ld_from_admin(string $raw): string {
    $raw = trim($raw);
    if ($raw === '') return '';
    if (preg_match('~<script\b[^>]*>(.*?)</script>~is', $raw, $m)) $raw = $m[1];
    $j = json_decode(trim($raw), true);
    return is_array($j) ? '<script type="application/ld+json">' . json_encode($j, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . '</script>' : '';
}

function get_custom_page(string $slug): ?array {
    $slug = trim($slug);
    if ($slug === '') return null;
    $t = 's'; $p = [$slug];
    $w = stale_where(['page_title', 'content', 'meta_title'], $t, $p);
    $r = db_all("SELECT * FROM custom_pages WHERE slug_url = ? AND status = 1 $w LIMIT 1", $t, $p)[0] ?? null;
    if (!$r) return null;
    [$html, $toc] = prepare_html((string)$r['content']);
    return [
        'title' => $r['page_title'], 'crumb' => trim((string)$r['breadcrumb_title']) !== '' ? $r['breadcrumb_title'] : $r['page_title'],
        'slug'  => $r['slug_url'], 'html' => $html, 'toc' => $toc, 'date' => fmt_date($r['created_at']),
        'text'  => excerpt($r['content'], 160), 'schema' => json_ld_from_admin((string)$r['schema_markup']),
        'meta'  => ['title' => trim((string)$r['meta_title']), 'desc' => trim((string)$r['meta_desc']), 'keys' => trim((string)$r['meta_key'])],
    ];
}

/* ---------- policy pages ---------- */

function legal_links(): array {
    return [['Privacy Policy', 'privacy-policy'], ['Terms & Conditions', 'terms-condition'], ['Refund & Cancellation', 'refund-policy'], ['Shipping & Delivery', 'shipping-return']];
}

/** <ul> from a list of plain-text or HTML items (items must already be escaped where needed). */
function legal_ul(array $items): string { return '<ul>' . implode('', array_map(function ($i) { return '<li>' . $i . '</li>'; }, $items)) . '</ul>'; }

/** Contact box used at the end of every policy. Email, phone, address and hours come from the database. */
function legal_contact_html(array $c): string {
    $h  = '<div class="legal-contact"><strong>' . e(COMPANY['legal']) . '</strong>';
    $h .= '<span>Proprietor: ' . e(COMPANY['owner']) . '</span>';
    $h .= '<span>' . icon('mail') . '<a href="mailto:' . e($c['email']) . '">' . e($c['email']) . '</a></span>';
    $h .= '<span>' . icon('phone') . '<a href="' . e($c['tel']) . '">' . e($c['phone_txt']) . '</a></span>';
    $h .= '<span>' . icon('pin') . e($c['address']) . '</span>';
    if ($c['hours'] !== '') $h .= '<span>' . icon('clock') . e($c['hours']) . '</span>';
    return $h . '</div>';
}
