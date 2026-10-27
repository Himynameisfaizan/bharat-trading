<?php
require_once __DIR__ . '/includes/functions.php';

/* ---------- load everything the page needs ---------- */
$contact  = get_contact();
$slides   = get_banners() ?: hero_fallback();
$about    = get_about();
$cats     = get_categories();
$products = get_products(8);
$reviews  = get_reviews(9);
$blogs    = get_blogs(3);
$gallery  = get_gallery(9);

$product_total = count_products();
$has_blog    = !empty($blogs);
$has_gallery = !empty($gallery);

$rated = array_column($reviews, 'rating');
$avg   = $rated ? round(array_sum($rated) / count($rated), 1) : 0;
$review_url = GOOGLE_REVIEW_URL !== '' ? GOOGLE_REVIEW_URL : 'https://www.google.com/search?q=' . rawurlencode('Bharat Trading Nashik reviews');
$prod_cats  = array_values(array_unique(array_column($products, 'cat')));

require __DIR__ . '/includes/header.php';
?>
<main id="main">

<!-- ============ HERO ============ -->
<section class="hero" id="top" aria-roledescription="carousel" aria-label="Featured">
  <div class="hero-slides">
    <?php foreach ($slides as $i => $s): $tag = $i === 0 ? 'h1' : 'h2'; ?>
    <article class="hero-slide<?= $i === 0 ? ' is-active' : '' ?>" aria-hidden="<?= $i === 0 ? 'false' : 'true' ?>">
      <div class="hero-bg<?= $s['img'] ? '' : ' fb' . (($i % 3) + 1) ?>"<?php if ($s['img']): ?> style="background-image:url('<?= e($s['img']) ?>')"<?php endif; ?>></div>
      <div class="container hero-inner">
        <div class="hero-copy">
          <p class="hero-kicker"><?= icon('award') ?>APEDA-registered merchant exporter from Nashik</p>
          <<?= $tag ?> class="hero-title"><?= e($s['title']) ?></<?= $tag ?>>
          <?php if ($s['desc']): ?><p class="hero-text"><?= e($s['desc']) ?></p><?php endif; ?>
          <div class="hero-cta">
            <a class="btn btn-gold" href="<?= e($s['link'] ?: '#inquiry') ?>"><?= $s['link'] ? 'Learn more' : 'Request a quote' ?><?= icon('arrow') ?></a>
            <a class="btn btn-ghost" href="#products">View products</a>
          </div>
        </div>
      </div>
    </article>
    <?php endforeach; ?>
  </div>

  <aside class="hero-aside" aria-label="Company at a glance">
    <div class="container">
      <div class="manifest">
        <div class="mf-head"><span>Trade profile</span><span class="mf-stamp">APEDA RCMC</span></div>
        <dl>
          <div><dt>Based in</dt><dd>Nashik, Maharashtra, India</dd></div>
          <div><dt>Products</dt><dd>Onions, Basmati and non-Basmati rice, spices</dd></div>
          <div><dt>Markets in APEDA application</dt><dd><?= e(implode(', ', COMPANY['markets'])) ?></dd></div>
          <div><dt>Supply capacity</dt><dd><?= number_format(COMPANY['capacity']) ?> MT per month</dd></div>
          <div><dt>RCMC valid until</dt><dd><?= e(COMPANY['rcmc_valid']) ?></dd></div>
        </dl>
        <div class="mf-foot"><?= e(COMPANY['rcmc']) ?></div>
      </div>
    </div>
  </aside>

  <div class="hero-ui">
    <div class="container hero-ui-in">
      <div class="hero-count"><b id="heroCur">01</b><span>/ <?= sprintf('%02d', count($slides)) ?></span></div>
      <?php if (count($slides) > 1): ?>
      <div class="hero-dots">
        <?php foreach ($slides as $i => $s): ?><button type="button" class="<?= $i === 0 ? 'is-active' : '' ?>" aria-label="Go to slide <?= $i + 1 ?>"><i></i></button><?php endforeach; ?>
      </div>
      <div class="hero-arrows">
        <button type="button" id="heroPrev" aria-label="Previous slide"><?= icon('left') ?></button>
        <button type="button" id="heroNext" aria-label="Next slide"><?= icon('arrow') ?></button>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<div class="trustbar">
  <div class="container trust-in">
    <div class="trust-item"><?= icon('award') ?><div><b>APEDA registered</b><span>RCMC valid to <?= e(COMPANY['rcmc_valid']) ?></span></div></div>
    <div class="trust-item"><?= icon('shield') ?><div><b>IEC and GST registered</b><span>IEC <?= e(COMPANY['iec']) ?></span></div></div>
    <div class="trust-item"><?= icon('box') ?><div><b>Customised packing</b><span>As per buyer requirement</span></div></div>
    <div class="trust-item"><?= icon('search') ?><div><b>Quality inspection</b><span>Arranged as mutually agreed</span></div></div>
  </div>
