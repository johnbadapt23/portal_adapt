var gulp = require('gulp');
var fs = require('fs');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');

// source/fonts/ doesn't exist in this repo today - assets/fonts/ (Apercu,
// HelveticaNeue, Henry-Jones, fontawesome) is static, hand-placed webfont
// output with no source/ equivalent to regenerate it from. gulp.src() on a
// glob whose base directory doesn't exist at all throws ENOENT (not just
// "0 files matched", which allowEmpty would handle) - so check for real
// input first and skip cleanly instead of failing the whole build over a
// task with nothing to do. If .ttf/.otf files are added to source/fonts/
// later, this starts actually running the conversion again automatically.
function hasFontsToConvert() {
    var dir = 'source/fonts';
    if (!fs.existsSync(dir)) return false;
    return fs.readdirSync(dir).some(function (f) {
        return /\.(ttf|otf)$/i.test(f);
    });
}

gulp.task('build:fonts', function (done) {
    if (!hasFontsToConvert()) {
        return done();
    }
    // Required lazily, only when there's actually something to convert:
    // gulp-fontgen shells out to the system `fontforge` binary as soon as
    // it's require()'d (not just when the task runs), and gulpfile.js's
    // requireDir loads every task file up front regardless of which gulp
    // task you invoke. Requiring it unconditionally at module scope means
    // every CI run - even ones that never touch fonts - needs fontforge
    // installed just to satisfy an import-time check. Gating the require
    // behind hasFontsToConvert() means CI only needs fontforge once real
    // .ttf/.otf files show up in source/fonts/.
    var fontgen = require("gulp-fontgen");
    return gulp.src(path.src.fonts, { encoding: false }) // .ttf/.otf are binary
        .pipe(fontgen({
            dest: path.build.fonts,
            css_fontpath: '../fonts/'
        }))
        .pipe(reload({stream: true}));
});
