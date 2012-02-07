<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Andreas Schempp 2011
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 * @version    $Id: tl_iso_payment_modules.php 2354 2011-07-18 19:51:52Z aschempp $
 */


/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_iso_payment_modules']['palettes']['telecash'] = '{type_legend},name,label,type;{note_legend:hide},note;{config_legend},telecash_storeID,telecash_secretkey,new_order_status,trans_type,countries,shipping_modules,product_types;{gateway_legend},{expert_legend:hide},guests,protected;{enabled_legend},enabled';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['telecash_storeID'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['telecash_storeID'],
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>15, 'tl_class'=>'w50'),
);

$GLOBALS['TL_DCA']['tl_iso_payment_modules']['fields']['telecash_secretkey'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_iso_payment_modules']['telecash_secretkey'],
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'),
);

