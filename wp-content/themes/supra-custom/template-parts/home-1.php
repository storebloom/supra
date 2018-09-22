<?php
/**
 * Template part for home page section 1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'home-section', '1', get_the_ID() );
?>
<div data-section="1" id="home-section-1" class="homepage-section" style="<?php echo esc_attr( $main_image ); ?>">
	<?php if ( isset( $section_info['title'] ) && '' !== $section_info['title'] ) : ?>
		<div class="section-title">
			<?php echo esc_html( $section_info['title'] ); ?>
		</div>
	<?php endif; ?>

	<div class="request-info-wrap">
		<h2><?php echo esc_html__( 'Request A Rate', 'supra-custom' ); ?></h2>

		<form action="/request-rate">
			<div class="form-type">
				<input type="radio" name="type" value="drayage">
				<?php echo esc_html__( 'Drayage', 'supra-custom' ); ?>
				<input type="radio" name="type" value="ftl">
				<?php echo esc_html__( 'FTL', 'supra-custom' ); ?>
				<input type="radio" name="type" value="distribution">
				<?php echo esc_html__( 'Distribution', 'supra-custom' ); ?>
			</div>
			<div class="form-zip">
				<input type="text" name="pickup" pattern="[0-9]{5}" title="" placeholder="Pickup Zip" />
			</div>
			<div class="form-zip">
				<input type="text" name="delivery" pattern="[0-9]{5}" title="" placeholder="Delivery Zip" />
			</div>
			<button type="submit" class="supra-button-red">
				<?php echo esc_html__( 'Get Quote', 'supra-custom' ); ?>
			</button>
		</form>
	</div>
	<div class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
	</div>
</div>
