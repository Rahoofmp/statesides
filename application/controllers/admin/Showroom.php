<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once './libs/phpqrcode/qrlib.php';

class Showroom extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}
	public function meeting_mint($action='',$id='')
	{
		$data['title']="Meeting Minutes";
		if ($this->input->post('search')){
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			if(element( 'meeting_id', $post_arr ))				
				$post_arr['code'] = $this->Base_model->getMeetingSampleCode($post_arr['meeting_id']);
		}
		else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'active';
			$post_arr['type'] = 'all';
		}
		$post_arr['items'] = TRUE;
		$post_arr['order'] = 'id';
		$post_arr['order_by'] = 'DESC';
		$data['post_arr'] = $post_arr;
		$this->loadView($data);
	}
	function create($action='',$id='')
	{

				// print_r($id);die();
		$data['title'] = "Create"; 
		$this->load->model('Sales_model');
		$this->load->model('Member_model');
		$data['vat'] = $this->Member_model->getVatDetails();
		$data['code']=$this->Showroom_model->getMaxMeetingId()+1001;
		if($this->input->post('submit') )
		{
			// die();
			if($this->validate_add_minutes())
			{ 
				$post_arr=$this->input->post();
				$this->Showroom_model->begin();
				$result=$this->Showroom_model->insertMeeting($post_arr);
				$enc_id=$this->Base_model->encrypt_decrypt('encrypt',$result);
				if(isset($post_arr['code'])){
					$tempDir = './assets/images/qr_code/meeting/';  
					$codeContents = urlencode(base_url('login/showroom-items-print/'.$enc_id));
					QRcode::png($codeContents, $tempDir.''.$post_arr['code'].'.png', QR_ECLEVEL_L, 5); 
				}
				if ($result) {

					$this->Showroom_model->commit();
					$enc_id=$this->Base_model->encrypt_decrypt('encrypt',$result);
					$this->redirect('Added Successfully',"showroom/add-items/$enc_id",TRUE);
					
				}
				else{
					$this->Sales_model->rollback();
					$this->redirect("Insertion Failed","showroom/create",FALSE);
				}
				
			}
// print_r($enc_id);die();
			if(element('customer_name',$this->input->post())){
				$post_arr['customer_name']=$this->input->post("customer_name");
				$post_arr['customer_name']=$this->Base_model->getCustomerName($post_arr['customer_name']);
				$data['post_arr']=	$post_arr;
			}
			if(element('sales_name',$this->input->post())){
				$post_arr['sales_name']=$this->input->post("sales_name");
				$post_arr['sales_name']=$this->Base_model->getUserName($post_arr['sales_name']);
				$data['post_arr']=	$post_arr;
			}

			if(element('user_name',$this->input->post())){
				$post_arr['user_name']=$this->input->post("user_name");
				$count = count($post_arr['user_name']);
				for($i=1 ; $i<$count; $i++)
				{

					$post_arr['user_name'][$i]=$this->Base_model->getSalesName($post_arr['user_name'][$i]);
				}
				$post_arr['count']=$count;
				$data['post_arr']=	$post_arr;
			}
		}	
		if($action=='delete')
		{

			$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );	
			$details=$this->Showroom_model->deleteSalesQuotation($data['id']);
			if($details)
			{
				$msg="Deleted Successfully";
				$this->redirect($msg,'showroom/meeting-mint',True);
			}
			else
			{
				$msg="Error on Deletion";
				$this->redirect($msg,'showroom/meeting-mint',false);
			}
		}
		$this->loadView($data);
	}
	public function get_meeting_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			$post_arr['items']=true;
			$post_arr['order']= 'id';
			$post_arr['order_by']= 'DESC';
			$count_without_filter = $this->Showroom_model->getMeetingCount();
			$count_with_filter = $this->Showroom_model->getMeetingDetailAjax($post_arr, 1);
			$details = $this->Showroom_model->getMeetingDetailAjax( $post_arr,'');
			// print_r($details[0]['code']);die();
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $details,
			);
			echo json_encode($response);
		} 
	}

	function validate_add_minutes() 
	{
		$this->form_validation->set_rules('code', 'Minutes No:', 'required');
		$this->form_validation->set_rules('user_name[]', 'User Name', 'required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
		$this->form_validation->set_rules('sales_name', 'Salesman', 'required');
		$result =  $this->form_validation->run();
		return $result;
	}

	function add_items($enc_id='',$action='',$id='')
	{
		$data['title'] = "Sample Items"; 
		$this->load->model('Member_model');
		$this->load->model('Item_model');
		if ($enc_id) {
			$data['enc_id'] = $enc_id;
			$meeting_id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		} else{
			$this->redirect("Invalid Address","showroom/meeting-mint",FALSE);
		}
		$data['enc_id']=$enc_id;
		$data['image']=$this->Showroom_model->getMeetingImages($meeting_id);
// print_r($data['image']);die();
		$data['vat'] = $this->Member_model->getVatDetails();
		$data['ad_note'] = $this->Showroom_model->getMeetingMintNote($meeting_id);
		$this->loadView($data);
	}


	function get_items() {
		$this->load->model('Sample_model');
		$this->form_validation->set_rules('note', 'note', 'required');
		if ($this->input->post('id')&& $this->form_validation->run()) {
			$post_arr = $this->input->post();
			$items_arr = [];
			$post_arr['form_data'] = json_decode($post_arr['form_data']);
			$item_string =  explode(' - ', $post_arr['item_name']);
			$comming_arr = [
				'1'=>$item_string['0'],
				'2'=>$item_string['1'],
				'3'=>$post_arr['note'],
			];
			array_push($post_arr['form_data'], $comming_arr);

			foreach ($post_arr['form_data'] as $key => $value) {

				$item = $value[1];

				if(in_array( $item,$items_arr)){
					// print_r($items_arr);die();
					$response['status'] = FALSE;
					$response['msg'] = 'Item already added';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
					die();
				}else{
					array_push($items_arr, $item);
				}
			}
			$response['item_info'] = $this->Sample_model->getAllSampleDetails($this->input->post('id'),$images=true);
			// print_r($response['item_info']);die();
			if ($response['item_info']) {
				$response['success'] = TRUE;
				$response['msg'] = 'succces';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}else{

				$response['success'] = FALSE;
				$response['msg'] = "Data not Found";

				$this->output->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			} 
		}
		else{
			$response['success'] = FALSE;
			$response['msg'] = "Enter the Note";
			$this->output->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}
	}
	function add_meeting_sample() {
		if ($this->input->is_ajax_request() ) {
			if ( $this->validate_save_items() ) {

				$post = $this->input->post();
				// print_r($post);die();
				$total_items = 0;
				$total_qty = 0;
				$total_amount = 0;
				$total_vat = 0;
				$avg_vat = 0;
				$total_vat_inclusive = 0;
				$this->load->model('Sample_model');

				$this->Showroom_model->begin();
				$date=date('Y-m-d H:i:s'); 

				foreach ($post['data'] as $key => $value) {
					$search_arr = [
						'code' => $value['code'],
					];
					$item_details=$this->Sample_model->getAllSampleItemsDetails($search_arr);


					$total_items++; 
					$value['total_price'] = ( $value['price'] ); 

					$value['sample_id'] = $item_details['id'];
					$value['date'] = $date; 
					$total_amount += $value['total_price'];  
					// print_r($value);die();

					$inserted = $this->Showroom_model->insertSampleItems( $post['meeting_id'], $value );

					if(!$inserted){
						$response['success'] = FALSE;
						$response['msg'] = 'Failed..! Please try again';
						$this->set_session_flash_data( $response['msg'], $response['success']  );
						$this->Sales_model->rollback();
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit();
					}
				} 

				$post['total_items'] = $total_items; 
				$post['total_amount'] = $total_amount; 
				// print_r($post);die();
				if($this->Showroom_model->updateMeetingSamples($post['meeting_id'],$post))
				{

					$this->Showroom_model->commit();
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Meeting Samples Added', serialize($post));
					$response['success'] = TRUE;
					$response['msg'] = 'Successfully added the Samples...!';
					$this->set_session_flash_data( $response['msg'], $response['success']  );

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Showroom_model->rollback();
					$response['success'] = FALSE;
					$response['msg'] = 'Failed. Please try again...';
					$this->set_session_flash_data( $response['msg'], $response['success']  );

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();


				}

			}else{
				$response['success'] = FALSE;
				$response['msg'] = validation_errors(' ', ' ');
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
			$_POST['meeting_id'] =  $this->Base_model->encrypt_decrypt('decrypt', $_POST['id']);
			// $_POST['by_amount'] =   $_POST['by_amount'];
			// $_POST['by_percentage'] =   $_POST['by_percentage'];
			unset($_POST['id']);
			foreach ($_POST['data'] as $key => $value) {

				$_POST['data'][$key]['code'] = $value['1'];
				$_POST['data'][$key]['name'] = $value['2'];
				$_POST['data'][$key]['note'] = $value['3'];
				$_POST['data'][$key]['cost'] = $value['4'];
				$_POST['data'][$key]['price'] = $value['5'];

				unset($_POST['data'][$key]['0']);
				unset($_POST['data'][$key]['1']);
				unset($_POST['data'][$key]['2']);
				unset($_POST['data'][$key]['3']);
				unset($_POST['data'][$key]['4']);
				unset($_POST['data'][$key]['5']);
				unset($_POST['data'][$key]['6']);
				unset($_POST['data'][$key]['7']);

				$this->form_validation->set_rules('data[]', 'Data', 'required');
			}

			$this->form_validation->set_rules('meeting_id', 'Item', 'required');
			
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
			$this->form_validation->set_rules('ad_note', 'Additional Note', 'required');  
		}
		$res = $this->form_validation->run();
		return $res;
	}
	function get_item_details() {
		$this->load->model('Sample_model');
		if ($this->input->post('id')) {
			$post_arr = $this->input->post();

			$response['item_info'] = $this->Sample_model->getAllSampleDetails($this->input->post('id'));
			if ($response['item_info']) {


				$response['success'] = TRUE;
				$response['msg'] = 'succces';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();


			}else{

				$response['success'] = FALSE;
				$response['msg'] = "Data not Found";

				$this->output->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			} 

		}
		else{
			$response['success'] = FALSE;
			$response['msg'] = "Invalid Item";

			$this->output->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}

	}
	function edit_meeting($id='')
	{
		$data['title'] = "Edit"; 
		$this->load->model('Member_model');

		$data['enc_id']= $id;
		$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );
		$data['details']= element(0,$this->Showroom_model->getMeetingDetails($data['id']));

		if($this->input->post('update') && $this->validate_add_updateminutes())
		{
			$post_arr=$this->input->post();
			$post_arr['user_name']=implode(",",$post_arr['user_name']);
			// print_r($post_arr);die();

			$update=$this->Showroom_model->updateSampleMeetingMint($data['id'],$post_arr);

			if($update)
			{
				$files=$_FILES;
				$config['upload_path'] = './assets/images/meeting/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
				$config['max_size'] = '2000000';
				$config['remove_spaces'] = true;
				$config['overwrite'] = false;
				$config['encrypt_name'] = TRUE;

				$this->load->library('upload', $config);

				for($i=1; $i<count($files["upload_file"]["name"]);$i++)
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
							$this->redirect( $error, 'showroom/edit-meeting/'.$id, false );
						} else {

							$image_arr = $this->upload->data();  
							$file_name=$image_arr['file_name'];
						// print_r($id);die();
							$this->Showroom_model->addMeetingImages($data['id'],$file_name);
						}
					}
				}


				$msg="Updated Successfully";
				$this->redirect($msg,'showroom/edit-meeting/'.$id,True);
			}
			else
			{
				$msg="Error on updation";
				$this->redirect($msg,'showroom/edit-meeting/'.$id,False);
			}
		}
		if($this->input->post('submit')=='delete_images')
		{
			$post=$this->input->post('images[]');
			$delete = FALSE;
					// die();
			if($post){
				foreach ($post as $key => $image_id) {
					// die();
					$delete=$this->Showroom_model->deleteMeetingImages($image_id);
				}
					// die();
				$enc_id=$this->Base_model->encrypt_decrypt('encrypt',$id);


				if ($delete) {
					$msg="";
					$this->redirect($msg,'showroom/edit-meeting/'.$id,True);
				} else{
					$msg="";
					$this->redirect($msg,'showroom/edit-meeting/'.$id,False);
				}
			}
		} 


		$this->loadView($data);
	}
	public function remove_meeting_sample($id, $meeting_id,$path="edit-meeting")
	{
		$this->load->model('Sample_model');
		$post_arr=array();
		$sample_id = $this->Base_model->encrypt_decrypt( 'decrypt', $id );	

		$meeting_dec_id = $this->Base_model->encrypt_decrypt( 'decrypt', $meeting_id );	
		$item_details=$this->Showroom_model->gettMeetingSampleDetails($sample_id);

		$sales_details = element(0,$this->Showroom_model->getMeetingDetails($meeting_dec_id));

		// print_r($sales_details);die();
		$items_vat = array_column($sales_details['items'], 'vat_perc', 'sample_meeting_id');

		$this->Showroom_model->begin();
		$details = $this->Showroom_model->deleteMeetingMintSample($sample_id,$meeting_dec_id);

		if($details)
		{
			$this->Showroom_model->commit();
			$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Item Deleted', $meeting_dec_id);
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'showroom/'.$path.'/'.$meeting_id,True);
		} else {
			$this->Sales_model->rollback();
			$msg="Error on Deleting Item";
			$this->redirect($msg,'showroom/edit-meeting/'.$meeting_id,false);
		}
		$this->loadView($data);
	}
	
	function validate_add_updateminutes() 
	{
		$this->form_validation->set_rules('user_name[]', 'User Name', 'required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
		$this->form_validation->set_rules('sales_name', 'Salesman', 'required');		
		$result =  $this->form_validation->run();
		// print_r( $this->form_validation->error_array());die();
		return $result;
	}
	public  function price_code($enc_id='')
	{ 
		$data['title']='Price Code';
		$this->loadView($data);
	}
	function delete_images($enc_id='')
	{ 
		// print_r($enc_id);die();
		$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id);	
		$details=$this->Showroom_model->deleteMeetingImages($data['id']);
		if($details)
		{
			$msg="Deleted Successfully";
			$this->redirect($msg,'showroom/edit-meeting',True);
		}
		else
		{
			$msg="Error on Deletion";
			$this->redirect($msg,'showroom/edit-meeting',false);
		}
	}
	public  function read_qr_code()
	{ 
		$data['title']='Read the  code';

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			$sample_id = $this->Showroom_model->getShowroomIdByCode($post_arr['sample_code']);


			if($sample_id){
				$enc_id = $this->Base_model->encrypt_decrypt( 'encrypt', $sample_id );

				$this->redirect('','login/showroom-items-print/'.$enc_id, FALSE);
			}else{

				$msg="Invalid code";
				$this->redirect($msg, 'showroom/read-qr-code/', FALSE);
			}

		}
		$this->loadView($data);

	}

	public  function upload($enc_id){ 

		$meeting_id=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );
		$request = 1;
		if(isset($_POST['request'])){ 
			$request = $_POST['request'];
			// print_r($_POST['request']);die();
		}
		if($request == 1){ 
			if($_FILES['file']['error']!=4)
			{
				$config['upload_path'] = './assets/images/meeting/';
				$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
				$config['max_size'] = '500';
				$config['remove_spaces'] = true;
				$config['overwrite'] = false;
				$config['encrypt_name'] = TRUE;

				$this->load->library('upload', $config);
				$msg = '';
				if (!$this->upload->do_upload("file")) {
					$msg = lang('image_not_selected');
					$error = $this->upload->display_errors();
					$this->redirect( $error, "showroom/add-items", false );
				} else {
					$image_arr = $this->upload->data(); 
				// print_r($image_arr);die();
					$data['filename']=$image_arr['file_name'];
					$meeting_id=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );
					$result=$this->Showroom_model->addMeetingImages($meeting_id,$data['filename']);

				}
			}


		}elseif($request == 2){
				// print_r($data['filename']);die();
			// print_r($_FILES);die();
			// print_r($_POST['name']);die();
			$target_dir = 'assets/images/meeting/';
			$filename = $target_dir.$_POST['name'];  
			// print_r($filename);die();
			unlink($filename);
			
		}

	}
}
