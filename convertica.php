<?php
/**
* 2007-2023 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2023 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Convertica extends Module
{
    protected $config_form = false;
    public $helper_instance;

    public function __construct()
    {
        $this->name = 'convertica';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.0';
        $this->author = 'ClassyDevs';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Convertica PrestaShop');
        $this->description = $this->l('Convertica PrestaShop');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->define_constants();
        $this->include_mandatories();

        $this->helper_instance = Helper_Global::get_instance();
        $this->helper_instance->include_core_files();
    }

    public function define_constants(){
        if ( ! defined( 'CP_VERSION' ) ) {
            define( 'CP_VERSION', '3.5.24' );
        }
        
        if ( ! defined( 'CP_BASE_DIR' ) ) {
            define( 'CP_BASE_DIR', _PS_MODULE_DIR_ . $this->name );
        }

        if ( ! defined( '__CP_ROOT__' ) ) {
            define( '__CP_ROOT__', dirname( __FILE__ ) );
        }

        if ( ! defined( 'CP_BASE_URL' ) ) {
            define( 'CP_BASE_URL', _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/' . $this->name . '/' );
        }
        
        if ( ! defined( 'CP_DIR_NAME' ) ) {
            define( 'CP_DIR_NAME', $this->name );
        }

        if ( ! defined( 'CP_DIR_FILE_NAME' ) ) {
            define( 'CP_DIR_FILE_NAME', $this->name . '/' . $this->name . '.php' );
        }
        
        if ( ! defined( 'CP_PLUS_NAME' ) ) {
            define( 'CP_PLUS_NAME', 'Convert Plus' );
        }
        
        if ( ! defined( 'CP_PLUS_SLUG' ) ) {
            define( 'CP_PLUS_SLUG', 'convert-plus' );
        }

        if ( ! defined( 'CP_PLUS_UPLOADS_DIR' ) ) {
            define( 'CP_PLUS_UPLOADS_DIR', CP_BASE_DIR . '/uploads/' );
        }

        if ( ! defined( 'CP_PLUS_UPLOADS_URL' ) ) {
            define( 'CP_PLUS_UPLOADS_URL', CP_BASE_URL . 'uploads/' );
        }
        
        // if ( is_admin() ) {
            // register_activation_hook( __FILE__, 'on_cp_activate' );
        // }
        
        if ( ! defined( 'CP_PLUGIN_URL' ) ) {
            define( 'CP_PLUGIN_URL', _PS_BASE_URL_SSL_ . __PS_BASE_URI__ . 'modules/' . $this->name . '/' );
        }
    }

    public function include_mandatories(){
        require_once CP_BASE_DIR . '/helper_global.php';
    }
    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('CONVERTICA_LIVE_MODE', false);
        include CP_BASE_DIR . '/sql/install.php';
        include CP_BASE_DIR . '/sql/install_tabs.php';

        $this->helper_instance->convertica_update_option( 'convert_plug_redirect', true );
        $this->helper_instance->convertica_update_option( 'dismiss-cp-update-notice', false );

        $cp_previous_version = $this->helper_instance->convertica_get_option( 'cp_previous_version' );

        if ( ! $cp_previous_version ) {
            $this->helper_instance->convertica_update_option( 'cp_is_new_user', true );
        } else {
            $this->helper_instance->convertica_update_option( 'cp_is_new_user', false );
        }

        $this->create_default_campaign();



        // save previous version of plugin in option.
        $this->helper_instance->convertica_update_option( 'cp_previous_version', CP_VERSION );

        // create_cplus_maxmind_folder_on_activation();

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader');
            $this->registerHook('displayFooter');
    }

    public function create_default_campaign() {
        // create default campaign.
        $smile_lists = $this->helper_instance->convertica_get_option( 'smile_lists' );
        if ( ! $smile_lists ) {
            $data = array();
            $list = array(
                'date'          => gmdate( 'd-m-Y' ),
                'list-name'     => 'First',
                'list-provider' => 'Convert Plug',
                'list'          => '',
                'provider_list' => '',
            );

            $data[] = $list;
            $this->helper_instance->convertica_update_option( 'smile_lists', $data );

        }

        $data_settings = $this->helper_instance->convertica_get_option(  'convert_plug_settings' );
        if ( ! $data_settings ) {
            $module_setings = array(
                'cp-enable-mx-record'   => '0',
                'cp-default-messages'   => '1',
                'cp-already-subscribed' => 'Already Subscribed...!',
                'cp-double-optin'       => '1',
                'cp-gdpr-optin'         => '1',
                'cp-sub-notify'         => '0',
                'cp-sub-email'          => Configuration::get('PS_SHOP_EMAIL'),
                'cp-email-sub'          => 'Congratulations! You have a New Subscriber!',
                'cp-google-fonts'       => '1',
                'cp-timezone'           => 'wordpress',
                'user_inactivity'       => '60',
                'cp-edit-style-link'    => '0',
                'cp-plugin-support'     => '0',
                'cp-disable-impression' => '0',
                'cp-close-inline'       => '0',
                'cp-disable-storage'    => '0',
                'cp-disable-pot'        => '1',
                'cp-disable-domain'     => '0',
                'cp-domain-name'        => '',
                'cp-lazy-img'           => '0',
                'cp-close-gravity'      => '1',
                'cp-load-syn'           => '0',
                'cp_change_ntf_id'      => '1',
                'cp_notify_email_to'    => Configuration::get('PS_SHOP_EMAIL'),
                'cp-access-role'        => '',
                'cp-user-role'          => 'administrator',
                'cp-new-user-role'      => '',
                'cp-email-body'         => '',
            );
            $this->helper_instance->convertica_update_option( 'convert_plug_settings', $module_setings );
        }

    }

    public function uninstall()
    {
        Configuration::deleteByName('CONVERTICA_LIVE_MODE');

        include CP_BASE_DIR . '/sql/uninstall.php';
        include CP_BASE_DIR . '/sql/uninstall_tabs.php';

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitConverticaModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitConverticaModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'CONVERTICA_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'CONVERTICA_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'CONVERTICA_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'CONVERTICA_LIVE_MODE' => Configuration::get('CONVERTICA_LIVE_MODE', true),
            'CONVERTICA_ACCOUNT_EMAIL' => Configuration::get('CONVERTICA_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'CONVERTICA_ACCOUNT_PASSWORD' => Configuration::get('CONVERTICA_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookDisplayBackOfficeHeader()
    {
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
        Media::addJsDef([
            'ajaxurl' => $this->context->link->getAdminLink('AdminConvAjax'),
        ]);
        $this->helper_instance->do_action('admin_print_scripts');
        $this->helper_instance->do_action('admin_enqueue_scripts');
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');

        $this->helper_instance->do_action('wp_enqueue_scripts');
    }

    private function displayFooter(){
        $this->helper_instance->do_action('wp_footer');
    }
}
