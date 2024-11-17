<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function all_members()
	{ 

		$details = $search_arr = $post_arr=[];
		if( $this->input->post('submit') )
		{
			if( $this->input->post('submit') == 'reset')
			{
				$search_arr = [];

			}elseif( $this->input->post('submit') == 'filter'){
				$post_arr = $this->input->post();
				if(!element('user_name',$post_arr)){
					$post_arr['user_name'] = '';
				}
				if($this->input->post('user_name'))
				{
					$search_arr['user_name'] = $this->Base_model->getUserName($post_arr['user_name']);
				}
				$search_arr['first_name'] = $post_arr['first_name'];
				$search_arr['email'] = $post_arr['email'];
				$search_arr['user_type'] = $post_arr['user_type'];
				$search_arr['mobile'] = $post_arr['mobile'];
				// print_r($search_arr);die();
			}
			$details = $this->Member_model->getAllMembers( $search_arr );
			
		}

		$data['search_arr'] = $search_arr; 
		$data['details'] = $details; 

// print_r($data['details']);die();
		$data['title'] = lang('all_members'); 
		$this->loadView($data);
	}

	function profile($url_id = '')
	{ 
		$url_id = $this->Base_model->encrypt_decrypt('decrypt',$url_id); 
		
		if( $url_id )
		{
			$user_id = $url_id;
			$user_name = $this->Base_model->getUserName($user_id);
			if( !$user_name )
			{
				$msg = lang('invalid_user_name');
				$this->redirect($msg, 'member/profile', FALSE);
			}

		}elseif( $this->input->get( 'user_id' ) ){

			$encoded_user_id =  $this->input->get('user_id');
			$user_id = $this->Base_model->encrypt_decrypt('decrypt', $encoded_user_id);
			$user_name = $this->Base_model->getUserName($user_id); 

		}elseif( $this->input->get( 'user_name' ) ){

			$user_name =  $this->input->get( 'user_name' ); 
			$user_id = $this->Base_model->getUserId($user_name); 
			$data['user_type']=  $this->Base_model->getUserType($user_id);
			 // print_r($data['user_type']);die();
			if (!$user_id) {
				$msg = lang('text_invalid_user_name');
				$this->redirect($msg, 'member/profile', FALSE);
			}

		}else{            
			$user_id = log_user_id();
			$user_name = $this->Base_model->getUserName($user_id);

		}
		$this->load->model('Settings_model');
		$data['department'] = $this->Settings_model->getDepartmentMaster();
		if( $this->input->post( 'profile_update' ) )
		{


			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			$post_arr['file_name'] = NULL;

			if($_FILES['userfile']['error']!=4)
			{
				$config['upload_path'] = './assets/images/profile_pic/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
				$config['max_size'] = '2000000';
				$config['remove_spaces'] = true;
				$config['overwrite'] = false;
				$config['encrypt_name'] = TRUE;

				$this->load->library('upload', $config);
				$msg = '';
				if (!$this->upload->do_upload()) {
					$msg = lang('image_not_selected');
					$error = $this->upload->display_errors();
					$this->redirect( $error, "member/profile?user_name=$user_name", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['file_name']=$image_arr['file_name'];
				}
			}
			$update_profile =  $this->Member_model->updateUserProfile( $post_arr, $user_id );

			if($update_profile)
			{
				
				$update_department= $this->Member_model->updateLoginInfoDepartment( $user_id,$post_arr['department']);
				// print_r($user_id );die();

				if($update_department)
				{

					$this->redirect(lang("success_profile_updation"), "member/profile?user_name=$user_name", TRUE);
				}
			}
			else
				$this->redirect(lang("failed_profile_updating"), "member/profile?user_name=$user_name", FLASE);
		}

		if($this->input->post('credential_update') == 'username' && $this->validate_change_credential('update_username')){

			$post_arr = $this->input->post();
			$user_id = $this->Base_model->getUserId( $post_arr['username'] );

			$update = $this->Member_model->updateUserName( $post_arr['new_username'], $user_id, $post_arr['username'] );

			if ($update) {  
				$msg = lang('user_name_updated_successfully');
				$this->redirect($msg, 'member/profile?user_name='.$post_arr['new_username'], TRUE);
			} else {
				$msg = lang('error_on_user_name_updation');
				$this->redirect($msg, "member/profile?user_name=$user_name", FALSE);
			}

		}
		elseif($this->input->post('credential_update') == 'password' && $this->validate_change_credential('update_password')){

			$post_arr = $this->input->post();

			// if (  $this->Base_model->getLoginInfoField( 'secure_pin', log_user_id() ) !=  $post_arr['secure_pin'] ) 
			// {
			// 	$this->redirect(lang("invalid_security_pin"), "member/change-credential?user_name=$user_name");
			// }

			$this->config->load('bcrypt');
			$this->load->library('bcrypt');
			$new_password_hashed = $this->bcrypt->hash_password( $post_arr["new_password"] );

			$user_name =  $post_arr['username']; 
			$user_id = $this->Base_model->getUserId( $post_arr['username'] );
			
			if ( $this->Member_model->updatePassword( $new_password_hashed, $user_id )) 
			{
				$this->load->model('Mail_model');
				$mail_arr = array(
					'user_id' => $user_id,
					'password' => $post_arr["new_password"],
					'email' => $this->Base_model->getUserInfoField('email', $user_id),
					'fullname' => $this->Base_model->getUserInfoField('first_name', $user_id),
				);  

				$post_arr['ticket_id'] = $this->Mail_model->sendEmails("password",$mail_arr);

				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'update_password_admin', serialize($post_arr));

				$this->redirect( lang('password_updated_successfully'), "member/profile?user_name=$user_name", TRUE);
			} else {
				die('2222');
				$msg = lang('error_on_password_updation');
				$this->redirect($msg, "member/profile?user_name=$user_name", FALSE);
			}
		}
		$data['department_id'] = $this->Base_model->getLoginInfoField('department_id',$user_id);
		$select_arr = ['first_name', 'user_photo', 'email', 'mobile',  'facebook', 'instagram', 'twitter'];
		$data['user_details'] = $this->Base_model->getUserDetails($user_id, $select_arr ); 	

		$data['user_name'] = $user_name;
		$data['user_id'] = $user_id;
		$data['title'] = lang('profile'); 


		$this->loadView($data);
	}


	public function valid_date($dob)
	{
		$year= date('Y', strtotime($dob) );
		$month= date('m', strtotime($dob) );
		$day= date('d', strtotime($dob) );

		return checkdate($month, $day, $year); 
	}

	function change_credential()
	{ 
		if( $this->input->post('search_user'))
		{
			if($this->validate_change_credential('search') )
			{
				$user_name = $this->input->post('search_user');
				$user_id = $this->Base_model->getUserId( $user_name );
			}else {
				$msg = lang('text_invalid_user_name');
				$this->redirect($msg, 'member/change-credential', FALSE);
			}
		}elseif( $this->input->get( 'user_name' ) ){

			$user_name =  $this->input->get( 'user_name' ); 
			$user_id = $this->Base_model->getUserId($user_name); 

			if (!$user_id) {
				$msg = lang('text_invalid_user_name');
				$this->redirect($msg, 'member/change-credential', FALSE);
			}

		}else{            
			$user_id = log_user_id();
			$user_name = $this->Base_model->getUserName($user_id);
			
		}

		if($this->input->post('credential_update') == 'username' && $this->validate_change_credential('update_username')){

			$post_arr = $this->input->post();
			

			// if (  $this->Base_model->getLoginInfoField( 'secure_pin', log_user_id() ) !=  $post_arr['secure_pin'] ) 
			// {
			// 	$this->redirect(lang("invalid_security_pin"), "member/change-credential?user_name=$user_name");
			// }


			$user_id = $this->Base_model->getUserId( $post_arr['username'] );

			$update = $this->Member_model->updateUserName( $post_arr['new_username'], $user_id, $post_arr['username'] );

			if ($update) {  
				$msg = lang('user_name_updated_successfully');
				$this->redirect($msg, 'member/change-credential?user_name='.$post_arr['new_username'], TRUE);
			} else {
				$msg = lang('error_on_user_name_updation');
				$this->redirect($msg, "member/change-credential?user_name=$user_name", FALSE);
			}

		}elseif($this->input->post('credential_update') == 'password' && $this->validate_change_credential('update_password')){

			$post_arr = $this->input->post();

			// if (  $this->Base_model->getLoginInfoField( 'secure_pin', log_user_id() ) !=  $post_arr['secure_pin'] ) 
			// {
			// 	$this->redirect(lang("invalid_security_pin"), "member/change-credential?user_name=$user_name");
			// }

			$this->config->load('bcrypt');
			$this->load->library('bcrypt');
			$new_password_hashed = $this->bcrypt->hash_password( $post_arr["new_password"] );

			$user_name =  $post_arr['username']; 
			$user_id = $this->Base_model->getUserId( $post_arr['username'] );
			
			if ( $this->Member_model->updatePassword( $new_password_hashed, $user_id )) 
			{
				$this->load->model('Mail_model');
				$mail_arr = array(
					'user_id' => $user_id,
					'password' => $post_arr["new_password"],
					'email' => $this->Base_model->getUserInfoField('email', $user_id),
					'fullname' => $this->Base_model->getUserInfoField('first_name', $user_id),
				);  

				$post_arr['ticket_id'] = $this->Mail_model->sendEmails("password",$mail_arr);

				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'update_password_admin', serialize($post_arr));

				$this->redirect( lang('password_updated_successfully'), "member/change-credential?user_name=$user_name", TRUE);
			} else {
				die('2222');
				$msg = lang('error_on_password_updation');
				$this->redirect($msg, "member/change-credential?user_name=$user_name", FALSE);
			}
		}elseif($this->input->post('credential_update') == 'security_password' && $this->validate_change_credential('update_security_password')){

			$post_arr = $this->input->post();
			if (  $this->Base_model->getLoginInfoField( 'secure_pin', log_user_id() ) !=  $post_arr['secure_pin'] ) 
			{
				$this->redirect(lang("invalid_security_pin"), "member/change-credential?user_name=$user_name");
			}  

			$user_name =  $post_arr['username']; 
			$user_id = $this->Base_model->getUserId( $post_arr['username'] );

			if ( $this->Member_model->updateSecurityPin( $post_arr["new_security_password"], $user_id )) { 

				$mail_arr = array(
					'user_id' => $user_id,
					'security_pin' => $post_arr["new_security_password"],
				);        
				$this->load->model('Mail_model');
				$res = $this->Mail_model->sendEmails("security_pin", $mail_arr); 

				$this->Base_model->insertIntoActivityHistory( log_user_id(), log_user_id(), 'update_pin_admin' );              

				$msg = lang('pin_updated_successfully');
				$this->redirect($msg, "member/change-credential?user_name=$user_name", TRUE);
			} else {
				$msg = lang('error_on_pin_updation');
				$this->redirect($msg, "member/change-credential?user_name=$user_name", FALSE);
			}
		}

		$select_arr = ['first_name', 'mobile', 'email', 'country', 'city',];

		$data['user_details'] = $this->Base_model->getUserDetails($user_id, $select_arr ); 	

		$data['user_name'] = $user_name; 

		$data['title'] = lang('change_credential'); 
		$this->loadView($data);
	}

	protected function validate_change_credential( $action ){
		if( $action == 'search')
		{	
			$this->form_validation->set_rules('user_name', lang('user_name'), 'required|is_exist[login_info.user_name]');

		}elseif( $action == 'update_username'){

			$user_name_min_length = value_by_key('user_name_min_length');
			$user_name_max_length = value_by_key('user_name_max_length');
			$this->form_validation->set_rules('username', lang('user_name'), 'required|is_exist[login_info.user_name]');

			$this->form_validation->set_rules('new_username', lang('new_username'), 'required|alpha_numeric|min_length['. $user_name_min_length .']|max_length['. $user_name_max_length .']|differs[username]|is_unique[login_info.user_name]');

		}elseif( $action == 'update_password'){

			$password_length = value_by_key('password_length'); 

			$this->form_validation->set_rules('username', lang('user_name'), 'required|is_exist[login_info.user_name]');

			$this->form_validation->set_rules( 'new_password', lang('new_password'), 'required');
			$this->form_validation->set_rules( 'confirm_password', lang( 'confirm_password' ), 'required|min_length['. $password_length .']|matches[new_password]' );
		}elseif( $action == 'update_security_password'){

			$this->form_validation->set_rules('username', lang('user_name'), 'required|is_exist[login_info.user_name]');

			$this->form_validation->set_rules( 'new_security_password', lang('new_security_password'), 'required');
			
			$this->form_validation->set_rules( 'confirm_security_password', lang( 'confirm_security_password' ), 'required|min_length[8]|matches[new_security_password]' );
		}

		$result = $this->form_validation->run();
			// print_r($this->form_validation->error_array());die();
		return $result;

	}  


	public function verify_kyc()
	{
		$data['title']="Verify KYC";
		$data['details']=$this->Member_model->getKycDetails();
		if($this->input->post('verify_kyc'))
		{
			$post=$this->input->post();
			$used_user_id = $post['verify_kyc'];
			$kyc = $this->Member_model->getKycDetails($used_user_id);
			$kyc = element('0',$kyc);
			$user_info_update=$this->Member_model->updateuserInfo($kyc,$used_user_id);
			$login_info_update=$this->Member_model->updateLoginInfo('verified',$used_user_id);
			$update = $this->Member_model->changeKYCVerificationStatus($post,'verified');
			$this->Member_model->commit();
			if($update){
				$msg = "KYC Verified";
				$this->redirect($msg, "member/verify-kyc", TRUE);
			}
			else
			{
				$msg = "KYC Verification failed";
				$this->redirect($msg, "member/verify-kyc", FALSE);
			}
		}
		if($this->input->post('un_verify_kyc'))
		{
			$post_array = $this->input->post();
			$used_user_id = $post_array['un_verify_kyc'];
			$reason = $post_array["reason"];
			if($reason == "")
			{
				$msg = "Please update reason of KYC rejection.";
				$this->redirect($msg, "member/verify-kyc", FALSE);
			}
			
			$post_array["reason"]="KYC rejected : ".$reason ;
			$login_info_update=$this->Member_model->updateLoginInfo('no',$used_user_id);
			$update = $this->Member_model->changeKYCVerificationStatus($post_array,'deleted');
			$this->Member_model->commit();
			if($update){
				$this->Base_model->insertIntoActivityHistory($used_user_id,log_user_id(), 'KYC uplaoded status rejected',serialize($post_array));
				$msg = "KYC Rejected..";
				$this->redirect($msg, "member/verify-kyc", FALSE);
			}
			else {
				$msg = "KYC Verified failed..";
				$this->redirect($msg, "member/verify-kyc", FALSE);
			}			
		}


		// print_r($details); die();
		$this->loadView($data);
	}
	function add_supplier($enc_id='')
	{
		$data['title']="Add Supplier";
		if ($this->input->post('add_supplier') && $this->validate_add_supplier()) {
			$post_arr = $this->input->post();
			$ins=$this->Member_model->addSupplierDetails($post_arr);

			if($ins){
				$msg ="Supplier Added Successfully";
				$this->redirect("<b>$msg </b>", "member/add-supplier", TRUE);
			}
			else{
				$msg = 'Failed To Add Supplier';
				$this->redirect("<b>$msg </b>", "member/add-supplier", FALSE);
			}
			
		}
		if( $enc_id ){

			$user_name = $this->Base_model->encrypt_decrypt('decrypt',$enc_id); 
			if (!$user_name) {
				$msg = lang('text_invalid_customer_username');
				$this->redirect($msg, 'member/add-supplier', FALSE);
			}
			if ($this->input->post('update_supplier') && $this->validate_add_supplier()) {
				$post_arr = $this->input->post();

				$update=$this->Member_model->updateSupplierDetails($post_arr, $user_name);

				if($update){
					$msg = 'Updation completed Successfully';
					$this->redirect("<b>$msg </b>", "member/supplier-list", TRUE);
				}
				else{
					$msg = 'Updateion Failed...!';
					$this->redirect("<b>$msg </b>", "member/supplier-list", FALSE);
				}

			}
			$data['id']=$user_name;
			$search_arr['user_name']=$user_name;
			$data['supplier'] = $this->Member_model->getAllSuppliers( $search_arr );
			
		}

		$this->loadView($data);
	}
	function supplier_list()
	{ 

		if( $this->input->post('submit') )
		{
			$search_arr = $this->input->post();
				// $details = $this->Member_model->getAllSuppliers( $search_arr );
				// $data['details'] = $details; 
			
			$data['search_arr'] = $search_arr; 
		}
		$data['title'] = lang('suppliers_list'); 
		$this->loadView($data);
	}
	function create_vat($enc_id='',$action='')
	{
		if($this->input->post('create') && $this->validate_add_vat())
		{
			$post_arr = $this->input->post();
			$insert = $this->Member_model->insertVat($post_arr);
			if($insert)
			{
				$msg = 'Created Successfully';
				$this->redirect("<b>$msg </b>", "member/create-vat", TRUE);
			}
			else
			{
				$msg = 'Failed';
				$this->redirect("<b>$msg </b>", "member/create-vat",FALSE);
			}
			
		}
		if ($this->input->post('update') && $this->validate_add_vat()) {
			$post_arr = $this->input->post();
			$update = $this->Member_model->updateVat($post_arr);
			if($update)
			{
				$msg='Updated  Successfully';
				$this->redirect("<b> $msg </b>","member/create-vat");
			}
			else
			{
				$msg = 'Failed On Updation';
				$this->redirect("<b> $msg</b>","member/create-vat");
			}
			
		}
		if($enc_id)
		{
			$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$data['vat_detail'] = $this->Member_model->getVatDetails($id);
			$data['id'] = $id;
			
		}
		if($action == 'delete')
		{
			$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$delete = $this->Member_model->updateVat('',$id);
			if($delete)
			{
				
				$msg = 'Successfully deleted ';
				$this->redirect($msg,'member/create-vat',TRUE);
			}
			else
			{
				$msg = 'Error on deletion';
				$this->redirect($msg,'member/create-vat',FALSE);
			}
		}

		$data['details'] = $this->Member_model->getVatDetails();
		$data['title'] =" VAT";
		$this->loadView($data);
	}

	function validate_add_vat()
	{
		$this->form_validation->set_rules('name','Name','required');
		$this->form_validation->set_rules('value','Value','required|numeric');
		$result = $this->form_validation->run();
		return $result;
	}

	public function get_supplier_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Member_model->getCount();
			$count_with_filter = $this->Member_model->getAllSuppliersAjax($post_arr, 1);
			$details = $this->Member_model->getAllSuppliersAjax( $post_arr,'');
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $details,
			);

			echo json_encode($response);
		}
	}
	function validate_add_supplier() 
	{
		$post_arr = $this->input->post();
		
		if(element('add_supplier',$post_arr))
		{
			$this->form_validation->set_rules('user_name', 'UserName', 'required|alpha_numeric|is_unique[supplier_info.user_name]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[supplier_info.email]');
		}else{
 			$user_name = $this->Base_model->encrypt_decrypt('decrypt',$this->uri->segment(4));
			if($post_arr['user_name'] != $user_name){
				$this->form_validation->set_rules('user_name', 'UserName', 'required|alpha_numeric|is_unique[supplier_info.user_name]');
			}else {		
				$details = $this->Member_model->EmailDetails($post_arr['user_name']);
				if($post_arr['email']!= $details){
					$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[supplier_info.email]');
				}
			} 
		}
		
		$this->form_validation->set_rules('name', 'Supplier Name', 'required');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('contact_person', 'Contact Person', 'required');

		$result =  $this->form_validation->run();
		return $result;
	}



}
