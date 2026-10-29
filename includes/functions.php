<?php
/**
 * includes/functions.php
 * Shared helpers + data getters for BHARAT TRADING.
 * Every section on the home page reads from the database through these functions.
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }
define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/connect.php';          // provides $conn and $site
require_once __DIR__ . '/content.php';                 // static genuine content (defaults)

/* ================= SETTINGS (edit here) ================= */
const STALE_GUARD             = true;                    // hide leftover data from the previous website
const STALE_MARKERS           = ['bhagirath', 'azadpur']; // words that identify that old data
const BANNER_ACTIVE_STATUS    = 0;                       // value of banners.status that means "show"
const REVIEWS_ARE_FROM_GOOGLE = true;                    // set false if admin reviews are not real Google reviews
const GOOGLE_REVIEW_URL       = '';                      // paste your Google "write a review" link
const UPLOAD_BASES            = ['', 'uploads/', 'admin/uploads/', 'admin/']; // where admin uploads may live

const COMPANY = [
    'name'       => 'Bharat Trading',
    'legal'      => 'BHARAT TRADING',
    'owner'      => 'Bharat Gangadhar Vable',
    'role'       => 'Proprietor',
    'email'      => 'bharattrade2008@gmail.com',
    'phone'      => '7304818011',
    'address'    => 'Bharat Apartments, Plot No. 11, Talak Path, Nashik Road, Nashik, Maharashtra 422101, India',
    'rcmc'       => 'RCMC/APEDA/34319/2026-2027',
    'rcmc_valid' => '14 September 2031',
    'gstin'      => '27AOEPV7897Q2ZG',
    'iec'        => 'AOEPV7897Q',
    'udyam'      => 'UDYAM-MH-23-0129181',
    'capacity'   => 12000,
    'markets'    => ['Qatar', 'Saudi Arabia', 'United Arab Emirates'],
];

/* ================= BASIC HELPERS ================= */
function e($s): string { return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

function url(string $path = ''): string {
    global $site;
    return rtrim($site, '/') . '/' . ltrim($path, '/');
}

function db_all(string $sql, string $types = '', array $params = []): array {
    global $conn;
    try {
        if ($types === '') {
            $res = $conn->query($sql);
            return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
        }
        $st = $conn->prepare($sql);
        $st->bind_param($types, ...$params);
        $st->execute();
        $rows = $st->get_result()->fetch_all(MYSQLI_ASSOC);
        $st->close();
        return $rows;
    } catch (Throwable $ex) {
        error_log('[db_all] ' . $ex->getMessage());
        return [];
    }
}

function is_stale(...$vals): bool {
    if (!STALE_GUARD) return false;
    foreach ($vals as $v) {
        $v = strtolower((string)$v);
        foreach (STALE_MARKERS as $m) { if ($v !== '' && strpos($v, $m) !== false) return true; }
    }
    return false;
}

/** Builds "AND col NOT LIKE ?" clauses so old-site rows are skipped in SQL. */
function stale_where(array $cols, string &$types, array &$params): string {
    if (!STALE_GUARD) return '';
    $sql = '';
    foreach ($cols as $c) {
        foreach (STALE_MARKERS as $m) {
            $sql .= " AND IFNULL($c,'') NOT LIKE ?";
            $types .= 's';
            $params[] = '%' . $m . '%';
        }
    }
    return $sql;
}

function excerpt($html, int $len = 140): string {
    $t = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags((string)$html), ENT_QUOTES, 'UTF-8')));
    return mb_strlen($t) > $len ? rtrim(mb_substr($t, 0, $len - 1)) . '…' : $t;
}

/** Safe read of a text value from the query string. */
function qs(string $k): string { $v = $_GET[$k] ?? ''; return is_string($v) ? trim($v) : ''; }

function slugify(string $s): string { return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($s)), '-') ?: 'item'; }
function initials(string $n): string {
    $p = preg_split('/\s+/', trim($n)); $o = '';
    foreach (array_slice($p, 0, 2) as $w) { $o .= mb_strtoupper(mb_substr($w, 0, 1)); }
    return $o ?: 'C';
}
function fmt_date($d): string { $t = strtotime((string)$d); return $t ? date('d M Y', $t) : ''; }
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}

