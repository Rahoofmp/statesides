<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	// function add_project( $edit_id ='' , $action = '')
	// {   

	// 	$edit_details = array();
	// 	if($action == 'edit'){

	// 		$edit_details = $this->Project_model->geteditDetails($edit_id);
	// 	}

	// 	$log_user_id = log_user_id();

	// 	if ($this->input->post('add_market') && $this->validate_add_market()) {

	// 		$post_arr = $this->input->post();

	// 		$market_file_name = NULL;

	// 		if($_FILES['userfile']['error']!=4)
	// 		{                
	// 			$config['upload_path'] = './assets/uploads/Market/';
	// 			$config['allowed_types'] = 'gif|jpg|png|jpeg';
	// 			$config['max_size'] = '2000000000';
	// 			$config['remove_spaces'] = true;
	// 			$config['overwrite'] = false;

	// 			$this->load->library('upload', $config);
	// 			$msg = '';
	// 			if (!$this->upload->do_upload()) { 
	// 				$msg = $this->upload->display_errors();
	// 				$this->redirect($msg, 'market/add_market', FALSE);
	// 			} else { 
	// 				$image =  $this->upload->data() ;  
	// 				$market_file_name = $image['file_name'];
	// 			}
	// 		}
	// 		if($market_file_name == "")
	// 		{
	// 			$msg = "Error on upload image";
	// 			$this->redirect($msg, 'market/add_market', FALSE);
	// 		}
	// 		$result = $this->Project_model->insertMarketDetails($post_arr,$market_file_name);

	// 		if ($result) {      
	// 			$msg = 'Data added successfully.';
	// 			$this->redirect($msg, 'market/add_market', TRUE);
	// 		} else {
	// 			$msg = 'Data added failed.';
	// 			$this->redirect($msg, 'market/add_market', FALSE);
	// 		}
	// 	}


	// 	if ($this->input->post('update_market') && $this->validate_add_market()) {

	// 		$post_arr = $this->input->post();

	// 		$market_file_name = NULL;

	// 		if($_FILES['userfile']['error']!=4)
	// 		{                
	// 			$config['upload_path'] = './assets/uploads/Market/';
	// 			$config['allowed_types'] = 'gif|jpg|png|jpeg';
	// 			$config['max_size'] = '2000000000';
	// 			$config['remove_spaces'] = true;
	// 			$config['overwrite'] = false;

	// 			$this->load->library('upload', $config);
	// 			$msg = '';
	// 			if (!$this->upload->do_upload()) { 
	// 				$msg = $this->upload->display_errors();
	// 				$this->redirect($msg, 'market/add_market', FALSE);
	// 			} else { 
	// 				$image =  $this->upload->data() ;  
	// 				$market_file_name = $image['file_name'];
	// 			}
	// 		}

	// 		$result = $this->Project_model->updateMarketDetails($post_arr,$market_file_name , $edit_id);

	// 		if ($result) {      
	// 			$msg = "Course updated successfully";
	// 			$this->redirect($msg, "market/add_market/$edit_id", TRUE);
	// 		} else {
	// 			$msg = 'Course updated failed';
	// 			$this->redirect($msg, "market/add_market/$edit_id", FALSE);
	// 		}
	// 	}

	// 	$data['edit_id'] = $edit_id;
	// 	$data['edit_details'] = $edit_details;
	// 	$data['title'] = 'Add Market';

	// 	$data['market_details'] = $this->Project_model->getMarketDetails();

	// 	$this->loadView($data);
	// }
	function add_project($enc_id='')
	{   	

		if ($this->input->post('add_project') && $this->validate_add_project()) {
			$post_arr = $this->input->post();
			$post_arr['user_id'] = log_user_id();
			$data['result'] = $this->Project_model->insertProjectDetails($post_arr);
			if ($data['result']) {      
				$msg = 'Data added successfully.';
				$this->redirect($msg, 'project/add-project', TRUE);
			} else {
				$msg = 'Data added failed.';
				$this->redirect($msg, 'project/add-project', FALSE);
			}
		}
		$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		if ($this->input->post('update_project') && $this->validate_add_project()) {
			$post_arr = $this->input->post();
			$post_arr['id'] = $id;
			$update = $this->Project_model->updateProjectDetails($post_arr);
			if ($update) {      
				$msg = 'Upadated successfully.';
				$this->redirect($msg, 'project/project-list', TRUE);
			} else {
				$msg = 'Updation failed.';
				$this->redirect($msg, 'project/add-project/'.$id, FALSE);
			}
		}
		// if($this->input->post('search'))
		// {


		// }
		$post_arr['project_id']=$id;
		$data['id'] = $id;
		$data['customer_name'] = $this->Project_model->getCustomerName();
		$data['project'] = $this->Project_model->getProjectDetails($post_arr);
		$data['project']  = element(0,$data['project']);
		$data['title'] = 'Add Project';
		$this->loadView($data);
	}

	function project_details( $edit_id ='' , $action = '')
	{   
		
		// $edit_details = array();
		// if($action == 'edit'){

		// 	$edit_details = $this->Project_model->geteditDetails($edit_id);
		// }

		// $log_user_id = log_user_id();
		
		// if ($this->input->post('add_market') && $this->validate_add_market()) {

		// 	$post_arr = $this->input->post();

		// 	$market_file_name = NULL;

		// 	if($_FILES['userfile']['error']!=4)
		// 	{                
		// 		$config['upload_path'] = './assets/uploads/Market/';
		// 		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		// 		$config['max_size'] = '2000000000';
		// 		$config['remove_spaces'] = true;
		// 		$config['overwrite'] = false;

		// 		$this->load->library('upload', $config);
		// 		$msg = '';
		// 		if (!$this->upload->do_upload()) { 
		// 			$msg = $this->upload->display_errors();
		// 			$this->redirect($msg, 'market/add_market', FALSE);
		// 		} else { 
		// 			$image =  $this->upload->data() ;  
		// 			$market_file_name = $image['file_name'];
		// 		}
		// 	}
		// 	if($market_file_name == "")
		// 	{
		// 		$msg = "Error on upload image";
		// 		$this->redirect($msg, 'market/add_market', FALSE);
		// 	}
		// 	$result = $this->Project_model->insertMarketDetails($post_arr,$market_file_name);

		// 	if ($result) {      
		// 		$msg = 'Data added successfully.';
		// 		$this->redirect($msg, 'market/add_market', TRUE);
		// 	} else {
		// 		$msg = 'Data added failed.';
		// 		$this->redirect($msg, 'market/add_market', FALSE);
		// 	}
		// }


		// if ($this->input->post('update_market') && $this->validate_add_market()) {

		// 	$post_arr = $this->input->post();

		// 	$market_file_name = NULL;

		// 	if($_FILES['userfile']['error']!=4)
		// 	{                
		// 		$config['upload_path'] = './assets/uploads/Market/';
		// 		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		// 		$config['max_size'] = '2000000000';
		// 		$config['remove_spaces'] = true;
		// 		$config['overwrite'] = false;

		// 		$this->load->library('upload', $config);
		// 		$msg = '';
		// 		if (!$this->upload->do_upload()) { 
		// 			$msg = $this->upload->display_errors();
		// 			$this->redirect($msg, 'market/add_market', FALSE);
		// 		} else { 
		// 			$image =  $this->upload->data() ;  
		// 			$market_file_name = $image['file_name'];
		// 		}
		// 	}

		// 	$result = $this->Project_model->updateMarketDetails($post_arr,$market_file_name , $edit_id);

		// 	if ($result) {      
		// 		$msg = "Course updated successfully";
		// 		$this->redirect($msg, "market/add_market/$edit_id", TRUE);
		// 	} else {
		// 		$msg = 'Course updated failed';
		// 		$this->redirect($msg, "market/add_market/$edit_id", FALSE);
		// 	}
		// }

		// $data['edit_id'] = $edit_id;
		// $data['edit_details'] = $edit_details;
		$data['title'] = 'Add Market';
		
		// $data['market_details'] = $this->Project_model->getMarketDetails();

		$this->loadView($data);
	}

	function validate_add_market() 
	{
		
		$this->form_validation->set_rules('pair', 'Pair', 'trim|required');
		$this->form_validation->set_rules('percentual', 'Percentual', 'trim|required');
		$this->form_validation->set_rules('bot', 'Bot', 'trim|required');
		$this->form_validation->set_rules('date', 'Date', 'trim|required');
		$this->form_validation->set_rules('sort_order', 'Sort Order', 'trim|required');

		$result =  $this->form_validation->run();

		return $result;
	}
	function validate_add_project() 
	{
		
		$this->form_validation->set_rules('project_name', 'Project Name', 'trim|required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('location', 'Location', 'trim|required');
		$this->form_validation->set_rules('estimated_cost', 'Estimated Cost', 'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('estimated_value', 'Estimated Value', 'trim|required|numeric|greater_than[0]');
		$this->form_validation->set_rules('estimated_duration', 'Estimated Duration', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|required');

		$result =  $this->form_validation->run();
		return $result;
	}

	function delete_market() 
	{
		
		$result = 0;
		$market_id = $this->input->post('market_id');
		if($market_id)
		{
			$result = $this->Project_model->deleteMarket($market_id);
			$this->Base_model->insertIntoActivityHistory(16, 16, "market_id deleted--$market_id");                       
		}
		echo $result;
	}

	function activate_market() 
	{
		$result = 0;
		$market_id = $this->input->post('market_id');
		if($market_id)
		{

			$result = $this->Project_model->activatemarket($market_id);
			$this->Base_model->insertIntoActivityHistory($log_user_id, $log_user_id, "market activated--$market_id");                       
		}
		echo $result;
	}

	function project_list()
	{
		$data['title']="Project List";

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']); 
			if(element('customer_name',$post_arr))
				$post_arr['cus_name'] = $this->Project_model->getCustomerName($post_arr['customer_name']); 

		}else{
			$post_arr['limit'] = 10; 
		}


		$data['project'] = $this->Project_model->getProjectDetails($post_arr);
		$data['post_arr'] = $post_arr;

		$this->loadView($data);
	}

	function changeStatus()
	{

		$post_arr = $this->input->post();
		$status=$post_arr['status'];
		$id=$post_arr['id'];
		if($post_arr){   
			$result = $this->Project_model->updateStatus($id,$status);
			
			echo $result;

			
		}
	}

	public function index()
	{
		$data['title'] = 'Dashboard';
		$post_arr = [];
		if($this->input->post('search'))
		{
			$post_arr = $this->input->post();
			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']); 
			$data['project_details'] = $this->Project_model->getProjectDetails($post_arr);
			if(element('project_id',$post_arr)){
				$data['project_jobs'] = $this->Project_model->getProjectJobs($post_arr['project_id']);
				$data['project_count']=count($data['project_jobs']);
				$data['total_est_time'] = array_sum(array_column($data['project_jobs'],'estimated_working_hrs'));
				$data['total_spent_time'] = array_sum(array_column($data['project_jobs'],'actual_workin_hrs'));
				$data['total_time_difference']=$data['total_spent_time']-$data['total_est_time'];

				$this->load->model(['Material_model', 'Delivery_model', 'Packages_model']);
				$search_arr = [
					'project_id' => $post_arr['project_id'],
					'items' => true,
					'start' => 0,
					'length' => 100000,
				];

				$data['inventory'] = $this->Material_model->getInventoryAjax( $search_arr);

 				$data['delivery'] = $this->Delivery_model->getDeliveryAjax($search_arr);	
 
 				$data['packages'] = $this->Packages_model->getPackageDetailAjax( $search_arr);
 


			}
			
		}
		$data['post_arr'] = $post_arr;
		$this->loadView($data);
	}

	public function get_delivery_project_dashboard() {
	
		if ($this->input->is_ajax_request()) {

			$this->load->model('Delivery_model');
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();


			$count_without_filter = $this->Delivery_model->getDeliveryCount();
			$count_with_filter = $this->Delivery_model->getDeliveryAjax($post_arr,1);
			$result_data = $this->Delivery_model->getDeliveryAjax($post_arr);
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
		}
	}


	public function get_package_list_dashboard() {
		
		if ($this->input->is_ajax_request()) {
			$this->load->model('Packages_model');

			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
            $post_arr['items']=true;

			$count_without_filter = $this->Packages_model->getPackageCount();
			$count_with_filter = $this->Packages_model->getPackageDetailAjax($post_arr, 1);
			$details = $this->Packages_model->getPackageDetailAjax( $post_arr,'');
			// print_r($count_with_filter);die();
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $details,
			);

			echo json_encode($response);
		} 
	}

}






