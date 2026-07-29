var gulp = require('gulp');
var deploy = require( 'gulp-deploy-git' );

var path = require('../../paths.js');

gulp.task('deploy:git', function() {
    return gulp.src('**/*', { encoding: false }) // covers binary files (images, fonts, etc.) in this broad glob
        .pipe(deploy({
            repository: path.deploy.repository
        }));
});
