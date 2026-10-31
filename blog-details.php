<?php
require_once __DIR__ . '/includes/functions.php';

$contact    = get_contact();
$slug       = qs('slug');
$b          = get_blog($slug);
$active_key = 'blog';
$extra_js   = ['pages.js'];

/* ---------- article not found ---------- */
if (!$b) {
  http_response_code(404);
  $page_title   = 'Article not found | Bharat Trading';
  $page_desc    = 'This article could not be found. Browse the latest Bharat Trading articles.';
  $page_keys    = '';
  $extra_head   = '<meta name="robots" content="noindex,follow">';
  $banner_title = 'Article not found';
  $banner_sub   = 'The article you are looking for is not available at this address.';
  $crumbs       = [['Blog', url('blog')], ['Not found', null]];
  require __DIR__ . '/includes/header.php';
  require __DIR__ . '/includes/page-banner.php';
?>
  <main id="main">
    <section class="section">
      <div class="container">
        <div class="empty">
          <h2>Let us help you find it</h2>
          <p>The link may be old or the article may have been renamed. Here are our latest articles.</p>
          <div class="empty-actions"><a class="btn btn-brand" href="<?= e(url('blog')) ?>">All articles</a><a class="btn btn-gold" href="<?= e(url('contact')) ?>#inquiry">Contact us</a></div>
        </div>
        <div class="bgrid" style="margin-top:2rem">
          <?php foreach (get_blogs(3) as $r): ?>
            <article class="bcard">
              <a class="bcard-media" href="<?= e(url($r['url'])) ?>"><?php if ($r['img']): ?><img src="<?= e($r['img']) ?>" alt="<?= e($r['title']) ?>" loading="lazy"><?php else: ?><span class="ph ph-spice"><?= icon('spice', 'ph-ico') ?></span><?php endif; ?></a>
              <div class="bcard-body">
                <h3><a href="<?= e(url($r['url'])) ?>"><?= e($r['title']) ?></a></h3><a class="link-arrow" href="<?= e(url($r['url'])) ?>">Read more<?= icon('arrow') ?></a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>
<?php
  require __DIR__ . '/includes/footer.php';
  exit;
}

/* ---------- page setup ---------- */
$canonical  = url('blog/' . rawurlencode($b['slug']));
$page_title = $b['meta']['title'] ?: $b['title'] . ' | Bharat Trading';
$page_desc  = $b['meta']['desc']  ?: excerpt($b['html'], 155);
$page_keys  = $b['meta']['keys']  ?: 'Bharat Trading, Indian commodities export, sourcing guide';

// Article schema is generated here (not copied from the database) so it always names this website
$ld = array_filter([
  '@context' => 'https://schema.org',
  '@type' => 'Article',
  'headline' => $b['title'],
  'description' => $page_desc,
  'datePublished' => $b['iso'],
  'mainEntityOfPage' => $canonical,
  'image' => $b['img'] ?: null,
  'author' => ['@type' => 'Person', 'name' => $b['author']],
  'publisher' => ['@type' => 'Organization', 'name' => COMPANY['legal'], 'url' => url()]
]);
$extra_head = ($b['img'] ? '<meta property="og:image" content="' . e($b['img']) . '">' . "\n" : '')
  . '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . '</script>';

$banner_title = $b['title'];
$banner_class = 'pbanner-title--long';
$banner_small = true;
$banner_extra = '<span>' . icon('check') . e($b['author']) . '</span><span>' . icon('clock') . e($b['date']) . '</span><span>' . icon('search') . (int)$b['mins'] . ' min read</span>';
$crumbs       = [['Blog', url('blog')], [excerpt($b['title'], 38), null]];

$related = get_blogs(3, 0, '', $b['slug']);
$share_u = rawurlencode($canonical);
$share_t = rawurlencode($b['title']);

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-banner.php';
?>
<main id="main">
  <div class="read-progress" id="readProgress" aria-hidden="true"></div>

  <section class="section art-sec">
    <div class="container art-layout">

      <article class="art-main">
        <?php if ($b['img']): ?><figure class="art-cover"><img src="<?= e($b['img']) ?>" alt="<?= e($b['title']) ?>"></figure><?php endif; ?>
        <div class="article" id="articleBody"><?= $b['html'] ?></div>

        <footer class="art-end">
          <div class="art-author"><span class="owner-av"><?= e(initials($b['author'])) ?></span>
            <div><b><?= e($b['author']) ?></b><span>Published <?= e($b['date']) ?> on the <?= e(COMPANY['name']) ?> blog</span></div>
          </div>
          <div class="art-share">
            <span>Share</span>
            <a href="https://wa.me/?text=<?= $share_t ?>%20<?= $share_u ?>" target="_blank" rel="noopener" aria-label="Share on WhatsApp"><?= icon('wa') ?></a>
            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $share_u ?>" target="_blank" rel="noopener" aria-label="Share on LinkedIn"><?= icon('linkedin') ?></a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_u ?>" target="_blank" rel="noopener" aria-label="Share on Facebook"><?= icon('facebook') ?></a>
            <a href="https://twitter.com/intent/tweet?url=<?= $share_u ?>&amp;text=<?= $share_t ?>" target="_blank" rel="noopener" aria-label="Share on X"><?= icon('twitter') ?></a>
          </div>
        </footer>
      </article>

      <aside class="art-aside">
        <?php if (count($b['toc']) > 1): ?>
          <nav class="aside-card toc" aria-label="In this article">
            <h3>In this article</h3>
            <ol><?php foreach ($b['toc'] as $t): ?><li><a href="#<?= e($t[0]) ?>"><?= e($t[1]) ?></a></li><?php endforeach; ?></ol>
          </nav>
        <?php endif; ?>

        <div class="aside-card aside-card--dark art-cta">
          <h3>Need to source this?</h3>
          <p>Share your product, quantity and destination. We will respond with a quotation.</p>
          <a class="btn btn-gold btn-block" href="<?= e(url('contact')) ?>#inquiry">Request a quote</a>
          <a class="art-cta-phone" href="<?= e($contact['tel']) ?>"><?= icon('phone') ?><?= e($contact['phone_txt']) ?></a>
        </div>
      </aside>
    </div>
  </section>

  <?php if ($related): ?>
    <section class="section section--tint">
      <div class="container">
        <header class="sec-head reveal">
          <div>
            <h2 class="sec-title">Keep reading</h2>
          </div><a class="link-arrow" href="<?= e(url('blog')) ?>">All articles<?= icon('arrow') ?></a>
        </header>
        <div class="bgrid">
          <?php foreach ($related as $r): ?>
            <article class="bcard reveal">
              <a class="bcard-media" href="<?= e(url($r['url'])) ?>" aria-label="<?= e($r['title']) ?>"><?php if ($r['img']): ?><img src="<?= e($r['img']) ?>" alt="<?= e($r['title']) ?>" loading="lazy"><?php else: ?><span class="ph ph-spice"><?= icon('spice', 'ph-ico') ?></span><?php endif; ?></a>
              <div class="bcard-body">
                <span class="bmeta"><?= e($r['date']) ?> <i></i> <?= (int)$r['mins'] ?> min read</span>
                <h3><a href="<?= e(url($r['url'])) ?>"><?= e($r['title']) ?></a></h3>
                <p><?= e($r['text']) ?></p>
                <a class="link-arrow" href="<?= e(url($r['url'])) ?>">Read more<?= icon('arrow') ?></a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <?php require __DIR__ . '/includes/cta-band.php'; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>