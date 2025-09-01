jQuery(document).ready(function($) {
    'use strict';
    
    // Initialize Google Maps if available
    if (typeof google !== 'undefined') {
        initializeMaps();
    }
    
    function initializeMaps() {
        // Initialize main directory map
        if ($('#pilates-map').length) {
            initPilatesDirectoryMap();
        }
        
        // Initialize single studio maps
        if ($('#single-studio-map').length) {
            initSingleStudioMap();
        }
    }
    
    function initPilatesDirectoryMap() {
        const map = new google.maps.Map(document.getElementById('pilates-map'), {
            zoom: 12,
            center: {lat: 59.3293, lng: 18.0686}, // Stockholm center
            styles: getMapStyles()
        });
        
        loadStudioMarkers(map);
    }
    
    function loadStudioMarkers(map) {
        $.ajax({
            url: pilates_ajax.ajax_url,
            type: 'GET',
            data: {
                action: 'get_studio_markers'
            },
            success: function(markers) {
                const bounds = new google.maps.LatLngBounds();
                const infoWindows = [];
                
                markers.forEach(function(studioData, index) {
                    const marker = new google.maps.Marker({
                        position: {lat: studioData.lat, lng: studioData.lng},
                        map: map,
                        title: studioData.name,
                        icon: {
                            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(getCustomMarkerSVG()),
                            scaledSize: new google.maps.Size(40, 40),
                            anchor: new google.maps.Point(20, 40)
                        }
                    });
                    
                    const infoWindow = new google.maps.InfoWindow({
                        content: createInfoWindowContent(studioData)
                    });
                    
                    marker.addListener('click', function() {
                        // Close all other info windows
                        infoWindows.forEach(function(iw) {
                            iw.close();
                        });
                        
                        infoWindow.open(map, marker);
                    });
                    
                    infoWindows.push(infoWindow);
                    bounds.extend(marker.getPosition());
                });
                
                if (markers.length > 0) {
                    map.fitBounds(bounds);
                    
                    // Don't zoom in too much for single markers
                    google.maps.event.addListenerOnce(map, 'bounds_changed', function() {
                        if (map.getZoom() > 15) {
                            map.setZoom(15);
                        }
                    });
                }
            },
            error: function() {
                console.log('Kunde inte ladda studio-markörer');
            }
        });
    }
    
    function createInfoWindowContent(studio) {
        let ratingHTML = '';
        if (studio.rating) {
            const stars = createStarsHTML(studio.rating);
            ratingHTML = `<div class="rating">${stars} ${studio.rating}/5</div>`;
        }
        
        return `
            <div class="map-studio-info" style="max-width: 250px;">
                <h4 style="margin: 0 0 8px 0; color: #1a1a1a;">
                    <a href="${studio.url}" style="text-decoration: none; color: #2d5016;">${studio.name}</a>
                </h4>
                <p style="margin: 0 0 8px 0; color: #666; font-size: 14px;">${studio.address}</p>
                ${ratingHTML}
                <div style="margin-top: 10px;">
                    <a href="${studio.url}" class="btn btn-primary" style="font-size: 12px; padding: 6px 12px;">Läs mer</a>
                </div>
            </div>
        `;
    }
    
    function createStarsHTML(rating) {
        let stars = '';
        const fullStars = Math.floor(rating);
        const hasHalfStar = (rating - fullStars) >= 0.5;
        
        for (let i = 0; i < fullStars; i++) {
            stars += '★';
        }
        
        if (hasHalfStar) {
            stars += '☆';
        }
        
        return `<span style="color: #ffc107; font-size: 14px;">${stars}</span>`;
    }
    
    function getCustomMarkerSVG() {
        return `
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg">
                <circle cx="20" cy="20" r="18" fill="#2d5016" stroke="#ffffff" stroke-width="2"/>
                <circle cx="20" cy="20" r="8" fill="#ffffff"/>
                <text x="20" y="25" text-anchor="middle" fill="#2d5016" font-size="12" font-weight="bold">P</text>
            </svg>
        `;
    }
    
    function getMapStyles() {
        return [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{visibility: 'off'}]
            },
            {
                featureType: 'transit',
                elementType: 'labels',
                stylers: [{visibility: 'off'}]
            },
            {
                featureType: 'road',
                elementType: 'labels.icon',
                stylers: [{visibility: 'off'}]
            },
            {
                featureType: 'water',
                elementType: 'geometry.fill',
                stylers: [{color: '#74b9ff'}]
            },
            {
                featureType: 'landscape.natural',
                elementType: 'geometry.fill',
                stylers: [{color: '#f5f2ed'}]
            }
        ];
    }
    
    // Filter form enhancements
    $('.pilates-filters select').on('change', function() {
        const $form = $(this).closest('.pilates-filters');
        $form.find('#apply-filters').addClass('highlight');
    });
    
    $('.pilates-filters input').on('input', function() {
        const $form = $(this).closest('.pilates-filters');
        $form.find('#apply-filters').addClass('highlight');
    });
    
    // Smooth animations for studio cards
    $('.studio-card').hover(
        function() {
            $(this).find('.studio-image img').css('transform', 'scale(1.05)');
        },
        function() {
            $(this).find('.studio-image img').css('transform', 'scale(1)');
        }
    );
    
    // Address geocoding for admin
    if ($('#studio_address').length) {
        $('#studio_address').on('blur', function() {
            const address = $(this).val();
            if (address && typeof google !== 'undefined') {
                geocodeAddress(address);
            }
        });
    }
    
    function geocodeAddress(address) {
        const geocoder = new google.maps.Geocoder();
        
        geocoder.geocode({
            address: address + ', Stockholm, Sweden',
            componentRestrictions: {
                country: 'SE',
                locality: 'Stockholm'
            }
        }, function(results, status) {
            if (status === 'OK' && results[0]) {
                const lat = results[0].geometry.location.lat();
                const lng = results[0].geometry.location.lng();
                
                $('#studio_latitude').val(lat);
                $('#studio_longitude').val(lng);
                
                console.log('Koordinater uppdaterade automatiskt');
            }
        });
    }
    
    // Star rating interaction
    $('.star-rating label').hover(
        function() {
            const rating = $(this).prev('input').val();
            $(this).addClass('hover');
            $(this).prevAll('label').addClass('hover');
            $(this).nextAll('label').removeClass('hover');
        },
        function() {
            $('.star-rating label').removeClass('hover');
        }
    );
    
    // Mobile filter toggle
    if ($(window).width() <= 768) {
        $('.pilates-filters h3').on('click', function() {
            $(this).next().slideToggle();
            $(this).toggleClass('active');
        });
        
        // Hide filters by default on mobile
        $('.pilates-filters .filter-row').hide();
    }
    
    // Infinite scroll alternative to load more button
    let isNearBottom = false;
    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1000) {
            if (!isNearBottom && $('#load-more-studios').is(':visible')) {
                isNearBottom = true;
                $('#load-more-studios').click();
                
                setTimeout(function() {
                    isNearBottom = false;
                }, 2000);
            }
        }
    });
    
    // Analytics tracking for interactions
    $('.studio-card a, .btn').on('click', function() {
        const action = $(this).hasClass('btn-primary') ? 'view_studio' : 'click_link';
        const label = $(this).closest('.studio-card').find('.studio-name').text().trim();
        
        if (typeof gtag !== 'undefined') {
            gtag('event', action, {
                event_category: 'studio_interaction',
                event_label: label
            });
        }
    });
    
    // Error handling for missing elements
    $(document).ajaxError(function(event, jqxhr, settings, thrownError) {
        console.log('AJAX Error:', thrownError);
    });
    
    // Initialize any additional features
    initializeAccessibility();
    initializePerformanceOptimizations();
    
    function initializeAccessibility() {
        // Add ARIA labels to interactive elements
        $('.studio-card').attr('role', 'article');
        $('.btn').attr('role', 'button');
        
        // Keyboard navigation for filters
        $('.pilates-filters select, .pilates-filters input').on('keydown', function(e) {
            if (e.key === 'Enter' && $(this).is('input[type="text"]')) {
                $('#apply-filters').click();
            }
        });
    }
    
    function initializePerformanceOptimizations() {
        // Debounce resize events
        let resizeTimeout;
        $(window).on('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                if ($('#pilates-map').length && typeof google !== 'undefined') {
                    google.maps.event.trigger(map, 'resize');
                }
            }, 250);
        });
        
        // Lazy load images that are not initially visible
        $('.studio-card img').each(function() {
            const img = $(this);
            if (img.offset().top > $(window).height() + $(window).scrollTop() + 1000) {
                const src = img.attr('src');
                img.attr('data-src', src).removeAttr('src').addClass('lazy');
            }
        });
        
        // Load lazy images when they come into view
        $(window).on('scroll', function() {
            $('.lazy').each(function() {
                const img = $(this);
                if (img.offset().top < $(window).height() + $(window).scrollTop() + 500) {
                    img.attr('src', img.attr('data-src')).removeClass('lazy');
                }
            });
        });
    }
});