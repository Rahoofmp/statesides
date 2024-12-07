<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Website extends Base_Controller 
{

	function __construct() {
		parent::__construct(); 
		
	}
	public function index()
	{
		$data['title'] = 'Home';
		$this->loadView($data);
	}
	public function terms_and_conditions()
	{
		$data['title'] = 'terms and conditions';
		$this->loadView($data);
	}

	public function customer_registration()
	{

		$data['title']='Create Leads';
		if (log_user_id()) {
			
			$data['user_name']=$this->Base_model->getUserName(log_user_id());
		}

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

			// if($_FILES['ss_cirtifcate']['error']!=4)
			// {


			// 	$this->load->library('upload', $config);
			// 	$msg = '';
			// 	if (!$this->upload->do_upload('ss_cirtifcate')) {
			// 		$msg = lang('image_not_selected');
			// 		$error = $this->upload->display_errors();

			// 		$this->redirect( $error, "customer-registration", false );
			// 	} else {
			// 		$image_arr = $this->upload->data();  
			// 		$post_arr['ss_cirtifcate']=$image_arr['file_name'];
			// 	}
			// }



			
			// if($_FILES['police_clearence']['error']!=4)
			// {
			// 	$this->load->library('upload', $config);


			// 	$msg = '';
			// 	if (!$this->upload->do_upload('police_clearence')) {
			// 		$msg = lang('image_not_selected');
			// 		$error = $this->upload->display_errors();
			// 		$this->redirect( $error, "customer-registration", false );
			// 	} else {
			// 		$image_arr = $this->upload->data();  
			// 		$post_arr['police_clearence']=$image_arr['file_name'];
			// 	}
			// }


			// if($_FILES['job_cirtificate']['error']!=4)
			// {
			// 	$this->load->library('upload', $config);


			// 	$msg = '';
			// 	if (!$this->upload->do_upload('job_cirtificate')) {
			// 		$msg = lang('image_not_selected');
			// 		$error = $this->upload->display_errors();
			// 		$this->redirect( $error, "customer-registration", false );
			// 	} else {
			// 		$image_arr = $this->upload->data();  
			// 		$post_arr['job_cirtificate']=$image_arr['file_name'];
			// 	}
			// }


			// if($_FILES['passport_copy']['error']!=4)
			// {
			// 	$this->load->library('upload', $config);

			// 	$msg = '';
			// 	if (!$this->upload->do_upload('passport_copy')) {
			// 		$msg = lang('image_not_selected');
			// 		$error = $this->upload->display_errors();
			// 		$this->redirect( $error, "customer-registration", false );
			// 	} else {
			// 		$image_arr = $this->upload->data();  
			// 		$post_arr['passport_copy']=$image_arr['file_name'];
			// 	}
			// }

			// if($_FILES['dob_certificate']['error']!=4)
			// {
			// 	$this->load->library('upload', $config);

			// 	$msg = '';
			// 	if (!$this->upload->do_upload('dob_certificate')) {
			// 		$msg = lang('image_not_selected');
			// 		$error = $this->upload->display_errors();
			// 		$this->redirect( $error, "customer-registration", false );
			// 	} else {
			// 		$image_arr = $this->upload->data();  
			// 		$post_arr['dob_certificate']=$image_arr['file_name'];
			// 	}
			// }

			if (log_user_id()) {
				$post_arr['created_by']=$this->Base_model->getLoginInfoField('sub_id',log_user_id());
				$post_arr['salesman_id']=log_user_id();

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
				
			}

			$this->load->model('Packages_model');
			$this->Packages_model->begin();
			$this->Website_model->begin();
			

			$create_lead =  $this->Website_model->customerRegister($post_arr);

			


			if($create_lead)
			{
				if (element('source_user',$post_arr)) {

					$post_arr['insert_id']=$create_lead;
					$this->Packages_model->insertSource($post_arr);
					$this->Packages_model->commit();
					$this->Website_model->commit();
				}

				if (log_user_id()) {

					$this->redirect( 'Registration completed', "dashboard", true );
				}
				

				$this->redirect( 'Registration completed, our team will contact you soon', "customer-registration", true );
			}
			else{
				$this->redirect( 'Error on Registration', "customer-registration", false );
			}

			

		}
		$data['title'] = 'Customer Registration';
		$this->loadView($data);
	}


	protected function validate_leads( ){

		
		$this->form_validation->set_rules('first_name', lang('first_name'), 'required');
		$this->form_validation->set_rules('last_name', lang('last_name'), 'required');
		$this->form_validation->set_rules('mobile', lang('mobile'), 'trim|required');
		$this->form_validation->set_rules('gender', lang('gender'));
		$this->form_validation->set_rules('email', lang('email'),'trim|required|is_unique[customer_info.email]');
		
		


		$result = $this->form_validation->run();
		return $result;


	}


}

