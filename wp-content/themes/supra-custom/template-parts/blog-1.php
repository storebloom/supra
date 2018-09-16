<?php
/**
 * Template part for blog page section 1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'blog-section', '1', get_the_ID() );
?>
<div data-section="1" id="blog-section-1" class="blog-section" style="<?php echo esc_attr( $main_image ); ?>">
	<div class="section-title">
		<?php echo esc_html( get_the_title() ); ?>
	</div>

	<div class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
	</div>
</div>
