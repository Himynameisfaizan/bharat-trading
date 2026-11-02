<?php
/**
 * 404.php  -  "page not found".
 * .htaccess sends every address that is not a real file, folder or known route to this page.
 */
require_once __DIR__ . '/includes/functions.php';
http_response_code(404);                                   // search engines must see a real 404

$contact = get_contact();
$asked   = mb_substr((string)parse_url((string)($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH), 0, 90);

$page_title = 'Page not found (404) | ' . COMPANY['name'];
$page_desc  = 'The page you are looking for could not be found.';
$page_keys  = '';
$extra_head = '<meta name="robots" content="noindex,nofollow">';
$extra_css  = ['error.css'];
$active_key = '';

$suggest = array_slice(get_catalogue(), 0, 4);
$posts   = get_blogs(3);

require __DIR__ . '/includes/header.php';
?>
<main id="main">

<section class="nf">
  <div class="container nf-in">
    <div class="nf-art" aria-hidden="true">
      <span class="nf-arch"><b>404</b></span>
      <span class="nf-float nf-float--1"><?= icon('onion') ?></span>
      <span class="nf-float nf-float--2"><?= icon('rice') ?></span>
      <span class="nf-float nf-float--3"><?= icon('spice') ?></span>
    </div>

    <div class="nf-copy">
      <h1>This page could not be found</h1>
      <p class="nf-lead">The link may be old, the address may have a typo, or the page may have moved. Search our products or use one of the links below and you will be back on track.</p>
      <?php if ($asked !== ''): ?><p class="nf-asked">Address requested: <code><?= e($asked) ?></code></p><?php endif; ?>

      <form class="nf-search" action="<?= e(url('products')) ?>" method="get" role="search">
        <label class="visually-hidden" for="nfq">Search products</label>
        <input id="nfq" type="search" name="q" placeholder="Search products, for example rice or turmeric" autocomplete="off">
        <button type="submit" aria-label="Search"><?= icon('search') ?></button>
      </form>

      <div class="nf-actions">
        <a class="btn btn-gold" href="<?= e(url()) ?>"><?= icon('left') ?>Back to home</a>
        <a class="btn btn-ghost" href="<?= e(url('products')) ?>">Our products</a>
        <a class="btn btn-ghost" href="<?= e(url('contact')) ?>">Contact us</a>
      </div>

      <ul class="nf-links">
        <li><a href="<?= e(url('about')) ?>">About us</a></li>
        <li><a href="<?= e(url('blog')) ?>">Blog</a></li>
        <li><a href="<?= e(url('privacy-policy')) ?>">Privacy policy</a></li>
        <li><a href="<?= e(url('sitemap.xml')) ?>">Sitemap</a></li>
      </ul>
    </div>
  </div>
</section>

<?php if ($suggest): ?>
<section class="section section--tint">
  <div class="container">
    <header class="sec-head"><div><h2 class="sec-title">While you are here</h2><p class="sec-sub">A few of the products buyers ask us about most.</p></div><a class="link-arrow" href="<?= e(url('products')) ?>">All products<?= icon('arrow') ?></a></header>
    <div class="pgrid">
      <?php foreach ($suggest as $p): ?>
      <article class="pcard">
        <a class="pcard-media" href="<?= e(url($p['url'])) ?>" aria-label="<?= e($p['name']) ?>">
          <?php if ($p['img']): ?><img src="<?= e($p['img']) ?>" alt="<?= e($p['name']) ?>" loading="lazy"><?php else: ?><span class="ph ph-<?= e($p['group']) ?>"><?= icon($p['group'], 'ph-ico') ?></span><?php endif; ?>
          <span class="pcard-tag"><?= e($p['cat']) ?></span>
        </a>
        <div class="pcard-body">
          <h3><a href="<?= e(url($p['url'])) ?>"><?= e($p['name']) ?></a></h3>
          <?php if ($p['desc']): ?><p><?= e($p['desc']) ?></p><?php endif; ?>
          <div class="pcard-foot"><span class="price-note">Price on enquiry</span><a class="pcard-link" href="<?= e(url($p['url'])) ?>">View details<?= icon('arrow') ?></a></div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($posts): ?>
<section class="section">
  <div class="container">
    <header class="sec-head"><div><h2 class="sec-title">Worth a read</h2></div><a class="link-arrow" href="<?= e(url('blog')) ?>">All articles<?= icon('arrow') ?></a></header>
    <div class="bgrid">
      <?php foreach ($posts as $b): ?>
      <article class="bcard">
        <a class="bcard-media" href="<?= e(url($b['url'])) ?>" aria-label="<?= e($b['title']) ?>"><?php if ($b['img']): ?><img src="<?= e($b['img']) ?>" alt="<?= e($b['title']) ?>" loading="lazy"><?php else: ?><span class="ph ph-spice"><?= icon('spice', 'ph-ico') ?></span><?php endif; ?></a>
        <div class="bcard-body">
          <span class="bmeta"><?= e($b['date']) ?> <i></i> <?= (int)$b['mins'] ?> min read</span>
          <h3><a href="<?= e(url($b['url'])) ?>"><?= e($b['title']) ?></a></h3>
          <a class="link-arrow" href="<?= e(url($b['url'])) ?>">Read more<?= icon('arrow') ?></a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php
$cta_title = 'Cannot find what you need?';
$cta_text  = 'Tell us the product, quantity and destination and we will respond with a quotation.';
require __DIR__ . '/includes/cta-band.php';
?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
