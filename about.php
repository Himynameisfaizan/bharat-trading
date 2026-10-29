<?php
require_once __DIR__ . '/includes/functions.php';

$contact   = get_contact();
$sections  = get_about_sections();
$certs     = get_certificates();
$catalogue = get_catalogue();

// group products by category for the "what we supply" cards
$groups = [];
foreach ($catalogue as $pr) {
    $k = $pr['cat_slug'];
    if (!isset($groups[$k])) $groups[$k] = ['name' => $pr['cat'], 'slug' => $k, 'group' => $pr['group'], 'items' => []];
    $groups[$k]['items'][] = $pr;
}

$m = page_meta('about.php', [
    'title'    => 'About Bharat Trading | Merchant Exporter of Indian Onions, Rice & Spices, Nashik',
    'desc'     => 'Bharat Trading is an APEDA-registered merchant exporter in Nashik, Maharashtra, supplying fresh Indian onions, Basmati and non-Basmati rice and Indian spices to buyers worldwide.',
    'keywords' => 'about Bharat Trading, merchant exporter Nashik, APEDA registered exporter, Indian onion exporter, Basmati rice exporter',
]);
$page_title = $m['title']; $page_desc = $m['desc']; $page_keys = $m['keywords'];
$active_key = 'about';
$extra_css  = ['pages.css'];
$extra_js   = ['pages.js'];

$banner_title = 'About Bharat Trading';
$banner_sub   = 'A Nashik-based merchant exporter supplying Indian onions, rice and spices to buyers who value clear specifications, customised packing and straightforward communication.';
$crumbs       = [['About Us', null]];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-banner.php';

$intro = $sections[0];
$more  = array_slice($sections, 1);
?>
<main id="main">

<!-- ============ INTRO ============ -->
<section class="section about">
  <div class="container about-grid">
    <div class="about-media reveal">
      <?php if ($intro['img']): ?>
        <img src="<?= e($intro['img']) ?>" alt="About <?= e(COMPANY['name']) ?>" loading="lazy">
      <?php else: ?>
        <div class="about-art"><span><?= icon('onion') ?></span><span><?= icon('rice') ?></span><span><?= icon('spice') ?></span></div>
      <?php endif; ?>
      <div class="about-badge"><b>Nashik</b><span>Maharashtra, India</span></div>
    </div>
    <div class="about-body reveal">
      <h2 class="sec-title"><?= e($intro['title']) ?></h2>
      <div class="prose"><?= $intro['content'] ?></div>
      <ul class="ticks">
        <li><?= icon('check') ?>Merchant exporter and supplier</li>
        <li><?= icon('check') ?>APEDA RCMC registered</li>
        <li><?= icon('check') ?>Customised packing</li>
        <li><?= icon('check') ?>Buyer-specific grades and sizes</li>
      </ul>
      <div class="about-foot">
        <div class="owner"><span class="owner-av"><?= e(initials(COMPANY['owner'])) ?></span><div><b><?= e(COMPANY['owner']) ?></b><span><?= e(COMPANY['role']) ?>, <?= e(COMPANY['name']) ?></span></div></div>
        <a class="btn btn-brand" href="<?= e(url('contact.php')) ?>#inquiry">Talk to us<?= icon('arrow') ?></a>
      </div>
    </div>
  </div>
</section>

<!-- ============ NUMBERS ============ -->
<section class="statband">
  <div class="container statband-in reveal">
    <div class="stat"><b><span class="count" data-to="<?= (int)COMPANY['capacity'] ?>">0</span></b><span>MT monthly supply capacity, as stated by the proprietor</span></div>
    <div class="stat"><b><span class="count" data-to="<?= count(COMPANY['markets']) ?>">0</span></b><span>export markets listed in our APEDA application</span></div>
    <div class="stat"><b><span class="count" data-to="<?= count($catalogue) ?>">0</span></b><span>product lines on offer</span></div>
    <div class="stat"><b><span class="count" data-to="4">0</span></b><span>registrations: APEDA RCMC, IEC, GST and Udyam</span></div>
  </div>
</section>

<?php foreach ($more as $k => $sec): ?>
<section class="section<?= $k % 2 === 0 ? ' section--tint' : '' ?>">
  <div class="container split<?= $k % 2 === 0 ? ' split--rev' : '' ?>">
    <div class="split-media reveal">
      <?php if ($sec['img']): ?><img src="<?= e($sec['img']) ?>" alt="<?= e($sec['title']) ?>" loading="lazy"><?php else: ?><span class="ph ph-<?= $k % 2 === 0 ? 'rice' : 'spice' ?>"><?= icon($k % 2 === 0 ? 'rice' : 'spice', 'ph-ico') ?></span><?php endif; ?>
    </div>
    <div class="split-body reveal">
      <h2 class="sec-title"><?= e($sec['title']) ?></h2>
      <div class="prose"><?= $sec['content'] ?></div>
    </div>
  </div>
</section>
<?php endforeach; ?>

<!-- ============ VALUES ============ -->
<section class="section section--tint">
  <div class="container">
    <header class="sec-head reveal">
      <div><h2 class="sec-title">How we work with buyers</h2><p class="sec-sub">We are a merchant exporter, which means our value lies in sourcing the right product, agreeing the specification clearly and coordinating the shipment. These are the principles we work to.</p></div>
    </header>
    <div class="vgrid">
      <?php foreach (company_values() as $v): ?>
      <article class="vcard reveal">
        <span class="vcard-ico"><?= icon($v[0]) ?></span>
        <h3><?= e($v[1]) ?></h3>
        <p><?= e($v[2]) ?></p>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ WHAT WE SUPPLY ============ -->
