<?php
require_once __DIR__ . '/includes/functions.php';

$contact = get_contact();
$all     = get_catalogue();
$cats    = get_categories();

/* ---------- filters from the URL ---------- */
$cat  = qs('category');
$q    = qs('q');
$sort = in_array(qs('sort'), ['az', 'za'], true) ? qs('sort') : 'new';
$page = max(1, (int)qs('page'));
$per  = 9;

$counts = [];
foreach ($all as $pr) { $counts[$pr['cat_slug']] = ($counts[$pr['cat_slug']] ?? 0) + 1; }

$cat_name = '';
foreach ($cats as $c) { if ($c['slug'] === $cat) $cat_name = $c['name']; }
if ($cat !== '' && $cat_name === '') { foreach ($all as $pr) { if ($pr['cat_slug'] === $cat) { $cat_name = $pr['cat']; break; } } }

$list = array_values(array_filter($all, function ($pr) use ($cat, $q) {
    if ($cat !== '' && $pr['cat_slug'] !== $cat) return false;
    if ($q !== '' && stripos($pr['name'] . ' ' . $pr['cat'] . ' ' . $pr['desc'], $q) === false) return false;
    return true;
}));
if ($sort === 'az') usort($list, function ($a, $b) { return strcasecmp($a['name'], $b['name']); });
if ($sort === 'za') usort($list, function ($a, $b) { return strcasecmp($b['name'], $a['name']); });

$total = count($list);
$pages = max(1, (int)ceil($total / $per));
$page  = min($page, $pages);
$items = array_slice($list, ($page - 1) * $per, $per);

function shop_url(array $over = []): string {
    $p = array_filter(array_merge(['category' => qs('category'), 'q' => qs('q'), 'sort' => qs('sort'), 'page' => ''], $over), function ($v) { return $v !== '' && $v !== null; });
    return url('products.php') . ($p ? '?' . http_build_query($p) : '');
}

/* ---------- page setup ---------- */
$m = page_meta('products.php', [
    'title'    => ($cat_name ? $cat_name . ' Exporter' : 'Our Products') . ' | Onions, Basmati Rice & Indian Spices | Bharat Trading',
    'desc'     => 'Browse fresh Indian onions, Basmati rice (1121 White Sella, Golden Sella), non-Basmati rice, turmeric, red chilli, cumin, coriander and black pepper from Bharat Trading, Nashik.',
    'keywords' => 'Indian onion exporter, Basmati rice exporter, 1121 White Sella rice, Golden Sella rice, non-Basmati rice supplier, turmeric exporter, red chilli exporter, cumin seeds supplier, coriander seeds exporter, black pepper supplier',
]);
$page_title = $m['title']; $page_desc = $m['desc']; $page_keys = $m['keywords'];
if ($cat !== '' || $q !== '' || $page > 1) { $extra_head = '<meta name="robots" content="noindex,follow">'; }
$canonical  = url('products.php') . ($cat !== '' && $q === '' && $page === 1 ? '?category=' . rawurlencode($cat) : '');
$active_key = 'products';
$extra_css  = ['pages.css'];
$extra_js   = ['pages.js'];

