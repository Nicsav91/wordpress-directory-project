jQuery(document).ready(function($) {
    'use strict';
    
    // Variables
    let currentPage = 1;
    let isLoading = false;
    
    // Filter functionality
    $('#apply-filters').on('click', function() {
        filterStudios();
    });
    
    // Clear filters
    $('#clear-filters').on('click', function() {
        $('#area-filter').val('');
        $('#price-filter').val('');
        $('#specialty-filter').val('');
        $('#search-input').val('');
        filterStudios();
    });
    
    // Search on enter
    $('#search-input').on('keypress', function(e) {
        if (e.which == 13) {
            filterStudios();
        }
    });
    
    // Live search with debounce
    let searchTimeout;
    $('#search-input').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            filterStudios();
        }, 500);
    });
    
    // Load more functionality
    $('#load-more-studios').on('click', function() {
        loadMoreStudios();
    });
    
    function filterStudios() {
        if (isLoading) return;
        
        isLoading = true;
        currentPage = 1;
        
        const data = {
            action: 'filter_studios',
            area: $('#area-filter').val(),
            price_class: $('#price-filter').val(),
            specialty: $('#specialty-filter').val(),
            search: $('#search-input').val(),
            page: currentPage,
            nonce: pilates_ajax.nonce
        };
        
        $('#studios-grid').html('<div class="loading">Laddar studios...</div>');
        
        $.post(pilates_ajax.ajax_url, data, function(response) {
            if (response.success) {
                $('#studios-grid').html('');
                
                if (response.data.studios.length > 0) {
                    response.data.studios.forEach(function(studio) {
                        $('#studios-grid').append(studio);
                    });
                    
                    // Show/hide load more button
                    if (response.data.has_more) {
                        $('#load-more-studios').show();
                    } else {
                        $('#load-more-studios').hide();
                    }
                } else {
                    $('#studios-grid').html('<div class="no-results"><p>Inga studios hittades med dessa filter.</p></div>');
                    $('#load-more-studios').hide();
                }
            } else {
                $('#studios-grid').html('<div class="error"><p>Ett fel uppstod. Försök igen.</p></div>');
            }
            
            isLoading = false;
        }).fail(function() {
            $('#studios-grid').html('<div class="error"><p>Ett fel uppstod. Försök igen.</p></div>');
            isLoading = false;
        });
    }
    
    function loadMoreStudios() {
        if (isLoading) return;
        
        isLoading = true;
        currentPage++;
        
        const data = {
            action: 'load_more_studios',
            page: currentPage,
            nonce: pilates_ajax.nonce
        };
        
        $('#load-more-studios').text('Laddar...');
        
        $.post(pilates_ajax.ajax_url, data, function(response) {
            if (response.success && response.data.studios.length > 0) {
                response.data.studios.forEach(function(studio) {
                    $('#studios-grid').append(studio);
                });
                
                if (!response.data.has_more) {
                    $('#load-more-studios').hide();
                } else {
                    $('#load-more-studios').text('Visa fler');
                }
            } else {
                $('#load-more-studios').hide();
            }
            
            isLoading = false;
        }).fail(function() {
            $('#load-more-studios').text('Fel uppstod');
            isLoading = false;
        });
    }
    
    // Review modal functionality
    window.openReviewModal = function(studioId) {
        $('#review-studio-id').val(studioId);
        $('#reviewModal').show();
    };
    
    // Close modal
    $('.modal .close').on('click', function() {
        $(this).closest('.modal').hide();
    });
    
    // Close modal on background click
    $('.modal').on('click', function(e) {
        if (e.target === this) {
            $(this).hide();
        }
    });
    
    // Review form submission
    $('#reviewForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            action: 'add_pilates_review',
            studio_id: $('#review-studio-id').val(),
            rating: $('input[name="rating"]:checked').val(),
            comment: $('#review-comment').val(),
            nonce: pilates_ajax.nonce
        };
        
        if (!formData.rating) {
            alert('Välj ett betyg');
            return;
        }
        
        if (!formData.comment.trim()) {
            alert('Skriv en kommentar');
            return;
        }
        
        const submitBtn = $('#reviewForm button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.text('Skickar...').prop('disabled', true);
        
        $.post(pilates_ajax.ajax_url, formData, function(response) {
            if (response.success) {
                alert('Recension tillagd!');
                $('#reviewModal').hide();
                location.reload(); // Reload to show new review
            } else {
                alert(response.data || 'Ett fel uppstod');
            }
            
            submitBtn.text(originalText).prop('disabled', false);
        }).fail(function() {
            alert('Ett fel uppstod. Försök igen.');
            submitBtn.text(originalText).prop('disabled', false);
        });
    });
    
    // Mobile menu toggle (if needed)
    $('.menu-toggle').on('click', function() {
        $(this).toggleClass('active');
        $('.main-navigation').toggleClass('active');
    });
    
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(e) {
        const target = $($(this).attr('href'));
        if (target.length) {
            e.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });
    
    // Sticky header on scroll
    let lastScrollTop = 0;
    $(window).scroll(function() {
        const st = $(this).scrollTop();
        
        if (st > lastScrollTop && st > 100) {
            // Scrolling down
            $('.site-header').addClass('scrolled-down');
        } else {
            // Scrolling up
            $('.site-header').removeClass('scrolled-down');
        }
        
        lastScrollTop = st;
    });
    
    // Initialize tooltips if needed
    $('[data-tooltip]').hover(function() {
        // Add tooltip functionality
    });
    
    // Image lazy loading fallback
    $('img[data-src]').each(function() {
        const img = $(this);
        img.attr('src', img.attr('data-src')).removeAttr('data-src');
    });
    
    // Form validation improvements
    $('input, textarea, select').on('blur', function() {
        const field = $(this);
        if (field.is(':required') && !field.val()) {
            field.addClass('error');
        } else {
            field.removeClass('error');
        }
    });
    
    // Initialize any maps on the page
    if (typeof google !== 'undefined' && $('.pilates-map-container').length) {
        initPilatesMap();
    }
    
    // Print functionality
    $('.print-studio').on('click', function() {
        window.print();
    });
    
    // Share functionality
    $('.share-studio').on('click', function(e) {
        e.preventDefault();
        
        if (navigator.share) {
            navigator.share({
                title: document.title,
                url: window.location.href
            });
        } else {
            // Fallback - copy to clipboard
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Länk kopierad!');
            });
        }
    });
});