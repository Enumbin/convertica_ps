<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */
// edited
$helper_instance = Helper_Global::get_instance();
// Add new input type "media".
if ( function_exists( 'smile_add_input_type' ) ) {
	smile_add_input_type( 'media', 'media_settings_field' );
}

$helper_instance->add_action( 'admin_enqueue_scripts', 'framework_media_admin_styles' );
/**
 * Function Name:framework_media_admin_styles description.
 *
 * @param  array $hook ap page list.
 */
function framework_media_admin_styles( $hook ) {
	if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
		$cp_page = strpos( $hook, CP_PLUS_SLUG );
		$helper_instance = Helper_Global::get_instance();
		$data    = $helper_instance->convertica_get_option( 'convert_plug_debug' );

		wp_enqueue_script( 'media-upload' );
		if ( false !== $cp_page && isset( $_GET['style-view'] ) && 'edit' === $_GET['style-view'] ) {
			wp_enqueue_media();
			if ( isset( $data['cp-dev-mode'] ) && '1' === $data['cp-dev-mode'] ) {
				wp_enqueue_script( 'convert-plus-media-script', SMILE_FRAMEWORK_URI . '/lib/fields/media/media.js', array(), '1.0.0', true );
				wp_enqueue_style( 'convert-plus-media-style', SMILE_FRAMEWORK_URI . '/lib/fields/media/media.css', array(), CP_VERSION );
			}
		}
	}
}

/**
 * Function Name:media_settings_field Function to handle new input type "media".
 *
 * @param  string $name     settings provided when using the input type "media".
 * @param  string $settings holds the default / updated value.
 * @param  string $value    html output generated by the function.
 * @param  string $default_value    defualt output generated by the function.
 * @return string           html output generated by the function.
 */
function media_settings_field( $name, $settings, $value, $default_value = null ) {

	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';

	$btn_label   = ( '' !== $value ) ? __( 'Change Image', 'smile' ) : __( 'Select Image', 'smile' );
	$img_arr     = explode( '|', $value );
	$img_size    = isset( $img_arr[1] ) ? $img_arr[1] : 'full';
	$displaysize = false;
	$displaysize = ( '' !== $value ) ? strpos( $value, 'http' ) : true;
	$alt         = '';
	$newvalue    = $value;
	if ( false !== $displaysize ) {
		$hidesize = 'hide-for-default';
		$src      = $value;
	} else {
		$hidesize = '';
		$src      = wp_get_attachment_image_src( $img_arr[0] );
		$alt      = get_post_meta( $img_arr[0], '_wp_attachment_image_alt', true );
		$newvalue = $value . '|' . $alt;
		$src      = $src[0];
	}
	if ( false !== strpos( $src, '|' ) ) {
		$image_src = explode( '|', $src );
		$image_src = $image_src[0];
	} else {
		$image_src = $src;
	}

	// Apply partials.
	// Add attr 'css-image-url' for MEDIA support.
	if ( is_numeric( $img_arr[0] ) ) {
		$css_src = wp_get_attachment_image_src( $img_arr[0], $img_size );
		$img_url = $css_src[0];
	} else {
		$img_url = $img_arr[0];
	}
	$settings['css-image-url'] = $img_url;
	$settings['css-image-alt'] = $alt;
	$partials                  = generate_partial_atts( $settings );

	$img     = ( '' == $value ) ? '<p class="description">' . __( 'No Image Selected', 'smile' ) . '</p>' : '<img src="' . $image_src . '"/>';
	$display = ( '' !== $value ) ? 'style="display:block;"' : 'style="display:none;"';
	$uid     = uniqid();

	$_SESSION[ $input_name ] = $uid;
	$output                  = '';
	$output                 .= '<div class="' . $input_name . '_' . $uid . '_container smile-media-container">' . $img . '</div>';
	$output                 .= '<input type="text" id="smile_' . $input_name . '_' . $uid . '" class="form-control smile-input smile-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" value="' . $newvalue . '" ' . $partials . ' />';
	$output                 .= '<div class="smile-media-actions">';
	$rmv_btn                 = ( '' === $value ) ? 'display:none;' : '';
	$dflt_btn                = ( '' === $default_value ) ? 'display:none;' : '';
	if ( '' === $default_value ) {
		$output .= '<button style="' . $rmv_btn . '" id="remove_' . $input_name . '_' . $uid . '" ' . $display . ' class="button button-secondary smile-remove-media form-control smile-input smile-' . $type . '">' . __( 'Remove', 'smile' ) . '</button>';
	}
	$output    .= '<button style="' . $dflt_btn . '" data-default="' . $default_value . '" id="default_' . $input_name . '_' . $uid . '" ' . $display . ' class="button button-secondary smile-default-media form-control smile-input smile-' . $type . '">' . __( 'Default', 'smile' ) . '</button>';
	$output    .= '<button id="' . $input_name . '_' . $uid . '" data-uid="' . $uid . '" class="button button-secondary smile-upload-media form-control smile-input smile-' . $type . '">' . $btn_label . '</button>';
	$output    .= '</div>';
	$imagesizes = cp_get_all_image_sizes();
	$output    .= '</div>';
	$output    .= '<div class="smile-element-container cp-media-sizes ' . $hidesize . '" data-name="' . $input_name . '_' . $uid . '" data-element="cp-media-' . $uid . '" data-operator="!==" data-value="">';
	$output    .= '<strong><label for="smile_' . $input_name . '_size">' . __( 'Select Size', 'smile' ) . '</label></strong>';
	$output    .= '<p>';

	$image_url = wp_get_attachment_url( $img_arr[0] ); // Just the file name.
	$output   .= '<select id="smile_' . $input_name . '_size" class="cp-media-' . $uid . ' form-control smile-input cp-media-size" name="' . $input_name . '_size" data-id="' . $img_arr[0] . '" data-alt="' . $alt . '" data-image-name="' . $image_url . '">';
	foreach ( $imagesizes as $title => $size ) {
		$s_title    = ucwords( str_replace( '-', ' ', $title ) );
		$data_sizes = '';
		if ( is_array( $size ) && ! empty( $size ) ) {
			$s_title   .= ' - ' . $size['width'] . ' x ' . $size['height'];
			$data_sizes = $size['width'] . 'x' . $size['height'];
		}
		if ( $title == $img_size ) {
			$selected = 'selected';
		} else {
			$selected = '';
		}
		$output .= '<option ' . $selected . ' value="' . $title . '" data-size="' . $data_sizes . '">' . $s_title . '</option>';
	}
	$output .= '</select></p>';
	return $output;
}

