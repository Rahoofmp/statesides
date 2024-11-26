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
		else{
			$this->redirect('No Access', 'dashboard', FALSE);
		}

		if ($this->input->post('follow_up')) {

			$post_arr=$this->input->post();
			$enc_id=$post_arr['enc_id'];
			$customer_id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			if ($customer_id) {
				$this->load->model('Member_model');
				$post_arr['customer_id']=$customer_id;
				$post_arr['user_id']=log_user_id();
				$post_arr['type']='follow';
				$post_arr['date']=date('Y-m-d');
				$create_follow=$this->Member_model->createReminder($post_arr);

				if ($create_follow) {
					$this->redirect('Fowllow-up Added successfully', 'customer/add-customer/'.$enc_id, true);
				}
				else{
					$this->redirect('Error On Adding Fowllow-up', 'customer/add-customer/'.$enc_id, true);
				}
			}
			else
			{
				$this->redirect('Invalid Lead/cusomer', 'add-customer', FALSE);
			}
			
		}

		if ($this->input->post('inactive')) {

			$post_arr=$this->input->post();
			$enc_id=$post_arr['enc_id'];
			$customer_id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			if ($customer_id) {
				$this->load->model('Member_model');
				$post_arr['customer_id']=$customer_id;
				$post_arr['user_id']=log_user_id();
				$post_arr['type']='inactive';
				$post_arr['date']=date('Y-m-d');
				$post_arr['message']=$post_arr['reason'];
				$create_follow=$this->Member_model->createReminder($post_arr);
				$inactive_lead=$this->Base_model->inactivateLead($post_arr['customer_id']);

				if ($inactive_lead) {
					$this->redirect('Lead Inactivated Successfully', 'customer/customer-list', true);
				}
				else{
					$this->redirect('inactivation Failed', 'customer/customer-list', true);
				}
			}
			else
			{
				$this->redirect('Invalid Lead/cusomer', 'add-customer', FALSE);
			}
			
		}


		if ($this->input->post('update_customer') && $this->validate_leads()) {
			$post_arr = $this->input->post();



			$config['upload_path'] = './assets/images/leads_data/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
			$config['max_size'] = '2000000';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = TRUE;

			if($_FILES['ss_cirtifcate']['error']!=4)
			{
				

				$this->load->library('upload', $config);
				$msg = '';
				if (!$this->upload->do_upload('ss_cirtifcate')) {
					$msg = lang('image_not_selected');
					$error = $this->upload->display_errors();
					
					$this->redirect( $error, "packages/create-leads", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['ss_cirtifcate']=$image_arr['file_name'];
				}
			}



			
			if($_FILES['police_clearence']['error']!=4)
			{
				$this->load->library('upload', $config);

				
				$msg = '';
				if (!$this->upload->do_upload('police_clearence')) {
					$msg = lang('image_not_selected');
					$error = $this->upload->display_errors();
					$this->redirect( $error, "packages/create-leads", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['police_clearence']=$image_arr['file_name'];
				}
			}


			if($_FILES['job_cirtificate']['error']!=4)
			{
				$this->load->library('upload', $config);

				
				$msg = '';
				if (!$this->upload->do_upload('job_cirtificate')) {
					$msg = lang('image_not_selected');
					$error = $this->upload->display_errors();
					$this->redirect( $error, "packages/create-leads", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['job_cirtificate']=$image_arr['file_name'];
				}
			}


			if($_FILES['passport_copy']['error']!=4)
			{
				$this->load->library('upload', $config);
				
				$msg = '';
				if (!$this->upload->do_upload('passport_copy')) {
					$msg = lang('image_not_selected');
					$error = $this->upload->display_errors();
					$this->redirect( $error, "packages/create-leads", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['passport_copy']=$image_arr['file_name'];
				}
			}

			if($_FILES['dob_certificate']['error']!=4)
			{
				$this->load->library('upload', $config);
				
				$msg = '';
				if (!$this->upload->do_upload('dob_certificate')) {
					$msg = lang('image_not_selected');
					$error = $this->upload->display_errors();
					$this->redirect( $error, "packages/create-leads", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['dob_certificate']=$image_arr['file_name'];
				}
			}

			if (element('advance_amount',$post_arr)) {


				$post_arr['due_amount']=$post_arr['total_amount']-$post_arr['advance_amount'];
			}
			else{
				$post_arr['due_amount']=0;
				$post_arr['advance_amount']=0;
				
			}

			$post_arr['id']=$customer_id;

			$this->Customer_model->begin();

			// if ($post_arr['enquiry_status']=='customer') {

			// 	$this->load->model('Signup_model');

			// }

			
			$update_lead =  $this->Customer_model->updateLead($post_arr);

	
			if($update_lead)
			{
				$this->redirect( 'Lead updated successfully', "customer/customer-list", true );
			}
			else{
				$this->redirect( 'Error on updating lead', "customer/add-customer/".$enc_id, false );
			}



		}
		$data['title'] = 'Modify Customer';
		$this->loadView($data);
	}

	protected function validate_leads(){

		
		$this->form_validation->set_rules('first_name', lang('first_name'), 'required');
		$this->form_validation->set_rules('last_name', lang('last_name'), 'required');
		$this->form_validation->set_rules('mobile', lang('mobile'), 'required');
		$this->form_validation->set_rules('gender', lang('gender'), 'required');
		$this->form_validation->set_rules('date', lang('date'), 'required');
		$this->form_validation->set_rules('emmigration', lang('emmigration'), 'required');
		

		$result = $this->form_validation->run();
		
		return $result;


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
		// print_r($this->input->post());
		// die();
		if( $this->input->post() )
		{
			if( $this->input->post('submit') == 'reset')
			{
				$search_arr = [];

			}elseif( $this->input->post('submit') == 'filter'){
				$post_arr = $this->input->post();

				if(!element('customer_username',$post_arr)){
					$post_arr['customer_username'] = '';
				} 

				if(element('source_id',$post_arr)){
					$search_arr['source_user'] =$this->Base_model->getSourceName($post_arr['source_id']);


				} 

				if(!element('source_id',$post_arr)){
					$post_arr['source_id'] = '';
				}

				if(!element('salesman_id',$post_arr)){
					$post_arr['salesman_id'] = '';
				}else{
					$post_arr['salesman_name'] = $this->Base_model->getUserName($post_arr['salesman_id']);
					$search_arr['salesman_name'] = $post_arr['salesman_name'];

				}
				
				// $search_arr['name'] = $post_arr['name'];
				$search_arr['enquiry'] = $post_arr['enquiry'];
				$search_arr['source_id'] = $post_arr['source_id'];
				$search_arr['customer_username'] = $post_arr['customer_username'];
				$search_arr['salesman_id'] = $post_arr['salesman_id'];

			}
			// $details = $this->Customer_model->getAllCustomers( $search_arr );

		}


		$data['search_arr'] = $search_arr; 
		$data['details'] = $details; 

		// print_r($data);die();
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






