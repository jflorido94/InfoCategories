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
$sql = array();


// ---- Creacion de la tabla para los textos en las categorias
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'TCNInfoCategory` (
    `id_TCNInfoCategory` int(11) NOT NULL AUTO_INCREMENT,
    `categories` varchar(255) NOT NULL,
    `message` text NOT NULL,
    `mobile` boolean NOT NULL,
    PRIMARY KEY  (`id_TCNInfoCategory`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';


// ejecuta todos as querys contenidas en el array $sql
// devuelve falso si alguna falla
foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
