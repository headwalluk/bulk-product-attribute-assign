/**
 * Admin JavaScript for Bulk Assign Product Attributes.
 *
 * @package Bulk_Product_Attribute_Assign
 */

(function ($) {
  'use strict';

  const BPAA = {
    /**
     * Initialize the plugin.
     */
    init: function () {
      this.initSelect2();
      this.bindEvents();
      this.updateProductCount();
    },

    /**
     * Initialize Select2 on attribute and term selects.
     */
    initSelect2: function () {
      $('#bpaa-attribute').select2({
        placeholder: bpaaAdmin.strings.selectAttribute,
        allowClear: true,
        width: '100%',
      });

      $('#bpaa-terms').select2({
        placeholder: bpaaAdmin.strings.selectTerms,
        allowClear: true,
        width: '100%',
      });
    },

    /**
     * Bind event listeners.
     */
    bindEvents: function () {
      const self = this;

      // Attribute change - load terms.
      $('#bpaa-attribute').on('change', function () {
        self.loadTerms($(this).val());
      });

      // Form submission.
      $('#bpaa-form').on('submit', function (e) {
        e.preventDefault();
        self.handleSubmit();
      });

      // Update product count when options change.
      $('input[name="include_virtual"]').on('change', function () {
        self.updateProductCount();
      });
    },

    /**
     * Load terms for the selected attribute.
     */
    loadTerms: function (attributeName) {
      const self = this;
      const $termsSelect = $('#bpaa-terms');

      if (!attributeName) {
        $termsSelect.prop('disabled', true).empty().trigger('change');
        return;
      }

      // Show loading state.
      $termsSelect
        .prop('disabled', true)
        .empty()
        .append($('<option>', { value: '', text: 'Loading terms...' }))
        .trigger('change');

      // Make AJAX call to get terms.
      $.ajax({
        url: bpaaAdmin.ajaxUrl,
        type: 'POST',
        data: {
          action: bpaaAdmin.getTermsAction,
          nonce: bpaaAdmin.termsNonce,
          attribute: attributeName,
        },
        success: function (response) {
          if (response.success && response.data.terms) {
            // Clear and populate select.
            $termsSelect.empty();

            // Add terms as options.
            $.each(response.data.terms, function (index, term) {
              $termsSelect.append(
                $('<option>', {
                  value: term.id,
                  text: term.text,
                })
              );
            });

            // Enable select and trigger change for Select2.
            $termsSelect.prop('disabled', false).trigger('change');
          } else {
            const message = response.data && response.data.message ? response.data.message : 'Failed to load terms.';
            alert(message);
            $termsSelect.empty().prop('disabled', true).trigger('change');
          }
        },
        error: function () {
          alert('Error loading terms. Please try again.');
          $termsSelect.empty().prop('disabled', true).trigger('change');
        },
      });
    },

    /**
     * Update the product count preview.
     */
    updateProductCount: function () {
      const includeVirtual = $('input[name="include_virtual"]').is(':checked');
      const $countDisplay = $('#bpaa-count');

      $countDisplay.text('Calculating...');

      // Make AJAX call to get product count.
      $.ajax({
        url: bpaaAdmin.ajaxUrl,
        type: 'POST',
        data: {
          action: bpaaAdmin.getProductCountAction,
          nonce: bpaaAdmin.productCountNonce,
          include_virtual: includeVirtual
        },
        success: function (response) {
          if (response.success && typeof response.data.count !== 'undefined') {
            $countDisplay.text(response.data.count);
          } else {
            $countDisplay.text('Error');
          }
        },
        error: function () {
          $countDisplay.text('Error');
        }
      });
    },

    /**
     * Handle form submission.
     */
    handleSubmit: function () {
      const self = this;
      const attribute = $('#bpaa-attribute').val();
      const termIds = $('#bpaa-terms').val();
      const mode = $('input[name="mode"]:checked').val();
      const includeVirtual = $('input[name="include_virtual"]').is(':checked');
      const previewOnly = $('input[name="preview_only"]').is(':checked');

      // Validation.
      if (!attribute) {
        alert('Please select a product attribute.');
        return;
      }

      if (!termIds || termIds.length === 0) {
        alert('Please select at least one attribute term.');
        return;
      }

      // Confirmation.
      const message = previewOnly
        ? 'Preview products without making changes?'
        : bpaaAdmin.strings.confirmMessage;

      if (!confirm(message)) {
        return;
      }

      // Get total count and batch size first.
      this.showProgress();
      this.getTotalCount({
        attribute: attribute,
        termIds: termIds,
        mode: mode,
        includeVirtual: includeVirtual,
        dryRun: previewOnly,
      });
    },

    /**
     * Get total count before processing.
     */
    getTotalCount: function (settings) {
      const self = this;

      $.ajax({
        url: bpaaAdmin.ajaxUrl,
        type: 'POST',
        data: {
          action: bpaaAdmin.getTotalCountAction,
          nonce: bpaaAdmin.totalCountNonce,
          include_virtual: settings.includeVirtual,
        },
        success: function (response) {
          if (response.success && response.data) {
            // Initialize processing state with total count and calculated batch size.
            self.processingState = {
              attribute: settings.attribute,
              termIds: settings.termIds,
              mode: settings.mode,
              includeVirtual: settings.includeVirtual,
              dryRun: settings.dryRun,
              offset: 0,
              totalCount: response.data.total_count,
              batchSize: response.data.batch_size,
              totalProcessed: 0,
              totalSuccessful: 0,
              totalFailed: 0,
              errors: [],
              productIds: [],
            };

            // Start batch processing.
            self.processBatch();
          } else {
            const message = response.data && response.data.message ? response.data.message : 'Failed to get product count.';
            alert('Error: ' + message);
          }
        },
        error: function () {
          alert('AJAX error occurred while getting product count.');
        },
      });
    },

    /**
     * Process a single batch.
     */
    processBatch: function () {
      const self = this;
      const state = this.processingState;

      $.ajax({
        url: bpaaAdmin.ajaxUrl,
        type: 'POST',
        data: {
          action: bpaaAdmin.processBatchAction,
          nonce: bpaaAdmin.nonce,
          attribute: state.attribute,
          term_ids: state.termIds,
          mode: state.mode,
          include_virtual: state.includeVirtual,
          dry_run: state.dryRun,
          offset: state.offset,
          batch_size: state.batchSize,
        },
        success: function (response) {
          if (response.success && response.data) {
            const data = response.data;

            // Update totals.
            state.totalProcessed += data.processed;
            state.totalSuccessful += data.successful;
            state.totalFailed += data.failed;
            state.errors = state.errors.concat(data.errors || []);
            state.productIds = state.productIds.concat(data.product_ids || []);

            // Update progress display.
            self.updateProgress(state);

            // If more batches, continue processing.
            if (data.has_more) {
              state.offset = data.next_offset;
              self.processBatch();
            } else {
              // All done - show 100% for a moment before results.
              self.updateProgress(state, true);
              setTimeout(function () {
                self.showResults(state);
              }, 1500);
            }
          } else {
            const message = response.data && response.data.message ? response.data.message : 'Processing failed.';
            alert('Error: ' + message);
            self.showResults(state);
          }
        },
        error: function () {
          alert('AJAX error occurred. Please try again.');
          self.showResults(state);
        },
      });
    },

    /**
     * Update progress display.
     */
    updateProgress: function (state, forceComplete) {
      // Update progress text.
      const text = forceComplete
        ? 'Completed ' + state.totalCount + ' products'
        : 'Processed ' + state.totalProcessed + ' of ' + state.totalCount + ' products...';
      $('#bpaa-progress-text').text(text);

      // Calculate accurate percentage.
      let percentage = state.totalCount > 0 ? Math.floor((state.totalProcessed / state.totalCount) * 100) : 0;
      if (forceComplete) {
        percentage = 100;
      }
      $('#bpaa-progress-bar').css('width', percentage + '%');
    },

    /**
     * Show progress section.
     */
    showProgress: function () {
      // Reset progress state before showing.
      $('#bpaa-progress-text').text('Initializing...');
      $('#bpaa-progress-bar').css('width', '0%');

      // Show/hide sections.
      $('#bpaa-form').hide();
      $('#bpaa-progress').show();
      $('#bpaa-results').hide();
    },

    /**
     * Show results section.
     */
    showResults: function (state) {
      $('#bpaa-progress').hide();
      $('#bpaa-results').show();

      // Build results HTML.
      let html = '<div class="bpaa-results-grid">';

      // Summary stats.
      html += '<div class="bpaa-stat-card">';
      html += '<div class="bpaa-stat-value">' + state.totalProcessed + '</div>';
      html += '<div class="bpaa-stat-label">Products Processed</div>';
      html += '</div>';

      html += '<div class="bpaa-stat-card bpaa-stat-success">';
      html += '<div class="bpaa-stat-value">' + state.totalSuccessful + '</div>';
      html += '<div class="bpaa-stat-label">Successful</div>';
      html += '</div>';

      if (state.totalFailed > 0) {
        html += '<div class="bpaa-stat-card bpaa-stat-error">';
        html += '<div class="bpaa-stat-value">' + state.totalFailed + '</div>';
        html += '<div class="bpaa-stat-label">Failed</div>';
        html += '</div>';
      }

      html += '</div>';

      // Show errors if any.
      if (state.errors && state.errors.length > 0) {
        html += '<div class="bpaa-errors" style="margin-top: 20px;">';
        html += '<h3>Errors:</h3>';
        html += '<ul>';
        state.errors.forEach(function (error) {
          html += '<li>Product #' + error.product_id + ': ' + error.message + '</li>';
        });
        html += '</ul>';
        html += '</div>';
      }

      // Dry run message.
      if (state.dryRun) {
        html += '<div class="notice notice-info" style="margin-top: 20px; padding: 10px;">';
        html += '<p><strong>Preview Mode:</strong> No changes were made to products.</p>';
        html += '</div>';
      }

      $('.bpaa-results-content').html(html);

      // Show "Process Another" button.
      $('#bpaa-form').show();
    },
  };

  // Initialize on document ready.
  $(document).ready(function () {
    BPAA.init();
  });
})(jQuery);
