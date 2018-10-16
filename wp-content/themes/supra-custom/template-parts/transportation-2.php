<?php
/**
 * Template part for transportation page section 2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'transportation-section', '2', get_the_ID() );
$overviews    = isset( $section_info['history'] ) && is_array( $section_info['history'] ) ? $section_info['history'] : array();
?>
<div id="transportation-section-2" class="transportation-section">
	<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
		<h4 class="section-title">
			<?php echo wp_kses_post( $section_info['title'] ); ?>
		</h4>
	<?php endif; ?>

	<?php if ( isset( $section_info['content'] ) && '' !== $section_info['content'] ) : ?>
		<h4 class="section-content">
			<?php echo wp_kses_post( $section_info['content'] ); ?>
		</h4>
	<?php endif; ?>

	<div class="overview-wrap">
		<?php foreach ( $overviews as $num => $overview ) : ?>
			<div class="overview-item">
				<a href="#<?php echo esc_attr( 'overview-' . $num ); ?> " class="overview-content" style="background: url(<?php echo esc_attr( $overview['image'] ); ?>);">
					<?php echo wp_kses_post( $overview['content'] ); ?>
				</a>
			</div>
		<?php endforeach; ?>
	</div>

	<div data-aos="zoom-in-down" class="middle-graphic-wrap down">
		<?php get_template_part( 'images/inline', 'about-mid-cir-icon-lines.svg' ); ?>
	</div>
</div>
