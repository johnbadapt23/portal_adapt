// SETTINGS
var fs = require('fs');
var path_ = require('path');
var requireDir = require('require-dir')('./source/gulp/tasks', {
    recurse: true,
    // Skips macOS AppleDouble shadow files (._*) and other dotfiles that can
    // end up in this tree (e.g. from zip/network-share copies). These are
    // gitignored so CI never hits this, but a local checkout can still have
    // them on disk and require-dir's default readdir has no such filter.
    filter: function (fullPath) {
        return path_.basename(fullPath).charAt(0) !== '.';
    }
});
var settings = JSON.parse(fs.readFileSync('./package.json', 'utf8'));
var config = require('./source/gulp/config.js');
var path = require('./source/gulp/paths.js');

// INCLUDES
var gulp = require('gulp');

// TASKS
gulp.task('__start', gulp.parallel(
    config.serve.task,
    'watch'
));

gulp.task('_build', gulp.series(
    gulp.parallel(
        'build:fonts',
        'build:icons',
        'build:images'
    ),
    gulp.parallel(
        'build:scripts',
        'build:styles'
    ),
    'build:php'
));
