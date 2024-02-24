<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

//  require_once(dirname(__FILE__) . '/../../classes/class-convert-plug.php');

class AdminConvDashboardController extends ModuleAdminController
{
    /**
     * @var gamification
     */
    public $module;
    public $helper_instance;
    public $convert_plug;

    public function __construct()
    {
        $this->bootstrap = true;
        parent::__construct();

        $this->helper_instance = Helper_Global::get_instance();
        $this->convert_plug = new Convert_Plug();

    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addJqueryUI('ui.progressbar');
        $this->addJS(_MODULE_DIR_ . $this->module->name . '/views/js/bubble-popup.js');

        if (version_compare(_PS_VERSION_, '1.6.0', '>=') === true) {
            $this->addJs(_MODULE_DIR_ . $this->module->name . '/views/js/gamification_bt.js');
        } else {
            $this->addJs(_MODULE_DIR_ . $this->module->name . '/views/js/gamification.js');
        }
        // echo 'hello1';
        // echo __FILE__ . ' : ' . __LINE__;
        $controller = Context::getContext()->controller->controller_name;
        $this->helper_instance->do_action('admin_print_scripts-' . $controller);
        // die(__FILE__ . ' : ' . __LINE__);

        $this->addCSS(CP_PLUGIN_URL . 'admin/assets/css/wp.css');
        $this->addJs(_MODULE_DIR_ . $this->module->name . '/views/js/jquery.isotope.js');
        $this->addCSS([_MODULE_DIR_ . $this->module->name . '/views/css/bubble-popup.css', _MODULE_DIR_ . $this->module->name . '/views/css/isotope.css']);
    }

    public function initPageHeaderToolbar()
    {
        return;
    }


    public function initContent(){

        parent::initContent();

        $this->helper_instance->do_action('admin_menu');
    }
}