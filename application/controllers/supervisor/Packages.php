<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}
  

	public  function package_details($enc_id)
	{
		$data['title']='Package Details';
		if($enc_id)
		{
			$id=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );
			$data['details']= $this->Packages_model->getPackagesDetails($id,true);
		}
		$this->loadView($data);
	} 

	public  function read_package_code()
	{ 
		$data['title']='Read the package code';

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			$package_id = $this->Packages_model->getpackageIdByCode($post_arr['package_code']);
		

			if($package_id){
				$enc_id = $this->Base_model->encrypt_decrypt( 'encrypt', $package_id );

				$this->redirect('','packages/package-details/'.$enc_id, FALSE);
			}else{

				$msg="Invalid code";
				$this->redirect($msg, 'packages/read-package-code/', FALSE);
			}

		}
		$this->loadView($data);

	}

	public  function create_leads()
	{ 
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

			if (element('advance_amount',$post_arr) && element('total_amount',$post_arr)) {

								
				$post_arr['due_amount']=$post_arr['total_amount']-$post_arr['advance_amount'];
			}
			else{
				$post_arr['due_amount']=0;
				$post_arr['advance_amount']=0;
				$post_arr['total_amount']=0;
			}

			

			$create_lead =  $this->Packages_model->createLeads($post_arr);

			

			if($create_lead)
			{
				$this->redirect( 'Lead created successfully', "packages/create-leads", true );
			}
			else{
				$this->redirect( 'Error on creating lead', "packages/create-leads", false );
			}

			

		}
		$this->loadView($data);

	}

	protected function validate_leads( ){

		$this->form_validation->set_rules('sales_man', lang('sales_man'), 'required');
		$this->form_validation->set_rules('first_name', lang('first_name'), 'required');
		$this->form_validation->set_rules('last_name', lang('last_name'), 'required');
		$this->form_validation->set_rules('mobile', lang('mobile'), 'required');
		$this->form_validation->set_rules('gender', lang('gender'), 'required');
		$this->form_validation->set_rules('date', lang('date'), 'required');
		$this->form_validation->set_rules('emmigration', lang('emmigration'), 'required');

		$result = $this->form_validation->run();
		return $result;


	}


	

}
