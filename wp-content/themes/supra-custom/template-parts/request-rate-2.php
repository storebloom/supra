<?php
/**
 * Template part for request rate page section 2
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info      = get_section_info( 'request-rate-section', '2', get_the_ID() );
$show_ftl          = isset( $_GET['type'] ) && 'ftl' === $_GET['type'] ? '' : 'display: none;'; // CSRF ok.
$show_drayage      = ( isset( $_GET['type'] ) && 'drayage' === $_GET['type'] ) || ! isset( $_GET['type'] ) ? '' : 'display: none;'; // CSRF ok.
$show_distribution = isset( $_GET['type'] ) && 'distribution' === $_GET['type'] ? '' : 'display: none;'; // CSRF ok.
?>
<div data-section="1" id="request-rate-section-2" class="request-rate-section" style="<?php echo esc_attr( $main_image ); ?>">
	<?php if ( isset( $section_info['form-drayage'] ) && '' !== $section_info['form-drayage'] ) : // CSRF ok. ?>
		<div id="drayage" class="rr-form-wrap" style="<?php echo esc_attr( $show_drayage ); ?>">
			<?php echo do_shortcode( $section_info['form-drayage'] ); ?>
		</div>
	<?php endif; ?>

	<?php if ( isset( $section_info['form-ftl'] ) && '' !== $section_info['form-ftl'] ) : // CSRF ok. ?>
		<div id="ftl" class="rr-form-wrap" style="<?php echo esc_attr( $show_ftl ); ?>">
			<?php echo do_shortcode( $section_info['form-ftl'] ); ?>
		</div>
	<?php endif; ?>

	<?php if ( isset( $section_info['form-distribution'] ) && '' !== $section_info['form-distribution'] ) : // CSRF ok. ?>
		<div id="distribution" class="rr-form-wrap" style="<?php echo esc_attr( $show_distribution ); ?>">
			<?php echo do_shortcode( $section_info['form-distribution'] ); ?>
		</div>
	<?php endif; ?>
</div>
