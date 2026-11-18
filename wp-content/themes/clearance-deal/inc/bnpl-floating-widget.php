<?php
/**
 * Floating BNPL Eligibility Widget
 * Displays a vertical sticky tab on the right side of the screen with a slide-out card.
 *
 * @package Clearance_Deal
 */

defined( 'ABSPATH' ) || exit;

// Do not display BNPL widget on the /jobs/ page (https://clearance.co.ke/jobs/)
$is_jobs_page = is_page( 'jobs' ) || is_page( 251 ) || is_page_template( 'page-jobs.php' );
if ( ! $is_jobs_page && ! empty( $_SERVER['REQUEST_URI'] ) ) {
	$request_path = trim( (string) wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
	if ( 'jobs' === $request_path || 0 === strpos( $request_path, 'jobs/' ) ) {
		$is_jobs_page = true;
	}
}
if ( $is_jobs_page ) {
	return;
}

$ajax_url = admin_url( 'admin-ajax.php' );
$cpl_nonce = wp_create_nonce( 'cpl_save_phone' );
?>

<div id="bnplFloatingWidget" class="bnpl-floating-widget" aria-live="polite">
	<!-- Vertical Sticky Tab (Attached to the left of the card / right edge of screen) -->
	<button type="button" id="bnplStickyTab" aria-expanded="false" aria-controls="bnplCardPanel" class="bnpl-sticky-tab" aria-label="Check your BNPL Eligibility" title="Toggle BNPL Eligibility">
		<span class="bnpl-toggle-badge" id="bnplToggleBadge">
			<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
		</span>
		<span class="bnpl-tab-label">Check your BNPL Eligibility</span>
	</button>

	<!-- Slide-out Card Panel -->
	<div class="bnpl-card-panel" id="bnplCardPanel">
		<!-- Header -->
		<div class="bnpl-panel-header">
			<div class="bnpl-panel-heading">
				<span class="bnpl-badge-icon"><i class="fa-solid fa-bolt"></i></span>
				<span class="bnpl-heading-text">Check your BNPL Eligibility</span>
			</div>
			<button type="button" id="bnplToggleClose" class="bnpl-panel-toggle-btn" aria-label="Close panel" title="Close">
				<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
			</button>
		</div>

		<!-- Panel Content -->
		<div class="bnpl-panel-content">
			<!-- Step 1: Input Phone -->
			<div id="bnplStepInput" class="bnpl-step is-active">
				<p class="bnpl-step-desc">
					Get instant pre-approval to buy today with <strong>50% payment</strong> and pay the balance in easy instalments!
				</p>
				<form id="bnplPhoneForm" onsubmit="event.preventDefault(); window.bnplCheckEligibility();">
					<div class="bnpl-input-group">
						<label for="bnplPhoneInput" class="bnpl-label">Add your phone number:</label>
						<div class="bnpl-phone-box">
							<span class="bnpl-phone-prefix">🇰🇪 +254</span>
							<input
								type="tel"
								id="bnplPhoneInput"
								class="bnpl-phone-input"
								placeholder="7XX XXX XXX"
								inputmode="numeric"
								autocomplete="tel"
								maxlength="13"
								required
							>
						</div>
						<div id="bnplPhoneError" class="bnpl-error-message" style="display:none;">
							Please enter a valid phone number.
						</div>
					</div>
					<button type="submit" id="bnplSubmitBtn" class="bnpl-submit-btn">
						<span>Check Eligibility</span>
						<i class="fa-solid fa-arrow-right"></i>
					</button>
				</form>
			</div>

			<!-- Step 2: Loading State -->
			<div id="bnplStepLoading" class="bnpl-step" style="display:none;">
				<div class="bnpl-spinner-wrapper">
					<div class="bnpl-spinner"></div>
				</div>
				<p class="bnpl-loading-label">
					checking your eligitblity<span class="bnpl-animated-dots">...</span>
				</p>
				<div class="bnpl-progress-track">
					<div class="bnpl-progress-fill" id="bnplProgressFill"></div>
				</div>
			</div>

			<!-- Step 3: Success / Congratulations -->
			<div id="bnplStepSuccess" class="bnpl-step" style="display:none;">
				<div class="bnpl-success-badge">
					<i class="fa-solid fa-circle-check"></i>
				</div>
				<h4 class="bnpl-success-heading">🎉 Approved!</h4>
				<p class="bnpl-success-message" style="font-size: 13px; font-weight: 700; color: #3A0F34; line-height: 1.4; margin-bottom: 14px !important;">
					Congratulation you are eligible for buy now pay later, buy now before deals are over
				</p>
				<button type="button" id="bnplShopNowBtn" class="bnpl-shop-btn">
					<span>Shop Now</span>
					<i class="fa-solid fa-bag-shopping"></i>
				</button>
			</div>
		</div>
	</div>
</div>

<style id="bnpl-floating-widget-styles">
/* ==========================================================================
   BNPL Vertical Sticky Floating Widget
   ========================================================================== */
.bnpl-floating-widget {
	position: fixed;
	right: 0;
	top: 50%;
	transform: translateY(-50%);
	z-index: 99999;
	display: flex;
	align-items: center;
	font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
	-webkit-font-smoothing: antialiased;
}

/* Sticky Vertical Tab */
.bnpl-sticky-tab {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	gap: 8px;
	background: linear-gradient(180deg, #3A0F34 0%, #20071d 100%);
	color: #ffffff;
	border: 1.5px solid #EAAA00;
	border-right: none;
	border-radius: 12px 0 0 12px;
	padding: 14px 6px;
	cursor: pointer;
	box-shadow: -4px 4px 18px rgba(0, 0, 0, 0.2);
	user-select: none;
	-webkit-tap-highlight-color: transparent;
	transition: background 0.2s ease, transform 0.15s ease;
	flex-shrink: 0;
	outline: none;
	position: relative;
	z-index: 2;
}

.bnpl-sticky-tab:hover {
	background: linear-gradient(180deg, #501648 0%, #3A0F34 100%);
}

.bnpl-sticky-tab:active {
	transform: scale(0.97);
}

.bnpl-tab-label {
	writing-mode: vertical-rl;
	transform: rotate(180deg);
	font-size: 11px;
	font-weight: 800;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	white-space: nowrap;
	line-height: 1;
}

.bnpl-toggle-badge {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font-size: 11px;
	font-weight: 900;
	background: #EAAA00;
	color: #3A0F34;
	padding: 2px 5px;
	border-radius: 4px;
	letter-spacing: -0.5px;
	line-height: 1;
}

/* Card Panel */
.bnpl-card-panel {
	width: 290px;
	max-width: calc(100vw - 44px);
	background: #ffffff;
	border-radius: 14px 0 0 14px;
	box-shadow: -8px 8px 30px rgba(0, 0, 0, 0.22);
	border: 1.5px solid #3A0F34;
	border-right: none;
	overflow: hidden;
	display: flex;
	flex-direction: column;
	text-align: left;
	transition: transform 0.32s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.22s ease;
	transform-origin: right center;
	position: relative;
	z-index: 1;
}

/* Open State */
.bnpl-floating-widget.is-open .bnpl-card-panel {
	transform: translateX(0);
	opacity: 1;
	pointer-events: auto;
}

/* Collapsed State: smoothly slide card panel off-screen to the right */
.bnpl-floating-widget:not(.is-open) .bnpl-card-panel {
	transform: translateX(110%);
	opacity: 0;
	pointer-events: none;
	position: absolute;
	right: 0;
}

/* Panel Header */
.bnpl-panel-header {
	background: #3A0F34;
	border-bottom: 1.5px solid #EAAA00;
	padding: 10px 14px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 10px;
}

.bnpl-panel-heading {
	display: flex;
	align-items: center;
	gap: 7px;
	font-size: 12.5px;
	font-weight: 800;
	color: #ffffff;
}

.bnpl-badge-icon {
	color: #EAAA00;
	font-size: 13px;
}

.bnpl-panel-toggle-btn {
	background: #EAAA00;
	color: #3A0F34;
	border: 1px solid #d49b00;
	border-radius: 6px;
	width: 26px;
	height: 26px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font-size: 13px;
	font-weight: 700;
	cursor: pointer;
	line-height: 1;
	transition: all 0.2s ease;
	outline: none;
	padding: 0;
}

.bnpl-panel-toggle-btn:hover {
	background: #3A0F34;
	color: #ffffff;
	border-color: #EAAA00;
}

/* Panel Body */
.bnpl-panel-content {
	padding: 14px 16px;
	background: #ffffff;
}

.bnpl-step-desc {
	font-size: 11.5px;
	color: #475569;
	line-height: 1.45;
	margin-bottom: 12px !important;
}

.bnpl-label {
	display: block;
	font-size: 11.5px;
	font-weight: 700;
	color: #3A0F34;
	margin-bottom: 6px;
}

.bnpl-phone-box {
	display: flex;
	align-items: center;
	border: 1.5px solid #DDE3E8;
	border-radius: 8px;
	overflow: hidden;
	background: #fdfafc;
	transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.bnpl-phone-box:focus-within {
	border-color: #3A0F34;
	background: #ffffff;
	box-shadow: 0 0 0 3px rgba(58, 15, 52, 0.15);
}

.bnpl-phone-prefix {
	font-size: 12px;
	font-weight: 700;
	color: #3A0F34;
	background: #DDE3E8;
	padding: 9px 10px;
	border-right: 1px solid #cbd5e1;
	white-space: nowrap;
	user-select: none;
}

.bnpl-phone-input {
	flex: 1;
	border: none !important;
	outline: none !important;
	padding: 9px 10px !important;
	font-size: 14px !important;
	font-weight: 600 !important;
	color: #3A0F34 !important;
	background: transparent !important;
	width: 100% !important;
	box-shadow: none !important;
}

.bnpl-phone-input::placeholder {
	color: #94a3b8;
	font-weight: 400;
}

.bnpl-error-message {
	color: #dc2626;
	font-size: 11px;
	font-weight: 600;
	margin-top: 5px;
}

.bnpl-submit-btn {
	margin-top: 12px;
	width: 100%;
	background: linear-gradient(135deg, #3A0F34 0%, #20071d 100%);
	color: #ffffff;
	border: 1px solid #EAAA00;
	border-radius: 8px;
	padding: 10px 14px;
	font-size: 13px;
	font-weight: 800;
	letter-spacing: 0.02em;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	box-shadow: 0 3px 10px rgba(58, 15, 52, 0.25);
	transition: all 0.2s ease;
}

.bnpl-submit-btn:hover {
	background: #EAAA00;
	color: #3A0F34;
	border-color: #d49b00;
	box-shadow: 0 4px 14px rgba(234, 170, 0, 0.35);
}

/* Step 2: Loading */
.bnpl-spinner-wrapper {
	display: flex;
	justify-content: center;
	margin: 10px 0 12px;
}

.bnpl-spinner {
	width: 38px;
	height: 38px;
	border: 3.5px solid #DDE3E8;
	border-top-color: #3A0F34;
	border-radius: 50%;
	animation: bnplSpinAnim 0.75s linear infinite;
}

@keyframes bnplSpinAnim {
	to { transform: rotate(360deg); }
}

.bnpl-loading-label {
	font-size: 13px;
	font-weight: 800;
	color: #3A0F34;
	text-align: center;
	margin-bottom: 14px !important;
	text-transform: lowercase;
}

.bnpl-animated-dots {
	display: inline-block;
	animation: bnplDotsAnim 1.4s infinite;
}

@keyframes bnplDotsAnim {
	0%, 20% { opacity: 0; }
	40% { opacity: 0.5; }
	60%, 100% { opacity: 1; }
}

.bnpl-progress-track {
	width: 100%;
	height: 6px;
	background: #DDE3E8;
	border-radius: 999px;
	overflow: hidden;
}

.bnpl-progress-fill {
	height: 100%;
	width: 0%;
	background: linear-gradient(90deg, #3A0F34 0%, #EAAA00 100%);
	border-radius: 999px;
	transition: width 3s linear;
}

/* Step 3: Success */
.bnpl-success-badge {
	text-align: center;
	font-size: 38px;
	color: #EAAA00;
	margin: 4px 0 6px;
	animation: bnplBounceIn 0.4s ease;
}

@keyframes bnplBounceIn {
	0% { transform: scale(0.5); opacity: 0; }
	70% { transform: scale(1.15); }
	100% { transform: scale(1); opacity: 1; }
}

.bnpl-success-heading {
	font-size: 14px;
	font-weight: 800;
	color: #3A0F34;
	text-align: center;
	margin-bottom: 4px;
}

.bnpl-success-message {
	font-size: 12.5px;
	font-weight: 800;
	color: #3A0F34;
	text-align: center;
	margin-bottom: 4px !important;
	line-height: 1.35;
}

.bnpl-success-sub {
	font-size: 11px;
	color: #64748b;
	text-align: center;
	margin-bottom: 12px !important;
	line-height: 1.35;
}

.bnpl-shop-btn {
	width: 100%;
	background: #EAAA00;
	color: #3A0F34 !important;
	border: 1px solid #d49b00;
	border-radius: 8px;
	padding: 10px 14px;
	font-size: 13px;
	font-weight: 800;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	box-shadow: 0 3px 10px rgba(234, 170, 0, 0.25);
	text-decoration: none !important;
	transition: all 0.2s ease;
}

.bnpl-shop-btn:hover {
	background: #3A0F34;
	color: #ffffff !important;
	border-color: #EAAA00;
	box-shadow: 0 4px 14px rgba(58, 15, 52, 0.35);
}

@media (max-width: 480px) {
	.bnpl-card-panel {
		width: 275px;
	}
	.bnpl-tab-label {
		font-size: 10px;
	}
	.bnpl-panel-content {
		padding: 12px 14px;
	}
}
</style>

<script id="bnpl-floating-widget-script">
(function() {
	var widget = document.getElementById('bnplFloatingWidget');
	var tabToggle = document.getElementById('bnplStickyTab');
	var closeToggle = document.getElementById('bnplToggleClose');
	var phoneInput = document.getElementById('bnplPhoneInput');
	var phoneError = document.getElementById('bnplPhoneError');
	var stepInput = document.getElementById('bnplStepInput');
	var stepLoading = document.getElementById('bnplStepLoading');
	var stepSuccess = document.getElementById('bnplStepSuccess');
	var progressFill = document.getElementById('bnplProgressFill');
	var shopNowBtn = document.getElementById('bnplShopNowBtn');

	if (!widget || !tabToggle) return;

	var closeSvg = '<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
	var boltSvg = '<svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>';

	// Toggle function (x button and tab click)
	function toggleWidget() {
		var isOpen = widget.classList.toggle('is-open');
        tabToggle.setAttribute('aria-expanded',String(isOpen));
		var tabBadge = document.getElementById('bnplToggleBadge');
		if (tabBadge) {
			tabBadge.innerHTML = isOpen ? closeSvg : boltSvg;
		}
	}

	tabToggle.addEventListener('click', toggleWidget);
	if (closeToggle) {
		closeToggle.addEventListener('click', function(e) {
			e.stopPropagation();
			toggleWidget();
		});
	}

	// Eligibility Check Action
	window.bnplCheckEligibility = function() {
		if (!phoneInput) return;
		var raw = (phoneInput.value || '').trim().replace(/\s+/g, '');
		if (!raw || raw.length < 8) {
			if (phoneError) {
				phoneError.style.display = 'block';
				phoneError.textContent = 'Please enter your phone number (e.g. 0712345678).';
			}
			phoneInput.focus();
			return;
		}

		if (phoneError) phoneError.style.display = 'none';

		// Switch to Loading Screen
		stepInput.style.display = 'none';
		stepLoading.style.display = 'block';

		// Animate progress bar across 3 seconds
		setTimeout(function() {
			if (progressFill) progressFill.style.width = '100%';
		}, 50);

		// Silently save phone number to database via CPL AJAX
		try {
			var formData = new FormData();
			formData.append('action', 'cpl_save_phone');
			formData.append('nonce', '<?php echo esc_js( $cpl_nonce ); ?>');
			formData.append('phone', raw);
			fetch('<?php echo esc_url( $ajax_url ); ?>', {
				method: 'POST',
				body: formData
			}).catch(function(err) {});
		} catch (e) {}

		// After exactly 3 seconds, show "congragulation you are eligible- shop now"
		setTimeout(function() {
			stepLoading.style.display = 'none';
			stepSuccess.style.display = 'block';
		}, 3000);
	};

	// Shop Now Click Action: scroll to products & collapse widget
	if (shopNowBtn) {
		shopNowBtn.addEventListener('click', function(e) {
			e.preventDefault();
			widget.classList.remove('is-open');

			var target = document.getElementById('mainProductsGrid') ||
			             document.querySelector('.featured-band-grid') ||
			             document.getElementById('price-2') ||
			             document.getElementById('deals-section');
			if (target) {
				target.scrollIntoView({ behavior: 'smooth', block: 'start' });
			} else {
				window.location.href = '<?php echo esc_url( home_url( '/#deals-section' ) ); ?>';
			}
		});
	}
})();
</script>
