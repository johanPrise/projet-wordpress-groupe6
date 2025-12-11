/**
 * Main JavaScript pour le thème Vetements
 */

(function($) {
    'use strict';
    
    // Navigation mobile toggle (responsive)
    function toggleMobileMenu() {
        $('.menu-toggle').on('click', function() {
            $('.main-navigation').toggleClass('mobile-active');
        });
    }
    
    // Init au chargement
    $(document).ready(function() {
        toggleMobileMenu();
    });
    
})(jQuery);
