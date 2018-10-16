<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Supra_Custom
 */

$phone_number = get_option( 'supra-phone', true );
$phone_number = ! empty( $phone_number ) ? explode( ',', $phone_number ) : [ '1', '2', '3' ];
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="login-account-wrap">
			<div id="user-signin">
				<?php echo esc_html__( 'Sign In', 'supra-custom' ); ?>
			</div>
			<div class="login-spacer"></div>
			<div id="user-create">
				<?php echo esc_html__( 'Create Account', 'supra-custom' ); ?>
			</div>
		</div>
		<div class="site-branding">
			<a href="<?php echo esc_url( get_home_url() ); ?>">
				<?php get_template_part( 'images/inline', 'supra-logo.svg' ); ?>
			</a>
		</div><!-- .site-branding -->
		<div class="header-phone-wrap">
			<?php if ( wp_is_mobile() ) : ?>
				<img id="open-supra-menu" class="open-menu-icon" src="<?php echo esc_url( get_template_directory_uri() . '/images/icon-menu.svg' ); ?>" alt="Open Navigation Menu" />
				<?php
			else :
				?>
			<ul class="phone-numbers">
				<li class="phone-number-item">
					<?php echo esc_html__( 'Transportation', 'supra-custom' ); ?>
					<?php echo esc_html( $phone_number[0] ); ?>
				</li>
				<li class="phone-number-item">
					<?php echo esc_html__( 'Distribution', 'supra-custom' ); ?>
					<?php echo esc_html( $phone_number[1] ); ?>
				</li>
				<li class="phone-number-item">
					<?php echo esc_html__( 'Logistics', 'supra-custom' ); ?>
					<?php echo esc_html( $phone_number[1] ); ?>
				</li>
			</ul>
				<?php
			endif;
			?>
		</div>
		<nav id="site-navigation" class="main-navigation">
			<div class="supra-main-menu-open">
				<img id="open-supra-menu" class="open-menu-icon" src="<?php echo esc_url( get_template_directory_uri() . '/images/icon-menu.svg' ); ?>" alt="Open Navigation Menu" />
			</div>
			<div class="supra-main-menu-close">
				<?php echo esc_html__( 'Close', 'supra-custom' ); ?>
				<img id="close-supra-menu" class="menu-icon" src="<?php echo esc_url( get_template_directory_uri() . '/images/icon-close.svg' ); ?>" alt="Close Navigation Menu" />
			</div>

			<div class="main-menu-overlay">
				<ul class="supra-primary-menu">
					<?php
					$menu_items   = wp_get_nav_menu_items( 'main-menu' );
					$current_page = get_the_id();

					if ( is_array( $menu_items ) ) :
						foreach ( $menu_items as $num => $menu_item ) :
							$menu_item_id = $menu_item->object_id;
							$current      = $current_page === $menu_item_id ? esc_html( ' supra-primary-menu-item-current' ) : '';
							$contact      = 'Contact' === $menu_item->title ? ' contact-menu-item' : '';
							?>
							<li class="supra-primary-menu-item<?php echo esc_attr( $current . $contact ); ?>">
								<a href="<?php echo esc_url( $menu_item->url ); ?>">
									<?php echo esc_html( $menu_item->title ); ?>
								</a>
							</li>
							<?php
						endforeach;
					endif;
					?>
				</ul>
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
