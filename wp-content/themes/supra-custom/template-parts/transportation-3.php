<?php
/**
 * Template part for transportation page section 4
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

$section_info = get_section_info( 'transportation-section', '3', $id );
?>
<div id="transportation-section-3" class="transportation-section">
	<div class="section-3-left">
		<?php if ( isset( $section_info['image-1'] ) && '' !== $section_info['image-1'] ) : ?>
			<div class="image-wrap">
				<div class="image-number">01</div>
				<img src="<?php echo esc_attr( $section_info['image-1'] ); ?>">

				<div class="float-image-wrap">
					<img src="<?php echo esc_attr( $section_info['image-2'] ); ?>">
				</div>
			</div>
		<?php endif; ?>

		<?php if ( isset( $section_info['content-1'] ) && '' !== $section_info['content-1'] ) : ?>
			<div class="section-3-content">
				<?php echo wp_kses_post( $section_info['content-1'] ); ?>
			</div>
		<?php endif; ?>

		<div class="line-graphic-wrap">
			<?php get_template_part( 'images/inline', 'bottom-left-icon.svg' ); ?>
		</div>
	</div>
	<div class="section-3-right">
		<?php if ( isset( $section_info['content-2'] ) && '' !== $section_info['content-2'] ) : ?>
			<div class="section-3-content">
				<?php echo wp_kses_post( $section_info['content-2'] ); ?>
			</div>
		<?php endif; ?>

		<?php if ( isset( $section_info['image-3'] ) && '' !== $section_info['image-3'] ) : ?>
			<div class="image-wrap">
				<div class="image-number">02</div>
				<img src="<?php echo esc_attr( $section_info['image-3'] ); ?>">
			</div>
		<?php endif; ?>
		<div class="line-graphic-wrap">
			<?php get_template_part( 'images/inline', 'top-left-icon.svg' ); ?>
		</div>
	</div>

	<div class="section-3-left second">
		<?php if ( isset( $section_info['image-4'] ) && '' !== $section_info['image-4'] ) : ?>
			<div class="image-wrap">
				<div class="image-number">03</div>
				<img src="<?php echo esc_attr( $section_info['image-4'] ); ?>">
			</div>
		<?php endif; ?>

		<?php if ( isset( $section_info['content-3'] ) && '' !== $section_info['content-3'] ) : ?>
			<div class="section-3-content">
				<?php echo wp_kses_post( $section_info['content-3'] ); ?>
			</div>
		<?php endif; ?>

		<div class="line-graphic-wrap">
			<?php get_template_part( 'images/inline', 'bottom-left-icon.svg' ); ?>
		</div>
	</div>
	<div class="section-3-right second">
		<?php if ( isset( $section_info['content-4'] ) && '' !== $section_info['content-4'] ) : ?>
			<div class="section-3-content">
				<?php echo wp_kses_post( $section_info['content-4'] ); ?>
			</div>
		<?php endif; ?>
		<?php if ( isset( $section_info['image-5'] ) && '' !== $section_info['image-5'] ) : ?>
			<div class="image-wrap">
				<div class="image-number">04</div>
				<img src="<?php echo esc_attr( $section_info['image-5'] ); ?>">
			</div>
		<?php endif; ?>

		<div class="line-graphic-wrap">
			<?php get_template_part( 'images/inline', 'top-left-icon.svg' ); ?>
		</div>
	</div>

	<div class="section-3-left third">
		<?php if ( isset( $section_info['image-6'] ) && '' !== $section_info['image-6'] ) : ?>
			<div class="image-wrap">
				<div class="image-number">05</div>
				<img src="<?php echo esc_attr( $section_info['image-6'] ); ?>">
			</div>
		<?php endif; ?>

		<?php if ( isset( $section_info['content-5'] ) && '' !== $section_info['content-5'] ) : ?>
			<div class="section-3-content">
				<?php echo wp_kses_post( $section_info['content-5'] ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
