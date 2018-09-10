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
	 * Register the Team post type.
	 *
	 * @action init
	 */
	public function register_team() {
		$supports = array( 'title', 'editor', 'thumbnail' );
		$labels   = array(
			'name'                  => esc_html__( ' Teams', 'supra-custom' ),
			'singular_name'         => esc_html__( ' Team', 'supra-custom' ),
			'all_items'             => esc_html__( ' Teams', 'supra-custom' ),
			'menu_name'             => _x( ' Teams', 'Admin menu name', 'supra-custom' ),
			'add_new'               => esc_html__( 'Add New', 'supra-custom' ),
			'add_new_item'          => esc_html__( 'Add new team', 'supra-custom' ),
			'edit'                  => esc_html__( 'Edit', 'supra-custom' ),
			'edit_item'             => esc_html__( 'Edit team', 'supra-custom' ),
			'new_item'              => esc_html__( 'New team', 'supra-custom' ),
			'view'                  => esc_html__( 'View team', 'supra-custom' ),
			'view_item'             => esc_html__( 'View team', 'supra-custom' ),
			'search_items'          => esc_html__( 'Search teams', 'supra-custom' ),
			'not_found'             => esc_html__( 'No teams found', 'supra-custom' ),
			'not_found_in_trash'    => esc_html__( 'No teams found in trash', 'supra-custom' ),
			'parent'                => esc_html__( 'Parent team', 'supra-custom' ),
			'featured_image'        => esc_html__( ' Team image', 'supra-custom' ),
			'set_featured_image'    => esc_html__( 'Set team image', 'supra-custom' ),
			'remove_featured_image' => esc_html__( 'Remove team image', 'supra-custom' ),
			'use_featured_image'    => esc_html__( 'Use as team image', 'supra-custom' ),
			'insert_into_item'      => esc_html__( 'Insert into team', 'supra-custom' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this team', 'supra-custom' ),
			'filter_items_list'     => esc_html__( 'Filter teams', 'supra-custom' ),
			'items_list_navigation' => esc_html__( ' Teams navigation', 'supra-custom' ),
			'items_list'            => esc_html__( ' Teams list', 'supra-custom' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Team Members', 'supra-custom' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'menu_icon'          => 'dashicons-groups',
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'team',
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => $supports,
			'show_in_rest'       => true,
			'taxonomy'           => array(
				'team_region',
			),
		);

		register_post_type( 'team', $args );
	}

	/**
	 * Register the Career post type.
	 *
	 * @action init
	 */
	public function register_career() {
		$supports = array( 'title', 'editor', 'thumbnail' );
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
			'taxonomy'           => array(
				'department',
			),
		);

		register_post_type( 'career', $args );
	}

	/**
	 * Register the Partner post type.
	 *
	 * @action init
	 */
	public function register_partner() {
		$supports = array( 'title', 'thumbnail' );
		$labels   = array(
			'name'                  => esc_html__( ' Partners', 'supra-custom' ),
			'singular_name'         => esc_html__( ' Partner', 'supra-custom' ),
			'all_items'             => esc_html__( ' Partners', 'supra-custom' ),
			'menu_name'             => _x( ' Partners', 'Admin menu name', 'supra-custom' ),
			'add_new'               => esc_html__( 'Add New', 'supra-custom' ),
			'add_new_item'          => esc_html__( 'Add new partner', 'supra-custom' ),
			'edit'                  => esc_html__( 'Edit', 'supra-custom' ),
			'edit_item'             => esc_html__( 'Edit partner', 'supra-custom' ),
			'new_item'              => esc_html__( 'New partner', 'supra-custom' ),
			'view'                  => esc_html__( 'View partner', 'supra-custom' ),
			'view_item'             => esc_html__( 'View partner', 'supra-custom' ),
			'search_items'          => esc_html__( 'Search partners', 'supra-custom' ),
			'not_found'             => esc_html__( 'No partners found', 'supra-custom' ),
			'not_found_in_trash'    => esc_html__( 'No partners found in trash', 'supra-custom' ),
			'parent'                => esc_html__( 'Parent partner', 'supra-custom' ),
			'featured_image'        => esc_html__( ' Partner image', 'supra-custom' ),
			'set_featured_image'    => esc_html__( 'Set partner image', 'supra-custom' ),
			'remove_featured_image' => esc_html__( 'Remove partner image', 'supra-custom' ),
			'use_featured_image'    => esc_html__( 'Use as partner image', 'supra-custom' ),
			'insert_into_item'      => esc_html__( 'Insert into partner', 'supra-custom' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this partner', 'supra-custom' ),
			'filter_items_list'     => esc_html__( 'Filter partners', 'supra-custom' ),
			'items_list_navigation' => esc_html__( ' Partners navigation', 'supra-custom' ),
			'items_list'            => esc_html__( ' Partners list', 'supra-custom' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Partners', 'supra-custom' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'menu_icon'          => 'dashicons-networking',
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'partner',
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => $supports,
			'show_in_rest'       => true,
			'taxonomy'           => array(
				'association',
			),
		);

		register_post_type( 'partner', $args );
	}

	/**
	 * Register the Use Case post type.
	 *
	 * @action init
	 */
	public function register_usecase() {
		$supports = array( 'title' );
		$labels   = array(
			'name'                  => esc_html__( ' Use Cases', 'supra-custom' ),
			'singular_name'         => esc_html__( ' Use Case', 'supra-custom' ),
			'all_items'             => esc_html__( ' Use Cases', 'supra-custom' ),
			'menu_name'             => _x( ' Use Cases', 'Admin menu name', 'supra-custom' ),
			'add_new'               => esc_html__( 'Add New', 'supra-custom' ),
			'add_new_item'          => esc_html__( 'Add new use case', 'supra-custom' ),
			'edit'                  => esc_html__( 'Edit', 'supra-custom' ),
			'edit_item'             => esc_html__( 'Edit use case', 'supra-custom' ),
			'new_item'              => esc_html__( 'New use case', 'supra-custom' ),
			'view'                  => esc_html__( 'View use case', 'supra-custom' ),
			'view_item'             => esc_html__( 'View use case', 'supra-custom' ),
			'search_items'          => esc_html__( 'Search use cases', 'supra-custom' ),
			'not_found'             => esc_html__( 'No use cases found', 'supra-custom' ),
			'not_found_in_trash'    => esc_html__( 'No use cases found in trash', 'supra-custom' ),
			'parent'                => esc_html__( 'Parent use case', 'supra-custom' ),
			'featured_image'        => esc_html__( ' Use Case image', 'supra-custom' ),
			'set_featured_image'    => esc_html__( 'Set use case image', 'supra-custom' ),
			'remove_featured_image' => esc_html__( 'Remove use case image', 'supra-custom' ),
			'use_featured_image'    => esc_html__( 'Use as use case image', 'supra-custom' ),
			'insert_into_item'      => esc_html__( 'Insert into use case', 'supra-custom' ),
			'uploaded_to_this_item' => esc_html__( 'Uploaded to this use case', 'supra-custom' ),
			'filter_items_list'     => esc_html__( 'Filter use cases', 'supra-custom' ),
			'items_list_navigation' => esc_html__( ' Use Cases navigation', 'supra-custom' ),
			'items_list'            => esc_html__( ' Use Cases list', 'supra-custom' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => esc_html__( 'Use Cases', 'supra-custom' ),
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'menu_icon'          => 'dashicons-analytics',
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array(
				'slug' => 'usecase',
			),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => $supports,
			'show_in_rest'       => true,
		);

		register_post_type( 'usecase', $args );
	}

	/**
	 * Register custom taxonomies.
	 *
	 * @action init
	 */
	public function register_taxonomies() {
		// Region labels.
		$region_labels = array(
			'name'              => _x( 'Regions', 'taxonomy general name', 'supra-custom' ),
			'singular_name'     => _x( 'Region', 'taxonomy singular name', 'supra-custom' ),
			'search_items'      => esc_html__( 'Search Regions', 'supra-custom' ),
			'all_items'         => esc_html__( 'All Regions', 'supra-custom' ),
			'parent_item'       => esc_html__( 'Parent Region', 'supra-custom' ),
			'parent_item_colon' => esc_html__( 'Parent Region:', 'supra-custom' ),
			'edit_item'         => esc_html__( 'Edit Region', 'supra-custom' ),
			'update_item'       => esc_html__( 'Update Region', 'supra-custom' ),
			'add_new_item'      => esc_html__( 'Add New Region', 'supra-custom' ),
			'new_item_name'     => esc_html__( 'New Region', 'supra-custom' ),
			'menu_name'         => esc_html__( 'Regions', 'supra-custom' ),
			'not_found'         => esc_html__( 'No region found.', 'supra-custom' ),
		);

		// Association labels.
		$association_labels = array(
			'name'              => _x( 'Associations', 'taxonomy general name', 'supra-custom' ),
			'singular_name'     => _x( 'Association', 'taxonomy singular name', 'supra-custom' ),
			'search_items'      => esc_html__( 'Search Associations', 'supra-custom' ),
			'all_items'         => esc_html__( 'All Associations', 'supra-custom' ),
			'parent_item'       => esc_html__( 'Parent Association', 'supra-custom' ),
			'parent_item_colon' => esc_html__( 'Parent Association:', 'supra-custom' ),
			'edit_item'         => esc_html__( 'Edit Association', 'supra-custom' ),
			'update_item'       => esc_html__( 'Update Association', 'supra-custom' ),
			'add_new_item'      => esc_html__( 'Add New Association', 'supra-custom' ),
			'new_item_name'     => esc_html__( 'New Association', 'supra-custom' ),
			'menu_name'         => esc_html__( 'Associations', 'supra-custom' ),
			'not_found'         => esc_html__( 'No association found.', 'supra-custom' ),
		);

		// Department labels.
		$department_labels = array(
			'name'              => _x( 'Departments', 'taxonomy general name', 'supra-custom' ),
			'singular_name'     => _x( 'Department', 'taxonomy singular name', 'supra-custom' ),
			'search_items'      => esc_html__( 'Search Departments', 'supra-custom' ),
			'all_items'         => esc_html__( 'All Departments', 'supra-custom' ),
			'parent_item'       => esc_html__( 'Parent Department', 'supra-custom' ),
			'parent_item_colon' => esc_html__( 'Parent Department:', 'supra-custom' ),
			'edit_item'         => esc_html__( 'Edit Department', 'supra-custom' ),
			'update_item'       => esc_html__( 'Update Department', 'supra-custom' ),
			'add_new_item'      => esc_html__( 'Add New Department', 'supra-custom' ),
			'new_item_name'     => esc_html__( 'New Department', 'supra-custom' ),
			'menu_name'         => esc_html__( 'Departments', 'supra-custom' ),
			'not_found'         => esc_html__( 'No department found.', 'supra-custom' ),
		);

		$taxonomies = array(
			'team'    => array(
				'slug'         => 'team_region',
				'labels'       => $region_labels,
				'hierarchical' => true,
			),
			'partner' => array(
				'slug'         => 'association',
				'labels'       => $association_labels,
				'hierarchical' => true,
			),
			'career'  => array(
				'slug'         => 'department',
				'labels'       => $department_labels,
				'hierarchical' => true,
			),
		);

		// Register all custom taxonomies
		foreach ( $taxonomies as $type => $info ) {
			$args = array(
				'hierarchical'       => $info['hierarchical'],
				'labels'             => $info['labels'],
				'show_ui'            => true,
				'show_admin_column'  => true,
				'query_var'          => true,
				'rewrite'            => array(
					'slug' => $info['slug'],
				),
				'show_in_nav_menus'  => true,
				'show_tagcloud'      => false,
				'show_in_quick_edit' => true,
			);

			register_taxonomy( $info['slug'], $type, $args );
		}
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
