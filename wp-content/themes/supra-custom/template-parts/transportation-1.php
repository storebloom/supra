<?php
/**
 * Template part for transportation page section 1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'transportation-section', '1', get_the_ID() );
?>
<div data-section="1" id="transportation-section-1" class="transportation-section" style="<?php echo esc_attr( $main_image ); ?>">
	<?php if ( isset( $section_info['image'] ) && '' !== $section_info['image'] ) : ?>
		<div class="section-logo">
			<img src="<?php echo esc_attr( $section_info['image'] ); ?>">
		</div>
	<?php endif; ?>

	<div class="request-info-wrap">
		<?php echo ! empty( $section_info['form'] ) ? do_shortcode( $section_info['form'] ) : ''; ?>
	</div>
	<div class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
	</div>
</div>
