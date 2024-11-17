<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends Base_Controller 
{

	function __construct() {
		parent::__construct();  
	} 
	public function index()
	{ 

		$this->load->model('Settings_model');
		$data['title'] = 'Signup a user';
		$data['department'] = $this->Settings_model->getDepartmentMaster();
		if ($this->input->post('register') && $this->validate_signup()) {
			$register = $this->input->post();
			$this->Signup_model->begin();

			$response = $this->Signup_model->registrationProcess($register);
			if ($response['status']) {
				$this->Signup_model->commit();


				$ecn_user_id = $this->Base_model->encrypt_decrypt( 'encrypt', $response['user_id'] );
				$username = $response['username'];
				$password = $response['password'];

				$msg = lang('signup_completed_successfully').' , ID : '.$username.' ,password : '.$password;
				$this->redirect("<b>$msg </b>", "signup", TRUE);

			} else {
				$this->Signup_model->rollback();
				$this->redirect($msg, "signup", FALSE);
			}


		}

		$this->loadView($data);

	} 

	public function success( $ecn_user_id = null )
	{ 

		if( !$ecn_user_id ){
			$this->redirect( lang('text_no_permission'), 'signup', FALSE );
		}

		$user_id = $this->Base_model->encrypt_decrypt('decrypt',$ecn_user_id);
		$preview_details = $this->Base_model->getUserDetails($user_id); 

		$preview_details['invoice_number'] = str_pad($user_id, 8, '0', STR_PAD_LEFT);
		$preview_details['date'] = date('Y - M - d');     

		$preview_details['username'] = $this->Base_model->getUserName($user_id); 
		$preview_details['reg_method'] = $this->Base_model->getLoginInfoField( 'payment_type', $user_id);

		$data['title'] = lang('success');
		$data['preview_details'] = $preview_details;

		$this->loadView($data);

	}

	function validate_signup() {
		$password_length = value_by_key('password_length');
		$this->form_validation->set_rules('user_type','user type', 'trim|required|in_list[driver,dept_supervisor,supervisor,store_keeper,packager,admin,salesman,designer,purchaser,workers]');
		$this->form_validation->set_rules('user_name','user name', 'trim|required|is_unique[login_info.user_name]');
		// $this->form_validation->set_rules('department','Department', 'trim|required|is_exist[department.id]');
		
		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|is_unique[user_info.email]|callback_checkActiveEmail');
		$this->form_validation->set_message('checkActiveEmail','The Email already registered');
		$this->form_validation->set_rules('mobile','mobile', 'trim|required|is_unique[user_info.mobile]|callback_checkActiveMobile'); 
		$this->form_validation->set_message('checkActiveMobile','The Mobile Number already registered');
		$this->form_validation->set_rules('psw','password', 'trim|required|matches[cpsw]|min_length['. $password_length .']'); 
		$this->form_validation->set_rules('cpsw','confirm password','trim|required'); 
		
		$validation_result =  $this->form_validation->run();
		return $validation_result;
	}



	function checkActiveEmail($post_arr=[]){
		$post=$this->input->post();
		if($this->Signup_model->checkEmailExist($post)){
			return FALSE;
		}
		return TRUE; 
	}

	function checkActiveMobile($post){
		$post=$this->input->post();
		if($this->Signup_model->checkMobileExist($post)){
			return FALSE;
		}
		return TRUE; 
	}

}

