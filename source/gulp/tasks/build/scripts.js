var gulp = require('gulp');
var concat = require("gulp-concat");
var fileinclude = require('gulp-file-include');
var terser = require('gulp-terser');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');
var error = require('../../error.js');

gulp.task('build:scripts', function () {
    return gulp.src(path.src.scripts)
        .pipe(fileinclude({
            prefix: '@@',
            basepath: '@file'
        }))
        .on('error', error.handler)
        .pipe(terser())
        .on('error', error.handler)
        .pipe(concat('main.min.js'))
        .pipe(gulp.dest(path.build.scripts))
        .pipe(reload({stream: true}));
});