<section class="section">
  <div class="container">
    <header class="sec-head reveal">
      <div><h2 class="sec-title">What we supply</h2><p class="sec-sub">Three product families, each sourced to the grade, size and packing you specify. Other Indian spices are available as per buyer requirements.</p></div>
      <a class="link-arrow" href="<?= e(url('products.php')) ?>">See all products<?= icon('arrow') ?></a>
    </header>
    <div class="fam-grid">
      <?php foreach (array_slice($groups, 0, 3) as $g): ?>
      <article class="fam reveal">
        <span class="fam-ico"><?= icon($g['group'], 'fam-svg') ?></span>
        <h3><?= e($g['name']) ?></h3>
        <ul>
          <?php foreach (array_slice($g['items'], 0, 6) as $it): ?><li><a href="<?= e(url($it['url'])) ?>"><?= icon('check') ?><?= e($it['name']) ?></a></li><?php endforeach; ?>
        </ul>
        <a class="link-arrow" href="<?= e(url('products.php?category=' . rawurlencode($g['slug']))) ?>">View category<?= icon('arrow') ?></a>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ PROCESS ============ -->
<section class="section section--dark process">
  <div class="container">
    <header class="sec-head sec-head--light reveal">
      <div><h2 class="sec-title">How an order works</h2><p class="sec-sub">From first message to shipment, every step is agreed with you in advance so there are no surprises.</p></div>
    </header>
    <ol class="steps">
      <?php foreach (process_steps() as $i => $st): ?>
      <li class="step reveal"><span class="step-n"><?= sprintf('%02d', $i + 1) ?></span><h3><?= e($st[0]) ?></h3><p><?= e($st[1]) ?></p></li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<!-- ============ CREDENTIALS ============ -->
<section class="section">
  <div class="container">
    <header class="sec-head reveal">
      <div><h2 class="sec-title">Registrations and credentials</h2><p class="sec-sub">Our registration numbers are shown openly. You are welcome to verify them with the respective authorities before doing business with us.</p></div>
    </header>
    <div class="creds">
      <article class="cred reveal"><span class="cred-ico"><?= icon('award') ?></span><small>APEDA RCMC</small><b><?= e(COMPANY['rcmc']) ?></b><p>Registered with APEDA for scheduled products. Valid up to <?= e(COMPANY['rcmc_valid']) ?>.</p></article>
      <article class="cred reveal"><span class="cred-ico"><?= icon('globe') ?></span><small>Importer-Exporter Code</small><b><?= e(COMPANY['iec']) ?></b><p>IEC for import and export transactions.</p></article>
      <article class="cred reveal"><span class="cred-ico"><?= icon('shield') ?></span><small>GSTIN</small><b><?= e(COMPANY['gstin']) ?></b><p>Goods and Services Tax registration, Maharashtra.</p></article>
      <article class="cred reveal"><span class="cred-ico"><?= icon('box') ?></span><small>Udyam registration</small><b><?= e(COMPANY['udyam']) ?></b><p>Registered as an enterprise under Udyam.</p></article>
    </div>

    <?php if ($certs): ?>
    <h3 class="sub-title reveal">Certificates</h3>
    <div class="certs">
      <?php foreach ($certs as $c): ?>
      <a class="cert reveal" href="<?= e($c['src']) ?>" data-lightbox="<?= e($c['title']) ?>"><img src="<?= e($c['src']) ?>" alt="<?= e($c['title']) ?>" loading="lazy"><span><?= e($c['title']) ?></span></a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- ============ MARKETS + PROPRIETOR ============ -->
<section class="section section--tint">
  <div class="container duo">
    <div class="duo-card reveal">
      <h2 class="sec-title">Where we export</h2>
      <p class="prose">Qatar, Saudi Arabia and the United Arab Emirates are the destinations listed in our APEDA application. We handle enquiries from other markets too, so tell us your destination port and we will discuss what is possible.</p>
      <ul class="chips">
        <?php foreach (COMPANY['markets'] as $mk): ?><li><?= icon('globe') ?><?= e($mk) ?></li><?php endforeach; ?>
        <li class="chip--soft">Other destinations on request</li>
      </ul>
      <h3 class="sub-title">Who we supply</h3>
      <p class="prose">Importers, food distributors, wholesalers, retailers, food-service businesses and food-processing buyers.</p>
    </div>
    <div class="duo-card duo-card--dark reveal">
      <span class="owner-av owner-av--lg"><?= e(initials(COMPANY['owner'])) ?></span>
      <h2><?= e(COMPANY['owner']) ?></h2>
      <p class="duo-role"><?= e(COMPANY['role']) ?>, <?= e(COMPANY['legal']) ?></p>
      <p>Every enquiry reaches the proprietor directly. Write or call with your product, quantity and destination.</p>
      <ul class="duo-contact">
        <li><?= icon('phone') ?><a href="<?= e($contact['tel']) ?>"><?= e($contact['phone_txt']) ?></a></li>
        <li><?= icon('mail') ?><a href="mailto:<?= e($contact['email']) ?>"><?= e($contact['email']) ?></a></li>
        <li><?= icon('pin') ?><span><?= e($contact['address']) ?></span></li>
      </ul>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="section">
  <div class="container faq-wrap">
    <header class="reveal">
      <h2 class="sec-title">Questions buyers ask us</h2>
      <p class="sec-sub">Straight answers about what we do, how we price and how we work. If yours is not here, ask us.</p>
      <a class="btn btn-brand" href="<?= e(url('contact.php')) ?>#inquiry">Ask a question<?= icon('arrow') ?></a>
    </header>
    <div class="faq reveal">
      <?php foreach (company_faqs() as $f): ?>
      <details><summary><?= e($f[0]) ?></summary><p><?= e($f[1]) ?></p></details>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/includes/cta-band.php'; ?>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
