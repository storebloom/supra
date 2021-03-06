<?php
/**
 * Template part for home page section 4
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'home-section', '4', $id );
?>
<div id="home-section-4" class="homepage-section">
	<?php if ( isset( $section_info['image-4'] ) && '' !== $section_info['image-4'] ) : ?>
		<div data-aos="zoom-in" class="image-wrap">
			<img src="<?php echo esc_attr( $section_info['image-4'] ); ?>">

			<?php if ( isset( $section_info['content4'] ) && '' !== $section_info['content4'] ) : ?>
				<div class="section-4-content">
					<?php echo wp_kses_post( $section_info['content4'] ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<div data-aos="zoom-in-right" class="right-graphic-wrap">
		<?php get_template_part( 'images/inline', 'right-icon.svg' ); ?>
	</div>

	<div data-aos="zoom-in-left" class="left-graphic-wrap">
		<?php get_template_part( 'images/inline', 'left-icon.svg' ); ?>
	</div>

	<?php if ( isset( $section_info['image-5'] ) && '' !== $section_info['image-5'] ) : ?>
		<div data-aos="zoom-in" class="second-image-wrap">
			<img src="<?php echo esc_attr( $section_info['image-5'] ); ?>">
		</div>
	<?php endif; ?>
</div>
