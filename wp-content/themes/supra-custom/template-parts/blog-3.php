<?php
/**
 * Template part for blog page section 3
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

?>
<div id="blog-section-3" class="blog-section">
	<div class="blog-list">
		<?php
		$args = array(
			'post_type' => 'post',
		);

		$post_query = new WP_Query( $args );

		if ( $post_query->have_posts() ) :
			while ( $post_query->have_posts() ) :
				$post_query->the_post();

				$id         = get_the_ID();
				$thumbnail  = get_the_post_thumbnail_url( $id );
				$main_image = false !== $thumbnail ? $thumbnail : '';
				?>
				<div class="article-wrap">
					<a href="<?php echo esc_url( the_permalink() ); ?>">
						<div class="article-thumb">
							<img src="<?php echo esc_url( $main_image ); ?>">
						</div>
						<div class="article-title">
							<?php the_title(); ?>
						</div>
						<div class="article-content">
							<?php echo wp_kses_post( wp_trim_words( get_the_content(), 31, '...' ) ); ?>
						</div>
						<div class="article-read-more">
							<?php echo esc_html__( 'Read More', 'supra-custom' ); ?>
						</div>
					</a>
				</div>
				<?php
			endwhile;
		endif;
		?>
	</div>
</div>
