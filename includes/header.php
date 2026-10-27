<?php
/**
 * includes/header.php
 * Optional variables a page can set BEFORE including this file:
 *   $page_title, $page_desc, $page_keys, $nav_prefix (use url() on inner pages), $has_blog, $has_gallery
 */
require_once __DIR__ . '/functions.php';

$contact    = $contact ?? get_contact();
$meta       = page_meta();
$page_title = $page_title ?? $meta['title'];
$page_desc  = $page_desc  ?? $meta['desc'];
$page_keys  = $page_keys  ?? $meta['keywords'];
$nav_prefix = $nav_prefix ?? '';
$has_blog    = $has_blog    ?? true;
$has_gallery = $has_gallery ?? true;
$favicon    = get_logo('favicon');
$css_file   = '/assets/css/style.css';

$nav = [['Home', '#top'], ['About', '#about'], ['Products', '#products'], ['Reviews', '#reviews'], ['Blog', '#blog'], ['Gallery', '#gallery'], ['Contact', '#inquiry']];
$nav = array_values(array_filter($nav, function ($n) use ($has_blog, $has_gallery) {
    return !($n[1] === '#blog' && !$has_blog) && !($n[1] === '#gallery' && !$has_gallery);
}));

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
<link rel="canonical" href="<?= e(url()) ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($page_title) ?>">
<meta property="og:description" content="<?= e($page_desc) ?>">
<meta property="og:url" content="<?= e(url()) ?>">
<meta name="theme-color" content="#14463a">
<?php if ($favicon): ?><link rel="icon" href="<?= e($favicon) ?>"><?php endif; ?>
<script>document.documentElement.classList.add('js')</script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="<?= e(url($css_file)) ?>?v=<?= (int)@filemtime(ROOT_PATH . $css_file) ?>">
<script type="application/ld+json"><?= json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body>
<a class="skip" href="#main">Skip to content</a>

<div class="topbar">
  <div class="container topbar-in">
    <ul class="tb-list">
      <li><a href="mailto:<?= e($contact['email']) ?>"><?= icon('mail') ?><span><?= e($contact['email']) ?></span></a></li>
      <li><a href="<?= e($contact['tel']) ?>"><?= icon('phone') ?><span><?= e($contact['phone_txt']) ?></span></a></li>
      <?php if ($contact['hours']): ?><li class="tb-hours"><?= icon('clock') ?><span><?= e($contact['hours']) ?></span></li><?php endif; ?>
    </ul>
    <div class="tb-right">
      <span class="tb-note"><?= icon('award') ?>APEDA-registered merchant exporter</span>
      <?php foreach ($contact['social'] as $net => $link): ?>
        <a class="tb-soc" href="<?= e($link) ?>" target="_blank" rel="noopener" aria-label="<?= e(ucfirst($net)) ?>"><?= icon($net) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<header class="site-header" id="siteHeader">
  <div class="container hd-in">
    <a class="brand" href="<?= e(url()) ?>" aria-label="<?= e(COMPANY['legal']) ?> home"><?= logo_html('header') ?></a>
    <nav class="nav" id="mainNav" aria-label="Primary">
      <ul>
        <?php foreach ($nav as $n): ?>
          <li><a href="<?= e($nav_prefix . $n[1]) ?>"><?= e($n[0]) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <a class="btn btn-gold nav-cta" href="<?= e($nav_prefix) ?>#inquiry">Get a quote</a>
    </nav>
    <div class="hd-actions">
      <a class="btn btn-gold btn-sm hd-cta" href="<?= e($nav_prefix) ?>#inquiry">Get a quote</a>
      <button class="burger" id="burger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mainNav"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
<div class="nav-scrim" id="navScrim"></div>
