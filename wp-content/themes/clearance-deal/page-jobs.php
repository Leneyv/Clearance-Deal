<?php
/**
 * Template Name: Careers / Jobs Page
 * Template for displaying clearance.co.ke/jobs
 *
 * @package Clearance_Deal
 */

get_header();
?>

<style>
/* Jobs Page Modern Styling */
.cd-jobs-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #ffffff;
    padding: 60px 20px 48px;
    text-align: center;
    position: relative;
    border-bottom: 3px solid #e11d48;
}
.cd-jobs-hero-inner {
    max-width: 860px;
    margin: 0 auto;
}
.cd-jobs-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(225, 29, 72, 0.2);
    border: 1px solid rgba(225, 29, 72, 0.4);
    color: #fda4af;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 18px;
}
.cd-jobs-hero h1 {
    font-size: clamp(2rem, 4.5vw, 3rem);
    font-weight: 800;
    line-height: 1.15;
    margin-bottom: 14px;
    color: #ffffff;
}
.cd-jobs-hero h1 span {
    background: linear-gradient(135deg, #fb7185, #f43f5e);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.cd-jobs-hero p {
    font-size: 1.05rem;
    color: #cbd5e1;
    line-height: 1.6;
    max-width: 680px;
    margin: 0 auto;
}

/* Trust Bar / Perks */
.cd-jobs-perks {
    max-width: 1100px;
    margin: -24px auto 40px;
    padding: 0 20px;
    position: relative;
    z-index: 10;
}
.cd-jobs-perks-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}
.cd-perk-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 18px 20px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 14px;
}
.cd-perk-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    background: #ffe4e6;
    color: #e11d48;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.cd-perk-text h4 {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 2px 0;
}
.cd-perk-text p {
    font-size: 12px;
    color: #64748b;
    margin: 0;
}

/* Jobs Listings Section */
.cd-jobs-container {
    max-width: 1100px;
    margin: 0 auto 60px;
    padding: 0 20px;
}
.cd-jobs-header {
    text-align: center;
    margin-bottom: 36px;
}
.cd-jobs-header h2 {
    font-size: 28px;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 8px;
}
.cd-jobs-header p {
    font-size: 15px;
    color: #64748b;
}

