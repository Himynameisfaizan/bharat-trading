<?php
$pageTitle = 'Locations & Custom Pages | AristoNut';
$meta_title = 'Locations & Custom Pages | AristoNut';
$meta_desc = 'Explore location and information pages published by AristoNut.';
$meta_key = 'AristoNut, locations, custom pages, premium makhana';
$breadcrumb_title = 'Locations';

// Adjust this path if your database connection file is in a different folder.
include 'config/connect.php';

// Fetch active custom pages using the schema in custom_pages.sql.
$customPages = [];
$sql = "SELECT page_title, breadcrumb_title, slug_url, meta_title, meta_desc
        FROM custom_pages
        WHERE status = 1 AND slug_url <> ''
        ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customPages[] = $row;
    }
}

function locationPageText($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

// Use the existing shared site header (it should output the opening HTML/head/body).
include 'includes/header.php';
?>

<style>
/* ================= ARISTONUT PREMIUM THEME ================= */
:root {
    --location-brand-dark: #2c1e16;
    /* AristoNut Dark Brown */
    --location-brand-accent: #9c5521;
    /* AristoNut Premium Brown */
    --location-bg-light: #fffaf5;
    /* AristoNut Light Beige */
    --location-muted: #6b5b53;
    /* AristoNut Muted Text */
    --location-border: rgba(139, 69, 19, 0.1);
}

body.location-page {
    margin: 0;
    background: #ffffff;
    color: var(--location-brand-dark);
    font-family: "Inter", Arial, sans-serif;
}

/* Premium Hero Section */
.location-hero {
    position: relative;
    overflow: hidden;
    padding: 70px 0 60px;
    color: var(--location-brand-dark);
    text-align: center;
    background-color: var(--location-bg-light);
    border-bottom: 1px solid var(--location-border);
}

.location-hero .eyebrow {
    display: inline-block;
    margin-bottom: 15px;
    font-family: "Inter", sans-serif;
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 2px;
    color: var(--location-brand-accent);
    text-transform: uppercase;
    border-bottom: 2px solid var(--location-brand-accent);
    padding-bottom: 4px;
}

.location-hero h1 {
    margin: 0 0 15px;
    font-size: clamp(2rem, 3.5vw, 3rem);
    font-weight: 800;
    font-family: "Poppins", sans-serif;
    line-height: 1.15;
    letter-spacing: -0.5px;
}

.location-hero p {
    max-width: 680px;
    margin: 0 auto;
    color: var(--location-muted);
    font-size: 1.05rem;
    line-height: 1.6;
}

/* Breadcrumb Styling */
.location-breadcrumb-wrap {
    background: #ffffff;
    border-bottom: 1px solid rgba(0, 0, 0, 0.03);
}

.location-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin: 0;
    padding: 15px 0;
    list-style: none;
    color: var(--location-muted);
    font-size: 0.9rem;
    font-weight: 500;
}

.location-breadcrumb a {
    color: var(--location-brand-accent);
    text-decoration: none;
    transition: color 0.2s ease;
}

.location-breadcrumb a:hover {
    color: var(--location-brand-dark);
}

.location-section {
    padding: 80px 0;
    background: #ffffff;
}

.location-section-heading {
    max-width: 720px;
    margin: 0 auto 40px;
    text-align: center;
}

