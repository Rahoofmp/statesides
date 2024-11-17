<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Signature Currency Helpers
 *
 * @package		Signature MLM
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Signature Team
 * @link		www.signaturesoftwarelab.com
 */

// ------------------------------------------------------------------------

if ( ! function_exists('cur_format'))
{
	/**
	 * Currency
	 *
	 * Fetches a format of amount with currecny
	 *
	 * @param	string	$number		The amount
	 * @param	string	$currency 	currency code
	 * @param	array	$format		number format,
	 * @return	string
	 */
	function cur_format($number, $currency= NULL, $format = TRUE, $value = '')
	{
		// echo "aa".$number;die();
		return get_instance()->currency->format($number, $currency, $format, $value);
	}
}

if ( ! function_exists('cur_code'))
{
	/**
	 * Currency
	 *
	 * Fetches a default currency code
	 * @param	string	$code 		
	 */
	function cur_code()
	{
		 $currency_id = get_instance()->currency->getId();
		 return get_instance()->currency->getCode($currency_id);
	}
}

if ( ! function_exists('cur_symbol'))
{
	/**
	 * Currency
	 *
	 * Fetches a default currency symbol
	 * @param	string	$symbol 		
	 */
	function cur_symbol()
	{
		 $currency = get_instance()->currency->getDefaultCurrencyCode();
		 return get_instance()->currency->getSymbol($currency);
	}
}
