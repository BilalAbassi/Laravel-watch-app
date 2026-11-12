

import Alpine from 'alpinejs';

if (!window.Alpine && !document.querySelector('script[src*="alpinejs"]')) {
    window.Alpine = Alpine;
    Alpine.start();
}

