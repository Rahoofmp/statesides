<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once ("Api_Controller.php");
class Users extends Api_Controller {

	function __construct() {
		parent::__construct();  

		$this->load->model(['Zone_model', 'Website_model','Base_model','Settings_model','Customer_model','Mail_model','Dashboard_model']);
	}



	public function dashboard()
	{ 
		try{

			$token = $this->check_header();  
			
			$user_id=$token->user_id;
			$user_name=$this->Base_model->getUserName($user_id);
			if (!$user_name) {
				$response['success']=FALSE;
				$msg="Please Login";
				$response['invalid_user']=$msg;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
				
			}
			else
			{
				// $response['user_details']=$this->Base_model->getUserdetails($user_id);
				$response['full_name']  = $this->Base_model->getFullName( $user_id );
				$response['user_name']=$user_name;

				$response['pending_delivery_count'] = $this->Dashboard_model->getDeliveryCount( $user_id,'pending');
				$response['sendto_delivery_count'] = $this->Dashboard_model->getDeliveryCount( $user_id,'send_to_delivery');
				// $response['recent_projects'] = $this->Dashboard_model->getRecentProjectName();
				$response['details'] = $this->Dashboard_model->getRecentDeliveries('',$user_id);
				$response['count_delivery_notes']=$this->Dashboard_model->getTotalDeliveryNotesCount('',$user_id);
				$response['success']=True;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}

		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		}  


	}



	public function read_qr_code()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;

		$post_arr = $this->input->post();

		if ($post_arr['package_code'] && $user_id) {

			$this->load->model('Packages_model');

			$package_id = $this->Packages_model->getpackageIdByCode($post_arr['package_code']);

			$response['success']=True;
			$response['package_details']= $this->Packages_model->getPackagesDetails($package_id,true);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();


			

		}
	}



	public function delivery_details()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;

		$post_arr = $this->input->post();

		if ($post_arr['enc_id'] && $user_id) {

			$delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $post_arr['enc_id'] );

			if($delivery_id){

				$this->load->model('Delivery_model');
				$data['delivery_id']=$delivery_id;
				$response['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );
				$response['site_details']= $this->Base_model->getCompanyInformation();

				$response['success']=True;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
			$response['success']=false;
			$response['message']= 'Invalid Delivery id';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}
	}


	public function print_details()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;

		$post_arr = $this->input->post();


		if ($post_arr['package_code'] && $user_id) {

			$this->load->model('Packages_model');

			$package_id = $this->Packages_model->getpackageIdByCode($post_arr['package_code']);


			if($package_id){

				$details= $this->Packages_model->getPackagesDetails($package_id,true);

				$site_details= $this->Base_model->getCompanyInformation();

				// $response['html'] 
				// =$this->smarty->view("package_details_pdf.tpl", $details, TRUE);

				// print_r($response['html']);
				// die();
				$response['success']=True;
				$response['details']=$details;
				$response['site_details']=$site_details;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 
			}
			else{
				$response['success']=FALSE;
				$response['msg']='details not found';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}

		}
		$response['success']=FALSE;
		$response['msg']='please provide a package code';
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();
	}

	public function print_qr()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;

		$post_arr = $this->input->post();


		if ($post_arr['package_code'] && $user_id) {

			$this->load->model('Packages_model');

			$package_id = $this->Packages_model->getpackageIdByCode($post_arr['package_code']);


			if($package_id){

				$details['details']= $this->Packages_model->getPackagesDetails($package_id,true);

				$details['site_details']= $this->Base_model->getCompanyInformation();

				$response['html'] 
				=$this->smarty->view("qr_print.tpl", $details, TRUE);


				$response['success']=True;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 
			}
			else{
				$response['success']=FALSE;
				$response['msg']='details not found';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}

		}
		$response['success']=FALSE;
		$response['msg']='please provide a package code';
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();
	}

	public function print_delivery_details()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;

		$post_arr = $this->input->post();

		if ($post_arr['delivery_id'] && $user_id) {

			$delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $post_arr['delivery_id'] );
			if($delivery_id){
				$details['site_details']= $this->Base_model->getCompanyInformation();
				$this->load->model('Delivery_model');
				$data['delivery_id']=$delivery_id;
				$details['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );
				$response['html'] 
				=$this->smarty->view("print_delivery_details.tpl", $details, TRUE);

				// print_r($response['html']);
				// die();
				$response['success']=True;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}

			$response['success']=false;
			$response['message']= 'Invalid Delivery id';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}


		$response['success']=FALSE;
		$response['msg']='please provide a package code';
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();
	}

