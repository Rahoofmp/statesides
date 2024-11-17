<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once './libs/phpqrcode/qrlib.php';
class Sample extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function add($enc_id='')
	{  
		$data['title'] = lang('Add');  
		if ($enc_id) {
			$data['title'] = 'Edit Sample';
			$data['id']=$id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$data['edit_item']=	$this->Sample_model->getAllSampleDetails($id);
		}
		$data['code']=$this->Sample_model->getSampleCodeAuto()+1001;
		if($this->input->post('delete'))
		{
			$post=$this->input->post();
			$delete = FALSE;
			if($post){
				$image_id = $post['img_id'];

				foreach ($post['images'] as $key => $image_id) {
					$delete=$this->Sample_model->deleteItemImage($image_id);
				}
					// print_r($image_id);die();
				$enc_id=$this->Base_model->encrypt_decrypt('encrypt',$id);
				if ($delete) {
					$this->redirect("Image Deleted","sample/add/$enc_id",TRUE);
				} else{
					$this->redirect("Image Deletion Failed","sample/add/$enc_id",False);

				}
			}
		}
		if($this->input->post('submit')=='add_item')
		{
			if($this->validate_sample_items())
			{ 
				$post=$this->input->post(); 
				$this->Sample_model->begin();
				$post['user_id'] = log_user_id(); 
				$result = $this->Sample_model->addSample($post);
				if(isset($post['code'])){
					$enc_id=$this->Base_model->encrypt_decrypt('encrypt',$result);

					$tempDir = './assets/images/qr_code/sample/';  
					$codeContents = urlencode(base_url('login/sample_master_details/
						'.$enc_id));
					QRcode::png($codeContents, $tempDir.''.$post['code'].'.png', QR_ECLEVEL_L, 5); 
				}
				if($result)
				{
					if ($_FILES['upload_file']['error'] != 4) {
						$files=$_FILES;
						$count=count($_FILES["upload_file"]["name"]);

						for($i=1; $i< $count; $i++)
						{
							$_FILES['upload_file']['name']= $files['upload_file']['name'][$i];
							$_FILES['upload_file']['type']= $files['upload_file']['type'][$i];
							$_FILES['upload_file']['tmp_name']= $files['upload_file']['tmp_name'][$i];
							$_FILES['upload_file']['error']= $files['upload_file']['error'][$i];
							$_FILES['upload_file']['size']= $files['upload_file']['size'][$i]; 
							if ($_FILES['upload_file']['name']=='') {
								$this->Sample_model->addSampleImages($result, 'no-image.png');
							}  else{
								$file_name='no-image.png';

								$config['upload_path'] = './assets/images/sample/';
								$config['allowed_types'] = 'gif|jpg|png|jpeg';
								$config['max_size'] = '2000000';
								$config['remove_spaces'] = true;
								$config['overwrite'] = false;
								$config['encrypt_name'] = TRUE;

								$this->load->library('upload', $config);
								$msg = '';
								if (!$this->upload->do_upload('upload_file')) {


									$error = $this->upload->display_errors();

									$this->redirect( $error, "sample/add", false );
								} else {
									$image_arr = $this->upload->data();  
									$file_name=$image_arr['file_name'];
									$this->Sample_model->addSampleImages($result,$file_name);
								}
							}
						}
					}else{
						$this->Sample_model->addSampleImages($result, 'no-image.png');

					}
					$this->Sample_model->commit();
					$msg = "Added Successfully";
					$this->redirect($msg,'sample/add',True);
				}
				else
				{
					$this->Sample_model->rollback();
					$msg = "Error on adding";
					$this->redirect($msg,'sample/add',False);
				}
			}

			if(element('main_category',$this->input->post())){
				$edit_item['category']=$this->input->post("main_category");
				$edit_item['category_name']=$this->Base_model->getCategoryName($edit_item['category']);
				// print_r($edit_item['category_name']);die();
				$data['edit_item']=	$edit_item;
			}
			if(element('supplier',$this->input->post())){
				$edit_item['supplier']=$this->input->post("supplier");
				$edit_item['supplier_name']=$this->Base_model->getSupplierName($edit_item['supplier']);
				// print_r($edit_item['supplier_name']);die();
				$data['edit_item']=	$edit_item;
			}
		}


		if($this->input->post('submit')=='update_item' && $this->validate_sample_items())
		{
			$post=$this->input->post();
			// print_r($post);die();

			$this->load->model('Material_model');
			$this->Sample_model->begin();
			if ($post['code']!=$data['edit_item']['code']) {
				$checkunique=$this->Material_model->getItemIdByCode($post['code']);
				if ($checkunique) {
					$this->redirect("Item Code should be unique","sample/add/$enc_id",FALSE);
				}
			}
			$post['user_id'] = log_user_id();
			$result = $this->Sample_model->updateSample($post,$id);
			// print_r($id);die();
			$files=$_FILES;
			$config['upload_path'] = './assets/images/sample/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '2000000';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = TRUE;

			$this->load->library('upload', $config);

			for($i=0; $i<count($files["upload_file"]["name"]);$i++)
			{
				$file_name=NULL;
				if($files['upload_file']['error'][$i]!=4)
				{
					$_FILES['upload_file']['name']= $files['upload_file']['name'][$i];
					$_FILES['upload_file']['type']= $files['upload_file']['type'][$i];
					$_FILES['upload_file']['tmp_name'] = $files['upload_file']['tmp_name'][$i];
					$_FILES['upload_file']['error']= $files['upload_file']['error'][$i]; 
					$_FILES['upload_file']['size'] = $files['upload_file']['size'][$i];


					$msg = '';

					$this->upload->initialize($config);

					if (!$this->upload->do_upload('upload_file')) {
						$error = $this->upload->display_errors();
						$this->redirect( $error, "showroom/add", false );
					}else{

						$image_arr = $this->upload->data();  
						$file_name=$image_arr['file_name'];
						// print_r($file_name);die();
						$this->Sample_model->addSampleImages($id,$file_name);
					}
				}
			}
			$this->Sample_model->commit();
			$msg = "Updated Successfully";
			$this->redirect($msg,'sample/list',True);
		}
		
		$this->loadView($data);
	}
	private function validate_sample_items() 
	{
		if ($_POST['submit']=='update_item') {
			$this->form_validation->set_rules('code', 'Item Code', 'required');
		}
		else
			$this->form_validation->set_rules('code', 'Item Code', 'required|is_unique[items.code]');
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('main_category', 'Item Category', 'required');
		$this->form_validation->set_rules('cost', 'Item Cost', 'required|numeric');
		$this->form_validation->set_rules('price', 'Selling Price', 'required|numeric');
		$this->form_validation->set_rules('supplier', 'Supplier Name', 'required');
		$this->form_validation->set_rules('paint_code', 'Paint Code', 'required');
		$this->form_validation->set_rules('size', 'Size', 'required|trim');
		$this->form_validation->set_rules('brand', 'Brand', 'required');
		$this->form_validation->set_rules('origin', 'Origin', 'required');

		$this->form_validation->set_rules('type', 'Type of material', 'required');
		$this->form_validation->set_rules('grade', 'Grade', 'required');

		$result =  $this->form_validation->run();
		return $result;
	}

	public function list($action='',$id='')
	{ 
		$data['title']="Sample List";
		if($action)
		{
			$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );	
			$this->Sample_model->begin();
			$details=$this->Sample_model->deleteSample($data['id']);

			if($details)
			{
				$this->Sample_model->commit();
				$msg="Deleted Successfully";
				$this->redirect($msg,'sample/list',True);
			}
			else
			{
				$this->Sample_model->rollback();
				$msg="Error on Deletion";
				$this->redirect($msg,'sample/list',false);
			}
		}

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			if(element( 'item_id', $post_arr ))	{ 
				$post_arr['code'] = $this->Sample_model->getSampleCode( 'code', $post_arr['item_id']); 
			}	 
			if(element( 'category_id', $post_arr ))	{ 
				$post_arr['category_name'] = $this->Base_model->getCategoryName( $post_arr['category_id']); 
			}	 
			// print_r($post_arr);
		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = '1';
			$post_arr['type'] = 'all';
		}
		$post_arr['images'] = TRUE;;
		$data['post_arr']= $post_arr;
		// print_r($data['post_arr']);die();

		$this->loadView($data);
	}
	public function get_sample_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$post_arr['items']=true;
			$count_without_filter = $this->Sample_model->getSampleCount();
			$count_with_filter = $this->Sample_model->getSampleAjax($post_arr, 1);
			$details = $this->Sample_model->getSampleAjax( $post_arr,'');
			// print_r($details);die();
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $details,
			);
			echo json_encode($response);
		} 
	}
	public  function sample_details($enc_id='')
	{
		$data['title']='Sample Details';

		
		if($enc_id){
			$id=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );

			$data['details']= $this->Sample_model->getAllSampleDetails($id, true);
			print_r($data['details']);
			// die();
		}else{
			$this->redirect('Invalid ID','sample/sample_details',FALSE);
		}
		$this->loadView($data);
	}
}