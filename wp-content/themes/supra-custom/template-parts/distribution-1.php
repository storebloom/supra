<?php
/**
 * Template part for distribution page section 1
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'distribution-section', '1', get_the_ID() );
?>
<div data-section="1" id="distribution-section-1" class="distribution-section" style="<?php echo esc_attr( $main_image ); ?>">
	<?php if ( isset( $section_info['image'] ) && '' !== $section_info['image'] ) : ?>
		<div class="section-logo">
			<img src="<?php echo esc_attr( $section_info['image'] ); ?>">
		</div>
	<?php endif; ?>

	<div class="request-info-wrap">
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
	<div data-aos="zoom-in-down" class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
	</div>
</div>
