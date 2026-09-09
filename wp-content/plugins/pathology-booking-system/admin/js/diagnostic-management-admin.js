/**
 * Diagnostic Management SPA JS Controller
 */
(function($) {
    'use strict';

    var PTBS_DiagAdmin = {
        currentTab: 'category',
        currentPage: 1,
        perPage: 15,
        searchQuery: '',
        searchTimer: null,

        init: function() {
            var self = this;

            // Load initial tab from hash or default
            var hash = window.location.hash.replace('#', '');
            if (hash && ['category', 'subcategory', 'condition', 'test', 'package', 'center_location', 'sync'].indexOf(hash) !== -1) {
                self.currentTab = hash;
            }

            self.bindEvents();
            self.loadTabContent(self.currentTab);
        },

        bindEvents: function() {
            var self = this;

            // Tab Switching
            $(document).on('click', '.ptbs-tab-btn', function(e) {
                e.preventDefault();
                var tab = $(this).data('tab');
                if (tab) {
                    self.currentTab = tab;
                    self.currentPage = 1;
                    window.location.hash = tab;
                    $('.ptbs-tab-btn').removeClass('active');
                    $(this).addClass('active');
                    self.loadTabContent(tab);
                }
            });

            // Pagination Click
            $(document).on('click', '.ptbs-page-btn', function(e) {
                e.preventDefault();
                if ($(this).is(':disabled')) return;
                var page = $(this).data('page');
                if (page) {
                    self.currentPage = parseInt(page, 10);
                    self.loadTabContent(self.currentTab);
                }
            });

            // Per Page Change
            $(document).on('change', '#ptbs_per_page_select', function() {
                self.perPage = parseInt($(this).val(), 10);
                self.currentPage = 1;
                self.loadTabContent(self.currentTab);
            });

            // Search Filter Debounced
            $(document).on('input', '#ptbs_table_search', function() {
                var val = $(this).val();
                clearTimeout(self.searchTimer);
                self.searchTimer = setTimeout(function() {
                    self.searchQuery = val;
                    self.currentPage = 1;
                    self.loadTabContent(self.currentTab);
                }, 400);
            });

            // Open Add Drawer
            $(document).on('click', '#ptbs_btn_add_item', function(e) {
                e.preventDefault();
                self.openDrawer(self.currentTab, 0);
            });

            // Open Edit Drawer
            $(document).on('click', '.ptbs-edit-item-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var tab = $(this).data('tab') || self.currentTab;
                self.openDrawer(tab, id);
            });

            // Close Drawer
            $(document).on('click', '.ptbs-drawer-close, .ptbs-drawer-cancel, .ptbs-drawer-overlay', function(e) {
                if ($(e.target).hasClass('ptbs-drawer-overlay') || $(e.target).hasClass('ptbs-drawer-close') || $(e.target).hasClass('ptbs-drawer-cancel')) {
                    self.closeDrawer();
                }
            });

            // Save Form via AJAX
            $(document).on('submit', '#ptbs_drawer_form', function(e) {
                e.preventDefault();
                self.saveForm($(this));
            });

            // Toggle Active/Inactive Status
            $(document).on('change', '.ptbs-status-toggle-input', function() {
                var id = $(this).data('id');
                var type = $(this).data('type');
                var isChecked = $(this).is(':checked');
                var newStatus = isChecked ? 'Active' : 'Inactive';
                self.toggleStatus(id, type, newStatus, $(this));
            });

            // Delete Item
            $(document).on('click', '.ptbs-delete-item-btn', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var type = $(this).data('type');
                if (confirm('Are you sure you want to delete this item?')) {
                    self.deleteItem(id, type);
                }
            });

            // Run Catalog Sync via AJAX
            $(document).on('click', '#ptbs_run_sync_btn', function(e) {
                e.preventDefault();
                var $btn = $(this);
                var $form = $('#ptbs_sync_catalog_form');
                var formData = $form.serialize();
                $btn.prop('disabled', true).text('⏳ Running Sync... Please wait...');

                $.ajax({
                    url: ptbsAdminSettings.ajaxurl,
                    type: 'POST',
                    data: formData + '&action=ptbs_run_catalog_sync&nonce=' + ptbsAdminSettings.nonce,
                    success: function(res) {
                        $btn.prop('disabled', false).text('⚡ Run High-Performance Catalog Sync Now');
                        if (res.success) {
                            self.showToast('success', res.data.message);
                            self.loadTabContent('sync');
                        } else {
                            self.showToast('error', res.data.message || 'Sync failed.');
                        }
                    },
                    error: function() {
                        $btn.prop('disabled', false).text('⚡ Run High-Performance Catalog Sync Now');
                        self.showToast('error', 'Server timeout or error during catalog sync.');
                    }
                });
            });
        },

        loadTabContent: function(tab) {
            var self = this;
            var $container = $('#ptbs_table_container');

            // Update Add Button Text
            var addBtnLabel = '+ Add New Item';
            if (tab === 'category') addBtnLabel = '+ Add New Category';
            else if (tab === 'subcategory') addBtnLabel = '+ Add New Sub Category';
            else if (tab === 'condition') addBtnLabel = '+ Add New Condition';
            else if (tab === 'test') addBtnLabel = '+ Add New Test';
            else if (tab === 'package') addBtnLabel = '+ Add New Package';
            else if (tab === 'center_location') addBtnLabel = '+ Add New Center Location';

            $('#ptbs_btn_add_item').text(addBtnLabel);
            if (tab === 'sync') {
                $('#ptbs_btn_add_item').hide();
            } else {
                $('#ptbs_btn_add_item').show();
            }

            // Skeletal Loader
            $container.html('<div style="padding:40px;"><div class="ptbs-skeleton" style="margin-bottom:12px;"></div><div class="ptbs-skeleton" style="margin-bottom:12px;"></div><div class="ptbs-skeleton"></div></div>');

            $.ajax({
                url: ptbsAdminSettings.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ptbs_get_diagnostic_tab_data',
                    nonce: ptbsAdminSettings.nonce,
                    tab: tab,
                    paged: self.currentPage,
                    per_page: self.perPage,
                    search: self.searchQuery
                },
                success: function(res) {
                    if (res.success) {
                        $container.html(res.data.html);
                        if (res.data.stats) {
                            self.updateStats(res.data.stats);
                        }
                    } else {
                        $container.html('<div style="padding:20px; color:#ef4444;">Failed to load data.</div>');
                    }
                },
                error: function() {
                    $container.html('<div style="padding:20px; color:#ef4444;">Server error occurred.</div>');
                }
            });
        },

        updateStats: function(stats) {
            $('#stat_active_tests').text(stats.active_tests || 0);
            $('#stat_active_packages').text(stats.active_packages || 0);
            $('#stat_subcategories').text(stats.subcategories || 0);
            $('#stat_conditions').text(stats.conditions || 0);
        },

        openDrawer: function(tab, id) {
            var self = this;
            var $overlay = $('#ptbs_drawer_overlay');
            var $body = $('#ptbs_drawer_body');
            var $title = $('#ptbs_drawer_title');

            $title.text(id > 0 ? 'Edit Details' : 'Add New Item');
            $body.html('<div style="padding:40px;"><div class="ptbs-skeleton" style="margin-bottom:12px;"></div><div class="ptbs-skeleton" style="margin-bottom:12px;"></div><div class="ptbs-skeleton"></div></div>');
            $overlay.addClass('active');

            $.ajax({
                url: ptbsAdminSettings.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ptbs_get_diagnostic_form',
                    nonce: ptbsAdminSettings.nonce,
                    tab: tab,
                    id: id
                },
                success: function(res) {
                    if (res.success) {
                        $body.html(res.data.html);
                        $title.text(res.data.title);
                        self.initSelect2Controls();
                    } else {
                        $body.html('<div style="color:#ef4444;">Failed to load form.</div>');
                    }
                }
            });
        },

        closeDrawer: function() {
            $('#ptbs_drawer_overlay').removeClass('active');
        },

        initSelect2Controls: function() {
            if ($('.ptbs-select2-multi').length) {
                $('.ptbs-select2-multi').select2({
                    placeholder: 'Search and select options...',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#ptbs_drawer_body')
                });
            }

            // Image Media Uploader
            $('#ptbs_cat_img_btn').off('click').on('click', function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: 'Select Image',
                    button: { text: 'Use Image' },
                    multiple: false
                });
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#ptbs_cat_img_id').val(attachment.id);
                    $('#ptbs_cat_img_preview').html('<img src="' + attachment.url + '" style="max-width:100px; border-radius:6px; margin-top:8px;">');
                });
                frame.open();
            });
        },

        saveForm: function($form) {
            var self = this;
            var formData = $form.serialize();

            var $btn = $form.find('button[type="submit"]');
            $btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: ptbsAdminSettings.ajaxurl,
                type: 'POST',
                data: formData + '&action=ptbs_save_diagnostic_item&nonce=' + ptbsAdminSettings.nonce,
                success: function(res) {
                    $btn.prop('disabled', false).text('Save & Publish');
                    if (res.success) {
                        self.showToast('success', res.data.message);
                        self.closeDrawer();
                        self.loadTabContent(self.currentTab);
                    } else {
                        self.showToast('error', res.data.message || 'Error saving item.');
                    }
                },
                error: function() {
                    $btn.prop('disabled', false).text('Save & Publish');
                    self.showToast('error', 'Server error occurred while saving.');
                }
            });
        },

        toggleStatus: function(id, type, newStatus, $input) {
            var self = this;
            $.ajax({
                url: ptbsAdminSettings.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ptbs_toggle_diagnostic_status',
                    nonce: ptbsAdminSettings.nonce,
                    id: id,
                    type: type,
                    status: newStatus
                },
                success: function(res) {
                    if (res.success) {
                        self.showToast('success', 'Status updated to ' + newStatus);
                    } else {
                        $input.prop('checked', ! $input.is(':checked'));
                        self.showToast('error', res.data.message || 'Failed to update status.');
                    }
                },
                error: function() {
                    $input.prop('checked', ! $input.is(':checked'));
                    self.showToast('error', 'Server error during status update.');
                }
            });
        },

        deleteItem: function(id, type) {
            var self = this;
            $.ajax({
                url: ptbsAdminSettings.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ptbs_delete_diagnostic_item',
                    nonce: ptbsAdminSettings.nonce,
                    id: id,
                    type: type
                },
                success: function(res) {
                    if (res.success) {
                        self.showToast('success', res.data.message);
                        self.loadTabContent(self.currentTab);
                    } else {
                        self.showToast('error', res.data.message || 'Failed to delete item.');
                    }
                }
            });
        },

        filterTableRows: function() {
            var q = this.searchQuery;
            $('.ptbs-table tbody tr').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.indexOf(q) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        },

        showToast: function(type, message) {
            var $toast = $('<div class="ptbs-toast ' + type + '"><span>' + (type === 'success' ? '✓' : '✕') + '</span><span>' + message + '</span></div>');
            $('#ptbs_toast_container').append($toast);
            setTimeout(function() {
                $toast.fadeOut(300, function() { $(this).remove(); });
            }, 3000);
        }
    };

    $(document).ready(function() {
        if ($('.ptbs-diagnostic-wrap').length) {
            PTBS_DiagAdmin.init();
        }
    });

})(jQuery);
