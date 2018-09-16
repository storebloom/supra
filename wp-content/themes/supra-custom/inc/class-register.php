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
}
