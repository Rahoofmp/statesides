<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function index()
	{  
		$data['title'] = lang('dashboard'); 
		$user_id = log_user_id();
 		$data['pending_delivery_count'] = $this->Dashboard_model->getDeliveryCount( $user_id,'pending');
 		$data['sendto_delivery_count'] = $this->Dashboard_model->getDeliveryCount( $user_id,'send_to_delivery');
		$data['recent_projects'] = $this->Dashboard_model->getRecentProjectName();
		$data['details'] = $this->Dashboard_model->getRecentDeliveries('',$user_id);
		$data['count_delivery_notes']=$this->Dashboard_model->getTotalDeliveryNotesCount('',$user_id);
		$this->loadView($data);
	}

}
