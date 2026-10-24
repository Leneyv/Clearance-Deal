/**
 * Clearance Pay Later – admin JS.
 * Handles: agreement status save, mark instalment paid, send email, template load.
 */
(function ($) {
  'use strict';

  var cfg  = window.cplAdmin || {};
  var AJAX = cfg.ajaxUrl || ajaxurl || '';
  var i18n = cfg.i18n    || {};

  // ----------------------------------------------------------------
  // Agreement status
  // ----------------------------------------------------------------
  $(document).on('click', '.cpl-save-agreement-btn', function () {
    var btn     = $(this);
    var orderId = btn.data('order-id');
    var status  = $('#cpl-agreement-status').val();
    var saved   = btn.siblings('.cpl-agreement-saved');

    btn.text(i18n.saving || 'Saving…').prop('disabled', true);

    $.post(AJAX, {
      action:           'cpl_update_agreement',
      nonce:            cfg.nonceAgreement,
      order_id:         orderId,
      agreement_status: status,
    })
    .done(function (res) {
      if (res.success) {
        saved.prop('hidden', false);
        setTimeout(function () { saved.prop('hidden', true); }, 2500);
      } else {
        alert(i18n.error || 'Error. Please try again.');
      }
    })
    .fail(function () { alert(i18n.error || 'Error. Please try again.'); })
    .always(function () { btn.text(i18n.saved || 'Save').prop('disabled', false); });
  });

  // ----------------------------------------------------------------
  // Mark instalment paid
  // ----------------------------------------------------------------
  $(document).on('click', '.cpl-mark-paid-btn', function () {
    var btn        = $(this);
    var orderId    = btn.data('order-id');
    var instalment = btn.data('instalment');

    btn.text('…').prop('disabled', true);

    $.post(AJAX, {
      action:     'cpl_mark_instalment_paid',
      nonce:      cfg.nonceInstalment,
      order_id:   orderId,
      instalment: instalment,
    })
    .done(function (res) {
      if (res.success) {
        var row = $('#cpl-row-' + instalment);
        row.addClass('cpl-row-paid');
        var statusTd = row.find('.cpl-status-pending').closest('td');
        statusTd.html('<span class="cpl-status-paid">&#10003; ' + (i18n.paid || 'Paid') + ' (' + (res.data.paid_date || '') + ')</span>');
        btn.closest('td').html('');
      } else {
        btn.text(i18n.markPaid || 'Mark Paid').prop('disabled', false);
        alert(i18n.error || 'Error. Please try again.');
      }
    })
    .fail(function () {
      btn.text(i18n.markPaid || 'Mark Paid').prop('disabled', false);
      alert(i18n.error || 'Error. Please try again.');
    });
  });

  // ----------------------------------------------------------------
  // Email template loader
  // ----------------------------------------------------------------
  $(document).on('change', '#cpl-email-template', function () {
    var orderId  = $(this).data('order-id');
    var template = $(this).val();

    $.post(AJAX, {
      action:   'cpl_get_email_template',
      nonce:    cfg.nonceAgreement,
      order_id: orderId,
      template: template,
    })
    .done(function (res) {
      if (res.success && res.data) {
        $('#cpl-email-subject').val(res.data.subject || '');
        $('#cpl-email-body').val(res.data.body || '');
      }
    });
  });

  // Load default template on page load.
  if ($('#cpl-email-template').length) {
    $('#cpl-email-template').trigger('change');
  }

  // ----------------------------------------------------------------
  // Send email
  // ----------------------------------------------------------------
  $(document).on('click', '.cpl-send-email-btn', function () {
    var btn     = $(this);
    var orderId = btn.data('order-id');
    var subject = $('#cpl-email-subject').val().trim();
    var body    = $('#cpl-email-body').val().trim();
    var status  = btn.siblings('.cpl-email-status');

    if (!subject || !body) {
      status.text('Subject and message are required.').removeClass('success').addClass('error');
      return;
    }

    btn.prop('disabled', true).text(i18n.saving || 'Sending…');
    status.text('').removeClass('success error');

    $.post(AJAX, {
      action:   'cpl_send_email',
      nonce:    cfg.nonceAgreement,
      order_id: orderId,
      subject:  subject,
      body:     body,
    })
    .done(function (res) {
      if (res.success) {
        status.text(res.data.message || 'Email sent!').addClass('success');
      } else {
        status.text((res.data && res.data.message) || 'Failed.').addClass('error');
      }
    })
    .fail(function () { status.text('Request failed.').addClass('error'); })
    .always(function () { btn.prop('disabled', false).text('Send Email'); });
  });

}(jQuery));
