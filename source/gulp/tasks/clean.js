var gulp = require('gulp');
var fs = require('fs');

var path = require('../paths.js');

gulp.task('_clean', function (done) {
    fs.rm(path.build.base, { recursive: true, force: true }, function () {
        done();
    });
});
