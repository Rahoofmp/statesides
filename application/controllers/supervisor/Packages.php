<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}




	public  function create_leads()
	{ 
		 // phpinfo();
		 // die();

		$data['title']='Create Leads';

		if ($this->input->post() && $this->validate_leads()) {
			$post_arr = $this->input->post();

			
			// print_r($_FILES);
			// die();
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

			$total_amount=0;
			$advance_amount=0;
			$due_amount=0;

			if (element('total_amount',$post_arr)) {

				$post_arr['total_amount']=$post_arr['total_amount'];
			}

			if (element('advance_amount',$post_arr)) {

				$post_arr['advance_amount']=$post_arr['advance_amount'];
			}

			$post_arr['due_amount']=$total_amount-$advance_amount;


			// print_r($post_arr);
			// die();

			$this->Packages_model->begin();

			$create_lead =  $this->Packages_model->createLeads($post_arr);

			// print_r($create_lead);
			// die();

			

			if($create_lead)
			{
				if (element('source_user',$post_arr)) {
					$post_arr['insert_id']=$create_lead;
					$this->Packages_model->insertSource($post_arr);
					$this->Packages_model->commit();
				}



				$this->redirect( 'Lead created successfully', "packages/create-leads", true );
			}
			else{
				$this->redirect( 'Error on creating lead', "packages/create-leads", false );
			}

			

		}
		$this->loadView($data);

	}

	function list_leads()
	{ 

		$details = $search_arr = $post_arr=[];
		if( $this->input->post('submit') )
		{
			if( $this->input->post('submit') == 'reset')
			{
				$search_arr = [];

			}elseif( $this->input->post('submit') == 'filter'){
				$post_arr = $this->input->post();
				// print_r($post_arr);
				// die();


				if(!element('customer_username',$post_arr)){
					$post_arr['customer_username'] = '';
				}

				if(element('source_id',$post_arr)){
					$post_arr['source_user'] =$this->Base_model->getSourceName($post_arr['source_id']);

				} 


				if(element('country',$post_arr)){
					$post_arr['country_name'] =$this->Base_model->getCountryName($post_arr['country']);

				} 

				if(!element('salesman_id',$post_arr)){
					$post_arr['salesman_id'] = '';
				}else{
					$post_arr['salesman_name'] = $this->Base_model->getUserName($post_arr['salesman_id']);
					$search_arr['salesman_name'] = $post_arr['salesman_name'];

				}
				
				$search_arr['enquiry'] = $post_arr['enquiry'];

				// $search_arr['name'] = $post_arr['name'];
				// $search_arr['email'] = $post_arr['email'];
				// $search_arr['customer_username'] = $post_arr['customer_username'];
				// $search_arr['salesman_id'] = $post_arr['salesman_id'];

			}
			// $details = $this->Customer_model->getAllCustomers( $search_arr );

		}
		

		$data['search_arr'] = $search_arr; 
		$data['post_arr'] = $post_arr; 
		// $data['details'] = $details; 

		// print_r($data);die();
		$data['title'] = 'Leads Details'; 
		$this->loadView($data);
	}

	function modify_leads($enc_id='')
	{   	
		$this->load->model('Customer_model');
		
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
			// print_r($data['customer']);
			// die();

			

		}



		if ($this->input->post('update_customer') && $this->validate_leads_update()) {
			$post_arr = $this->input->post();


			$config['upload_path'] = './assets/images/leads_data/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|zip';
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
					
					$this->redirect( $error, "packages/modify-leads", false );
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
					$this->redirect( $error, "packages/modify-leads", false );
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
					$this->redirect( $error, "packages/modify-leads", false );
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
					$this->redirect( $error, "packages/modify-leads", false );
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
					$this->redirect( $error, "packages/modify-leads", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['dob_certificate']=$image_arr['file_name'];
				}
			}


			$post_arr['id']=$customer_id;

			$total_amount=0;
			$advance_amount=0;
			$due_amount=0;

			if (element('total_amount',$post_arr)) {

				$post_arr['total_amount']=$post_arr['total_amount'];
			}

			if (element('advance_amount',$post_arr)) {

				$post_arr['advance_amount']=$post_arr['advance_amount'];
			}

			$post_arr['due_amount']=$total_amount-$advance_amount;


			
			$update_lead =  $this->Customer_model->updateLead($post_arr);


			if($update_lead)
			{
				$this->redirect( 'Lead updated successfully', "packages/list-leads", true );
			}
			else{
				$this->redirect( 'Error on updating lead', "packages/modify-leads/".$enc_id, false );
			}



		}
		// print_r($this->input->post());
		// die();

		
		$data['title'] = 'Modify Leads';
		$this->loadView($data);
	}

	public function get_customer_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Customer_model->getOrderCount();
			$count_with_filter = $this->Customer_model->getAllCustomersAjax($post_arr, 1);
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

	protected function validate_leads( ){

		$this->form_validation->set_rules('sales_man', lang('sales_man'), 'required');
		$this->form_validation->set_rules('first_name', lang('first_name'), 'required');
		$this->form_validation->set_rules('last_name', lang('last_name'), 'required');
		$this->form_validation->set_rules('mobile', lang('mobile'), 'required');
		$this->form_validation->set_rules('gender', lang('gender'));
		$this->form_validation->set_rules('date', lang('date'), 'required');
		$this->form_validation->set_rules('emmigration', lang('emmigration'));
		$this->form_validation->set_rules('total_amount', lang('total_amount'));
		$this->form_validation->set_rules('email', lang('email'),'trim|required|is_unique[customer_info.email]');


		$result = $this->form_validation->run();
		return $result;


	}
	protected function validate_leads_update( ){

		$this->form_validation->set_rules('sales_man', lang('sales_man'), 'required');
		$this->form_validation->set_rules('first_name', lang('first_name'), 'required');
		$this->form_validation->set_rules('last_name', lang('last_name'), 'required');
		$this->form_validation->set_rules('mobile', lang('mobile'), 'trim|required');
		$this->form_validation->set_rules('gender', lang('gender'));
		$this->form_validation->set_rules('date', lang('date'), 'required');
		$this->form_validation->set_rules('emmigration', lang('emmigration'));
		$this->form_validation->set_rules('total_amount', lang('total_amount'));
		$this->form_validation->set_rules('email', lang('email'),'trim|required');




		$result = $this->form_validation->run();

		return $result;


	}


	

}
