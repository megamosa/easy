/**
 * MagoArab EasYorder JavaScript
 * 
 * @category    MagoArab
 * @package     MagoArab_EasYorder
 * @author      MagoArab Team
 * @copyright   Copyright (c) 2024 MagoArab (https://magoarab.com)
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */

define([
    'jquery',
    'mage/validation',
    'mage/translate',
    'mage/storage',
    'mage/url',
    'Magento_Ui/js/modal/alert',
    'mage/loader'
], function ($, validation, $t, storage, url, alert, loader) {
    'use strict';

    $.widget('magoarab.easyorder', {
        options: {
            formSelector: '.easyorder-form',
            qtySelector: '.qty-input',
            qtyPlusSelector: '.qty-btn[data-action="plus"]',
            qtyMinusSelector: '.qty-btn[data-action="minus"]',
            submitSelector: '.easyorder-submit',
            totalSelector: '.total-price',
            shippingCost: 15,
            submitUrl: '',
            basePrice: 0,
            productId: null
        },

        _create: function () {
            this._bind();
            this._initializeForm();
            this._updateTotal();
        },

        _bind: function () {
            var self = this;
            
            // Quantity controls
            this.element.on('click', this.options.qtyPlusSelector, function (e) {
                e.preventDefault();
                self._changeQuantity(1);
            });
            
            this.element.on('click', this.options.qtyMinusSelector, function (e) {
                e.preventDefault();
                self._changeQuantity(-1);
            });
            
            this.element.on('change keyup', this.options.qtySelector, function () {
                self._updateTotal();
            });
            
            // Form submission
            this.element.on('submit', this.options.formSelector, function (e) {
                e.preventDefault();
                self._submitOrder();
            });
            
            // Payment method change
            this.element.on('change', 'input[name="payment_method"]', function () {
                self._updateTotal();
            });
            
            // Shipping method change
            this.element.on('change', 'input[name="shipping_method"]', function () {
                self._updateTotal();
            });
            
            // Province change for shipping calculation
            this.element.on('change', 'select[name="province"]', function () {
                self._estimateShipping();
            });
        },

        _initializeForm: function () {
            var form = this.element.find(this.options.formSelector);
            
            // Initialize validation
            form.validation({
                errorClass: 'mage-error',
                errorElement: 'div',
                meta: 'validate',
                ignore: ':hidden:not(select)'
            });
            
            // Add animation classes
            this.element.addClass('fade-in');
        },

        _changeQuantity: function (change) {
            var qtyInput = this.element.find(this.options.qtySelector);
            var currentQty = parseInt(qtyInput.val()) || 1;
            var newQty = currentQty + change;
            
            if (newQty >= 1) {
                qtyInput.val(newQty);
                this._updateTotal();
                
                // Add animation
                qtyInput.addClass('slide-up');
                setTimeout(function () {
                    qtyInput.removeClass('slide-up');
                }, 300);
            }
        },

        _updateTotal: function () {
            var qty = parseInt(this.element.find(this.options.qtySelector).val()) || 1;
            var basePrice = parseFloat(this.options.basePrice) || 0;
            var shippingCost = this._getShippingCost();
            var total = (basePrice * qty) + shippingCost;
            
            this.element.find(this.options.totalSelector).text(
                this._formatPrice(total)
            );
        },

        _getShippingCost: function () {
            var selectedShipping = this.element.find('input[name="shipping_method"]:checked').val();
            
            // Different shipping costs based on method
            switch (selectedShipping) {
                case 'cod':
                    return 15;
                case 'free':
                    return 0;
                case 'express':
                    return 25;
                default:
                    return this.options.shippingCost;
            }
        },

        _formatPrice: function (price) {
            return price.toFixed(2) + ' ' + $t('EGP');
        },

        _estimateShipping: function () {
            var province = this.element.find('select[name="province"]').val();
            var self = this;
            
            if (!province) {
                return;
            }
            
            // Show loading
            this.element.find('.shipping-cost-text').html($t('Calculating shipping...'));
            
            // Estimate shipping cost based on province
            storage.post(
                url.build('easyorder/order/estimate'),
                JSON.stringify({
                    province: province,
                    product_id: this.options.productId
                }),
                true
            ).done(function (response) {
                if (response.success) {
                    self.options.shippingCost = response.shipping_cost;
                    self._updateTotal();
                    self.element.find('.shipping-cost-text').html(
                        $t('Shipping cost: ') + self._formatPrice(response.shipping_cost)
                    );
                } else {
                    self.element.find('.shipping-cost-text').html($t('Unable to calculate shipping'));
                }
            }).fail(function () {
                self.element.find('.shipping-cost-text').html($t('Error calculating shipping'));
            });
        },

        _submitOrder: function () {
            var form = this.element.find(this.options.formSelector);
            var self = this;
            
            // Validate form
            if (!form.validation('isValid')) {
                return;
            }
            
            // Get form data
            var formData = form.serializeArray();
            var data = {};
            
            $.each(formData, function (i, field) {
                data[field.name] = field.value;
            });
            
            // Show loading state
            this._setLoading(true);
            
            // Submit order
            storage.post(
                this.options.submitUrl,
                JSON.stringify(data),
                true
            ).done(function (response) {
                self._setLoading(false);
                
                if (response.success) {
                    self._showSuccess(response.message, response.order_id);
                    self._resetForm();
                } else {
                    self._showError(response.message);
                }
            }).fail(function (xhr) {
                self._setLoading(false);
                
                var errorMessage = $t('Connection error. Please try again.');
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                
                self._showError(errorMessage);
            });
        },

        _setLoading: function (loading) {
            var submitBtn = this.element.find(this.options.submitSelector);
            
            if (loading) {
                submitBtn.addClass('loading').prop('disabled', true);
                this.element.find('.easyorder-form').addClass('loading');
            } else {
                submitBtn.removeClass('loading').prop('disabled', false);
                this.element.find('.easyorder-form').removeClass('loading');
            }
        },

        _showSuccess: function (message, orderId) {
            var successHtml = '<div class="success-message">' +
                '<strong>' + $t('Success!') + '</strong><br>' +
                message + '<br>' +
                (orderId ? $t('Order ID: ') + orderId : '') +
                '</div>';
            
            // Remove existing messages
            this.element.find('.success-message, .error-message').remove();
            
            // Add success message
            this.element.find('.easyorder-actions').after(successHtml);
            
            // Scroll to message
            this._scrollToMessage();
            
            // Auto-hide after 10 seconds
            setTimeout(function () {
                $('.success-message').fadeOut();
            }, 10000);
        },

        _showError: function (message) {
            var errorHtml = '<div class="error-message mage-error" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; border: 1px solid #f5c6cb; margin-top: 15px; text-align: center;">' +
                '<strong>' + $t('Error!') + '</strong><br>' +
                message +
                '</div>';
            
            // Remove existing messages
            this.element.find('.success-message, .error-message').remove();
            
            // Add error message
            this.element.find('.easyorder-actions').after(errorHtml);
            
            // Scroll to message
            this._scrollToMessage();
            
            // Auto-hide after 8 seconds
            setTimeout(function () {
                $('.error-message').fadeOut();
            }, 8000);
        },

        _scrollToMessage: function () {
            var messageElement = this.element.find('.success-message, .error-message');
            if (messageElement.length) {
                $('html, body').animate({
                    scrollTop: messageElement.offset().top - 100
                }, 500);
            }
        },

        _resetForm: function () {
            var form = this.element.find(this.options.formSelector);
            form[0].reset();
            
            // Reset quantity to 1
            this.element.find(this.options.qtySelector).val(1);
            
            // Update total
            this._updateTotal();
            
            // Remove validation classes
            form.find('.mage-error').removeClass('mage-error');
            form.find('.mage-error').remove();
        },

        _destroy: function () {
            this.element.off();
        }
    });

    // Initialize widget on document ready
    $(document).ready(function () {
        $('.magoarab-easyorder-container').each(function () {
            var container = $(this);
            var productId = container.data('product-id');
            var form = container.find('.easyorder-form');
            var basePrice = parseFloat(container.find('.total-price').data('base-price')) || 0;
            
            container.easyorder({
                productId: productId,
                basePrice: basePrice,
                submitUrl: form.data('submit-url') || '/easyorder/order/submit'
            });
        });
    });

    return $.magoarab.easyorder;
});