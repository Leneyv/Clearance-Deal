/**
 * Clearance Pay Later – add-to-cart payment-choice modal.
 *
 * Fixes:
 *  - Always reads the SALE (now) price, never the struck-through was price.
 *  - Multiplies by quantity before showing the breakdown.
 *  - Variable products: show variation selector before CPL modal.
 *  - Single product page: navigate via URL (reliable across all WC configurations).
 */
(function () {
  'use strict';

  var cfg    = window.cplCart || {};
  var NONCE  = cfg.nonce || '';
  var AJAX   = cfg.ajaxUrl || '';
  var SITE   = (cfg.siteUrl || '/').replace(/\/$/, '');

  var depositPct   = parseFloat(cfg.depositPct)   || 50;
  var interestRate = parseFloat(cfg.interestRate) || 12;
  var months       = parseInt(cfg.months, 10)     || 12;

  // ----------------------------------------------------------------
  // Calculation
  // ----------------------------------------------------------------
  function calcPlan(total) {
    total    = parseFloat(total) || 0;
    var dep  = round2(total * depositPct / 100);
    var bal  = round2(total - dep);
    var int  = round2(bal * interestRate / 100);
    var rep  = round2(bal + int);
    var mth  = round2(rep / months);
    var last = round2(rep - mth * (months - 1));
    return { total: total, deposit: dep, monthly: mth, last: last };
  }

  function round2(n) { return Math.round(n * 100) / 100; }

  function fmt(n) {
    return 'KES ' + n.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  // ----------------------------------------------------------------
  // Price reader – always returns the CURRENT (discounted) price.
  // ----------------------------------------------------------------
  function getSalePrice(container) {
    var scope = container || document;

    var insEl = scope.querySelector('ins .woocommerce-Price-amount bdi') ||
                scope.querySelector('ins .woocommerce-Price-amount');
    if (insEl) return parsePriceText(insEl.textContent);

    var all = scope.querySelectorAll('.woocommerce-Price-amount bdi, .woocommerce-Price-amount');
    for (var i = 0; i < all.length; i++) {
      if (!all[i].closest('del')) {
        return parsePriceText(all[i].textContent);
      }
    }

    return 0;
  }

  function parsePriceText(text) {
    var cleaned = (text || '').replace(/[^\d.]/g, '');
    var parts = cleaned.split('.');
    if (parts.length > 2) {
      cleaned = parts.slice(0, -1).join('') + '.' + parts[parts.length - 1];
    }
    return parseFloat(cleaned) || 0;
  }

  function escHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  // ----------------------------------------------------------------
  // DOM references – CPL modal
  // ----------------------------------------------------------------
  var _buyNowHref  = null;
  var _modalCtx    = null;

  var overlay    = document.getElementById('cpl-cart-overlay');
  if (!overlay) return;

  var fullPrice  = document.getElementById('cpl-full-price');
  var depPrice   = document.getElementById('cpl-deposit-price');
  var instalLine = document.getElementById('cpl-instalment-line');
  var radios     = overlay.querySelectorAll('.cpl-plan-radio');
  var continueBtn = document.getElementById('cpl-cart-continue-btn');
  var optFull    = document.getElementById('cpl-plan-option-full');
  var optDep     = document.getElementById('cpl-plan-option-deposit');

  // ----------------------------------------------------------------
  // DOM references – Variation selector overlay
  // ----------------------------------------------------------------
  var varOverlay = document.getElementById('cpl-var-overlay');
  var varList    = document.getElementById('cpl-var-list');
  var varConfirm = document.getElementById('cpl-var-confirm');
  var varClose   = document.getElementById('cpl-var-close');

  // ----------------------------------------------------------------
  // Populate CPL modal prices
  // ----------------------------------------------------------------
  function populate(total) {
    var p = calcPlan(total);

    if (fullPrice)  fullPrice.textContent  = fmt(total);
    if (depPrice)   depPrice.textContent   = fmt(p.deposit);
    if (instalLine) instalLine.textContent = fmt(p.monthly) + '/month × ' + months;

    radios.forEach(function (r) { r.checked = r.value === 'full'; });
    updateSelection();
  }

  // ----------------------------------------------------------------
  // Selection visuals
  // ----------------------------------------------------------------
  function updateSelection() {
    radios.forEach(function (r) {
      var opt = r.value === 'full' ? optFull : optDep;
      if (opt) opt.classList.toggle('cpl-checked', r.checked);
    });
  }

  radios.forEach(function (r) { r.addEventListener('change', updateSelection); });

  [optFull, optDep].forEach(function (card) {
    if (!card) return;
    card.addEventListener('click', function (e) {
      if (e.target.tagName === 'INPUT') return;
      var radio = card.querySelector('.cpl-plan-radio');
      if (radio) { radio.checked = true; updateSelection(); }
    });
  });

  // ----------------------------------------------------------------
  // Open / close CPL modal
  // ----------------------------------------------------------------
  function openModal(total) {
    populate(total);
    overlay.hidden = false;
    document.body.classList.add('cpl-cart-open');
    continueBtn && continueBtn.focus();
    trapFocus(overlay);
  }

  function closeModal() {
    overlay.hidden = true;
    document.body.classList.remove('cpl-cart-open');
    releaseFocus();
  }

  // ----------------------------------------------------------------
  // Continue button: save choice via AJAX + plain cookie, then close
  // ----------------------------------------------------------------
  continueBtn && continueBtn.addEventListener('click', function () {
    var chosen = 'full';
    radios.forEach(function (r) { if (r.checked) chosen = r.value; });

    document.cookie = 'cpl_plan_choice=' + encodeURIComponent(chosen) +
                      '; path=/; SameSite=Lax';

    var fd = new FormData();
    fd.append('action', 'cpl_save_plan_choice');
    fd.append('nonce', NONCE);
    fd.append('choice', chosen);
    fetch(AJAX, { method: 'POST', body: fd, credentials: 'same-origin' }).catch(function(){});

    closeModal();
    document.dispatchEvent(new CustomEvent('cplPlanChosen', { detail: { choice: chosen } }));
  });

  // ----------------------------------------------------------------
  // Variation selector
  // ----------------------------------------------------------------
  function openVariationSelector(variations, onSelect) {
    if (!varOverlay || !varList) return;

    varList.innerHTML = '';
    variations.forEach(function (v, idx) {
      var label = document.createElement('label');
      label.className = 'cpl-var-option';

      var radio = document.createElement('input');
      radio.type  = 'radio';
      radio.name  = 'cpl_variation';
      radio.value = String(v.id);
      radio.setAttribute('data-price', String(v.price));
      if (idx === 0) radio.checked = true;

      var labelSpan = document.createElement('span');
      labelSpan.className   = 'cpl-var-label';
      labelSpan.textContent = v.label;

      var priceSpan = document.createElement('span');
      priceSpan.className   = 'cpl-var-price';
      priceSpan.textContent = fmt(v.price);

      label.appendChild(radio);
      label.appendChild(labelSpan);
      label.appendChild(priceSpan);
      varList.appendChild(label);

      // Visual checked state
      radio.addEventListener('change', function () {
        varList.querySelectorAll('.cpl-var-option').forEach(function (el) {
          el.classList.remove('cpl-var-checked');
        });
        label.classList.add('cpl-var-checked');
      });
      if (idx === 0) label.classList.add('cpl-var-checked');
    });

    varOverlay.removeAttribute('hidden');
    document.body.classList.add('cpl-cart-open');

    // Confirm
    var confirmHandler = function () {
      var checked = varList.querySelector('input[type="radio"]:checked');
      if (!checked) return;
      var variationId = parseInt(checked.value, 10);
      var price       = parseFloat(checked.getAttribute('data-price')) || 0;
      closeVariationSelector();
      onSelect(variationId, price);
    };

    // Close
    var closeHandler = function () {
      closeVariationSelector();
    };

    if (varConfirm) {
      varConfirm.onclick = confirmHandler;
    }
    if (varClose) {
      varClose.onclick = closeHandler;
    }
    varOverlay.onclick = function (e) {
      if (e.target === varOverlay) closeHandler();
    };
  }

  function closeVariationSelector() {
    if (!varOverlay) return;
    varOverlay.setAttribute('hidden', '');
    document.body.classList.remove('cpl-cart-open');
  }

  // ----------------------------------------------------------------
  // SHOP / ARCHIVE pages: AJAX add-to-cart
  // ----------------------------------------------------------------
  function bindAjaxAddToCart() {
    if (!window.jQuery) return;

    jQuery(document.body).on('added_to_cart', function (e, fragments, hash, $button) {
      var btn  = $button && $button[0];
      var qty  = parseInt((btn && btn.getAttribute('data-quantity')) || '1', 10) || 1;

      var card = btn && (
        btn.closest('li.product') ||
        btn.closest('.product')   ||
        btn.closest('article')
      );

      var unitPrice = getSalePrice(card || document);
      var total     = round2(unitPrice * qty);

      if (total > 0) { _modalCtx = 'ajax'; openModal(total); }
    });
  }

  // ----------------------------------------------------------------
  // SINGLE PRODUCT page: intercept form submit and navigate via
  // ?add-to-cart=ID URL so the server adds product and redirects
  // to checkout directly.
  // ----------------------------------------------------------------
  function bindSingleProduct() {
    var form = document.querySelector('form.cart');
    if (!form) return;

    form.addEventListener('submit', function (e) {
      var variationInput = form.querySelector('input[name="variation_id"]');
      if (variationInput && (!variationInput.value || parseInt(variationInput.value, 10) < 1)) {
        return;
      }

      e.preventDefault();

      var variationId = variationInput ? parseInt(variationInput.value, 10) : 0;
      var productIdEl = form.querySelector('[name="add-to-cart"], [name="product_id"]');
      var productId   = productIdEl ? parseInt(productIdEl.value, 10) : 0;
      var addId       = (variationId > 0) ? variationId : productId;
      var qtyInput    = form.querySelector('[name="quantity"]');
      var qtyVal      = (qtyInput && qtyInput.value) ? qtyInput.value : '1';

      if (addId) {
        window.location.href = SITE + '/?add-to-cart=' + addId + '&quantity=' + qtyVal;
      } else {
        form.submit();
      }
    });
  }

  // ----------------------------------------------------------------
  // Buy Now button (.btn-get-deal): add to cart and go straight to
  // checkout. Variable products first show the variation selector.
  // ----------------------------------------------------------------
  function bindBuyNowButton() {
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.btn-get-deal');
      if (!btn || btn.classList.contains('is-disabled')) return;

      var href = btn.getAttribute('href');
      if (!href || href === '#') return;

      e.preventDefault();

      var productType = btn.getAttribute('data-product-type');
      var variationsRaw = btn.getAttribute('data-variations');
      var variationsData = null;

      if (variationsRaw) {
        try { variationsData = JSON.parse(variationsRaw); } catch (ex) {}
      }

      if (productType === 'variable' && variationsData && variationsData.length > 0) {
        openVariationSelector(variationsData, function (variationId) {
          window.location.href = SITE + '/?add-to-cart=' + variationId;
        });
        return;
      }

      // Simple product: navigate directly to add-to-cart URL → checkout
      window.location.href = href;
    });
  }

  // ----------------------------------------------------------------
  // Shop / Category page: intercept WooCommerce AJAX add-to-cart
  // buttons before WooCommerce's own handler fires (capture phase),
  // then navigate directly via ?add-to-cart= URL → checkout.
  // ----------------------------------------------------------------
  function bindShopAddToCart() {
    document.body.addEventListener('click', function (e) {
      var btn = e.target.closest('.ajax_add_to_cart:not(.wc-interactive)');
      if (!btn) return;

      var productId = btn.getAttribute('data-product_id');
      if (!productId) return;

      e.preventDefault();
      e.stopImmediatePropagation();

      window.location.href = SITE + '/?add-to-cart=' + productId;
    }, true);
  }

  // ----------------------------------------------------------------
  // Init
  // ----------------------------------------------------------------
  function init() {
    bindAjaxAddToCart();
    bindSingleProduct();
    bindBuyNowButton();
    bindShopAddToCart();

    document.addEventListener('cplPlanChosen', function () {
      var ctx = _modalCtx;
      _modalCtx = null;

      if (ctx === 'ajax') {
        window.location.href = cfg.checkoutUrl;

      } else if (ctx === 'buynow' && _buyNowHref) {
        var href = _buyNowHref;
        _buyNowHref = null;
        window.location.href = href;
      }
      // ctx === 'form': onContinue listener in bindSingleProduct handles it.
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  // ----------------------------------------------------------------
  // Focus trap
  // ----------------------------------------------------------------
  var _focusableQuery = 'a[href],button:not([disabled]),input,select,[tabindex]:not([tabindex="-1"])';
  var _firstFocusable, _lastFocusable, _focusHandler;

  function trapFocus(container) {
    var focusable = Array.from(container.querySelectorAll(_focusableQuery));
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

}());

// =============================================================================
// Mini-cart panel + checkout item-remove
// =============================================================================
(function () {
  'use strict';

  var cfg        = window.cplCart || {};
  var AJAX       = cfg.ajaxUrl   || '';
  var CART_NONCE = cfg.cartNonce || '';
  var CHECKOUT   = cfg.checkoutUrl || '/checkout/';

  var panel    = document.getElementById('cpl-mini-cart-panel');
  var backdrop = document.getElementById('cpl-mini-cart-backdrop');
  var bodyEl   = document.getElementById('cpl-mini-cart-body');
  var closeBtn = document.getElementById('cpl-mini-cart-close');

  // ----------------------------------------------------------------
  // Inject cart icon into .header-actions (before .nav-toggle)
  // ----------------------------------------------------------------
  function injectCartIcon() {
    var iconBtn = document.getElementById('cpl-cart-icon-btn');
    if (!iconBtn) return;
    var actions = document.querySelector('.header-actions');
    if (!actions) return;
    var toggle = actions.querySelector('.nav-toggle');
    if (toggle) {
      actions.insertBefore(iconBtn, toggle);
    } else {
      actions.appendChild(iconBtn);
    }
  }

  // ----------------------------------------------------------------
  // Open / close — simple class toggle, no animations
  // ----------------------------------------------------------------
  function openMiniCart() {
    if (!panel) return;
    panel.classList.add('cpl-mc-open');
    if (backdrop) backdrop.classList.add('cpl-mc-open');
    document.body.classList.add('cpl-no-scroll');
    var iconBtn = document.getElementById('cpl-cart-icon-btn');
    if (iconBtn) iconBtn.setAttribute('aria-expanded', 'true');
    loadMiniCart();
  }

  function closeMiniCart() {
    if (!panel) return;
    panel.classList.remove('cpl-mc-open');
    if (backdrop) backdrop.classList.remove('cpl-mc-open');
    document.body.classList.remove('cpl-no-scroll');
    var iconBtn = document.getElementById('cpl-cart-icon-btn');
    if (iconBtn) iconBtn.setAttribute('aria-expanded', 'false');
  }

  // ----------------------------------------------------------------
  // Load mini-cart HTML via AJAX
  // ----------------------------------------------------------------
  function loadMiniCart() {
    if (!bodyEl) return;
    bodyEl.innerHTML = '<div class="cpl-mc-spinner" aria-hidden="true"></div>';

    var fd = new FormData();
    fd.append('action', 'cpl_get_mini_cart');
    fd.append('nonce', CART_NONCE);

    fetch(AJAX, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          bodyEl.innerHTML = data.data.html;
          updateCount(data.data.count);
          updateFooter(data.data.count);
        } else {
          bodyEl.innerHTML = '<p class="cpl-mc-empty"><a href="' + CHECKOUT + '">Go to checkout &rarr;</a></p>';
        }
      })
      .catch(function () {
        bodyEl.innerHTML = '<p class="cpl-mc-empty"><a href="' + CHECKOUT + '">Go to checkout &rarr;</a></p>';
      });
  }

  // ----------------------------------------------------------------
  // Update the cart count badge
  // ----------------------------------------------------------------
  function updateCount(n) {
    var badge = document.getElementById('cpl-cart-count');
    if (!badge) return;
    var count = parseInt(n, 10) || 0;
    badge.textContent = count > 0 ? String(count) : '';
  }

  // ----------------------------------------------------------------
  // Show/hide the Proceed to Checkout footer
  // ----------------------------------------------------------------
  function updateFooter(n) {
    var footer = document.getElementById('cpl-mini-cart-footer');
    if (!footer) return;
    footer.classList.toggle('cpl-mc-empty-footer', !parseInt(n, 10));
  }

  // ----------------------------------------------------------------
  // Event delegation on panel: handles × remove and +/- quantity
  // ----------------------------------------------------------------
  function bindPanelDelegation() {
    if (!panel) return;
    panel.addEventListener('click', function (e) {
      var btn;

      // Remove item
      btn = e.target.closest('.cpl-mc-remove');
      if (btn && !btn.disabled) {
        e.preventDefault();
        btn.disabled = true;
        var fd = new FormData();
        fd.append('action', 'cpl_remove_cart_item');
        fd.append('nonce',  btn.getAttribute('data-nonce') || CART_NONCE);
        fd.append('key',    btn.getAttribute('data-key'));
        fetch(AJAX, { method: 'POST', body: fd, credentials: 'same-origin' })
          .then(function (r) { return r.json(); })
          .then(function (data) {
            if (data.success && bodyEl) {
              bodyEl.innerHTML = data.data.html;
              updateCount(data.data.count);
              updateFooter(data.data.count);
            } else {
              btn.disabled = false;
            }
          })
          .catch(function () { btn.disabled = false; });
        return;
      }

      // Decrease quantity
      btn = e.target.closest('.cpl-mc-minus');
      if (btn) {
        var wrap  = btn.closest('.cpl-mc-qty-wrap');
        var qtyEl = wrap && wrap.querySelector('.cpl-mc-qty');
        var qty   = Math.max(0, parseInt(qtyEl ? qtyEl.textContent : '1', 10) - 1);
        doCartQty(btn.getAttribute('data-key'), btn.getAttribute('data-nonce'), qty);
        return;
      }

      // Increase quantity
      btn = e.target.closest('.cpl-mc-plus');
      if (btn) {
        var wrap  = btn.closest('.cpl-mc-qty-wrap');
        var qtyEl = wrap && wrap.querySelector('.cpl-mc-qty');
        var qty   = parseInt(qtyEl ? qtyEl.textContent : '1', 10) + 1;
        doCartQty(btn.getAttribute('data-key'), btn.getAttribute('data-nonce'), qty);
        return;
      }
    });
  }

  // ----------------------------------------------------------------
  // AJAX: update quantity
  // ----------------------------------------------------------------
  function doCartQty(key, nonce, qty) {
    if (!bodyEl) return;
    var fd = new FormData();
    fd.append('action', 'cpl_update_cart_qty');
    fd.append('nonce',  nonce || CART_NONCE);
    fd.append('key',    key);
    fd.append('qty',    qty);

    fetch(AJAX, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          bodyEl.innerHTML = data.data.html;
          updateCount(data.data.count);
          updateFooter(data.data.count);
        }
      })
      .catch(function () {});
  }

  // ----------------------------------------------------------------
  // AJAX: remove item (checkout page only)
  // ----------------------------------------------------------------
  function doCartRemove(key, nonce) {
    var fd = new FormData();
    fd.append('action', 'cpl_remove_cart_item');
    fd.append('nonce',  nonce || CART_NONCE);
    fd.append('key',    key);

    fetch(AJAX, { method: 'POST', body: fd, credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        if (data.success) {
          updateCount(data.data.count);
          if (window.jQuery) jQuery(document.body).trigger('update_checkout');
        }
      })
      .catch(function () {});
  }

  // ----------------------------------------------------------------
  // Checkout: delegated handler for .cpl-checkout-remove buttons
  // ----------------------------------------------------------------
  function bindCheckoutRemove() {
    if (!cfg.isCheckout) return;
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.cpl-checkout-remove');
      if (!btn || btn.disabled) return;
      e.preventDefault();
      btn.disabled    = true;
      btn.textContent = (cfg.i18n && cfg.i18n.removing) ? cfg.i18n.removing : 'Removing…';
      doCartRemove(btn.getAttribute('data-key'), btn.getAttribute('data-nonce'));
    });
  }

  // ----------------------------------------------------------------
  // Init
  // ----------------------------------------------------------------
  function init() {
    injectCartIcon();
    bindPanelDelegation();
    bindCheckoutRemove();

    var iconBtn = document.getElementById('cpl-cart-icon-btn');
    if (iconBtn) {
      iconBtn.addEventListener('click', function () {
        window.location.href = cfg.checkoutUrl;
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

}());
