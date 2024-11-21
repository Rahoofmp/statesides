<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function index()
	{  
		$data['title'] = lang('dashboard'); 
		$this->load->model('Member_model');
		$user_id = log_user_id();
		$customers=0;
		$leads=0;
		$details=0;



		$date=date('Y-m-d');
		$today_reminder= $this->Member_model->getTodayreminders($user_id,$date);

		if ($today_reminder) {
			
			$data['today_reminder']=$today_reminder['message'];
			$data['reminder_id']=$today_reminder['id'];
		}

		$details = $this->Dashboard_model->getEnquiryDetails();
		
		$total_count = $this->Dashboard_model->getAllEnquiryDetailsCount();
		if ($details) {
			foreach ($details as $key => $value) {

				if ($key=='enquiry_status') {

					if ($value=='lead') {
						$leads+=1;
					}
					else{
						$customers+=1;
					}
				}
			}
		}
		



		$data['details']=$details;
		$data['enquires']=$total_count;
		$data['leads']=$leads;
		$data['customers']=$customers;

		
		// print_r($data);
		// die();
		// $data['recent_projects'] = $this->Dashboard_model->getRecentProjectName();
		$id=log_user_id();
		
		$data['log_user_name'] = log_user_name();
		$this->loadView($data);
	}

}
