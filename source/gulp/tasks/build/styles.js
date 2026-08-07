var gulp = require('gulp');
var concat = require("gulp-concat");
var sass = require('gulp-sass')(require('sass'));
var sassGlob = require('gulp-sass-glob');
var prefixer = require('gulp-autoprefixer').default; // v10+ is ESM-only; CJS interop requires .default
var cleanCSS = require('gulp-clean-css');
var browserSync = require("browser-sync");
var reload = browserSync.reload;

var path = require('../../paths.js');
var error = require('../../error.js');

// The build used to compile everything (vendor libraries + every
// templates/**/*.scss file) into a single main.min.css, loaded on every
// page regardless of which template was active. header.php now enqueues
// bundles conditionally based on the current page template (see its
// wp_enqueue_style calls), so the build compiles one bundle per entry
// point instead of concatenating them all together:
//
//   global.min.css      - vendor libraries + base/forms/sections/print +
//                          header/footer partials. Always loaded.
//   core.min.css         - every templates/**/*.scss file EXCEPT the ones
//                          split out below. Default bundle for any
//                          template not explicitly special-cased.
//   tpl-agenda.min.css   - templates/template-agenda.php only (verified
//                          exclusive - see main-tpl-agenda.scss).
//   tpl-events.min.css   - templates/template-events.php (and, loaded
//                          alongside core.min.css, template-events-portal.php
//                          - see main-tpl-events.scss).
//
// Extending this list means adding a new main-tpl-*.scss entry point,
// excluding the matching templates/_*.scss file from main-core.scss, and
// wiring up the conditional enqueue in header.php - see the notes in
// main-core.scss before doing that for a new template.

function compileBundle(src, outputName) {
    return gulp.src(src)
        .pipe(sassGlob())
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .on('error', error.handler)
        .pipe(prefixer())
        .pipe(cleanCSS())
        .pipe(concat(outputName))
        .pipe(gulp.dest(path.build.styles))
        .pipe(reload({stream: true}));
}

function buildGlobalStyles() {
    // Vendor CSS is concatenated with the compiled main-global.scss output
    // into one file, same as the old main.min.css did - these libraries
    // aren't split per-template (out of scope for this pass), so they stay
    // in the always-loaded bundle.
    return gulp.src(path.src.vendorStyles.concat(['source/scss/main-global.scss']))
        .pipe(sassGlob())
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .on('error', error.handler)
        .pipe(prefixer())
        .pipe(cleanCSS())
        .pipe(concat('global.min.css'))
        .pipe(gulp.dest(path.build.styles))
        .pipe(reload({stream: true}));
}

function buildCoreStyles() {
    return compileBundle('source/scss/main-core.scss', 'core.min.css');
}

function buildAgendaStyles() {
    return compileBundle('source/scss/main-tpl-agenda.scss', 'tpl-agenda.min.css');
}

function buildEventsStyles() {
    return compileBundle('source/scss/main-tpl-events.scss', 'tpl-events.min.css');
}

gulp.task('build:styles', gulp.parallel(
    buildGlobalStyles,
    buildCoreStyles,
    buildAgendaStyles,
    buildEventsStyles
));