// 	public function print_delivery_qr()
// 	{
// 		$token = $this->check_header();  

// 		$user_id=$token->user_id;

// 		$post_arr = $this->input->post();

// 		if ($post_arr['delivery_id'] && $user_id) {

// 			$delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $post_arr['delivery_id'] );
// 			if($delivery_id){
// 				$details['site_details']= $this->Base_model->getCompanyInformation();
// 				$this->load->model('Delivery_model');
// 				$data['delivery_id']=$delivery_id;
// 				$details['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );

// 				$response['html'] 
// 				=$this->smarty->view("print_qr_delivery.tpl", $details, TRUE);


// 				$response['success']=True;
// 				$this->output
// 				->set_status_header(200)
// 				->set_content_type('application/json', 'utf-8')
// 				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
// 				->_display();
// 				exit();
// 			}
			
// 			$response['success']=false;
// 			$response['message']= 'Invalid Delivery id';
// 			$this->output
// 			->set_status_header(200)
// 			->set_content_type('application/json', 'utf-8')
// 			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
// 			->_display();
// 			exit();
// 		}


// 		$response['success']=FALSE;
// 		$response['msg']='please provide a package code';
// 		$this->output
// 		->set_status_header(200)
// 		->set_content_type('application/json', 'utf-8')
// 		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
// 		->_display();
// 		exit();
// 	}

	public function print_delivery_qr()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;

		$post_arr = $this->input->post();

		if ($post_arr['delivery_id'] && $user_id) {

			$delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $post_arr['delivery_id'] );
			if($delivery_id){
				$details['site_details']= $this->Base_model->getCompanyInformation();
				$this->load->model('Delivery_model');
				$data['delivery_id']=$delivery_id;
				$details['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );

				// unset($details['details']['packages']);

				$response['delivery_details']=$details;

				// $response['html'] 
				// =$this->smarty->view("print_qr_delivery.tpl", $details, TRUE);


				$response['success']=True;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}
			
			$response['success']=false;
			$response['message']= 'Invalid Delivery id';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}


		$response['success']=FALSE;
		$response['msg']='please provide a package code';
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();
	}


	public function delivery_list()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;

		$post_arr = $this->input->post();

		if ($user_id && $post_arr['status']) {

			

			$this->load->model('Delivery_model');


			$details_count=$this->Delivery_model->getDeliveryListApiCount($post_arr);
			


			$limit1 =10;
			$page = $post_arr['page_index'] *$limit1;

			$post_arr['page']=$page;
			$post_arr['limit1']=$limit1;
			$delivery_list=$this->Delivery_model->getDeliveryListApi($post_arr);
			$delivery_count=$this->Delivery_model->getDeliveryListApiCount($post_arr);

			$response['end']=false;
			if($post_arr['limit1'] >$delivery_count){
				$response['end']=true;
			}


			if($page==0){
				if($delivery_list){
					$response['success']= TRUE;
					$response['delivery_list']=$delivery_list; 
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 

				}
				else{
					$response['success'] = TRUE; 
					$response['msg'] = "No Details Found"; 


					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}


			}else{

				if($page<=$details_count){

					if($delivery_list){
						$response['success']= TRUE;
						$response['delivery_list'] = $delivery_list; 
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit();

					}
					else
					{
						$response['success'] = TRUE; 
						$response['msg'] = "no data"; 

						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit();
					}
				}
				else{
					$response['success'] = TRUE; 
					$response['msg'] = "no data"; 
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}
			}


		}
		$response['success']=FALSE;
		$response['msg']='please provide status';

		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();

	}

	public function projuct_list()
	{
		// $token = $this->check_header();  
		// $user_id=$token->user_id;
		$response['projucts']=$this->Base_model->getProjectIdAuto();
		$response['success']=True;
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();

	}

	public function update_location()
	{
		$token = $this->check_header();  
		$user_id=$token->user_id;
		$post = $this->input->post(); 
		if ($user_id && $post['id']) {

			$this->load->model('Delivery_model');
			$this->Delivery_model->begin();

			$delivery_id=$this->Base_model->encrypt_decrypt('decrypt',$post['id']);
			$location_details=$post['location_details'];
			$update = $this->Delivery_model->updateDeliveryLocation($location_details,$delivery_id);

			if ($update) {
				$this->Delivery_model->commit();
				$response['success'] = TRUE;
				$response['msg'] = 'Location Details updated!';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}
			else{

				$this->Delivery_model->rollback();
				$response['success'] = false;
				$response['msg'] = 'Error  while updating';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}
		}
		$response['success']=FALSE;
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();

	}


	public function update_delivery_status()
	{
		$token = $this->check_header();  
		$user_id=$token->user_id;

		if ($this->input->post() && $this->validate_update_delivery()) {
			
			
			$post_arr = $this->input->post();




			$delivery_id=$post_arr['id'];
			$package_ids=$post_arr['package_ids'];
			$db_ids=$post_arr['db_id'];



			if($delivery_id){
				$details['delivery_id']=$delivery_id;
				$this->form_validation->set_rules('delivery_id', 'Delivery Id', 'required|callback_checkDeliveryIdExist');
				$this->form_validation->set_rules('status', 'Status', 'required|in_list[partially_delivered,delivered]');

				$this->load->model('Delivery_model');

				$details = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );

				
				if (!$details) {
					$response['success']=FALSE;
					$response['msg']='Invalid Id';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}


				if($post_arr['package_ids']){

					
					$this->Delivery_model->begin();


					$update_delivery_status = $this->Delivery_model->updateDeliveryStatus( $delivery_id, $post_arr['status'] );


					$this->load->model('Mail_model');

					$mail_arr = array(
						'email' => $details['email'],
						'fullname' => $details['cus_name'],
						'user_id' => $details['user_id'],
						'delivery_code' => $details['code'],
						'project_code' => $details['project_code'],
						'project_name' => $details['project_name'],
						'status' => ucfirst(str_replace('_', ' ', $post_arr['status'])),
						'delivery_person' => $details['driver_name'],
					);
					if( $post_arr['status'] == 'partially_delivered' ){
						$mail_arr['subject'] = 'The listed packages are Delivered';
					}else{			
						$mail_arr['subject'] = 'Packages Delivered';	

					}



					$package_ids = isset($package_ids) ? explode(',', $package_ids) : [];
					$db_ids = isset($db_ids) ? explode(',', $db_ids) : [];

					$combined_ids = array_map(null, $package_ids, $db_ids); 

					foreach ($combined_ids as $ids) {
						$package_id = $ids[0];  
						$db_id = $ids[1];      
						$update_delivery_package_status = $this->Delivery_model->updateDeliveryPackageStatus($db_id, $post_arr['status'], $delivery_id);
					}

					if($update_delivery_package_status)
					{

						$this->load->model('Mail_model');

						$data['details']['status'] = $mail_arr['status'];
						$data['details']['project_package_status'] = $mail_arr['status'];

						
						$this->data['site_details']=$this->Base_model->getCompanyInformation();

						$mail_arr['attachmentString'] = $this->Delivery_model->generatePdf($details); 
						$mail_send=$this->Mail_model->sendEmails("update_delivery_status",$mail_arr);
						$this->Delivery_model->commit();

						$msg="Udpated Successfully";
						$response['success']=True;
						$response['msg']='updated';
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit();
					} else {

						$this->Delivery_model->rollback();
						$msg="Failed, please try again...";
						$response['success']=FALSE;
						$response['msg']=$msg;
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit();
					}

				}

			}

			$response['success']=FALSE;
			$response['error']=$this->form_validation->error_array();
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();



		}

		$response['success']=FALSE;
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();

	}

	public function package_names()
	{
		// $token = $this->check_header();  
		// $user_id=$token->user_id;
		$response['projucts']=$this->Base_model->getPackageIdAjax();
		$response['success']=True;
		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();

	}

	public function read_delivery_code()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;

		$post_arr = $this->input->post();

		if ($post_arr['package_code'] && $user_id) {

			$this->load->model('Packages_model');

			$package_id = $this->Packages_model->getpackageIdByCode($post_arr['package_code']);

			$response['success']=True;
			$response['package_details']= $this->Packages_model->getPackagesDetails($package_id,true);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();




		}
	}

	public function profile_update()
	{
		$token = $this->check_header();  
		$user_id=$token->user_id;
		$user_name=$this->Base_model->getUserName($user_id);

		try
		{

			if($user_id)
			{
				$post_arr=$this->input->post();


				$post_arr['file_name'] = NULL;

				$this->load->model('Member_model');

				$update_profile = $this->Member_model->updateUserProfile( $post_arr, $user_id );

				$response['success']=True;
				$response['msg']='Profile Updated';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
			$response['success']=false;
			$response['msg']= 'invalid User';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();





		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	public function searchDelivery()
	{
		$token = $this->check_header();  
		$user_id=$token->user_id;
		

		try
		{

			if($user_id)
			{
				$post_arr=$this->input->post();

				$deliverys = $this->Base_model->getProjectIdAuto($post_arr['term']);
				
				if ($deliverys) {

					$response['success']=True;
					$response['deliverys']=$deliverys;
					$response['msg']='Delivery Names';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
				else{
					$response['response']=$deliverys;
					$response['success']=True;
					$response['msg']='No deliverys found';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}


			}
			$response['success']=false;
			$response['msg']= 'invalid User';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();





		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}
	
		public function searchProjects()
	{
		$token = $this->check_header();  

		$user_id=$token->user_id;
		

		try
		{

			if($user_id)
			{
				$post_arr=$this->input->post();

				$projucts = $this->Base_model->getProjectNames($post_arr['term']);
				
				if ($projucts) {

					$response['success']=True;
					$response['projucts']=$projucts;
					$response['msg']='Project Names';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
				else{
					$response['response']=$projucts;
					$response['success']=True;
					$response['msg']='No projucts found';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}


			}
			$response['success']=false;
			$response['msg']= 'invalid User';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();





		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	public function profile_image()
	{
		$token = $this->check_header();  
		$user_id=$token->user_id;
		$user_name=$this->Base_model->getUserName($user_id);

		try
		{

			if($user_id)
			{


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


				$this->load->model('Member_model');
				$update_profile =  $this->Member_model->updateUserProfileImageApi($post_arr, $user_id );

				if ($update_profile) {
					$response['success']=True;
					$response['msg']='Profile Updated';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
				$response['success']=false;
				$response['msg']='Error on Updation';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();


			}
			$response['success']=false;
			$response['msg']= 'invalid User';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();





		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	public function profile()
	{
		$token = $this->check_header();  
		$user_id=$token->user_id;

		try
		{

			if($user_id)
			{
				$select_arr = ['first_name', 'user_photo', 'email', 'mobile',  'facebook', 'instagram', 'twitter'];
				$data['user_details'] = $this->Base_model->getUserDetails($user_id, $select_arr ); 
				$response['details']=$data['user_details'];		
				$response['success']=True;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}

			$response['success']=false;
			$response['msg']= 'invalid User';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 


	}


	public function code_reading()
	{


		$token = $this->check_header();  
		$user_id=$token->user_id;



		try
		{
			$post_arr=$this->input->post();

			if ($post_arr['code'] && $user_id) {


				if ( ($post_arr['type'] != 'package') && ($post_arr['type'] !='delivery')) {

					$response['success']=FALSE;
					$response['msg']='Invalid Type';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}

				if ($post_arr['type']=='package') {

					$this->load->model('Packages_model');

					$package_id = $this->Packages_model->getpackageIdByCode($post_arr['code']);
					$details=$this->Packages_model->getPackagesDetails($package_id,true);

					if (empty($details)) {
						$response['success']=false;
						$response['msg']='Invalid Code';
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit(); 
					}

					$response['success']=true;
					$response['package_details']=$details;
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}

				if ($post_arr['type']=='delivery') {

					$delivery_id=$post_arr['code'];
					$this->load->model('Delivery_model');

					$delivery_id = $this->Delivery_model->getDeliveryIdByCode($post_arr['code']);

					

					$details= $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );



					if (empty($details)) {

						$response['success']=false;
						$response['msg']='Invalid Id';
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit(); 

					}

					$response['success']=True;
					$response['details']=$details;
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();


				}






			}

			$response['success']=false;
			$response['msg']= 'Invalid Code';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 


	}



	public function change_credential()
	{
		$token = $this->check_header();  
		$user_id=$token->user_id;

		try
		{

			if($this->validate_change_credential())
			{

				$post_arr = $this->input->post();


				$this->config->load('bcrypt');
				$this->load->library('bcrypt');
				$new_password_hashed = $this->bcrypt->hash_password( $post_arr["new_password"] );
				$email=$this->Base_model->getUserInfoField('email',$user_id);
				$fullname=$this->Base_model->getUserInfoField('first_name',$user_id);

				$this->load->model('Member_model');
				if ( $this->Member_model->updatePassword( $new_password_hashed, $user_id )) 
				{
					$this->load->model('Mail_model');
					$mail_arr = array(
						'user_id' => $user_id,
						'password' => $post_arr["new_password"],
						'email' => $email,
						'fullname' => $fullname,
					);  

					$post_arr['ticket_id'] = $this->Mail_model->sendEmails("password",$mail_arr);

					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'update_password_admin', serialize($post_arr));

					$response['success']=True;
					$response['msg']= 'Password updated';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();


				} else {
					$msg = lang('error_on_password_updation');
					$response['success']=false;
					$response['msg']= 'Error on updation';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}

			}
			$response['success']=false;
			$response['msg']= $this->form_validation->error_array();
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 


	}





	protected function validate_change_credential(){

		$password_length = value_by_key('password_length');
		$this->form_validation->set_rules( 'new_password', lang('new_password'), 'trim|required');
		$this->form_validation->set_rules( 'confirm_password', lang( 'confirm_password' ), 'trim|required|min_length['. $password_length .']|matches[new_password]' );


		$result = $this->form_validation->run(); 
		return $result;
	}

	protected function validate_update_delivery(){


		$this->form_validation->set_rules('package_ids',lang('Package Id'),'trim|required');
		$this->form_validation->set_rules('status',lang('Status'),'trim|required');


		$result = $this->form_validation->run(); 

		if (!$result) {

			$response['success']=false;
			$response['msg']= $this->form_validation->error_array();
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}

		
		return $result;
	}

	public function checkDeliveryIdExist() {

		$delivery_id=$this->input->post('id');

		$exist = $this->Base_model->isDeliveryIdExist($delivery_id, 'all');


		$this->form_validation->set_message('checkDeliveryIdExist', 'Delivery ID not Exist');

		if($exist){
			return TRUE;
		}
		return FALSE;
	}




}