function phone_intl(string $p): string {
    $d = preg_replace('/\D+/', '', $p);
    return strlen($d) === 10 ? '91' . $d : $d;
}
function phone_display(string $p): string {
    $d = preg_replace('/\D+/', '', $p);
    if (strlen($d) === 10) return '+91 ' . substr($d, 0, 5) . ' ' . substr($d, 5);
    return $p;
}

/* ================= IMAGES & LOGO ================= */
/** Finds an uploaded file in common admin folders. Returns URL or '' if the file does not exist. */
function find_img($file, array $subs = []): string {
    $file = trim((string)$file);
    if ($file === '') return '';
    if (preg_match('~^(https?:)?//~i', $file)) return $file;
    $file = ltrim(str_replace('\\', '/', $file), '/');
    $subs = array_merge([''], array_map(function ($s) { return trim($s, '/'); }, $subs));
    foreach (UPLOAD_BASES as $b) {
        foreach ($subs as $s) {
            $p = $b . ($s !== '' ? $s . '/' : '') . $file;
            if (is_file(ROOT_PATH . '/' . $p)) {
                return url(implode('/', array_map('rawurlencode', explode('/', $p))));
            }
        }
    }
    return '';
}

function get_logo(string $loc): string {
    static $cache = [];
    if (!isset($cache[$loc])) {
        $r = db_all("SELECT logo_path FROM logos WHERE location=? AND is_active=1 ORDER BY id DESC LIMIT 1", 's', [$loc]);
        $cache[$loc] = $r ? find_img($r[0]['logo_path'], ['logo', 'uploads/logo']) : '';
    }
    return $cache[$loc];
}

/** Uploaded logo if present, otherwise the built-in BHARAT TRADING wordmark. */
function logo_html(string $loc = 'header', bool $light = false): string {
    $src = get_logo($loc);
    if ($src) {
        return '<img class="brand-img" src="' . e($src) . '" alt="' . e(COMPANY['legal']) . '" width="200" height="60">';
    }
    return '<span class="wordmark' . ($light ? ' wordmark--light' : '') . '" role="img" aria-label="' . e(COMPANY['legal']) . '">'
         . '<span class="wm-mark" aria-hidden="true">B</span>'
         . '<span class="wm-text"><b>BHARAT</b><i>TRADING</i></span></span>';
}

