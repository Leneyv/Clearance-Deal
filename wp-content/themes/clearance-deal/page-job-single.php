<?php
/**
 * Template Name: Single Job Posting
 * Template for displaying clearance.co.ke/jobs/{job-slug}/
 *
 * @package Clearance_Deal
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/jobs-data.php';

$all_jobs = cd_get_job_listings();
$current_slug = get_post()->post_name;

if ( ! isset( $all_jobs[ $current_slug ] ) ) {
	// Fallback to first job or redirect to /jobs
	wp_safe_redirect( home_url( '/jobs/' ) );
	exit;
}

$job = $all_jobs[ $current_slug ];

get_header();
?>

<!-- Schema.org JobPosting Structured Data for Google Jobs -->
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "JobPosting",
  "title": "<?php echo esc_js( $job['title'] ); ?>",
  "description": "<?php echo esc_js( strip_tags( $job['description'] ) ); ?>",
  "identifier": {
    "@type": "PropertyValue",
    "name": "Clearance Deal Kenya",
    "value": "<?php echo esc_js( $job['ref'] ); ?>"
  },
  "datePosted": "2026-10-14",
  "validThrough": "2027-01-31T23:59:59+03:00",
  "employmentType": "FULL_TIME",
  "hiringOrganization": {
    "@type": "Organization",
    "name": "Clearance Deal Kenya",
    "sameAs": "https://clearance.co.ke",
    "logo": "https://clearance.co.ke/wp-content/themes/clearance-deal/assets/images/logo.svg"
  },
  "jobLocation": {
    "@type": "Place",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Office 303, Highlight Business Park, 4th Parklands",
      "addressLocality": "Nairobi",
      "addressRegion": "Nairobi County",
      "postalCode": "00100",
      "addressCountry": "KE"
    }
  },
  "baseSalary": {
    "@type": "MonetaryAmount",
    "currency": "USD",
    "value": {
      "@type": "QuantitativeValue",
      "value": <?php echo (int) $job['salary_amount']; ?>,
      "unitText": "MONTH"
    }
  }
}
</script>

<style>
/* Single Job Page Styling */
.cd-job-single-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    padding: 48px 20px 40px;
    border-bottom: 3px solid #e11d48;
}
.cd-job-single-hero-inner {
    max-width: 1100px;
    margin: 0 auto;
}
.cd-job-breadcrumb {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 20px;
}
.cd-job-breadcrumb a {
    color: #cbd5e1;
    text-decoration: none;
    transition: color 0.2s;
}
.cd-job-breadcrumb a:hover {
    color: #ffffff;
}
.cd-job-single-ref-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(225, 29, 72, 0.2);
    border: 1px solid rgba(225, 29, 72, 0.4);
    color: #fda4af;
    padding: 5px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 14px;
}
.cd-job-single-hero h1 {
    font-size: clamp(2rem, 4vw, 2.75rem);
    font-weight: 800;
    line-height: 1.15;
    margin-bottom: 16px;
    color: #ffffff;
}
.cd-job-single-meta {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}
.cd-job-single-salary {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
    padding: 6px 14px;
    border-radius: 999px;
    font-weight: 800;
    font-size: 15px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.cd-job-single-tag {
    background: rgba(255, 255, 255, 0.1);
    color: #e2e8f0;
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.cd-job-single-actions {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
    padding-top: 8px;
}
.cd-btn-apply-main {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg, #25d366, #128c7e);
    color: #ffffff !important;
    font-weight: 800;
    font-size: 15px;
    padding: 13px 26px;
    border-radius: 10px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4);
    transition: all 0.2s ease;
}
.cd-btn-apply-main:hover {
    filter: brightness(1.08);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
}
.cd-btn-share-wa {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    font-weight: 600;
    font-size: 14px;
    padding: 12px 20px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s ease;
}
.cd-btn-share-wa:hover {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
}

/* Two-column Main Container */
.cd-job-single-layout {
    max-width: 1100px;
    margin: 40px auto 80px;
    padding: 0 20px;
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 36px;
    align-items: start;
}
@media (max-width: 900px) {
    .cd-job-single-layout {
        grid-template-columns: 1fr;
    }
}

/* Main Content Column */
.cd-job-main-col {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 36px 32px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.04);
}
.cd-job-section-block {
    margin-bottom: 36px;
}
.cd-job-section-block:last-child {
    margin-bottom: 0;
}
.cd-job-section-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f5f9;
}
.cd-job-section-title i {
    color: #e11d48;
}
.cd-job-desc-text {
    font-size: 15px;
    line-height: 1.7;
    color: #334155;
}
.cd-remuneration-card {
    background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
    border: 1.5px solid #a7f3d0;
    border-radius: 12px;
    padding: 20px 22px;
    margin: 20px 0;
    display: flex;
    align-items: flex-start;
    gap: 14px;
}
.cd-remuneration-card i {
    font-size: 24px;
    color: #059669;
    flex-shrink: 0;
    margin-top: 2px;
}
.cd-remuneration-card strong {
    color: #065f46;
    font-size: 16px;
    display: block;
    margin-bottom: 4px;
}
.cd-remuneration-card p {
    margin: 0;
    font-size: 14px;
    color: #047857;
    line-height: 1.5;
}
.cd-job-checklist {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.cd-job-checklist li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 14px;
    color: #334155;
    line-height: 1.6;
}
.cd-job-checklist li i {
    color: #e11d48;
    font-size: 15px;
    margin-top: 4px;
    flex-shrink: 0;
}
.cd-job-perks-list li i {
    color: #059669;
}

