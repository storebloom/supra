<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Supra_Custom
 */

$fb  = get_option( 'supra-facebook', true );
$fb  = ! empty( $fb ) ? $fb : '#';
$tw  = get_option( 'supra-twitter', true );
$tw  = ! empty( $tw ) ? $tw : '#';
$li  = get_option( 'supra-linkedin', true );
$li  = ! empty( $li ) ? $li : '#';
$rss = get_option( 'supra-rss', true );
$rss = ! empty( $rss ) ? $rss : '#';
?>

	</div><!-- #content -->
<div class="global-footer-section">
<div data-aos="zoom-in-down" class="middle-graphic-wrap">
	<?php get_template_part( 'images/inline', 'long-lines-icons.svg' ); ?>
</div>

<?php require locate_template( 'template-parts/footer-section.php' ); ?>
</div>
<footer id="colophon" class="site-footer">
		<div class="first-footer-area"><?php dynamic_sidebar( 'footer-1' ); ?></div>
		<div class="second-footer-area"><?php dynamic_sidebar( 'footer-2' ); ?></div>
		<div class="third-footer-area"><?php dynamic_sidebar( 'footer-3' ); ?></div>
		<div class="fourth-footer-area">
			<?php dynamic_sidebar( 'footer-4' ); ?>
			<a href="<?php echo esc_attr( $fb ); ?>">
				<?php get_template_part( 'images/inline', 'fb-icon.svg' ); ?>
			</a>
			<a href="<?php echo esc_attr( $tw ); ?>">
				<?php get_template_part( 'images/inline', 'twitter-icon.svg' ); ?>
			</a>
			<a href="<?php echo esc_attr( $li ); ?>">
				<?php get_template_part( 'images/inline', 'linkedin-icon.svg' ); ?>
			</a>
			<a href="<?php echo esc_attr( $rss ); ?>">
				<?php get_template_part( 'images/inline', 'rss-icon.svg' ); ?>
			</a>
		</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
