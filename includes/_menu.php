<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Direct access not allowed.
}

// theme_menus
function theme_menus() {
    register_nav_menus(array(
        'main-menu' => __('Main Menu', 'theme'),
        'new-menu' => __('New Menu', 'theme'),
        'bottom-menu' => __('Bottom Menu', 'theme'),
        'mobile-menu' => __('Mobile Menu', 'theme'),
        'footer-menu' => __('Footer Menu', 'theme')
    ));
}

// theme_nav
function theme_nav($name){
	wp_nav_menu(
		array(
			'theme_location'  => $name . '-menu',
			'container'       => '',
			'container_class' => false,
			'container_id'    => '',
			'menu_class'      => $name . '-menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul>%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		)
	);
}

// theme_nav
function theme_nav_mobile($name){
	wp_nav_menu(
		array(
			'theme_location'  => $name . '-menu',
			'container'       => '',
			'container_class' => false,
			'container_id'    => '',
			'menu_class'      => $name . '-menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul>%3$s</ul>',
			'depth'           => 0,
			'walker'          => ''
		)
	);
}

?>
