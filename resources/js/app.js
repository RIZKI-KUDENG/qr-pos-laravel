import './bootstrap';

import Alpine from 'alpinejs';
import menuApp from './alpine/menuApp';
import './alpine/store/cartStore';

document.addEventListener('alpine:init', () => {
    Alpine.data('menuApp', menuApp)
})


window.Alpine = Alpine;

Alpine.start();
