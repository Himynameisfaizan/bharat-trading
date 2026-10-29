<?php
/**
 * includes/enquiry-form.php — reusable enquiry form (submits to inquiry-submit.php through main.js).
 * Optional var: $form_product = product name to pre-select.
 */
$form_product = $form_product ?? '';
$opts = array_column(get_catalogue(), 'name');
if ($form_product !== '' && !in_array($form_product, $opts, true)) array_unshift($opts, $form_product);
?>
<form class="inq-form" id="inqForm" action="<?= e(url('inquiry-submit.php')) ?>" method="post" novalidate>
  <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
  <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
  <div class="f-row">
    <label>Full name<input type="text" name="name" required maxlength="120" autocomplete="name"></label>
    <label>Email<input type="email" name="email" required maxlength="160" autocomplete="email"></label>
  </div>
  <div class="f-row">
    <label>Phone or WhatsApp<input type="tel" name="phone" maxlength="30" autocomplete="tel"></label>
    <label>Country<input type="text" name="country" maxlength="80" autocomplete="country-name"></label>
  </div>
  <label>Product of interest
    <select name="product" id="inqProduct">
      <option value="">Select a product</option>
      <?php foreach ($opts as $o): ?><option<?= $o === $form_product ? ' selected' : '' ?>><?= e($o) ?></option><?php endforeach; ?>
      <option>Other</option>
    </select>
  </label>
  <label>Your requirement
    <textarea name="message" rows="5" required maxlength="2000" placeholder="Quantity per shipment, grade or size, packing type, destination port and delivery window"></textarea>
  </label>
  <button class="btn btn-gold btn-block" type="submit">Send enquiry</button>
  <p class="f-status" id="inqStatus" role="status" aria-live="polite"></p>
</form>
