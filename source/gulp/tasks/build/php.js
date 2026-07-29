var gulp = require('gulp');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

gulp.task('build:php', function () {
    return gulp.src(path.src.php)
        .pipe(reload({stream: true}));
});