</div>

<!-- ============ ABOUT ============ -->
<section class="section about" id="about">
  <div class="container about-grid">
    <div class="about-media reveal">
      <?php if ($about['img']): ?>
        <img src="<?= e($about['img']) ?>" alt="About <?= e(COMPANY['name']) ?>" loading="lazy">
      <?php else: ?>
        <div class="about-art"><span><?= icon('onion') ?></span><span><?= icon('rice') ?></span><span><?= icon('spice') ?></span></div>
      <?php endif; ?>
      <div class="about-badge"><b>Nashik</b><span>Maharashtra, India</span></div>
    </div>

    <div class="about-body reveal">
      <h2 class="sec-title"><?= e($about['title']) ?></h2>
      <div class="prose"><?= $about['content'] ?></div>
      <ul class="ticks">
        <li><?= icon('check') ?>Buyer-oriented sourcing</li>
        <li><?= icon('check') ?>Grades and sizes as per requirement</li>
        <li><?= icon('check') ?>Customised export packing</li>
        <li><?= icon('check') ?>Inspection and shipment coordination</li>
      </ul>
      <div class="about-stats">
        <div><b><span class="count" data-to="<?= (int)COMPANY['capacity'] ?>">0</span></b><span>MT monthly supply capacity</span></div>
        <div><b><span class="count" data-to="<?= count(COMPANY['markets']) ?>">0</span></b><span>export markets in APEDA application</span></div>
        <div><b><span class="count" data-to="<?= (int)$product_total ?>">0</span></b><span>product lines</span></div>
      </div>
      <div class="about-foot">
        <div class="owner"><span class="owner-av"><?= e(initials(COMPANY['owner'])) ?></span><div><b><?= e(COMPANY['owner']) ?></b><span><?= e(COMPANY['role']) ?>, <?= e(COMPANY['name']) ?></span></div></div>
        <a class="btn btn-brand" href="#inquiry">Talk to us<?= icon('arrow') ?></a>
      </div>
    </div>
  </div>
</section>

<!-- ============ CATEGORIES ============ -->
<section class="section cats" id="categories">
  <div class="container">
    <header class="sec-head reveal">
      <div><h2 class="sec-title">What we supply</h2><p class="sec-sub">Three product families, sourced to the grade, size and packing your market needs.</p></div>
      <a class="link-arrow" href="<?= e(url('products.php')) ?>">Browse all products<?= icon('arrow') ?></a>
    </header>
    <div class="cat-grid">
      <?php foreach ($cats as $c): $href = strpos($c['url'], '#') === 0 ? $c['url'] : url($c['url']); ?>
      <a class="cat-tile reveal" href="<?= e($href) ?>">
        <span class="cat-media">
          <?php if ($c['img']): ?><img src="<?= e($c['img']) ?>" alt="<?= e($c['name']) ?>" loading="lazy"><?php else: ?><span class="ph ph-<?= e($c['icon']) ?>"><?= icon($c['icon'], 'ph-ico') ?></span><?php endif; ?>
        </span>
        <span class="cat-info">
          <strong><?= e($c['name']) ?></strong>
          <em><?= $c['count'] > 0 ? $c['count'] . ($c['count'] === 1 ? ' product' : ' products') : e($c['note'] ?? 'Explore the range') ?></em>
        </span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ PRODUCTS ============ -->
