<?php
/**
 * Template Name: Contact
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
	for ( $g = 1; $g <= 2; $g++ ) {
		include locate_template( 'template-parts/contact-' . $g . '.php' );
	}

	get_template_part( 'footer', 'section' );
endwhile; // End of the loop.

get_footer();
