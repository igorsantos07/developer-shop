var gulp   = require('gulp');
var elixir = require('laravel-elixir');

elixir.config.css.autoprefix.options = {
    browsers: ['last 2 versions', '> 1%', 'ChromeAndroid >= 4']
};

elixir(function (mix) {
    mix.less('../../../src/assets/styles', 'www/main.css');
    mix.browserify('../../../src/assets/scripts/main.js', 'www/main.js');
    mix.browserSync({
        files: 'www/*',
        proxy: 'devshop.dev'
    });
});
