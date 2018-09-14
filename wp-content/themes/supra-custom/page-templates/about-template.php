<?php
/**
 * Template Name: About
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
	// Set for 5 sections.  Change integer to add or remove sections.
	for ( $c = 1; $c <= 4; $c++ ) {
		include locate_template( 'template-parts/about-' . $c . '.php' );
	}

endwhile; // End of the loop.

get_footer();
