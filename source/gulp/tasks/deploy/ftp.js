var gulp = require('gulp');
var ftp = require( 'vinyl-ftp' );

var path = require('../../paths.js');

gulp.task('deploy:ftp', function() {
    var conn = ftp.create(path.deploy.ftp);
    return gulp.src( path.deploy.files, {
            base: path.deploy.base,
            buffer: false,
            encoding: false // covers binary files (images, fonts, etc.) in this broad glob
        })
        .pipe( conn.dest( path.deploy.ftp.directory ) )
});
