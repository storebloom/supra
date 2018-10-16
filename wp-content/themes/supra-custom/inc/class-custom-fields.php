<?php
/**
 * Custom Fields
 *
 * @package Supra_Custom
 */

namespace SupraCustom;

/**
 * Custom_Fields Class
 *
 * @package Supra_Custom
 */
class Custom_Fields {

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
	 * Enqueue Assets for custom fields.
	 *
	 * @action admin_enqueue_scripts
	 */
	public function enqueue_admin_assets() {
		wp_enqueue_script( "{$this->theme->assets_prefix}-custom-fields" );
		wp_add_inline_script( "{$this->theme->assets_prefix}-custom-fields", sprintf( 'SupraCustomFields.boot( %s );',
			wp_json_encode( array(
				'nonce' => wp_create_nonce( $this->theme->meta_prefix ),
			) )
		) );
	}

	/**
	 * Enqueue front Assets for custom fields.
	 *
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_front_assets() {
		global $post;

		if ( is_page( 'Homepage' ) || is_page( 'Company' ) ) {
			wp_enqueue_script( "{$this->theme->assets_prefix}-animation-manifest" );
			wp_enqueue_script( "{$this->theme->assets_prefix}-animation-vendor" );
			wp_enqueue_script( "{$this->theme->assets_prefix}-animation-app" );
		}

		$postid     = isset( $post->ID ) ? $post->ID : '';
		$post_title = isset( $post->post_title ) ? $post->post_title : '';

		wp_enqueue_script( "{$this->theme->assets_prefix}-scrollify" );
		wp_enqueue_script( "{$this->theme->assets_prefix}-front-ui" );
		wp_add_inline_script( "{$this->theme->assets_prefix}-front-ui", sprintf( 'SupraFrontUI.boot( %s );',
			wp_json_encode( array(
				'nonce'  => wp_create_nonce( $this->theme->meta_prefix ),
				'mobile' => wp_is_mobile(),
				'postid' => $postid,
				'page'   => $post_title,
			) )
		) );
	}

	/**
	 * Register the page description metabox in page editor.
	 *
	 * @action add_meta_boxes
	 */
	public function register_theme_metaboxes( $post_type, $post ) {
		$template_file = get_post_meta( $post->ID, '_wp_page_template', true );
		$metaboxes     = $this->set_the_meta_boxes( $post->ID, $template_file );

		foreach ( $metaboxes as $metabox ) {
			add_meta_box(
				$metabox['id'],
				$metabox['description'],
				array( $this, 'get_metabox_html' ),
				$metabox['screen'],
				$metabox['context'],
				$metabox['priority'],
				$metabox['args']
			);
		}
	}

	/**
	 * Building function for metabox html.
	 *
	 * @param string $fields The html for all custom fields in that metabox.
	 */
	public function get_metabox_html( $postid, $fields = array() ) {
		// Noncename needed to verify where the data originated.
		wp_nonce_field( 'supra-meta-settings', 'supra_meta_noncename' );

		echo isset( $fields['args'] ) ? $fields['args'] : ''; // XSS ok. All html is sanitized before getting to this point.
	}

	/**
	 * Save the custom field meta metabox data.
	 *
	 * @action save_post
	 *
	 * @param integer $post_id the current posts id.
	 * @param object $post the current post object.
	 */
	public function save_meta( $post_id, $post ) {
		$value = array();

		if ( isset( $_POST['supra_meta_noncename'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['supra_meta_noncename'] ) ), 'supra-meta-settings' ) ) { // WPSC: input var ok;
			return $post->ID;
		}

