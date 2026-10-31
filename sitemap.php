<?php
/**
 * sitemap.php  -  XML sitemap built from the database.
 * It is served as  /sitemap.xml  (see .htaccess). New products, categories and blog posts
 * appear automatically; nothing has to be edited when content is added in the admin panel.
 *
 * URLs listed:   /   /about   /products   /blog   /contact
 *                /category/{slug}   /product/{slug}   /blog/{slug}
 * The domain comes from $site in config/connect.php.
 */
ob_start();
require_once __DIR__ . '/config/connect.php';          // $conn and $site
require_once __DIR__ . '/includes/content.php';        // static_catalogue(): used only if the products table is empty
ini_set('display_errors', '0');                        // a PHP notice must never end up inside the XML
mysqli_report(MYSQLI_REPORT_OFF);
ob_end_clean();

$base = rtrim($site, '/') . '/';

function sm_loc(string $path): string {
    global $base;
    return $base . implode('/', array_map('rawurlencode', explode('/', ltrim($path, '/'))));
}
function sm_x($s): string { return htmlspecialchars((string)$s, ENT_XML1 | ENT_QUOTES, 'UTF-8'); }
function sm_rows(string $sql): array {
    global $conn;
    $r = $conn->query($sql);
    return $r ? $r->fetch_all(MYSQLI_ASSOC) : [];
}
function sm_date($v): string { $t = strtotime((string)$v); return $t ? date('c', $t) : ''; }

$urls = [];   // each: [loc, lastmod, changefreq, priority]

/* ---------- data ---------- */
$blogs = sm_rows("SELECT slug, created_at FROM blogs WHERE status = 1 AND slug <> '' ORDER BY created_at DESC, blog_id DESC");
$prods = sm_rows("SELECT slug_url FROM products WHERE status = 1 AND (is_disabled = 0 OR is_disabled IS NULL) AND slug_url <> '' ORDER BY id ASC");
$cats  = sm_rows("SELECT c.slug_url FROM categories c WHERE c.status = 1 AND c.slug_url <> ''
                  AND EXISTS (SELECT 1 FROM products p WHERE p.pro_cate = c.cate_id AND p.status = 1 AND (p.is_disabled = 0 OR p.is_disabled IS NULL))
                  ORDER BY c.id ASC");
if (!$prods) {                                         // site is running on the built-in catalogue
    $cats = [];
    foreach (static_catalogue() as $slug => $x) { $prods[] = ['slug_url' => $slug]; $cats[$x['cat_slug']] = ['slug_url' => $x['cat_slug']]; }
}

$latest_post = 0;
foreach ($blogs as $b) { $latest_post = max($latest_post, (int)strtotime((string)$b['created_at'])); }

/* ---------- main pages ---------- */
$pages = [['', 'index.php', 'weekly', '1.0'], ['about', 'about.php', 'monthly', '0.8'], ['products', 'products.php', 'weekly', '0.9'],
          ['blog', 'blog.php', 'weekly', '0.7'], ['contact', 'contact.php', 'yearly', '0.7']];
foreach ($pages as [$path, $file, $freq, $prio]) {
    $mt = (int)@filemtime(__DIR__ . '/' . $file);
    if ($path === '' || $path === 'blog') $mt = max($mt, $latest_post);
    $urls[] = [sm_loc($path), $mt ? date('c', $mt) : '', $freq, $prio];
}
/* ---------- categories, products, blog posts (lastmod only where the database really has a date) ---------- */
foreach ($cats  as $c) { $urls[] = [sm_loc('category/' . $c['slug_url']), '', 'weekly', '0.8']; }
foreach ($prods as $p) { $urls[] = [sm_loc('product/'  . $p['slug_url']), '', 'monthly', '0.8']; }
foreach ($blogs as $b) { $urls[] = [sm_loc('blog/'     . $b['slug']),     sm_date($b['created_at']), 'monthly', '0.6']; }

/* ---------- output ---------- */
header('Content-Type: application/xml; charset=utf-8');
header('Cache-Control: public, max-age=3600');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach (array_slice($urls, 0, 50000) as $u) {
    echo "  <url>\n    <loc>" . sm_x($u[0]) . "</loc>\n";
    if ($u[1] !== '') echo '    <lastmod>' . sm_x($u[1]) . "</lastmod>\n";
    echo '    <changefreq>' . $u[2] . "</changefreq>\n    <priority>" . $u[3] . "</priority>\n  </url>\n";
}
echo "</urlset>\n";
