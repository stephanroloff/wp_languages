/**
 * Frontend JavaScript for WP Languages
 * Handles language switcher interactions
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLanguageSwitcher);
    } else {
        initLanguageSwitcher();
    }

    function initLanguageSwitcher() {
        const switchers = document.querySelectorAll('.wp-languages-switcher');
        
        switchers.forEach(function(switcher) {
            const links = switcher.querySelectorAll('.wp-languages-switcher-link:not(.disabled)');
            
            links.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    // Allow normal link behavior, but we can add analytics or other tracking here if needed
                    // The link already has the correct href, so it will navigate normally
                });
            });
        });
    }

})();
