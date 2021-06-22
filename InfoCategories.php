<?php

/**
 * 2007-2021 PrestaShop
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
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class InfoCategories extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'InfoCategories';
        $this->tab = 'content_management';
        $this->version = '1.0.0';
        $this->author = 'Tecinet'; // Javier Florido email: jflorido94@hotmail.com
        $this->need_instance = 1;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Info Categories');
        $this->description = $this->l('Module to show a HTML block in the header of the categories that you choose');

        $this->confirmUninstall = $this->l('¿Seguro que quieres desinstalar este modulo?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('INFOCATEGORIES_LIVE_MODE', false);

        include(dirname(__FILE__) . '/sql/install.php');

        return parent::install() &&
            $this->registerHook('displayCategoryHeader');
    }

    public function uninstall()
    {
        include(dirname(__FILE__) . '/sql/uninstall.php');

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
        if (((bool)Tools::isSubmit('submitInfoCategoriesModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        //$output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return /*$output .*/ $this->renderForm();
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
        $helper->submit_action = 'submitInfoCategoriesModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
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
        return
            [
                'form' =>
                [
                    'tinymce' => true,
                    'legend' =>
                    [
                        'title' => $this->l('Settings'),
                        'icon' => 'icon-gears',
                    ],
                    'input' =>
                    [
                        [
                            'type' => 'categories',
                            'label' => $this->l('Categories'),
                            'name' => 'TCNINFOCATEGORY_categories',
                            'desc' => $this->l('Select the category where you want show the message'),
                            'tree' =>
                            [
                                'root_category' => 0,
                                'use_checkbox' => 1,
                                'id' => 'id_category',
                                'name' => 'name_category',
                                'selected_categories' => explode(',', $this->getTextInfoData()['categories']),
                            ]

                        ],
                        [
                            'type' => 'textarea',
                            'label' => $this->l('Message'),
                            'name' => 'TCNINFOCATEGORY_message',
                            'desc' => $this->l('Enter the message that you want show'),
                            'autoload_rte' => true,
                        ],
                        [
                            'type' => 'switch',
                            'label' => $this->l('Show on Mobile'),
                            'name' => 'TCNINFOCATEGORY_mobile',
                            'is_bool' => true,
                            'desc' => $this->l('Show message in mobile devices'),
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
                        ],
                    ],
                    'submit' =>
                    [
                        'title' => $this->l('Save'),
                    ],
                ],
            ];
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'TCNINFOCATEGORY_categories' => Tools::getValue('TCNINFOCATEGORY_categories', explode(',', $this->getTextInfoData()['categories'])),
            'TCNINFOCATEGORY_message' => Tools::getValue('TCNINFOCATEGORY_message', $this->getTextInfoData()['message']),
            'TCNINFOCATEGORY_mobile' => Tools::getValue('TCNINFOCATEGORY_mobile', $this->getTextInfoData()['mobile']),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        if (Tools::isSubmit('submitInfoCategoriesModule')) {
            $form_values = $this->getConfigFormValues();

            if (Db::getInstance()->getValue('SELECT count(*) FROM `ps_TCNInfoCategory`') == 0) {

                Db::getInstance()->insert('TCNInfoCategory', array(
                    'id_TCNInfoCategory' => 1,
                    'categories'     => pSQL(implode(',', $form_values['TCNINFOCATEGORY_categories'])),
                    'message'        => $form_values['TCNINFOCATEGORY_message'],
                    'mobile'         => $form_values['TCNINFOCATEGORY_mobile'],
                ));
            } else {
                Db::getInstance()->update('TCNInfoCategory', array(
                    'categories'  => pSQL(implode(',', $form_values['TCNINFOCATEGORY_categories'])),
                    'message'     => $form_values['TCNINFOCATEGORY_message'],
                    'mobile'      => $form_values['TCNINFOCATEGORY_mobile'],
                ), 'id_TCNInfoCategory = 1');
            }
        }
    }

    protected function getTextInfoData()
    {
        return $data = Db::getInstance()->getRow('SELECT * FROM `ps_TCNInfoCategory`');
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path . 'views/js/back.js');
            $this->context->controller->addCSS($this->_path . 'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path . '/views/js/front.js');
        $this->context->controller->addCSS($this->_path . '/views/css/front.css');
    }

    /**
     * 
     */
    public function hookDisplayCategoryHeader()
    {
        $aux = $this->getTextInfoData();
        $data = [
            "categories" => explode(',', $aux["categories"]),
            "message" => $aux["message"],
            "mobile"  => $aux["mobile"],
        ];
        $this->context->smarty->assign([
            'data' => $data,
        ]);
        return $this->display(__FILE__, 'category.tpl');
    }
}
