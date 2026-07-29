var gulp = require('gulp');
var zip = require('gulp-zip').default; // ESM-only package; CJS interop requires .default

var path = require('../../paths.js');

gulp.task('deploy:zip', function() {
    return gulp.src(path.deploy.files, { encoding: false }) // covers binary files (images, fonts, etc.) in this broad glob
        .pipe(zip(path.deploy.archive))
        .pipe(gulp.dest(path.deploy.folder));
});
