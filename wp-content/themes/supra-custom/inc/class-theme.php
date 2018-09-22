<?php
/**
 * Bootstraps the Supra Custom theme.
 *
 * @package Supra_Custom
 */

namespace SupraCustom;

/**
 * Main plugin bootstrap file.
 */
class Theme extends Theme_Base {

	/**
	 * Plugin assets prefix.
	 *
	 * @var string Lowercased dashed prefix.
	 */
	public $assets_prefix;

	/**
	 * Plugin meta prefix.
	 *
	 * @var string Lowercased underscored prefix.
	 */
	public $meta_prefix;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		parent::__construct();

		// Initiate classes.
		$classes = array(
			new Custom_Fields( $this ),
			new Register( $this ),
		);

		// Add classes doc hooks.
		foreach ( $classes as $instance ) {
			$this->add_doc_hooks( $instance );
		}

		// Define some prefixes to use througout the plugin.
		$this->assets_prefix = strtolower( preg_replace( '/\B([A-Z])/', '-$1', __NAMESPACE__ ) );
		$this->meta_prefix   = strtolower( preg_replace( '/\B([A-Z])/', '_$1', __NAMESPACE__ ) );
	}

	/**
	 * Register Front Assets
	 *
	 * @action wp_enqueue_scripts
	 */
	public function register_assets() {
		// Version CSS file in a theme
		wp_enqueue_style( 'font', 'https://fonts.googleapis.com/css?family=Montserrat:500,600,700', array(), '1' );
		wp_enqueue_style( 'theme-styles', get_stylesheet_directory_uri() . '/style.css', array(), time() );
		wp_enqueue_style( 'supra-custom-style', get_stylesheet_directory_uri() . '/styles/main.css', array(), time() );
		wp_register_script( "{$this->assets_prefix}-front-ui", "{$this->dir_url}/js/supra-front-ui.js", array(
			'jquery',
			'wp-util',
		), time(), true );
	}

	/**
	 * Register admin scripts/styles.
	 *
	 * @action admin_enqueue_scripts
	 */
	public function register_admin_assets() {
		wp_register_script( "{$this->assets_prefix}-custom-fields", "{$this->dir_url}/js/supra-custom-fields.js", array(
			'jquery',
			'wp-util',
		), time(), true );
		wp_register_script( "{$this->assets_prefix}-front-ui", "{$this->dir_url}/js/supra-front-ui.js", array(
			'jquery',
			'wp-util',
		), time(), true );
	}
}
