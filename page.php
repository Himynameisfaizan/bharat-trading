<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/custom-pages.php';

$contact    = get_contact();
$slug       = qs('slug');
$cp         = get_custom_page($slug);
$active_key = '';
$extra_css  = ['legal.css'];
$extra_js   = ['pages.js'];

/* ---------- page not found or switched off in the admin panel ---------- */
if (!$cp) {
    http_response_code(404);
    $page_title   = 'Page not found | Bharat Trading';
    $page_desc    = 'This page could not be found.';
    $page_keys    = '';
    $extra_head   = '<meta name="robots" content="noindex,follow">';
    $banner_title = 'Page not found';
    $banner_sub   = 'The page you are looking for is not available at this address.';
    $crumbs       = [['Pages', url('locations')], ['Not found', null]];
    require __DIR__ . '/includes/header.php';
    require __DIR__ . '/includes/page-banner.php';
    ?>
<main id="main">
    <section class="section">
        <div class="container">
            <div class="empty">
                <h2>Let us help you find it</h2>
                <p>The link may be old, or the page may have been removed. You can browse our other pages or write to
                    us.</p>
                <div class="empty-actions"><a class="btn btn-brand" href="<?= e(url('locations')) ?>">Browse pages</a><a
                        class="btn btn-gold" href="<?= e(url('contact')) ?>#inquiry">Contact us</a></div>
            </div>
        </div>
    </section>
</main>
<?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

/* ---------- page setup ---------- */
$canonical  = url('page/' . rawurlencode($cp['slug']));
$page_title = mb_strlen($cp['meta']['title']) >= 12 ? $cp['meta']['title'] : $cp['title'] . ' | ' . COMPANY['name'];
$page_desc  = mb_strlen($cp['meta']['desc'])  >= 30 ? $cp['meta']['desc']  : $cp['text'];
$page_keys  = $cp['meta']['keys'] !== '' ? $cp['meta']['keys'] : $cp['title'] . ', ' . COMPANY['name'];
$extra_head = $cp['schema'];                                   // JSON-LD typed in the admin panel (only if valid)

$banner_title = $cp['title'];
$banner_class = mb_strlen($cp['title']) > 42 ? 'pbanner-title--long' : '';
$banner_small = true;
$banner_extra = $cp['date'] !== '' ? '<span>' . icon('clock') . 'Published ' . e($cp['date']) . '</span>' : '';
$crumbs       = [['Locations', url('locations')], [$cp['crumb'], null]];

$others  = get_custom_pages(8, $cp['slug']);
$share_u = rawurlencode($canonical);
$share_t = rawurlencode($cp['title']);

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-banner.php';
?>
<main id="main">
    <div class="read-progress" id="readProgress" aria-hidden="true"></div>

    <section class="section art-sec">
        <div class="container art-layout">

            <article class="art-main">
                <?php if (trim(strip_tags($cp['html'], '<img>')) !== ''): ?>
                <div class="article" id="articleBody"><?= $cp['html'] ?></div>
                <?php else: ?>
                <div class="empty">
                    <h3>This page is being prepared</h3>
                    <p>Please check back soon, or contact us directly.</p>
                </div>
                <?php endif; ?>

                <footer class="art-end">
                    <div class="art-author"><span class="owner-av"><?= e(initials(COMPANY['owner'])) ?></span>
                        <div><b><?= e(COMPANY['legal']) ?></b><span>Merchant exporter, Nashik, India</span></div>
                    </div>
                    <div class="art-share">
                        <span>Share</span>
                        <a href="https://wa.me/?text=<?= $share_t ?>%20<?= $share_u ?>" target="_blank" rel="noopener"
                            aria-label="Share on WhatsApp"><?= icon('wa') ?></a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $share_u ?>" target="_blank"
                            rel="noopener" aria-label="Share on LinkedIn"><?= icon('linkedin') ?></a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_u ?>" target="_blank"
                            rel="noopener" aria-label="Share on Facebook"><?= icon('facebook') ?></a>
                        <a href="https://twitter.com/intent/tweet?url=<?= $share_u ?>&amp;text=<?= $share_t ?>"
                            target="_blank" rel="noopener" aria-label="Share on X"><?= icon('twitter') ?></a>
                    </div>
                </footer>
            </article>

            <aside class="art-aside">
                <?php if (count($cp['toc']) > 1): ?>
                <nav class="aside-card toc" aria-label="On this page">
                    <h3>On this page</h3>
                    <ol><?php foreach ($cp['toc'] as $t): ?><li><a href="#<?= e($t[0]) ?>"><?= e($t[1]) ?></a></li>
                        <?php endforeach; ?></ol>
                </nav>
                <?php endif; ?>

                <?php if ($others): ?>
                <nav class="aside-card legal-links" aria-label="More pages">
                    <h3>More pages</h3>
                    <ul>
                        <?php foreach ($others as $o): ?><li><a
                                href="<?= e(url($o['url'])) ?>"><span><?= e($o['title']) ?></span><?= icon('arrow') ?></a>
                        </li><?php endforeach; ?>
                        <li><a href="<?= e(url('locations')) ?>"><span><strong>View all
                                        pages</strong></span><?= icon('arrow') ?></a></li>
                    </ul>
                </nav>
                <?php endif; ?>

                <div class="aside-card aside-card--dark art-cta">
                    <h3>Need a quotation?</h3>
                    <p>Share your product, quantity and destination. We will respond with a quotation.</p>
                    <a class="btn btn-gold btn-block" href="<?= e(url('contact')) ?>#inquiry">Request a quote</a>
                    <a class="art-cta-phone"
                        href="<?= e($contact['tel']) ?>"><?= icon('phone') ?><?= e($contact['phone_txt']) ?></a>
                </div>
            </aside>

        </div>
    </section>

    <?php require __DIR__ . '/includes/cta-band.php'; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>