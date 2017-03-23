<?php
/**
* 2007-2016 PrestaShop
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
*  @copyright 2017 mpSOFT
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of mpSOFT
*/

if (!defined('MP_DBCLEAN_TEMPLATE_FOLDER')) {
    define(
        'MP_DBCLEAN_TEMPLATE_FOLDER',
        _PS_MODULE_DIR_ . 'mpdbclean'
        . DIRECTORY_SEPARATOR . 'views'
        . DIRECTORY_SEPARATOR . 'templates'
    );
}

class AdminMpDbCleanController extends ModuleAdminController
{
    private $smarty;
    private $msg_languages;
    private $msg_carriers;
    private $msg_currencies;
    
    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();
        $this->_LANG_  = $this->context->language->id;

        parent::__construct();

        $this->smarty = Context::getContext()->smarty;
        $this->msg_languages = [];
        $this->msg_currencies = [];
        $this->msg_carriers = [];
    }

    public function initToolbar()
    {
        parent::initToolbar();
        unset($this->toolbar_btn['new']);
    }

    public function postProcess()
    {
        if (!empty(Tools::getValue('txtLanguageList', ''))) {
            $this->processLanguages();
        }
        if (!empty(Tools::getValue('txtCarrierList', ''))) {
            $this->processCarriers();
        }
        if (!empty(Tools::getValue('txtCurrencyList', ''))) {
            $this->processCurrencies();
        }
    }
    
    /**
     * Process carrier deletion
     * @return boolean
     */
    private function processCarriers()
    {
        $carriers = Tools::getValue('txtCarrierList', '');
        $updCarrier = Tools::getValue('selUpdateCarrier');
        if (empty($carriers)) {
            return false;
        }
        //Get all carriers table
        $db = Db::getInstance();
        $listCarriers = $this->getMySQLTable("carrier");
        //Delete carrier reference
        foreach ($listCarriers as $carrier) {
            try {
                if (!$this->contains('order_carrier', $carrier)) {
                    $db->delete($carrier, "id_carrier in ($carriers)", 0, false, false);
                }
            } catch (Exception $exc) {
                if (!$this->contains('Unknown column', $exc->getMessage())) {
                    $this->msg_carriers[] = $exc->getMessage();
                }
            }
        }
        //Delete carrier range price and weight
        try {
            $db->delete('range_price', 'id_carrier in (' . $carriers . ')');
            $db->delete('range_weight', 'id_carrier in (' . $carriers . ')');
        } catch (Exception $exc) {
            $this->msg_carriers[] = ['type'=>'error', 'message'=>$exc->getMessage()];
        }

        $arrTableUpdate =
                [
                    'cart',
                    'cart_rule_carrier',
                    'delivery',
                    'order_carrier',
                    'orders',
                    'range_price',
                    'range_weight',
                    'warehouse_carrier'
                ];
        foreach ($arrTableUpdate as $table) {
            try {
                $db->update($table, ["id_carrier" => $updCarrier], "id_carrier in ($carriers)");
            } catch (Exception $exc) {
                $this->msg_carriers[] = ['type'=>'warning', 'message'=>$exc->getMessage()];
            }
        }
        if (empty($this->msg_carriers)) {
            $this->msg_carriers[] = ['type'=>'success', 'message'=>$this->l('Carriers successfully deleted')];
        }
        if (empty($carriers)) {
            $this->msg_carriers = [];
        }
    }
    
    /**
     * Process currency deletion
     * @return boolean
     */
    private function processCurrencies()
    {
        $currencies  = Tools::getValue('txtCurrencyList', '');
        $updCurrency = Tools::getValue('selUpdateCurrency');
        if (empty($currencies)) {
            return false;
        }
        //Get all currencies table
        $db = Db::getInstance();
        $listCurrencies = $this->getMySQLTable("currency");
        foreach ($listCurrencies as $currency) {
            try {
                $db->delete($currency, "id_currency in ($currencies)", 0, false, false);
            } catch (Exception $exc) {
                $this->msg_currencies[] = ['type'=>'warning', 'message'=>$exc->getMessage()];
            }
        }
        $arrTableUpdate =
                [
                    'cart',
                    'layered_price_index',
                    'order_payment',
                    'orders',
                    'product_supplier',
                    'specific_price',
                    'specific_price_rule',
                    'supply_order',
                    'supply_order_detail',
                    'warehouse',
                ];
        foreach ($arrTableUpdate as $table) {
            try {
                $db->update($table, ["id_currency" => $updCurrency], "id_currency in ($currencies)");
            } catch (Exception $exc) {
                $this->msg_currencies[] = ['type'=>'warning', 'message'=>$exc->getMessage()];
            }
        }
        if (empty($this->msg_currencies)) {
            $this->msg_currencies[] = ['type'=>'success', 'message'=>$this->l('Currencies successfully deleted')];
        }
        if (empty($currencies)) {
            $this->msg_currencies = [];
        }
    }
    
    /**
     * @return boolean 
     */
    private function processLanguages()
    {
        $langs =  Tools::getValue('txtLanguageList', '');
        if (!empty($langs)) {
            //DELETE ALL SELECTED TRANSLATIONS
            $db = Db::getInstance();
            $sqlTables = "show tables;";
            $resultTbl = [];
            $tables = $db->executeS($sqlTables);
            foreach ($tables as $tableArr) {
                foreach ($tableArr as $table) {
                    if ($this->contains("_lang", $table) && $table!=_DB_PREFIX_ . "lang") {
                        try {
                            $db->delete($table, "id_lang in ($langs)", 0, false, false);
                        } catch (Exception $exc) {
                            $resultTbl[] = ["name"=>$table, "msg"=>$exc->getMessage()];
                        }
                    }
                }
            }
            $this->smarty->assign('lang_result', $resultTbl);
            if (empty($resultTbl)) {
                $mail_targets = [];
                //Get iso_code
                $sqlISO = new DbQueryCore();
                $sqlISO ->select('iso_code')
                        ->from("lang")
                        ->where("id_lang in ($langs)");
                $resultISO = $db->executeS($sqlISO);
                //DELETE MAIL FOLDER
                foreach ($resultISO as $iso) {
                    $iso_code = $iso['iso_code'];
                    if ($iso_code!='en') {
                        $target = _PS_MAIL_DIR_ . $iso_code;
                        $mail_targets[] = $target;
                        $this->deleteFolder($target);
                    }
                }
                //DELETE LANGUAGE
                try {
                    $db->delete("lang", "id_lang in($langs)");
                } catch (Exception $exc) {
                    $this->msg_languages[] = ['type'=>'error', 'message'=>$exc->getMessage()];
                }
            } else {
                foreach ($resultTbl as $error) {
                    $this->msg_languages[] = ['type'=>'warning', 'message'=>$error['msg']];
                }
            }
        }
        if (empty($this->msg_languages)) {
            $this->msg_languages[] = ['type'=>'success', 'message'=>$this->l('Languages successfully deleted')];
        }
        if (empty($langs)) {
            $this->msg_languages = [];
        }
    }
    
    public function setMedia()
    {
        parent::setMedia();
    }

    public function initContent()
    {
        /**
         * Check form submit
         */
        $this->postProcess();
        
        parent::initContent();
        
        //DISPLAY MESSAGES
        $messageTemplatePath = MP_DBCLEAN_TEMPLATE_FOLDER
                . DIRECTORY_SEPARATOR .  'admin'
                . DIRECTORY_SEPARATOR . 'displayMessages.tpl';
        $this->smarty->assign('messages', $this->msg_carriers);
        $fetchCarriers = $this->smarty->fetch($messageTemplatePath);
        $this->smarty->assign('carriers_display_messages', $fetchCarriers);
        
        $this->smarty->assign('messages', $this->msg_currencies);
        $fetchCurrencies = $this->smarty->fetch($messageTemplatePath);
        $this->smarty->assign('currencies_display_messages', $fetchCurrencies);
        
        $this->smarty->assign('messages', $this->msg_languages);
        $fetchLanguages = $this->smarty->fetch($messageTemplatePath);
        $this->smarty->assign('languages_display_messages', $fetchLanguages);
        
        $this->smarty->clearAssign('messages');
        
        $this->smarty->assign('lang_rows', $this->getLanguageListContent());
        $this->smarty->assign('carrier_rows', $this->getCarrierListContent());
        $this->smarty->assign('currency_rows', $this->getCurrencyListContent());
        $this->smarty->assign('carrier_enabled', $this->getCarrierEnabledList());
        $this->smarty->assign('currency_enabled', $this->getCurrencyEnabledList());
        $this->smarty->assign('img_lang_dir', $this->getRootPath() . 'img/l/');
        $this->smarty->assign('img_carrier_dir', $this->getRootPath() . 'img/s/');
        $this->smarty->assign('img_folder', $this->getRootPath() . 'modules/mpdbclean/views/img/');
        $this->smarty->assign('msg_languages', $this->msg_languages);
        $this->smarty->assign('msg_carriers', $this->msg_carriers);
        $this->smarty->assign('msg_currencies', $this->msg_currencies);
        $content =  $this->smarty->fetch(_PS_MODULE_DIR_ . 'mpdbclean/views/templates/admin/form.tpl');
        $this->smarty->assign(array('content' => $content));
    }
    
    /**
     * Get the root payh of site
     */
    private function getRootPath()
    {
        $http = '';
        if (empty($_SERVER['HTTPS'])) {
            $http = 'http://';
        } else {
            $http = 'https://';
        }
        $root = $_SERVER['REWRITEBASE'];
        return $http . $_SERVER['HTTP_HOST'] . $root;
    }
    
    /**
     * Generate language list content
     */
    private function getLanguageListContent()
    {
        $db = Db::getInstance();
        $query = new DbQueryCore();
        
        $query
                ->select('id_lang')
                ->select('name')
                ->select('iso_code')
                ->from('lang')
                ->where('active=0')
                ->orderBy('name');
        $result = $db->executeS($query);
        
        return $result;
    }
    
    /**
     * generate carrier list
     * @return resultset
     */
    private function getCarrierListContent()
    {
        $db = Db::getInstance();
        $query = new DbQueryCore();
        
        $query
                ->select('id_carrier')
                ->select('name')
                ->from('carrier')
                ->where('active=0 or deleted=1')
                ->orderBy('name');
        $result = $db->executeS($query);
        
        return $result;
    }
    
    /**
     * generate currency list
     * @return resultset
     */
    private function getCurrencyListContent()
    {
        $db = Db::getInstance();
        $query = new DbQueryCore();
        
        $query
                ->select('id_currency')
                ->select('name')
                ->select('iso_code')
                ->select('sign')
                ->from('currency')
                ->where('active=0 or deleted=1')
                ->orderBy('name');
        $result = $db->executeS($query);
        
        return $result;
    }
    
    /**
     * generate carrier list
     * @return resultset
     */
    private function getCarrierEnabledList()
    {
        $db = Db::getInstance();
        $query = new DbQueryCore();
        
        $query
                ->select('id_carrier')
                ->select('name')
                ->from('carrier')
                ->where('active=1')
                ->where('deleted=0')
                ->orderBy('name');
        $result = $db->executeS($query);
        
        return $result;
    }
    
    /**
     * generate carrier list
     * @return resultset
     */
    private function getCurrencyEnabledList()
    {
        $db = Db::getInstance();
        $query = new DbQueryCore();
        
        $query
                ->select('id_currency')
                ->select('name')
                ->from('currency')
                ->where('active=1')
                ->where('deleted=0')
                ->orderBy('name');
        $result = $db->executeS($query);
        
        return $result;
    }
    
    /**
     * Find all table in database containing a specific word
     * @param type $contains string to match table list
     * @return array
     */
    private function getMySQLTable($contains)
    {
        $db = Db::getInstance();
        $result = $db->executeS("show tables;");
        $tables = [];
        foreach ($result as $row) {
            foreach ($row as $col) {
                if ($this->contains($contains, $col)) {
                    $tables[] = $col;
                }
            }
        }
        return $tables;
    }
    
    /**
     * Check if a string is contained in another string
     * @param type $needle string to find 
     * @param type $haystack string to search in
     * @return boolean
     */
    private function contains($needle, $haystack)
    {
        return strpos($haystack, $needle) !== false;
    }
    
    /**
     * Delete whole folder specified
     * @param type $target path to folder to delete
     * @return boolean
     */
    private function deleteFolder($target)
    {
        if (empty($target)) {
            return false;
        }
        
        if (!file_exists($target)) {
            return false;
        }
        
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                $this->deleteFolder($file);
            }
            if (!file_exists($target)) {
                chmod($target, 0777);
                rmdir($target);
            }
        } elseif (is_file($target)) {
            chmod($target, 0777);
            unlink($target);
        }
    }
}