/**
 * Fucntion Name:cp_get_all_image_sizes.
 *
 * @return array val.
 */
function cp_get_all_image_sizes() {
	global $_wp_additional_image_sizes;
	$default_image_sizes = array( 'thumbnail', 'medium', 'large' );
	$image_sizes['full'] = array();
	foreach ( $default_image_sizes as $size ) {
		$image_sizes[ $size ]['width']  = intval( get_option( "{$size}_size_w" ) );
		$image_sizes[ $size ]['height'] = intval( get_option( "{$size}_size_h" ) );
		$image_sizes[ $size ]['crop']   = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
	}

	if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
		$image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
	}

	return $image_sizes;
}

// edited
// add_action( 'wp_ajax_nopriv_cp_get_image', 'cp_get_image' );
// add_action( 'wp_ajax_cp_get_image', 'cp_get_image' );
/**
 * Fucntion Name:cp_get_image.
 */
function cp_get_image() {
	if ( ! current_user_can( 'access_cp' ) ) {
			die( -1 );
	}

	check_ajax_referer( 'cp_media_nonce', 'security_nonce' );

	$img_id = isset( $_POST['img_id'] ) ? (int) $_POST['img_id'] : '';
	$size   = isset( $_POST['size'] ) ? sanitize_text_field( $_POST['size'] ) : '';
	$img    = wp_get_attachment_image_src( $img_id, $size );
	wp_send_json( $img[0] );
}

if ( ! function_exists( 'cp_handle_upload_prefilter' ) ) {
	if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
		$cp_page = isset( $_POST['admin_page'] ) ? sanitize_text_field( $_POST['admin_page'] ) : '';

		if ( isset( $cp_page ) && 'customizer' === $cp_page ) {

			add_filter( 'wp_handle_upload_prefilter', 'cp_handle_upload_prefilter' );
		}
	}
	/**
	 * Fucntion Name:cp_handle_upload_prefilter.
	 *
	 * @param  string $file holds the default / updated value.
	 * @return array val.
	 */
	function cp_handle_upload_prefilter( $file ) {
		if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
			$page = isset( $_POST['admin_page'] ) ? sanitize_text_field( $_POST['admin_page'] ) : '';

			if ( isset( $page ) && 'customizer' === $page ) {
				$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

				if ( 'jpg' !== $ext && 'jpeg' !== $ext && 'png' !== $ext && 'gif' !== $ext && 'ico' !== $ext ) {
					$file['error'] = 'The uploaded ' . $ext . ' file is not supported. Please upload a valid image file. e.g. .jpg, .jpeg, .gif, .png, .ico';
				}
			}

			return $file;
		}
	}
}
