<?php
/**
 * Template part for home page section 5
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'home-section', '5', get_the_ID() );
?>
<div id="home-section-5" class="homepage-section">
	<?php if ( ! empty( $section_info['title'] ) ) : ?>
		<h4 class="section-title">
			<?php echo esc_html( $section_info['title'] ); ?>
		</h4>
	<?php endif; ?>

	<?php if ( isset( $section_info['content'] ) && '' !== $section_info['content'] ) : ?>
		<div class="section-5-content">
			<?php echo wp_kses_post( $section_info['content'] ); ?>
		</div>
	<?php endif; ?>

	<div class="home-5-info-wrap">
		<?php for ( $b = 1; $b <= 6; $b++ ) : ?>
			<div class="home-5-info-item">
				<?php echo wp_kses_post( $section_info[ 'image-content-' . (string) $b ] ); ?>
			</div>
		<?php endfor; ?>
	</div>

	<?php if ( isset( $section_info['image'] ) && '' !== $section_info['image'] ) : ?>
		<div data-aos="zoom-in" class="third-image-wrap">
			<img src="<?php echo esc_attr( $section_info['image'] ); ?>">
		</div>
	<?php endif; ?>

	<div data-aos="zoom-in-down" class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'home-long-lines.svg' ); ?>
	</div>
</div>
