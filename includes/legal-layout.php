<?php
/**
 * includes/legal-layout.php  -  shared layout of the policy pages.
 *
 * A policy page sets these variables and then does:  require __DIR__ . '/includes/legal-layout.php';
 *   $page_title, $page_desc, $page_keys   SEO
 *   $legal_key        file name without .php, used to highlight the link in the sidebar
 *   $legal_title      page heading
 *   $legal_lead       banner sentence
 *   $legal_updated    "Last updated" text
 *   $legal_glance     array of short points shown in the "In short" box
 *   $legal_sections   array of [heading, html]
 */
$extra_css = ['legal.css'];
$extra_js  = ['pages.js'];
$active_key = '';
$legal_glance   = $legal_glance   ?? [];
$legal_sections = $legal_sections ?? [];

$banner_title = $legal_title;
$banner_sub   = $legal_lead ?? '';
$banner_small = true;
$crumbs       = [[$legal_title, null]];

require __DIR__ . '/header.php';
require __DIR__ . '/page-banner.php';
?>
<main id="main">
<div class="read-progress" id="readProgress" aria-hidden="true"></div>

<section class="section art-sec legal">
  <div class="container art-layout">

    <article class="art-main">
      <div class="legal-top">
        <span class="legal-chip"><?= icon('clock') ?>Last updated: <?= e($legal_updated) ?></span>
        <span class="legal-chip"><?= icon('shield') ?><?= e(COMPANY['legal']) ?>, Nashik, India</span>
      </div>

      <?php if ($legal_glance): ?>
      <div class="glance">
        <h2>In short</h2>
        <ul class="checklist"><?php foreach ($legal_glance as $g): ?><li><?= icon('check') ?><?= e($g) ?></li><?php endforeach; ?></ul>
      </div>
      <?php endif; ?>

      <div class="article" id="articleBody">
        <?php foreach ($legal_sections as $i => $s): ?>
          <h2 id="s<?= $i + 1 ?>"><?= e($s[0]) ?></h2>
          <?= $s[1] ?>
        <?php endforeach; ?>
      </div>
    </article>

    <aside class="art-aside">
      <nav class="aside-card toc" aria-label="On this page">
        <h3>On this page</h3>
        <ol><?php foreach ($legal_sections as $i => $s): ?><li><a href="#s<?= $i + 1 ?>"><?= e($s[0]) ?></a></li><?php endforeach; ?></ol>
      </nav>

      <nav class="aside-card legal-links" aria-label="Our policies">
        <h3>Our policies</h3>
        <ul>
          <?php foreach (legal_links() as $l): ?>
          <li><a href="<?= e(url($l[1])) ?>"<?= $l[1] === $legal_key ? ' class="is-active" aria-current="page"' : '' ?>><span><?= e($l[0]) ?></span><?= icon('arrow') ?></a></li>
          <?php endforeach; ?>
        </ul>
      </nav>

      <div class="aside-card aside-card--dark art-cta">
        <h3>Questions?</h3>
        <p>Write or call us and we will be glad to explain any point.</p>
        <a class="btn btn-gold btn-block" href="mailto:<?= e($contact['email']) ?>"><?= icon('mail') ?>Email us</a>
        <a class="art-cta-phone" href="<?= e($contact['tel']) ?>"><?= icon('phone') ?><?= e($contact['phone_txt']) ?></a>
      </div>
    </aside>

  </div>
</section>

<?php require __DIR__ . '/cta-band.php'; ?>
</main>
<?php require __DIR__ . '/footer.php'; ?>
