<?php
/**
 * config/paths.php  —  WHERE YOUR UPLOADED IMAGES LIVE
 *
 * The database stores only a file name (for example "hyg.jpeg") or a short path
 * (for example "uploads/banners/banner_123.jpg"). The folder itself is decided by your admin panel.
 * If images do not show, this is the only file you need to edit.
 *
 * Folders are relative to the website root (the folder that contains index.php).
 * Open  site-check.php  in the browser to see which images are found and which are missing.
 */
return [

    // 1) Exact folders per image type. The site tries them from first to last.
    'dirs' => [
        'logo'        => ['uploads/logo', 'admin/uploads/logo', 'admin/logo', 'logo'],
        'banner'      => ['uploads/banners', 'admin/uploads/banners'],
        'about'       => ['uploads/about_us', 'admin/uploads/about_us'],
        'category'    => ['uploads/categories', 'uploads/category', 'uploads', 'admin/uploads/category', 'admin/uploads'],
        'product'     => ['uploads/products', 'uploads/product', 'uploads', 'admin/uploads/products', 'admin/uploads'],
        'blog'        => ['uploads/blogs', 'uploads/blog', 'uploads', 'admin/uploads/blogs', 'admin/uploads'],
        'gallery'     => ['uploads/gallery', 'uploads', 'admin/uploads/gallery', 'admin/uploads'],
        'testimonial' => ['uploads/testimonials', 'uploads/testimonial', 'uploads', 'admin/uploads'],
        'certificate' => ['uploads/certificates', 'uploads/certificate', 'uploads', 'admin/uploads'],
    ],

    // 2) Safety net: if a file is not in the folders above, these folders (and everything inside them)
    //    are searched by file name. The list is cached for 2 minutes.
    'search_roots' => ['uploads', 'admin/uploads', 'admin', 'logo', 'assets', 'images', 'upload'],

    // 3) Only if your admin panel is a SEPARATE project outside this website folder, list its uploads
    //    folder here:  [ 'full path on disk', 'public URL of that same folder' ]
    'external' => [
        // ['C:/xampp/htdocs/projects/bharat-admin/uploads', 'http://localhost/projects/bharat-admin/uploads'],
    ],
];