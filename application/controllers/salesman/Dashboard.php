<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function index()
	{  
		$data['title'] = lang('dashboard'); 
		

		// $data['recent_projects'] = $this->Dashboard_model->getRecentProjectName();
		$id=log_user_id();
		
		$data['log_user_name'] = log_user_name();
		$this->loadView($data);
	}

}
