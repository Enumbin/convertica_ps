<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */
// edited
$helper_instance = Helper_Global::get_instance();
// Add new input type "border width".
if ( function_exists( 'smile_add_input_type' ) ) {
	smile_add_input_type( 'margin', 'margin_settings_field' );
}

$helper_instance->add_action( 'admin_enqueue_scripts', 'enqueue_margin_param_scripts' );
/**
 * Function Name:enqueue_margin_param_scripts description.
 *
 * @param  array $hook ap page list.
 */
function enqueue_margin_param_scripts( $hook ) {
	if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
		$cp_page = strpos( $hook, CP_PLUS_SLUG );
		$helper_instance = Helper_Global::get_instance();
		$data    = $helper_instance->convertica_get_option( 'convert_plug_debug' );

		if ( false !== $cp_page && isset( $_GET['style-view'] ) && 'edit' === $_GET['style-view'] ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-ui-core' );
			wp_enqueue_script( 'convert-plus-margin-script', SMILE_FRAMEWORK_URI . '/lib/fields/margin/js/margin.js', array( 'jquery' ), CP_VERSION, false );

			wp_enqueue_style( 'jquery-ui' );
			if ( isset( $data['cp-dev-mode'] ) && '1' === $data['cp-dev-mode'] ) {
				wp_enqueue_style( 'convert-plus-margin-style', SMILE_FRAMEWORK_URI . '/lib/fields/margin/css/margin.css', array(), CP_VERSION );
			}
		}
	}
}

/**
 * Function Name:margin_settings_field Function to handle new input type "margin".
 *
 * @param  string $name     settings provided when using the input type "margin".
 * @param  string $settings holds the default / updated value.
 * @param  string $value    html output generated by the function.
 * @return string           html output generated by the function.
 */
function margin_settings_field( $name, $settings, $value ) {
	$input_name = $name;
	$type       = isset( $settings['type'] ) ? $settings['type'] : '';
	$class      = isset( $settings['class'] ) ? $settings['class'] : '';
	$output     = '<p><textarea id="margin-code" class="content form-control smile-input smile-' . $type . ' ' . $input_name . ' ' . $type . ' ' . $class . '" name="' . $input_name . '" rows="6" cols="6">' . $value . '</textarea></p>';

	$pairs    = explode( '|', $value );
	$settings = array();
	if ( is_array( $pairs ) && ! empty( $pairs ) && 1 < count( $pairs ) ) {
		foreach ( $pairs as $pair ) {
			$values                 = explode( ':', $pair );
			$settings[ $values[0] ] = $values[1];
		}
	}

	$all_sides = isset( $settings['all_sides'] ) ? $settings['all_sides'] : 1;
	$top       = isset( $settings['top'] ) ? $settings['top'] : 1;
	$left      = isset( $settings['left'] ) ? $settings['left'] : 1;
	$right     = isset( $settings['right'] ) ? $settings['right'] : 1;
	$bottom    = isset( $settings['bottom'] ) ? $settings['bottom'] : 1;

	ob_start();
	echo wp_kses_post( $output );
	?>
	<div class="box">
		<div class="holder">
			<div class="frame">
				<div class="setting-block all-sides">
					<div class="row">
						<label for="margin"><?php esc_html_e( 'All Sides', 'smile' ); ?></label>
						<label class="align-right" for="margin-all_sides">px</label>
						<div class="text-1">
							<input id="margin-all_sides" type="text" value="<?php echo esc_attr( $all_sides ); ?>">
						</div>
					</div>
					<div id="slider-margin-all_sides" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="left: 0%;"></a><span class="range-quantity" ></span></div>
				</div>    
				<div class="setting-block">
					<div class="row">
						<label for="top"><?php esc_html_e( 'Top', 'smile' ); ?></label>
						<label class="align-right" for="top">px</label>
						<div class="text-1">
							<input id="margin-top" type="text" value="<?php echo esc_attr( $top ); ?>">
						</div>
					</div>
					<div id="slider-margin-top" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a><span class="range-quantity" ></span></div>
					<div class="row mtop15">
						<label for="margin-left"><?php esc_html_e( 'Left', 'smile' ); ?></label>
						<label class="align-right" for="left">px</label>
						<div class="text-1">
							<input id="margin-left" type="text" value="<?php echo esc_attr( $left ); ?>">
						</div>
					</div>
					<div id="slider-margin-left" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a><span class="range-quantity" ></span></div>
					<div class="row mtop15">
						<label for="right"><?php esc_html_e( 'Right', 'smile' ); ?></label>
						<label class="align-right" for="right">px</label>
						<div class="text-1">
							<input id="margin-right" type="text" value="<?php echo esc_attr( $right ); ?>">
						</div>
					</div>
					<div id="slider-margin-right" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a><span class="range-quantity" ></span></div>
					<div class="row mtop15">
						<label for="bottom"><?php esc_html_e( 'Bottom', 'smile' ); ?></label>
						<label class="align-right" for="bottom">px</label>
						<div class="text-1">
							<input id="margin-bottom" type="text" value="<?php echo esc_attr( $bottom ); ?>">
						</div>
					</div>
					<div id="slider-margin-bottom" class="slider-bar large ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"><a class="ui-slider-handle ui-state-default ui-corner-all" href="#"></a><span class="range-quantity" ></span></div>
				</div>
			</div>
		</div>
	</div>  

	<?php
	return ob_get_clean();
}
