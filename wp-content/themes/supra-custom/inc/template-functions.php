<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Supra_Custom
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function supra_custom_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'supra_custom_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function supra_custom_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'supra_custom_pingback_header' );

/**
 * Helper function to retreive page sections data.
 *
 * @param string  $page The page name to get info for.
 * @param string  $section The Section of the page to get info for.
 * @param integer $postid The post id to get info from.
 *
 * @return array
 */
function get_section_info( $page, $section, $postid ) {
	$the_meta = get_post_meta( $postid, 'page-meta', true );

	return ! empty( $the_meta[ $page . '-' . $section ] ) ? $the_meta[ $page . '-' . $section ] : '';
}