		// Is the user allowed to edit the post or page?
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $post->ID;
		}

		// Make sure this is not a revision.
		if ( 'revision' === $post->post_type ) {
			return;
		}

		// Sanitize all types of meta data.
		if ( isset( $_POST['page-meta'] ) && is_array( $_POST['page-meta'] ) ) {
			foreach ( $_POST['page-meta'] as $section => $meta ) {
				foreach ( $meta as $field_name => $field_value ) {
					if ( 'partners' === $field_name ) {
						foreach ( $field_value as $num => $overlay_values ) {
							$value[ $section ][ $field_name ][ $num ]['title'] = sanitize_text_field( wp_unslash( $overlay_values['title'] ) );
							$value[ $section ][ $field_name ][ $num ]['image'] = sanitize_text_field( wp_unslash( $overlay_values['image'] ) );
						}
					} elseif ( 'link-repeater' === $field_name ) {
						foreach ( $field_value as $num => $link_values ) {
							$value[ $section ][ $field_name ][ $num ]['title'] = sanitize_text_field( wp_unslash( $link_values['title'] ) );
							$value[ $section ][ $field_name ][ $num ]['url']   = str_replace( 'http://', '', esc_url_raw( $link_values['url'] ) );
						}
					} elseif ( 'wysiwyg-repeater' === $field_name ) {
						foreach ( $field_value as $num => $wysiwyg_values ) {
							$value[ $section ][ $field_name ][ $num ]['content'] = wp_kses_post( wp_unslash( $wysiwyg_values['content'] ) );
						}
					} elseif ( 'history' === $field_name ) {
						foreach ( $field_value as $num => $history_values ) {
							$value[ $section ][ $field_name ][ $num ]['image']   = sanitize_text_field( wp_unslash( $history_values['image'] ) );
							$value[ $section ][ $field_name ][ $num ]['content'] = wp_kses_post( wp_unslash( $history_values['content'] ) );
						}
					} else {
						$value[ $section ][ $field_name ] = wp_kses_post( wp_unslash( $field_value ) );
					}
				}
			}

			// Make sure the file array isn't empty
			if ( ! empty( $_FILES['page-meta']['name'] ) ) {

				// Setup the array of supported file types. In this case, it's just PDF.
				$supported_types = array( 'application/pdf' );

				// Get the file type of the upload
				$arr_file_type = wp_check_filetype( basename( $_FILES['page-meta']['name'] ) );
				$uploaded_type = $arr_file_type['type'];

				// Check if the type is supported. If not, throw an error.
				if ( in_array( $uploaded_type, $supported_types, true ) ) {

					// Use the WordPress API to upload the file
					$upload = wp_upload_bits( $_FILES['page-meta']['name'], null, wp_safe_remote_get( $_FILES['page-meta']['tmp_name'] ) );

					if ( isset( $upload['error'] ) && false !== $upload['error'] ) {
						wp_die( 'There was an error uploading your file. The error is: ' . esc_html( $upload['error'] ) );
					} else {
						$value['article-fields-section']['file'] = $upload['url'];
					} // End if().
				} else {
					wp_die( 'The file type that you\'ve uploaded is not a PDF.' );
				} // End if().
			} // End if().
		}

		update_post_meta( $post->ID, 'page-meta', $value );
	}

	/**
	 * Set all custom metaboxes in this function.
	 *
	 * @param integer $postid The post id of current page.
	 * @param string $page_template The page template to get metaboxes for.
	 */
	private function set_the_meta_boxes( $postid, $page_template ) {
		$metabox_array = array();
		$post_type     = get_post_type( $postid );

		// Page switch case.
		switch ( $page_template ) {
			case 'page-templates/homepage-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'home-section-';

				// Home section 1.
				$title_field = $this->create_custom_field( $postid, $prefix . '1', 'title', 'text' );

				// Home section 2.
				$title_field2  = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$content_field = $this->create_custom_field( $postid, $prefix . '2', 'content', 'wysiwyg' );

				// Home section 3.
				$title_field3 = $this->create_custom_field( $postid, $prefix . '3', 'title', 'text' );
				$image_text1  = $this->create_custom_field( $postid, $prefix . '3', 'image-content-1', 'wysiwyg' );
				$image_text2  = $this->create_custom_field( $postid, $prefix . '3', 'image-content-2', 'wysiwyg' );
				$image_text3  = $this->create_custom_field( $postid, $prefix . '3', 'image-content-3', 'wysiwyg' );
				$image_field1 = $this->create_custom_field( $postid, $prefix . '3', 'image-1', 'image' );
				$image_field2 = $this->create_custom_field( $postid, $prefix . '3', 'image-2', 'image' );
				$image_field3 = $this->create_custom_field( $postid, $prefix . '3', 'image-3', 'image' );
				$image_title1 = $this->create_custom_field( $postid, $prefix . '3', 'image-title-1', 'text' );
				$image_title2 = $this->create_custom_field( $postid, $prefix . '3', 'image-title-2', 'text' );
				$image_title3 = $this->create_custom_field( $postid, $prefix . '3', 'image-title-3', 'text' );
				$image_url1   = $this->create_custom_field( $postid, $prefix . '3', 'image-url-1', 'text' );
				$image_url2   = $this->create_custom_field( $postid, $prefix . '3', 'image-url-2', 'text' );
				$image_url3   = $this->create_custom_field( $postid, $prefix . '3', 'image-url-3', 'text' );

				// Home section 4.
				$content_field4 = $this->create_custom_field( $postid, $prefix . '4', 'content4', 'wysiwyg' );
				$image_field4   = $this->create_custom_field( $postid, $prefix . '4', 'image-4', 'image' );
				$image_field5   = $this->create_custom_field( $postid, $prefix . '4', 'image-5', 'image' );

				// Home section 5.
				$title_field6   = $this->create_custom_field( $postid, $prefix . '5', 'title', 'text' );
				$content_field5 = $this->create_custom_field( $postid, $prefix . '5', 'content', 'wysiwyg' );
				$image_content1 = $this->create_custom_field( $postid, $prefix . '5', 'image-content-1', 'wysiwyg' );
				$image_content2 = $this->create_custom_field( $postid, $prefix . '5', 'image-content-2', 'wysiwyg' );
				$image_content3 = $this->create_custom_field( $postid, $prefix . '5', 'image-content-3', 'wysiwyg' );
				$image_content4 = $this->create_custom_field( $postid, $prefix . '5', 'image-content-4', 'wysiwyg' );
				$image_content5 = $this->create_custom_field( $postid, $prefix . '5', 'image-content-5', 'wysiwyg' );
				$image_content6 = $this->create_custom_field( $postid, $prefix . '5', 'image-content-6', 'wysiwyg' );
				$image_field6   = $this->create_custom_field( $postid, $prefix . '5', 'image', 'image' );

				// Home section 6.
				$title_field4   = $this->create_custom_field( $postid, $prefix . '6', 'title', 'text' );
				$content_field6 = $this->create_custom_field( $postid, $prefix . '6', 'content', 'wysiwyg' );

				// Home section 7.
				$title_field5     = $this->create_custom_field( $postid, $prefix . '7', 'title', 'text' );
				$content_field7   = $this->create_custom_field( $postid, $prefix . '7', 'content', 'wysiwyg' );
				$image_link1      = $this->create_custom_field( $postid, $prefix . '7', 'link-1', 'text' );
				$image_7_content1 = $this->create_custom_field( $postid, $prefix . '7', 'image-content-1', 'wysiwyg' );
				$image_link2      = $this->create_custom_field( $postid, $prefix . '7', 'link-2', 'text' );
				$image_7_content2 = $this->create_custom_field( $postid, $prefix . '7', 'image-content-2', 'wysiwyg' );
				$image_link3      = $this->create_custom_field( $postid, $prefix . '7', 'link-3', 'text' );
				$image_7_content3 = $this->create_custom_field( $postid, $prefix . '7', 'image-content-3', 'wysiwyg' );
				$image_link4      = $this->create_custom_field( $postid, $prefix . '7', 'link-4', 'text' );
				$image_7_content4 = $this->create_custom_field( $postid, $prefix . '7', 'image-content-4', 'wysiwyg' );
				$image_link5      = $this->create_custom_field( $postid, $prefix . '7', 'link-5', 'text' );
				$image_7_content5 = $this->create_custom_field( $postid, $prefix . '7', 'image-content-5', 'wysiwyg' );
				$image_field7     = $this->create_custom_field( $postid, $prefix . '7', 'image', 'image' );
				$form_field       = $this->create_custom_field( $postid, $prefix . '7', 'form', 'text' );
				$contact_desc     = $this->create_custom_field( $postid, $prefix . '7', 'contact-desc', 'text' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '1-supra',
						'description' => 'Home Section 1',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field . $form_field,
					),
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'Home Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field2 . $content_field,
					),
					array(
						'id'          => $prefix . '3-supra',
						'description' => 'Home Section 3',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field3 . $image_field1 . $image_title1 . $image_text1 . $image_url1 . $image_field2 . $image_title2 . $image_text2 . $image_url2 . $image_field3 . $image_title3 . $image_text3 . $image_url3,
					),
					array(
						'id'          => $prefix . '4-supra',
						'description' => 'Home Section 4',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $content_field4 . $image_field4 . $image_field5,
					),
					array(
						'id'          => $prefix . '5-supra',
						'description' => 'Home Section 5',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field6 . $content_field5 . $image_content1 . $image_content2 . $image_content3 . $image_content4 . $image_content5 . $image_content6 . $image_field6,
					),
					array(
						'id'          => $prefix . '6-supra',
						'description' => 'Home Section 6',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field4 . $content_field6,
					),
					array(
						'id'          => $prefix . '7-supra',
						'description' => 'Global Footer Section',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field5 . $content_field7 . $image_7_content1 . $image_link1 . $image_7_content2 . $image_link2 . $image_7_content3 . $image_link3 . $image_7_content4 . $image_link4 . $image_7_content5 . $image_link5 . $image_field7 . $form_field . $contact_desc,
					),
				);
				break;
			case 'page-templates/about-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'about-section-';

				// About section 1.
				$title_field = $this->create_custom_field( $postid, $prefix . '1', 'title', 'text' );

				// About section 2.
				$title_field2  = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$content_field = $this->create_custom_field( $postid, $prefix . '2', 'content', 'wysiwyg' );
				$image_field   = $this->create_custom_field( $postid, $prefix . '2', 'image', 'image' );

				// About section 3.
				$title_field3  = $this->create_custom_field( $postid, $prefix . '3', 'title', 'text' );
				$image_field2  = $this->create_custom_field( $postid, $prefix . '3', 'image', 'image' );
				$history_field = $this->create_custom_field( $postid, $prefix . '3', 'history', 'wysiwyg_image' );

				// About section 4.
				$title_field4     = $this->create_custom_field( $postid, $prefix . '4', 'title', 'text' );
				$image_text_field = $this->create_custom_field( $postid, $prefix . '4', 'partners', 'image_text' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '1-supra',
						'description' => 'About Section 1',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field,
					),
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'About Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field2 . $content_field . $image_field,
					),
					array(
						'id'          => $prefix . '3-supra',
						'description' => 'About Section 3',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field3 . $image_field2 . $history_field,
					),
					array(
						'id'          => $prefix . '4-supra',
						'description' => 'About Section 4',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field4 . $image_text_field,
					),
				);
				break;
			case 'page-templates/transportation-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'transportation-section-';

				// Tranportation section 1.
				$logo_field = $this->create_custom_field( $postid, $prefix . '1', 'image', 'image' );
				$form_field = $this->create_custom_field( $postid, $prefix . '1', 'form', 'text' );

				// Tranportation section 2.
				$title_field    = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$content_field  = $this->create_custom_field( $postid, $prefix . '2', 'content', 'wysiwyg' );
				$overview_field = $this->create_custom_field( $postid, $prefix . '2', 'overview', 'wysiwyg_image' );

				// Tranportation section 3.
				$image_field1   = $this->create_custom_field( $postid, $prefix . '3', 'image-1', 'image' );
				$image_field2   = $this->create_custom_field( $postid, $prefix . '3', 'image-2', 'image' );
				$content_field1 = $this->create_custom_field( $postid, $prefix . '3', 'content-1', 'wysiwyg' );
				$image_field3   = $this->create_custom_field( $postid, $prefix . '3', 'image-3', 'image' );
				$content_field2 = $this->create_custom_field( $postid, $prefix . '3', 'content-2', 'wysiwyg' );
				$image_field4   = $this->create_custom_field( $postid, $prefix . '3', 'image-4', 'image' );
				$content_field3 = $this->create_custom_field( $postid, $prefix . '3', 'content-3', 'wysiwyg' );
				$image_field5   = $this->create_custom_field( $postid, $prefix . '3', 'image-5', 'image' );
				$content_field4 = $this->create_custom_field( $postid, $prefix . '3', 'content-4', 'wysiwyg' );
				$image_field6   = $this->create_custom_field( $postid, $prefix . '3', 'image-6', 'image' );
				$content_field5 = $this->create_custom_field( $postid, $prefix . '3', 'content-5', 'wysiwyg' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '1-supra',
						'description' => 'Tranportation Section 1',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $logo_field . $form_field,
					),
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'Tranportation Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field . $content_field . $overview_field,
					),
					array(
						'id'          => $prefix . '3-supra',
						'description' => 'Tranportation Section 3',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $image_field1 . $image_field2 . $content_field1 . $image_field3 . $content_field2 . $image_field4 . $content_field3 . $image_field5 . $content_field4 . $image_field6 . $content_field5,
					),
				);
				break;
			case 'page-templates/logistics-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'logistics-section-';

				// Logistics section 1.
				$logo_field = $this->create_custom_field( $postid, $prefix . '1', 'image', 'image' );
				$form_field = $this->create_custom_field( $postid, $prefix . '1', 'form', 'text' );

				// Logistics section 2.
				$title_field    = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$content_field  = $this->create_custom_field( $postid, $prefix . '2', 'content', 'wysiwyg' );
				$overview_field = $this->create_custom_field( $postid, $prefix . '2', 'overview', 'wysiwyg_image' );

				// Logistics section 3.
				$image_field1   = $this->create_custom_field( $postid, $prefix . '3', 'image-1', 'image' );
				$image_field2   = $this->create_custom_field( $postid, $prefix . '3', 'image-2', 'image' );
				$content_field1 = $this->create_custom_field( $postid, $prefix . '3', 'content-1', 'wysiwyg' );
				$image_field3   = $this->create_custom_field( $postid, $prefix . '3', 'image-3', 'image' );
				$content_field2 = $this->create_custom_field( $postid, $prefix . '3', 'content-2', 'wysiwyg' );
				$image_field4   = $this->create_custom_field( $postid, $prefix . '3', 'image-4', 'image' );
				$content_field3 = $this->create_custom_field( $postid, $prefix . '3', 'content-3', 'wysiwyg' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '1-supra',
						'description' => 'Logistics Section 1',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $logo_field . $form_field,
					),
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'Logistics Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field . $content_field . $overview_field,
					),
					array(
						'id'          => $prefix . '3-supra',
						'description' => 'Logistics Section 3',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $content_field1 . $image_field1 . $image_field2 . $content_field2 . $image_field3 . $content_field3 . $image_field4,
					),
				);
				break;
			case 'page-templates/distribution-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'distribution-section-';

				// Distribution section 1.
				$logo_field = $this->create_custom_field( $postid, $prefix . '1', 'image', 'image' );
				$form_field = $this->create_custom_field( $postid, $prefix . '1', 'form', 'text' );

				// Distribution section 2.
				$title_field    = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$content_field  = $this->create_custom_field( $postid, $prefix . '2', 'content', 'wysiwyg' );
				$overview_field = $this->create_custom_field( $postid, $prefix . '2', 'overview', 'wysiwyg_image' );

				// Distribution section 3.
				$image_field1   = $this->create_custom_field( $postid, $prefix . '3', 'image-1', 'image' );
				$image_field2   = $this->create_custom_field( $postid, $prefix . '3', 'image-2', 'image' );
				$content_field1 = $this->create_custom_field( $postid, $prefix . '3', 'content-1', 'wysiwyg' );
				$image_field3   = $this->create_custom_field( $postid, $prefix . '3', 'image-3', 'image' );
				$content_field2 = $this->create_custom_field( $postid, $prefix . '3', 'content-2', 'wysiwyg' );
				$image_field4   = $this->create_custom_field( $postid, $prefix . '3', 'image-4', 'image' );
				$content_field3 = $this->create_custom_field( $postid, $prefix . '3', 'content-3', 'wysiwyg' );
				$image_field5   = $this->create_custom_field( $postid, $prefix . '3', 'image-5', 'image' );
				$content_field4 = $this->create_custom_field( $postid, $prefix . '3', 'content-4', 'wysiwyg' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '1-supra',
						'description' => 'Distribution Section 1',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $logo_field . $form_field,
					),
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'Distribution Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field . $content_field . $overview_field,
					),
					array(
						'id'          => $prefix . '3-supra',
						'description' => 'Distribution Section 3',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $content_field1 . $image_field1 . $image_field2 . $content_field2 . $image_field3 . $content_field3 . $image_field4 . $content_field4 . $image_field5,
					),
				);
				break;
			case 'page-templates/blog-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'blog-section-';

				// Blog section 2.
				$title_field2  = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$content_field = $this->create_custom_field( $postid, $prefix . '2', 'content', 'wysiwyg' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'Blog Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field2 . $content_field,
					),
				);
				break;
			case 'page-templates/careers-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'careers-section-';

				// Careers section 2.
				$title_field2  = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$content_field = $this->create_custom_field( $postid, $prefix . '2', 'content', 'wysiwyg' );

				// Careers section 3.
				$image_field1   = $this->create_custom_field( $postid, $prefix . '3', 'image-1', 'image' );
				$image_field2   = $this->create_custom_field( $postid, $prefix . '3', 'image-2', 'image' );
				$content_field1 = $this->create_custom_field( $postid, $prefix . '3', 'content-1', 'wysiwyg' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'Careers Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field2 . $content_field,
					),
					array(
						'id'          => $prefix . '3-supra',
						'description' => 'Careers Section 3',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $content_field1 . $image_field1 . $image_field2,
					),
				);
				break;
			case 'page-templates/contact-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'contact-section-';

				// Contact section 1.
				$title_field = $this->create_custom_field( $postid, $prefix . '1', 'title', 'text' );

				// Contact section 2.
				$title_field2  = $this->create_custom_field( $postid, $prefix . '2', 'title', 'text' );
				$content_field = $this->create_custom_field( $postid, $prefix . '2', 'content', 'wysiwyg' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '1-supra',
						'description' => 'Contact Section 1',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field,
					),
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'Contact Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field2 . $content_field,
					),
				);
				break;
			case 'page-templates/rate-request-template.php':
				// Remove editor features for specific page.
				remove_post_type_support( 'page', 'editor' );

				$prefix = 'request-rate-section-';

				// Request Rate section 1.
				$title_field = $this->create_custom_field( $postid, $prefix . '1', 'title', 'text' );

				// Request Rate section 2.
				$form_1 = $this->create_custom_field( $postid, $prefix . '2', 'form-drayage', 'text' );
				$form_2 = $this->create_custom_field( $postid, $prefix . '2', 'form-ftl', 'text' );
				$form_3 = $this->create_custom_field( $postid, $prefix . '2', 'form-distribution', 'text' );

				$metabox_array = array(
					array(
						'id'          => $prefix . '1-supra',
						'description' => 'Request Rate Section 1',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $title_field,
					),
					array(
						'id'          => $prefix . '2-supra',
						'description' => 'Reqeust Rate Section 2',
						'screen'      => 'page',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $form_1 . $form_2 . $form_3,
					),
				);
				break;
		} // End switch().

		// Post Type switch case.
		switch ( $post_type ) {
			case 'career':
				$url_field = $this->create_custom_field( $postid, 'career-section-supra', 'apply-link', 'text' );

				$metabox_array = array(
					array(
						'id'          => 'career-section-supra',
						'description' => 'Add Url To iSolved Job Page',
						'screen'      => 'career',
						'context'     => 'normal',
						'priority'    => 'high',
						'args'        => $url_field,
					),
				);
				break;
		} // End switch().

		return $metabox_array;
	}

	/**
	 * Building function for registering and returning custom fields html.
	 *
	 * @param integer $postid The post/page/cpt to get custom field value from.
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $type The type of field to create.
	 * @param string $post_type The post type of the post specified.
	 *
	 * @return string
	 */
	private function create_custom_field( $postid, $section, $name, $type ) {
		$name2      = 'overview' === $name ? 'history' : $name;
		$value      = get_post_meta( $postid, 'page-meta', true );
		$value      = isset( $value[ $section ][ $name2 ] ) ? $value[ $section ][ $name2 ] : '';
		$field_type = 'get_' . $type . '_field_html';

		return $this->$field_type( $section, $name, $value );
	}

	/**
	 * Call back function for returning custom text field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_text_field_html( $section, $name, $value = '' ) {
		$allowed_tags          = wp_kses_allowed_html( 'post' );
		$allowed_tags['input'] = array(
			'value' => true,
			'name'  => true,
			'class' => true,
			'size'  => true,
		);

		$html  = '<div class="supra-text-field">';
		$html .= '<div class="field-label-wrap">';
		$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
		$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . ']" value="' . esc_html( $value ) . '" size="60">';
		$html .= '</div>';
		$html .= '</div>';

		return wp_kses( $html, $allowed_tags );
	}

	/**
	 * Call back function for returning custom text field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_image_field_html( $section, $name, $value = '' ) {
		$html  = '<div class="supra-image-field">';
		$html .= '<div class="field-label-wrap">';
		$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
		$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . ']" value="' . esc_html( $value ) . '" size="60">';
		$html .= '<button class="add-supra-image">' . esc_html__( 'Add Image', 'supra-custom' ) . '</button>';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Call back function for returning custom image text field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_image_text_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$title = isset( $field_value['title'] ) ? $field_value['title'] : '';
				$image = isset( $field_value['image'] ) ? $field_value['image'] : '';

				if ( 1 < count( $value ) ) {
					$html .= '<button type="button" class="remove-image-text-field">-</button>';
				}

				$html .= '<div data-num="' . $field_num . '" class="supra-image-text-field">';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][title]" value="' . esc_html( $title ) . '" size="60">';
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Image</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][image]" value="' . esc_html( $image ) . '" size="60">';
				$html .= '<button class="add-supra-image">' . esc_html__( 'Add Image', 'supra-custom' ) . '</button>';
				$html .= '</div>';
				$html .= '</div>';
			}
		} else {

			$html  = '<div data-num="1" class="supra-image-text-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][title]" value="' . esc_html( $value ) . '" size="60">';
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Image</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][image]" value="' . esc_html( $value ) . '" size="60">';
			$html .= '<button class="add-supra-image">' . esc_html__( 'Add Image', 'supra-custom' ) . '</button>';
			$html .= '</div>';
			$html .= '</div>';
		}

		$html .= '<button type="button" class="add-image-text-field">+</button>';

		return $html;
	}

	/**
	 * Call back function for returning wysiwyg field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_wysiwyg_field_html( $section, $name, $value = '' ) {
		$html = '';

		$options = array(
			'media_buttons' => true,
			'textarea_name' => 'page-meta[' . $section . '][' . $name . ']',
		);

		$id = $section . '_' . $name . '_1';

		$html .= '<div class="supra-wysiwyg-field">';
		$html .= '<div class="field-label-wrap">';
		$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';

		ob_start();
		wp_editor( $value, $id, $options );

		$html .= ob_get_clean();
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Call back function for returning repeater wysiwyg / image field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_wysiwyg_image_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$image   = isset( $field_value['image'] ) ? $field_value['image'] : '';
				$content = isset( $field_value['content'] ) ? $field_value['content'] : '';
				$options = array(
					'media_buttons' => true,
					'textarea_name' => 'page-meta[' . $section . '][history][' . $field_num . '][content]',
				);
				$id      = $section . '_' . $name . '_' . $field_num . '_content';

				$html .= '<div data-num="' . $field_num . '" class="supra-wysiwyg-image-repeater-field">';

				if ( 1 < count( $value ) && ! empty( $field_value['content'] ) ) {
					$html .= '<button type="button" class="remove-wysiwyg-image-repeater-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

				ob_start();
				wp_editor( $content, $id, $options );

				$html .= ob_get_clean();
				$html .= \_WP_Editors::enqueue_scripts();
				$html .= \_WP_Editors::editor_js();
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Image</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][history][' . $field_num . '][image]" value="' . esc_attr( $image ) . '" size="60">';
				$html .= '<button class="add-supra-image">' . esc_html__( 'Add Image', 'supra-custom' ) . '</button>';
				$html .= '</div>';
				$html .= '</div>';
			}
		} else {
			$options = array(
				'media_buttons' => true,
				'textarea_name' => 'page-meta[' . $section . '][history][1][content]',
			);
			$id      = $section . '_' . $name . '_1_content';

			$html .= '<div data-num="1" class="supra-wysiwyg-image-repeater-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

			ob_start();
			wp_editor( '', $id, $options );

			$html .= ob_get_clean();
			$html .= \_WP_Editors::enqueue_scripts();
			$html .= \_WP_Editors::editor_js();
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Image</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][history][1][image]" value="" size="60">';
			$html .= '<button class="add-supra-image">' . esc_html__( 'Add Image', 'supra-custom' ) . '</button>';
			$html .= '</div>';
			$html .= '</div>';
		} // End if().

		$html .= '<button type="button" class="add-wysiwyg-image-repeater-field">+</button>';

		return $html;
	}

	/**
	 * Call back function for returning repeater wysiwyg field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_wysiwyg_repeater_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$title   = isset( $field_value['title'] ) ? $field_value['title'] : '';
				$content = isset( $field_value['content'] ) ? $field_value['content'] : '';
				$url     = isset( $field_value['url'] ) ? $field_value['url'] : '';
				$options = array(
					'media_buttons' => true,
					'textarea_name' => 'page-meta[' . $section . '][' . $name . '][' . $field_num . '][content]',
				);
				$id      = $section . '_' . $name . '_' . $field_num;

				$html .= '<div data-num="' . $field_num . '" class="supra-wysiwyg-repeater-field">';

				if ( 1 < count( $value ) && ! empty( $field_value['content'] ) ) {
					$html .= '<button type="button" class="remove-wysiwyg-repeater-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

				ob_start();
				wp_editor( $content, $id, $options );

				$html .= ob_get_clean();
				$html .= \_WP_Editors::enqueue_scripts();
				$html .= \_WP_Editors::editor_js();
				$html .= '</div>';
			}
		} else {
			$options = array(
				'media_buttons' => true,
				'textarea_name' => 'page-meta[' . $section . '][' . $name . '][1][content]',
			);
			$id      = $section . '_' . $name . '_1';

			$html .= '<div data-num="1" class="supra-wysiwyg-repeater-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

			ob_start();
			wp_editor( '', $id, $options );

			$html .= ob_get_clean();
			$html .= \_WP_Editors::enqueue_scripts();
			$html .= \_WP_Editors::editor_js();
			$html .= '</div>';
			$html .= '</div>';
		} // End if().

		$html .= '<button type="button" class="add-wysiwyg-repeater-field">+</button>';

		return $html;
	}

	/**
	 * Call back function for returning custom overlay repeater wysiwyg field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_overlay_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$title   = isset( $field_value['title'] ) ? $field_value['title'] : '';
				$content = isset( $field_value['content'] ) ? $field_value['content'] : '';
				$url     = isset( $field_value['url'] ) ? $field_value['url'] : '';
				$options = array(
					'media_buttons' => true,
					'textarea_name' => 'page-meta[' . $section . '][' . $name . '][' . $field_num . '][content]',
				);
				$id      = $section . '_' . $name . '_' . $field_num;

				$html .= '<div data-num="' . $field_num . '" class="supra-overlay-field">';

				if ( 1 < count( $value ) && ! empty( $field_value['content'] ) ) {
					$html .= '<button type="button" class="remove-overlay-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][title]" value="' . esc_attr( $title ) . '" size="60">';
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' URL (Leave empty if overlay)</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][url]" value="' . esc_attr( $url ) . '" size="60">';
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

				ob_start();
				wp_editor( $content, $id, $options );

				$html .= ob_get_clean();
				$html .= \_WP_Editors::enqueue_scripts();
				$html .= \_WP_Editors::editor_js();
				$html .= '</div>';
				$html .= '</div>';
			} // End foreach().
		} else {
			$options = array(
				'media_buttons' => true,
				'textarea_name' => 'page-meta[' . $section . '][' . $name . '][1][content]',
			);
			$id      = $section . '_' . $name . '_1';

			$html .= '<div data-num="1" class="supra-overlay-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][title]" value="" size="60">';
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' URL (Leave empty if overlay)</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][url]" value="" size="60">';
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Content</label>';

			ob_start();
			wp_editor( '', $id, $options );

			$html .= ob_get_clean();
			$html .= \_WP_Editors::enqueue_scripts();
			$html .= \_WP_Editors::editor_js();
			$html .= '</div>';
			$html .= '</div>';
		} // End if().

		$html .= '<button type="button" class="add-overlay-field">+</button>';

		return $html;
	}

	/**
	 * Call back function for returning custom overlay repeater wysiwyg field html
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_link_repeater_field_html( $section, $name, $value = '' ) {
		$html = '';

		if ( is_array( $value ) ) {
			foreach ( $value as $field_num => $field_value ) {
				$title = isset( $field_value['title'] ) ? $field_value['title'] : '';
				$url   = isset( $field_value['url'] ) ? $field_value['url'] : '';
				$html  .= '<div data-num="' . $field_num . '" class="supra-link-field">';

				if ( 1 < count( $value ) && ! empty( $field_value['url'] ) ) {
					$html .= '<button type="button" class="remove-link-field">-</button>';
				}

				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][title]" value="' . esc_attr( $title ) . '" size="60">';
				$html .= '</div>';
				$html .= '<div class="field-label-wrap">';
				$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' URL</label>';
				$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][' . $field_num . '][url]" value="' . esc_attr( $url ) . '" size="60">';
				$html .= '</div>';
				$html .= '</div>';
			}
		} else {
			$html .= '<div data-num="1" class="supra-link-field">';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' Title</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][title]" value="" size="60">';
			$html .= '</div>';
			$html .= '<div class="field-label-wrap">';
			$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . ' URL</label>';
			$html .= '<input type="text" name="page-meta[' . $section . '][' . $name . '][1][url]" value="" size="60">';
			$html .= '</div>';
			$html .= '</div>';
		} // End if().

		$html .= '<button type="button" class="add-link-field">+</button>';

		return $html;
	}

	/**
	 * Call back for file uploader field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_file_field_html( $section, $name, $value = array() ) {
		$html = '<div class="supra-field-field">';

		if ( ! empty( $value ) ) {
			$html .= '<div class="file-name">' . esc_html( $value ) . '</div>';
		}

		$html .= '<div class="field-label-wrap">';
		$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
		$html .= '<input type="file" name="page-meta" value="" size="60">';
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * Call back function for returning custom image repeater field html.
	 *
	 * @param string $section The metabox section.
	 * @param string $name The field name.
	 * @param string $value The custom field value if any.
	 */
	private function get_image_repeater_field_html( $section, $name, $value = array() ) {
		$html  = '<div class="supra-image-repeater-field">';
		$html .= '<label class="supra-admin-label">' . ucfirst( str_replace( '-', ' ', $name ) ) . '</label>';
		$html .= '<p>';
		$html .= '<button type="button" class="upload-supra-image">';
		$html .= esc_html__( 'add image', 'supra-custom' );
		$html .= '</button>';
		$html .= '</p>';
		$html .= '<div class="supra-image-list-wrap">';

		if ( is_array( $value ) ) {
			foreach ( $value as $num => $image_url ) {
				$html .= '<div class="supra-image">';
				$html .= '<span class="supra-remove-image">';
				$html .= 'x';
				$html .= '</span>';
				$html .= '<img src="' . $image_url . '">';
				$html .= '<input type="hidden" name="page-meta[' . $section . '][' . $name . '][' . $num . ']" id="supra-' . $name . '-' . $num . '" value="' . $image_url . '">';
				$html .= '</div>';
			}
		}

		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/**
	 * AJAX Call Back function to return a new wysiwyg for overlay
	 *
	 * @action wp_ajax_get_overlay_field
	 */
	public function get_overlay_field() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['count'] ) || '' === $_POST['count'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay field failed' );
		}

		$count   = intval( $_POST['count'] ) + 1; // WPCS: input var ok.
		$section = sanitize_text_field( wp_unslash( $_POST['section'] ) ); // WPCS: input var ok.

		$options = array(
			'media_buttons' => true,
			'textarea_name' => 'page-meta[' . $section . '][overlay-repeater][' . $count . '][content]',
		);
		$id      = $section . '_overlay-repeater_' . $count;

		wp_editor( '', $id, $options );

		wp_die();
	}

	/**
	 * AJAX Call Back function to return a new wysiwyg.
	 *
	 * @action wp_ajax_get_wysiwyg_field
	 */
	public function get_wysiwyg_field() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['count'] ) || '' === $_POST['count'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay field failed' );
		}

		$count   = intval( $_POST['count'] ) + 1; // WPCS: input var ok.
		$section = sanitize_text_field( wp_unslash( $_POST['section'] ) ); // WPCS: input var ok.

		$options = array(
			'media_buttons' => true,
			'textarea_name' => 'page-meta[' . $section . '][wysiwyg-repeater][' . $count . '][content]',
		);
		$id      = $section . '_wysiwyg-repeater_' . $count;

		wp_editor( '', $id, $options );

		wp_die();
	}

	/**
	 * AJAX Call Back function to return a new wysiwyg.
	 *
	 * @action wp_ajax_get_wysiwyg_image_field
	 */
	public function get_wysiwyg_image_field() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['count'] ) || '' === $_POST['count'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay field failed' );
		}

		$count   = intval( $_POST['count'] ) + 1; // WPCS: input var ok.
		$section = sanitize_text_field( wp_unslash( $_POST['section'] ) ); // WPCS: input var ok.

		$options = array(
			'media_buttons' => true,
			'textarea_name' => 'page-meta[' . $section . '][history][' . $count . '][content]',
		);
		$id      = $section . '_history_' . $count . '_content';

		wp_editor( '', $id, $options );

		wp_die();
	}

	/**
	 * AJAX Call Back function to return a the overlay content for specified link.
	 *
	 * @action wp_ajax_get_overlay_content
	 */
	public function get_overlay_content() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		$homeid = get_page_by_title( 'Homepage' );

		if ( ! isset( $_POST['section'] ) || '' === $_POST['section'] ) { // WPCS: input var ok.
			wp_send_json_error( 'Get overlay content failed' );
		}

		$section = sanitize_text_field( wp_unslash( $_POST['section'] ) ); // WPCS: input var ok.
		$number  = sanitize_text_field( wp_unslash( $_POST['number'] ) ); // WPCS: input var ok.
		$postid  = isset( $_POST['footer'] ) ? $homeid->ID : (int) $_POST['postid'];

		$post_meta = get_post_meta( $postid, 'page-meta', true );
		$title     = isset( $post_meta[ $section ]['overlay-repeater'][ $number ]['title'] ) ? $post_meta[ $section ]['overlay-repeater'][ $number ]['title'] : '';
		$content   = isset( $post_meta[ $section ]['overlay-repeater'][ $number ]['content'] ) ? $post_meta[ $section ]['overlay-repeater'][ $number ]['content'] : '';

		$html = '<div class="overlay-title">';
		$html .= $title;
		$html .= '</div>';
		$html .= '<div class="overlay-content">';
		$html .= $content;
		$html .= '</div>';

		wp_send_json_success( $html );
	}

	/**
	 * AJAX Call back to get articles based on search query.
	 *
	 * @action wp_ajax_get_articles
	 * @action wp_ajax_nopriv_get_articles
	 */
	public function get_articles() {
		check_ajax_referer( $this->theme->meta_prefix, 'nonce' );

		if ( ! isset( $_POST['query'] ) ) { // WPCS: input var ok.
			wp_send_json_error( 'Article return failed.' );
		}

		$query = sanitize_text_field( wp_unslash( $_POST['query'] ) ); // WPCS: input var ok.
		$type  = isset( $_POST['type'] ) ? sanitize_text_field( wp_unslash( $_POST['type'] ) ) : ''; // WPCS: input var ok.

		if ( 'white' === $type ) {
			$type = 'white-paper';
		}

		if ( 'education' === $type ) {
			$type = 'education-center';
		}

		$sort = isset( $_POST['sort'] ) ? $_POST['sort'] : 'ASC';
		$args = array(
			's'              => $query,
			'post_type'      => 'post',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'order'          => $sort,
			'orderby'        => 'publish_date',
			'tax_query'      => array(
				array(
					'taxonomy' => 'category',
					'field'    => 'slug',
					'terms'    => array( $type ),
				),
			),
		);

		$results = get_posts( $args );
		$html    = $this->get_result_html( $results, $type );

		wp_send_json_success( $html );
	}

	/**
	 * Helper function to build html of article results.
	 *
	 * @param array $articles The array of results.
	 * @param string $type The type of html to return
	 */
	private function get_result_html( $articles, $type ) {
		$html = '';

		if ( is_array( $articles ) ) {
			switch ( $type ) {
				case 'white-paper' :
					foreach ( $articles as $white ) {
						ob_start();
						include( get_template_directory() . '/single-templates/white-paper.php' );
						$html .= ob_get_clean();
					}
					break;
				case 'education-center' :
					foreach ( $articles as $education ) {
						ob_start();
						include( get_template_directory() . '/single-templates/education-center.php' );
						$html .= ob_get_clean();
					}
					break;
				case 'media' :
					foreach ( $articles as $media ) {
						ob_start();
						include( get_template_directory() . '/single-templates/media.php' );
						$html .= ob_get_clean();
					}
					break;
			}
		}

		return $html;
	}
}
