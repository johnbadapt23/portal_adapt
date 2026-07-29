var gulp = require('gulp');
var rename = require('gulp-rename');
var iconfont = require('gulp-iconfont');
var iconfontCss = require('gulp-iconfont-css');

var path = require('../../paths.js');
var timestamp = Math.round(Date.now()/1000);

gulp.task('build:icons', function (done) {
    gulp.src(path.src.icons)
        .pipe(iconfontCss({
          fontName: 'icons',
          targetPath: 'icons.css',
          fontPath: '../fonts/'
        }))
        .pipe(iconfont({
            fontName: 'icons',
            // svgicons2svgfont (gulp-iconfont's underlying dependency) renamed
            // this option; the old name now throws instead of being accepted.
            prependUnicode: true,
            formats: ['ttf', 'eot', 'woff', 'woff2', 'svg'],
            timestamp: timestamp,
        }))
        .pipe(gulp.dest(path.build.fonts))
        .on('end', function () {
            gulp.src(path.build.fonts + 'icons.css')
                .pipe(rename('_icons.scss'))
                .pipe(gulp.dest('source/scss/global/'))
                .on('end', done);
        });
});
