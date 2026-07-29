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
        scripts: [
            'node_modules/owl.carousel/dist/owl.carousel.js',
            'node_modules/jquery-match-height/dist/jquery.matchHeight.js',
            'node_modules/magnific-popup/dist/jquery.magnific-popup.js',
            'node_modules/isotope-layout/dist/isotope.pkgd.js',
            'node_modules/mediaelement/build/mediaelement.js',
            'node_modules/slick-carousel/slick/slick.js',
            'node_modules/js-cookie/dist/js.cookie.js',
            'node_modules/jquery.scrollto/jquery.scrollTo.js',
            'node_modules/perfect-scrollbar/dist/perfect-scrollbar.js',
            'node_modules/jquery.localscroll/jquery.localScroll.js',
            'node_modules/select2/dist/js/select2.js',
            'node_modules/flexslider/jquery.flexslider.js',
            'source/js/main.js'
        ],

        styles: [
            'node_modules/perfect-scrollbar/css/perfect-scrollbar.css',
            'node_modules/owl.carousel/dist/assets/owl.carousel.css',
            'node_modules/magnific-popup/dist/magnific-popup.css',
            'node_modules/slick-carousel/slick/slick.scss',
            'node_modules/flexslider/flexslider.css',
            'node_modules/mediaelement/build/mediaelementplayer.min.css',
            'node_modules/mediaelement/build/mediaelementplayer-legacy.min.css',
            'node_modules/hover.css/css/hover-min.css',
            'node_modules/select2/dist/css/select2.css',
            'source/scss/main.scss'
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
        archive: 'henry-jones.zip',
        repository: 'https://github.com/dotdevv/adapt.git'
    }
};
