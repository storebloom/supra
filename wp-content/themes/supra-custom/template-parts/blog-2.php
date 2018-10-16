<?php
/**
 * Template part for blog page section 2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'blog-section', '2', get_the_ID() );
?>
<div id="blog-section-2" class="blog-section">
	<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
		<h4 class="section-title">
			<?php echo wp_kses_post( $section_info['title'] ); ?>
		</h4>
	<?php endif; ?>

	<?php if ( isset( $section_info['content'] ) && '' !== $section_info['content'] ) : ?>
		<div class="section-content">
			<?php echo wp_kses_post( $section_info['content'] ); ?>
		</div>
	<?php endif; ?>

	<div data-aos="zoom-in-down" class="middle-graphic-wrap down">
		<?php get_template_part( 'images/inline', 'down-arrow-center.svg' ); ?>
	</div>
</div>
