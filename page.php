<?php
include('config/connect.php');

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, $_GET['slug']) : '';

$pageQuery = mysqli_query($conn, "SELECT * FROM custom_pages WHERE slug_url = '$slug' AND status = 1");
$pageData = mysqli_fetch_assoc($pageQuery);

if (!$pageData) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$blogQuery = mysqli_query($conn, "SELECT * FROM blogs WHERE slug = '$slug' AND status = 1");
$blog = mysqli_fetch_assoc($blogQuery);

$cate_id = $category['cate_id'] ?? '';
$cat_primary_id = $category['id'] ?? ''; 
$categoryName = $category['categories'] ?? '';

$pageTitle = !empty($pageData['meta_title']) ? $pageData['meta_title'] : $pageData['page_title'];
$meta_description = !empty($pageData['meta_desc']) ? $pageData['meta_desc'] : strip_tags(substr($pageData['content'], 0, 160));
$meta_keywords = $pageData['meta_key'] ?? '';
// Breadcrumb ke liye variable 
$breadcrumb_name = $pageData['breadcrumb_title']; 

$raw_schema = trim($pageData['schema_markup'] ?? '');
if (!empty($raw_schema)) {
    if (stripos($raw_schema, '<script') === false) {
        $page_schema = "<script type=\"application/ld+json\">\n" . $raw_schema . "\n</script>";
    } else {
        $page_schema = $raw_schema;
    }
} else {
    $page_schema = "";
}

$contactQuery = mysqli_query($conn, "SELECT phone FROM contacts LIMIT 1");
$contactInfo = mysqli_fetch_assoc($contactQuery);
$sitePhone = !empty($contactInfo['phone']) ? $contactInfo['phone'] : '+91-8448211202';

include 'includes/header.php';
?>

<style>
/* Premium Hero & Overlap Design */
.custom-page-hero {
    background-color: #1a1a1a;
    background-image: url('https://via.placeholder.com/1920x500/1a1a1a/444444');
    /* Replace with dynamic banner if available */
    background-size: cover;
    background-position: center;
    position: relative;
    padding: 120px 0 140px 0;
    /* Extra bottom padding for overlap */
    color: #ffffff;
    text-align: center;
}

.custom-page-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.65);
}

.hero-content-wrapper {
    position: relative;
    z-index: 2;
}

.overlapping-content-section {
    margin-top: -80px;
    /* Pulls content up over the hero section */
    position: relative;
    z-index: 10;
}

.page-content-box {
    background: #ffffff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
}

.page-content-body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 15px 0;
}

.page-content-body h1,
.page-content-body h2,
.page-content-body h3 {
    color: #222;
    margin-top: 25px;
    margin-bottom: 15px;
    font-weight: 700;
}

.category-list li:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.category-list a:hover {
    color: var(--primary-green, #7bc243) !important;
    padding-left: 5px;
    transition: 0.2s;
}

.active-cat {
    color: var(--primary-green, #7bc243) !important;
}

@keyframes fadeUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-up {
    animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>

<!-- Premium Hero Section -->
<section class="custom-page-hero animate-fade-up">
    <div class="container hero-content-wrapper">
        <h1 class="display-4 fw-bold mb-3"><?= htmlspecialchars($pageData['page_title']); ?></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb justify-content-center text-light mb-0"
                style="background:transparent; font-size:1rem;">
                <li class="breadcrumb-item"><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="location.php" class="text-white text-decoration-none">Locations</a>
                </li>
                <li class="breadcrumb-item active text-white opacity-75" aria-current="page">
                    <?= htmlspecialchars($breadcrumb_name); ?></li>
            </ol>
        </nav>
    </div>
</section>

<!-- Main Page Content -->
<section class="overlapping-content-section pb-5 bg-light">
    <div class="container animate-fade-up" style="animation-delay: 0.2s;">
        <div class="row g-5">

            <!-- LEFT COLUMN: Content Area -->
            <div class="col-lg-8">
                <div class="page-content-box border">

                    <div class="page-content-body" style="line-height: 1.8; color: #444; font-size: 1.05rem;">
                        <?= $pageData['content']; ?>
                    </div>

                    <!-- Social Share Links -->
                    <div class="social-share mt-5 pt-4 border-top d-flex align-items-center">
                        <span class="fw-bold me-3 text-dark">Share this:</span>
                        <?php $current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($current_url) ?>"
                            target="_blank" class="btn btn-sm btn-outline-primary me-2 rounded-circle"
                            style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;"><i
                                class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($current_url) ?>" target="_blank"
                            class="btn btn-sm btn-outline-info me-2 rounded-circle"
                            style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;"><i
                                class="fa-brands fa-twitter"></i></a>
                        <a href="https://wa.me/?text=<?= urlencode($current_url) ?>" target="_blank"
                            class="btn btn-sm btn-outline-success me-2 rounded-circle"
                            style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;"><i
                                class="fa-brands fa-whatsapp"></i></a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode($current_url) ?>"
                            target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle"
                            style="width:35px;height:35px;display:flex;align-items:center;justify-content:center;"><i
                                class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-widgets position-sticky" style="top: 20px;">

                    <!-- Search Widget -->
                    <div class="widget-box bg-white p-4 rounded shadow-sm border mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Search</h5>
                        <form action="blog.php" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control me-2" placeholder="Search..." required
                                style="font-size: 0.9rem;">
                            <button type="submit" class="btn text-white px-3"
                                style="background: var(--primary-green, #7bc243);"><i
                                    class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                    </div>

                    <!-- Dynamic Categories -->
                    <div class="widget-box bg-white p-4 rounded shadow-sm border mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2" style="font-size: 1.1rem; color: #222;">Categories
                        </h5>
                        <ul class="list-unstyled mb-0 category-list">
                            <?php
                            $sideCatQuery = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY id DESC");
                            while($sideCat = mysqli_fetch_assoc($sideCatQuery)):
                                $isActive = ($sideCat['cate_id'] == $cate_id || $sideCat['slug_url'] == $slug) ? 'fw-bold active-cat' : 'text-muted';
                            ?>
                            <li class="mb-2 pb-2 border-bottom" style="border-color: #f8f9fa !important;">
                                <a href="category.php?slug=<?= htmlspecialchars($sideCat['slug_url']) ?>"
                                    class="text-decoration-none d-flex justify-content-between align-items-center category-link <?= $isActive ?>"
                                    style="font-size: 0.95rem;">
                                    <span><i class="fa-solid fa-angle-right me-2" style="font-size: 0.75rem;"></i>
                                        <?= htmlspecialchars($sideCat['categories']) ?></span>
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>

                    <!-- Request Quote Banner -->
                    <div class="widget-box rounded shadow-sm text-center p-4 text-white"
                        style="background: linear-gradient(135deg, var(--primary-green, #7bc243) 0%, #1a3f1b 100%);">
                        <div class="icon-wrap mb-3"><i class="fa-solid fa-headset fs-1 text-white opacity-75"></i></div>
                        <h4 class="fw-bold mb-2 text-white">Need Consultation?</h4>
                        <p class="small mb-4 opacity-75">Reach out to our experts today for customized solutions and
                            pricing.</p>
                        <a href="contact.php" class="btn bg-white w-100 fw-bold shadow-sm mb-3"
                            style="color: var(--primary-green, #7bc243);">Request Quote</a>
                        <a href="tel:<?= htmlspecialchars($sitePhone) ?>"
                            class="text-white text-decoration-none small fw-bold"><i class="fa-solid fa-phone me-1"></i>
                            <?= htmlspecialchars($sitePhone) ?></a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Footer Section -->
<?php include 'includes/footer.php'; ?>