var gulp = require('gulp');
var image = require('gulp-image');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

gulp.task('build:images', function () {
    return gulp.src(path.src.images)
        .pipe(image({
          pngquant: true,
          optipng: false,
          zopflipng: true,
          jpegRecompress: false,
          mozjpeg: true,
          gifsicle: true,
          svgo: true
        }))
        .pipe(gulp.dest(path.build.images))
        .pipe(reload({stream: true}));
});
