<?php
/**
 * Template part for careers page section 3
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'careers-section', '3', $id );
?>
<div id="careers-section-3" class="careers-section">
	<div data-aos="zoom-in-right" class="section-3-left">
		<?php if ( isset( $section_info['image-1'] ) && '' !== $section_info['image-1'] ) : ?>
			<div class="image-wrap">
				<img src="<?php echo esc_attr( $section_info['image-1'] ); ?>">

				<div class="float-image-wrap">
					<img src="<?php echo esc_attr( $section_info['image-2'] ); ?>">
				</div>
			</div>
		<?php endif; ?>

		<?php if ( isset( $section_info['content-1'] ) && '' !== $section_info['content-1'] ) : ?>
			<div class="section-3-content">
				<?php echo wp_kses_post( $section_info['content-1'] ); ?>
			</div>
		<?php endif; ?>

		<div data-aos="zoom-in-down" class="line-graphic-wrap">
			<?php get_template_part( 'images/inline', 'mid-long-lines.svg' ); ?>
		</div>
	</div>
</div>
