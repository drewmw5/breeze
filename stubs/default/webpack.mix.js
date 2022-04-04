const mix = require('laravel-mix');
require('mix-tailwindcss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.ts('resources/js/app.js', 'public/js/app.js')
    .copyDirectory('resources/includes', 'public')
    .browserSync({
        proxy: 'https://site',
        host: 'localhost',
        notify: false,
        https: {
            cert: "./dockerfiles/localhost/fullchain.pem",
            key: "./dockerfiles/localhost/privkey.pem"
        }
    })
    .sass('resources/css/app.scss', 'public/css')
    .tailwind()
    .react()


if (mix.inProduction()) {
    mix.version();
}
