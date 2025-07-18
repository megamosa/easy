/**
 * MagoArab EasYorder Admin Configuration JS
 */
define([
    'jquery',
    'Magento_Ui/js/modal/modal'
], function ($, modal) {
    'use strict';

    return {
        init: function() {
            this.initColorPicker();
            this.initTestEmail();
            this.initDependencies();
        },

        initColorPicker: function() {
            // Initialize color picker for button color
            if ($('#magoarab_easyorder_design_button_color').length) {
                $('#magoarab_easyorder_design_button_color').spectrum({
                    preferredFormat: "hex",
                    showInput: true,
                    showPalette: true,
                    palette: [
                        ['#007cba', '#e74c3c', '#2ecc71', '#f39c12'],
                        ['#9b59b6', '#34495e', '#16a085', '#27ae60']
                    ]
                });
            }
        },

        initTestEmail: function() {
            // Test email functionality
            $(document).on('click', '#test-email-btn', function() {
                var email = $('#test_email_address').val();
                if (!email) {
                    alert('Please enter an email address');
                    return;
                }

                $.ajax({
                    url: '/admin/easyorder/config/testemail',
                    type: 'POST',
                    data: {
                        email: email,
                        form_key: FORM_KEY
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Test email sent successfully!');
                        } else {
                            alert('Error sending test email: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error sending test email');
                    }
                });
            });
        },

        initDependencies: function() {
            // Show/hide fields based on dependencies
            var self = this;
            
            // Enable/disable related fields when module is enabled/disabled
            $('#magoarab_easyorder_general_enabled').change(function() {
                var isEnabled = $(this).val() === '1';
                $('.easyorder-dependent-field').toggle(isEnabled);
            }).trigger('change');

            // Show/hide email fields based on email requirement
            $('#magoarab_easyorder_general_require_email').change(function() {
                var requireEmail = $(this).val() === '1';
                $('.email-dependent-field').toggle(requireEmail);
            }).trigger('change');

            // Show/hide customer creation fields
            $('#magoarab_easyorder_general_auto_create_customer').change(function() {
                var autoCreate = $(this).val() === '1';
                $('.customer-creation-field').toggle(autoCreate);
            }).trigger('change');
        }
    };
});

// Initialize when DOM is ready
require(['jquery', 'domReady!'], function($) {
    if (typeof easyorderConfig !== 'undefined') {
        easyorderConfig.init();
    }
});