<?php
/**
 * 2007-2022 PrestaShop
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
 *  @copyright 2007-2022 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

$mod_name = 'convertica';
$languages = Language::getLanguages(false);
$main_tab = new Tab();
$main_tab->active = 1;
$main_tab->class_name = 'AdminConverticaMain';
$main_tab->name = [];

foreach ($languages as $lang) {
    $main_tab->name[$lang['id_lang']] = 'Convertica';
}
$main_tab->id_parent = '';
$main_tab->module = $mod_name;
$main_tab->add();

$tabs = [];

$id_parent = Tab::getIdFromClassName('AdminConverticaMain');
$tabs = [
    [
        'class_name' => 'AdminConvDashboard',
        'id_parent' => $id_parent,
        'name' => 'Dashboard',
        'icon' => 'brush',
    ],
    [
        'class_name' => 'AdminConvModalpop',
        'id_parent' => $id_parent,
        'name' => 'Modal Popup',
        'icon' => 'brush',
    ],
    [
        'class_name' => 'AdminConvInfobar',
        'id_parent' => $id_parent,
        'name' => 'Info Bar',
        'icon' => 'brush',
    ],
    [
        'class_name' => 'AdminConvSlidein',
        'id_parent' => $id_parent,
        'name' => 'Slide In',
        'icon' => 'brush',
    ],
    // [
    //     'class_name' => 'AdminCrazyExtendedmodules',
    //     'id_parent' => $id_parent,
    //     'module' => 'crazyelements',
    //     'name' => 'Extend Third Party Modules',
    //     'active' => 1,
    // ],
    // [
    //     'class_name' => 'AdminCrazyFrontendEditor',
    //     'id_parent' => $id_parent,
    //     'module' => 'crazyelements',
    //     'name' => 'Crazyelements Frontend Editor',
    //     'active' => 0,
    // ],
    // [
    //     'class_name' => 'AdminCrazyAjaxUrl',
    //     'id_parent' => -1,
    //     'module' => 'crazyelements',
    //     'name' => 'AdminCrazyAjaxUrl',
    //     'active' => 1,
    // ],
    // [
    //     'class_name' => 'AdminCrazyImages',
    //     'id_parent' => -1,
    //     'module' => 'crazyelements',
    //     'name' => 'Image Type',
    //     'active' => 1,
    // ]
];

foreach($tabs as $t){
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = $t['class_name'];
    $tab->name = [];
    
    foreach ($languages as $lang) {
        $tab->name[$lang['id_lang']] = $t['name'];
    }
    $tab->id_parent = $t['id_parent'];
    $tab->module = $mod_name;
    $tab->add();
}