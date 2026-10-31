<?php
/**
 * site-check.php  —  development helper (works only when $local = true in config/connect.php)
 * Shows: database connection, which database images are found or missing, and old-site leftovers.
 * Delete this file before the website goes live.
 */
require_once __DIR__ . '/includes/functions.php';
if (empty($local)) { http_response_code(403); exit('site-check.php works only on localhost. Set $local = true in config/connect.php.'); }

// run the main queries once so any SQL problem is recorded
get_catalogue(); get_categories(); get_banners(); get_about_sections(); get_blogs(3); get_gallery(3); get_reviews(3); get_contact();

$sources = [
    ['Banner',       'banner',      "SELECT id, banner_path AS f FROM banners"],
    ['About section','about',       "SELECT id, image_url AS f FROM about_sections"],
    ['Category',     'category',    "SELECT id, image AS f FROM categories"],
    ['Product',      'product',     "SELECT id, pro_img AS f FROM products"],
    ['Blog',         'blog',        "SELECT blog_id AS id, image AS f FROM blogs"],
    ['Testimonial',  'testimonial', "SELECT test_id AS id, image AS f FROM testimonials"],
    ['Certificate',  'certificate', "SELECT cert_id AS id, image AS f FROM certificates"],
    ['Gallery',      'gallery',     "SELECT ID AS id, image_path AS f FROM gallery"],
    ['Logo (active)','logo',        "SELECT id, logo_path AS f FROM logos WHERE is_active = 1"],
];
$rows = []; $missing = 0; $found = 0;
foreach ($sources as [$label, $type, $sql]) {
    foreach (db_all($sql) as $r) {
        foreach (explode(',', (string)$r['f']) as $f) {
            $f = trim($f); if ($f === '') continue;
            $u = find_img($f, $type);
            $u ? $found++ : $missing++;
            $rows[] = [$label, $type, $r['id'], $f, $u];
        }
    }
}

$leftovers = [];
$scan = ['about_sections' => ['title', 'content'], 'banners' => ['title', 'description'], 'blogs' => ['title', 'description'], 'categories' => ['categories'],
         'products' => ['pro_name', 'short_desc', 'description'], 'testimonials' => ['message'], 'contacts' => ['email', 'address', 'contact_email'], 'meta' => ['meta_title']];
foreach ($scan as $tbl => $cols) {
    $or = [];
    foreach ($cols as $c) foreach (STALE_MARKERS as $m) { $or[] = "$c LIKE '%" . $conn->real_escape_string($m) . "%'"; }
    $n = (int)(db_all("SELECT COUNT(*) AS n FROM $tbl WHERE " . implode(' OR ', $or))[0]['n'] ?? 0);
    $tot = (int)(db_all("SELECT COUNT(*) AS n FROM $tbl")[0]['n'] ?? 0);
    $leftovers[] = [$tbl, $n, $tot];
}
$dbname = db_all('SELECT DATABASE() AS d')[0]['d'] ?? '?';
$cfg = img_cfg();
?><!DOCTYPE html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<title>Site check</title>
<style>
body{font:15px/1.55 system-ui,Segoe UI,Arial,sans-serif;margin:0;background:#f4f6f5;color:#14201c}
.w{max-width:1100px;margin:0 auto;padding:24px}
h1{margin:0 0 4px}h2{margin:32px 0 10px;font-size:1.15rem}
.card{background:#fff;border:1px solid #d9dfdc;border-radius:10px;padding:16px 18px;margin-bottom:14px}
table{width:100%;border-collapse:collapse;background:#fff;border:1px solid #d9dfdc;border-radius:8px;overflow:hidden;font-size:.9rem}
th,td{padding:8px 10px;border-bottom:1px solid #e6eae8;text-align:left;vertical-align:middle;word-break:break-all}
th{background:#eef2f0}
.ok{color:#117a3d;font-weight:700}.bad{color:#b3261e;font-weight:700}.warn{color:#a15c00;font-weight:700}
img{width:44px;height:44px;object-fit:cover;border-radius:6px;display:block}
code{background:#eef2f0;padding:1px 6px;border-radius:4px}
</style></head><body><div class="w">
<h1>Site check</h1>
<p>Database: <b><?= e($dbname) ?></b> &nbsp;|&nbsp; Site URL: <b><?= e($site) ?></b> &nbsp;|&nbsp; Website folder: <code><?= e(ROOT_PATH) ?></code></p>

<h2>1. Database queries</h2>
<div class="card">
<?php $errs = array_unique($GLOBALS['bt_db_errors'] ?? []); if (!$errs): ?>
  <span class="ok">All page queries ran without errors.</span>
<?php else: foreach ($errs as $er): ?><div class="bad"><?= e($er) ?></div><?php endforeach; endif; ?>
</div>

<h2>2. Images: <span class="ok"><?= $found ?> found</span>, <span class="<?= $missing ? 'bad' : 'ok' ?>"><?= $missing ?> missing</span></h2>
<?php if ($missing): ?>
<div class="card">
  <b>How to fix missing images</b>
  <ol>
    <li>Copy the <code>uploads</code> folder of your admin panel into <code><?= e(ROOT_PATH) ?></code> (so that files like <code>uploads/banners/...</code> exist there).</li>
    <li>If your admin saves images in a different folder, add that folder name to the matching list in <code>config/paths.php</code> (the <code>dirs</code> section, e.g. <code>'product' =&gt; ['uploads/products', ...]</code>).</li>
    <li>If the admin is a separate project outside this folder, put its uploads folder in the <code>external</code> section of <code>config/paths.php</code>.</li>
    <li>Reload this page. The folder list is cached for 2 minutes, so a new upload may take that long to appear.</li>
  </ol>
  Folders searched by name as a safety net: <code><?= e(implode(', ', $cfg['search_roots'])) ?></code>
</div>
<?php endif; ?>
<table>
  <tr><th>Type</th><th>Row</th><th>Value in database</th><th>Status</th><th></th></tr>
  <?php foreach ($rows as $r): ?>
  <tr>
    <td><?= e($r[0]) ?></td><td><?= e($r[2]) ?></td><td><?= e($r[3]) ?></td>
    <td><?= $r[4] ? '<span class="ok">found</span><br><small>' . e(str_replace($site, '', $r[4])) . '</small>' : '<span class="bad">missing</span>' ?></td>
    <td><?php if ($r[4]): ?><img src="<?= e($r[4]) ?>" alt=""><?php endif; ?></td>
  </tr>
  <?php endforeach; ?>
</table>

<h2>3. Content that still belongs to the previous website</h2>
<p>Rows that contain <?= e(implode(' or ', array_map(function ($m) { return '"' . $m . '"'; }, STALE_MARKERS))) ?>. Replace them from the admin panel before launch. Setting <code>STALE_GUARD</code> to <code>true</code> in <code>includes/functions.php</code> hides such rows on the website.</p>
<table>
  <tr><th>Table</th><th>Rows with old content</th><th>Total rows</th></tr>
  <?php foreach ($leftovers as $l): ?><tr><td><?= e($l[0]) ?></td><td class="<?= $l[1] ? 'warn' : 'ok' ?>"><?= $l[1] ?></td><td><?= $l[2] ?></td></tr><?php endforeach; ?>
</table>
<p><small>STALE_GUARD is currently <b><?= STALE_GUARD ? 'ON' : 'OFF' ?></b>. Delete this file before going live.</small></p>
</div></body></html>
