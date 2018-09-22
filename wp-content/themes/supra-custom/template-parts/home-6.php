<?php
/**
 * Template part for home page section 6
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'home-section', '6', get_the_ID() );
?>
<div id="home-section-6" class="homepage-section">
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

	<div class="supra-cta">
		<a href="/careers">
			<button class="supra-button-red">
				<?php echo esc_html__( 'View Positions', 'supra-custom' ); ?>
			</button>
		</a>
	</div>
</div>
