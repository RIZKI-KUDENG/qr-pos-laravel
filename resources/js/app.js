import './bootstrap';

import Alpine from 'alpinejs';
import menuApp from './alpine/menuApp';

document.addEventListener('alpine:init', () => {
    Alpine.data('menuApp', menuApp)
})


window.Alpine = Alpine;

Alpine.start();
