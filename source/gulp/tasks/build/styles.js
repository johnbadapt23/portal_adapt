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
// page regardless of which template was active. adapt_enqueue_template_styles()
// in functions.php now enqueues bundles conditionally based on the current
// page template, so the build compiles one bundle per entry point instead
// of concatenating them all together:
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
//   tpl-flexible.min.css - templates/template-flexible.php only (see
//                          main-tpl-flexible.scss - unlike agenda/events this
//                          one keeps its source files in main-core.scss too,
//                          since they're shared with other templates; it's
//                          a verified-safe subset, not an exclusive file).
//   tpl-home.min.css     - templates/template-home.php only - same
//                          verified-safe-subset shape as tpl-flexible, see
//                          main-tpl-home.scss. NOTE: this is NOT the real
//                          homepage template despite the name - see
//                          tpl-portal-flexible.min.css below.
//   tpl-portal-flexible.min.css - templates/template-portal-flexible.php -
//                          this IS the real homepage template (confirmed
//                          live via body class template-portal-flexible).
//                          Same verified-safe-subset shape, see
//                          main-tpl-portal-flexible.scss.
//   tpl-single-post.min.css - templates/single-post.php - not a Page
//                          template, gated by is_singular('post') instead
//                          of is_page_template() (see
//                          adapt_enqueue_template_styles() in functions.php).
//                          Same verified-safe-subset shape, see
//                          main-tpl-single-post.scss for what's kept in and
//                          why (needs more than the usual "pooled six").
//
// Extending this list means adding a new main-tpl-*.scss entry point,
// excluding the matching templates/_*.scss file from main-core.scss (only if
// truly exclusive - see main-tpl-flexible.scss for the alternative when it
// isn't), and wiring up the conditional enqueue in
// adapt_enqueue_template_styles() in functions.php - see the notes in
// main-core.scss before doing that for a new template.

// clean-css defaults to level 1 optimizations only (whitespace/comment
// stripping, basic value normalization) - level 2 adds safe structural
// optimizations (merging non-adjacent rules with identical selectors or
// bodies, removing duplicate rules/properties, restructuring shorthands,
// deduping @font-face/@media blocks, etc.) that are all still
// behavior-preserving, just not attempted at level 1. Measured on this
// theme's actual bundles: level 2 shrinks core.min.css by ~15%, global.min.css
// by ~5%, and the tpl-*.min.css bundles by up to ~23%, purely from a build
// config change - no template/SCSS edits needed. Not using level 2's
// `restructureRules`/`mergeSemantically` sub-options (both off by default
// even at level 2) since those are the more aggressive, occasionally
// order-sensitive transforms; everything level 2 does by default is the
// well-established safe set.
var CLEAN_CSS_OPTIONS = { level: 2 };

function compileBundle(src, outputName) {
    return gulp.src(src)
        .pipe(sassGlob())
        .pipe(sass({
            outputStyle: 'compressed'
        }))
        .on('error', error.handler)
        .pipe(prefixer())
        .pipe(cleanCSS(CLEAN_CSS_OPTIONS))
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
        .pipe(cleanCSS(CLEAN_CSS_OPTIONS))
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

function buildFlexibleStyles() {
    return compileBundle('source/scss/main-tpl-flexible.scss', 'tpl-flexible.min.css');
}

function buildHomeStyles() {
    return compileBundle('source/scss/main-tpl-home.scss', 'tpl-home.min.css');
}

function buildPortalFlexibleStyles() {
    return compileBundle('source/scss/main-tpl-portal-flexible.scss', 'tpl-portal-flexible.min.css');
}

function buildSinglePostStyles() {
    return compileBundle('source/scss/main-tpl-single-post.scss', 'tpl-single-post.min.css');
}

gulp.task('build:styles', gulp.parallel(
    buildGlobalStyles,
    buildCoreStyles,
    buildAgendaStyles,
    buildEventsStyles,
    buildFlexibleStyles,
    buildHomeStyles,
    buildPortalFlexibleStyles,
    buildSinglePostStyles
));
