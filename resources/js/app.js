import './bootstrap';

import Alpine from 'alpinejs';
import menuApp from './alpine/menuApp';
import posApp from './alpine/menuApp';
import './alpine/store/cartStore';

document.addEventListener('alpine:init', () => {
    Alpine.data('menuApp', menuApp)
    Alpine.data('posApp', posApp)
})


window.Alpine = Alpine;

Alpine.start();
