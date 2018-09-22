<?php
/**
 * Template part for about page section 3
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'about-section', '3', get_the_ID() );
$histories    = isset( $section_info['history'] ) && is_array( $section_info['history'] ) ? $section_info['history'] : array();
?>
<div id="about-section-3" class="about-section grey-back">
	<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
		<h4 class="section-title">
			<?php echo wp_kses_post( $section_info['title'] ); ?>
		</h4>
	<?php endif; ?>

	<div class="history-wrap">
		<div class="history-wrap-inner">
			<?php foreach ( $histories as $history ) : ?>
				<div class="history-item">
					<div class="history-red-image">
						<img src="<?php echo esc_attr( $history['image'] ); ?>">
					</div>
					<div class="history-content">
						<?php echo wp_kses_post( $history['content'] ); ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'about-slightly-longer.svg' ); ?>
	</div>

	<?php if ( isset( $section_info['image'] ) && '' !== $section_info['image'] ) : ?>
		<div class="section-image">
			<img src="<?php echo esc_attr( $section_info['image'] ); ?>">
		</div>
	<?php endif; ?>
</div>
