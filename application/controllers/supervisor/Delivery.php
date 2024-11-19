<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}
	
	public function delivery_list($action='',$id='')
	{
		$this->load->model('Packages_model');
		$data['title']="Delivery List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			
			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']);

			if(element( 'package_id', $post_arr ))				
				$post_arr['package_name'] = $this->Base_model->getPackageName($post_arr['package_id']);

		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'send_to_delivery';
		}
		$post_arr['order'] = 'id';
		$post_arr['order_by'] = 'DESC';
		
		$post_arr['supervisor_id'] = log_user_id();
		$post_arr['packages'] = true;

		// $data['details'] = $this->Delivery_model->getDeliveryDetails($post_arr);
		$data['post_arr'] = $post_arr;

	
		
		if($action)
		{
			$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );	
			if($this->Packages_model->checkDeliveryPackage($data['id'])){

				$msg = "Failed..! Pacakge is added on a Delivery Note";
				$this->redirect($msg,'packages/package-list',false);
			}


			$details=$this->Packages_model->deletePackages($data['id']);
			if($details)
			{
				$msg="Deleted Successfully";
				$this->redirect($msg,'delivery/delivery-list',True);
			}
			else
			{
				$msg="Error on Deletion";
				$this->redirect($msg,'delivery/delivery-list',false);
			}
		}
		// print_r($data['details']);
		// die();
		$this->loadView($data);
	}

	public function checkDeliveryIdExist($delivery_id) {

		$exist = $this->Base_model->isDeliveryIdExist($delivery_id, 'all');

		$this->form_validation->set_message('checkDeliveryIdExist', 'Delivery note not Exist');

		if($exist){
			return TRUE;
		}
		return FALSE;
	}

	public  function read_delivery_code()
	{ 
		$this->redirect('','dashboard/index', true);

		$data['title']='Read the delivery code';

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			$delivery_id = $this->Delivery_model->getDeliveryIdByCode($post_arr['delivery_code']);

			if($delivery_id){
				$enc_id = $this->Base_model->encrypt_decrypt( 'encrypt', $delivery_id );

				$this->redirect('','delivery/delivery-details/'.$enc_id, FALSE);
			}else{

				$msg="Invalid code";
				$this->redirect($msg, 'delivery/read-delivery-code/', FALSE);
			}

		}
		$this->loadView($data);

	}
	public  function delivery_details($enc_id='')
	{ 
		$data['title']='Delivery Details';

		$data['enc_id']=$enc_id;
		if($enc_id){

			$_POST['delivery_id'] = $delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );


			if($delivery_id){
					$data['delivery_id']=$delivery_id;
				$this->form_validation->set_rules('delivery_id', 'Delivery Note', 'required|callback_checkDeliveryIdExist');
				$this->form_validation->set_rules('status', 'Status', 'required|in_list[partially_delivered,delivered]');

				$data['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );
				// print_r($data['details']);
				// die();
				if($this->form_validation->run() && $this->input->post('update_status')){

					$post_arr = $this->input->post();
					
					$this->Delivery_model->begin();
					$this->load->model('Mail_model');
					$update_delivery_status = $this->Delivery_model->updateDeliveryStatus( $delivery_id, $post_arr['status'] );


					$mail_arr = array(
						'email' => $data['details']['email'],
						'fullname' => $data['details']['cus_name'],
						'user_id' => $data['details']['user_id'],
						'delivery_code' => $data['details']['code'],
						'project_code' => $data['details']['project_code'],
						'project_name' => $data['details']['project_name'],
						'status' => ucfirst(str_replace('_', ' ', $post_arr['status'])),
						'delivery_person' => $data['details']['driver_name'],
					);
					if( $post_arr['status'] == 'partially_delivered' ){
						$mail_arr['subject'] = 'The listed packages are Delivered';
					}else{			
						$mail_arr['subject'] = 'Packages Delivered';	

					}


					if( element('delivery_package', $post_arr) ){
						$delivery_package_row_ids = array_keys($post_arr['delivery_package']);
					}else{
						$delivery_package_row_ids = [];

					}
					// if(empty($delivery_package_row_ids)){
					// 	$this->Delivery_model->rollback(); 
					// 	$msg="Failed, packages are empty... Please add atleast one package";
					// 	$this->redirect($msg,'delivery/delivery-details/'.$enc_id, FALSE);
					// }

					$update_delivery_package_status = TRUE;
					$update_package_status = TRUE;
					if(!empty($delivery_package_row_ids)){
						$update_delivery_package_status = $this->Delivery_model->updateDeliveryPackageStatus( $delivery_package_row_ids, 'delivered', $delivery_id );
						$update_package_status = $this->Delivery_model->updatePackageStatus( $delivery_package_row_ids, 'delivered' );
					}

					
					if($update_delivery_status && $update_package_status && $update_delivery_package_status)
					{

						$this->load->model('Mail_model');

						$data['details']['status'] = $mail_arr['status'];
						$data['details']['project_package_status'] = $mail_arr['status'];
						$mail_arr['attachmentString'] = $this->Delivery_model->generatePdf($data['details']); 
						$mail_send=$this->Mail_model->sendEmails("update_delivery_status",$mail_arr);
						
						
						$this->Delivery_model->commit();

						$msg="Udpated Successfully";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, TRUE);
					} else {

						$this->Delivery_model->rollback();
						$msg="Failed, please try again...";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, FALSE);
					}

				}


				$this->loadView($data);
			}else{
				show_error('Invalid Delivery Id', '404' );
			}
		}else{
			show_error('Delivery Id not exist', '404' );
		}

	}
		public  function print_delivery_details($enc_id='')
	{ 
		$data['title']='Delivery Details';

		$data['enc_id']=$enc_id;
		if($enc_id){

			$_POST['delivery_id'] = $delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );


			if($delivery_id){

				$this->form_validation->set_rules('delivery_id', 'Delivery Note', 'required|callback_checkDeliveryIdExist');
				$this->form_validation->set_rules('status', 'Status', 'required|in_list[partially_delivered,delivered]');

				$data['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );
				// print_r($data['details']);
				// die();
				if($this->form_validation->run() && $this->input->post('update_status')){

					$post_arr = $this->input->post();
					
					$this->Delivery_model->begin();
					$this->load->model('Mail_model');
					$update_delivery_status = $this->Delivery_model->updateDeliveryStatus( $delivery_id, $post_arr['status'] );


					$mail_arr = array(
						'email' => $data['details']['email'],
						'fullname' => $data['details']['cus_name'],
						'user_id' => $data['details']['user_id'],
						'delivery_code' => $data['details']['code'],
						'project_code' => $data['details']['project_code'],
						'project_name' => $data['details']['project_name'],
						'status' => ucfirst(str_replace('_', ' ', $post_arr['status'])),
						'delivery_person' => $data['details']['driver_name'],
					);
					if( $post_arr['status'] == 'partially_delivered' ){
						$mail_arr['subject'] = 'The listed packages are Delivered';
					}else{			
						$mail_arr['subject'] = 'Packages Delivered';	

					}


					if( element('delivery_package', $post_arr) ){
						$delivery_package_row_ids = array_keys($post_arr['delivery_package']);
					}else{
						$delivery_package_row_ids = [];

					}
					// if(empty($delivery_package_row_ids)){
					// 	$this->Delivery_model->rollback(); 
					// 	$msg="Failed, packages are empty... Please add atleast one package";
					// 	$this->redirect($msg,'delivery/delivery-details/'.$enc_id, FALSE);
					// }

					$update_delivery_package_status = TRUE;
					$update_package_status = TRUE;
					if(!empty($delivery_package_row_ids)){
						$update_delivery_package_status = $this->Delivery_model->updateDeliveryPackageStatus( $delivery_package_row_ids, 'delivered', $delivery_id );
						$update_package_status = $this->Delivery_model->updatePackageStatus( $delivery_package_row_ids, 'delivered' );
					}

					
					if($update_delivery_status && $update_package_status && $update_delivery_package_status)
					{

						$this->load->model('Mail_model');
						$mail_send=$this->Mail_model->sendEmails("update_delivery_status",$mail_arr);
						
						$this->Delivery_model->commit();

						$msg="Udpated Successfully";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, TRUE);
					} else {

						$this->Delivery_model->rollback();
						$msg="Failed, please try again...";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, FALSE);
					}

				}


				$this->loadView($data);
			}else{
				show_error('Invalid Delivery Id', '404' );
			}
		}else{
			show_error('Delivery Id not exist', '404' );
		}

	}
	public function reports()
	{

		$data['title']="Reports";
		$post_arr =[];
		if ($this->input->post('search')) {
			$post_arr = $this->input->post(); 
			$this->load->model('Packages_model'); 
			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Packages_model->getProjectName($post_arr['project_id']);

			if(element( 'package_id', $post_arr ))				
				$post_arr['package_name'] = $this->Packages_model->getPackageName($post_arr['package_id']);
		} 

		$post_arr['supervisor_id']=log_user_id();
		$data['details'] = $this->Delivery_model->getDeliveryDetails($post_arr);
		// print_r($data['details']);die();
		$data['post_arr'] = $post_arr;
		
		
		$this->loadView($data);
	}
	function create()
	{  
		$data['title'] = lang('create');  


		if($this->input->post('submit'))
		{
			$post=$this->input->post();


			$this->Delivery_model->begin();

			$post['user_id'] = log_user_id();


			$result = $this->Delivery_model->addDeliveryNote($post);
			
			if($result)
			{
				$package_id = $this->Base_model->encrypt_decrypt('encrypt', $result);
				$this->Delivery_model->commit();

				$msg = "Added Successfully";
				$this->redirect($msg,'delivery/add-delivery-items/'.$package_id,True);
			}
			else
			{
				$this->Delivery_model->rollback();
				$msg = "Error on adding";
				$this->redirect($msg,'delivery/create',False);
			}

		}
		// print_r($this->form_validation->error_array());


		$this->loadView($data);
	}


	public function get_delivery_ajax() {

		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();

			$count_without_filter = $this->Delivery_model->getDeliveryCount(log_user_id(),log_user_type());
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
		function save_location_details() {

		if ($this->input->is_ajax_request() ) {

			

			$post = $this->input->post(); 
			$this->Delivery_model->begin();

			$delivery_id=$post['id'];
			$location_details=$post['location_details'];
			
			$update = $this->Delivery_model->updateDeliveryLocation($location_details,$delivery_id);
			if ($update) {

				
				$this->Delivery_model->commit();
				$response['success'] = TRUE;
				$response['msg'] = 'Location Details updated!';
				$this->set_session_flash_data( $response['msg'], $response['success']  );
				
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
			else{
				$response['success'] = FALSE;
				$response['msg'] = 'Failed..! Please check the inputs';
				$this->set_session_flash_data( $response['msg'], $response['success']  );
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}


}
