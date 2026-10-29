<?php
/** includes/footer.php — closes <body>. Needs $contact (set by header.php). */
$extra_js = $extra_js ?? [];
$f_cats = array_slice(get_categories(), 0, 6);
?>
<footer class="site-footer">
    <div class="container ft-grid">
        <div class="ft-brand">
            <a href="<?= e(url()) ?>" aria-label="<?= e(COMPANY['legal']) ?> home"><?= logo_html('footer', true) ?></a>
            <p>Merchant exporter and supplier of Indian agricultural commodities and food products, based in Nashik,
                Maharashtra.</p>
            <?php if ($contact['social']): ?>
            <div class="ft-social">
                <?php foreach ($contact['social'] as $net => $link): ?>
                <a href="<?= e($link) ?>" target="_blank" rel="noopener"
                    aria-label="<?= e(ucfirst($net)) ?>"><?= icon($net) ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <nav class="ft-col" aria-label="Footer">
            <h3>Quick links</h3>
            <ul>
                <?php foreach ($nav as $n): ?><li><a href="<?= e($n[1]) ?>"><?= e($n[0]) ?></a></li><?php endforeach; ?>
            </ul>
        </nav>

        <div class="ft-col">
            <h3>Our products</h3>
            <ul>
                <?php foreach ($f_cats as $c): ?><li><a href="<?= e(url($c['url'])) ?>"><?= e($c['name']) ?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="ft-col ft-contact">
            <h3>Contact</h3>
            <ul>
                <li><?= icon('pin') ?><span><?= e($contact['address']) ?></span></li>
                <li><?= icon('phone') ?><a href="<?= e($contact['tel']) ?>"><?= e($contact['phone_txt']) ?></a></li>
                <li><?= icon('mail') ?><a href="mailto:<?= e($contact['email']) ?>"><?= e($contact['email']) ?></a></li>
                <?php if ($contact['hours']): ?><li><?= icon('clock') ?><span><?= e($contact['hours']) ?></span></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <div class="ft-reg">
        <div class="container ft-reg-in">
            <span><b>APEDA RCMC</b> <?= e(COMPANY['rcmc']) ?>, valid up to <?= e(COMPANY['rcmc_valid']) ?></span>
            <span><b>IEC</b> <?= e(COMPANY['iec']) ?></span>
            <span><b>GSTIN</b> <?= e(COMPANY['gstin']) ?></span>
            <span><b>Udyam</b> <?= e(COMPANY['udyam']) ?></span>
        </div>
    </div>

    <div class="ft-bottom">
        <div class="container ft-bottom-in">
            <p>&copy; <?= date('Y') ?> <?= e(COMPANY['legal']) ?>. All rights reserved.</p>
            <p>Proprietor: <?= e(COMPANY['owner']) ?></p>
        </div>
    </div>
</footer>

<a class="fab fab-wa"
    href="<?= e($contact['wa']) ?>?text=<?= rawurlencode('Hello Bharat Trading, I would like to enquire about your products.') ?>"
    target="_blank" rel="noopener" aria-label="Chat on WhatsApp"><?= icon('wa') ?></a>
<button class="fab fab-top" id="toTop" type="button" aria-label="Back to top"><?= icon('up') ?></button>

<script src="<?= e(url('/assets/js/main.js')) ?>?v=<?= (int)@filemtime(ROOT_PATH . '/assets/js/main.js') ?>" defer>
</script>
<?php foreach ($extra_js as $jf): ?><script
    src="<?= e(url('/assets/js/' . $jf)) ?>?v=<?= (int)@filemtime(ROOT_PATH . '/assets/js/' . $jf) ?>" defer></script>
<?php endforeach; ?>
</body>

</html>