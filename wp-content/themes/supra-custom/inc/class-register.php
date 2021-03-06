<?php
/**
 * Register
 *
 * @package Supra_Custom
 */

namespace SupraCustom;

/**
 * Custom_Fields Class
 *
 * @package Supra_Custom
 */
class Register {

	/**
	 * Theme instance.
	 *
	 * @var object
	 */
	public $theme;

	/**
	 * Class constructor.
	 *
	 * @param object $plugin Plugin class.
	 */
	public function __construct( $theme ) {
		$this->theme = $theme;
	}

	/**
	 * Register the Career post type.
	 *
	 * @action init
	 */
	public function register_career() {
		$supports = array( 'title', 'editor' );
		$labels   = array(
			'name'                  => esc_html__( ' Careers', 'supra-custom' ),
			'singular_name'         => esc_html__( ' Career', 'supra-custom' ),
			'all_items'             => esc_html__( ' Careers', 'supra-custom' ),
			'menu_name'             => _x( ' Careers', 'Admin menu name', 'supra-custom' ),
			'add_new'               => esc_html__( 'Add New', 'supra-custom' ),
			'add_new_item'          => esc_html__( 'Add new career', 'supra-custom' ),
			'edit'                  => esc_html__( 'Edit', 'supra-custom' ),
			'edit_item'             => esc_html__( 'Edit career', 'supra-custom' ),
			'new_item'              => esc_html__( 'New career', 'supra-custom' ),
			'view'                  => esc_html__( 'View career', 'supra-custom' ),
			'view_item'             => esc_html__( 'View career', 'supra-custom' ),
			'search_items'          => esc_html__( 'Search careers', 'supra-custom' ),
			'not_found'             => esc_html__( 'No careers found', 'supra-custom' ),
			'not_found_in_trash'    => esc_html__( 'No careers found in trash', 'supra-custom' ),
			'parent'                => esc_html__( 'Parent career', 'supra-custom' ),
			'featured_image'        => esc_html__( ' Career image', 'supra-custom' ),
			'set_featured_image'    => esc_html__( 'Set career image', 'supra-custom' ),
			'remove_featured_image' => esc_html__( 'Remove career image', 'supra-custom' ),
			'use_featured_image'    => esc_html__( 'Use as career image', 'supra-custom' ),
			'insert_into_item'      => esc_html__( 'Insert into career', 'supra-custom' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this career', 'supra-custom' ),
			'filter_items_list'     => esc_html__( 'Filter careers', 'supra-custom' ),
			'items_list_navigation' => esc_html__( ' Careers navigation', 'supra-custom' ),
			'items_list'            => esc_html__( ' Careers list', 'supra-custom' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Career Options', 'supra-custom' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'menu_icon'          => 'dashicons-businessman',
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'career',
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => $supports,
			'show_in_rest'       => true,
		);

		register_post_type( 'career', $args );
	}

	/**
	 * Register footer widget area.
	 *
	 * @action widgets_init
	 */
	public function footer_widgets_init() {
		for ( $x = 1; $x <= 5; $x++ ) {
			register_sidebar(
				array(
					'name'          => 'Footer ' . $x,
					'id'            => 'footer-' . $x,
					'description'   => '',
					'class'         => '',
					'before_widget' => '<li id="%1$s" class="widget %2$s">',
					'after_widget'  => '</li>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '</h2>',
				)
			);
		}
	}

	/**
	 * Add theme options menu page.
	 *
	 * @action admin_menu
	 */
	public function admin_menu() {
		add_theme_page(
			'Theme Options',
			'Supra Options',
			'edit_theme_options',
			'themes_options',
			array( $this, 'option_menu_display' )
		);
	}

	/**
	 * Register setting for options page.
	 *
	 * @action admin_init
	 */
	public function register_options() {
		register_setting( 'theme-options-settings', 'supra-phone' );
		register_setting( 'theme-options-settings', 'supra-facebook' );
		register_setting( 'theme-options-settings', 'supra-twitter' );
		register_setting( 'theme-options-settings', 'supra-linkedin' );
		register_setting( 'theme-options-settings', 'supra-rss' );
		register_setting( 'theme-options-settings', 'supra-contact-back' );
	}

	/**
	 * Theme options display.
	 */
	public function option_menu_display() {
		?>
		<div class="wrap">
			<h1>Supra Theme Options</h1>

			<form method="post" action="options.php">
				<?php settings_fields( 'theme-options-settings' ); ?>
				<?php do_settings_sections( 'theme-options-settings' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Phone Number</th>
						<td>
							<input type="text" name="supra-phone" value="<?php echo esc_attr( get_option( 'supra-phone' ) ); ?>" size="60"/>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Facebook URL</th>
						<td>
							<input type="text" name="supra-facebook" value="<?php echo esc_attr( get_option( 'supra-facebook' ) ); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Twitter URL</th>
						<td>
							<input type="text" name="supra-twitter" value="<?php echo esc_attr( get_option( 'supra-twitter' ) ); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Linkedin URL</th>
						<td>
							<input type="text" name="supra-linkedin" value="<?php echo esc_attr( get_option( 'supra-linkedin' ) ); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">RSS URL</th>
						<td>
							<input type="text" name="supra-rss" value="<?php echo esc_attr( get_option( 'supra-rss' ) ); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Contact Footer Back Image</th>
						<td>
							<input type="text" name="supra-contact-back" value="<?php echo esc_attr( get_option( 'supra-contact-back' ) ); ?>" />
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue Assets for front ui.
	 *
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_assets() {
		global $post;

		wp_enqueue_script( "{$this->theme->assets_prefix}-front-ui" );
		wp_add_inline_script( "{$this->theme->assets_prefix}-front-ui", sprintf( 'SupraFrontUI.boot( %s );',
			wp_json_encode( array(
				'nonce' => wp_create_nonce( $this->theme->meta_prefix ),
				'page'  => $post->post_title,
			) )
		) );
	}
}
