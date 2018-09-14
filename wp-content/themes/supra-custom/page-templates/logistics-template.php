<?php
/**
 * Template Name: Transportation
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
	for ( $d = 1; $d <= 3; $d++ ) {
		include locate_template( 'template-parts/transportation-' . $d . '.php' );
	}

endwhile; // End of the loop.

get_footer();
