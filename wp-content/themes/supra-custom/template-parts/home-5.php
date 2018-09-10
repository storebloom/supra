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

	<div class="home-5-info-wrap">
		<?php for ( $b = 1; $b <= 6; $b++ ) : ?>
			<div class="home-5-info-item">
				<div class="home-5-icon">
					<?php get_template_part( 'images/inline', 'home-5-icon-' . (string) $b . '.svg' ); ?>
				</div>
				<div class="home-5-info-content">
					<?php echo esc_html( $section_info[ 'image-content-' . (string) $b ] ); ?>
				</div>
			</div>
		<?php endfor; ?>
	</div>

	<?php if ( isset( $section_info['image'] ) && '' !== $section_info['image'] ) : ?>
		<div class="image-wrap">
			<img src="<?php echo esc_attr( $section_info['image'] ); ?>">
		</div>
	<?php endif; ?>

	<div class="middle-graphic-wrap long">
		<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
	</div>
</div>
