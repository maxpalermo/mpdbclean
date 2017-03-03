<?php
/**
* 2007-2015 PrestaShop
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
*  @author    mpSOFT Massimiliano Palermo <info@mpsoft.it>
*  @copyright 2017 mpSOFT®
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of mpSOFT®
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class MpDbClean extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'mpdbclean';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'mpsoft';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('DB Clean');
        $this->description = $this->l('Cleans the database from unnecessary information, such as additional languages, currencies, couriers, and more.');

        $this->confirmUninstall = $this->l('This action uninstall module. Are you sure?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        return parent::install()
                && $this->registerHook('backOfficeHeader')
                && $this->installTab();
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallTab();
    }
    
    /**
     * Install Tab voice in Admin Tab Menu 
     */
    public function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminMpDbClean';
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
                $tab->name[$lang['id_lang']] = $this->l('DB Clean');
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminTools');
        $tab->module = $this->name;
        return $tab->add();
    }
    
    /**
     * Remove Tab voice from Admin Tab Menu
     */
    public function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminMpDbClean');
        if ($id_tab) {
                $tab = new Tab($id_tab);
                return $tab->delete();
        } else {
                return false;
        }
    }
    
    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        //$this->context->controller->addJS($this->_path.'views/js/back.js');
        //$this->context->controller->addCSS($this->_path.'views/css/back.css');
    }
}
