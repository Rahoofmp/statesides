<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}


	public function profile($url_id = '')
	{
		$data['title']=" Edit Profile";
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

			if (!$user_id) {
				$msg = lang('text_invalid_user_name');
				$this->redirect($msg, 'member/profile', FALSE);
			}

		}else{            
			$user_id = log_user_id();
			$user_name = $this->Base_model->getUserName($user_id);

		}
		
		if( $this->input->post( 'profile_update' ) )
		{


			
			$post_arr = $this->input->post();
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
				$this->redirect(lang("success_profile_updation"), "member/profile?user_name=$user_name", TRUE);
			else
				$this->redirect(lang("failed_profile_updating"), "member/profile?user_name=$user_name", FLASE);
		}

		$select_arr = ['first_name', 'user_photo', 'email', 'mobile',  'facebook', 'instagram', 'twitter'];
		$data['user_details'] = $this->Base_model->getUserDetails($user_id, $select_arr ); 			
		
		$data['user_name'] = $user_name;
		$data['user_id'] = $user_id;
		$this->loadView($data);
	}	



	function change_credential()
	{ 

		$user_id = log_user_id();
		$user_name = log_user_name(); 

		if($this->input->post('credential_update') == 'password' && $this->validate_change_credential('update_password')){

			$post_arr = $this->input->post();

			$this->config->load('bcrypt');
			$this->load->library('bcrypt');
			$new_password_hashed = $this->bcrypt->hash_password( $post_arr["new_password"] );


			if ( $this->Member_model->updatePassword( $new_password_hashed, $user_id )) 
			{
				$this->load->model('Mail_model');
				$mail_arr = array(
					'user_id' => $user_id,
					'password' => $post_arr["new_password"],
					'email' => $this->data[ 'user_details' ]['email'],
					'fullname' => $this->data[ 'user_details' ]['first_name'],
				);  

				$post_arr['ticket_id'] = $this->Mail_model->sendEmails("password",$mail_arr);

				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'update_password_admin', serialize($post_arr));

				$this->redirect( lang('password_updated_successfully'), "member/change-credential?user_name=$user_name", TRUE);
			} else {
				$msg = lang('error_on_password_updation');
				$this->redirect($msg, "member/change-credential?user_name=$user_name", FALSE);
			}

		}
		$data['user_name'] = $user_name; 
		$data['enc_user_id'] = $this->Base_model->encrypt_decrypt("encrypt",$user_id);; 

		$data['title'] = lang('change_credential'); 
		$this->loadView($data);
	}

	function reminder_settings()
	{ 

		$user_id = log_user_id();
		$user_name = log_user_name(); 

		$data['current_reminders'] = $this->Member_model->getcurrentreminders($user_id);


		if($this->input->post() && $this->validate_reminder())
		{

			$post_arr = $this->input->post();
			$post_arr['user_id']=$user_id;
			$create_reminder=$this->Member_model->createReminder($post_arr);

			if ($create_reminder) {

				$this->redirect( 'Reminder Created', "member/reminder-settings", true );
			}
			else{
				$this->redirect( 'Error On Reminder Creation', "member/reminder-settings", false );
			}


		}
		
		
		$data['title'] = 'Reminder Settings'; 
		$this->loadView($data);
	}

	protected function validate_change_credential( $action ){

		if( $action == 'update_password'){

			$password_length = value_by_key('password_length');
			$this->form_validation->set_rules( 'new_password', lang('new_password'), 'trim|required');
			$this->form_validation->set_rules( 'confirm_password', lang( 'confirm_password' ), 'trim|required|min_length['. $password_length .']|matches[new_password]' );
		}

		$result = $this->form_validation->run(); 
		return $result;
	}

	protected function validate_reminder(){

		$this->form_validation->set_rules( 'date', lang('date'), 'trim|required');
		$this->form_validation->set_rules( 'message', lang( 'message' ), 'trim|required' );
		$result = $this->form_validation->run(); 
		return $result;
	}

}
