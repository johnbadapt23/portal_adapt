const globals = require('globals');

module.exports = [
    {
        // source/js/main.js is not valid JS on its own - it contains a
        // gulp-file-include preprocessor directive (@@include(...), see
        // source/gulp/tasks/build/scripts.js) that only becomes real
        // JavaScript once the build concatenates includes/_maps.js in.
        // ESLint's parser can't handle that directive, so this file is
        // linted manually / via targeted checks instead of through this
        // config until it's worth restructuring the include mechanism.
        // The dot-underscore patterns exclude macOS AppleDouble resource-fork
        // files (._foo.js, .__foo.js) that can end up on disk locally but are
        // never tracked in git and never touched by the build.
        ignores: ['source/js/main.js', '**/.*', '**/._*', '**/.__*']
    },
    {
        files: ['source/js/**/*.js'],
        languageOptions: {
            ecmaVersion: 2021,
            sourceType: 'script',
            globals: {
                ...globals.browser,
                $: 'readonly',
                jQuery: 'readonly',
                ajaxobject: 'readonly',
                // Third-party libraries loaded as separate <script> tags
                // (GSAP/ScrollMagic stack, AOS, js-cookie, PerfectScrollbar,
                // Isotope, FormCrafts embed widget) rather than bundled
                // through npm, so ESLint has no other way to know about them.
                TweenLite: 'readonly',
                TimelineMax: 'readonly',
                Linear: 'readonly',
                ScrollMagic: 'readonly',
                gsap: 'readonly',
                AOS: 'readonly',
                Cookies: 'readonly',
                PerfectScrollbar: 'readonly',
                Isotope: 'readonly',
                FormCraftsPopup: 'readonly',
                google: 'readonly'
            }
        },
        rules: {
            'no-var': 'error',
            'prefer-const': ['error', { destructuring: 'any' }],
            'no-undef': 'error'
        }
    }
];
