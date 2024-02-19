<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

defined( '_PS_VERSION_' ) || die( 'No direct script access allowed!' );

$helper_instance = Helper_Global::get_instance();
$is_cp_status  = ( function_exists( 'bsf_product_status' ) ) ? bsf_product_status( '14058953' ) : '';
$reg_menu_hide = ( ( defined( 'BSF_UNREG_MENU' ) && ( true === BSF_UNREG_MENU || 'true' === BSF_UNREG_MENU ) ) ||
	( defined( 'BSF_REMOVE_14058953_FROM_REGISTRATION' ) && ( true === BSF_REMOVE_14058953_FROM_REGISTRATION || 'true' === BSF_REMOVE_14058953_FROM_REGISTRATION ) ) ) ? true : false;
if ( true !== $reg_menu_hide ) {
	if ( $is_cp_status ) {
		$reg_menu_hide = true;
	}
}
?>
<div id="main">
    <div id="content"  class="bootstrap">
	<div class="wrap about-wrap about-cp bend">
		<div class="wrap-container">
			<div class="bend-heading-section cp-about-header">
				<h1>
				<?php
				/* translators:%s plugin name*/
				echo sprintf( $helper_instance->esc_html__( 'Welcome to %s !', 'smile' ), CP_PLUS_NAME);
				?>
				</h1>
				<h3>
				<?php
				/* translators:%s plugin name %s plugin name */
				echo sprintf( $helper_instance->esc_html__( 'Welcome to %1$s - the easiest WordPress plugin to convert website traffic into leads. %2$s will help you build email lists, drive traffic, promote videos, offer coupons and much more!', 'smile' ), CP_PLUS_NAME, CP_PLUS_NAME );
				?>
				</h3>
				<div class="bend-head-logo">
					<div class="bend-product-ver">
						<?php
						echo $helper_instance->esc_html__( 'Version', 'smile' );
						echo ' ' . CP_VERSION;
						?>
					</div>
				</div>
			</div><!-- bend-heading section -->
			<div class="msg"></div>
			<div class="bend-content-wrap">
				<h2 class="nav-tab-wrapper">
				<?php
						$cp_about = Context::getContext()->link->getAdminLink('AdminConvDashboard');
						// $cp_about          = add_query_arg(
						// 	array(
						// 		'page' => CP_PLUS_SLUG,
						// 	),
						// 	admin_url( 'admin.php' )
						// );
						$cp_modules = Context::getContext()->link->getAdminLink('AdminConvDashboard', true, [], ['view' => 'modules']);
						// $cp_modules        = add_query_arg(
						// 	array(
						// 		'page' => CP_PLUS_SLUG,
						// 		'view' => 'modules',
						// 	),
						// 	admin_url( 'admin.php' )
						// );
						$cp_knowledge_base = Context::getContext()->link->getAdminLink('AdminConvDashboard', true, [], ['view' => 'knowledge_base']);
						// $cp_knowledge_base = add_query_arg(
						// 	array(
						// 		'page' => CP_PLUS_SLUG,
						// 		'view' => 'knowledge_base',
						// 	),
						// 	admin_url( 'admin.php' )
						// );
						$cp_debug_author = Context::getContext()->link->getAdminLink('AdminConvDashboard', true, [], ['view' => 'debug', 'author' => 'true']);
						// $cp_debug_author   = add_query_arg(
						// 	array(
						// 		'page'   => CP_PLUS_SLUG,
						// 		'view'   => 'debug',
						// 		'author' => 'true',
						// 	),
						// 	admin_url( 'admin.php' )
						// );
						$cp_settings = Context::getContext()->link->getAdminLink('AdminConvDashboard', true, [], ['view' => 'settings']);
						// $cp_settings       = add_query_arg(
						// 	array(
						// 		'page' => CP_PLUS_SLUG,
						// 		'view' => 'settings',
						// 	),
						// 	admin_url( 'admin.php' )
						// );
						?>
					<a class="nav-tab" href="<?php echo $cp_about; ?>" title="<?php echo $helper_instance->esc_html__( 'About', 'smile' ); ?>"><?php echo $helper_instance->esc_html__( 'About', 'smile' ); ?></a>
					<a class="nav-tab nav-tab-active" href="<?php echo $cp_modules; ?>" title="<?php echo $helper_instance->esc_html__( 'Modules', 'smile' ); ?>"><?php echo $helper_instance->esc_html__( 'Modules', 'smile' ); ?></a>

					<a class="nav-tab" href="<?php echo $cp_knowledge_base; ?>" title="<?php echo $helper_instance->esc_html__( 'knowledge Base', 'smile' ); ?>"><?php echo $helper_instance->esc_html__( 'Knowledge Base', 'smile' ); ?></a>

					<?php
					if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
						if ( isset( $_GET['author'] ) ) {
							?>
					<a class="nav-tab" href="<?php echo $cp_debug_author; ?>" title="<?php echo $helper_instance->esc_html__( 'Debug', 'smile' ); ?>"><?php echo $helper_instance->esc_html__( 'Debug', 'smile' ); ?></a>
							<?php
						}
					}
					?>

				</h2>
				<div id="smile-module-settings">
					<?php
					$modules        = Smile_Framework::$modules;
					$stored_modules = $helper_instance->convertica_get_option( 'convert_plug_modules' );

					?>
					<form id="convert_plug_modules" class="cp-modules-list">
						<input type="hidden" name="action" value="SmileUpdateModules" />
						<input type="hidden" name="controller" value="AdminConvAjax" />
						<input type="hidden" name="ajax" value="true" />
						<?php
						$output = '';
						foreach ( $modules as $module => $opts ) {
							$file        = $opts['file'];
							$module_img  = $opts['img'];
							$module_desc = $opts['desc'];
							$module_name = str_replace( ' ', '_', $module );
							$checked     = is_array( $stored_modules ) && in_array( $module_name, $stored_modules ) ? 'checked="checked"' : '';
							$output     .= '<div class="cp-module-box">';
							$output     .= '<div class="cp-module">';
							$output     .= "\t" . '<div class="cp-module-switch">';
							$uniq        = uniqid();
							$output     .= "\t\t" . '<div class="switch-wrapper">
							<input type="text"  id="smile_' . $module_name . '" class="form-control smile-input smile-switch-input "  value="' . $module . '" />
							<input type="checkbox" ' . $checked . ' id="smile_' . $module_name . '_btn_' . $uniq . '" name="' . $module_name . '" class="ios-toggle smile-input smile-switch-input switch-checkbox smile-switch " value="' . $module . '" >
							<label class="smile-switch-btn checkbox-label" data-on="ON"  data-off="OFF" data-id="smile_' . $module_name . '" for="smile_' . $module_name . '_btn_' . $uniq . '">
							</label>
							</div>';
							$output     .= "\t" . '</div>';
							$output     .= "\t" . '<div class="cp-module-desc">';
							$output     .= "\t" . '<h3>' . $module . '</h3>';
							$output     .= "\t" . '<p>' . $module_desc . '</p>';
							$output     .= "\t" . '</div>';
							$output     .= '</div>';
							$output     .= '</div>';
						}

						echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</form>
					<button type="button" class="button button-primary button-hero button-update-modules"><?php echo $helper_instance->esc_html__( 'Save Modules', 'smile' ); ?></button>
					<a class="button button-secondary button-hero advance-cp-setting" href="<?php echo $cp_settings; ?>" title="<?php echo $helper_instance->esc_html__( 'Advanced Settings', 'smile' ); ?>"><?php echo $helper_instance->esc_html__( 'Advanced Settings', 'smile' ); ?></a>

				</div>
			</div>
		</div>
		</div>
	</div><!-- #content -->
</div><!-- #main -->
