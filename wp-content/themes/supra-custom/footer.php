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

?>

	</div><!-- #content -->

<footer id="colophon" class="site-footer">
	<div class="middle-graphic-wrap">
		<?php get_template_part( 'images/inline', 'middle-line-icon.svg' ); ?>
	</div>

	<?php if ( is_page( 'contact' ) ) : ?>
		<h4><?php echo esc_html__( 'Contact Us Today', 'supra-custom' ); ?></h4>

		<h3><?php echo esc_html__( 'How Can We Help.', 'supra-custom' ); ?></h3>

		<?php echo do_shortcode( '[contact-form-7 id="149" title="Contact Form"]' ); ?>
	<?php else : ?>
		<div class="first-footer-area"><?php dynamic_sidebar( 'footer-1' ); ?></div>
		<div class="second-footer-area"><?php dynamic_sidebar( 'footer-2' ); ?></div>
		<div class="third-footer-area"><?php dynamic_sidebar( 'footer-3' ); ?></div>
		<div class="fourth-footer-area"><?php dynamic_sidebar( 'footer-4' ); ?></div>
	<?php endif; ?>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