<section class="section prod" id="products">
  <div class="container">
    <header class="sec-head reveal">
      <div><h2 class="sec-title">Our products</h2><p class="sec-sub">Pricing is quoted per enquiry, based on specification, quantity, packing, destination and agreed FOB or CIF terms.</p></div>
      <a class="link-arrow" href="<?= e(url('products.php')) ?>">View full catalogue<?= icon('arrow') ?></a>
    </header>

    <?php if (count($prod_cats) > 1): ?>
    <div class="ptabs reveal" role="tablist" aria-label="Filter products">
      <button type="button" class="ptab is-active" data-filter="all">All</button>
      <?php foreach ($prod_cats as $pc): ?><button type="button" class="ptab" data-filter="<?= e(slugify($pc)) ?>"><?= e($pc) ?></button><?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="pgrid">
      <?php foreach ($products as $p): ?>
      <article class="pcard reveal" data-cat="<?= e(slugify($p['cat'])) ?>">
        <div class="pcard-media">
          <?php if ($p['img']): ?><img src="<?= e($p['img']) ?>" alt="<?= e($p['name']) ?>" loading="lazy"><?php else: ?><span class="ph ph-<?= e($p['group']) ?>"><?= icon($p['group'], 'ph-ico') ?></span><?php endif; ?>
          <span class="pcard-tag"><?= e($p['cat']) ?></span>
        </div>
        <div class="pcard-body">
          <h3><?= e($p['name']) ?></h3>
          <?php if ($p['desc']): ?><p><?= e($p['desc']) ?></p><?php endif; ?>
          <div class="pcard-foot">
            <span class="price-note">Price on enquiry</span>
            <?php if ($p['url']): ?><a class="pcard-link" href="<?= e(url($p['url'])) ?>">Details<?= icon('arrow') ?></a><?php endif; ?>
            <a class="btn btn-brand btn-sm" href="#inquiry" data-quote="<?= e($p['name']) ?>">Get quote</a>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ REVIEWS ============ -->
<section class="section reviews" id="reviews">
  <div class="container">
    <header class="sec-head sec-head--light reveal">
      <div><h2 class="sec-title">What buyers say about us</h2><p class="sec-sub">Feedback from the importers, distributors and food businesses we have worked with.</p></div>
    </header>
    <div class="rv-wrap">
      <div class="rv-summary reveal">
        <div class="rv-g"><?= icon('g', 'g-ico') ?><span>Reviews</span></div>
        <?php if ($reviews): ?>
          <div class="rv-score"><b><?= number_format($avg, 1) ?></b><?= stars((int)round($avg)) ?></div>
          <p>Average from <?= count($reviews) ?> <?= count($reviews) === 1 ? 'review' : 'reviews' ?></p>
        <?php else: ?>
          <div class="rv-score rv-score--empty"><?= stars(5) ?></div>
          <p>Worked with Bharat Trading? Tell other buyers about it.</p>
        <?php endif; ?>
        <a class="btn btn-gold btn-sm" href="<?= e($review_url) ?>" target="_blank" rel="noopener">Write a review</a>
      </div>

      <?php if ($reviews): ?>
      <div class="rv-main reveal">
        <div class="rv-track" id="rvTrack">
          <?php foreach ($reviews as $r): ?>
          <figure class="rv-card">
            <div class="rv-top">
              <span class="rv-av"><?php if ($r['img']): ?><img src="<?= e($r['img']) ?>" alt="" loading="lazy"><?php else: ?><?= e(initials($r['name'])) ?><?php endif; ?></span>
              <div><b><?= e($r['name']) ?></b><span><?= e($r['role']) ?></span></div>
              <?php if (REVIEWS_ARE_FROM_GOOGLE): ?><?= icon('g', 'g-ico g-ico--sm') ?><?php endif; ?>
            </div>
            <div class="rv-rate"><?= stars($r['rating']) ?><?php if ($r['date']): ?><time><?= e($r['date']) ?></time><?php endif; ?></div>
            <blockquote><?= e($r['text']) ?></blockquote>
            <figcaption>
              <?php if ($r['topic']): ?><span><?= e($r['topic']) ?></span><?php endif; ?>
              <?php if (REVIEWS_ARE_FROM_GOOGLE): ?><span class="rv-ver"><?= icon('check') ?>Posted on Google</span><?php endif; ?>
            </figcaption>
          </figure>
          <?php endforeach; ?>
        </div>
        <?php if (count($reviews) > 2): ?>
        <div class="rv-nav"><button type="button" id="rvPrev" aria-label="Previous reviews"><?= icon('left') ?></button><button type="button" id="rvNext" aria-label="Next reviews"><?= icon('arrow') ?></button></div>
        <?php endif; ?>
      </div>
      <?php else: ?>
      <div class="rv-empty reveal">
        <h3>Your feedback helps the next buyer decide</h3>
        <p>If we have supplied your shipment, a short Google review about quality, packing and communication goes a long way.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============ BLOG ============ -->
