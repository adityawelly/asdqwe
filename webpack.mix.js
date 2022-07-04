const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.scripts([
        'resources/js/vendor/core/jquery.3.2.1.min.js',
        'resources/js/vendor/core/popper.min.js',
        'resources/js/vendor/core/bootstrap.min.js',
        'resources/js/vendor/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js',
        'resources/js/vendor/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js',
        'resources/js/vendor/plugin/jquery-scrollbar/jquery.scrollbar.min.js',
        'resources/js/vendor/plugin/datatables/datatables.min.js',
        'resources/js/vendor/plugin/bootstrap-notify/bootstrap-notify.min.js',
        'resources/js/vendor/atlantis.min.js'
    ], 'public/js/app.js')
    .styles([
        'resources/sass/bootstrap.min.css',
        'resources/sass/atlantis.min.css'
    ], 'public/css/app.css');
