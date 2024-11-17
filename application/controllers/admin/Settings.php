<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}


	function residual_bonus()
	{  
		$data['title'] = 'Level Bonus'; 

		$residual_details = $this->Settings_model->getResidualBonusDetails();
		$level  = 20;
		if($this->input->post('update_residual')){

			$details = $this->input->post();
			
			$result = $this->Settings_model->updateResidualBonus($level,$details);

			if($result){
				$msg = "Level Bonus updated successfully..";
				$this->redirect($msg, "settings/residual-bonus", TRUE);
			}
			else{
				$msg = "Error On Level Bonus updation..";
				$this->redirect($msg, "settings/residual-bonus", FALSE);
			}
		}

		$data['residual_details'] = $residual_details;
		$this->loadView($data);
	}


	function binary_bonus()
	{  
		$data['title'] = 'Binary Bonus'; 

		$binary_value = $this->Settings_model->getBinaryValue();

		if($this->input->post('update_residual')){

			$details = $this->input->post();
			
			$result = $this->Settings_model->updateResidualBonus($level,$details);

			if($result){
				$msg = "Level Bonus updated successfully..";
				$this->redirect($msg, "settings/residual-bonus", TRUE);
			}
			else{
				$msg = "Error On Level Bonus updation..";
				$this->redirect($msg, "settings/residual-bonus", FALSE);
			}
		}

		$data['residual_details'] = $residual_details;
		$this->loadView($data);
	}


	function transfer()
	{  
		$data['title'] = lang('transfer'); 
		$this->loadView($data);
	}

	function payout()
	{  
		$data['title'] = lang('payout'); 
		$this->loadView($data);
	}


	function website_profile()
	{       
		$site_info = $this->Settings_model->getCompanyInformation();
		if ($this->input->post('update') && $this->validate_website_profile()) {
			$site_info = $this->input->post();  

			$update_company_info = $this->Settings_model->updateWebisteInfo($site_info);

			if ( $update_company_info ) { 
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'update_company_settings', serialize($site_info) );
				$msg = lang('success_site_updated');
				$this->redirect($msg, "settings/website-profile", TRUE);

			} else {
				$msg = lang('error_on_site_updation');
				$this->redirect($msg, "settings/website-profile", FALSE);
			}
		}

		$data['site_info'] = $site_info;

		$data['title'] = lang('website_profile'); 
		$this->loadView($data);
	}

	protected function validate_website_profile() {
		$this->form_validation->set_rules('website_name', lang('website_name'), 'trim|required');
		$this->form_validation->set_rules('address', lang('address'), 'trim|required');
		$this->form_validation->set_rules('email', lang('email'), 'trim|required|valid_email');
		$this->form_validation->set_rules('phone', lang('phone'), 'trim|required'); 
		$validation_result =  $this->form_validation->run();
		return $validation_result;
	}

	// public function packages($rank_id="")
	// {   
	// 	$rank_id_details = $this->input->post();


	// 	if($rank_id)
	// 	{
	// 		$rank_id_details = $this->Settings_model->getRankDetails($rank_id);
	// 		$this->set('rank_id_details', $rank_id_details[0]);
	// 		$this->set('rank_id', $rank_id);
	// 	}

	// 	$data['title'] = lang('rank_settings');
	// 	$data['ranks'] = $this->Settings_model->getRankDetails(); 
	// 	$this->loadView($data);
	// }


	public function ranks($rank_id="")
	{   
		$rank_id_details = $this->input->post();


		if($rank_id)
		{
			$rank_id_details = $this->Settings_model->getRewardDetails($rank_id);
			$this->set('rank_id_details', $rank_id_details[0]);
			$this->set('rank_id', $rank_id);
		}

		$data['title'] = lang('rank_settings');
		$data['ranks'] = $this->Settings_model->getRewardDetails(); 
		$this->loadView($data);
	}

	protected function validate_package_settings() {
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('value', 'Value', 'trim|required|numeric');

		$this->form_validation->set_rules('ceiling_value', 'Ceiling', 'trim|required|numeric');
		$this->form_validation->set_rules('total_return', 'Total return', 'trim|required|numeric');

		$result =  $this->form_validation->run();
		return $result;

	}

	public function rank_form( $rank_enc_id = '' ) {
		$title = lang('add_rank');
		
		$rank = [];

		if( $rank_enc_id ){

			$title = lang('edit_rank');
			$rank_id = $this->Base_model->encrypt_decrypt( 'decrypt', $rank_enc_id );

			$rank = $this->Settings_model->getRewardDetails($rank_id);
			
			if( empty($rank) ){
				$msg = lang('rank_not_exist');
				$this->redirect($msg, 'settings/ranks', TRUE);
			}	
		}

		if ( $this->input->post('rank') == 'insert' ) {
			$rank = $this->input->post();
			if (  $this->validate_rank_settings('insert')) {


				$result = $this->Settings_model->insertNewRank($rank);

				if ($result) {                
					$msg = lang('rank_added_successfully');
					$this->redirect($msg, 'settings/ranks', TRUE);
				} else {
					$msg = lang('error_on_rank_adding');
					$this->redirect($msg, 'settings/rank_form', FALSE);
				}
			}
		}
		else if ($this->input->post('rank') == 'update'  ) {
			
			$rank = $this->input->post();

			$result = $this->Settings_model->updateReward($rank, $rank_id);

			if ($result) {                
				$msg = lang('rank_updated_successfully');
				$this->redirect($msg, 'settings/ranks', TRUE);
			} else {
				$msg = lang('error_on_rank_updation');
				$this->redirect($msg, 'settings/rank/'.$rank_enc_id, FALSE);
			}
		}

		$status_arr = [
			'0' => lang('inactive'),
			'1' => lang('active')
		];

		$data['status_arr'] = $status_arr; 

		$data['rank_enc_id'] = $rank_enc_id; 

		$data['rank'] = $rank; 
		$data['title'] = $title; 
		$this->loadView($data);
	} 
	
	function training()
	{   
		$log_user_id = log_user_id();
		
		if ($this->input->post('efile_upload') && $this->validate_upload_efiles()) {

			$post_arr = $this->input->post();
			$doc_title = $post_arr["doc_title"];
			$doc_desc = $post_arr["doc_desc"];
			$sort_order = $post_arr["sort_order"];
			$doc_file_name = NULL;

			if($_FILES['userfile']['error']!=4)
			{                
				$config['upload_path'] = './assets/uploads/Training_tools/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|ppt|xls|xlsx|doc|docx';
				$config['max_size'] = '2000000000';
				$config['remove_spaces'] = true;
				$config['overwrite'] = false;

				$this->load->library('upload', $config);
				$msg = '';
				if (!$this->upload->do_upload()) { 
					$msg = $this->upload->display_errors();
					$this->redirect($msg, 'settings/training', FALSE);
				} else { 
					$image =  $this->upload->data() ;  
					$doc_file_name = $image['file_name'];
				}
			}
			if($doc_file_name == "")
			{
				$msg = lang('text_error_on_upload_efile');
				$this->redirect($msg, 'settings/training', FALSE);
			}
			$result = $this->Settings_model->insertEfiles($doc_title,$doc_desc,$doc_file_name,$sort_order);

			if ($result) {      
				$msg = lang('text_efile_uploaded_successfully');
				$this->redirect($msg, 'settings/training', TRUE);
			} else {
				$msg = lang('text_error_on_upload_efile');
				$this->redirect($msg, 'settings/training', FALSE);
			}
		}

		$data['title'] = lang('text_upload_efiles');
		
		$data['efile_details'] = $this->Settings_model->getEfileDetails();
		$this->loadView($data);
	}


	function auto_trade()
	{   
		
		if ($this->input->post('add_trade') && $this->validate_auto_trade()) {
			$post_arr = $this->input->post();
			$title = $post_arr["heading"];
			$description = $post_arr["description"];

			$result = $this->Settings_model->updateAutoTrade($title , $description);

			if ($result) {      
				$msg = lang('auto_trade_updated_successfully');
				$this->redirect($msg, 'settings/auto_trade', TRUE);
			} else {
				$msg = lang('auto_trade_updated_failed');
				$this->redirect($msg, 'settings/auto_trade', FALSE);
			}
		}

		$data['title'] = lang('auto_trade');
		
		$data['trade_details'] = $this->Settings_model->getAutoTrade();
		$this->loadView($data);
	}

	function validate_upload_efiles() 
	{
		
		$this->form_validation->set_rules('doc_title', lang('training_file_title'), 'trim|required');
		$this->form_validation->set_rules('doc_desc', lang('description'), 'trim|required');
		$this->form_validation->set_rules('sort_order', lang('text_sort_order'), 'trim|required|numeric|greater_than[0]');
		
		$result =  $this->form_validation->run();

		return $result;
	}

	function validate_auto_trade() 
	{
		
		$this->form_validation->set_rules('heading', lang('heading'), 'trim|required');
		$this->form_validation->set_rules('description', lang('description'), 'trim|required');
		$result =  $this->form_validation->run();

		return $result;
	}

	function delete_efiles() 
	{
		$result = 0;
		$efile_id = $this->input->post('efile_id');
		if($efile_id)
		{

			$result = $this->Settings_model->deleteEfile($efile_id);
			$this->Base_model->insertIntoActivityHistory($log_user_id, $log_user_id, 'training tool deleted');                       
		}
		echo $result;
	}

	//active inactive

	function activate_user()
	{  

		$account_details = array(); 
		$user_id = log_user_id();
		$user_name = $this->Base_model->getUserName($user_id);
		$this->load->model('Business_model');
		if($this->input->post('activate_user') )
		{ 
			$this->form_validation->set_rules('user_name', lang('user_name'), 'trim|required');
			if( $this->form_validation->run() )
			{ 
				$post_arr = $this->input->post();
				$user_id = $this->Base_model->getUserId($post_arr['user_name']);
				$status = $this->Base_model->getUserStatus($user_id);

				if($status == '1'){
					$msg = lang('user_alraedy_active');
					$this->redirect($msg, "settings/activate_user", FALSE);
				}
				else{

					$result = $this->Settings_model->updateActivateDate($user_id);

					if($result){

						$msg = lang('user_activate_successfully');
						$this->redirect($msg, "settings/activate_user", TRUE);

					}
					else{
						$msg = lang('user_activation_failed');
						$this->redirect($msg, "settings/activate_user", FALSE);
					}


				}
			} 
		} 

		$data['title'] = lang('activate_user'); 

		$this->loadView($data);
	}
	function pin_allocation()
	{
		$packages = $this->Settings_model->getPackageUpgrade();
		$data['packages'] = $packages;
		if($this->input->post())
		{
			$post_arr=$this->input->post();
			$count = $post_arr['count'];
			$user_id = $this->Base_model->getUserId($post_arr['user_name']);
			if(!$user_id)
			{
				$msg = 'Invalid user id';
				$this->redirect($msg, "settings/pin-allocation", FALSE);
			}
			for ($i=0; $i < $count; $i++) { 
				# code...
				$random_string=$this->Base_model->getRandomStringEpin(10,'pin_allocation','random_string');
				$result=$this->Settings_model->addPinAllocation($post_arr,$random_string);
			}
			
			if($result)
			{
				$msg = 'ePIN allocated successfully';
				$this->redirect($msg, "settings/pin-allocation", TRUE);
			}
			else
			{
				$msg = 'error on creating ePIN...!';
				$this->redirect($msg, "settings/pin-allocation", FALSE);
			}
			
		}

		
		$data['title'] = 'ePIN ALLOCATION'; 
		$this->loadView($data);
	}
	
	function user_epin()
	{
		$data['title'] = 'USER ePIN'; 
		if ($this->input->post('submit') == 'search')
		{
			$user_id=NULL;
			$post_arr = $this->input->post();

			if($post_arr['user_name'])
			{
				$user_name = $post_arr['user_name'];

				$user_id = $this->Base_model->getUserId($user_name);
				$data['user_name']=$user_name;

			}
			
			$post_arr['from_date'] = ($post_arr['from_date']) ? $post_arr['from_date'] : date('Y-m-01');

			$post_arr['end_date'] = ($post_arr['end_date']) ? $post_arr['end_date'] : date('Y-m-t');
			
			$from_date = $post_arr['from_date'];
			$end_date = $post_arr['end_date']; 

			$user_epin = $this->Settings_model->getUserEpinDetails($user_id, $from_date, $end_date );
			$data['user_epin']=$user_epin;			
		}

		
		$this->loadView($data);
	}
	public function removePin($id='')
	{
		if($id)
			$res=$this->Settings_model->removePin($id);
		if($res)
			$this->redirect('Successfully deleted','settings/user-epin',true);
	}

	public function department_master($enc_id = '')
	{
		$data['title'] = 'Department Master';
		if($this->input->post('add_department') && $this->validate_department() )
		{
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			$insert_department = $this->Settings_model->insertDepartmentMaster($post_arr);
			if($insert_department)
			{
				$this->Base_model->insertIntoActivityHistory(log_user_id(),log_user_id(),'department insertion');
				$msg = 'Successfully insert department';
				$this->redirect($msg,'settings/department-master',TRUE);
			}
			else
			{
				$msg = 'Error on department insertion';
				$this->redirect($msg,'settings/department-master',FALSE);
			}
		}
		$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		$data['department_details'] = $this->Settings_model->getDepartmentMaster($id);
		if($this->input->post('update_department') && $this->validate_department())
		{
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			$update = $this->Settings_model->updateDepartmentMaster($post_arr,$id);
			if($update)
			{
				$this->Base_model->insertIntoActivityHistory(log_user_id(),log_user_id(),'department updation');
				$msg = 'Department Updated Successfully';
				$this->redirect($msg,'settings/department-list',TRUE);
			}
			else
			{
				$msg = 'Error on department updation';
				$this->redirect($msg,'settings/department-master/'. $enc_id,FALSE);
			}
		}
		$data['id'] = $id;
		$this->loadView($data);
	}

	public function validate_department()
	{
		$this->form_validation->set_rules('department_id','Department ID','trim|required');
		$this->form_validation->set_rules('department_name','Department Name','trim|required');
		$this->form_validation->set_rules('cost_per_hour','Department Cost per Hour','trim|required|numeric');
		$this->form_validation->set_rules('status','Status','trim|required');
		$result = $this->form_validation->run();
		return $result;
	}

	public function department_list($action='',$enc_id='')
	{
		$data['title'] = 'Department List';

		$post_arr['status']='active';
		$post_arr['department_id']='';

		if($this->input->post('submit'))
		{
			$post_arr= $this->input->post();

			if (element('department_id',$this->input->post())) {
				$post_arr['department_id'] = $post_arr['department_id'];
 				$post_arr['department_name'] =  $this->Base_model->getDepartmentName($post_arr['department_id']);
			}

			if(element('status',$this->input->post()))
			{
				$post_arr['status']=$this->input->post('status');
				
			}
		// print_r($post_arr);die();
			
		}
		$data['post_arr'] = $post_arr;
		$data['department_details'] = $this->Settings_model->getDepartmentMaster( '', $post_arr, '' ); 

		

		if($action == 'delete')
		{
			$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$delete = $this->Settings_model->updateDepartmentStatus($id);
			if($delete)
			{
				$this->Base_model->insertIntoActivityHistory(log_user_id(),log_user_id(),'department deletion');
				$msg = 'Successfully delete department';
				$this->redirect($msg,'settings/department-list',TRUE);
			}
			else
			{
				$msg = 'Error on department deletion';
				$this->redirect($msg,'settings,department-list',FALSE);
			}
		}


		$this->loadView($data);
	}

}
