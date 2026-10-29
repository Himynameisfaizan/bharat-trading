<?php
require_once __DIR__ . '/includes/functions.php';

$contact = get_contact();
$maps_dir = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($contact['address']);

$m = page_meta('contact.php', [
    'title'    => 'Contact Bharat Trading | Request a Quote for Onions, Rice & Spices, Nashik',
    'desc'     => 'Contact Bharat Trading in Nashik, Maharashtra. Send your requirement for Indian onions, Basmati rice or spices and receive a quotation based on your specification.',
    'keywords' => 'contact Bharat Trading, Indian onion exporter Nashik, request quote Basmati rice, spices exporter enquiry',
]);
$page_title = $m['title']; $page_desc = $m['desc']; $page_keys = $m['keywords'];
$active_key  = 'contact';
$has_inquiry = true;
$extra_css   = ['pages.css'];
$extra_js    = ['pages.js'];

$banner_title = 'Contact Us';
$banner_sub   = 'Tell us what you need and where it is going. We will respond with a quotation based on your specification, quantity, packing and destination.';
$crumbs       = [['Contact Us', null]];

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/page-banner.php';
?>
<main id="main">

<!-- ============ CONTACT CARDS ============ -->
<section class="section cinfo-sec">
  <div class="container cinfo">
    <article class="cinfo-card reveal">
      <span class="cinfo-ico"><?= icon('pin') ?></span>
      <h2>Visit our office</h2>
      <p><?= e($contact['address']) ?></p>
      <a class="link-arrow" href="<?= e($maps_dir) ?>" target="_blank" rel="noopener">Get directions<?= icon('arrow') ?></a>
    </article>
    <article class="cinfo-card reveal">
      <span class="cinfo-ico"><?= icon('phone') ?></span>
      <h2>Call us</h2>
      <p><a href="<?= e($contact['tel']) ?>"><?= e($contact['phone_txt']) ?></a><?php if ($contact['hours']): ?><br><small><?= e($contact['hours']) ?></small><?php endif; ?></p>
      <a class="link-arrow" href="<?= e($contact['tel']) ?>">Call now<?= icon('arrow') ?></a>
    </article>
    <article class="cinfo-card reveal">
      <span class="cinfo-ico"><?= icon('mail') ?></span>
      <h2>Email us</h2>
      <p><a href="mailto:<?= e($contact['email']) ?>"><?= e($contact['email']) ?></a></p>
      <a class="link-arrow" href="mailto:<?= e($contact['email']) ?>">Write an email<?= icon('arrow') ?></a>
    </article>
    <article class="cinfo-card reveal">
      <span class="cinfo-ico"><?= icon('wa') ?></span>
      <h2>WhatsApp</h2>
      <p>Message us your requirement and attach any specification you already have.</p>
      <a class="link-arrow" href="<?= e($contact['wa']) ?>?text=<?= rawurlencode('Hello Bharat Trading, I would like to discuss a requirement.') ?>" target="_blank" rel="noopener">Start a chat<?= icon('arrow') ?></a>
    </article>
  </div>
</section>

<!-- ============ FORM + NEXT STEPS ============ -->
<section class="section section--tint" id="inquiry">
  <div class="container contact-grid">
    <div class="contact-form reveal">
      <h2 class="sec-title">Request a quotation</h2>
      <p class="sec-sub">A name, an email and a short description of your requirement are enough to start. The more detail you share, the more precise our quotation will be.</p>
      <?php $form_product = ''; require __DIR__ . '/includes/enquiry-form.php'; ?>
    </div>

    <aside class="contact-aside reveal">
      <div class="aside-card">
        <h3>What happens after you write</h3>
        <ol class="next-steps">
          <li><b>1</b><div><strong>We read your requirement</strong><span>Your message goes straight to the proprietor.</span></div></li>
          <li><b>2</b><div><strong>We confirm the specification</strong><span>Grade, size, packing and destination are clarified with you.</span></div></li>
          <li><b>3</b><div><strong>You receive a quotation</strong><span>Priced on your specification and the agreed FOB or CIF terms.</span></div></li>
        </ol>
      </div>
      <div class="aside-card aside-card--tint">
        <h3>Helpful to include</h3>
        <ul class="checklist"><?php foreach (enquiry_checklist() as $c): ?><li><?= icon('check') ?><?= e($c) ?></li><?php endforeach; ?></ul>
      </div>
      <div class="aside-card aside-card--dark">
        <span class="owner-av"><?= e(initials(COMPANY['owner'])) ?></span>
        <h3><?= e(COMPANY['owner']) ?></h3>
        <p><?= e(COMPANY['role']) ?>, <?= e(COMPANY['legal']) ?></p>
      </div>
    </aside>
  </div>
</section>

<!-- ============ MAP ============ -->
<section class="mapband">
  <iframe src="<?= e($contact['map']) ?>" title="Bharat Trading location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
  <div class="container mapband-in">
    <div class="mapcard">
      <h2>Bharat Trading</h2>
      <p><?= e($contact['address']) ?></p>
      <a class="btn btn-brand btn-sm" href="<?= e($maps_dir) ?>" target="_blank" rel="noopener">Open in Google Maps<?= icon('arrow') ?></a>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="section">
  <div class="container faq-wrap">
    <header class="reveal">
      <h2 class="sec-title">Before you write</h2>
      <p class="sec-sub">Answers to the questions we hear most often from new buyers.</p>
    </header>
    <div class="faq reveal"><?php foreach (array_slice(company_faqs(), 0, 6) as $f): ?><details><summary><?= e($f[0]) ?></summary><p><?= e($f[1]) ?></p></details><?php endforeach; ?></div>
  </div>
</section>

<!-- ============ REGISTRATIONS ============ -->
<section class="regstrip">
  <div class="container regstrip-in">
    <h2>Registered business</h2>
    <ul>
      <li><small>APEDA RCMC</small><b><?= e(COMPANY['rcmc']) ?></b></li>
      <li><small>IEC</small><b><?= e(COMPANY['iec']) ?></b></li>
      <li><small>GSTIN</small><b><?= e(COMPANY['gstin']) ?></b></li>
      <li><small>Udyam</small><b><?= e(COMPANY['udyam']) ?></b></li>
    </ul>
  </div>
</section>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
