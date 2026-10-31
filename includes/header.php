<?php

/**
 * includes/header.php
 * Optional variables a page can set BEFORE including this file:
 *   $page_title, $page_desc, $page_keys, $active_key (nav item to highlight), $has_inquiry, $extra_css, $extra_head, $canonical
 */
require_once __DIR__ . '/functions.php';

$contact     = $contact ?? get_contact();
$current     = basename((string)parse_url($_SERVER['SCRIPT_NAME'] ?? 'index.php', PHP_URL_PATH));
$on_home     = ($current === 'index.php');
$active_key  = $active_key ?? ($on_home ? 'home' : '');
$has_inquiry = $has_inquiry ?? $on_home;                    // does this page have an element with id="inquiry"?
$extra_css   = array_values(array_unique(array_merge(['pages.css'], $extra_css ?? [])));   // pages.css is used on every page
if (!isset($page_title)) {
    $m = page_meta($current);
    $page_title = $m['title'];
    $page_desc = $page_desc ?? $m['desc'];
    $page_keys = $page_keys ?? $m['keywords'];
}
$page_desc = $page_desc ?? '';
$page_keys = $page_keys ?? '';
$favicon     = get_logo('favicon');
$canonical   = $canonical ?? url($on_home ? '' : basename($current, '.php'));
$css_file    = '/assets/css/style.css';

// label, target, key. On the home page links to home sections become plain #anchors.
$nav_def = [['Home', '', 'home'], ['About Us', 'about', 'about'], ['Products', 'products', 'products'],
            ['Reviews', '#reviews', 'reviews'], ['Blog', 'blog', 'blog'], ['Gallery', '#gallery', 'gallery'], ['Contact', 'contact', 'contact']];
$nav = [];
foreach ($nav_def as $n) {
    $is_home_part = ($n[1] === '' || $n[1][0] === '#');
    $href = ($on_home && $is_home_part) ? ($n[1] ?: '#top') : url($n[1]);
    $nav[] = [$n[0], $href, $n[2]];
}

$cta_href = $has_inquiry ? '#inquiry' : url('contact') . '#inquiry';

$ld = array_filter([
    '@context'  => 'https://schema.org',
    '@type'     => 'Organization',
    'name'      => COMPANY['legal'],
    'url'       => url(),
    'logo'      => get_logo('header') ?: null,
    'email'     => $contact['email'],
    'telephone' => '+' . phone_intl($contact['phone']),
    'address'   => ['@type' => 'PostalAddress', 'streetAddress' => $contact['address']],
]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?></title>
    <meta name="description" content="<?= e($page_desc) ?>">
    <meta name="keywords" content="<?= e($page_keys) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= e($page_title) ?>">
    <meta property="og:description" content="<?= e($page_desc) ?>">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta name="theme-color" content="#14463a">
    <?php if ($favicon): ?>
    <link rel="icon" href="<?= e($favicon) ?>"><?php endif; ?>
    <script>
    document.documentElement.classList.add('js')
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="<?= e(url($css_file)) ?>?v=<?= (int)@filemtime(ROOT_PATH . $css_file) ?>">
    <?php foreach ($extra_css as $cf): ?>
    <link rel="stylesheet"
        href="<?= e(url('/assets/css/' . $cf)) ?>?v=<?= (int)@filemtime(ROOT_PATH . '/assets/css/' . $cf) ?>">
    <link rel="stylesheet" href="assets/css/page.css">
    <?php endforeach; ?>
    <?= $extra_head ?? '' ?>
    <script type="application/ld+json">
    <?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?>
    </script>
</head>

<body>
    <a class="skip" href="#main">Skip to content</a>

    <div class="topbar">
        <div class="container topbar-in">
            <ul class="tb-list">
                <li><a
                        href="mailto:<?= e($contact['email']) ?>"><?= icon('mail') ?><span><?= e($contact['email']) ?></span></a>
                </li>
                <li><a
                        href="<?= e($contact['tel']) ?>"><?= icon('phone') ?><span><?= e($contact['phone_txt']) ?></span></a>
                </li>
                <?php if ($contact['hours']): ?><li class="tb-hours">
                    <?= icon('clock') ?><span><?= e($contact['hours']) ?></span></li><?php endif; ?>
            </ul>
            <div class="tb-right">
                <span class="tb-note"><?= icon('award') ?>APEDA-registered merchant exporter</span>
                <?php foreach ($contact['social'] as $net => $link): ?>
                <a class="tb-soc" href="<?= e($link) ?>" target="_blank" rel="noopener"
                    aria-label="<?= e(ucfirst($net)) ?>"><?= icon($net) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <header class="site-header" id="siteHeader">
        <div class="container hd-in">
            <a class="brand" href="<?= e(url()) ?>"
                aria-label="<?= e(COMPANY['legal']) ?> home"><?= logo_html('header') ?></a>
            <nav class="nav" id="mainNav" aria-label="Primary">
                <ul>
                    <?php foreach ($nav as $n): ?>
                    <li><a href="<?= e($n[1]) ?>"
                            <?= $n[2] === $active_key ? ' class="active" aria-current="page"' : '' ?>><?= e($n[0]) ?></a>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a class="btn btn-gold nav-cta" href="<?= e($cta_href) ?>">Get a quote</a>
            </nav>
            <div class="hd-actions">
                <a class="btn btn-gold btn-sm hd-cta" href="<?= e($cta_href) ?>">Get a quote</a>
                <button class="burger" id="burger" type="button" aria-label="Open menu" aria-expanded="false"
                    aria-controls="mainNav"><span></span><span></span><span></span></button>
            </div>
        </div>
    </header>
    <div class="nav-scrim" id="navScrim"></div>