<?php if ($has_blog): $first = $blogs[0]; $rest = array_slice($blogs, 1); ?>
<section class="section blog" id="blog">
  <div class="container">
    <header class="sec-head reveal">
      <div><h2 class="sec-title">Insights for importers</h2><p class="sec-sub">Practical notes on sourcing, grading, packing and shipping Indian commodities.</p></div>
      <a class="link-arrow" href="<?= e(url('blog.php')) ?>">Read all articles<?= icon('arrow') ?></a>
    </header>
    <div class="blog-grid<?= $rest ? '' : ' blog-grid--one' ?>">
      <a class="post post--lead reveal" href="<?= e(url($first['url'])) ?>">
        <span class="post-media"><?php if ($first['img']): ?><img src="<?= e($first['img']) ?>" alt="<?= e($first['title']) ?>" loading="lazy"><?php else: ?><span class="ph ph-spice"><?= icon('spice', 'ph-ico') ?></span><?php endif; ?></span>
        <span class="post-body">
          <span class="post-meta"><?= e($first['date']) ?>, <?= (int)$first['mins'] ?> min read</span>
          <strong><?= e($first['title']) ?></strong>
          <span class="post-text"><?= e($first['text']) ?></span>
          <span class="link-arrow">Read article<?= icon('arrow') ?></span>
        </span>
      </a>
      <?php if ($rest): ?>
      <div class="post-list">
        <?php foreach ($rest as $b): ?>
        <a class="post post--row reveal" href="<?= e(url($b['url'])) ?>">
          <span class="post-media"><?php if ($b['img']): ?><img src="<?= e($b['img']) ?>" alt="<?= e($b['title']) ?>" loading="lazy"><?php else: ?><span class="ph ph-spice"><?= icon('spice', 'ph-ico') ?></span><?php endif; ?></span>
          <span class="post-body">
            <span class="post-meta"><?= e($b['date']) ?>, <?= (int)$b['mins'] ?> min read</span>
            <strong><?= e($b['title']) ?></strong>
            <span class="post-text"><?= e($b['text']) ?></span>
          </span>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ GALLERY ============ -->
<?php if ($has_gallery): ?>
<section class="section gallery" id="gallery">
  <div class="container">
    <header class="sec-head reveal">
      <div><h2 class="sec-title">Gallery</h2><p class="sec-sub">A look at the produce and packing we work with.</p></div>
    </header>
    <div class="g-grid">
      <?php foreach ($gallery as $g): ?>
      <a class="g-item reveal" href="<?= e($g['src']) ?>" data-lightbox="<?= e($g['alt']) ?>">
        <img src="<?= e($g['src']) ?>" alt="<?= e($g['alt']) ?>" loading="lazy">
        <span><?= e($g['alt']) ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ INQUIRY ============ -->
<section class="section inquiry" id="inquiry">
  <div class="container">
    <header class="sec-head reveal">
      <div><h2 class="sec-title">Send us your requirement</h2><p class="sec-sub">Tell us the product, quantity and destination. We will come back with a quotation.</p></div>
    </header>
    <div class="inq-grid">
      <div class="inq-map reveal">
        <iframe src="<?= e($contact['map']) ?>" title="Bharat Trading location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
        <div class="inq-card">
          <ul>
            <li><?= icon('pin') ?><span><?= e($contact['address']) ?></span></li>
            <li><?= icon('phone') ?><a href="<?= e($contact['tel']) ?>"><?= e($contact['phone_txt']) ?></a></li>
            <li><?= icon('mail') ?><a href="mailto:<?= e($contact['email']) ?>"><?= e($contact['email']) ?></a></li>
          </ul>
        </div>
      </div>

      <form class="inq-form reveal" id="inqForm" action="<?= e(url('inquiry-submit.php')) ?>" method="post" novalidate>
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
            <?php foreach ($products as $p): ?><option><?= e($p['name']) ?></option><?php endforeach; ?>
            <option>Other</option>
          </select>
        </label>
        <label>Quantity, grade, packing and destination
          <textarea name="message" rows="5" required maxlength="2000"></textarea>
        </label>
        <button class="btn btn-gold btn-block" type="submit">Send enquiry</button>
        <p class="f-status" id="inqStatus" role="status" aria-live="polite"></p>
      </form>
    </div>
  </div>
</section>

</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
