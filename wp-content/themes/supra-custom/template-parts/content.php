<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Supra_Custom
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header" style="background: url( <?php echo esc_url( get_the_post_thumbnail_url() ); ?>">
		<?php the_title(); ?>

		<div class="middle-graphic-wrap">
			<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
		</div>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		the_content();
		?>
	</div><!-- .entry-content -->
</article>
