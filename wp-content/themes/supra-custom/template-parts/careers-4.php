<?php
/**
 * Template part for careers page section 4
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

?>
<div id="careers-section-4" class="careers-section">
	<div class="career-list">
		<?php
		$args = array(
			'post_type' => 'career',
		);

		$post_query = new WP_Query( $args );
		?>

		<div class="careers-count">
			<?php echo esc_html__( 'Current Openings', 'supra-custom' ); ?>
			(<?php echo esc_html( $post_query->post_count ); ?>)
		</div>

		<?php
		if ( $post_query->have_posts() ) :
			while ( $post_query->have_posts() ) :
				$post_query->the_post();
				?>
				<div class="career-wrap">
					<a href="<?php echo esc_url( the_permalink() ); ?>">
						<div class="article-title">
							<?php the_title(); ?>
						</div>
						<div class="article-content">
							<?php the_content(); ?>
						</div>
						<div data-aos="zoom-in-right" class="right-arrow-icon">
							<?php get_template_part( 'images/inline', 'career-icon-black.svg' ); ?>
						</div>
					</a>
				</div>
				<?php
			endwhile;
		endif;
		?>
	</div>
</div>
