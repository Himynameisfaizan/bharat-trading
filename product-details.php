<?php
require_once __DIR__ . '/includes/functions.php';

$contact = get_contact();
$slug    = qs('slug');
$d       = get_product_detail($slug);

$active_key  = 'products';
$extra_css   = ['pages.css'];
$extra_js    = ['pages.js'];
$has_inquiry = true;

/* ---------- product not found ---------- */
if (!$d) {
    http_response_code(404);
    $page_title   = 'Product not found | Bharat Trading';
    $page_desc    = 'This product could not be found. Browse the full Bharat Trading range.';
    $page_keys    = '';
    $extra_head   = '<meta name="robots" content="noindex,follow">';
    $banner_title = 'Product not found';
    $banner_sub   = 'The product you are looking for is not available at this address.';
    $crumbs       = [['Products', url('products.php')], ['Not found', null]];
    require __DIR__ . '/includes/header.php';
    require __DIR__ . '/includes/page-banner.php';
    ?>
<main id="main">
    <section class="section">
        <div class="container">
            <div class="empty">
                <h2>Let us help you find it</h2>
                <p>The link may be old, or the product may have been renamed. Here is what we currently supply, or you
                    can tell us what you need.</p>
                <div class="empty-actions"><a class="btn btn-brand" href="<?= e(url('products.php')) ?>">View all
                        products</a><a class="btn btn-gold" href="<?= e(url('contact.php')) ?>#inquiry">Send your
                        requirement</a></div>
            </div>
            <div class="pgrid" style="margin-top:2rem">
                <?php foreach (array_slice(get_catalogue(), 0, 4) as $p): ?>
                <article class="pcard">
                    <a class="pcard-media" href="<?= e(url($p['url'])) ?>"><?php if ($p['img']): ?><img
                            src="<?= e($p['img']) ?>" alt="<?= e($p['name']) ?>" loading="lazy"><?php else: ?><span
                            class="ph ph-<?= e($p['group']) ?>"><?= icon($p['group'], 'ph-ico') ?></span><?php endif; ?></a>
                    <div class="pcard-body">
                        <h3><a href="<?= e(url($p['url'])) ?>"><?= e($p['name']) ?></a></h3>
                        <div class="pcard-foot"><a class="pcard-link" href="<?= e(url($p['url'])) ?>">View
                                details<?= icon('arrow') ?></a></div>
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
$name = $d['name'];
$page_title = $d['meta']['title'] ?: $name . ' Exporter and Supplier | Bharat Trading, Nashik';
$page_desc  = $d['meta']['desc']  ?: excerpt($d['summary'], 155);
$page_keys  = $d['meta']['keys']  ?: $name . ' exporter, ' . $name . ' supplier India, buy ' . $name . ' from India, Bharat Trading Nashik';
$canonical  = url('product-details.php?slug=' . rawurlencode($d['slug']));

$ld = ['@context' => 'https://schema.org', '@type' => 'Product', 'name' => $name, 'description' => $page_desc, 'category' => $d['cat'],
       'brand' => ['@type' => 'Brand', 'name' => COMPANY['legal']]];
if ($d['images']) $ld['image'] = $d['images'];
$extra_head = '<script type="application/ld+json">' . json_encode($ld, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) . '</script>';

$banner_title = $name;
$banner_small = true;
$crumbs = [['Products', url('products.php')], [$d['cat'], url('products.php?category=' . rawurlencode($d['cat_slug']))], [$name, null]];

$related = get_related($d['slug'], $d['cat_slug'], 4);
$wa_text = rawurlencode('Hello Bharat Trading, I would like a quotation for ' . $name . '.');
$uses = $d['uses'] ?: ['Importers and wholesalers', 'Food distributors and retailers', 'Food-service and food-processing businesses'];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-banner.php';
?>
<main id="main">

    <!-- ============ PRODUCT SUMMARY ============ -->
    <section class="section pd-sec">
        <div class="container pd">
            <div class="gal" id="gal">
                <?php if ($d['images']): ?>
                <a class="gal-main" id="galMain" href="<?= e($d['images'][0]) ?>" data-lightbox="<?= e($name) ?>"><img
                        src="<?= e($d['images'][0]) ?>" alt="<?= e($name) ?>" id="galImg"></a>
                <?php if (count($d['images']) > 1): ?>
                <div class="gal-thumbs">
                    <?php foreach ($d['images'] as $i => $im): ?><button type="button"
                        class="gal-thumb<?= $i === 0 ? ' is-active' : '' ?>" data-src="<?= e($im) ?>"
                        aria-label="Show image <?= $i + 1 ?>"><img src="<?= e($im) ?>" alt=""
                            loading="lazy"></button><?php endforeach; ?>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <div class="gal-main gal-main--ph"><span
                        class="ph ph-<?= e($d['group']) ?>"><?= icon($d['group'], 'ph-ico') ?></span></div>
                <?php endif; ?>
            </div>

            <div class="pd-info">
                <a class="pd-cat"
                    href="<?= e(url('products.php?category=' . rawurlencode($d['cat_slug']))) ?>"><?= e($d['cat']) ?></a>
                <p class="pd-summary"><?= e($d['summary']) ?></p>
                <dl class="facts">
                    <?php foreach (array_slice($d['facts'], 0, 7) as $f): ?><div>
                        <dt><?= e($f[0]) ?></dt>
                        <dd><?= e($f[1]) ?></dd>
                    </div><?php endforeach; ?>
                    <div>
                        <dt>Price</dt>
                        <dd>On enquiry</dd>
                    </div>
                </dl>
                <div class="pd-actions">
                    <a class="btn btn-gold" href="#inquiry" data-quote="<?= e($name) ?>">Request a
                        quote<?= icon('arrow') ?></a>
                    <a class="btn btn-outline" href="<?= e($contact['wa']) ?>?text=<?= $wa_text ?>" target="_blank"
                        rel="noopener"><?= icon('wa') ?>WhatsApp</a>
                    <a class="btn btn-outline" href="<?= e($contact['tel']) ?>"><?= icon('phone') ?>Call</a>
                </div>
                <ul class="pd-badges">
                    <li><?= icon('award') ?>APEDA registered exporter</li>
                    <li><?= icon('box') ?>Customised packing</li>
                    <li><?= icon('search') ?>Inspection as agreed</li>
                </ul>
            </div>
        </div>
    </section>

    <nav class="subnav" aria-label="On this page">
        <div class="container subnav-in">
            <a href="#overview">Overview</a><a href="#specification">Specification</a><a href="#terms">Terms and
                shipment</a><a href="#faq">FAQs</a><a href="#inquiry" class="subnav-cta">Enquire</a>
        </div>
    </nav>

    <!-- ============ OVERVIEW ============ -->
    <section class="section" id="overview">
        <div class="container pd-two">
            <div class="reveal">
                <h2 class="sec-title">About <?= e($name) ?></h2>
                <div class="prose prose--lg">
                    <?php if ($d['paras']): foreach ($d['paras'] as $para): ?><p><?= e($para) ?></p><?php endforeach;
        elseif ($d['overview_html'] !== ''): echo $d['overview_html'];
        else: ?><p><?= e($d['summary']) ?></p><?php endif; ?>
                </div>
            </div>
            <aside class="pd-side reveal">
                <div class="aside-card">
                    <h3>Suited for</h3>
                    <ul class="checklist"><?php foreach ($uses as $u): ?><li><?= icon('check') ?><?= e($u) ?></li>
                        <?php endforeach; ?></ul>
                </div>
                <div class="aside-card aside-card--tint">
                    <h3>Why buy through Bharat Trading</h3>
                    <ul class="checklist">
                        <li><?= icon('check') ?>Specification agreed before you order</li>
                        <li><?= icon('check') ?>Packing customised to your market</li>
                        <li><?= icon('check') ?>Inspection and shipment coordinated</li>
                        <li><?= icon('check') ?>Registration numbers shown openly</li>
                    </ul>
                </div>
            </aside>
        </div>
    </section>

    <!-- ============ SPECIFICATION ============ -->
    <section class="section section--tint" id="specification">
        <div class="container">
            <header class="sec-head reveal">
                <div>
                    <h2 class="sec-title">Specification</h2>
                    <p class="sec-sub">Exact parameters are agreed with you for each order. This is what we confirm with
                        every buyer.</p>
                </div>
            </header>
            <div class="spec-grid">
                <table class="spec-table reveal">
                    <tbody>
                        <?php foreach ($d['facts'] as $f): ?><tr>
                            <th scope="row"><?= e($f[0]) ?></th>
                            <td><?= e($f[1]) ?></td>
                        </tr><?php endforeach; ?>
                    </tbody>
                </table>
                <?php if ($d['spec_html'] !== ''): ?><div class="prose spec-html reveal"><?= $d['spec_html'] ?></div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ============ TERMS AND SHIPMENT ============ -->
    <section class="section" id="terms">
        <div class="container">
            <header class="sec-head reveal">
                <div>
                    <h2 class="sec-title">Terms and shipment</h2>
                    <p class="sec-sub">The same clear terms apply to every product we supply.</p>
                </div>
            </header>
            <div class="terms-grid">
                <dl class="terms terms--stack reveal">
                    <?php foreach (trade_terms() as $t): ?><div>
                        <dt><?= e($t[0]) ?></dt>
                        <dd><?= e($t[1]) ?></dd>
                    </div><?php endforeach; ?>
                </dl>
                <div class="aside-card reveal">
                    <h3>What to include in your enquiry</h3>
                    <ul class="checklist"><?php foreach (enquiry_checklist() as $c): ?><li>
                            <?= icon('check') ?><?= e($c) ?></li><?php endforeach; ?></ul>
                </div>
            </div>
            <ol class="mini-steps reveal">
                <?php foreach (array_slice(process_steps(), 0, 6) as $i => $st): ?><li>
                    <b><?= $i + 1 ?></b><span><?= e($st[0]) ?></span></li><?php endforeach; ?>
            </ol>
        </div>
    </section>

    <!-- ============ FAQ ============ -->
    <section class="section section--tint" id="faq">
        <div class="container faq-wrap">
            <header class="reveal">
                <h2 class="sec-title">Frequently asked questions</h2>
                <p class="sec-sub">Quick answers about ordering <?= e($name) ?> from us.</p>
            </header>
            <div class="faq reveal"><?php foreach ($d['faqs'] as $f): ?><details>
                    <summary><?= e($f[0]) ?></summary>
                    <p><?= e($f[1]) ?></p>
                </details><?php endforeach; ?></div>
        </div>
    </section>

    <!-- ============ ENQUIRY ============ -->
    <section class="section section--dark inq-sec" id="inquiry">
        <div class="container pd-inq">
            <div class="pd-inq-copy reveal">
                <h2 class="sec-title">Request a quotation for <?= e($name) ?></h2>
                <p>Share your requirement and we will come back with a quotation based on your specification, quantity,
                    packing and destination.</p>
                <ul class="checklist checklist--light"><?php foreach (enquiry_checklist() as $c): ?><li>
                        <?= icon('check') ?><?= e($c) ?></li><?php endforeach; ?></ul>
                <div class="pd-inq-contact">
                    <a href="<?= e($contact['tel']) ?>"><?= icon('phone') ?><?= e($contact['phone_txt']) ?></a>
                    <a href="mailto:<?= e($contact['email']) ?>"><?= icon('mail') ?><?= e($contact['email']) ?></a>
                </div>
            </div>
            <div class="reveal"><?php $form_product = $name; require __DIR__ . '/includes/enquiry-form.php'; ?></div>
        </div>
    </section>

    <!-- ============ RELATED ============ -->
    <?php if ($related): ?>
    <section class="section">
        <div class="container">
            <header class="sec-head reveal">
                <div>
                    <h2 class="sec-title">You may also need</h2>
                </div><a class="link-arrow" href="<?= e(url('products.php')) ?>">All products<?= icon('arrow') ?></a>
            </header>
            <div class="pgrid">
                <?php foreach ($related as $p): ?>
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
                        <div class="pcard-foot"><span class="price-note">Price on enquiry</span><a class="pcard-link"
                                href="<?= e(url($p['url'])) ?>">View details<?= icon('arrow') ?></a></div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>