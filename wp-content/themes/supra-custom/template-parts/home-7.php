<?php
/**
 * Template part for home page section 7
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'home-section', '7', get_the_ID() );
?>
<div id="home-section-7" class="homepage-section" style="background: url(<?php echo esc_attr( $section_info['image'] ); ?>);">
	<?php if ( ! empty( $section_info['title'] ) ) : ?>
		<h4 class="section-title">
			<?php echo esc_html( $section_info['title'] ); ?>
		</h4>
	<?php endif; ?>

	<?php if ( isset( $section_info['content'] ) && '' !== $section_info['content'] ) : ?>
		<div class="section-content">
			<?php echo wp_kses_post( $section_info['content'] ); ?>
		</div>
	<?php endif; ?>

	<div class="home-7-info-wrap">
		<?php for ( $c = 1; $c <= 4; $c++ ) : ?>
			<div class="home-7-info-item">
				<div class="home-7-icon">
					<?php get_template_part( 'images/inline', 'home-7-icon-' . (string) $c . '.svg' ); ?>
				</div>
				<div class="home-7-info-content">
					<?php echo esc_html( $section_info[ 'image-content-' . (string) $c ] ); ?>
				</div>
			</div>
		<?php endfor; ?>
	</div>
</div>
