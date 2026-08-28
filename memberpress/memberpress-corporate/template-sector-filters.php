<?php
/**
 * Template Name: Sectors Filter Template
 */

// This is a stale fork of templates/template-sector-filters.php that fell
// behind the canonical file (missing persona filtering, sector menu_order
// sorting, the featured-post section, and other later fixes - it also had a
// leftover debug echo of a raw GET value). Both files share the exact same
// Template Name, so a page editor in wp-admin has no way to tell them apart
// in the template dropdown; delegating to the canonical file here ensures
// any page that already has this one selected gets the current, correct
// markup instead of the outdated snapshot.
include locate_template( '/templates/template-sector-filters.php' );
