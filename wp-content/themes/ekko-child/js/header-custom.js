/**
 * Dynamic Header Redesign Interactive Scripts & AJAX Handlers
 */
jQuery(document).ready(function($) {
    'use strict';

    // Mobile Navigation Menu Toggle
    $('#mobileNavToggle').on('click', function(e) {
        e.preventDefault();
        $('#bottomNavWrap').toggleClass('mobile-open');
    });

    // --------------------------------------------------------------------------
    // 1. Dynamic Location Selector & City Popover
    // --------------------------------------------------------------------------
    $('#locationSelectorPill').on('click', function(e) {
        e.stopPropagation();
        $('#locationDropdownPopover').toggleClass('show');
    });

    $('#closeCityPopover').on('click', function(e) {
        e.stopPropagation();
        $('#locationDropdownPopover').removeClass('show');
    });

    // Handle City Selection Click
    $(document).on('click', '.city-item', function(e) {
        e.preventDefault();
        var selectedCity = $(this).data('city');
        
        $('.city-item').removeClass('active').find('.check-icon').remove();
        $(this).addClass('active').append(' <i class="fa fa-check check-icon"></i>');

        $('#currentLocationText').html(selectedCity + ' <i class="fa fa-chevron-down location-arrow"></i>');
        $('#locationDropdownPopover').removeClass('show');

        // AJAX update city cookie
        if (typeof ptbsHeaderData !== 'undefined') {
            $.ajax({
                url: ptbsHeaderData.ajaxurl,
                type: 'POST',
                data: {
                    action: 'ptbs_set_city',
                    city: selectedCity,
                    nonce: ptbsHeaderData.nonce
                },
                success: function(response) {
                    if (response.success) {
                        console.log('City updated dynamically to:', selectedCity);
                    }
                }
            });
        }
    });

    // --------------------------------------------------------------------------
    // 2. Dynamic Live Search Auto-Suggest
    // --------------------------------------------------------------------------
    var searchTimer = null;

    $('#headerSearchInput').on('keyup input', function() {
        var query = $(this).val().trim();
        clearTimeout(searchTimer);

        if (query.length < 2) {
            $('#liveSearchSuggestions').removeClass('show').empty();
            $('#searchSpinner').hide();
            return;
        }

        $('#searchSpinner').show();

        searchTimer = setTimeout(function() {
            if (typeof ptbsHeaderData !== 'undefined') {
                $.ajax({
                    url: ptbsHeaderData.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'ptbs_live_search',
                        query: query,
                        nonce: ptbsHeaderData.nonce
                    },
                    success: function(response) {
                        $('#searchSpinner').hide();
                        var $overlay = $('#liveSearchSuggestions');
                        $overlay.empty();

                        if (response.success && response.data.length > 0) {
                            $.each(response.data, function(index, item) {
                                var html = '<a href="' + item.permalink + '" class="search-suggest-item">';
                                html += '<div class="suggest-title-group">';
                                html += '<span class="suggest-title">' + item.title + '</span>';
                                html += '<span class="suggest-badge">' + item.badge + '</span>';
                                html += '</div>';
                                if (item.price) {
                                    html += '<span class="suggest-price">' + item.price + '</span>';
                                }
                                html += '</a>';
                                $overlay.append(html);
                            });
                            $overlay.addClass('show');
                        } else {
                            $overlay.html('<div class="search-suggest-item"><span class="suggest-title">No tests or packages found.</span></div>').addClass('show');
                        }
                    },
                    error: function() {
                        $('#searchSpinner').hide();
                    }
                });
            }
        }, 300);
    });

    // Close overlays when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.location-selector-pill-wrap').length) {
            $('#locationDropdownPopover').removeClass('show');
        }
        if (!$(e.target).closest('.header-search-wrap').length) {
            $('#liveSearchSuggestions').removeClass('show');
        }
    });
});
