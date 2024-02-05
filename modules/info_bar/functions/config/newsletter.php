<?php
/**
 * Prohibit direct script loading.
 *
 * @package Convert_Plus.
 */

if ( function_exists( 'smile_framework_add_options' ) ) {
	smile_framework_add_options(
		'Convert_Plug_Smile_Info_Bars',
		'newsletter',
		array(
			'style_name'    => 'Newsletter',
			'demo_url'      => CP_PLUGIN_URL . 'modules/info_bar/assets/demos/newsletter/newsletter.html',
			'demo_dir'      => CP_BASE_DIR_IFB . 'functions/config/' . '../../assets/demos/newsletter/newsletter.html',
			'img_url'       => CP_PLUGIN_URL . 'modules/info_bar/assets/demos/newsletter/newsletter.png',
			'customizer_js' => CP_PLUGIN_URL . 'modules/info_bar/assets/demos/newsletter/customizer.js',
			'category'      => 'All,Optins',
			'tags'          => 'form',
			'options'       => array(),
		)
	);
}
