var gulp = require('gulp');
var image = require('gulp-image').default; // ESM-only package; CJS interop requires .default
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

gulp.task('build:images', function () {
    // encoding: false is required for any binary file (images, fonts, zips,
    // etc.) read through gulp.src(). Its default (encoding: 'utf8') decodes
    // file bytes as UTF-8 text and re-encodes them - lossless for text
    // source, but it corrupts arbitrary binary content: a 256-byte PNG in
    // this repo came out as 419 bytes with a different hash after a bare
    // gulp.src() read it, before any plugin even touched it. That's what
    // was actually breaking gifsicle on loading.gif ("file not in GIF
    // format") - the file gifsicle received was never valid to begin with.
    return gulp.src(path.src.images, { encoding: false })
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
