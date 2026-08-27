<?php
/**
 * Template Name: TNC Register Template
 */

// Identical to template-register-advantage.php (Template Name: Advantage
// Register Template) apart from the ACF field-name prefix - see the comment
// there. Also fixes a pre-existing typo where this template's testimonial
// slider used the field name tnr_registration_testimonial_slider instead of
// tnc_registration_testimonial_slider (neither exists in the ACF field
// groups today, so the section was non-functional either way).
$reg_prefix = 'tnc';
include locate_template( '/templates/template-register-advantage.php' );
