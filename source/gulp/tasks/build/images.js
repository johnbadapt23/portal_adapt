var gulp = require('gulp');
var image = require('gulp-image');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

gulp.task('build:images', function () {
    return gulp.src(path.src.images)
        .pipe(image({
          // pngquant's prebuilt binary depends on libimagequant.so at runtime,
          // which isn't present on GitHub-hosted runners, and its from-source
          // fallback needs the libimagequant git submodule that npm installs
          // don't fetch - install fails outright (imagemin/pngquant-bin#130,
          // a long-standing, recurring issue). zopflipng still does lossless
          // PNG recompression, so PNGs are still optimized, just not via
          // pngquant's lossy palette quantization.
          pngquant: false,
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
