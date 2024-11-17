<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function add_customer($enc_id='')
	{   	
		
		if( $enc_id ){


			// $customer_username =  $this->input->get( 'customer_username' ); 
			$customer_id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id); 

			if (!$customer_id) {
				$msg = lang('text_invalid_customer_username');
				$this->redirect($msg, 'customer/add-customer', FALSE);
			}
			$data['id']=$customer_id;
			$search_arr['customer_username']=$customer_id;
			$data['customer'] = element(0,$this->Customer_model->getAllCustomers( $search_arr ));

			

		}
		if ($this->input->post('add_customer') && $this->validate_add_customer()) {
			$post_arr = $this->input->post();
			$post_arr['password']=$post_arr['psw'];
			$post_arr['created_by']= log_user_id();
			$this->load->model('Packages_model');
			if(element( 'salesman_id', $post_arr ))				
				$post_arr['salesman_name'] = $this->Base_model->getUserName($post_arr['salesman_id']);

			$ins=$this->Customer_model->addCustomerDetails($post_arr);

			if($ins){
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Customer Registered', serialize($post_arr));
				$this->load->model('Mail_model');
				$email_alerts_arr = $this->software->getSettingsByKey('register'); 

				if(value_by_key('register') && $email_alerts_arr['code'] == 'e_mail_alert')
				{

					$this->load->model('Mail_model'); 
					$post_arr['fullname'] = $post_arr['name'];

					// $send_mail = $this->Mail_model->sendEmails('customer_registration', $post_arr);
				}

				$msg = 'Registration completed Successfully'.' , ID : '.$post_arr['customer_username'].' ,password : '.$post_arr['password'];
				$this->redirect("<b>$msg </b>", "customer/add-customer", TRUE);
			}
			else{
				$msg = 'Registration Failed...!';
				$this->redirect("<b>$msg </b>", "customer/add-customer", FALSE);
			}
			
		}


		if ($this->input->post('update_customer') && $this->validate_update_customer()) {
			$post_arr = $this->input->post();

			$post_arr['password']=$post_arr['psw'];
			if($post_arr['psw']){
				if ($post_arr['psw']!=$post_arr['cpsw']) {
					$this->redirect("Confirm Password missmatch to Password", "customer/add-customer/$enc_id", FALSE);
				}
			}
			if($post_arr['customer_username']!=$data['customer']['customer_username']){
				if ($this->Customer_model->check_exists('customer_username',$post_arr['customer_username'])) {
					$this->redirect("UserName Already Exists", "customer/add-customer/$enc_id", FALSE);
				}
			}
			if($post_arr['mobile']!=$data['customer']['mobile']){
				if ($this->Customer_model->check_exists('mobile',$post_arr['mobile'])) {
					$this->redirect("Mobile Already Exists", "customer/add-customer/$enc_id", FALSE);
				}
			}
			if($post_arr['email']!=$data['customer']['email']){
				if ($this->Customer_model->check_exists('email',$post_arr['email'])) {
					$this->redirect("Email Already Exists", "customer/add-customer/$enc_id", FALSE);
				}
			}

			$update=$this->Customer_model->updateCustomerDetails($post_arr,$customer_id);
			if($update){
				$this->load->model('Mail_model'); 

				// $send_mail = $this->Mail_model->sendEmails('customer_updation', $post_arr);
				
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(), 'customer_data_updated', serialize($post_arr));

				$msg = 'Updateion completed Successfully'.' , ID : '.$post_arr['customer_username'];
				$this->redirect("<b>$msg </b>", "customer/customer-list", TRUE);
			}
			else{
				$msg = 'Updateion Failed...!';
				$this->redirect("<b>$msg </b>", "customer/customer-list", FALSE);
			}
			
		}
		// print_r($this->input->post());
		// die();

		
		$data['title'] = 'Add Customer';
		$this->loadView($data);
	}
	function validate_add_customer() 
	{
		$password_length = value_by_key('password_length');
		$this->form_validation->set_rules('customer_username', 'UserName', 'required|is_unique[customer_info.customer_username]|alpha_numeric');
		$this->form_validation->set_rules('name', 'Customer Name', 'required');
		$this->form_validation->set_rules('mobile', 'Contact Number', 'required|is_unique[customer_info.mobile]');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[customer_info.email]');
		$this->form_validation->set_rules('location', 'Location', 'required');
		$this->form_validation->set_rules('psw','password', 'required|matches[cpsw]|min_length['. $password_length .']'); 
		$this->form_validation->set_rules('cpsw','confirm password','required'); 
		$this->form_validation->set_rules( 'user_type', 'Customer Type', 'required|in_list[lead,customer]'); 
		$this->form_validation->set_rules('salesman_id','salesman_id ','required'); 

		$this->form_validation->set_rules( 'organization_type', 'Organization Type', 'required|in_list[Individual,organization]');
		$result =  $this->form_validation->run();

		return $result;
	}
	function validate_update_customer() 
	{

		$this->form_validation->set_rules('customer_username', 'UserName', 'required');
		$this->form_validation->set_rules('name', 'Customer Name', 'required');
		$this->form_validation->set_rules('mobile', 'Contact Number', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('salesman_id', 'Salesman', 'required|numeric');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('location', 'Location', 'required');		
		$this->form_validation->set_rules( 'user_type', 'Customer Type', 'required|in_list[lead,customer]');
		$this->form_validation->set_rules( 'organization_type', 'Organization Type', 'required|in_list[Individual,organization]'); 

		$result =  $this->form_validation->run();

		return $result;
	}
	function customer_list()
	{ 

		$details = $search_arr = $post_arr=[];
		if( $this->input->post('submit') )
		{
			if( $this->input->post('submit') == 'reset')
			{
				$search_arr = [];

			}elseif( $this->input->post('submit') == 'filter'){
				$post_arr = $this->input->post();
				if(!element('customer_username',$post_arr)){
					$post_arr['customer_username'] = '';
				} 

				if(!element('salesman_id',$post_arr)){
					$post_arr['salesman_id'] = '';
				}else{
					$post_arr['salesman_name'] = $this->Base_model->getUserName($post_arr['salesman_id']);
					$search_arr['salesman_name'] = $post_arr['salesman_name'];

				}
				
				$search_arr['name'] = $post_arr['name'];
				$search_arr['email'] = $post_arr['email'];
				$search_arr['customer_username'] = $post_arr['customer_username'];
				$search_arr['salesman_id'] = $post_arr['salesman_id'];

			}
			// $details = $this->Customer_model->getAllCustomers( $search_arr );

		}

		$data['search_arr'] = $search_arr; 
		// $data['details'] = $details; 

		// print_r($data['details']);die();
		$data['title'] = lang('customers_list'); 
		$this->loadView($data);
	}
	public function get_customer_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Customer_model->getOrderCount();
			$count_with_filter = $this->Customer_model->getAllCustomersAjax($post_arr, 1);
			$post_arr['salesman_id'] = log_user_id();
			// print_r($post_arr);die();
			$details = $this->Customer_model->getAllCustomersAjax( $post_arr,'');
			// echo $this->db->last_query();
			// die();
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $details,
			);

			echo json_encode($response);
		}
	}

	function customer_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);
			// die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getCustomerIdAuto($post['q']);
			echo json_encode($json);
		}
	}

}






