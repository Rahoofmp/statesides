<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Currency {     
	
	public $currencies = array();
	public $currency_id = NULL;
	public $currency_code = NULL;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();

		$this->CI->db->select("*");
		$this->CI->db->where("status", 1);
		$query = $this->CI->db->get("currency");

		foreach ($query->result_array() as $result) {
			$this->currencies[$result['code']] = array(
				'currency_id'   => $result['currency_id'],
				'code' 			=> $result['code'],
				'title'         => $result['title'],
				'symbol_left'   => $result['symbol_left'],
				'symbol_right'  => $result['symbol_right'],
				'decimal_place' => $result['decimal_place'],
				'value'         => $result['value']
			);
		}
		$this->currency_id = $this->getId();
		$this->currency_code = $this->getCode($this->currency_id);
	}

	public function format($number, $currency= NULL, $format = true, $value = '') {
		if(!$currency){
			$currency = $this->currency_code;
		}

		if(!isset($this->currencies[$currency])){
			echo "currency not exist";
			die();
		}
		
		$symbol_left = $this->currencies[$currency]['symbol_left'];
		$symbol_right = $this->currencies[$currency]['symbol_right'];
		$decimal_place = $this->currencies[$currency]['decimal_place'];

		if (!$value) {
			$value = $this->currencies[$currency]['value'];
		}

		$amount = $value ? (float)$number * $value : (float)$number;
		
		$amount = round($amount, (int)$decimal_place);
		
		if (!$format) {
			return $amount;
		}

		$string = '';

		if($amount<0){
			$string = "-";
			$amount = $amount*(-1);
		}

		if ($symbol_left) {
			$string .= $symbol_left;
		}

		$string .= number_format($amount, (int)$decimal_place, lang('decimal_point'), lang('thousand_point'));

		if ($symbol_right) {
			$string .= $symbol_right;
		}

		return $string;	
	}

	public function convert($value, $from, $to) {
		if (isset($this->currencies[$from])) {
			$from = $this->currencies[$from]['value'];
		} else {
			$from = 1;
		}

		if (isset($this->currencies[$to])) {
			$to = $this->currencies[$to]['value'];
		} else {
			$to = 1;
		}

		return $value * ($to / $from);
	}
	public function getId($currency = NULL) {
		if(!$currency)
		{
			$currency = $this->getDefaultCurrencyCode();
		}
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['currency_id'];
		} else {
			return 0;
		}
	}

	public function getCode($currency_id) {
		$currencies = array_column($this->currencies, "code", "currency_id");
		return $currencies[$currency_id];
	}


	public function getSymbolLeft($currency = NULL) {
		if(!$currency)
		{
			$currency = $this->getDefaultCurrencyCode();
		}
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_left'];
		} else {
			return '';
		}
	}

	public function getSymbol($currency = NULL) {
		if(!$currency)
		{
			$currency = $this->getDefaultCurrencyCode();
		}
		if (isset($this->currencies[$currency])) {
			return ($this->currencies[$currency]['symbol_left']) ? $this->currencies[$currency]['symbol_left'] :  $this->currencies[$currency]['symbol_right'] ;
		} else {
			return '';
		}
	}

	public function getSymbolRight($currency = NULL) {
		if(!$currency)
		{
			$currency = $this->getDefaultCurrencyCode();
		}
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['symbol_right'];
		} else {
			return '';
		}
	}

	public function getDecimalPlace($currency = NULL) {
		if(!$currency)
		{
			$currency = $this->getDefaultCurrencyCode();
		}
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['decimal_place'];
		} else {
			return 0;
		}
	}


	public function getValue($currency = NULL) {
		if(!$currency)
		{
			$currency = $this->getDefaultCurrencyCode();
		}
		if (isset($this->currencies[$currency])) {
			return $this->currencies[$currency]['value'];
		} else {
			return 0;
		}
	}
	
	public function getDefaultCurrencyCode() {
		$this->CI->db->select("code");
		$this->CI->db->where("status", 1);
		$query = $this->CI->db->get("currency");

		foreach ($query->result_array() as $result) {
			return $result["code"];
		}

	}

	public function has($currency) {
		return isset($this->currencies[$currency]);
	}

}