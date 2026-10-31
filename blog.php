<?php
require_once __DIR__ . '/includes/functions.php';

$contact = get_contact();
$q    = qs('q');
$page = max(1, (int)qs('page'));
$per  = 9;
$lead = ($q === '');                                    // the newest article is featured unless the visitor is searching

$total      = count_blogs($q);
$list_total = $lead ? max(0, $total - 1) : $total;
$pages      = max(1, (int)ceil($list_total / $per));
$page       = min($page, $pages);

$feature = ($lead && $page === 1) ? (get_blogs(1, 0)[0] ?? null) : null;
$offset  = ($lead ? 1 : 0) + ($page - 1) * $per;
$posts   = get_blogs($per, $offset, $q);

function blog_list_url(array $over = []): string
{
  $p = array_filter(array_merge(['q' => qs('q'), 'page' => ''], $over), function ($v) {
    return $v !== '' && $v !== null;
  });
  return url('blog') . ($p ? '?' . http_build_query($p) : '');
}

$m = page_meta('blog.php', [
  'title'    => 'Blog | Sourcing, Grading and Export Guides for Indian Commodities | Bharat Trading',
  'desc'     => 'Practical guides for importers and wholesalers on sourcing, grading, packing and shipping Indian agricultural commodities and spices.',
  'keywords' => 'Indian spices export guide, buying Indian commodities, export packing, bulk spices sourcing, Bharat Trading blog',
]);
$page_title = $m['title'];
$page_desc = $m['desc'];
$page_keys = $m['keywords'];
if ($q !== '' || $page > 1) {
  $extra_head = '<meta name="robots" content="noindex,follow">';
}
$canonical  = url('blog');
$active_key = 'blog';
$extra_js   = ['pages.js'];

$banner_title = $q !== '' ? 'Search results' : 'Our Blog';
$banner_sub   = $q !== '' ? $total . ' ' . ($total === 1 ? 'article' : 'articles') . ' found for "' . $q . '"' : 'Guides and notes for importers, wholesalers and food businesses on sourcing, grading, packing and shipping.';
$crumbs       = [['Blog', null]];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-banner.php';
?>
<main id="main">
  <section class="section blog-sec">
    <div class="container">

      <div class="blog-tools reveal">
        <p><?= $total ?> <?= $total === 1 ? 'article' : 'articles' ?><?= $q !== '' ? ' matching your search' : '' ?></p>
        <form action="<?= e(url('blog')) ?>" method="get" role="search" class="blog-search">
          <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search articles" aria-label="Search articles">
          <button type="submit" aria-label="Search"><?= icon('search') ?></button>
        </form>
      </div>

      <?php if ($feature): ?>
        <a class="bfeat reveal" href="<?= e(url($feature['url'])) ?>">
          <span class="bfeat-media"><?php if ($feature['img']): ?><img src="<?= e($feature['img']) ?>" alt="<?= e($feature['title']) ?>"><?php else: ?><span class="ph ph-spice"><?= icon('spice', 'ph-ico') ?></span><?php endif; ?></span>
          <span class="bfeat-body">
            <span class="bfeat-tag">Latest article</span>
            <span class="bmeta"><?= icon('clock') ?><?= e($feature['date']) ?>, <?= (int)$feature['mins'] ?> min read</span>
            <h2><?= e($feature['title']) ?></h2>
            <span class="bfeat-text"><?= e($feature['text']) ?></span>
            <span class="btn btn-brand">Read the article<?= icon('arrow') ?></span>
          </span>
        </a>
      <?php endif; ?>

      <?php if ($posts): ?>
        <div class="bgrid">
          <?php foreach ($posts as $b): ?>
            <article class="bcard reveal">
              <a class="bcard-media" href="<?= e(url($b['url'])) ?>" aria-label="<?= e($b['title']) ?>">
                <?php if ($b['img']): ?><img src="<?= e($b['img']) ?>" alt="<?= e($b['title']) ?>" loading="lazy"><?php else: ?><span class="ph ph-spice"><?= icon('spice', 'ph-ico') ?></span><?php endif; ?>
              </a>
              <div class="bcard-body">
                <span class="bmeta"><?= e($b['date']) ?> <i></i> <?= (int)$b['mins'] ?> min read</span>
                <h3><a href="<?= e(url($b['url'])) ?>"><?= e($b['title']) ?></a></h3>
                <p><?= e($b['text']) ?></p>
                <a class="link-arrow" href="<?= e(url($b['url'])) ?>">Read more<?= icon('arrow') ?></a>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <?php if ($pages > 1): ?>
          <nav class="pager" aria-label="Pagination">
            <?php if ($page > 1): ?><a href="<?= e(blog_list_url(['page' => $page > 2 ? $page - 1 : ''])) ?>" rel="prev" aria-label="Previous page"><?= icon('left') ?></a><?php endif; ?>
            <?php for ($i = 1; $i <= $pages; $i++): ?>
              <a href="<?= e(blog_list_url(['page' => $i > 1 ? $i : ''])) ?>" class="<?= $i === $page ? 'is-active' : '' ?>" <?= $i === $page ? ' aria-current="page"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $pages): ?><a href="<?= e(blog_list_url(['page' => $page + 1])) ?>" rel="next" aria-label="Next page"><?= icon('arrow') ?></a><?php endif; ?>
          </nav>
        <?php endif; ?>

      <?php elseif (!$feature): ?>
        <div class="empty">
          <h3><?= $q !== '' ? 'No articles matched your search' : 'Articles are on the way' ?></h3>
          <p><?= $q !== '' ? 'Try a different word, or browse all articles.' : 'We are preparing guides on sourcing, grading and packing. Meanwhile, you are welcome to ask us anything directly.' ?></p>
          <div class="empty-actions"><?php if ($q !== ''): ?><a class="btn btn-brand" href="<?= e(url('blog')) ?>">All articles</a><?php endif; ?><a class="btn btn-gold" href="<?= e(url('contact')) ?>#inquiry">Ask a question</a></div>
        </div>
      <?php endif; ?>

    </div>
  </section>

  <?php
  $cta_title = 'Looking to source Indian commodities?';
  $cta_text  = 'Tell us the product, quantity and destination and we will respond with a quotation based on your specification.';
  require __DIR__ . '/includes/cta-band.php';
  ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>