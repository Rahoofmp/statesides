<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends Base_Controller 
{

	function __construct() {
		parent::__construct(); 
		
	}
	public function index()
	{
		$data['title'] = 'Home';
		$this->loadView($data);
	}
	public function terms_and_conditions()
	{
		$data['title'] = 'terms and conditions';
		$this->loadView($data);
	}

}

