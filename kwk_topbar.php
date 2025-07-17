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

require_once __DIR__ . '/classes/KwkTopbar.php';

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
        return parent::install()
            && $this->createTable()
            && $this->installTab()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayBackOfficeHeader')
            && Configuration::updateValue('KWK_TOPBAR_ROTATION_INTERVAL', 5)
            && Configuration::updateValue('KWK_TOPBAR_DEFAULT_BG_COLOR', '#6b6b6b')
            && Configuration::updateValue('KWK_TOPBAR_DEFAULT_TEXT_COLOR', '#ffffff');
    }

    public function uninstall()
    {
        return parent::uninstall()
            && $this->dropTable()
            && $this->uninstallTab()
            && Configuration::deleteByName('KWK_TOPBAR_ROTATION_INTERVAL')
            && Configuration::deleteByName('KWK_TOPBAR_DEFAULT_BG_COLOR')
            && Configuration::deleteByName('KWK_TOPBAR_DEFAULT_TEXT_COLOR');
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
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminModules');
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
       if (Tools::isSubmit('submitKwkTopbarConfig')) {
            $this->postProcess();
            $this->context->controller->confirmations[] = 'Settings updated.';
        }

        $output = $this->renderForm();
        $output .= '<a href="' . $this->context->link->getAdminLink('AdminKwkTopbar') . '" class="btn btn-primary">Manage Top Bars</a>';
        return $output;
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
        $helper->submit_action = 'submitKwkTopbarConfig';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$this->getConfigForm()]);
    }

    protected function getConfigForm()
    {
        return [
            'form' => [
                'legend' => ['title' => 'General Settings', 'icon' => 'icon-cogs'],
                'input' => [
                    [
                        'type' => 'text',
                        'label' => 'Rotation Interval (seconds)',
                        'name' => 'KWK_TOPBAR_ROTATION_INTERVAL',
                        'desc' => 'Time between top bar messages (in seconds).',
                        'required' => true,
                    ],
                    [
                        'type' => 'color',
                        'label' => 'Default Background Color',
                        'name' => 'KWK_TOPBAR_DEFAULT_BG_COLOR',
                        'desc' => 'Default background color for top bars.',
                        'required' => true,
                    ],
                    [
                        'type' => 'color',
                        'label' => 'Default Text Color',
                        'name' => 'KWK_TOPBAR_DEFAULT_TEXT_COLOR',
                        'desc' => 'Default text color for top bars.',
                        'required' => true,
                    ],
                ],
                'submit' => ['title' => 'Save'],
            ],
        ];
    }

    protected function getConfigFormValues()
    {
        return [
            'KWK_TOPBAR_ROTATION_INTERVAL' => Configuration::get('KWK_TOPBAR_ROTATION_INTERVAL', 5),
            'KWK_TOPBAR_DEFAULT_BG_COLOR' => Configuration::get('KWK_TOPBAR_DEFAULT_BG_COLOR', '#6b6b6b'),
            'KWK_TOPBAR_DEFAULT_TEXT_COLOR' => Configuration::get('KWK_TOPBAR_DEFAULT_TEXT_COLOR', '#ffffff'),
        ];
    }

    protected function postProcess()
    {
        Configuration::updateValue('KWK_TOPBAR_ROTATION_INTERVAL', (int)Tools::getValue('KWK_TOPBAR_ROTATION_INTERVAL'));
        Configuration::updateValue('KWK_TOPBAR_DEFAULT_BG_COLOR', Tools::getValue('KWK_TOPBAR_DEFAULT_BG_COLOR'));
        Configuration::updateValue('KWK_TOPBAR_DEFAULT_TEXT_COLOR', Tools::getValue('KWK_TOPBAR_DEFAULT_TEXT_COLOR'));
    }

    public function hookDisplayBackOfficeHeader()
    {
        if (Tools::getValue('controller') === 'AdminKwkTopbar') {
            $this->context->controller->addJS($this->_path.'views/js/topbar_back.js', 'all');
            $this->context->controller->addCSS($this->_path.'views/css/topbar_back.css');
        }
    }

    public function hookDisplayHeader()
    {
        $topbars = KwkTopbar::getActiveTopbars();
        if (empty($topbars)) {
            return;
        }

        $this->context->controller->addCSS($this->_path . 'views/css/topbar.css', 'all', 100);
        $this->context->controller->addJS($this->_path . 'views/js/topbar.js');

        $this->context->smarty->assign([
            'topbars' => $topbars,
            'rotation_interval' => (int)Configuration::get('KWK_TOPBAR_ROTATION_INTERVAL', 5000),
            'default_bg_color' => Configuration::get('KWK_TOPBAR_DEFAULT_BG_COLOR', '#6b6b6b'),
            'default_text_color' => Configuration::get('KWK_TOPBAR_DEFAULT_TEXT_COLOR', '#ffffff'),
        ]);
        
        return $this->display(__FILE__, 'views/templates/hook/display_header.tpl');
    }

    public function clearCache()
    {
        $this->_clearCache('display_header.tpl');
    }

}
