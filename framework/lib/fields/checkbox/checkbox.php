<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

// Add new input type "checkbox".
if ( function_exists( 'smile_add_input_type' ) ) {
	smile_add_input_type( 'checkbox', 'cp_checkbox_settings_field' );
}

/**
 * Function Name:cp_checkbox_settings_field Function to handle new input type "switch".
 *
 * @param  string $name     settings provided when using the input type "switch".
 * @param  string $settings holds the default / updated value.
 * @param  string $value    html output generated by the function.
 * @return string           html output generated by the function.
 */
function cp_checkbox_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$options    = isset( $settings['options'] ) ? $settings['options'] : '';
	$output     = '';
	$n          = 0;
	$values     = explode( '|', $value );
	$output    .= '<p><input type="hidden" name="' . $input_name . '" value="' . $value . '" id="smile_' . $input_name . '" class="form-control smile-input smile-' . $type . ' ' . $input_name . '"></p>';
	if ( is_array( $options ) && '' !== $options ) {
		foreach ( $options as $text_val => $val ) {
			if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
				$text_val = $val;
			}
			$checked = '';
			if ( '' !== $value && in_array( $val, $values ) ) {
				$checked = ' checked="checked"';
			}
			$output .= '<div class="checkbox">
			<p><label><input type="checkbox" value="' . $val . '" id="smile_' . $input_name . '_' . $n . '" class="smile-' . $type . ' smile_' . $input_name . '" ' . $checked . '>' . $text_val . '</label></p></div>';
			$n++;

		}
	}
	$output .= cp_get_checkbox_js( $input_name );

	return $output;
}

if ( ! function_exists( 'cp_get_checkbox_js' ) ) {
	/**
	 * Function Name: cp_get_checkbox_js.
	 *
	 * @param  string $input_name string parameter.
	 * @return mixed             mixed val.
	 */
	function cp_get_checkbox_js( $input_name ) {
		$helper_instance = Helper_Global::get_instance();
		ob_start();
		?>
		<script type="text/javascript">
			jQuery(document).ready( function() {
				var checkbox = jQuery(".smile_<?php echo $helper_instance->esc_attr( $input_name ); ?>");
				var input = jQuery("#smile_<?php echo $helper_instance->esc_attr( $input_name ); ?>");
				var val = '';
				checkbox.on("change",function(){
					val = "";
					jQuery.each( checkbox, function(){
						var isChecked = jQuery(this).is(":checked");
						if( isChecked ) {
							val += jQuery(this).val()+"|";
						}
					});
					val = val.slice(0,-1)
					input.val(val);
					input.attr("value",val);
					input.trigger("change");
					jQuery(document).trigger('smile-checkbox-change',[input , val] );
				});
			});
		</script>
		<?php
		return ob_get_clean();
	}
}
