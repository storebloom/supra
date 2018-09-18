<?php
/**
 * Template Name: Rate Request
 *
 * @package Supra_Custom
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<?php
	$id         = get_the_ID();
	$thumbnail  = get_the_post_thumbnail_url( $id );
	$main_image = false !== $thumbnail ? 'background: url(' . $thumbnail . ');' : '';

	// Set for 2 sections.  Change integer to add or remove sections.
	for ( $h = 1; $h <= 2; $h++ ) {
		include locate_template( 'template-parts/request-rate-' . $h . '.php' );
	}

	get_template_part( 'footer', 'section' );
endwhile; // End of the loop.

get_footer();
