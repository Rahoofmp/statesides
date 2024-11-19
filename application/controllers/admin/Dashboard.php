<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function index()
	{  
		$data['title'] = lang('dashboard'); 
		$id=log_user_id();
		
		// $data['recent_projects'] = $this->Dashboard_model->getRecentProjectName();
	
		
		
		$data['log_user_name'] = log_user_name();
		$this->loadView($data);
	}

}
