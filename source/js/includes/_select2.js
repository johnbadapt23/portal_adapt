(function($) {

// Select2, split out of main.js into its own bundle (assets/js/select2.min.js
// - see source/gulp/paths.js/scripts.js and functions.php's
// my_enqueue_scripts()) since it only ever targets $('select'), and only
// 13 of roughly 180 templates in this theme render a <select> at all.
//
// Exposed on window (rather than a bare function declaration) since
// main.js's own IIFE calls this from three separate places - on
// $(document).ready(), on $(window).on('load'), and on $(window).on('resize')
// - and needs a global reference to call into, guarded with
// typeof select2 === 'function' so those call sites stay harmless no-ops
// on the ~167 templates that don't load this bundle at all.
window.select2 = function () {
	if ($('form').hasClass('hs-form')) {
	} else {
		if ($('form').hasClass('mepr-form')) {
			$('select').select2();
		} else {
			$('select').select2({minimumResultsForSearch: -1});
		}
	}
};

})(jQuery);