/* Job Cards List */
.cd-job-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.cd-job-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    overflow: hidden;
    transition: all 0.25s ease;
}
.cd-job-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.cd-job-header {
    padding: 24px 28px 18px;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    border-bottom: 1px solid #f1f5f9;
}
.cd-job-title-group h3 {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.cd-job-ref {
    display: inline-block;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    color: #334155;
    font-family: monospace, ui-monospace, SFMono-Regular, Menlo;
    font-size: 13px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 6px;
    letter-spacing: 0.5px;
}
.cd-job-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 13px;
    color: #64748b;
}
.cd-job-tags span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.cd-job-salary {
    background: #ecfdf5;
    color: #047857 !important;
    border: 1px solid #a7f3d0;
    padding: 3px 10px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 13px;
}
.cd-job-apply-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #e11d48;
    color: #ffffff;
    font-weight: 700;
    font-size: 14px;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
}
.cd-job-apply-btn:hover {
    background: #be123c;
    color: #ffffff;
    transform: translateY(-1px);
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
    position: relative;
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
.cd-job-body {
    padding: 22px 28px 26px;
}
.cd-job-description {
    font-size: 14px;
    color: #334155;
    line-height: 1.6;
    margin-bottom: 20px;
}
.cd-job-sections {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 22px;
    background: #f8fafc;
    border: 1px solid #edf2f7;
    border-radius: 12px;
    padding: 20px 22px;
}
.cd-job-section h4 {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cd-job-section ul {
    margin: 0;
    padding-left: 18px;
    font-size: 13px;
    color: #475569;
    line-height: 1.6;
}
.cd-job-section ul li {
    margin-bottom: 6px;
}

/* How to Apply Banner */
.cd-apply-instructions {
    background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
    border: 1.5px solid #fecdd3;
    border-radius: 16px;
    padding: 32px;
    margin-top: 48px;
}
.cd-apply-instructions h3 {
    font-size: 20px;
    font-weight: 800;
    color: #9f1239;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cd-apply-instructions p {
    font-size: 14px;
    color: #4c0519;
    line-height: 1.6;
    margin-bottom: 18px;
}
.cd-apply-channels {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}
.cd-apply-channel {
    background: #ffffff;
    border: 1px solid #fda4af;
    border-radius: 10px;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: #0f172a;
    font-weight: 700;
    font-size: 14px;
    transition: all 0.2s ease;
}
.cd-apply-channel:hover {
    border-color: #e11d48;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(225,29,72,0.15);
}
.cd-apply-channel i {
    font-size: 22px;
}
</style>

<!-- Hero Section -->
<section class="cd-jobs-hero">
    <div class="cd-jobs-hero-inner">
        <div class="cd-jobs-badge">
            <i class="fa-solid fa-briefcase"></i> Careers at Clearance Kenya
        </div>
        <h1>Build the Future of Retail &amp; E-Commerce in <span>Kenya</span></h1>
        <p>Join our fast-growing, high-energy team in Nairobi. We are redefining beauty, human hair, cosmetics, and clearance retail across East Africa with world-class logistics and customer trust.</p>
    </div>
</section>

<!-- Company Perks Bar -->
<section class="cd-jobs-perks">
    <div class="cd-jobs-perks-grid">
        <div class="cd-perk-card">
            <div class="cd-perk-icon"><i class="fa-solid fa-location-dot"></i></div>
            <div class="cd-perk-text">
                <h4>Prime Nairobi Office</h4>
                <p>Highlight Business Park, Parklands</p>
            </div>
        </div>
        <div class="cd-perk-card">
            <div class="cd-perk-icon"><i class="fa-solid fa-chart-line"></i></div>
            <div class="cd-perk-text">
                <h4>Rapid Growth</h4>
                <p>Merit-driven career advancement</p>
            </div>
        </div>
        <div class="cd-perk-card">
            <div class="cd-perk-icon"><i class="fa-solid fa-hand-holding-dollar"></i></div>
            <div class="cd-perk-text">
                <h4>Competitive Packages</h4>
                <p>Base salary + quarterly incentives</p>
            </div>
        </div>
        <div class="cd-perk-card">
            <div class="cd-perk-icon"><i class="fa-solid fa-users"></i></div>
            <div class="cd-perk-text">
                <h4>Supportive Culture</h4>
                <p>Collaborative, innovative leadership</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Jobs Content -->
<main class="cd-jobs-container">
    <div class="cd-jobs-header">
        <h2>Open Career Opportunities</h2>
        <p>Review the job descriptions below. Please cite the exact Job Reference Number in your application subject line.</p>
    </div>

    <div class="cd-job-list">

        <!-- 1. General Manager -->
        <article class="cd-job-card" id="job-cdke2026-29">
            <div class="cd-job-header">
                <div class="cd-job-title-group">
                    <h3>1. General Manager <span class="cd-job-ref">Ref: CDKE2026/29</span></h3>
                    <div class="cd-job-tags">
                        <span class="cd-job-salary"><i class="fa-solid fa-money-bill-wave"></i> US$ 3,600 / month</span>
                        <span><i class="fa-solid fa-briefcase" style="color:#e11d48;"></i> Full-Time</span>
                        <span><i class="fa-solid fa-location-dot" style="color:#0284c7;"></i> Nairobi Showroom &amp; HQ</span>
                        <span><i class="fa-solid fa-clock" style="color:#f59e0b;"></i> Mon – Sat</span>
                        <span><i class="fa-solid fa-layer-group" style="color:#10b981;"></i> Senior Management</span>
                    </div>
                </div>
                <button type="button" class="cd-job-apply-btn" onclick="openApplyModal('CDKE2026/29', 'General Manager')">
                    Apply with CV <i class="fa-brands fa-whatsapp"></i>
                </button>
            </div>
            <div class="cd-job-body">
                <p class="cd-job-description">
                    We are seeking a visionary, results-oriented <strong>General Manager</strong> to oversee day-to-day retail, e-commerce, warehousing, and commercial operations across Kenya. You will report directly to executive leadership, driving profitability, showroom footfall, courier SLA fulfillment, and customer satisfaction.
                    <br><strong style="color:#047857; display:inline-block; margin-top:8px;"><i class="fa-solid fa-hand-holding-dollar"></i> Remuneration:</strong> Fixed compensation of <strong>US$ 3,600 per month</strong> plus performance-based executive incentives.
                </p>
                <div class="cd-job-sections">
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-list-check" style="color:#e11d48;"></i> Key Responsibilities</h4>
                        <ul>
                            <li>Lead daily store operations, customer service, and dispatch logistics across Nairobi and upcountry branches.</li>
                            <li>Develop and execute strategic retail expansion, clearance inventory turnover, and revenue growth plans.</li>
                            <li>Supervise department heads in sales, accounting, marketing, and warehousing to ensure high performance and strict KPIs.</li>
                            <li>Maintain rigorous operational controls, cash flow oversight, and compliance with statutory and tax regulations.</li>
                        </ul>
                    </div>
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-graduation-cap" style="color:#0284c7;"></i> Requirements &amp; Qualifications</h4>
                        <ul>
                            <li>Bachelor's degree in Business Administration, Operations Management, or related field (MBA is an advantage).</li>
                            <li>5+ years of senior leadership experience in retail, wholesale FMCG, e-commerce, or beauty/fashion distribution.</li>
                            <li>Demonstrated expertise in P&amp;L management, team leadership, supply chain, and customer dispute resolution.</li>
                            <li>Strong analytical mindset and proficiency with modern ERP/inventory management platforms.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

        <!-- 2. Account Manager -->
        <article class="cd-job-card" id="job-cdke2026-30">
            <div class="cd-job-header">
                <div class="cd-job-title-group">
                    <h3>2. Account Manager <span class="cd-job-ref">Ref: CDKE2026/30</span></h3>
                    <div class="cd-job-tags">
                        <span class="cd-job-salary"><i class="fa-solid fa-money-bill-wave"></i> US$ 3,000 / month</span>
                        <span><i class="fa-solid fa-briefcase" style="color:#e11d48;"></i> Full-Time</span>
                        <span><i class="fa-solid fa-location-dot" style="color:#0284c7;"></i> Parklands, Nairobi</span>
                        <span><i class="fa-solid fa-clock" style="color:#f59e0b;"></i> Mon – Sat</span>
                        <span><i class="fa-solid fa-handshake" style="color:#10b981;"></i> B2B &amp; Salon Partnerships</span>
                    </div>
                </div>
                <button type="button" class="cd-job-apply-btn" onclick="openApplyModal('CDKE2026/30', 'Account Manager')">
                    Apply with CV <i class="fa-brands fa-whatsapp"></i>
                </button>
            </div>
            <div class="cd-job-body">
                <p class="cd-job-description">
                    The <strong>Account Manager</strong> will build and nurture high-value relationships with premium hair salons, freelance hair stylists, beauty influencers, and wholesale partners throughout Nairobi and key metropolitan hubs.
                    <br><strong style="color:#047857; display:inline-block; margin-top:8px;"><i class="fa-solid fa-hand-holding-dollar"></i> Remuneration:</strong> Salary of <strong>US$ 3,000 per month</strong> with quarterly partnership bonuses.
                </p>
                <div class="cd-job-sections">
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-list-check" style="color:#e11d48;"></i> Key Responsibilities</h4>
                        <ul>
                            <li>Acquire and manage high-volume wholesale salon accounts for human hair bundles, lace closures, and professional beauty kits.</li>
                            <li>Act as the primary point of contact for key VIP accounts, resolving pricing inquiries and restocking requests.</li>
                            <li>Promote clearance deals and bulk bundle incentives to salon owners and celebrity stylists.</li>
                            <li>Monitor client order frequency, payment terms, and account receivables in partnership with Finance.</li>
                        </ul>
                    </div>
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-graduation-cap" style="color:#0284c7;"></i> Requirements &amp; Qualifications</h4>
                        <ul>
                            <li>Degree or Diploma in Sales, Marketing, Public Relations, or Business Management.</li>
                            <li>3+ years proven B2B account management experience, preferably in hair, cosmetics, or lifestyle consumer goods.</li>
                            <li>Extensive network and established relationships within the Nairobi salon and beauty industry.</li>
                            <li>Outstanding negotiation, interpersonal presentation, and client retention skills.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

        <!-- 3. Sales Manager -->
        <article class="cd-job-card" id="job-cdke2026-31">
            <div class="cd-job-header">
                <div class="cd-job-title-group">
                    <h3>3. Sales Manager <span class="cd-job-ref">Ref: CDKE2026/31</span></h3>
                    <div class="cd-job-tags">
                        <span class="cd-job-salary"><i class="fa-solid fa-money-bill-wave"></i> US$ 3,000 / month</span>
                        <span><i class="fa-solid fa-briefcase" style="color:#e11d48;"></i> Full-Time</span>
                        <span><i class="fa-solid fa-location-dot" style="color:#0284c7;"></i> Highlight Business Park, Nairobi</span>
                        <span><i class="fa-solid fa-clock" style="color:#f59e0b;"></i> Mon – Sat</span>
                        <span><i class="fa-solid fa-bullseye" style="color:#10b981;"></i> Retail &amp; Online Conversions</span>
                    </div>
                </div>
                <button type="button" class="cd-job-apply-btn" onclick="openApplyModal('CDKE2026/31', 'Sales Manager')">
                    Apply with CV <i class="fa-brands fa-whatsapp"></i>
                </button>
            </div>
            <div class="cd-job-body">
                <p class="cd-job-description">
                    We are hiring an energetic <strong>Sales Manager</strong> to direct our omnichannel sales forces across our Nairobi physical showroom, WhatsApp commerce team, website incoming leads, and inbound telephone calls.
                    <br><strong style="color:#047857; display:inline-block; margin-top:8px;"><i class="fa-solid fa-hand-holding-dollar"></i> Remuneration:</strong> Salary of <strong>US$ 3,000 per month</strong> plus team sales commission accelerators.
                </p>
                <div class="cd-job-sections">
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-list-check" style="color:#e11d48;"></i> Key Responsibilities</h4>
                        <ul>
                            <li>Lead and motivate the showroom sales executives and WhatsApp lead-conversion specialists to beat monthly sales targets.</li>
                            <li>Optimize conversion funnels for inbound WhatsApp inquiries and abandoned website carts.</li>
                            <li>Train sales personnel in hair burn tests, lace tint matching, and Lipa Polepole (BNPL) payment explanations.</li>
                            <li>Track daily sales numbers, conversion metrics, average order value (AOV), and customer feedback.</li>
                        </ul>
                    </div>
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-graduation-cap" style="color:#0284c7;"></i> Requirements &amp; Qualifications</h4>
                        <ul>
                            <li>3+ to 5 years sales management experience with a proven track record of hitting and exceeding aggressive targets.</li>
                            <li>Hands-on experience in WhatsApp commerce, tele-sales, or showroom retail.</li>
                            <li>In-depth product knowledge of human hair extensions, lace front wigs, and cosmetic beauty items.</li>
                            <li>Charismatic leader with strong coaching, data tracking, and communication skills.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

        <!-- 4. Digital Marketing Manager -->
        <article class="cd-job-card" id="job-cdke2026-32">
            <div class="cd-job-header">
                <div class="cd-job-title-group">
                    <h3>4. Digital Marketing Manager <span class="cd-job-ref">Ref: CDKE2026/32</span></h3>
                    <div class="cd-job-tags">
                        <span class="cd-job-salary"><i class="fa-solid fa-money-bill-wave"></i> US$ 3,000 / month</span>
                        <span><i class="fa-solid fa-briefcase" style="color:#e11d48;"></i> Full-Time</span>
                        <span><i class="fa-solid fa-location-dot" style="color:#0284c7;"></i> Nairobi (Showroom &amp; Studio)</span>
                        <span><i class="fa-solid fa-clock" style="color:#f59e0b;"></i> Mon – Sat</span>
                        <span><i class="fa-solid fa-hashtag" style="color:#10b981;"></i> Meta Ads, TikTok &amp; SEO</span>
                    </div>
                </div>
                <button type="button" class="cd-job-apply-btn" onclick="openApplyModal('CDKE2026/32', 'Digital Marketing Manager')">
                    Apply with CV <i class="fa-brands fa-whatsapp"></i>
                </button>
            </div>
            <div class="cd-job-body">
                <p class="cd-job-description">
                    Are you a master of performance marketing and viral social content? As our <strong>Digital Marketing Manager</strong>, you will spearhead paid social media campaigns (Meta Ads, TikTok Ads, Google Search), organic social growth, influencer collaborations, and clearance deal awareness across Kenya.
                    <br><strong style="color:#047857; display:inline-block; margin-top:8px;"><i class="fa-solid fa-hand-holding-dollar"></i> Remuneration:</strong> Salary of <strong>US$ 3,000 per month</strong> with performance bonuses tied to ROAS and customer acquisition.
                </p>
                <div class="cd-job-sections">
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-list-check" style="color:#e11d48;"></i> Key Responsibilities</h4>
                        <ul>
                            <li>Plan, execute, and scale high-ROAS paid ad campaigns on Meta (Facebook &amp; Instagram) and TikTok targeting beauty buyers.</li>
                            <li>Coordinate video and photoshoot content in our showroom featuring wig installs, burn tests, and unboxing videos.</li>
                            <li>Drive qualified traffic to clearance.co.ke and optimize our direct-to-WhatsApp conversion funnel.</li>
                            <li>Manage influencer partnerships, micro-creator seeding, and community engagement on social channels.</li>
                        </ul>
                    </div>
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-graduation-cap" style="color:#0284c7;"></i> Requirements &amp; Qualifications</h4>
                        <ul>
                            <li>Bachelor's degree in Marketing, Communications, Digital Media, or equivalent practical portfolio.</li>
                            <li>3+ years managing significant digital ad budgets (KSh 500k+ monthly) with proven return on ad spend (ROAS).</li>
                            <li>Deep familiarity with the Kenyan beauty &amp; hair culture, viral trends, and creative formats.</li>
                            <li>Proficiency with Meta Ads Manager, TikTok Ads, Google Analytics, Canva/CapCut, and WordPress SEO.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

        <!-- 5. Account Assistant -->
        <article class="cd-job-card" id="job-cdke2026-33">
            <div class="cd-job-header">
                <div class="cd-job-title-group">
                    <h3>5. Account Assistant <span class="cd-job-ref">Ref: CDKE2026/33</span></h3>
                    <div class="cd-job-tags">
                        <span class="cd-job-salary"><i class="fa-solid fa-money-bill-wave"></i> US$ 2,200 / month</span>
                        <span><i class="fa-solid fa-briefcase" style="color:#e11d48;"></i> Full-Time</span>
                        <span><i class="fa-solid fa-location-dot" style="color:#0284c7;"></i> Parklands Office, Nairobi</span>
                        <span><i class="fa-solid fa-clock" style="color:#f59e0b;"></i> Mon – Sat</span>
                        <span><i class="fa-solid fa-calculator" style="color:#10b981;"></i> Accounting &amp; Reconciliation</span>
                    </div>
                </div>
                <button type="button" class="cd-job-apply-btn" onclick="openApplyModal('CDKE2026/33', 'Account Assistant')">
                    Apply with CV <i class="fa-brands fa-whatsapp"></i>
                </button>
            </div>
            <div class="cd-job-body">
                <p class="cd-job-description">
                    The <strong>Account Assistant</strong> will support our financial health by maintaining daily bookkeeping, M-Pesa till / PesaPal payment reconciliations, Lipa Polepole instalment tracking, and invoice management.
                    <br><strong style="color:#047857; display:inline-block; margin-top:8px;"><i class="fa-solid fa-hand-holding-dollar"></i> Remuneration:</strong> Salary of <strong>US$ 2,200 per month</strong> with statutory benefits.
                </p>
                <div class="cd-job-sections">
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-list-check" style="color:#e11d48;"></i> Key Responsibilities</h4>
                        <ul>
                            <li>Perform daily reconciliations of Lipa na M-Pesa paybill/till statements against WooCommerce orders and rider remittances.</li>
                            <li>Track and follow up on customer Lipa Polepole (50% BNPL) instalment schedules and customer account ledgers.</li>
                            <li>Prepare supplier invoices, utility vouchers, payroll schedules, and petty cash disbursements.</li>
                            <li>Assist external auditors and ensure prompt VAT / withholding tax filings on KRA iTax.</li>
                        </ul>
                    </div>
                    <div class="cd-job-section">
                        <h4><i class="fa-solid fa-graduation-cap" style="color:#0284c7;"></i> Requirements &amp; Qualifications</h4>
                        <ul>
                            <li>CPA Part II or Bachelor of Commerce (Finance/Accounting option) / Diploma in Accounting.</li>
                            <li>2+ years accounting experience in an e-commerce, retail store, or wholesale business setting.</li>
                            <li>High proficiency in QuickBooks / ERP software and advanced Microsoft Excel skills (VLOOKUP, Pivot Tables).</li>
                            <li>Uncompromising integrity, attention to detail, and sound understanding of KRA compliance requirements.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </article>

    </div>

    <!-- Application Instructions Banner -->
    <div class="cd-apply-instructions" id="how-to-apply">
        <h3><i class="fa-solid fa-briefcase"></i> How to Apply &amp; Inquiries</h3>
        <p>
            To apply, click <strong>"Apply with CV"</strong> on any position above to attach your CV and connect directly with our hiring desk via WhatsApp.
        </p>
        <div class="cd-apply-channels">
            <a href="https://wa.me/254751338975?text=Hello%20Clearance%20Kenya%20HR%2C%20I%20am%20inquiring%20about%20the%20open%20job%20positions." target="_blank" rel="noopener" class="cd-apply-channel">
                <i class="fa-brands fa-whatsapp" style="color:#16a34a;"></i>
                <div>
                    <div>Recruitment Desk WhatsApp</div>
                    <span style="font-size:12px; color:#64748b; font-weight:normal;">+254 751 338 975 (HR Queries)</span>
                </div>
            </a>
            <div class="cd-apply-channel" style="cursor:default;">
                <i class="fa-solid fa-location-dot" style="color:#0284c7;"></i>
                <div>
                    <div>Showroom &amp; Office Drop-in</div>
                    <span style="font-size:12px; color:#64748b; font-weight:normal;">Office 303, Highlight Business Park, 4th Parklands, Nairobi</span>
                </div>
            </div>
        </div>
    </div>
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
            <input type="hidden" id="modalJobRef" name="job_ref" value="">
            <input type="hidden" id="modalJobTitle" name="job_title" value="">

            <div style="background:#f1f5f9; border-radius:10px; padding:12px 16px; margin-bottom:18px; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span style="font-size:11px; text-transform:uppercase; color:#64748b; font-weight:700; display:block;">Applying For</span>
                    <strong id="modalJobDisplayTitle" style="font-size:15px; color:#0f172a;">Job Title</strong>
                </div>
                <span id="modalJobDisplayRef" class="cd-job-ref" style="background:#fff;">CDKE2026/XX</span>
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

    // Reset status
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

// Close on backdrop click
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

// Form submission via AJAX then open WhatsApp
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

            // Open WhatsApp with prefilled message and CV link
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

