var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss');
    mix.styles([
       'vendor/bootstrap.min.css',
        'app.css'
    ],'public/output/final.css','public/css');

    mix.scripts([
        'vendor/jquery-2.1.4.min.js',
        'vendor/jquery.embedly-3.1.2.min.js',
        'vendor/bootstrap.min.js',
        'app.js'
    ],'public/output/scripts.js');

   // mix.copy('public/css/vendor/fonts', 'public/fonts');

    mix.version(['public/output/final.css','public/output/scripts.js']);

});