/* Bottom Apply Card in Main Col */
.cd-single-apply-cta {
    background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
    border: 1.5px solid #fecdd3;
    border-radius: 14px;
    padding: 28px;
    text-align: center;
    margin-top: 40px;
}
.cd-single-apply-cta h3 {
    font-size: 20px;
    font-weight: 800;
    color: #9f1239;
    margin: 0 0 8px 0;
}
.cd-single-apply-cta p {
    font-size: 14px;
    color: #881337;
    line-height: 1.5;
    margin: 0 0 20px 0;
}

/* Sidebar Column */
.cd-job-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
    position: sticky;
    top: 20px;
}
.cd-sidebar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.03);
}
.cd-sidebar-card h4 {
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 16px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 10px;
    border-bottom: 1px solid #f1f5f9;
}
.cd-sidebar-info-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px solid #f8fafc;
    font-size: 13px;
}
.cd-sidebar-info-row:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
.cd-sidebar-label {
    color: #64748b;
    font-weight: 600;
}
.cd-sidebar-val {
    color: #0f172a;
    font-weight: 700;
    text-align: right;
}
.cd-sidebar-val.salary-val {
    color: #047857;
}

/* Other Jobs List in Sidebar */
.cd-other-jobs-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.cd-other-job-item {
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 10px;
    padding: 12px 14px;
    text-decoration: none;
    transition: all 0.2s ease;
    display: block;
}
.cd-other-job-item:hover {
    background: #fff;
    border-color: #cbd5e1;
    transform: translateX(3px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.cd-other-job-title {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 4px;
}
.cd-other-job-meta {
    font-size: 12px;
    color: #059669;
    font-weight: 600;
}

/* Modal Popup Styling */
.cd-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    z-index: 99999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 0;
    visibility: hidden;
    transition: all 0.25s ease;
}
.cd-modal-overlay.is-active {
    opacity: 1;
    visibility: visible;
}
.cd-modal-card {
    background: #ffffff;
    border-radius: 18px;
    max-width: 540px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,0.25);
    position: relative;
    padding: 32px 28px;
    transform: translateY(20px);
    transition: transform 0.25s ease;
}
.cd-modal-overlay.is-active .cd-modal-card {
    transform: translateY(0);
}
.cd-modal-close {
    position: absolute;
    top: 18px;
    right: 20px;
    background: #f1f5f9;
    border: none;
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #475569;
    font-size: 18px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.cd-modal-close:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.cd-form-group {
    margin-bottom: 16px;
    text-align: left;
}
.cd-form-group label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 6px;
}
.cd-form-group input,
.cd-form-group textarea {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14px;
    color: #0f172a;
    background: #fff;
    outline: none;
    transition: border-color 0.2s ease;
}
.cd-form-group input:focus,
.cd-form-group textarea:focus {
    border-color: #e11d48;
}
.cd-file-drop {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
}
.cd-file-drop:hover {
    border-color: #e11d48;
    background: #fff1f2;
}
.cd-submit-btn {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #25d366, #128c7e);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-size: 15px;
    font-weight: 800;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.35);
    transition: all 0.2s ease;
}
.cd-submit-btn:hover {
    filter: brightness(1.05);
    transform: translateY(-1px);
}
.cd-submit-btn:disabled {
    opacity: 0.65;
    cursor: not-allowed;
    transform: none;
}
</style>

