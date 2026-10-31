<?php
/**
 * includes/page-banner.php — inner page title banner with breadcrumb.
 * Vars: $banner_title, $banner_sub (optional), $crumbs = [[label, href|null], ...], $banner_small (optional bool)
 */
$crumbs       = $crumbs ?? [];
$banner_sub   = $banner_sub ?? '';
$banner_small = $banner_small ?? false;
$banner_class = $banner_class ?? '';
$banner_extra = $banner_extra ?? '';
?>
<section class="pbanner<?= $banner_small ? ' pbanner--sm' : '' ?>">
    <div class="container pbanner-in">
        <nav class="crumbs" aria-label="Breadcrumb">
            <ol>
                <li><a href="<?= e(url()) ?>">Home</a></li>
                <?php foreach ($crumbs as $c): ?>
                <li><?php if (!empty($c[1])): ?><a href="<?= e($c[1]) ?>"><?= e($c[0]) ?></a><?php else: ?><span
                        aria-current="page"><?= e($c[0]) ?></span><?php endif; ?></li>
                <?php endforeach; ?>
            </ol>
        </nav>
        <h1 class="pbanner-title <?= e($banner_class) ?>"><?= e($banner_title) ?></h1>
        <?php if ($banner_sub !== ''): ?><p class="pbanner-sub"><?= e($banner_sub) ?></p><?php endif; ?>
        <?php if ($banner_extra !== ''): ?><div class="pbanner-meta"><?= $banner_extra ?></div><?php endif; ?>
    </div>
    <div class="pbanner-art" aria-hidden="true">
        <span><?= icon('onion') ?></span><span><?= icon('rice') ?></span><span><?= icon('spice') ?></span></div>
</section>