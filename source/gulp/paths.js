module.exports = {
    build: {
        base: 'assets/',
        scripts: 'assets/js/',
        styles: 'assets/css/',
        images: 'assets/images/',
        fonts: 'assets/fonts/',
        favicons: 'assets/icons/'
    },
    src: {
        html: '**/*.html',
        php: '**/*.php',
        // perfect-scrollbar (JS + CSS, see vendorStyles below) removed -
        // its only call site was main.js's "Ecosystem team popup" handler
        // on a.speaker-popup, which doesn't exist in any current template,
        // ACF field, or other JS (confirmed via repo-wide grep). Dead
        // weight on every single page load; the handler and its matching
        // dead SCSS in _kits.scss were removed alongside this.
        //
        // isotope-layout also moved out of this always-loaded bundle - see
        // isotopeScripts below - since it's only ever used against
        // .kits-listing.grid, exclusive to templates/template-kit-type.php
        // and templates/template-customer.php.
        scripts: [
            'node_modules/owl.carousel/dist/owl.carousel.js',
            'node_modules/jquery-match-height/dist/jquery.matchHeight.js',
            'node_modules/magnific-popup/dist/jquery.magnific-popup.js',
            'node_modules/mediaelement/build/mediaelement.js',
            'node_modules/slick-carousel/slick/slick.js',
            'node_modules/js-cookie/dist/js.cookie.js',
            'node_modules/jquery.scrollto/jquery.scrollTo.js',
            'node_modules/jquery.localscroll/jquery.localScroll.js',
            'node_modules/select2/dist/js/select2.js',
            'node_modules/flexslider/jquery.flexslider.js',
            'source/js/main.js'
        ],

        // Isotope + its filtering glue (source/js/includes/_isotope.js),
        // concatenated into its own assets/js/isotope.min.js - see
        // build:scripts:isotope in scripts.js and the conditional enqueue
        // in functions.php's my_enqueue_scripts(). Kept out of the
        // sitewide main.min.js bundle above; only the two kits/customer
        // templates need it.
        isotopeScripts: [
            'node_modules/isotope-layout/dist/isotope.pkgd.js',
            'source/js/includes/_isotope.js'
        ],

        // Vendor CSS, concatenated into global.min.css alongside
        // main-global.scss's compiled output (source/gulp/tasks/build/styles.js) -
        // these aren't split per-template, so they stay in the
        // always-loaded bundle. main.scss (the old single-bundle entry
        // point) is superseded by main-global.scss / main-core.scss /
        // main-tpl-*.scss - see the comments at the top of styles.js.
        vendorStyles: [
            'node_modules/owl.carousel/dist/assets/owl.carousel.css',
            'node_modules/magnific-popup/dist/magnific-popup.css',
            'node_modules/slick-carousel/slick/slick.scss',
            'node_modules/flexslider/flexslider.css',
            'node_modules/mediaelement/build/mediaelementplayer.min.css',
            'node_modules/mediaelement/build/mediaelementplayer-legacy.min.css',
            'node_modules/hover.css/css/hover-min.css',
            'node_modules/select2/dist/css/select2.css'
        ],
        images: [
            'source/images/**/*.jpg',
            'source/images/**/*.gif',
            'source/images/**/*.svg',
            'source/images/**/*.png'
        ],
        fonts: 'source/fonts/*.{ttf,otf}',
        icons: 'source/icons/*.svg',
        favicon: {
            master: 'source/images/favicon.png',
            path: '/assets/icons/',
            data: 'faviconData.json',
            html: 'templates/partials/_icons.php',
            design: {
    			ios: {
    				pictureAspect: 'backgroundAndMargin', // backgroundAndMargin, noChange
    				backgroundColor: '#ffffff',
    				margin: '21%'
    			},
    			desktopBrowser: {},
    			windows: {
    				pictureAspect: 'whiteSilhouette', // noChange, whiteSilhouette
    				backgroundColor: '#b69e58',
    				onConflict: 'override'
    			},
    			androidChrome: {
    				pictureAspect: 'backgroundAndMargin', // noChange, backgroundAndMargin, shadow
    				margin: '17%',
    				backgroundColor: '#ffffff',
    				themeColor: '#ffffff',
    				manifest: {
    					name: 'Hayball',
    					display: 'browser', // browser, standalone
    					orientation: 'notSet',
    					onConflict: 'override'
    				}
    			},
    			safariPinnedTab: {
    				pictureAspect: 'silhouette', // noChange, silhouette, blackAndWhite
    				themeColor: '#000000'
    			}
    		},
            settings: {
    			compression: 5, // 0-5
    			scalingAlgorithm: 'Lanczos', // Mitchell, NearestNeighbor, Cubic, Bilinear, Lanczos, Spline
    			errorOnImageTooSmall: false
    		}
        }
    },
    watch: {
        html: '**/*.html',
        php: '**/*.php',
        scripts: 'source/js/**/*.js',
        style: 'source/scss/**/*.scss',
        images: 'source/images/**/*.*',
        fonts: 'source/fonts/**/*.ttf',
        icons: 'source/icons/*.svg',
        favicon: 'source/images/favicon.png'
    },
    deploy: {
        files: '**/*',
        folder: './',
        archive: 'portal-adapt.zip',
        repository: 'https://github.com/johnbadapt23/portal_adapt.git'
    }
};
