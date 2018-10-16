<?php
/**
 * Template part for home page section 3
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'home-section', '3', get_the_ID() );
?>
<div id="home-section-3" class="homepage-section">

	<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
		<h4 class="section-title">
			<?php echo wp_kses_post( $section_info['title'] ); ?>
		</h4>
	<?php endif; ?>

	<div class="three-image-wrap">
	<?php
	if ( isset( $section_info['image-1'] ) && '' !== $section_info['image-1'] ) :
		for ( $x = 1; $x <= 3; $x++ ) :
			?>
			<div class="three-image-item" style="background: url(<?php echo esc_attr( $section_info[ 'image-' . $x ] ); ?>);">
				<a href="<?php echo esc_attr( $section_info[ 'image-url-' . $x ] ); ?>">
					<div class="three-image-title">
						<?php echo esc_html( $section_info[ 'image-title-' . $x ] ); ?>
					</div>
					<div class="three-image-content">
						<?php echo esc_html( $section_info[ 'image-content-' . $x ] ); ?>
					</div>
				</a>
			</div>
			<?php
		endfor;
	endif;
	?>
	</div>
	<div class="supra-cta">
		<a hre="#" class="">
			<button class="supra-button-red">
				<?php echo esc_html__( 'Learn More', 'supra-custom' ); ?>
			</button>
		</a>
	</div>
	<div data-aos="zoom-in-down" class="middle-graphic-wrap short">
		<?php get_template_part( 'images/inline', 'long-lines-icons.svg' ); ?>
	</div>
</div>
