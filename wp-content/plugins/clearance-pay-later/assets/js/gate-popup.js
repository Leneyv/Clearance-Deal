/**
 * Clearance Pay Later – gate popup.
 * No jQuery. Vanilla JS, lightweight.
 */
(function () {
  'use strict';

  var cfg     = window.cplGate || {};
  var delay   = (cfg.checkDelay || 8) * 1000;
  var SESSION_KEY = 'cpl_eligible';

  // ----------------------------------------------------------------
  // Should we show the popup?
  // ----------------------------------------------------------------
  function shouldShow() {
    if (cfg.popupFrequency === 'every_page') return true;
    // once_per_visit: use sessionStorage (cleared on browser close)
    return !sessionStorage.getItem(SESSION_KEY);
  }

  // ----------------------------------------------------------------
  // Elements
  // ----------------------------------------------------------------
  var overlay      = document.getElementById('cpl-gate-overlay');
  if (!overlay) return;  // template not rendered (bot/admin guard)

  var screenPhone    = document.getElementById('cpl-screen-phone');
  var screenChecking = document.getElementById('cpl-screen-checking');
  var screenResult   = document.getElementById('cpl-screen-result');
  var phoneInput     = document.getElementById('cpl-phone-input');
  var checkBtn       = document.getElementById('cpl-check-btn');
  var shopBtn        = document.getElementById('cpl-shop-btn');
  var closeBtn       = document.getElementById('cpl-gate-close');
  var errorEl        = document.getElementById('cpl-phone-error');
  var progressFill   = document.getElementById('cpl-progress-fill');

  // ----------------------------------------------------------------
  // Open / Close
  // ----------------------------------------------------------------
  function openPopup() {
    overlay.classList.add('cpl-visible');
    document.body.classList.add('cpl-gate-open');
    // Move focus to phone input when screen-phone is active.
    setTimeout(function () {
      if (phoneInput) phoneInput.focus();
    }, 300);
    trapFocus(overlay);
  }

  function closePopup() {
    overlay.classList.remove('cpl-visible');
    document.body.classList.remove('cpl-gate-open');
    releaseFocus();
  }

  function dismissPopup() {
    // Mark as seen so the popup doesn't reappear this session.
    sessionStorage.setItem(SESSION_KEY, '1');
    closePopup();
  }

  // ----------------------------------------------------------------
  // Screen switching
  // ----------------------------------------------------------------
  function showScreen(id) {
    [screenPhone, screenChecking, screenResult].forEach(function (el) {
      if (!el) return;
      el.hidden = el.id !== id;
    });
  }

  // ----------------------------------------------------------------
  // Phone validation  (mirrors PHP CPL_Calculator::normalise_phone)
  // ----------------------------------------------------------------
  function normalisePhone(raw) {
    var digits = raw.replace(/\D/g, '');
    // 07XX… or 01XX… (10 digits)
    if (digits.length === 10 && digits.charAt(0) === '0') {
      digits = '254' + digits.slice(1);
    }
    // 7XX… or 1XX… (9 digits)
    if (digits.length === 9 && (digits.charAt(0) === '7' || digits.charAt(0) === '1')) {
      digits = '254' + digits;
    }
    if (digits.length !== 12) return null;
    if (!/^254[71]\d{8}$/.test(digits)) return null;
    return digits;
  }

  function showError(msg) {
    if (!errorEl) return;
    errorEl.textContent = msg;
    errorEl.hidden = false;
    phoneInput && phoneInput.setAttribute('aria-invalid', 'true');
  }
  function clearError() {
    if (!errorEl) return;
    errorEl.textContent = '';
    errorEl.hidden = true;
    phoneInput && phoneInput.removeAttribute('aria-invalid');
  }

  // ----------------------------------------------------------------
  // AJAX save phone (fire-and-forget while checking animation plays)
  // ----------------------------------------------------------------
  function savePhone(normalised) {
    var fd = new FormData();
    fd.append('action', 'cpl_save_phone');
    fd.append('nonce', cfg.nonce || '');
    fd.append('phone', normalised);

    fetch(cfg.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' })
      .catch(function () {/* non-critical */ });

    // Store the phone in a session cookie so the cart can show it.
    // A session cookie (no expiry) is cleared when the browser closes.
    document.cookie = 'cpl_phone=' + encodeURIComponent(normalised) + '; path=/; SameSite=Lax';
  }

  // ----------------------------------------------------------------
  // Checking animation (progress bar over `delay` ms)
  // ----------------------------------------------------------------
  function startChecking(normalised) {
    showScreen('cpl-screen-checking');

    var start = Date.now();
    var raf;

    function tick() {
      var elapsed = Date.now() - start;
      var pct     = Math.min(100, (elapsed / delay) * 100);
      if (progressFill) progressFill.style.width = pct + '%';
      if (elapsed < delay) {
        raf = requestAnimationFrame(tick);
      } else {
        finishCheck();
      }
    }
    raf = requestAnimationFrame(tick);

    // Save phone in the background – don't wait for it.
    savePhone(normalised);

    function finishCheck() {
      cancelAnimationFrame(raf);
      // Every valid phone is eligible.
      sessionStorage.setItem(SESSION_KEY, '1');
      showScreen('cpl-screen-result');
    }
  }

  // ----------------------------------------------------------------
  // Check button handler
  // ----------------------------------------------------------------
  function onCheck() {
    clearError();
    var raw       = phoneInput ? phoneInput.value.trim() : '';
    var normalised = normalisePhone(raw);

    if (!normalised) {
      showError((cfg.i18n && cfg.i18n.invalidPhone) || 'Please enter a valid Kenyan phone number.');
      phoneInput && phoneInput.focus();
      return;
    }

    startChecking(normalised);
  }

  // ----------------------------------------------------------------
  // Shop Now button
  // ----------------------------------------------------------------
  function onShopNow() {
    closePopup();
    if (cfg.homeUrl && window.location.href !== cfg.homeUrl) {
      window.location.href = cfg.homeUrl;
    }
  }

  // ----------------------------------------------------------------
  // "Change number" link (in cart notice)
  // ----------------------------------------------------------------
  function bindChangeNumber() {
    var link = document.getElementById('cpl-change-number-link');
    if (!link) return;
    link.addEventListener('click', function (e) {
      e.preventDefault();
      sessionStorage.removeItem(SESSION_KEY);
      showScreen('cpl-screen-phone');
      openPopup();
    });
  }

  // ----------------------------------------------------------------
  // Focus trap
  // ----------------------------------------------------------------
  var _focusableQuery = 'a[href],button:not([disabled]),input,select,textarea,[tabindex]:not([tabindex="-1"])';
  var _firstFocusable, _lastFocusable, _focusHandler;

  function trapFocus(container) {
    var focusable = Array.from(container.querySelectorAll(_focusableQuery)).filter(function (el) {
      return !el.closest('[hidden]');
    });
    _firstFocusable = focusable[0];
    _lastFocusable  = focusable[focusable.length - 1];

    _focusHandler = function (e) {
      if (e.key !== 'Tab') return;
      if (e.shiftKey) {
        if (document.activeElement === _firstFocusable) {
          e.preventDefault();
          _lastFocusable && _lastFocusable.focus();
        }
      } else {
        if (document.activeElement === _lastFocusable) {
          e.preventDefault();
          _firstFocusable && _firstFocusable.focus();
        }
      }
    };
    document.addEventListener('keydown', _focusHandler);
  }

  function releaseFocus() {
    if (_focusHandler) document.removeEventListener('keydown', _focusHandler);
  }

  // ----------------------------------------------------------------
  // Enter key in phone field
  // ----------------------------------------------------------------
  phoneInput && phoneInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter') {
      e.preventDefault();
      onCheck();
    }
  });

  // ESC key also dismisses via the close button path.
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && overlay.classList.contains('cpl-visible')) {
      dismissPopup();
    }
  });

  // ----------------------------------------------------------------
  // Wire up handlers
  // ----------------------------------------------------------------
  checkBtn  && checkBtn.addEventListener('click', onCheck);
  shopBtn   && shopBtn.addEventListener('click', onShopNow);
  closeBtn  && closeBtn.addEventListener('click', dismissPopup);

  // ----------------------------------------------------------------
  // Init on DOMContentLoaded
  // ----------------------------------------------------------------
  function init() {
    bindChangeNumber();
    if (shouldShow()) {
      openPopup();
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

}());
