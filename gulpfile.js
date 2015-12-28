var gulp   = require('gulp');
var elixir = require('laravel-elixir');

elixir.config.css.autoprefix.options = {
    browsers: ['last 2 versions', '> 1%', 'ChromeAndroid >= 4']
};

elixir(function (mix) {
    mix.browserify('../../../src/assets/scripts/main.js', 'www/main.js');
    mix.less('../../../src/assets/styles', 'www/main.css');
    mix.copy('node_modules/react-select/dist/react-select.min.css', 'www/lib/react-select.css');
    mix.browserSync({
        files: 'www/*',
        proxy: 'devshop.dev'
    });
});
