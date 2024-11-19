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
		$data['driver_count'] = $this->Dashboard_model->getUserCount('driver');
		$data['staff_count'] = $this->Dashboard_model->getUserCount();
		$data['total_leads'] =$this->Dashboard_model->getCustomerinfo('lead');
		$data['customers'] =$this->Dashboard_model->getCustomerinfo('lead');

		// $data['admin']	=$this->dashboard_model->getUserCount('Customer');
		



		$data['storekeeper_count'] = $this->Dashboard_model->getUserCount('store_keeper');
		$data['project_count'] = $this->Dashboard_model->getTotalProjectCount();
		$data['job_order_count'] = $this->Dashboard_model->getTotalJobOrderCount();
		$data['pending_job_order'] = $this->Dashboard_model->getTotalJobOrderCount('','pending');
		$data['pending_delivery_count'] = $this->Dashboard_model->getDeliveryCount('','pending');
		// print_r($data['pending_delivery_count']);die();
		$data['sendto_delivery_count'] = $this->Dashboard_model->getDeliveryCount('','send_to_delivery');
 		
		// $data['recent_projects'] = $this->Dashboard_model->getRecentProjectName();
		$id=log_user_id();
		$data['details'] = $this->Dashboard_model->getRecentDeliveries();
		$data['count_delivery_notes']=$this->Dashboard_model->getTotalDeliveryNotesCount();
		$data['log_user_name'] = log_user_name();
		$this->loadView($data);
	}

}
