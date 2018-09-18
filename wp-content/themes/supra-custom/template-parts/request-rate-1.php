<?php
/**
 * Template part for request rate page section 1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'request-rate-section', '1', get_the_ID() );
?>
<div data-section="1" id="request-rate-section-1" class="request-rate-section" style="<?php echo esc_attr( $main_image ); ?>">
	<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
		<h1 class="section-title">
			<?php echo wp_kses_post( $section_info['title'] ); ?>
		</h1>
	<?php endif; ?>

	<div class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
	</div>
</div>
