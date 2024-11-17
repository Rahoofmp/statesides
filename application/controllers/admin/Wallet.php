<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function summary()
	{  
		$data['title'] = lang('wallet_summary'); 
		$this->loadView($data);
	}

	function transaction_history()
	{  
		$data['title'] = lang('transaction_history'); 
		$this->loadView($data);
	}

	function withdraw_request()
	{  
		$data['title'] = lang('withdraw_request'); 
		$this->loadView($data);
	}

	function fund_transfer()
	{  
		$data['title'] = lang('fund_transfer'); 
		$this->loadView($data);
	}

	function credit_debit()
	{  
		$data['title'] = lang('credit_debit'); 
		$this->loadView($data);
	}


	function withdrawal_history()
	{

		$data['title'] = lang('text_withdraw_history');
		$payout_details = $this->Wallet_model->getPayoutDetails("",$limit = '', $page = 0);
		$count_payout_details = count($payout_details);

		$data['payout_details'] = $payout_details;
		$data['count_payout_details'] = $count_payout_details;
		$this->loadView($data);
	}

}
