<?php
/**
* 2007-2025 PrestaShop
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
*  @copyright 2007-2025 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Kwk_topbar extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'kwk_topbar';
        $this->tab = 'front_office_features';
        $this->version = '1.0.1';
        $this->author = 'Kwansook';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Top Bar', [], 'Modules.Kwk_topbar.Admin');
        $this->description = $this->trans('Add top bar on your website', [], 'Modules.Kwk_topbar.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    public function install()
    {
        return parent::install() &&
            $this->createTable() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayHeader') && 
            $this->installTab() &&
            Configuration::updateValue('KWK_TOPBAR_IS_ACTIVE', false);
    }

    public function uninstall()
    {
        return Configuration::deleteByName('KWK_TOPBAR_IS_ACTIVE') &&
            $this->dropTable() &&
            $this->uninstallTab() &&
            parent::uninstall();
    }

    private function createTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "kwk_topbar` (
            `id_kwk_topbar` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `content` TEXT NOT NULL,
            `date_start` DATETIME DEFAULT NULL,
            `date_end` DATETIME DEFAULT NULL,
            `background_color` VARCHAR(7) NOT NULL DEFAULT '#6b6b6b',
            `text_color` VARCHAR(7) NOT NULL DEFAULT '#ffffff',
            `active` TINYINT(1) NOT NULL DEFAULT 1,
            `link` VARCHAR(255) DEFAULT NULL,
            `target_blank` TINYINT(1) NOT NULL DEFAULT 0,
            PRIMARY KEY (`id_kwk_topbar`)
        ) ENGINE=" . _MYSQL_ENGINE_ . " DEFAULT CHARSET=utf8;";

        return Db::getInstance()->execute($sql);
    }

    private function dropTable()
    {
        $sql = "DROP TABLE IF EXISTS `" . _DB_PREFIX_ . "kwk_topbar`;";
        return Db::getInstance()->execute($sql);
    }
    
    private function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminKwkTopbar';
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Top Bar';
        }
        $tab->id_parent = (int)Tab::getIdFromClassName('DEFAULT');
        $tab->module = $this->name;
        return $tab->add();
    }

    private function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminKwkTopbar');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

    public function getContent()
    {
        if (Tools::isSubmit('submitKwk_topbarModule')) {
            $this->postProcess();
            $this->clearCache();
            $this->context->controller->confirmations[] = $this->trans('Settings updated.', [], 'Modules.Kwk_topbar.Admin');
        }

        return $this->renderForm();
    }

    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitKwk_topbarModule';
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

    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->trans('Settings', [], 'Modules.Kwk_topbar.Admin'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->trans('Display Top Bar', [], 'Modules.Kwk_topbar.Admin'),
                        'name' => 'KWK_TOPBAR_IS_ACTIVE',
                        'is_bool' => true,
                        'desc' => $this->trans('Enable or disable the top bar on your website.', [], 'Modules.Kwk_topbar.Admin'),
                        'values' => [
                            ['id' => 'active_on', 'value' => true, 'label' => $this->trans('Enabled', [], 'Modules.Kwk_topbar.Admin')],
                            ['id' => 'active_off', 'value' => false, 'label' => $this->trans('Disabled', [], 'Modules.Kwk_topbar.Admin')],
                        ],
                    ],
                ],
                'submit' => [
                    'title' => $this->trans('Save', [], 'Admin.Actions'),
                ],
            ],
        ];
    }

    protected function getConfigFormValues()
    {
        return [
            'KWK_TOPBAR_IS_ACTIVE' => Configuration::get('KWK_TOPBAR_IS_ACTIVE', true),
        ];
    }

    protected function postProcess()
    {
        Configuration::updateValue('KWK_TOPBAR_IS_ACTIVE', Tools::getValue('KWK_TOPBAR_IS_ACTIVE'));
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('configure') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/topbar_back.js', 'all');
            $this->context->controller->addCSS($this->_path.'views/css/topbar_back.css');
        }
    }

    public function hookDisplayHeader()
    {
        if(!Configuration::get('KWK_TOPBAR_IS_ACTIVE')) {
            return;
        }
        $this->clearCache();
        $this->context->controller->addCSS($this->_path . 'views/css/topbar.css', 'all', 100);
        $this->context->controller->addJS($this->_path . 'views/js/topbar.js');

        return $this->display(__FILE__, 'views/templates/hook/display_header.tpl');
    }

    public function clearCache()
    {
        $this->_clearCache('display_header.tpl');
    }

}
