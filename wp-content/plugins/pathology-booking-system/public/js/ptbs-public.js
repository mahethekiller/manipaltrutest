jQuery(document).ready(function($) {
    let selectedCity = { id: 0, name: 'Select City' };
    let cart = [];
    let pendingCheckout = false;
    let extraPatientCount = 0;

    // Relocate modals to <body> to break out of theme stacking context & sticky headers
    $('body').append($('.ptbs-modal'));

    // Restore Selected City from LocalStorage if available
    const savedCityId = localStorage.getItem('ptbs_selected_city_id');
    const savedCityName = localStorage.getItem('ptbs_selected_city_name');
    if (savedCityId && savedCityName) {
        selectedCity = { id: parseInt(savedCityId, 10), name: savedCityName };
        $('#ptbs-current-city-label, .location-pill .location-city-name, .location-pill-wrap .location-city-name').text(selectedCity.name);
    }

    // Restore Cart from LocalStorage if available
    const savedCart = localStorage.getItem('ptbs_cart');
    if (savedCart) {
        try {
            cart = JSON.parse(savedCart);
            if (!Array.isArray(cart)) { cart = []; }
        } catch (e) {
            cart = [];
        }
        updateCartBadge();
    }

    // Initialize Cities & Family Checkboxes from Localized Vars
    if (typeof ptbs_vars !== 'undefined') {
        renderCityModalOptions(ptbs_vars.cities);
        renderFamilyCheckboxes(ptbs_vars.family_members);
    }

    // Modal Triggers for both Plugin and Theme Header
    $(document).on('click', '#ptbs-open-city-modal, #ptbs-current-city-label, .location-pill-wrap, .location-pill, .header-location-btn, .header-location-pill', function(e) {
        e.preventDefault();
        $('#ptbs-city-modal').addClass('active');
    });

    function handleCartClick(e) {
        if (e) { e.preventDefault(); }
        if (cart.length === 0) {
            alert('Your cart is empty. Please select at least one pathology test or health package.');
            return;
        }

        // Mandatory Account Creation Check
        if (!ptbs_vars || !ptbs_vars.is_user_logged) {
            pendingCheckout = true;
            $('#ptbs-auth-notice').show();
            $('#ptbs-auth-modal').addClass('active');
            return;
        }

        openCheckoutModal();
    }

    $(document).on('click', '#ptbs-open-cart, .header-cart-btn, #headerCartBtn', function(e) {
        handleCartClick(e);
    });

    // User Profile Dropdown Toggle in Top Header
    $(document).on('click', '#ptbsProfileBtn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#ptbsProfileMenu').slideToggle(150);
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.user-profile-dropdown-wrap').length) {
            $('#ptbsProfileMenu').hide();
        }
    });

    $(document).on('click', '#ptbs-open-auth-modal, #ptbsHeaderAuthBtn', function(e) {
        e.preventDefault();
        $('#ptbs-auth-notice').hide();
        $('#ptbs-auth-modal').addClass('active');
    });

    // Catalog Tab Switcher (Individual Tests vs Health Packages)
    $(document).on('click', '.ptbs-tabs .ptbs-tab-btn', function() {
        const targetTab = $(this).data('tab');
        $('.ptbs-tabs .ptbs-tab-btn').removeClass('active');
        $(this).addClass('active');

        $('.ptbs-tab-content').removeClass('active').hide();
        $('#ptbs-tab-' + targetTab).addClass('active').show();
    });

    // Patient Dashboard Tab Switcher
    $(document).on('click', '.ptbs-dash-tabs .ptbs-tab-btn', function() {
        const targetDashTab = $(this).data('dash-tab');
        $('.ptbs-dash-tabs .ptbs-tab-btn').removeClass('active');
        $(this).addClass('active');

        $('.ptbs-dash-tab-content').removeClass('active').hide();
        $('#ptbs-dash-tab-' + targetDashTab).addClass('active').show();
    });

    function openCheckoutModal() {
        renderPatientCards();
        updateCheckoutSummary();

        // Prefill contact phone, email, address, and pincode dynamically
        if (ptbs_vars && ptbs_vars.is_user_logged) {
            if (ptbs_vars.current_phone && !$('input[name="patient_phone"]').val()) {
                $('input[name="patient_phone"]').val(ptbs_vars.current_phone);
            }
            if (ptbs_vars.current_email && !$('input[name="patient_email"]').val()) {
                $('input[name="patient_email"]').val(ptbs_vars.current_email);
            }
            if (ptbs_vars.current_address && !$('textarea[name="address_line1"]').val()) {
                $('textarea[name="address_line1"]').val(ptbs_vars.current_address);
            }
            if (ptbs_vars.current_pincode && !$('input[name="pincode"]').val()) {
                $('input[name="pincode"]').val(ptbs_vars.current_pincode);
            }
        }

        $('#ptbs-checkout-modal').addClass('active');
    }

    $('.ptbs-modal-close').on('click', function() {
        $(this).closest('.ptbs-modal').removeClass('active');
    });

    // Main Catalog Tab Switching
    $('.ptbs-tab-btn[data-tab]').on('click', function() {
        const tab = $(this).data('tab');
        $('.ptbs-tab-btn[data-tab]').removeClass('active');
        $(this).addClass('active');
        $('.ptbs-tab-content').removeClass('active');
        $('#ptbs-tab-' + tab).addClass('active');
    });

    // Patient Dashboard Tab Switching
    $('.ptbs-tab-btn[data-dash-tab]').on('click', function() {
        const dashTab = $(this).data('dash-tab');
        $('.ptbs-tab-btn[data-dash-tab]').removeClass('active');
        $(this).addClass('active');
        $('.ptbs-dash-tab-content').hide();
        $('#ptbs-dash-tab-' + dashTab).show();
    });

    // Search Box Real-Time Filter
    $('#ptbs-search-input').on('keyup', function() {
        const query = $(this).val().toLowerCase();
        $('.ptbs-card').each(function() {
            const text = $(this).text().toLowerCase();
            if (text.indexOf(query) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Render Family Member Checkboxes in Checkout Step 2
    function renderFamilyCheckboxes(members) {
        const $container = $('#ptbs-family-checkboxes-container');
        if ($container.length === 0) return;
        $container.empty();

        if (members && members.length > 0) {
            members.forEach(function(m) {
                $container.append(`
                    <label class="ptbs-radio-box" style="margin-bottom:0; background:#f8fafc;">
                        <input type="checkbox" class="ptbs-patient-checkbox" data-type="family" data-id="${m.id}" data-name="${m.full_name}" data-relation="${m.relation}" data-age="${m.age}" data-gender="${m.gender}" data-phone="${m.phone || ''}">
                        <span>👨‍👩‍👧 <strong>${m.full_name}</strong> (${m.relation}, ${m.age} Yrs)</span>
                    </label>
                `);
            });
        }
    }

    // Toggle Patient Selection Checkboxes
    $(document).on('change', '.ptbs-patient-checkbox', function() {
        renderPatientCards();
        updateCheckoutSummary();
    });

    // Add Extra Inline Patient Card
    $('#ptbs-add-extra-patient-btn').on('click', function() {
        extraPatientCount++;
        const cardId = 'extra_patient_' + extraPatientCount;

        $('#ptbs-patients-cards-container').append(`
            <div class="ptbs-patient-card-box" id="${cardId}" style="background:#ffffff; border:1px solid #cbd5e1; padding:16px; border-radius:10px; position:relative;">
                <span class="ptbs-remove-extra-patient" data-card="${cardId}" style="position:absolute; top:12px; right:14px; cursor:pointer; color:#ef4444; font-weight:bold;">✖ Remove</span>
                <h5 style="margin:0 0 12px 0; color:#2563eb;">➕ New Patient #${extraPatientCount}</h5>
                <div class="ptbs-field">
                    <label>Patient Name *</label>
                    <input type="text" class="ptbs-p-name" required placeholder="Full Name">
                </div>
                <div class="ptbs-field-row">
                    <div class="ptbs-field">
                        <label>Relation</label>
                        <input type="text" class="ptbs-p-relation" value="Other" placeholder="Relation">
                    </div>
                    <div class="ptbs-field">
                        <label>Age *</label>
                        <input type="number" class="ptbs-p-age" required min="1" max="120" value="30">
                    </div>
                    <div class="ptbs-field">
                        <label>Gender *</label>
                        <select class="ptbs-p-gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
        `);

        updateCheckoutSummary();
    });

    // Remove Extra Patient Card
    $(document).on('click', '.ptbs-remove-extra-patient', function() {
        const cardId = $(this).data('card');
        $('#' + cardId).remove();
        updateCheckoutSummary();
    });

    // Render Active Selected Patient Cards Summary
    function renderPatientCards() {
        const $container = $('#ptbs-patients-cards-container');
        $container.find('.ptbs-preset-patient-card').remove();

        // 1. Check Myself
        if ($('.ptbs-patient-checkbox[data-type="myself"]').is(':checked')) {
            const userName = (ptbs_vars && ptbs_vars.current_user) ? ptbs_vars.current_user : '';
            $container.prepend(`
                <div class="ptbs-patient-card-box ptbs-preset-patient-card" data-preset="myself" style="background:#eff6ff; border:1px solid #bfdbfe; padding:16px; border-radius:10px;">
                    <h5 style="margin:0 0 10px 0; color:#1d4ed8;">👤 Patient: Myself (Account Holder)</h5>
                    <div class="ptbs-field">
                        <label>Patient Name *</label>
                        <input type="text" class="ptbs-p-name" value="${userName}" required>
                    </div>
                    <div class="ptbs-field-row">
                        <div class="ptbs-field">
                            <label>Age *</label>
                            <input type="number" class="ptbs-p-age" value="30" required min="1" max="120">
                        </div>
                        <div class="ptbs-field">
                            <label>Gender *</label>
                            <select class="ptbs-p-gender">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            `);
        }

        // 2. Check Family Members
        $('.ptbs-patient-checkbox[data-type="family"]:checked').each(function() {
            const fmId = $(this).data('id');
            const fmName = $(this).data('name');
            const fmRelation = $(this).data('relation');
            const fmAge = $(this).data('age');
            const fmGender = $(this).data('gender');

            $container.append(`
                <div class="ptbs-patient-card-box ptbs-preset-patient-card" data-preset="family_${fmId}" style="background:#f8fafc; border:1px solid #cbd5e1; padding:16px; border-radius:10px;">
                    <h5 style="margin:0 0 10px 0; color:#334155;">👨‍👩‍👧 Patient: ${fmName} (${fmRelation})</h5>
                    <div class="ptbs-field">
                        <label>Patient Name *</label>
                        <input type="text" class="ptbs-p-name" value="${fmName}" required>
                    </div>
                    <div class="ptbs-field-row">
                        <div class="ptbs-field">
                            <label>Relation</label>
                            <input type="text" class="ptbs-p-relation" value="${fmRelation}">
                        </div>
                        <div class="ptbs-field">
                            <label>Age *</label>
                            <input type="number" class="ptbs-p-age" value="${fmAge}" required min="1" max="120">
                        </div>
                        <div class="ptbs-field">
                            <label>Gender *</label>
                            <select class="ptbs-p-gender">
                                <option value="male" ${fmGender === 'male' ? 'selected' : ''}>Male</option>
                                <option value="female" ${fmGender === 'female' ? 'selected' : ''}>Female</option>
                                <option value="other" ${fmGender === 'other' ? 'selected' : ''}>Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            `);
        });
    }

    // Collect Selected Patients Payload
    function getSelectedPatientsList() {
        const patients = [];
        $('#ptbs-patients-cards-container .ptbs-patient-card-box').each(function() {
            const name = $(this).find('.ptbs-p-name').val();
            const relation = $(this).find('.ptbs-p-relation').val() || 'Self';
            const age = parseInt($(this).find('.ptbs-p-age').val() || '30', 10);
            const gender = $(this).find('.ptbs-p-gender').val() || 'male';

            if (name) {
                patients.push({
                    name: name,
                    relation: relation,
                    age: age,
                    gender: gender
                });
            }
        });
        return patients;
    }

    // Render City Options
    function renderCityModalOptions(cities) {
        const $container = $('#ptbs-city-options');
        $container.empty();

        if (!cities || cities.length === 0) {
            $container.append('<div class="ptbs-city-item" data-id="0" data-name="All Cities">All Cities</div>');
        } else {
            cities.forEach(function(c) {
                const activeClass = (c.id === selectedCity.id) ? ' active' : '';
                $container.append('<div class="ptbs-city-item' + activeClass + '" data-id="' + c.id + '" data-name="' + c.name + '">' + c.name + '</div>');
            });
        }

        $('.ptbs-city-item').on('click', function() {
            selectedCity.id = $(this).data('id');
            selectedCity.name = $(this).data('name');

            localStorage.setItem('ptbs_selected_city_id', selectedCity.id);
            localStorage.setItem('ptbs_selected_city_name', selectedCity.name);

            $('#ptbs-current-city-label, .location-pill .location-city-name, .location-pill-wrap .location-city-name').text(selectedCity.name);
            $('#ptbs-city-modal').removeClass('active');
            loadCatalogForCity(selectedCity.id);
        });

        if (selectedCity.id === 0 && cities && cities.length > 0) {
            const triggerMode = (typeof ptbs_vars !== 'undefined' && ptbs_vars.city_trigger_mode) ? ptbs_vars.city_trigger_mode : 'lab_page';
            if (triggerMode === 'site_load' || $('#ptbs-booking-app').length > 0) {
                $('#ptbs-city-modal').addClass('active');
            }
        } else {
            loadCatalogForCity(selectedCity.id);
        }
    }

    // Fetch Catalog via AJAX
    function loadCatalogForCity(cityId) {
        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'ptbs_get_city_catalog',
                security: ptbs_vars.public_nonce,
                city_id: cityId
            },
            success: function(res) {
                if (res.success) {
                    $('#ptbs-tests-grid').html(res.data.tests_html);
                    $('#ptbs-packages-grid').html(res.data.packages_html);
                }
            }
        });
    }

    // Add To Cart Handler for pre-rendered and AJAX cards
    $(document).on('click', '.ptbs-add-to-cart', function() {
        const item = {
            id: $(this).data('id'),
            type: $(this).data('type'),
            title: $(this).data('title'),
            price: parseFloat($(this).data('price'))
        };

        const index = cart.findIndex(i => i.id === item.id && i.type === item.type);
        if (index === -1) {
            cart.push(item);
            $(this).text(item.type === 'package' ? 'Package Added ✓' : 'Added ✓').removeClass('ptbs-btn-primary').addClass('ptbs-btn-outline');
        } else {
            cart.splice(index, 1);
            $(this).text(item.type === 'package' ? 'Add Package' : 'Add').removeClass('ptbs-btn-outline').addClass('ptbs-btn-primary');
        }
        updateCartBadge();
    });

    function updateCartBadge() {
        localStorage.setItem('ptbs_cart', JSON.stringify(cart));
        $('#ptbs-cart-count, #headerCartBadge').text(cart.length);
        if (cart.length > 0) {
            $('#headerCartBadge').css('display', 'flex').show();
        } else {
            $('#headerCartBadge').hide();
        }
        const total = cart.reduce((sum, i) => sum + i.price, 0);
        $('#ptbs-cart-total').text(total.toFixed(2));
    }

    let appliedCoupon = { code: '', discount: 0 };

    function updateCheckoutSummary() {
        const $list = $('#ptbs-summary-list');
        $list.empty();
        let subtotal = 0;

        cart.forEach(function(i, idx) {
            subtotal += i.price;
            $list.append('<li>' + i.title + ' - <strong>₹' + i.price.toFixed(2) + '</strong> <span class="ptbs-remove-item" data-index="' + idx + '" style="color:#ef4444; cursor:pointer; margin-left:8px;" title="Remove">✖</span></li>');
        });

        const selectedPatients = getSelectedPatientsList();
        const patientCount = Math.max(selectedPatients.length, 1);
        const grossSubtotal = subtotal * patientCount;

        if (patientCount > 1) {
            $list.append(`<li style="background:#eff6ff; font-weight:bold; color:#1d4ed8; padding:8px;">👥 Group Booking: ${patientCount} Patients (₹${subtotal.toFixed(2)} x ${patientCount})</li>`);
        }

        $('#ptbs-checkout-subtotal, #ptbs-summary-amount').text(grossSubtotal.toFixed(2));

        // Calculate active coupon discount against current subtotal
        let discount = 0;
        if (appliedCoupon.discount > 0) {
            discount = Math.min(appliedCoupon.discount, grossSubtotal);
            $('#ptbs-discount-row').css('display', 'flex');
            $('#ptbs-checkout-discount').text(discount.toFixed(2));
        } else {
            $('#ptbs-discount-row').hide();
            $('#ptbs-checkout-discount').text('0.00');
        }

        const finalTotal = Math.max(0, grossSubtotal - discount);
        $('#ptbs-checkout-total').text(finalTotal.toFixed(2));

        // Auto default date to today if empty and trigger time slot capacity check
        const $dateInput = $('input[name="booking_date"]');
        if ($dateInput.length > 0) {
            if (!$dateInput.val()) {
                const today = new Date().toISOString().split('T')[0];
                $dateInput.val(today);
            }
            $dateInput.trigger('change');
        }

        $('.ptbs-remove-item').off('click').on('click', function() {
            const removeIdx = $(this).data('index');
            cart.splice(removeIdx, 1);
            updateCartBadge();
            updateCheckoutSummary();
            if (cart.length === 0) {
                $('#ptbs-checkout-modal').removeClass('active');
            }
        });
    }

    // AJAX Apply Promo Coupon Code Handler
    $(document).on('click', '#ptbs_apply_coupon_btn', function(e) {
        e.preventDefault();
        const couponCode = $('#ptbs_coupon_code_input').val().trim();
        const subtotal = parseFloat($('#ptbs-checkout-subtotal').text() || '0');

        if (!couponCode) {
            $('#ptbs_coupon_msg').css('color', '#dc2626').text('Please enter a promo code.');
            return;
        }

        const $btn = $(this);
        $btn.prop('disabled', true).text('Checking...');

        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'ptbs_apply_coupon',
                security: ptbs_vars.public_nonce,
                coupon_code: couponCode,
                cart_subtotal: subtotal
            },
            success: function(res) {
                $btn.prop('disabled', false).text('Apply');
                if (res.success) {
                    appliedCoupon = {
                        code: res.data.coupon_code,
                        discount: res.data.discount_amount
                    };
                    $('#ptbs_coupon_msg').css('color', '#16a34a').text(res.data.message);
                    updateCheckoutSummary();
                } else {
                    appliedCoupon = { code: '', discount: 0 };
                    $('#ptbs_coupon_msg').css('color', '#dc2626').text(res.data.message || 'Invalid coupon.');
                    updateCheckoutSummary();
                }
            },
            error: function() {
                $btn.prop('disabled', false).text('Apply');
                $('#ptbs_coupon_msg').css('color', '#dc2626').text('Server error. Please try again.');
            }
        });
    });

    // Dynamic Time Slot Capacity Loader on Booking Date Change
    $(document).on('change', 'input[name="booking_date"]', function() {
        const selectedDate = $(this).val();
        if (!selectedDate) { return; }

        const $slotSelect = $('#ptbs-checkout-time-slot, .ptbs-checkout-time-slot, select[name="time_slot"], select[name="booking_slot"]');
        $slotSelect.prop('disabled', true);

        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: {
                action: 'ptbs_get_available_time_slots',
                security: ptbs_vars.public_nonce,
                booking_date: selectedDate
            },
            success: function(res) {
                $slotSelect.prop('disabled', false).empty();
                if (res.success && res.data.slots) {
                    res.data.slots.forEach(function(s) {
                        const disabledAttr = s.is_full ? 'disabled' : '';
                        const optionText = s.label + ' ' + s.badge;
                        $slotSelect.append('<option value="' + s.label + '" ' + disabledAttr + '>' + optionText + '</option>');
                    });
                }
            },
            error: function() {
                $slotSelect.prop('disabled', false);
            }
        });
    });

    // User Auth Tabs
    $('.ptbs-auth-tab').on('click', function() {
        const target = $(this).data('auth');
        $('.ptbs-auth-tab').removeClass('active');
        $(this).addClass('active');
        $('.ptbs-auth-form').removeClass('active');
        $('#ptbs-' + target + '-form').addClass('active');
    });

    $('#ptbs-login-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: $(this).serialize() + '&action=ptbs_login_user&security=' + ptbs_vars.auth_nonce,
            success: function(res) {
                if (res.success) {
                    ptbs_vars.is_user_logged = true;
                    ptbs_vars.current_user = res.data.display_name;
                    ptbs_vars.current_email = res.data.email;
                    ptbs_vars.current_phone = res.data.phone;
                    ptbs_vars.family_members = res.data.family_members || [];

                    renderFamilyCheckboxes(ptbs_vars.family_members);

                    if (pendingCheckout) {
                        $('#ptbs-auth-modal').removeClass('active');
                        openCheckoutModal();
                    } else {
                        alert(res.data.message);
                        window.location.reload();
                    }
                } else {
                    alert(res.data.message);
                }
            }
        });
    });

    $('#ptbs-register-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: $(this).serialize() + '&action=ptbs_register_user&security=' + ptbs_vars.auth_nonce,
            success: function(res) {
                if (res.success) {
                    ptbs_vars.is_user_logged = true;
                    ptbs_vars.current_user = res.data.display_name;
                    ptbs_vars.current_email = res.data.email;
                    ptbs_vars.current_phone = res.data.phone;
                    ptbs_vars.family_members = res.data.family_members || [];

                    renderFamilyCheckboxes(ptbs_vars.family_members);

                    if (pendingCheckout) {
                        $('#ptbs-auth-modal').removeClass('active');
                        openCheckoutModal();
                    } else {
                        alert(res.data.message);
                        window.location.reload();
                    }
                } else {
                    alert(res.data.message);
                }
            }
        });
    });

    // Family Member Manager AJAX
    $('#ptbs-add-family-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: $(this).serialize() + '&action=ptbs_save_family_member&security=' + ptbs_vars.auth_nonce,
            success: function(res) {
                alert(res.data.message);
                if (res.success) {
                    window.location.reload();
                }
            }
        });
    });

    $('.ptbs-delete-family-btn').on('click', function() {
        if (!confirm('Are you sure you want to remove this family profile?')) return;
        const memberId = $(this).data('id');
        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: { action: 'ptbs_delete_family_member', security: ptbs_vars.auth_nonce, member_id: memberId },
            success: function(res) {
                alert(res.data.message);
                if (res.success) {
                    window.location.reload();
                }
            }
        });
    });

    // Security Password Change AJAX
    $('#ptbs-change-password-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: $(this).serialize() + '&action=ptbs_change_password&security=' + ptbs_vars.auth_nonce,
            success: function(res) {
                alert(res.data.message);
                if (res.success) {
                    $('#ptbs-change-password-form')[0].reset();
                }
            }
        });
    });

    // Profile Info Update AJAX
    $('#ptbs-update-profile-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: $(this).serialize() + '&action=ptbs_update_profile&security=' + ptbs_vars.auth_nonce,
            success: function(res) {
                alert(res.data.message);
                if (res.success) {
                    window.location.reload();
                }
            }
        });
    });

    // Checkout Form Submission & Multi-Patient Payload Handoff
    $('#ptbs-checkout-form').on('submit', function(e) {
        e.preventDefault();

        if (cart.length === 0) {
            alert('Your booking list is empty.');
            return;
        }

        const selectedPatients = getSelectedPatientsList();
        if (selectedPatients.length === 0) {
            alert('Please select or add at least one patient for this appointment.');
            return;
        }

        const formData = $(this).serializeArray();
        const slotVal = $('select[name="time_slot"], select[name="booking_slot"]').val() || '';
        const gatewayVal = $('input[name="gateway"]:checked, input[name="payment_method"]:checked').val() || 'mock';

        formData.push({ name: 'action', value: 'ptbs_process_booking' });
        formData.push({ name: 'security', value: ptbs_vars.public_nonce });
        formData.push({ name: 'city_id', value: selectedCity.id });
        formData.push({ name: 'city_name', value: selectedCity.name });
        formData.push({ name: 'items', value: JSON.stringify(cart) });
        formData.push({ name: 'patients', value: JSON.stringify(selectedPatients) });
        formData.push({ name: 'booking_slot', value: slotVal });
        formData.push({ name: 'gateway', value: gatewayVal });

        // Set primary patient name for legacy compatibility
        formData.push({ name: 'patient_name', value: selectedPatients[0].name });
        formData.push({ name: 'patient_age', value: selectedPatients[0].age });
        formData.push({ name: 'patient_gender', value: selectedPatients[0].gender });

        $.ajax({
            url: ptbs_vars.ajax_url,
            type: 'POST',
            data: $.param(formData),
            success: function(res) {
                if (res.success) {
                    const data = res.data;
                    cart = [];
                    localStorage.removeItem('ptbs_cart');
                    updateCartBadge();

                    if (data.is_mock) {
                        alert(data.message);
                        $('#ptbs-checkout-modal').removeClass('active');
                        window.location.reload();
                    } else if (data.gateway === 'razorpay') {
                        openRazorpayCheckout(data.gateway_data);
                    } else {
                        alert('Booking #' + data.booking_number + ' created. Redirecting to PhonePe gateway...');
                        window.location.href = data.gateway_data.url;
                    }
                } else {
                    alert(res.data.message);
                }
            }
        });
    });

    function openRazorpayCheckout(options) {
        options.handler = function(response) {
            alert('Payment Successful! Payment ID: ' + response.razorpay_payment_id);
            window.location.reload();
        };

        const rzp = new Razorpay(options);
        rzp.open();
    }

    // Parameter Accordion Tree Toggle on Single Package Page
    $(document).on('click', '.ptbs-accordion-header', function() {
        const $body = $(this).next('.ptbs-accordion-body');
        const $icon = $(this).find('.ptbs-accordion-icon');
        
        $body.slideToggle(200);
        if ($icon.text() === '+') {
            $icon.text('−');
        } else {
            $icon.text('+');
        }
    });

    // Single Test & Package Page Add to Cart
    $(document).on('click', '.ptbs-single-add-cart', function() {
        const item = {
            id: $(this).data('id'),
            type: $(this).data('type'),
            title: $(this).data('title'),
            price: parseFloat($(this).data('price'))
        };

        const index = cart.findIndex(i => i.id === item.id && i.type === item.type);
        if (index === -1) {
            cart.push(item);
            $(this).text(item.type === 'package' ? 'Package Added to Cart ✓' : 'Test Added to Cart ✓').css('background', '#16a34a');
        } else {
            alert('This item is already added in your booking cart.');
        }

        updateCartBadge();
    });
});
