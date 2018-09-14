<?php
/**
 * Template part for about page section 4
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'about-section', '4', get_the_ID() );
$partners     = isset( $section_info['partners'] ) && is_array( $section_info['partners'] ) ? $section_info['partners'] : array();
?>
<div id="about-section-4" class="about-section">
	<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
		<h4 class="section-title">
			<?php echo wp_kses_post( $section_info['title'] ); ?>
		</h4>
	<?php endif; ?>

	<div class="partners-wrap">
		<?php foreach ( $partners as $partner ) : ?>
			<div class="partner-item">
				<img src="<?php echo esc_attr( $partner['image'] ); ?>" alt="<?php echo esc_attr( $partner['title'] ); ?>">
			</div>
		<?php endforeach; ?>
	</div>

	<div class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
	</div>
</div>