<!-- Single Job Hero -->
<section class="cd-job-single-hero">
    <div class="cd-job-single-hero-inner">
        <nav class="cd-job-breadcrumb" aria-label="Breadcrumbs">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <span>&rsaquo;</span>
            <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>">Careers at Clearance Kenya</a>
            <span>&rsaquo;</span>
            <span style="color:#ffffff; font-weight:600;"><?php echo esc_html( $job['title'] ); ?></span>
        </nav>

        <div class="cd-job-single-ref-badge">
            <i class="fa-solid fa-briefcase"></i> Job Reference: <?php echo esc_html( $job['ref'] ); ?>
        </div>

        <h1><?php echo esc_html( $job['title'] ); ?></h1>

        <div class="cd-job-single-meta">
            <span class="cd-job-single-salary">
                <i class="fa-solid fa-money-bill-wave"></i> <?php echo esc_html( $job['salary'] ); ?>
            </span>
            <span class="cd-job-single-tag">
                <i class="fa-solid fa-briefcase" style="color:#fb7185;"></i> <?php echo esc_html( $job['type'] ); ?>
            </span>
            <span class="cd-job-single-tag">
                <i class="fa-solid fa-location-dot" style="color:#38bdf8;"></i> <?php echo esc_html( $job['location'] ); ?>
            </span>
            <span class="cd-job-single-tag">
                <i class="fa-solid fa-clock" style="color:#fcd34d;"></i> <?php echo esc_html( $job['hours'] ); ?>
            </span>
            <span class="cd-job-single-tag">
                <i class="fa-solid <?php echo esc_attr( $job['category_icon'] ); ?>" style="color:#4ade80;"></i> <?php echo esc_html( $job['category'] ); ?>
            </span>
        </div>

        <div class="cd-job-single-actions">
            <button type="button" class="cd-btn-apply-main" onclick="openApplyModal('<?php echo esc_js( $job['ref'] ); ?>', '<?php echo esc_js( $job['title'] ); ?>')">
                Apply with CV <i class="fa-brands fa-whatsapp"></i>
            </button>
            <?php
            $share_url = get_permalink();
            $share_text = rawurlencode( 'Clearance Kenya is hiring a ' . $job['title'] . ' (' . $job['salary'] . '). Check details and apply here: ' . $share_url );
            ?>
            <a href="https://wa.me/?text=<?php echo $share_text; ?>" target="_blank" rel="noopener" class="cd-btn-share-wa">
                <i class="fa-brands fa-whatsapp" style="color:#25d366;"></i> Share Position
            </a>
            <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" class="cd-btn-share-wa">
                <i class="fa-solid fa-arrow-left"></i> View All 5 Open Jobs
            </a>
        </div>
    </div>
</section>

