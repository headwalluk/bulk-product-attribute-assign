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

      // Initialize SelectWoo on filter dropdowns.
      $('.bpaa-selectwoo').selectWoo({
        placeholder: bpaaAdmin.strings.selectFilter || 'Select...',
        allowClear: true,
        width: '100%',
      });
    },

    /**
     * Bind event listeners.
     */
    bindEvents: function () {
      const self = this;

      // Filter toggle.
      $('#bpaa-show-filters').on('change', function () {
        if ($(this).is(':checked')) {
          $('#bpaa-filter-panel').slideDown(300);
        } else {
          $('#bpaa-filter-panel').slideUp(300);
        }
        // Recalculate product count when toggle changes.
        self.updateProductCount();
      });

      // Update product count when filters change.
      $('#bpaa-filter-panel').on('change', 'input, select', function () {
        self.updateProductCount();
      });

      // Attribute change - load terms.
      $('#bpaa-attribute').on('change', function () {
        self.loadTerms($(this).val());
      });

      // Form submission.
      $('#bpaa-form').on('submit', function (e) {
        e.preventDefault();
        self.handleSubmit();
      });

      // Reset button.
      $('#bpaa-reset').on('click', function (e) {
        e.preventDefault();
        self.resetForm();
      });

      // Update product count when options change.
      $('input[name="include_virtual"]').on('change', function () {
        self.updateProductCount();
      });
    },

    /**
     * Reset form to default values.
     */
    resetForm: function () {
      // Reset attribute select.
      $('#bpaa-attribute').val('').trigger('change');
      
      // Reset terms select.
      $('#bpaa-terms').empty().append('<option value="">Select an attribute first...</option>').prop('disabled', true).trigger('change');
      
      // Reset mode to add.
      $('input[name="mode"][value="add"]').prop('checked', true);
      
      // Reset options.
      $('input[name="attribute_visible"]').prop('checked', false);
      $('input[name="preview_only"]').prop('checked', false);
      
      // Uncheck filter toggle and hide panel.
      $('#bpaa-show-filters').prop('checked', false);
      $('#bpaa-filter-panel').hide();
      
      // Reset filter values.
      $('#bpaa-product-status').val(['publish']).trigger('change');
      $('#bpaa-categories').val([]).trigger('change');
      $('#bpaa-tags').val([]).trigger('change');
      $('#bpaa-name-search').val('');
      $('input[name="bpaa_include_virtual"]').prop('checked', false);
      $('input[name="bpaa_include_downloadable"]').prop('checked', false);
      
      // Update product count.
      this.updateProductCount();
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
     * Collect filter values from the filter panel.
     */
    getFilterValues: function () {
      return {
        enabled: $('#bpaa-show-filters').is(':checked'),
        product_status: $('#bpaa-product-status').val() || ['publish'],
        categories: $('#bpaa-categories').val() || [],
        tags: $('#bpaa-tags').val() || [],
        name_search: $('#bpaa-name-search').val() || '',
        include_virtual: $('input[name="bpaa_include_virtual"]').is(':checked'),
        include_downloadable: $('input[name="bpaa_include_downloadable"]').is(':checked'),
      };
    },

    /**
     * Update the product count preview.
     */
    updateProductCount: function () {
      const filters = this.getFilterValues();
      const $countDisplay = $('#bpaa-count');

      $countDisplay.text('Calculating...');

      // Make AJAX call to get product count.
      $.ajax({
        url: bpaaAdmin.ajaxUrl,
        type: 'POST',
        data: {
          action: bpaaAdmin.getProductCountAction,
          nonce: bpaaAdmin.productCountNonce,
          filters_enabled: filters.enabled,
          filters: filters,
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
      const attributeVisible = $('input[name="attribute_visible"]').is(':checked');
      const previewOnly = $('input[name="preview_only"]').is(':checked');
      const filters = this.getFilterValues();

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
        attributeVisible: attributeVisible,
        dryRun: previewOnly,
        filters: filters,
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
          filters_enabled: settings.filters.enabled,
          filters: settings.filters,
        },
        success: function (response) {
          if (response.success && response.data) {
            // Initialize processing state with total count and calculated batch size.
            self.processingState = {
              attribute: settings.attribute,
              termIds: settings.termIds,
              mode: settings.mode,
              attributeVisible: settings.attributeVisible,
              filters: settings.filters,
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
          attribute_visible: state.attributeVisible,
          filters_enabled: state.filters.enabled,
          filters: state.filters,
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