.location-section-heading .small-label {
    color: var(--location-brand-accent);
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.location-section-heading h2 {
    margin: 10px 0 15px;
    color: var(--location-brand-dark);
    font-size: clamp(1.8rem, 2.5vw, 2.2rem);
    font-weight: 800;
    font-family: "Poppins", sans-serif;
}

.location-section-heading p {
    margin: 0;
    color: var(--location-muted);
    line-height: 1.6;
}

/* Premium Card Styling */
.location-card {
    display: flex;
    height: 100%;
    flex-direction: column;
    padding: 30px;
    border: 1px solid rgba(0, 0, 0, 0.04);
    border-radius: 16px;
    background: #ffffff;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}

.location-card:hover {
    transform: translateY(-5px);
    border-color: rgba(156, 85, 33, 0.2);
    box-shadow: 0 15px 30px rgba(44, 30, 22, 0.08);
}

.location-card-top {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
}

.location-card-icon {
    display: inline-flex;
    width: 48px;
    height: 48px;
    flex: 0 0 48px;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: var(--location-bg-light);
    color: var(--location-brand-accent);
    font-size: 1.3rem;
    border: 1px solid var(--location-border);
}

.location-card h3 {
    margin: 4px 0 0;
    color: var(--location-brand-dark);
    font-size: 1.15rem;
    font-weight: 700;
    font-family: "Poppins", sans-serif;
    line-height: 1.4;
    overflow-wrap: anywhere;
}

.location-card p {
    display: -webkit-box;
    overflow: hidden;
    margin: 0 0 25px;
    color: var(--location-muted);
    font-size: 0.95rem;
    line-height: 1.6;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
}

.location-card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px dashed var(--location-border);
}

.location-card-label {
    color: var(--location-brand-accent);
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Premium Button Styling */
.location-card-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px 22px;
    border: 1px solid var(--location-brand-dark);
    border-radius: 50px;
    background: transparent;
    color: var(--location-brand-dark);
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.location-card-button:hover {
    background: var(--location-brand-dark);
    color: #ffffff;
    box-shadow: 0 4px 15px rgba(44, 30, 22, 0.15);
}

.location-empty {
    padding: 50px 30px;
    border: 2px dashed rgba(139, 69, 19, 0.2);
    border-radius: 16px;
    background: var(--location-bg-light);
    text-align: center;
}

.location-empty h3 {
    margin-bottom: 12px;
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--location-brand-dark);
}

.location-empty p {
    margin: 0;
    color: var(--location-muted);
}

@media (max-width: 575.98px) {
    .location-hero {
        padding: 50px 0 40px;
    }

    .location-section {
        padding: 50px 0;
    }

    .location-card {
        padding: 20px;
    }

    .location-card-footer {
        align-items: flex-start;
        flex-direction: column;
    }

    .location-card-button {
        width: 100%;
    }
}
</style>

<header class="location-hero">
    <div class="container">
        <span class="eyebrow">AristoNut Updates</span>
        <h1>Explore Our Pages</h1>
        <p>Discover our location-focused pages, services and useful information tailored for wholesale and commercial
            requirements.</p>
    </div>
</header>

<div class="location-breadcrumb-wrap">
    <div class="container">
        <nav aria-label="Breadcrumb">
            <ol class="location-breadcrumb">
                <li><a href="index.php">Home</a></li>
                <li aria-hidden="true"><i class="bi bi-chevron-right" style="font-size: 0.75rem;"></i></li>
                <li aria-current="page">Locations</li>
            </ol>
        </nav>
    </div>
</div>

<main>
    <section class="location-section">
        <div class="container">
            <div class="location-section-heading">
                <span class="small-label">Find a Page</span>
                <h2>Our Locations &amp; Information Pages</h2>
                <p>Browse the custom pages published by AristoNut. Select a page to open its complete details in a new
                    tab.</p>
            </div>

            <?php if (!empty($customPages)): ?>
            <div class="row g-4 justify-content-center">
                <?php foreach ($customPages as $customPage):
                        $cardTitle = trim((string)($customPage['breadcrumb_title'] ?? ''));
                        if ($cardTitle === '') {
                            $cardTitle = trim((string)($customPage['page_title'] ?? ''));
                        }
                        if ($cardTitle === '') {
                            $cardTitle = 'Explore Page';
                        }

                        $description = trim(strip_tags((string)($customPage['meta_desc'] ?? '')));
                        if ($description === '') {
                            $description = 'Explore premium makhana details and information from AristoNut.';
                        }

                        $slug = trim((string)($customPage['slug_url'] ?? ''));
                        $pageUrl = 'page.php?slug=' . rawurlencode($slug);
                    ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="location-card">
                        <div class="location-card-top">
                            <div class="location-card-icon" aria-hidden="true">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <h3><?= locationPageText($cardTitle); ?></h3>
                        </div>

                        <p><?= locationPageText($description); ?></p>

                        <div class="location-card-footer">
                            <span class="location-card-label">AristoNut</span>
                            <a class="location-card-button" href="<?= locationPageText($pageUrl); ?>" target="_blank"
                                rel="noopener noreferrer"
                                aria-label="Open <?= locationPageText($cardTitle); ?> in a new tab">
                                View Page
                                <i class="bi bi-arrow-up-right ms-1" style="font-size: 0.8rem;"></i>
                            </a>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="location-empty">
                <i class="bi bi-file-earmark-break display-4 text-muted mb-3 d-block"></i>
                <h3>No pages available yet</h3>
                <p>Published custom location pages will appear here automatically.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>