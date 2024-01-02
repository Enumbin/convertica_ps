<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

defined( '_PS_VERSION_' ) || die( 'No direct script access allowed!' );

if ( isset( $_REQUEST['cp_admin_page_nonce'] ) && ! wp_verify_nonce( $_REQUEST['cp_admin_page_nonce'], 'cp_admin_page' ) ) {
	return;
}

$test = isset( $_GET['variant-test'] ) ? esc_attr( $_GET['variant-test'] ) : 'main';

switch ( $test ) {
	case 'new':
		require_once CP_BASE_DIR_MODAL . '/views/variant/new.php';
		break;
	case 'edit':
		require_once CP_BASE_DIR_MODAL . '/views/variant/edit.php';
		break;
	case 'main':
	default:
		require_once CP_BASE_DIR_MODAL . '/views/variant/variant.php';
		break;
}
