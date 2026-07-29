var gulp = require('gulp');
var concat = require("gulp-concat");
var sass = require('gulp-sass')(require('sass'));
var sassGlob = require('gulp-sass-glob');
var prefixer = require('gulp-autoprefixer').default; // v10+ is ESM-only; CJS interop requires .default
var cleanCSS = require('gulp-clean-css');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');
var error = require('../../error.js');

gulp.task('build:styles', function () {
    return gulp.src(path.src.styles)
        .pipe(sassGlob())
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .on('error', error.handler)
        .pipe(prefixer())
        .pipe(cleanCSS())
        .pipe(concat('main.min.css'))
        .pipe(gulp.dest(path.build.styles))
        .pipe(reload({stream: true}));
});
