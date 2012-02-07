<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * TYPOlight Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
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
 * @copyright  Winans Creative 2009, Intelligent Spark 2010, iserv.ch GmbH 2010
 * @author     Fred Bliss <fred.bliss@intelligentspark.com>
 * @author     Andreas Schempp <andreas@schempp.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */


/**
 * Handle Paypal payments
 *
 * @extends Payment
 */
class PaymentTelecash extends IsotopePayment{
	
	protected $arrLanguages = array('en_us'=>'en_US', 'en_uk'=>'en_UK', 'fr'=>'fr_FR', 'de'=>'de_DE', 'it_IT'=>6);
	protected $arrCurrencies = array('AUD'=>036, 'CAD'=>124, 'DKK'=>208, 'HKD'=>344, 'ISK'=>352, 'JPY'=>392, 'MXN'=>484, 'NZD'=>554, 'NOK'=>578, 'SGD'=>702, 'ZAR'=>710, 'SEK'=>752, 'CHF'=>756, 'THB'=>764, 'GBP'=>826, 'USD'=>840, 'TRY'=>949, 'EUR'=>978, 'PLN'=>985);

	public function __get($strKey){
		switch( $strKey ){
			case 'available':
				if (!array_key_exists($this->Isotope->Config->currency, $this->arrCurrencies)){
					return false;
				}
				return parent::__get($strKey);

			default:
				return parent::__get($strKey);
		}
	}
	
	public function statusOptions(){
		return array('pending', 'processing', 'complete', 'on_hold');
	}
	
	public function processPayment(){
		$objOrder = $this->Database->prepare("SELECT * FROM tl_iso_orders WHERE cart_id=?")->limit(1)->executeUncached($this->Isotope->Cart->id);
		// Check orederid sent back by the gateway
		if ($this->Input->get('uid') == $objOrder->uniqid){
			return true;
		}
		global $objPage;
	}
	

	public function checkoutForm(){
		global $objPage;
		
		$objOrder = $this->Database->prepare("SELECT * FROM tl_iso_orders WHERE cart_id=?")->limit(1)->executeUncached($this->Isotope->Cart->id);
		$intTotal = $this->Isotope->Cart->grandTotal;
		return '
			<h2>' . $GLOBALS['TL_LANG']['MSC']['pay_with_redirect'][0] . '</h2>
			<p class="message">' . $GLOBALS['TL_LANG']['MSC']['pay_with_redirect'][1] . '</p>
			<form method="post" id="telecash_payment" action="https://test.ipg-online.com/connect/gateway/processing">
				<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'" />
				<input type="hidden" name="txntype" value="sale">
				<input type="hidden" name="mode" value="payonly"/>
				<input type="hidden" name="language" value="'.(array_key_exists($GLOBALS['TL_LANGUAGE'], $this->arrLanguages) ? $this->arrLanguages[$GLOBALS['TL_LANGUAGE']] : 'de_DE').'"/>  
				<input type="hidden" name="timezone" value="CET"/>
				<input type="hidden" name="txndatetime" value="'.$this->getDateTime().'"/> 
				<input type="hidden" name="hash" value="'.$this->createHash($intTotal,$this->arrCurrencies[$this->Isotope->Config->currency]).'"/> 
				<input type="hidden" name="storename" value="'.$this->telecash_storeID.'"/> 
				<input type="hidden" name="chargetotal" value="'.$intTotal.'"/> 
				<input type="hidden" name="currency" value="'.$this->arrCurrencies[$this->Isotope->Config->currency].'"/>
				<input type="hidden" name="responseSuccessURL" value="'.$this->Environment->base.$this->addToUrl('step=complete').'?uid='.$objOrder->uniqid.'"/>
				<input type="hidden" name="responseFailURL" value="'.$this->Environment->base.$this->addToUrl('step=failed').'?uid='.$objOrder->uniqid.'"/>
				<input type="hidden" name="transactionNotificationURL" value="'.$this->Environment->base.'system/modules/isotope/postsale.php?mod=pay&id='.$this->id.'"/>
				<input type="submit" class="process_submit" value="'.$GLOBALS['TL_LANG']['MSC']['pay_with_redirect'][2].'"> 
			</form>
			<script type="text/javascript">
				<!--//--><![CDATA[//><!--
				window.addEvent( \'domready\' , function() {
	  				$(\'telecash_payment\').submit();
				});
				//--><!]]>
			</script>';
	}
	
	protected function getDateTime(){
	$dateTime = date("Y:m:d-H:i:s");
	return $dateTime;
	}
	
	protected function createHash($chargetotal, $currency) { 
		$stringToHash = $this->telecash_storeID . $this->getDateTime() . $chargetotal . $currency . $this->telecash_secretkey;
		$ascii = bin2hex($stringToHash); 
		return sha1($ascii);
	}
	
	/*
protected function responseHash($approvalcode){
		$hash = $this->telecash_secretkey . $approvalcode . $chargetotal . $currency . $datetime . $this->telecash_storeID; 
		return sha1($hash);	
	}
*/
}

