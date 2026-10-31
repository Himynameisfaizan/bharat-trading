<?php
/** includes/cta-band.php — closing call-to-action. Optional vars: $cta_title, $cta_text */
$cta_title = $cta_title ?? 'Have a requirement in mind?';
$cta_text  = $cta_text  ?? 'Send us the product, quantity and destination. We will respond with a quotation based on your specification.';
?>
<section class="ctaband">
    <div class="container ctaband-in">
        <div>
            <h2><?= e($cta_title) ?></h2>
            <p><?= e($cta_text) ?></p>
        </div>
        <div class="ctaband-actions">
            <a class="btn btn-gold" href="<?= e(url('contact.php')) ?>#inquiry">Request a quote<?= icon('arrow') ?></a>
            <a class="btn btn-ghost"
                href="<?= e($contact['wa']) ?>?text=<?= rawurlencode('Hello Bharat Trading, I would like to discuss a requirement.') ?>"
                target="_blank" rel="noopener">WhatsApp us</a>
            <a class="ctaband-phone"
                href="<?= e($contact['tel']) ?>"><?= icon('phone') ?><span><?= e($contact['phone_txt']) ?></span></a>
        </div>
    </div>
</section>