/* ================= ICONS ================= */
function icon(string $n, string $cls = 'ico'): string {
    static $i = null;
    if ($i === null) {
        $i = [
            'mail'   => ['0 0 24 24', '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>'],
            'phone'  => ['0 0 24 24', '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>'],
            'pin'    => ['0 0 24 24', '<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/>'],
            'clock'  => ['0 0 24 24', '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
            'check'  => ['0 0 24 24', '<polyline points="20 6 9 17 4 12"/>'],
            'arrow'  => ['0 0 24 24', '<line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/>'],
            'left'   => ['0 0 24 24', '<line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>'],
            'shield' => ['0 0 24 24', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>'],
            'box'    => ['0 0 24 24', '<path d="M16.5 9.4l-9-5.19M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>'],
            'search' => ['0 0 24 24', '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>'],
            'globe'  => ['0 0 24 24', '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>'],
            'award'  => ['0 0 24 24', '<circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/>'],
            'wa'     => ['0 0 24 24', '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>'],
            'up'     => ['0 0 24 24', '<polyline points="18 15 12 9 6 15"/>'],
            'facebook'  => ['0 0 24 24', '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>'],
            'instagram' => ['0 0 24 24', '<rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>'],
            'twitter'   => ['0 0 24 24', '<path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/>'],
            'linkedin'  => ['0 0 24 24', '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>'],
            'star'   => ['0 0 24 24', '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>', 'f'],
            'g'      => ['0 0 24 24', '<path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.46a5.52 5.52 0 0 1-2.4 3.62v3h3.88c2.27-2.09 3.58-5.17 3.58-8.81z"/><path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.94-2.91l-3.88-3a7.2 7.2 0 0 1-10.7-3.78H1.34v3.09A12 12 0 0 0 12 24z"/><path fill="#FBBC05" d="M5.36 14.31a7.2 7.2 0 0 1 0-4.62V6.6H1.34a12 12 0 0 0 0 10.8l4.02-3.09z"/><path fill="#EA4335" d="M12 4.77c1.76 0 3.34.61 4.59 1.8l3.44-3.44A11.5 11.5 0 0 0 12 0 12 12 0 0 0 1.34 6.6l4.02 3.09A7.15 7.15 0 0 1 12 4.77z"/>', 'r'],
            // large line icons used on category / product tiles
            'onion' => ['0 0 64 64', '<path d="M32 9c-1 6-3 9-3 13M32 9c1 6 3 9 3 13"/><path d="M32 22c-14 4-22 16-18 26 3 8 11 11 18 11s15-3 18-11c4-10-4-22-18-26z"/><path d="M32 22c-9 8-9 28 0 37M32 22c9 8 9 28 0 37"/>', 'b'],
            'rice'  => ['0 0 64 64', '<path d="M32 59V18"/><ellipse cx="24" cy="26" rx="4" ry="8" transform="rotate(-32 24 26)"/><ellipse cx="40" cy="26" rx="4" ry="8" transform="rotate(32 40 26)"/><ellipse cx="24" cy="40" rx="4" ry="8" transform="rotate(-32 24 40)"/><ellipse cx="40" cy="40" rx="4" ry="8" transform="rotate(32 40 40)"/><ellipse cx="32" cy="12" rx="3.5" ry="7"/>', 'b'],
            'spice' => ['0 0 64 64', '<path d="M18 22c10-9 30-5 30 14 0 13-9 22-26 23 9-6 13-15 11-25-1-6-8-8-15-12z"/><path d="M18 22c-3-4-2-9 3-12"/>', 'b'],
        ];
    }
    if (!isset($i[$n])) return '';
    [$vb, $in] = $i[$n];
    $mode = $i[$n][2] ?? 's';
    $attr = ['s' => 'fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"',
             'b' => 'fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"',
             'f' => 'fill="currentColor"', 'r' => ''][$mode];
    return '<svg class="' . $cls . '" viewBox="' . $vb . '" ' . $attr . ' aria-hidden="true" focusable="false">' . $in . '</svg>';
}

function stars(int $n): string {
    $o = '<span class="stars" role="img" aria-label="' . $n . ' out of 5">';
    for ($k = 1; $k <= 5; $k++) { $o .= '<span class="' . ($k <= $n ? 'on' : 'off') . '">' . icon('star') . '</span>'; }
    return $o . '</span>';
}

function guess_group(string $name): string {
    $n = strtolower($name);
    if (strpos($n, 'onion') !== false) return 'onion';
    if (strpos($n, 'rice') !== false || strpos($n, 'basmati') !== false) return 'rice';
    return 'spice';
}

/* ================= DATA GETTERS ================= */
function get_contact(): array {
    static $c = null;
    if ($c !== null) return $c;
    $r = db_all("SELECT * FROM contacts ORDER BY id DESC LIMIT 1")[0] ?? [];
    if ($r && is_stale($r['name'] ?? '', $r['email'] ?? '', $r['contact_email'] ?? '', $r['address'] ?? '', $r['map'] ?? '')) $r = [];

    $phone   = trim($r['phone'] ?? '') ?: COMPANY['phone'];
    $wa      = trim($r['wp_number'] ?? '') ?: $phone;
    $address = trim(($r['address'] ?? '') . ' ' . ($r['address2'] ?? '')) ?: COMPANY['address'];
    $map     = trim($r['map'] ?? '');
    if (stripos($map, '<iframe') !== false && preg_match('/src=["\']([^"\']+)/i', $map, $m)) $map = html_entity_decode($m[1]);
    if ($map === '' || stripos($map, 'embed') === false) {
        $map = 'https://www.google.com/maps?q=' . rawurlencode($address) . '&output=embed';
    }
    $c = [
        'email'     => trim($r['contact_email'] ?? '') ?: (trim($r['email'] ?? '') ?: COMPANY['email']),
        'phone'     => $phone,
        'phone_txt' => phone_display($phone),
        'tel'       => 'tel:+' . phone_intl($phone),
        'wa'        => 'https://wa.me/' . phone_intl($wa),
        'hours'     => trim($r['working_hours'] ?? ''),
        'address'   => $address,
        'map'       => $map,
        'social'    => array_filter([
            'facebook'  => trim($r['facebook'] ?? ''),
            'instagram' => trim($r['instagram'] ?? ''),
            'twitter'   => trim($r['twitter'] ?? ''),
            'linkedin'  => trim($r['linkdin'] ?? ''),
        ]),
    ];
    return $c;
}

function page_meta(string $page = 'index.php', array $d = []): array {
    $d += [
        'title'    => 'BHARAT TRADING | Indian Onion, Basmati Rice & Spice Exporter, Nashik',
        'desc'     => 'APEDA-registered merchant exporter from Nashik supplying fresh Indian onions, Basmati and non-Basmati rice and Indian spices with customised export packing.',
        'keywords' => 'Indian onion exporter, fresh Indian onions supplier, Basmati rice exporter, 1121 White Sella rice, Golden Sella rice, Non-Basmati rice supplier, turmeric exporter, red chilli exporter, cumin seeds supplier, coriander seeds exporter, black pepper supplier, Indian spices exporter',
    ];
    if ($page === 'index.php') {
        $r = db_all("SELECT meta_title, meta_key, meta_desc FROM meta WHERE page_url IN ('index.php','index','home','/','') ORDER BY id DESC LIMIT 1")[0] ?? [];
    } else {
        $r = db_all("SELECT meta_title, meta_key, meta_desc FROM meta WHERE page_url = ? ORDER BY id DESC LIMIT 1", 's', [$page])[0] ?? [];
    }
    if ($r && is_stale($r['meta_title'] ?? '', $r['meta_desc'] ?? '', $r['meta_key'] ?? '')) $r = [];
    return [
        'title'    => trim($r['meta_title'] ?? '') ?: $d['title'],
        'desc'     => trim($r['meta_desc'] ?? '') ?: $d['desc'],
        'keywords' => trim($r['meta_key'] ?? '') ?: $d['keywords'],
    ];
}

function hero_fallback(): array {
    return [
        ['img' => '', 'link' => '', 'title' => 'Indian agricultural commodities, exported with care', 'desc' => 'Fresh onions, Basmati and non-Basmati rice and Indian spices, sourced to your specification and shipped from Nashik.'],
        ['img' => '', 'link' => '', 'title' => 'Basmati rice in the Sella varieties buyers ask for', 'desc' => '1121 White Sella, Golden Sella and other available Sella varieties, packed the way your market needs it.'],
        ['img' => '', 'link' => '', 'title' => 'Spices sourced to your grade, size and packing', 'desc' => 'Turmeric, red chilli, cumin, coriander, black pepper and more, with quality inspection arranged as agreed.'],
    ];
}

function get_banners(): array {
    $t = 'i'; $p = [BANNER_ACTIVE_STATUS];
    $w = stale_where(['title', 'description'], $t, $p);
    $rows = db_all("SELECT banner_path, title, description, link_url FROM banners
                    WHERE status = ? AND (start_date IS NULL OR start_date <= NOW()) AND (end_date IS NULL OR end_date >= NOW()) $w
                    ORDER BY display_order ASC, id DESC LIMIT 6", $t, $p);
    $out = [];
    foreach ($rows as $r) {
        $out[] = [
            'img'   => find_img($r['banner_path'], ['banners', 'uploads/banners']),
            'title' => trim($r['title'] ?? '') ?: 'Merchant exporter of Indian agricultural commodities',
            'desc'  => trim($r['description'] ?? ''),
            'link'  => trim($r['link_url'] ?? ''),
        ];
    }
    return $out;
}

function get_about(): array {
    $t = ''; $p = [];
    $w = stale_where(['title', 'content'], $t, $p);
    $r = db_all("SELECT title, content, image_url FROM about_sections WHERE 1=1 $w ORDER BY section_order ASC, id ASC LIMIT 1", $t, $p)[0] ?? null;
    if ($r && trim(strip_tags($r['content'])) !== '') {
        return [
            'title'   => $r['title'],
            'content' => strip_tags($r['content'], '<p><br><strong><b><em><i><ul><ol><li><a>'),
            'img'     => find_img($r['image_url'], ['about_us', 'uploads/about_us']),
        ];
    }
    return [
        'title'   => 'A merchant exporter built on buyer-first sourcing',
        'content' => '<p>Bharat Trading is an India-based proprietorship and merchant export business located in Nashik, Maharashtra. We source and supply agricultural commodities and food products for domestic and international buyers.</p>'
                   . '<p>Our portfolio covers fresh Indian onions, Basmati rice including 1121 White Sella and Golden Sella, non-Basmati rice, turmeric and a range of Indian spices. Grades, specifications, packing and shipment arrangements are agreed with each buyer before an order is confirmed.</p>',
        'img'     => '',
    ];
}

function get_categories(): array {
    static $out = null;
    if ($out !== null) return $out;
    $t = ''; $p = [];
    $w = stale_where(['c.categories', 'c.meta_title', 'c.meta_desc'], $t, $p);
    $rows = db_all("SELECT c.categories, c.image, c.slug_url,
                    (SELECT COUNT(*) FROM products p WHERE p.pro_cate = c.cate_id AND p.status = 1) AS total
                    FROM categories c WHERE c.status = 1 $w ORDER BY c.id ASC", $t, $p);
    $out = [];
    foreach ($rows as $r) {
        $slug = $r['slug_url'] ?: slugify($r['categories']);
        $out[] = [
            'name'  => $r['categories'],
            'slug'  => $slug,
            'img'   => find_img($r['image'], ['categories', 'category', 'uploads/categories', 'uploads/category']),
            'url'   => 'products.php?category=' . rawurlencode($slug),
            'count' => (int)$r['total'],
            'icon'  => guess_group($r['categories']),
        ];
    }
    if (!$out) {
        $n = [];
        foreach (static_catalogue() as $sp) { $n[$sp['cat_slug']] = ($n[$sp['cat_slug']] ?? 0) + 1; }
        $out = [
            ['name' => 'Fresh Indian onions',        'slug' => 'fresh-produce', 'img' => '', 'url' => 'products.php?category=fresh-produce', 'count' => $n['fresh-produce'] ?? 0, 'icon' => 'onion'],
            ['name' => 'Basmati & non-Basmati rice', 'slug' => 'rice',          'img' => '', 'url' => 'products.php?category=rice',          'count' => $n['rice'] ?? 0,          'icon' => 'rice'],
            ['name' => 'Indian spices',              'slug' => 'spices',        'img' => '', 'url' => 'products.php?category=spices',        'count' => $n['spices'] ?? 0,        'icon' => 'spice'],
        ];
    }
    return $out;
}

/** First usable image from a field that may hold one name or a comma separated list. */
function first_img($field, array $subs): string {
    foreach (explode(',', (string)$field) as $f) { $u = find_img(trim($f), $subs); if ($u) return $u; }
    return '';
}
const PRODUCT_DIRS = ['products', 'product', 'uploads/products', 'uploads/product'];

/** Every visible product (database first, built-in catalogue if the database has none). */
function get_catalogue(): array {
    static $out = null;
    if ($out !== null) return $out;
    $t = ''; $p = [];
    $w = stale_where(['p.pro_name', 'p.short_desc', 'p.description', 'p.meta_title'], $t, $p);
    $rows = db_all("SELECT p.pro_name, SUBSTRING(p.short_desc,1,700) AS short_desc, SUBSTRING(p.description,1,700) AS description,
                           p.pro_img, p.slug_url, c.categories AS cat, c.slug_url AS cat_slug
                    FROM products p LEFT JOIN categories c ON c.cate_id = p.pro_cate
                    WHERE p.status = 1 AND (p.is_disabled = 0 OR p.is_disabled IS NULL) $w
                    ORDER BY p.id DESC LIMIT 500", $t, $p);
    $out = [];
    foreach ($rows as $r) {
        $cat = $r['cat'] ?: 'Products';
        $out[] = [
            'name' => $r['pro_name'], 'slug' => $r['slug_url'], 'cat' => $cat,
            'cat_slug' => $r['cat_slug'] ?: slugify($cat),
            'desc' => excerpt($r['description'] ?: $r['short_desc'], 110),
            'img'  => first_img($r['pro_img'], PRODUCT_DIRS),
            'url'  => 'product-details.php?slug=' . rawurlencode($r['slug_url']),
            'group' => guess_group($r['pro_name']), 'static' => false,
        ];
    }
    if (!$out) {
        foreach (static_catalogue() as $slug => $x) {
            $out[] = ['name' => $x['name'], 'slug' => $slug, 'cat' => $x['cat'], 'cat_slug' => $x['cat_slug'], 'desc' => $x['desc'], 'img' => '',
                      'url' => 'product-details.php?slug=' . $slug, 'group' => $x['group'], 'static' => true];
        }
    }
    return $out;
}

function get_products(int $limit = 8): array { return array_slice(get_catalogue(), 0, $limit); }
function count_products(): int { return count(get_catalogue()) ?: 8; }

/** Allows common formatting tags from the admin editor, removes scripts, inline styles and event handlers. */
function safe_html($html, string $allowed = '<p><br><strong><b><em><i><u><ul><ol><li><a><h2><h3><h4><table><thead><tbody><tfoot><tr><th><td><blockquote><span>'): string {
    $h = (string)$html;
    $h = preg_replace('~<h1(\b[^>]*)>~i', '<h2$1>', $h);
    $h = preg_replace('~</h1>~i', '</h2>', $h);
    $h = preg_replace('~<(script|style|iframe)\b.*?</\1>~is', '', $h);
    $h = strip_tags($h, $allowed);
    $h = preg_replace('/\s+on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $h);
    $h = preg_replace('/\s+style\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $h);
    $h = preg_replace('/(href)\s*=\s*(["\'])\s*javascript:[^"\']*\2/i', '$1=$2#$2', $h);
    return trim($h);
}

/** One product with everything the details page needs (database product or built-in one). */
function get_product_detail(string $slug): ?array {
    $slug = trim($slug);
    if ($slug === '') return null;
    $t = 's'; $p = [$slug];
    $w = stale_where(['p.pro_name', 'p.short_desc', 'p.description', 'p.meta_title'], $t, $p);
    $r = db_all("SELECT p.*, c.categories AS cat, c.slug_url AS cat_slug
                 FROM products p LEFT JOIN categories c ON c.cate_id = p.pro_cate
                 WHERE p.slug_url = ? AND p.status = 1 AND (p.is_disabled = 0 OR p.is_disabled IS NULL) $w LIMIT 1", $t, $p)[0] ?? null;
    if ($r) {
        $imgs = [];
        foreach (explode(',', (string)$r['pro_img']) as $f) { $u = find_img(trim($f), PRODUCT_DIRS); if ($u) $imgs[] = $u; }
        foreach (db_all("SELECT image_path FROM product_images WHERE product_id IN (?, ?) ORDER BY id ASC", 'ii', [(int)$r['id'], (int)$r['pro_id']]) as $x) {
            $u = find_img($x['image_path'], PRODUCT_DIRS);
            if ($u && !in_array($u, $imgs, true)) $imgs[] = $u;
        }
        $cat = $r['cat'] ?: 'Products';
        $facts = [['Product', $r['pro_name']], ['Category', $cat]];
        if (trim((string)$r['brand_name']) !== '') $facts[] = ['Brand', $r['brand_name']];
        array_push($facts, ['Packing', 'Customised as per buyer requirement'], ['Inspection', 'Arranged as mutually agreed']);
        return [
            'name' => $r['pro_name'], 'slug' => $r['slug_url'], 'cat' => $cat, 'cat_slug' => $r['cat_slug'] ?: slugify($cat),
            'group' => guess_group($r['pro_name']), 'static' => false, 'images' => $imgs,
            'summary' => excerpt($r['description'] ?: $r['short_desc'], 240),
            'paras' => [], 'overview_html' => safe_html($r['description']), 'spec_html' => safe_html($r['short_desc']),
            'facts' => $facts, 'uses' => [], 'faqs' => product_faqs($r['pro_name']),
            'meta' => ['title' => trim($r['meta_title']), 'desc' => trim($r['meta_desc']), 'keys' => trim((string)$r['meta_key'])],
        ];
    }
    $st = static_catalogue();
    if (isset($st[$slug])) {
        $x = $st[$slug];
        return [
            'name' => $x['name'], 'slug' => $slug, 'cat' => $x['cat'], 'cat_slug' => $x['cat_slug'], 'group' => $x['group'], 'static' => true, 'images' => [],
            'summary' => $x['summary'], 'paras' => $x['about'], 'overview_html' => '', 'spec_html' => '',
            'facts' => $x['facts'], 'uses' => $x['uses'], 'faqs' => product_faqs($x['name']),
            'meta' => ['title' => '', 'desc' => '', 'keys' => ''],
        ];
    }
    return null;
}

function get_related(string $slug, string $cat_slug, int $n = 4): array {
    $same = []; $other = [];
    foreach (get_catalogue() as $pr) {
        if ($pr['slug'] === $slug) continue;
        if ($pr['cat_slug'] === $cat_slug) { $same[] = $pr; } else { $other[] = $pr; }
    }
    return array_slice(array_merge($same, $other), 0, $n);
}

/** All About Us sections from the admin panel, in order (built-in text if none). */
function get_about_sections(): array {
    $t = ''; $p = [];
    $w = stale_where(['title', 'content'], $t, $p);
    $rows = db_all("SELECT title, content, image_url FROM about_sections WHERE 1=1 $w ORDER BY section_order ASC, id ASC LIMIT 6", $t, $p);
    $out = [];
    foreach ($rows as $r) {
        if (trim(strip_tags($r['content'])) === '') continue;
        $out[] = ['title' => $r['title'], 'content' => safe_html($r['content']), 'img' => find_img($r['image_url'], ['about_us', 'uploads/about_us'])];
    }
    return $out ?: [get_about()];
}

function get_certificates(): array {
    $out = [];
    foreach (db_all("SELECT title, image FROM certificates WHERE status = 1 ORDER BY cert_id DESC LIMIT 12") as $r) {
        $src = find_img($r['image'], ['certificates', 'certificate', 'uploads/certificates', 'uploads/certificate']);
        if ($src) $out[] = ['title' => $r['title'], 'src' => $src];
    }
    return $out;
}

function get_reviews(int $limit = 9): array {
    $t = ''; $p = [];
    $w = stale_where(['message', 'client_company', 'project_name'], $t, $p);
    $rows = db_all("SELECT name, designation, client_company, image, message, rating, project_name, project_date
                    FROM testimonials WHERE status = 1 $w ORDER BY featured DESC, display_order ASC, test_id DESC LIMIT " . (int)$limit, $t, $p);
    $out = [];
    foreach ($rows as $r) {
        $rt = (int)$r['rating'];
        $img = (stripos((string)$r['image'], 'person') === 0) ? '' : find_img($r['image'], ['testimonials', 'testimonial', 'uploads/testimonials', 'uploads/testimonial']);
        $out[] = [
            'name'   => $r['name'],
            'role'   => trim($r['designation'] . ($r['client_company'] ? ', ' . $r['client_company'] : ''), ', '),
            'img'    => $img,
            'text'   => trim($r['message']),
            'rating' => ($rt >= 1 && $rt <= 5) ? $rt : 5,
            'topic'  => $r['project_name'],
            'date'   => fmt_date($r['project_date']),
        ];
    }
    return $out;
}

function get_blogs(int $limit = 3): array {
    $t = ''; $p = [];
    $w = stale_where(['title', 'description', 'meta_title'], $t, $p);
    $rows = db_all("SELECT title, slug, author, image, SUBSTRING(description,1,2500) AS body, CHAR_LENGTH(description) AS len, created_at
                    FROM blogs WHERE status = 1 $w ORDER BY created_at DESC LIMIT " . (int)$limit, $t, $p);
    $out = [];
    foreach ($rows as $r) {
        $out[] = [
            'title'  => $r['title'],
            'text'   => excerpt($r['body'], 150),
            'img'    => find_img($r['image'], ['blogs', 'blog', 'uploads/blogs', 'uploads/blog']),
            'url'    => 'blog-details.php?slug=' . rawurlencode($r['slug']),
            'date'   => fmt_date($r['created_at']),
            'author' => $r['author'] ?: 'Admin',
            'mins'   => max(1, (int)round(((int)$r['len'] / 6) / 200)),
        ];
    }
    return $out;
}

function get_gallery(int $limit = 9): array {
    $out = [];
    foreach (db_all("SELECT image_name, image_path FROM gallery ORDER BY ID DESC LIMIT " . (int)$limit) as $r) {
        $src = find_img($r['image_path'], ['gallery', 'uploads/gallery']);
        if ($src) $out[] = ['src' => $src, 'alt' => $r['image_name'] ?: 'Bharat Trading gallery'];
    }
    if (!$out) {   // no gallery uploads yet: reuse product photos so the section is never empty
        foreach (get_products(12) as $pr) { if ($pr['img']) $out[] = ['src' => $pr['img'], 'alt' => $pr['name']]; }
    }
    return array_slice($out, 0, $limit);
}