<!-- Single Job Layout -->
<main class="cd-job-single-layout">

    <!-- Left Main Column -->
    <div class="cd-job-main-col">
        <!-- 1. Role Overview -->
        <div class="cd-job-section-block">
            <h2 class="cd-job-section-title">
                <i class="fa-solid fa-circle-info"></i> Position Overview
            </h2>
            <p class="cd-job-desc-text">
                <?php echo esc_html( $job['description'] ); ?>
            </p>

            <div class="cd-remuneration-card">
                <i class="fa-solid fa-hand-holding-dollar"></i>
                <div>
                    <strong>Remuneration &amp; Compensation</strong>
                    <p><?php echo esc_html( $job['remuneration_note'] ); ?></p>
                </div>
            </div>
        </div>

        <!-- 2. Key Responsibilities -->
        <div class="cd-job-section-block">
            <h2 class="cd-job-section-title">
                <i class="fa-solid fa-list-check"></i> Key Responsibilities
            </h2>
            <ul class="cd-job-checklist">
                <?php foreach ( $job['responsibilities'] as $resp ) : ?>
                    <li>
                        <i class="fa-solid fa-circle-check"></i>
                        <span><?php echo esc_html( $resp ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 3. Requirements & Qualifications -->
        <div class="cd-job-section-block">
            <h2 class="cd-job-section-title">
                <i class="fa-solid fa-graduation-cap"></i> Requirements &amp; Qualifications
            </h2>
            <ul class="cd-job-checklist">
                <?php foreach ( $job['requirements'] as $req ) : ?>
                    <li>
                        <i class="fa-solid fa-check"></i>
                        <span><?php echo esc_html( $req ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 4. Perks & Benefits -->
        <div class="cd-job-section-block">
            <h2 class="cd-job-section-title">
                <i class="fa-solid fa-gift"></i> What We Offer
            </h2>
            <ul class="cd-job-checklist cd-job-perks-list">
                <?php foreach ( $job['benefits'] as $benefit ) : ?>
                    <li>
                        <i class="fa-solid fa-star"></i>
                        <span><?php echo esc_html( $benefit ); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 5. Bottom Apply Callout -->
        <div class="cd-single-apply-cta">
            <h3>Ready to Join the Clearance Kenya Team?</h3>
            <p>Upload your CV and connect directly with our Recruitment Desk on WhatsApp. Quote <strong><?php echo esc_html( $job['ref'] ); ?></strong>.</p>
            <button type="button" class="cd-btn-apply-main" onclick="openApplyModal('<?php echo esc_js( $job['ref'] ); ?>', '<?php echo esc_js( $job['title'] ); ?>')">
                Submit Your CV via WhatsApp <i class="fa-brands fa-whatsapp"></i>
            </button>
        </div>
    </div>

    <!-- Right Sidebar -->
    <aside class="cd-job-sidebar">
        <!-- Quick Job Info -->
        <div class="cd-sidebar-card">
            <h4><i class="fa-solid fa-clipboard-list" style="color:#e11d48;"></i> Job Summary</h4>
            <div class="cd-sidebar-info-row">
                <span class="cd-sidebar-label">Job Reference</span>
                <span class="cd-sidebar-val font-mono"><?php echo esc_html( $job['ref'] ); ?></span>
            </div>
            <div class="cd-sidebar-info-row">
                <span class="cd-sidebar-label">Monthly Salary</span>
                <span class="cd-sidebar-val salary-val"><?php echo esc_html( $job['salary'] ); ?></span>
            </div>
            <div class="cd-sidebar-info-row">
                <span class="cd-sidebar-label">Employment Type</span>
                <span class="cd-sidebar-val"><?php echo esc_html( $job['type'] ); ?></span>
            </div>
            <div class="cd-sidebar-info-row">
                <span class="cd-sidebar-label">Department</span>
                <span class="cd-sidebar-val"><?php echo esc_html( $job['category'] ); ?></span>
            </div>
            <div class="cd-sidebar-info-row">
                <span class="cd-sidebar-label">Working Hours</span>
                <span class="cd-sidebar-val"><?php echo esc_html( $job['hours'] ); ?></span>
            </div>
            <div class="cd-sidebar-info-row">
                <span class="cd-sidebar-label">Duty Location</span>
                <span class="cd-sidebar-val"><?php echo esc_html( $job['location'] ); ?></span>
            </div>
            <div class="cd-sidebar-info-row">
                <span class="cd-sidebar-label">Office Address</span>
                <span class="cd-sidebar-val" style="font-size:12px; max-width:180px;">Highlight Business Park, Parklands</span>
            </div>
            <div style="margin-top:18px;">
                <button type="button" class="cd-submit-btn" style="width:100%; padding:11px;" onclick="openApplyModal('<?php echo esc_js( $job['ref'] ); ?>', '<?php echo esc_js( $job['title'] ); ?>')">
                    Apply for this Job <i class="fa-brands fa-whatsapp"></i>
                </button>
            </div>
        </div>

        <!-- HR Inquiries -->
        <div class="cd-sidebar-card" style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border-color: #bbf7d0;">
            <h4 style="color:#065f46;"><i class="fa-brands fa-whatsapp" style="color:#16a34a;"></i> HR Direct Desk</h4>
            <p style="font-size:13px; color:#14532d; line-height:1.5; margin:0 0 14px 0;">
                Have questions regarding this role or our hiring process? Chat with HR on WhatsApp.
            </p>
            <a href="https://wa.me/254751338975?text=Hello%20Clearance%20Kenya%20HR%2C%20I%20am%20inquiring%20about%20the%20<?php echo rawurlencode( $job['title'] ); ?>%20position%20(Ref%3A%20<?php echo rawurlencode( $job['ref'] ); ?>)." target="_blank" rel="noopener" class="cd-submit-btn" style="text-decoration:none; width:100%; padding:10px; font-size:14px;">
                <i class="fa-brands fa-whatsapp"></i> Chat with HR (+254 751 338 975)
            </a>
        </div>

        <!-- Other Open Opportunities -->
        <div class="cd-sidebar-card">
            <h4><i class="fa-solid fa-briefcase" style="color:#0284c7;"></i> More Open Roles</h4>
            <div class="cd-other-jobs-list">
                <?php
                foreach ( $all_jobs as $other_slug => $other_job ) :
                    if ( $other_slug === $current_slug ) {
                        continue;
                    }
                    ?>
                    <a href="<?php echo esc_url( home_url( '/jobs/' . $other_slug . '/' ) ); ?>" class="cd-other-job-item">
                        <div class="cd-other-job-title"><?php echo esc_html( $other_job['num'] . '. ' . $other_job['title'] ); ?></div>
                        <div class="cd-other-job-meta">
                            <span><?php echo esc_html( $other_job['salary'] ); ?></span> &bull;
                            <span style="color:#64748b;"><?php echo esc_html( $other_job['type'] ); ?></span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
            <div style="margin-top:14px; text-align:center;">
                <a href="<?php echo esc_url( home_url( '/jobs/' ) ); ?>" style="font-size:13px; font-weight:700; color:#e11d48; text-decoration:none;">
                    View All Careers &rarr;
                </a>
            </div>
        </div>
    </aside>

</main>

<!-- Job Application Popup Modal -->
<div class="cd-modal-overlay" id="jobApplyModal" aria-hidden="true">
    <div class="cd-modal-card">
        <button type="button" class="cd-modal-close" onclick="closeApplyModal()" aria-label="Close modal">&times;</button>
        
        <div style="text-align:center; margin-bottom:20px;">
            <div style="width:52px; height:52px; border-radius:50%; background:#ecfdf5; color:#16a34a; font-size:26px; display:inline-flex; align-items:center; justify-content:center; margin-bottom:10px;">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <h3 style="font-size:22px; font-weight:800; color:#0f172a; margin:0 0 6px 0;">Submit Application via WhatsApp</h3>
            <p style="font-size:13px; color:#64748b; margin:0;">Upload your CV and connect directly with our hiring team on WhatsApp</p>
        </div>

        <form id="cdJobApplyForm" enctype="multipart/form-data">
            <input type="hidden" name="action" value="cd_upload_job_cv">
            <input type="hidden" name="security" value="<?php echo esc_attr( wp_create_nonce( 'cd_job_application_nonce' ) ); ?>">
            <input type="hidden" id="modalJobRef" name="job_ref" value="<?php echo esc_attr( $job['ref'] ); ?>">
            <input type="hidden" id="modalJobTitle" name="job_title" value="<?php echo esc_attr( $job['title'] ); ?>">

            <div style="background:#f1f5f9; border-radius:10px; padding:12px 16px; margin-bottom:18px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:700; display:block;">Applying For</span>
                    <strong id="modalJobDisplayTitle" style="font-size:15px; color:#0f172a;"><?php echo esc_html( $job['title'] ); ?></strong>
                </div>
                <span id="modalJobDisplayRef" class="cd-job-ref" style="background:#fff; font-family:monospace;"><?php echo esc_html( $job['ref'] ); ?></span>
            </div>

            <div class="cd-form-group">
                <label for="applicantName">Full Name *</label>
                <input type="text" id="applicantName" name="applicant_name" placeholder="e.g. Grace Wanjiku" required>
            </div>

            <div class="cd-form-group">
                <label for="applicantPhone">Phone / WhatsApp Number *</label>
                <input type="tel" id="applicantPhone" name="applicant_phone" placeholder="e.g. +254 712 345 678" required>
            </div>

            <div class="cd-form-group">
                <label for="cvFile">Attach Your CV / Resume * (PDF or Word)</label>
                <div class="cd-file-drop" onclick="document.getElementById('cvFile').click()">
                    <i class="fa-solid fa-cloud-arrow-up" style="font-size:32px; color:#e11d48; margin-bottom:8px;"></i>
                    <div id="cvFileLabel" style="font-size:14px; font-weight:700; color:#0f172a;">Click to browse your CV file</div>
                    <div style="font-size:12px; color:#64748b; margin-top:4px;">Supported: PDF, DOC, DOCX (Max 15MB)</div>
                    <input type="file" id="cvFile" name="cv_file" accept=".pdf,.doc,.docx" style="display:none;" onchange="handleFileSelected(this)" required>
                </div>
            </div>

            <div class="cd-form-group">
                <label for="applicantNotes">Short Note / Introduction (Optional)</label>
                <textarea id="applicantNotes" name="applicant_notes" rows="2" placeholder="Briefly describe your relevant experience..."></textarea>
            </div>

            <div id="modalStatusMsg" style="font-size:13px; margin-bottom:14px; display:none; padding:10px 14px; border-radius:8px;"></div>

            <button type="submit" id="submitAppBtn" class="cd-submit-btn">
                <i class="fa-brands fa-whatsapp" style="font-size:18px;"></i> Send CV to WhatsApp (+254 751 338 975)
            </button>
        </form>
    </div>
</div>

<script>
var ajaxUrl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';

function openApplyModal(refNumber, jobTitle) {
    document.getElementById('modalJobRef').value = refNumber;
    document.getElementById('modalJobTitle').value = jobTitle;
    document.getElementById('modalJobDisplayTitle').textContent = jobTitle;
    document.getElementById('modalJobDisplayRef').textContent = refNumber;

    var status = document.getElementById('modalStatusMsg');
    status.style.display = 'none';
    status.textContent = '';

    var modal = document.getElementById('jobApplyModal');
    modal.classList.add('is-active');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

function closeApplyModal() {
    var modal = document.getElementById('jobApplyModal');
    modal.classList.remove('is-active');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

document.getElementById('jobApplyModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeApplyModal();
    }
});

function handleFileSelected(input) {
    var label = document.getElementById('cvFileLabel');
    if (input.files && input.files.length > 0) {
        var file = input.files[0];
        var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
        label.innerHTML = '<span style="color:#059669;"><i class="fa-solid fa-file-check"></i> ' + file.name + ' (' + sizeMB + ' MB)</span>';
    } else {
        label.textContent = 'Click to browse your CV file';
    }
}

document.getElementById('cdJobApplyForm').addEventListener('submit', function(e) {
    e.preventDefault();

    var submitBtn = document.getElementById('submitAppBtn');
    var status = document.getElementById('modalStatusMsg');
    var formData = new FormData(this);

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Uploading CV & Connecting to WhatsApp...';
    status.style.display = 'none';

    fetch(ajaxUrl, {
        method: 'POST',
        body: formData
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success && data.data && data.data.wa_url) {
            status.style.display = 'block';
            status.style.background = '#ecfdf5';
            status.style.color = '#065f46';
            status.style.border = '1px solid #a7f3d0';
            status.innerHTML = '<strong><i class="fa-solid fa-circle-check"></i> CV Attached!</strong> Opening WhatsApp now with your application details...';

            window.open(data.data.wa_url, '_blank');

            submitBtn.innerHTML = '<i class="fa-brands fa-whatsapp"></i> Opened in WhatsApp! Click again to reopen';
            submitBtn.disabled = false;
            submitBtn.onclick = function() {
                window.open(data.data.wa_url, '_blank');
            };
        } else {
            status.style.display = 'block';
            status.style.background = '#fef2f2';
            status.style.color = '#991b1b';
            status.style.border = '1px solid #fecaca';
            status.textContent = data.data && data.data.message ? data.data.message : 'Upload failed. Please check your network and try again.';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fa-brands fa-whatsapp"></i> Send CV to WhatsApp (+254 751 338 975)';
        }
    })
    .catch(function(err) {
        status.style.display = 'block';
        status.style.background = '#fef2f2';
        status.style.color = '#991b1b';
        status.style.border = '1px solid #fecaca';
        status.textContent = 'Connection error. Please try again.';
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa-brands fa-whatsapp"></i> Send CV to WhatsApp (+254 751 338 975)';
    });
});
</script>

<?php get_footer(); ?>
