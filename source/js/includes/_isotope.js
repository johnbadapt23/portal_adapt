(function($) {

// Kits Isotope Filtering
//
// Split out of main.js into its own bundle (assets/js/isotope.min.js,
// see source/gulp/paths.js/scripts.js and functions.php's my_enqueue_scripts())
// since Isotope only ever runs against .kits-listing.grid - confirmed
// exclusive to templates/template-kit-type.php and
// templates/template-customer.php - so there's no reason to ship this
// library, or this filtering code, on every other page on the site.

$(document).ready(function() {

	// init Isotope
	// Initialize Isotope
	// Scoped to .kits-listing.grid (template-kit-type.php / template-customer.php)
	// - a bare .grid selector also matched section.blogWrapper .container
	// #loop.grid on the insights/blog archive, which has no .kit-item
	// children. Isotope would still take over that container, lay out
	// zero items, and force it to height: 0px via inline style, an
	// inline override no stylesheet clearfix can win against.
	const grids = document.querySelectorAll('.kits-listing.grid');
	const isos = [];

	grids.forEach(function(grid) {
		const iso = new Isotope(grid, {
			itemSelector: '.kit-item',
			layoutMode: 'fitRows'
		});

		isos.push(iso);
	});

	// filter functions
	const filterFns = {
		// show if number is greater than 50
		numberGreaterThan50: function( itemElem ) {
			const number = itemElem.querySelector('.number').textContent;
			return parseInt( number, 10 ) > 50;
		},
		// show if name ends with -ium
		ium: function( itemElem ) {
			const name = itemElem.querySelector('.name').textContent;
			return name.match( /ium$/ );
		}
	};

	// Bind filter button click for each button group
	const filterGroups = document.querySelectorAll('.filter-group-listing');

	filterGroups.forEach(function(filtersElem) {
		filtersElem.addEventListener('click', function(event) {
			let filterValue;
			const target = event.target;

			// Find the nearest <a> element by traversing the DOM
			const anchorElement = target.closest('a');

			if (anchorElement) {
				filterValue = anchorElement.getAttribute('data-filter');


				// Toggle the 'is-checked' class on the clicked <a>
				anchorElement.classList.toggle('is-checked');

				// Handle the "All" filter
				if (anchorElement.classList.contains('all-filter')) {
					// If "All" is clicked, uncheck all other filter buttons
					document.querySelectorAll('.kit-filter').forEach(function(button) {
						if (button !== anchorElement) {
							button.classList.remove('is-checked');
						}
					});
				} else {
					// If any other filter is clicked, uncheck the "All" filter
					document.querySelector('.kit-filter.all-filter').classList.remove('is-checked');
				}

				// Initialize an array to store multiple filter values
				const filterValues = [];

				// Loop through checked filter buttons to collect filter values
				const checkedFilterButtons = document.querySelectorAll('.kit-filter.is-checked');
				checkedFilterButtons.forEach(function(button) {
					filterValues.push(button.getAttribute('data-filter'));
				});

				 // If no filters are checked, check the "All" filter
				if (filterValues.length === 0) {
					document.querySelector('.kit-filter.all-filter').classList.add('is-checked');
				}

				// Combine the filter values using a comma to select multiple items
				filterValue = filterValues.join(', ');

				// Apply filter to each Isotope instance
				isos.forEach(function(iso) {
					iso.arrange({ filter: filterValue });
				});
			}
		});
	});

});

})(jQuery);
