<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function add_package($action='',$id='')
	{

		$data['title'] = "Add Packages"; 
		if($this->input->post('submit') && $this->validate_add_package())
		{
			$post_arr=$this->input->post();
						// print_r($post_arr);die();

			$post_arr['file_name'] = 'no-image.png';
			$post_arr['user_id'] = log_user_id();

			if($_FILES['userfile']['error']!=4)
			{
				$config['upload_path'] = './assets/images/package_pic/';
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
					$this->redirect( $error, "packages/add_package", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['file_name']=$image_arr['file_name'];
				}
			}
			$this->Packages_model->begin();
			$this->load->model('Mail_model');

			$details=$this->Packages_model->insertProjectPackages($post_arr);
			$id=$post_arr['name'];
			$package=$post_arr['package'];
			$project_details=$this->Packages_model->getProjectDetails($id);
			$customer_email_id=$project_details['email'];
			$customer_name=$project_details['customer_name'];
			$project_name=$project_details['project_name'];
			// $mail_arr = array(
			// 	'email' => $customer_email_id,
			// 	'fullname' => $customer_name,
			// 	'package' => $package,
			// 	'project_name' => $project_name,
			// );
			$package_id=$this->Base_model->encrypt_decrypt('encrypt', $details);
			if($details)
			{
				$this->Packages_model->commit();
				// $mail_send=$this->Mail_model->sendEmails("package_create",$mail_arr);
				$msg="Inserted Successfully";
				$this->redirect($msg,'packages/add-package-items/'.$package_id,True);
			}
			else
			{
				$this->Packages_model->rollback();
				$msg="Error on insertion";
				$this->redirect($msg,'packages/add-package',False);
			}

		}

		
		$this->loadView($data);
	}

	function edit_package($id)
	{
		$data['title'] = lang('edit_packages'); 

		$data['enc_id']= $id;
		$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );
		$data['details']= $this->Packages_model->getPackagesDetails($data['id']);
		// print_r($data['details']);die();
		
		if($this->input->post('info_update'))
		{
			$post_arr=$this->input->post();
			// print_r($post_arr);die();

			$post_arr['file_name'] = NULL;

			if($_FILES['userfile']['error']!=4)
			{
				$config['upload_path'] = './assets/images/package_pic/';
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
					$this->redirect( $error, "packages/add_package", false );
				} else {
					$image_arr = $this->upload->data();  
					$post_arr['file_name']=$image_arr['file_name'];
				}
			} 

			$update=$this->Packages_model->updatePackages($data['id'],$post_arr);


			if($update)
			{
				$msg="Updated Successfully";
				$this->redirect($msg,'packages/edit-package/'.$id,True);
			}
			else
			{
				$msg="Error on updation";
				$this->redirect($msg,'packages/edit-package/'.$id,False);
			}
		} 
		// print_r($data['details']);
		// die();
		$this->loadView($data);
	}


	function user_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);
			// die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Packages_model->getUserIdAuto($post['q']);
			echo json_encode($json);
		}
	}


	function validate_add_package() 
	{
		$this->form_validation->set_rules('name', 'Project Name', 'trim|required');
		$this->form_validation->set_rules('package', 'Package', 'trim|required');
		$this->form_validation->set_rules('package_location', 'Location', 'trim|required');
		$this->form_validation->set_rules('area_master', 'Area Master Name', 'trim|required');
		$this->form_validation->set_rules('item', 'Item', 'trim|required');
		$result =  $this->form_validation->run();

		return $result;
	}

	public function add_package_items($enc_id='')
	{
		$data['title']="Project Details";
		
		$data['enc_id']=$enc_id;

		$package_id=$this->Base_model->encrypt_decrypt('decrypt', $enc_id);
		$project_details=$this->Packages_model->getProjectInfo($package_id);

		if($this->input->post('submit'))
		{
			$post=$this->input->post();
			// $insert=$this->Packages_model->insertPackageItems($post,$package_id);
			if($insert)
			{
				$msg="Inserted Successfully";
				$this->redirect($msg,'packages/add-package',True);
			}

			else
			{
				$msg="Error on Insertion";
				$this->redirect($msg,'packages/add-package',false);
			}
		}
		$data['project_details']=element(0,$project_details);


		$this->loadView($data);
	}

	public function package_list($action='',$id='')
	{

		$data['title']="Package List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			
			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']);

			if(element( 'package_id', $post_arr ))				
				$post_arr['package_name'] = $this->Base_model->getPackageName($post_arr['package_id']);
			if(element( 'type_id', $post_arr ))				
				$post_arr['areamaster_name'] = $this->Packages_model->getAreaMaster('name',$post_arr['type_id']);
			if(element( 'item_id', $post_arr ))				
				$post_arr['item_name'] = $this->Base_model->getItemName($post_arr['item_id']);

		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'pending';
		}

		$post_arr['items'] = TRUE;
		$post_arr['order'] = 'id';
		$post_arr['order_by'] = 'DESC';

		// $data['project'] = $this->Packages_model->getPackageDetail($post_arr);
		// print_r($data['project']);die();
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
				$this->redirect($msg,'packages/package-list',True);
			}
			else
			{
				$msg="Error on Deletion";
				$this->redirect($msg,'packages/package-list',false);
			}
		}
		$this->loadView($data);
	}
	public function get_package_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$post_arr['items']=true;

		$post_arr['order'] = 'id';
		$post_arr['order_by'] = 'DESC';


			
			$count_without_filter = $this->Packages_model->getPackageCount();
			$count_with_filter = $this->Packages_model->getPackageDetailAjax($post_arr, 1);
			$details = $this->Packages_model->getPackageDetailAjax( $post_arr,'');
			// print_r($count_with_filter);die();
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $details,
			);

			echo json_encode($response);
		} 
	}

	public  function package_details($enc_id='')
	{
		$data['title']='Package Details';

		
		if($enc_id){
			$id=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );

			$data['details']= $this->Packages_model->getPackagesDetails($id, true);
			// print_r($data['details']);
			// die();
		}else{
			$this->redirect('Invalid ID','package/package-details',FALSE);
		}
		$this->loadView($data);
	}
	public  function reports()
	{
		$data['title']='Reports';

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			if(element( 'packager_id', $post_arr ))				
				$post_arr['packager_name'] = $this->Packages_model->getUserName($post_arr['packager_id']);
			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']);

			if(element( 'package_id', $post_arr ))				
				$post_arr['package_name'] = $this->Base_model->getPackageName($post_arr['package_id']);

		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'pending';
		}

		$data['project'] = $this->Packages_model->getPackageDetails($post_arr);
		// print_r($data['project']);die();
		$data['post_arr'] = $post_arr;
		$this->loadView($data);
	}

	public function get_package_ajax() {
		$this->load->model('Report_model');
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Report_model->getPackagesCount();
			$count_with_filter = $this->Report_model->getPackageDtailsAjax($post_arr, 1);
			$result_data = $this->Report_model->getPackageDtailsAjax($post_arr);
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
		}
	}



	function save_items() {

		if ($this->input->is_ajax_request() ) {
			// print_r('$post');
			// die();
			if ( $this->validate_save_items() ) {
				// print_r($post);
				$post = $this->input->post();

				foreach ($post['data'] as $key => $value) {

					$inserted = $this->Packages_model->insertPackageItems( $post['package_id'], $value );

					if(!$inserted){
						$response['success'] = FALSE;
						$response['msg'] = 'Failed..! Please try again';
						$this->set_session_flash_data( $response['msg'], $response['success']  );
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit();
					}
				} 

				$response['success'] = TRUE;
				$response['msg'] = 'Successfully added the items...!';
				$this->set_session_flash_data( $response['msg'], $response['success']  );
				
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}else{
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

	private function validate_save_items() { 
		
		if(element('data', $this->input->post())){
			$_POST['data'] = json_decode($this->input->post('data'));
			$_POST['package_id'] =  $this->Base_model->encrypt_decrypt('decrypt', $_POST['id']);
			unset($_POST['id']);
			
			foreach ($_POST['data'] as $key => $value) {
				$_POST['data'][$key]['serial_no'] = $value['1'];
				$_POST['data'][$key]['name'] = $value['2'];
				$_POST['data'][$key]['qty'] = $value['3'];
				unset($_POST['data'][$key]['0']);
				unset($_POST['data'][$key]['1']);
				unset($_POST['data'][$key]['2']);
				unset($_POST['data'][$key]['3']);
				unset($_POST['data'][$key]['4']);

				$this->form_validation->set_rules('data[]', 'Data', 'required'); 
			}
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}
		$this->form_validation->set_rules('package_id', 'Package ID', 'required|callback_checkUPackageExist');
		$res = $this->form_validation->run();

		return $res;
	}

	public  function checkUPackageExist($package_id) {

		$exist = $this->Base_model->isPackageExist($package_id);

		$this->form_validation->set_message('checkUPackageExist', 'Package Id not Exist');

		if($exist){
			return TRUE;
		}
		return FALSE;
	}

	public function remove_package_item($id, $package_id)
	{
		$item_id = $this->Base_model->encrypt_decrypt( 'decrypt', $id );	
		$details = $this->Packages_model->deletePackageItem($item_id);
		if($details)
		{
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'packages/edit-package/'.$package_id,True);
		}
		else
		{
			$msg="Error on Deleting Item";
			$this->redirect($msg,'packages/edit-package/'.$package_id,false);
		}
		$this->loadView($data);
	}



	public  function read_package_code()
	{ 
		$data['title']='Read the package code';

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			$package_id = $this->Packages_model->getpackageIdByCode($post_arr['package_code']);
// print_r($post_arr); die();
			

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


	function type_master($enc_id='',$action='')
	{
		if($this->input->post('create') && $this->validate_add_typemaster())
		{
			$post_arr = $this->input->post();
			$insert = $this->Packages_model->insertTypeMaster($post_arr);
			if($insert)
			{
				$msg = 'Created Successfully';
				$this->redirect("<b>$msg </b>", "packages/type-master", TRUE);
			}
			else
			{
				$msg = 'Failed';
				$this->redirect("<b>$msg </b>", "packages/type-master",FALSE);
			}
			
		}
		if ($this->input->post('update') && $this->validate_add_typemaster()) {
			$post_arr = $this->input->post();
			$update = $this->Packages_model->updateTypeMaster($post_arr);
			if($update)
			{
				$msg='Updated  Successfully';
				$this->redirect("<b> $msg </b>","packages/type-master");
			}
			else
			{
				$msg = 'Failed On Updation';
				$this->redirect("<b> $msg</b>","packages/type-master");
			}
			
		}
		if($enc_id)
		{
			$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$data['master_detail'] = $this->Packages_model->getTypeMasterDetails($id);
			$data['id'] = $id;
			
		}
		if($action == 'delete')
		{
			$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$delete = $this->Packages_model->updateTypeMaster('',$id);
			if($delete)
			{
				
				$msg = 'Successfully deleted ';
				$this->redirect($msg,'packages/type-master',TRUE);
			}
			else
			{
				$msg = 'Error on deletion';
				$this->redirect($msg,'packages/type-master',FALSE);
			}
		}

		$data['details'] = $this->Packages_model->getTypeMasterDetails();
		$data['title'] ="Type/Area Master";
		$this->loadView($data);
	}

	function validate_add_typemaster()
	{
		$this->form_validation->set_rules('name','Name','required');
		$result = $this->form_validation->run();
		return $result;
	}



}