$banner_title = $cat_name ?: 'Our Products';
$banner_sub   = $cat_name ? 'Products in this category, sourced to your specification and packed as you require.' : 'Fresh Indian onions, rice and spices, sourced to your grade, size and packing requirement. Pricing is quoted per enquiry.';
$crumbs       = $cat_name ? [['Products', url('products.php')], [$cat_name, null]] : [['Products', null]];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-banner.php';
?>
<main id="main">
    <section class="section shop-sec">
        <div class="container shop">

            <!-- ---------- sidebar ---------- -->
            <aside class="side" aria-label="Product filters">
                <form class="side-box search" action="<?= e(url('products.php')) ?>" method="get" role="search">
                    <h2>Search</h2>
                    <?php if ($cat !== ''): ?><input type="hidden" name="category"
                        value="<?= e($cat) ?>"><?php endif; ?>
                    <div class="search-row">
                        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Search products"
                            aria-label="Search products">
                        <button type="submit" aria-label="Search"><?= icon('search') ?></button>
                    </div>
                </form>

                <nav class="side-box" aria-label="Categories">
                    <h2>Categories</h2>
                    <ul class="catlist">
                        <li><a href="<?= e(url('products.php')) ?>"
                                class="<?= $cat === '' ? 'is-active' : '' ?>"><span>All
                                    products</span><em><?= count($all) ?></em></a></li>
                        <?php foreach ($cats as $c): ?>
                        <li><a href="<?= e(url('products.php?category=' . rawurlencode($c['slug']))) ?>"
                                class="<?= $cat === $c['slug'] ? 'is-active' : '' ?>"><span><?= e($c['name']) ?></span><em><?= (int)($counts[$c['slug']] ?? 0) ?></em></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>

                <div class="side-box side-help">
                    <h2>Need something specific?</h2>
                    <p>We also source other Indian spices as per buyer requirements. Tell us what you need.</p>
                    <a class="btn btn-gold btn-sm btn-block" href="<?= e(url('contact.php')) ?>#inquiry">Send your
                        requirement</a>
                    <a class="side-phone"
                        href="<?= e($contact['tel']) ?>"><?= icon('phone') ?><?= e($contact['phone_txt']) ?></a>
                </div>
            </aside>

            <!-- ---------- results ---------- -->
            <div class="shop-main">
                <div class="toolbar">
                    <p class="toolbar-count"><?= $total ?>
                        <?= $total === 1 ? 'product' : 'products' ?><?= $cat_name ? ' in ' . e($cat_name) : '' ?><?= $q !== '' ? ' for &ldquo;' . e($q) . '&rdquo;' : '' ?>
                    </p>
                    <form method="get" action="<?= e(url('products.php')) ?>" class="sortform">
                        <?php if ($cat !== ''): ?><input type="hidden" name="category"
                            value="<?= e($cat) ?>"><?php endif; ?>
                        <?php if ($q !== ''): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
                        <label for="sort">Sort by</label>
                        <select name="sort" id="sort" data-autosubmit>
                            <option value="new" <?= $sort === 'new' ? ' selected' : '' ?>>Newest first</option>
                            <option value="az" <?= $sort === 'az' ? ' selected' : '' ?>>Name, A to Z</option>
                            <option value="za" <?= $sort === 'za' ? ' selected' : '' ?>>Name, Z to A</option>
                        </select>
                        <noscript><button class="btn btn-brand btn-sm" type="submit">Apply</button></noscript>
                    </form>
                </div>

                <?php if ($items): ?>
                <div class="pgrid pgrid--shop">
                    <?php foreach ($items as $p): ?>
                    <article class="pcard reveal">
                        <a class="pcard-media" href="<?= e(url($p['url'])) ?>" aria-label="<?= e($p['name']) ?>">
                            <?php if ($p['img']): ?><img src="<?= e($p['img']) ?>" alt="<?= e($p['name']) ?>"
                                loading="lazy"><?php else: ?><span
                                class="ph ph-<?= e($p['group']) ?>"><?= icon($p['group'], 'ph-ico') ?></span><?php endif; ?>
                            <span class="pcard-tag"><?= e($p['cat']) ?></span>
                        </a>
                        <div class="pcard-body">
                            <h3><a href="<?= e(url($p['url'])) ?>"><?= e($p['name']) ?></a></h3>
                            <?php if ($p['desc']): ?><p><?= e($p['desc']) ?></p><?php endif; ?>
                            <div class="pcard-foot">
                                <span class="price-note">Price on enquiry</span>
                                <a class="pcard-link" href="<?= e(url($p['url'])) ?>">View
                                    details<?= icon('arrow') ?></a>
                                <a class="btn btn-brand btn-sm" href="<?= e(url($p['url'])) ?>#inquiry">Get quote</a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($pages > 1): ?>
                <nav class="pager" aria-label="Pagination">
                    <?php if ($page > 1): ?><a href="<?= e(shop_url(['page' => $page - 1])) ?>" rel="prev"
                        aria-label="Previous page"><?= icon('left') ?></a><?php endif; ?>
                    <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <a href="<?= e(shop_url(['page' => $i > 1 ? $i : ''])) ?>"
                        class="<?= $i === $page ? 'is-active' : '' ?>"
                        <?= $i === $page ? ' aria-current="page"' : '' ?>><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $pages): ?><a href="<?= e(shop_url(['page' => $page + 1])) ?>" rel="next"
                        aria-label="Next page"><?= icon('arrow') ?></a><?php endif; ?>
                </nav>
                <?php endif; ?>

                <?php else: ?>
                <div class="empty">
                    <h3>No products matched your search</h3>
                    <p>Try a different word, or browse the full range. If you are looking for something that is not
                        listed, we may still be able to source it.</p>
                    <div class="empty-actions"><a class="btn btn-brand" href="<?= e(url('products.php')) ?>">View all
                            products</a><a class="btn btn-gold" href="<?= e(url('contact.php')) ?>#inquiry">Send your
                            requirement</a></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ============ TERMS AT A GLANCE ============ -->
    <section class="section section--tint">
        <div class="container terms-wrap">
            <header class="reveal">
                <h2 class="sec-title">How buying from us works</h2>
                <p class="sec-sub">Every product on this page is supplied on the same clear terms, so you know what to
                    expect before you write to us.</p>
                <a class="link-arrow" href="<?= e(url('about.php')) ?>">More about Bharat
                    Trading<?= icon('arrow') ?></a>
            </header>
            <dl class="terms reveal">
                <?php foreach (trade_terms() as $t): ?><div>
                    <dt><?= e($t[0]) ?></dt>
                    <dd><?= e($t[1]) ?></dd>
                </div><?php endforeach; ?>
            </dl>
        </div>
    </section>

    <?php
$cta_title = 'Cannot find the product you need?';
$cta_text  = 'We source other Indian spices and food products as per buyer requirements. Send us the details and we will tell you what is possible.';
require __DIR__ . '/includes/cta-band.php';
?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>