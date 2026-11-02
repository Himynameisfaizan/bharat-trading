<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/custom-pages.php';

$contact = get_contact();
$pages   = get_custom_pages(300);                              // every published custom page from the admin panel

$m = page_meta('location.php', [
    'title'    => 'Locations & Information Pages | Bharat Trading, Nashik',
    'desc'     => 'Browse the location and information pages published by Bharat Trading, merchant exporter of Indian onions, rice and spices.',
    'keywords' => 'Bharat Trading locations, export destinations, Indian onion exporter, Basmati rice exporter, spices exporter',
]);
$page_title = $m['title']; $page_desc = $m['desc']; $page_keys = $m['keywords'];
$canonical  = url('locations');
$active_key = '';
$extra_css  = ['legal.css'];

$banner_title = 'Locations & Information';
$banner_sub   = 'Pages about the markets, locations and services of Bharat Trading, written for importers, distributors and food businesses.';
$banner_small = true;
$crumbs       = [['Locations', null]];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-banner.php';
?>
<main id="main">
    <section class="section loc-sec">
        <div class="container">

            <?php if ($pages): ?>
            <div class="loc-tools reveal">
                <p><span id="locCount"><?= count($pages) ?></span> <?= count($pages) === 1 ? 'page' : 'pages' ?></p>
                <label class="loc-search"><span class="visually-hidden">Search pages</span><?= icon('search') ?><input
                        type="search" id="locSearch" placeholder="Search pages" autocomplete="off"></label>
            </div>

            <div class="loc-grid" id="locGrid">
                <?php foreach ($pages as $pg): ?>
                <article class="loc-card reveal" data-text="<?= e(mb_strtolower($pg['title'] . ' ' . $pg['desc'])) ?>">
                    <span class="loc-ico"><?= icon('pin') ?></span>
                    <h2><a href="<?= e(url($pg['url'])) ?>"><?= e($pg['title']) ?></a></h2>
                    <p><?= e($pg['desc']) ?></p>
                    <a class="link-arrow" href="<?= e(url($pg['url'])) ?>">View page<?= icon('arrow') ?></a>
                </article>
                <?php endforeach; ?>
            </div>
            <p class="loc-none" id="locNone" hidden>No page matched your search.</p>

            <?php else: ?>
            <div class="empty">
                <h2>Pages are on the way</h2>
                <p>We are preparing location and information pages. In the meantime, you can browse our products or
                    write to us with your requirement.</p>
                <div class="empty-actions"><a class="btn btn-brand" href="<?= e(url('products')) ?>">View products</a><a
                        class="btn btn-gold" href="<?= e(url('contact')) ?>#inquiry">Contact us</a></div>
            </div>
            <?php endif; ?>

        </div>
    </section>

    <?php require __DIR__ . '/includes/cta-band.php'; ?>
</main>

<?php if ($pages): ?>
<script>
(function() {
    var box = document.getElementById('locSearch'),
        cards = document.querySelectorAll('.loc-card'),
        count = document.getElementById('locCount'),
        none = document.getElementById('locNone');
    if (!box) return;
    box.addEventListener('input', function() {
        var q = box.value.trim().toLowerCase(),
            n = 0;
        cards.forEach(function(c) {
            var hit = q === '' || c.dataset.text.indexOf(q) !== -1;
            c.hidden = !hit;
            if (hit) {
                n++;
                c.classList.add('in');
            }
        });
        count.textContent = n;
        none.hidden = n !== 0;
    });
})();
</script>
<?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>