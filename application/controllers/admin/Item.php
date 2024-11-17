<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}
	public function index()
	{
		$data['title'] = 'Dashboard';
		$post_arr = [];
		$this->load->model('Sales_model');
		$this->load->model('Delivery_model');
		if($this->input->post('search'))
		{
			$post_arr = $this->input->post();
			if(element( 'item_id', $post_arr ))		
				$data['item_details'] = $this->Item_model->getAllItemDetails($post_arr['item_id']);
			else
			{
				$this->redirect("Please Select an Item code","item",FALSE);
			}
			if (empty($data['item_details'])) {
				$this->redirect("Item Details Not Found","item",FALSE);
			}
			$data['category_details']=element(0,$this->Delivery_model->getAllCategories($data['item_details']['category']));
			$data['sub_category_details']=element(0,$this->Delivery_model->getAllCategories($data['item_details']['sub_category']));
			$data['total_order']=$this->Sales_model->getTotalItem($data['item_details']['id']);
			$data['order_details'] = $this->Sales_model->getAllItemOrderDetails($post_arr['item_id']);
			
			
			// if(element('project_id',$post_arr)){
			// 	$data['project_jobs'] = $this->Project_model->getProjectJobs($post_arr['project_id']);
			// 	// print_r($data['project_jobs']);die();
			// 	$data['project_count']=count($data['project_jobs']);
			// 	$data['total_est_time'] = array_sum(array_column($data['project_jobs'],'estimated_working_hrs'));
			// 	$data['total_spent_time'] = array_sum(array_column($data['project_jobs'],'actual_workin_hrs'));
			// 	$data['total_time_difference']=$data['total_spent_time']-$data['total_est_time'];
			// }
			
			
		}
		$data['post_arr'] = $post_arr;
		$this->loadView($data);
	}

	function create_items($enc_id='')
	{  
		$data['title'] = lang('add_item');  
		$this->load->model('Member_model');
		$data['vat'] = $this->Member_model->getVatDetails();
		// $data['code']=$this->Item_model->getMaxItemId()+1010;
		if ($enc_id) {
			$data['title'] = 'Edit item';
			$data['id']=$id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			// print_r($data['id']);die();
			$data['edit_item']=	$this->Item_model->getAllItemDetails($id);
			// print_r($data['edit_item']);die();


		}

		if($this->input->post('submit')=='add_item'&& $this->validate_create_items())
		{ 
			$post=$this->input->post(); 
			$this->Item_model->begin();

			$post['user_id'] = log_user_id(); 

			if (empty($post['sub_category'])) {
				$post['sub_category']=0;
			}
			$result = $this->Item_model->addItems($post);
			
			if($result)
			{

				
				if ($_FILES['upload_file']['error'] != 4) {
					$files=$_FILES;
					$count=count($_FILES["upload_file"]["name"]);

					for($i=0; $i< $count; $i++)
					{
						$_FILES['upload_file']['name']= $files['upload_file']['name'][$i];
						$_FILES['upload_file']['type']= $files['upload_file']['type'][$i];
						$_FILES['upload_file']['tmp_name']= $files['upload_file']['tmp_name'][$i];
						$_FILES['upload_file']['error']= $files['upload_file']['error'][$i];
						$_FILES['upload_file']['size']= $files['upload_file']['size'][$i]; 
						if ($_FILES['upload_file']['name']=='') {
							$this->Item_model->addItemImages($result, 'no-image.png');
						}  else{
							$file_name='no-image.png';

							$config['upload_path'] = './assets/images/items/';
							$config['allowed_types'] = 'gif|jpg|png|jpeg';
							$config['max_size'] = '2000000';
							$config['remove_spaces'] = true;
							$config['overwrite'] = false;
							$config['encrypt_name'] = TRUE;

							$this->load->library('upload', $config);
							$msg = '';
							if (!$this->upload->do_upload('upload_file')) {


								$error = $this->upload->display_errors();

								$this->redirect( $error, "item/create-items", false );
							} else {
								$image_arr = $this->upload->data();  
								$file_name=$image_arr['file_name'];
							// $data['id']=$id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
							// print_r($data['id']);die();
							// print_r($id);die();
								$this->Item_model->addItemImages($result,$file_name);
							}

						}
					}
				}else{
					$this->Item_model->addItemImages($result, 'no-image.png');

				}
				$this->Item_model->commit();
				$msg = "Added Successfully";
				$this->redirect($msg,'item/create-items',True);
			}
			else
			{
				$this->Item_model->rollback();
				$msg = "Error on adding";
				$this->redirect($msg,'item/create-items',False);
			}

		}
		if($this->input->post('submit')=='update_item' && $this->validate_create_items())
		{
			$post=$this->input->post();
			// print_r($post);die();
			$this->load->model('Material_model');

			$this->Item_model->begin();
			if ($post['code']!=$data['edit_item']['code']) {
				$checkunique=$this->Material_model->getItemIdByCode($post['code']);
				if ($checkunique) {
					$this->redirect("Item Code should be unique","item/create-items/$enc_id",FALSE);
				}
			}

			$post['user_id'] = log_user_id();

			$result = $this->Item_model->updateItems($post,$id);
			// print_r($id);die();
			$files=$_FILES;
// print_r($_FILES);die();
			$config['upload_path'] = './assets/images/items/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$config['max_size'] = '2000000';
			$config['remove_spaces'] = true;
			$config['overwrite'] = false;
			$config['encrypt_name'] = TRUE;

			$this->load->library('upload', $config);
			
			// print_r($files["upload_file"]);die();

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
						$this->redirect( $error, "item/create-items", false );
					} else {

						$image_arr = $this->upload->data();  
						$file_name=$image_arr['file_name'];
						// print_r($image_arr);die();
						$this->Item_model->addItemImages($id,$file_name);
					}
				}
				if(in_array($files["upload_file"]["name"],$files["upload_file"])){
					unset($files['upload_file']['name']);

				}
			}


			$this->Item_model->commit();

			$msg = "Updated Successfully";
			$this->redirect($msg,'item/list-items',True);

		}
		if($this->input->post('submit')=='delete_images')
		{
			$post=$this->input->post('images[]');

			$delete = FALSE;
			if($post){
				foreach ($post as $key => $image_id) {
					$delete=$this->Item_model->deleteItemImage($image_id);
				}
				$enc_id=$this->Base_model->encrypt_decrypt('encrypt',$id);
				if ($delete) {
					$this->redirect("Image Deleted","item/create-items/$enc_id",TRUE);
				} else{
					$this->redirect("Image Deletion Failed","item/create-items/$enc_id",False);

				}
			}
		}



		$this->loadView($data);
	}


	private function validate_create_items() 
	{

		if ($_POST['submit']=='update_item') {
			$this->form_validation->set_rules('code', 'Item Code', 'required');
		}
		else
			$this->form_validation->set_rules('code', 'Item Code', 'required|is_unique[items.code]');
		$this->form_validation->set_rules('name', 'Item Name', 'required');
		$this->form_validation->set_rules('note', 'Spec', 'required');
		$this->form_validation->set_rules('main_category', 'Item Category', 'required');
		$this->form_validation->set_rules('cost', 'Item Cost', 'required|numeric');
		$this->form_validation->set_rules('vat', 'Item Cost', 'required|numeric');
		$this->form_validation->set_rules('price', 'Selling Price', 'required|numeric');
		$this->form_validation->set_rules('unit', 'Item Unit', 'required');
		$this->form_validation->set_rules('total_quantity', 'Item Total quantity', 'required|numeric');
		$this->form_validation->set_rules('type', 'Item Type', 'required');

		$result =  $this->form_validation->run();

		return $result;
	}

	public function list_items($action='',$id='')
	{ 
		$data['title']="Item List";
		// $data['details'] = $this->Item_model->getAllItemDetails();


		if($action)
		{
			$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );	
			$this->Item_model->begin();
			$details=$this->Item_model->deleteItem($data['id']);

			if($details)
			{
				$this->Item_model->commit();
				$msg="Deleted Successfully";
				$this->redirect($msg,'item/list-items',True);
			}
			else
			{
				$this->Item_model->rollback();
				$msg="Error on Deletion";
				$this->redirect($msg,'item/list-items',false);
			}
		}

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			if(element( 'item_id', $post_arr ))	{ 
				$post_arr['code'] = $this->Item_model->getItemCode( 'code', $post_arr['item_id']); 
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

		$this->loadView($data);
	}


	public function get_item_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$post_arr['items']=true;
			$count_without_filter = $this->Item_model->getItemsCount();
			$count_with_filter = $this->Item_model->getItemsAjax($post_arr, 1);
			$details = $this->Item_model->getItemsAjax( $post_arr,'');
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

	public function delete_image($image_id='',$item_id='')
	{
		if ($image_id && $item_id) {
			$res=$this->Item_model->deleteItemImage($image_id);
			$enc_id=$this->Base_model->encrypt_decrypt('encrypt',$item_id);
			if ($res) {
				$this->redirect("Image Deleted","item/create-items/$enc_id",TRUE);
			}
			else{
				$this->redirect("Image Deletion Failed","item/create-items/$enc_id",False);

			}
		}
	}

	function sub_category_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);
			// die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Item_model->getSubCategoryAjax($post,'active');
			echo json_encode($json);
		}
	}

	function get_item_note() {

		if ($this->input->is_ajax_request()) {
			$this->form_validation->set_rules('item_id', 'Item', 'required|greater_than[0]');

			if($this->form_validation->run()){ 
				$note = $this->Item_model->getItemNote($this->input->post('item_id'));
				echo $note;
			}else{
				echo '';
			}
		}
	}

	public function receipt_list( $action='', $enc_receipt_id='' )
	{
		if($action && $enc_receipt_id )
		{
			$receipt_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_receipt_id );	
			// print_r($receipt_id );die();
			$details=$this->Item_model->deleteConsumableReceipt($receipt_id);
			$data['material_receipt_id']=$receipt_id;
			$items=$this->Item_model->getConsumableItemDetails($data);
			foreach($items as $row){
				$details=$this->Item_model->deleteConsumableItem($row['id']);
			}
			// print_r($items);die();

			if($details)
			{
				$msg="Deleted Successfully";
				$this->redirect($msg,'item/receipt-list',True);
			} else {
				$msg="Error on Deletion";
				$this->redirect($msg,'item/receipt-list',false);
			}
		}


		$data['title']="Consumable Receipt List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			if(element( 'receipt_id', $post_arr ))	{ 
				$post_arr['bill_number'] = $this->Base_model->getConsumableReceiptBillNumber($post_arr['receipt_id']);
			}
			if(element( 'employee_id', $post_arr ))	{ 
				$post_arr['employee_name'] = $this->Base_model->getLoginInfoField('user_name',$post_arr['employee_id']);
			}		 
		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'active';
		}

		$data['post_arr'] = $post_arr;
		
		$this->loadView($data);
	}



	public function get_item_receipt_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$post_arr['items']=true;
			$count_without_filter = $this->Item_model->getItemReceiptCount();
			$count_with_filter = $this->Item_model->getItemReceiptAjax($post_arr, 1);
			$details = $this->Item_model->getItemReceiptAjax( $post_arr,'');
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

	function issue_add()
	{ 
		$data['voucher_number']=$data['code']=$this->Item_model->getMaxConsumableIssueId()+1001;
		if($this->input->post('submit'))
		{
			$post_arr=$this->input->post();
			if(element( 'supplier_id',$post_arr)){
				$data['supplier_name'] = $this->Base_model->getSupplierName($data['supplier_id'] );
			}
		}
		$data['title'] = "Issue Add"; 
		$this->loadView($data);
	}

	public function check_items() {
		$items_arr = [];
		if ($this->input->is_ajax_request()) {
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			// $this->form_validation->set_rules('job_order_id', 'Job Order', 'required');
			$this->form_validation->set_rules('item_id', 'Item', 'required');
			$this->form_validation->set_rules('item_qty', 'Quantity', 'required|numeric|greater_than[0]');

			if( $this->form_validation->run() ){
				$post_arr['form_data'] = json_decode($post_arr['form_data']);
				$comming_arr = [
					'0'=>$post_arr['item_qty'],
					// '1'=>$post_arr['job_order_id'],
					'2'=>$post_arr['item_name'],
					'3'=>$post_arr['item_name']
				];
				array_push($post_arr['form_data'], $comming_arr);
				$count=count($post_arr['form_data'])-1;

// print_r($post_arr['form_data']);die();
				foreach ($post_arr['form_data'] as $key => $value) {
					if ($key==$count) {
						
						$item=$post_arr['item_name'];
					}
					else
						$item=$value[1].$value[2].' - '.$value[3];

					if(in_array( $item, $items_arr)){

						$response['form_data'] = $post_arr['form_data'];
						$response['count'] = $item;
						$response['items_arr'] = $items_arr;
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
						array_push($items_arr,$item);
					}
				}
				$this->load->model('Material_model');
				$items = $this->Material_model->checkItemsAssigning($post_arr);
				// print_r($items);die();
				if( !empty( $items )){
					$response['form_data'] = $post_arr['form_data'];
					$response['count'] = $item;
					$response['items_arr'] = $items_arr;
					$response['data'] = $items;
					$response['status'] = TRUE;
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{			
					$response['status'] = FALSE;
					$response['msg'] = 'Item not available';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
			}else{			
				$response['status'] = FALSE;
				$response['msg'] = implode( ', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}


		} 
	}


	public function checkItemsAssigning($data)
	{
		$details=[];
		$this->db->select( 'name, code, cost, total_quantity as unit' )
		->where( 'id', $data['item_id'] )
        // ->where( 'total_quantity >=', $data['item_qty'] )
		->limit( '1' );
		$res = $this->db->get('items');

		foreach($res->result_array() as $row)
		{   
			$details = $row;

		}
		return $details;
	}


	function save_issue_items() {

		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_issue_items() ) {
				$post_arr = $this->input->post();
				$this->load->model('Material_model');
				$issue_details = $post_arr;
				// print_r($issue_details);die();
				unset($issue_details['data']);

				$job_id_arr = [];
				$items_arr = [];
				$total_cost = 0;
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');

				foreach ($post_arr['data'] as $key => $value) {



					// $value['job_orderid']=

					// if( !element( $value['bill_number'], $items_arr) ){  
					// 	$items_arr[$value['bill_number']] = $this->Material_model->checkMaterialReceiptByBill($value);
					// }
					$arr = explode(' - ',trim($value['job_order']));
					$item_arr = explode(' - ',trim($value['item']));
					$items_arr['remarks']=$value['remarks'];
					$items_arr['job_orderid']=$arr[0];
					$items_arr['item_orderid']=$item_arr[0];
					$items_arr['issued_qty']=$value['issued_qty'];
					// print_r($items_arr['issued_qty']);die();

					$items_arr['item_id']=$this->Material_model->getItemIdByCode($items_arr['item_orderid']);
					$items_arr['job_order_id']=$items_arr['job_orderid']=$this->Material_model->getJobOrderIdByOrderId($items_arr['job_orderid']);
					$material_receipt_items=$this->Item_model->getConsumableReceiptItemDetails($items_arr);	
					// print_r($material_receipt_items);die();
					$item_cost=$this->Item_model->getItemCode('cost',$items_arr['item_id']);				

					// CHEKING REMOVED AS PER REQUEST
					// if(!$material_receipt_items)
					// {
					// 	$this->Material_model->rollback();
					// 	$response['success'] = False;
					// 	$response['msg'] = 'Invalid Items';
					// 	$this->set_session_flash_data( $response['msg'], $response['success']  );

					// 	$this->output
					// 	->set_status_header(200)
					// 	->set_content_type('application/json', 'utf-8')
					// 	->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					// 	->_display();
					// 	exit();
					// }

					// print_r($value['issued_qty']);die();
					
					$reciept_items[] = [
						
						'receipt_id' => $material_receipt_items['material_receipt_id'],
						'job_order_id' =>$items_arr['job_order_id'],
						'item_id' =>$items_arr['item_id'],
						'remarks' =>$items_arr['remarks'],
						'issued_qty' => $value['issued_qty'],
						'status' => 'active',
						'date_added' => $today,
						'last_update' => $today,

					];
					$total_cost += $value['issued_qty'] * $item_cost;

				} 
				// print_r($item_cost);die();

				$issue_details['created_by'] = log_user_id();
				$issue_details['total_issued_qty'] = array_sum( array_column( $reciept_items, 'issued_qty') );
				$issue_details['total_cost'] = $total_cost;
				$issue_details['status'] = 'active';
				$issue_details['date_added'] = $today;
				$issue_details['last_updated'] = $today;
				$issue_details['issued_by'] =log_user_name();
				$issue_data[] = $issue_details;

				// print_r($reciept_details);
				

				$this->Item_model->begin(); 

				$issue_id = $this->Item_model->insertConsumableIssue($issue_data, $reciept_items); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($issue_id)
				{
					$this->Item_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Issue Added', serialize($issue_data));
					$response['success'] = TRUE;
					$response['msg'] = 'Added Successfully';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Item_model->rollback();
					$response['success'] = TRUE;
					$response['msg'] = 'Error on adding';
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
				$response['msg'] = implode(', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}

	private function validate_save_issue_items() { 
		
		if(element('data', $this->input->post())){
			$_POST['data'] = json_decode($this->input->post('data'));

			foreach ($_POST['data'] as $key => $value) {

				$_POST['data'][$key]['job_order'] = $value['1'];
				$_POST['data'][$key]['item'] = $value['2'];
				$_POST['data'][$key]['issued_qty'] = $value['7'];
				$_POST['data'][$key]['remarks'] = $value['3'];
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
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}
		if (array_key_exists('issue_id', $this->input->post())) {
			$this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required');
			
		}else{

			$this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required|is_unique[consumable_issue.voucher_number]');
		}
		$this->form_validation->set_rules('voucher_date', 'Voucher Date', 'required');
		
		$res = $this->form_validation->run();

		return $res;
	}
	function receipt_add()
	{ 
		
		if($this->input->post('submit'))
		{
			$post_arr=$this->input->post();
			if(element( 'supplier_id',$post_arr)){
				$data['supplier_name'] = $this->Base_model->getSupplierName($data['supplier_id'] );
			}
		}
		$data['title'] = "Receipt Add"; 
		$this->loadView($data);
	}



	function save_items1() {

		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_items() ) {
				$this->load->model('Material_model');
				$post_arr = $this->input->post();
				$reciept_details = $post_arr;
				unset($reciept_details['data']);

				$job_id_arr = [];
				$items_arr = [];
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');
// print_r($post_arr['data']);die();
				foreach ($post_arr['data'] as $key => $value) {

					// if( !element( $value['job_order_id'], $job_id_arr) ){
					// 	$job_id_arr[$value['job_order_id']] = $value['job_order_id'];
					// }

					if( !element( $value['item_code'], $items_arr) ){  
						$items_arr[$value['item_code']] = $this->Material_model->getItemDetails($value);
					}

					$reciept_items[] = [
						// 'job_order_id' => $job_id_arr[$value['job_order_id']],
						'item_id' => $items_arr[$value['item_code']]['id'],
						'unit' => $items_arr[$value['item_code']]['total_quantity'],
						'cost' => $items_arr[$value['item_code']]['cost'],
						'qty' =>  $value['qty'],
						'date_added' => $today,
						'last_update' => $today,
						'activity_id' => log_user_id(),

					];
					$total_cost += $value['qty'] * $items_arr[$value['item_code']]['cost'];
				} 

				$reciept_details['created_by'] = log_user_id();
				$reciept_details['total_qty'] = array_sum( array_column( $reciept_items, 'qty') );
				$reciept_details['total_cost'] = $total_cost;
				$reciept_details['status'] = 'active';
				$reciept_details['date_added'] = $today;
				$reciept_details['last_updated'] = $today;
				$reciept_details['name'] = $post_arr['employee_id'];
				$receipt_data[] = $reciept_details;
				// print_r($reciept_details);
				// print_r($reciept_items);
					// print_r($item_details);
				$this->Item_model->begin(); 

				$receipt_id = $this->Item_model->insertConsumableReciept($receipt_data, $reciept_items); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($receipt_id)
				{
					$this->Item_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Material Receipt Added', serialize($receipt_data));
					$response['success'] = TRUE;
					$response['msg'] = 'Added Successfully';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Item_model->rollback();
					$response['success'] = TRUE;
					$response['msg'] = 'Error on adding';
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
				$response['msg'] = implode(', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}
	function edit_save_items() {

		$this->load->model('Material_model');
		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_items() ) {
				$post_arr = $this->input->post();
				// print_r($post_arr );die();
				$receipt_id=$post_arr['receipt_id'];
				$search_arr= [
					'reciept_id' => $receipt_id,
					'start' => 0,
					'length' => 1,
					'items' => TRUE,
				];
				$material_receipt = $this->Item_model->getConsumableReceiptAjax($search_arr );
				if ($material_receipt['bill_number']!=$post_arr['bill_number']) {
					$checkExists=$this->Material_model->checkUnique('material_receipt','bill_number',$post_arr['bill_number']);
					if ($checkExists) {
						$response['success'] = FALSE;
						$response['msg'] = "Bill Number already Exists";
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit();

					}


					
				}



				$reciept_details = $post_arr;
				unset($reciept_details['data']);
				unset($reciept_details['receipt_id']);

				$job_id_arr = [];
				$items_arr = [];
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');

				foreach ($post_arr['data'] as $key => $value) {
					// if( !element( $value['job_order_id'], $job_id_arr) ){
					// 	// print_r($value['job_order_id']);die();
					// 	// $job_id_arr[$value['job_order_id']] = $this->Base_model->getJobId($value['job_order_id']);
					// 	$job_id_arr[$value['job_order_id']] =$value['job_order_id'];
					// }


					if( !element( $value['item_code'], $items_arr) ){  
						$items_arr[$value['item_code']] = $this->Material_model->getItemDetails($value);
					}

					$reciept_items[] = [
						// 'job_order_id' => $job_id_arr[$value['job_order_id']],
						'item_id' => $items_arr[$value['item_code']]['id'],
						'unit' => $items_arr[$value['item_code']]['total_quantity'],
						'cost' => $items_arr[$value['item_code']]['cost'],
						'qty' => $value['qty'],
						// 'date_added' => $today,
						'last_update' => $today,


					];
					$total_cost += $value['qty'] * $items_arr[$value['item_code']]['cost'];
				} 

				$reciept_details['created_by'] = log_user_id();
				$reciept_details['total_qty'] = array_sum( array_column( $reciept_items, 'qty') ) + $material_receipt['total_qty'];
				$reciept_details['total_cost'] = $total_cost+ $material_receipt['total_cost'];
				$reciept_details['status'] = 'active';
				$reciept_details['date_added'] = $today;
				$reciept_details['last_updated'] = $today;
				
				$reciept_details['id'] = $receipt_id;
				// $reciept_details['issued_by'] = log_user_id();
				$receipt_data[] = $reciept_details;
				// print_r($reciept_details);
				// print_r($reciept_items);
					// print_r($reciept_details);die();

				$this->Item_model->begin(); 

				$update = $this->Item_model->updateAllConsumableReceipt($receipt_data, $reciept_items,$receipt_id); 
				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($update)
				{
					$this->Item_model->commit(); 
					$response['success'] = TRUE;
					$response['msg'] = 'Added Successfully';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Item_model->rollback();
					$response['success'] = TRUE;
					$response['msg'] = 'Error on adding';
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
				$response['msg'] = implode(', ', $this->form_validation->error_array());
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
			

			foreach ($_POST['data'] as $key => $value) {
				// $_POST['data'][$key]['job_order_id'] = $value['1'];
				$_POST['data'][$key]['item_code'] = $value['1'];
				$_POST['data'][$key]['qty'] = $value['5'];
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
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}
		if (array_key_exists('receipt_id', $_POST)) {
			$this->form_validation->set_rules('bill_number', 'Bill Number', 'required');
		}
		else
			$this->form_validation->set_rules('bill_number', 'Bill Number', 'required|is_unique[consumable_receipt.bill_number]');
		$this->form_validation->set_rules('employee_id', 'Receiver Name', 'required');
		$this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required|is_exist[supplier_info.id]');
		$res = $this->form_validation->run();
		return $res;
	}

	public function check_consumable_items() {
		
		if ($this->input->is_ajax_request()) {
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			// $this->form_validation->set_rules('material_id', 'Bill Number', 'required');
			$this->form_validation->set_rules('item_id', 'Item', 'required');
			// $this->form_validation->set_rules('job_orderid', 'Job Order', 'required');
			// $this->form_validation->set_rules('project_id', 'Project', 'required');
			$this->form_validation->set_rules('item_remarks', 'Remarks', 'required');
			$this->form_validation->set_rules('item_qty', 'Quantity', 'required|numeric|greater_than[0]');

			if( $this->form_validation->run() ){
				$items_arr = [];
				$post_arr['form_data'] = json_decode($post_arr['form_data']);
				$comming_arr = [
					'0'=>$post_arr['item_qty'],
					// '1'=>$post_arr['material_id']
					'1'=>$post_arr['item_id'],
					// '2'=>$post_arr['job_orderid']
					
				];
				array_push($post_arr['form_data'], $comming_arr);

				foreach ($post_arr['form_data'] as $key => $value) {

					$item = $value[1];
					if(in_array( $item, $items_arr)){

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
				$post_arr['item_id']=$this->input->post('item_id');
				$post_arr1['item_id']=$this->input->post('item_id');
				$post_arr1['issued_qty']=$this->input->post('item_qty');
				$items = $this->Item_model->getConsumableReceiptItemDetails($post_arr1);
				// print_r($items);die();
				if( !empty( $items )){
					$response['data'] = $items;
					$response['status'] = TRUE;
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{			
					$response['status'] = FALSE;
					$response['msg'] = 'Item not available';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
			}else{			
				$response['status'] = FALSE;
				$response['msg'] = implode( ', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}


		} 
	}

	function return()
	{ 
		
		$this->load->model('Material_model');
		$data['voucher_number']=$data['code']=$this->Item_model->getMaxReturnId()+1001;
		if($this->input->post('submit'))
		{
			$post_arr=$this->input->post();
			if(element( 'supplier_id',$post_arr)){
				$data['supplier_name'] = $this->Base_model->getSupplierName($data['supplier_id'] );
			}
		}
		$data['title'] = "Return"; 
		$this->loadView($data);
	}



	function save_return_items() {

		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_return_items() ) {
				$post_arr = $this->input->post();
				// print_r($post_arr);die();
				$this->load->model('Material_model');
				$this->load->model('Item_model');
				$issue_details = $post_arr;
				unset($issue_details['data']);

				$job_id_arr = [];
				$items_arr = [];
				$total_cost = 0;
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');


				foreach ($post_arr['data'] as $key => $value) {



					// $value['job_orderid']=

					// if( !element( $value['bill_number'], $items_arr) ){  
					// 	$items_arr[$value['bill_number']] = $this->Material_model->checkMaterialReceiptByBill($value);
					// }
					$arr = explode(' - ',trim($value['job_order']));
					$item_arr = explode(' - ',trim($value['item']));
					// print_r($item_arr);die();
					$items_arr['remarks']=$value['remarks'];
					$items_arr['job_orderid']=$arr[0];
					$items_arr['item_orderid']=$item_arr[0];
					$items_arr['item_id']=$this->Material_model->getItemIdByCode($items_arr['item_orderid']);
					$items_arr['issued_qty']=$value['issued_qty'];
					$items_arr['job_order_id']=$items_arr['job_orderid']=$this->Material_model->getJobOrderIdByOrderId($items_arr['job_orderid']);
					$items = $this->Item_model->getConsumableIssueItemDetails($items_arr);
					$issue_id=$items['issue_id'];
					// print_r($items);die();

					$material_receipt_items=$this->Item_model->getConsumableIssuedItemDetails($issue_id,$items_arr);	
					// print_r($material_receipt_items);die();
					$item_cost=$this->Item_model->getItemCode('cost',$items_arr['item_id']);				

					// CHEKING REMOVED AS PER REQUEST
					// if(!$material_receipt_items)
					// {
					// 	$this->Material_model->rollback();
					// 	$response['success'] = False;
					// 	$response['msg'] = 'Invalid Items';
					// 	$this->set_session_flash_data( $response['msg'], $response['success']  );

					// 	$this->output
					// 	->set_status_header(200)
					// 	->set_content_type('application/json', 'utf-8')
					// 	->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					// 	->_display();
					// 	exit();
					// }
					
					$reciept_items[] = [
						
						'receipt_id' => $material_receipt_items[0]['issue_id'],
						'job_order_id' =>$items_arr['job_order_id'],
						'item_id' =>$items_arr['item_id'],
						'remarks' =>$items_arr['remarks'],
						
						'issued_qty' => $value['issued_qty'],
						'status' => 'active',
						'date_added' => $today,
						'last_update' => $today,

					];
					$total_cost += $value['issued_qty'] * $item_cost;

				} 
				

				$issue_details['created_by'] = log_user_id();
				$issue_details['total_issued_qty'] = array_sum( array_column( $reciept_items, 'issued_qty') );
				$issue_details['total_cost'] = $total_cost;
				$issue_details['status'] = 'active';
				$issue_details['date_added'] = $today;
				$issue_details['last_updated'] = $today;
				$issue_details['issue_id'] = $material_receipt_items[0]['issue_id'];
				$issue_data[] = $issue_details;

				// print_r($reciept_details);
				

				$this->Item_model->begin(); 

				$issue_id = $this->Item_model->insertConsumableReturn($issue_data, $reciept_items); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($issue_id)
				{
					$this->Item_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Return Added', serialize($issue_data));
					$response['success'] = TRUE;
					$response['msg'] = 'Added Successfully';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Item_model->rollback();
					$response['success'] = TRUE;
					$response['msg'] = 'Error on adding';
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
				$response['msg'] = implode(', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}

	private function validate_save_return_items() { 
		if(element('data', $this->input->post())){
			$_POST['data'] = json_decode($this->input->post('data'));

			foreach ($_POST['data'] as $key => $value) {

				$_POST['data'][$key]['job_order'] = $value['1'];
				$_POST['data'][$key]['item'] = $value['2'];
				$_POST['data'][$key]['remarks'] = $value['3'];
				$_POST['data'][$key]['issued_qty'] = $value['6'];
				// $_POST['data'][$key]['issue_id'] = $value['9'];
				unset($_POST['data'][$key]['0']);
				unset($_POST['data'][$key]['1']);
				unset($_POST['data'][$key]['2']);
				unset($_POST['data'][$key]['3']);
				unset($_POST['data'][$key]['4']);
				unset($_POST['data'][$key]['5']);
				unset($_POST['data'][$key]['6']);
				unset($_POST['data'][$key]['7']);
				unset($_POST['data'][$key]['8']);

				$this->form_validation->set_rules('data[]', 'Data', 'required'); 
			}
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}
		if (array_key_exists('issue_id', $this->input->post())) {
			$this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required');
			
		}else{

			$this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required|is_unique[consumable_return.voucher_number]');
		}
		$this->form_validation->set_rules('voucher_date', 'Voucher Date', 'required');
		
		$res = $this->form_validation->run();
		return $res;
	}

	function damage()
	{ 
		
		$data['title'] = "Damage/ Loss"; 
		$this->load->model('Material_model');
		$data['voucher_number']=$data['code']=$this->Item_model->getMaxDamageId()+1001;
		if($this->input->post('submit'))
		{
			$post_arr=$this->input->post();
			if(element( 'supplier_id',$post_arr)){
				$data['supplier_name'] = $this->Base_model->getSupplierName($data['supplier_id'] );
			}
		}
		$data['title'] = "Damage/ Loss"; 
		$this->loadView($data);
	}


	function save_damage_items() {

		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_damage_items() ) {
				$post_arr = $this->input->post();
				$this->load->model('Material_model');
				// print_r($post_arr);die();
				$issue_details = $post_arr;
				unset($issue_details['data']);

				$job_id_arr = [];
				$items_arr = [];
				$total_cost = 0;
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');


				foreach ($post_arr['data'] as $key => $value) {



					// $value['job_orderid']=

					// if( !element( $value['bill_number'], $items_arr) ){  
					// 	$items_arr[$value['bill_number']] = $this->Material_model->checkMaterialReceiptByBill($value);
					// }
					$arr = explode(' - ',trim($value['job_order']));
					$item_arr = explode(' - ',trim($value['item']));
					$items_arr['job_orderid']=$arr[0];
					$items_arr['remarks']=$value['remarks'];
					$items_arr['item_orderid']=$item_arr[0];
					$items_arr['issued_qty']=$value['issued_qty'];
					$items_arr['item_id']=$this->Material_model->getItemIdByCode($items_arr['item_orderid']);
					$items_arr['job_order_id']=$items_arr['job_orderid']=$this->Material_model->getJobOrderIdByOrderId($items_arr['job_orderid']);
					$items = $this->Item_model->getConsumableIssueItemDetails($items_arr);
					$issue_id=$items['issue_id'];
					// print_r($issue_id);die();
					$material_receipt_items=$this->Item_model->getConsumableIssuedItemDetails($issue_id);	
					// print_r($material_receipt_items);die();
					$item_cost=$this->Item_model->getItemCode('cost',$items_arr['item_id']);				

					// CHEKING REMOVED AS PER REQUEST
					// if(!$material_receipt_items)
					// {
					// 	$this->Material_model->rollback();
					// 	$response['success'] = False;
					// 	$response['msg'] = 'Invalid Items';
					// 	$this->set_session_flash_data( $response['msg'], $response['success']  );

					// 	$this->output
					// 	->set_status_header(200)
					// 	->set_content_type('application/json', 'utf-8')
					// 	->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					// 	->_display();
					// 	exit();
					// }
					
					$reciept_items[] = [
						
						'receipt_id' => $material_receipt_items[0]['issue_id'],
						'job_order_id' =>$items_arr['job_order_id'],
						'item_id' =>$items_arr['item_id'],
						'remarks' =>$items_arr['remarks'],
						
						'issued_qty' => $value['issued_qty'],
						'status' => 'active',
						'date_added' => $today,
						'last_update' => $today,

					];
					$total_cost += $value['issued_qty'] * $item_cost;

				} 
				

				$issue_details['created_by'] = log_user_id();
				$issue_details['total_issued_qty'] = array_sum( array_column( $reciept_items, 'issued_qty') );
				$issue_details['total_cost'] = $total_cost;
				$issue_details['status'] = 'active';
				$issue_details['date_added'] = $today;
				$issue_details['last_updated'] = $today;
				$issue_details['issue_id'] = $material_receipt_items[0]['issue_id'];
				$issue_data[] = $issue_details;

				// print_r($reciept_details);
				

				$this->Item_model->begin(); 

				$issue_id = $this->Item_model->insertConsumableDamage($issue_data, $reciept_items); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($issue_id)
				{
					$this->Item_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Issue Added', serialize($issue_data));
					$response['success'] = TRUE;
					$response['msg'] = 'Added Successfully';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Item_model->rollback();
					$response['success'] = TRUE;
					$response['msg'] = 'Error on adding';
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
				$response['msg'] = implode(', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}

	private function validate_save_damage_items() { 
		if(element('data', $this->input->post())){
			$_POST['data'] = json_decode($this->input->post('data'));

			foreach ($_POST['data'] as $key => $value) {

				$_POST['data'][$key]['job_order'] = $value['1'];
				$_POST['data'][$key]['item'] = $value['2'];
				$_POST['data'][$key]['remarks'] = $value['3'];
				$_POST['data'][$key]['issued_qty'] = $value['7'];
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
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}
		if (array_key_exists('issue_id', $this->input->post())) {
			$this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required');
			
		}else{

			$this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required|is_unique[consumable_damage.voucher_number]');
		}
		$this->form_validation->set_rules('voucher_date', 'Voucher Date', 'required');
		
		$res = $this->form_validation->run();
		return $res;
	}

	public function return_list( $action='', $enc_receipt_id='' )
	{
		if($action && $enc_receipt_id )
		{
			$receipt_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_receipt_id );	
			$details=$this->Item_model->deleteConsumableReturn($receipt_id);
			$items=$this->Item_model->getConsumableReturnItemDetails($receipt_id);
			foreach($items as $row){
				$delete=$this->Item_model->deleteConsumableReturnItem($row['id']);
			}
			if($details)
			{
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Return deleted', $receipt_id);
				$msg="Deleted Successfully";
				$this->redirect($msg,'item/return-list',True);
			} else {
				$msg="Error on Deletion";
				$this->redirect($msg,'item/return-list',false);
			}
		}
		$data['title']="Consumable Return List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			if(element( 'issue_id', $post_arr ))	{ 
				$post_arr['voucher_number'] = $this->Item_model->getReturnBillNumber($post_arr['issue_id']);
			}	 
		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'active';
		}

		$data['post_arr'] = $post_arr;
		
		$this->loadView($data);
	}

	public function get_material_return_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$post_arr['items']=true;
			$count_without_filter = $this->Item_model->getMaterialReturnCount();
			$count_with_filter = $this->Item_model->getMaterialReturnAjax($post_arr, 1);
			$details = $this->Item_model->getMaterialReturnAjax( $post_arr,'');
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


	public function damage_list( $action='', $enc_receipt_id='' )
	{
		if($action && $enc_receipt_id )
		{
			$receipt_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_receipt_id );	
			$details=$this->Item_model->deleteConsumableDamage($receipt_id);
			$items=$this->Item_model->getConsumableDamageItemDetails($receipt_id);
			foreach($items as $row){
				$delete=$this->Item_model->deleteConsumableDamageItem($row['id']);
			}
			if($details)
			{
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Return deleted', $receipt_id);
				$msg="Deleted Successfully";
				$this->redirect($msg,'item/damage-list',True);
			} else {
				$msg="Error on Deletion";
				$this->redirect($msg,'item/damage-list',false);
			}
		}


		$data['title']="Damage List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			if(element( 'issue_id', $post_arr ))	{ 
				$post_arr['voucher_number'] = $this->Item_model->getDamageBillNumber($post_arr['issue_id']);
			}	 
		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'active';
		}

		$data['post_arr'] = $post_arr;
		
		$this->loadView($data);
	}

	public function get_material_damage_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			$post_arr['items']=true;
			$count_without_filter = $this->Item_model->getMaterialDamageCount();
			$count_with_filter = $this->Item_model->getMaterialDamageAjax($post_arr, 1);
			$details = $this->Item_model->getMaterialDamageAjax( $post_arr,'');
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


	

	public function get_consumable_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$post_arr['items']=true;
			$count_without_filter = $this->Item_model->getConsumableReceiptCount();
			$count_with_filter = $this->Item_model->getConsumableReceiptAjax($post_arr, 1);
			$details = $this->Item_model->getConsumableReceiptAjax( $post_arr,'');
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
	
	function edit_receipt( $enc_receipt_id )
	{ 

		$receipt_id = $this->Base_model->encrypt_decrypt('decrypt', $enc_receipt_id);
		
		$data['receipt_id']=$receipt_id;
		$search_arr= [
			'reciept_id' => $this->Base_model->encrypt_decrypt('decrypt', $enc_receipt_id),
			'start' => 0,
			'length' => 1,
			'items' => TRUE,
		];
		$data['details'] = $this->Item_model->getConsumableReceiptAjax($search_arr );

		if ($this->input->post('update_receipt')) {
			$post_arr=$this->input->post();

			if ($data['details']['bill_number']!=$post_arr['bill_number']) {
				$this->form_validation->set_rules('bill_number', 'Bill Number', 'required|is_unique[consumable_receipt.bill_number]');

			}
			else{
				$this->form_validation->set_rules('bill_number', 'Bill Number', 'required');
			}
			$this->form_validation->set_rules('employee_id', 'Reciept Name', 'required');
			$this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required|is_exist[supplier_info.id]');

			if ( $this->form_validation->run() ) 
			{
				$this->Item_model->begin();
				$update=$this->Item_model->updateConsumableReceipt($post_arr,$receipt_id); 
				if ($update) {
					$this->Item_model->commit();
					$this->redirect("Consumable Receipt Updated Successfully","item/edit-receipt/$enc_receipt_id",TRUE);
				}
				else{
					$this->Item_model->rollback();
					$this->redirect("Consumable Receipt updation Failed","item/edit-receipt/$enc_receipt_id",FALSE);
				}
			}

		}

		$data['title'] = "Receipt Add"; 
		$this->loadView($data);
	}
	public function remove_material_item($enc_material_item_id, $reciept_id)
	{
		$material_item_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_material_item_id );	
		
		$details = $this->Item_model->deleteConsumableItem($material_item_id);
		if($details)
		{
			$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Receipt Item deleted', $material_item_id);
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'item/edit-receipt/'.$reciept_id,True);
		}
		else
		{
			$msg="Error on Deleting Item";
			$this->redirect($msg,'item/edit-receipt/'.$reciept_id,false);
		}
		$this->loadView($data);
	}



	public function issue_list( $action='', $enc_receipt_id='' )
	{
		if($action && $enc_receipt_id )
		{
			$receipt_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_receipt_id );	
			$details=$this->Item_model->deleteConsumableIssue($receipt_id);
			$items=$this->Item_model->getConsumableIssuedItemDetails($receipt_id);
			foreach($items as $row){
				$delete=$this->Item_model->deleteConsumableIssueItem($row['id']);
			}
			if($details)
			{
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Issue  deleted', $receipt_id);
				$msg="Deleted Successfully";
				$this->redirect($msg,'item/issue-list',True);
			} else {
				$msg="Error on Deletion";
				$this->redirect($msg,'item/issue-list',false);
			}
		}


		$data['title']="Material List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			if(element( 'issue_id', $post_arr ))	{ 
				$post_arr['voucher_number'] = $this->Item_model->getIssueBillNumber($post_arr['issue_id']);
			}	 
		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'active';
		}

		$data['post_arr'] = $post_arr;
		
		$this->loadView($data);
	}
	public function get_material_issue_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			// $post_arr['items']=true;
			$count_without_filter = $this->Item_model->getConsumableIssueCount();
			$count_with_filter = $this->Item_model->getConsumableIssueAjax($post_arr, 1);
			$details = $this->Item_model->getConsumableIssueAjax( $post_arr,'');
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


	function edit_issue( $enc_issue_id )
	{ 

		$issue_id = $this->Base_model->encrypt_decrypt('decrypt', $enc_issue_id);
		
		$search_arr= [
			'issue_id' => $this->Base_model->encrypt_decrypt('decrypt', $enc_issue_id),
			'start' => 0,
			'length' => 1,
			'items' => TRUE,
		];

		$data['material_data'] = $this->Item_model->getConsumableIssueAjax($search_arr );

// print_r($data['material_data']);die();
		if ($this->input->post('voucher_update')) {
			$voucher_details=$this->input->post();
			$voucher_details['issue_by']=log_user_id();

			// print_r($voucher_details);die();
			$update=$this->Item_model->updateVoucherDetails($voucher_details,$voucher_details['issue_id']);
			if ($update) {
				$this->redirect("Voucher Details Updated Successfully","item/edit-issue/$enc_issue_id",TRUE);
			}
			else
			{

				$this->redirect("Voucher Details updation Failed","item/edit-issue/$enc_issue_id",FALSE);
			}

			
		}

		
		$data['title'] = "Edit Issue"; 
		$this->loadView($data);
	}



	function update_issue_items() {
		$this->load->model('Material_model');
		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_issue_items() ) {
				$post_arr = $this->input->post();

// print_r($post_arr);die();
				$this->load->model('Item_model');
				$issue_details = $post_arr;
				unset($issue_details['data']);

				$job_id_arr = [];
				$issue_id=$post_arr['issue_id'];
				$items_arr = [];
				$total_cost = 0;
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');
				if ($post_arr['data']) {
					# code...

// print_r($post_arr['data']);die();
					foreach ($post_arr['data'] as $key => $value) {

						

						// if( !element( $value['bill_number'], $items_arr) ){  
						// 	$items_arr[$value['bill_number']] = $this->Material_model->checkMaterialReceiptByBill($value);
						// }
						$arr = explode(' - ',trim($value['job_order']));
						$item_arr = explode(' - ',trim($value['item']));
						$items_arr['job_orderid']=$arr[0];
						$items_arr['remarks']=$value['remarks'];
						$items_arr['item_orderid']=$item_arr[0];
						$items_arr['issued_qty']=$value['issued_qty'];
						$items_arr['item_id']=$this->Material_model->getItemIdByCode($items_arr['item_orderid']);
						$items_arr['job_order_id']=$this->Material_model->getJobOrderIdByOrderId($items_arr['job_orderid']);
						$material_receipt_items=$this->Item_model->getConsumableReceiptItemDetails($items_arr);	
						$item_cost=$this->Item_model->getItemCode('cost',$items_arr['item_id']);				

						$reciept_items[] = [
							
							'receipt_id' => $material_receipt_items['material_receipt_id'],
							'job_order_id' =>$items_arr['job_order_id'],
							'item_id' =>$items_arr['item_id'],
							'remarks' =>$items_arr['remarks'],

							'issued_qty' => $value['issued_qty'],
							'status' => 'active',
							'date_added' => $today,
							'last_update' => $today,
						];
						$total_cost += $value['issued_qty'] * $item_cost;
					} 
				}
				$issue_details['created_by'] = log_user_id();
				$issue_details['total_issued_qty'] = array_sum( array_column( $reciept_items, 'issued_qty') );
				$issue_details['total_cost'] = $total_cost;
				$issue_details['status'] = 'active';
				$issue_details['date_added'] = $today;
				$issue_details['last_updated'] = $today;
				$issue_data[] = $issue_details;

				// print_r($reciept_details);
				// print_r($reciept_items);
					// print_r($item_details);
				
				$this->Item_model->begin(); 

				$update = $this->Item_model->updateConsumableIssue($issue_details, $issue_id); 
				$update = $this->Item_model->insertIssuedConsumableReciept($reciept_items,$issue_id); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($update)
				{
					$this->Item_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Issue Items Added', serialize($reciept_items));
					$response['success'] = TRUE;
					$response['msg'] = 'Added Successfully';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Item_model->rollback();
					$response['success'] = TRUE;
					$response['msg'] = 'Error on adding';
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
				$response['msg'] = implode(', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}


	function edit_damage( $enc_issue_id )
	{ 

		$issue_id = $this->Base_model->encrypt_decrypt('decrypt', $enc_issue_id);
		
		$search_arr= [
			'issue_id' => $this->Base_model->encrypt_decrypt('decrypt', $enc_issue_id),
			'start' => 0,
			'length' => 1,
			'items' => TRUE,
		];

		$data['material_data'] = $this->Item_model->getMaterialDamageAjax($search_arr );
// print_r($data['material_data']);die();
		if ($this->input->post('voucher_update')) {
			$voucher_details=$this->input->post();
			$update=$this->Item_model->updateVoucherDamageDetails($voucher_details,$voucher_details['issue_id']);
			if ($update) {
				$this->redirect("Voucher Details Updated Successfully","item/edit-damage/$enc_issue_id",TRUE);
			}
			else
			{

				$this->redirect("Voucher Details updation Failed","item/edit-damage/$enc_issue_id",FALSE);
			}

			
		}

		
		$data['title'] = "Edit Issue"; 
		$this->loadView($data);
	}


	function update_damage_items() {
		$this->load->model('Material_model');
		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_issue_items() ) {
				$post_arr = $this->input->post();

// print_r($post_arr);die();
				$this->load->model('Item_model');
				$issue_details = $post_arr;
				unset($issue_details['data']);

				$job_id_arr = [];
				$issue_id=$post_arr['issue_id'];
				$items_arr = [];
				$total_cost = 0;
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');
				if ($post_arr['data']) {
					# code...


					foreach ($post_arr['data'] as $key => $value) {

						

						// if( !element( $value['bill_number'], $items_arr) ){  
						// 	$items_arr[$value['bill_number']] = $this->Material_model->checkMaterialReceiptByBill($value);
						// }
						$arr = explode(' - ',trim($value['job_order']));
						$item_arr = explode(' - ',trim($value['item']));
						$items_arr['job_orderid']=$arr[0];
						$items_arr['remarks']=$value['remarks'];
						$items_arr['item_orderid']=$item_arr[0];
						$items_arr['item_id']=$this->Material_model->getItemIdByCode($items_arr['item_orderid']);
						$items_arr['issued_qty']=$value['issued_qty'];
						$items_arr['job_orderid']=$this->Material_model->getJobOrderIdByOrderId($items_arr['job_orderid']);
						$material_receipt_items=$this->Item_model->getConsumableReceiptItemDetails($items_arr);	
						$item_cost=$this->Item_model->getItemCode('cost',$items_arr['item_id']);				

						$reciept_items[] = [
							
							'receipt_id' => $material_receipt_items['material_receipt_id'],
							'job_order_id' =>$items_arr['job_orderid'],
							'item_id' =>$items_arr['item_id'],
							'remarks' =>$items_arr['remarks'],

							'issued_qty' => $value['issued_qty'],
							'status' => 'active',
							'date_added' => $today,
							'last_update' => $today,
						];
						$total_cost += $value['issued_qty'] * $item_cost;
					} 
				}
				$issue_details['created_by'] = log_user_id();
				$issue_details['total_issued_qty'] = array_sum( array_column( $reciept_items, 'issued_qty') );
				$issue_details['total_cost'] = $total_cost;
				$issue_details['status'] = 'active';
				$issue_details['date_added'] = $today;
				$issue_details['last_updated'] = $today;
				$issue_data[] = $issue_details;

				// print_r($reciept_details);
				// print_r($reciept_items);
					// print_r($item_details);
				
				$this->Item_model->begin(); 

				$update = $this->Item_model->updateConsumableDamage($issue_details, $issue_id); 
				$update = $this->Item_model->insertDamageConsumableReciept($reciept_items,$issue_id); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($update)
				{
					$this->Item_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Damage Items Added', serialize($reciept_items));
					$response['success'] = TRUE;
					$response['msg'] = 'Added Successfully';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Item_model->rollback();
					$response['success'] = TRUE;
					$response['msg'] = 'Error on adding';
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
				$response['msg'] = implode(', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}
	function edit_return( $enc_issue_id )
	{ 

		$issue_id = $this->Base_model->encrypt_decrypt('decrypt', $enc_issue_id);
		
		$search_arr= [
			'issue_id' => $this->Base_model->encrypt_decrypt('decrypt', $enc_issue_id),
			'start' => 0,
			'length' => 1,
			'items' => TRUE,
		];

		$data['material_data'] = $this->Item_model->getMaterialReturnAjax($search_arr );
		// print_r($data['material_data']);die();
		if ($this->input->post('voucher_update')) {
			$voucher_details=$this->input->post();
			// print_r($voucher_details);die();
			$update=$this->Item_model->updateVoucherReturnDetails($voucher_details,$voucher_details['issue_id']);
			if ($update) {
				$this->redirect("Voucher Details Updated Successfully","item/edit-return/$enc_issue_id",TRUE);
			}
			else
			{

				$this->redirect("Voucher Details updation Failed","item/edit-return/$enc_issue_id",FALSE);
			}

			
		}

		
		$data['title'] = "Receipt Add"; 
		$this->loadView($data);
	}
	function update_return_items() {
		$this->load->model('Material_model');
		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_return_items() ) {
				$post_arr = $this->input->post();

// print_r($post_arr);die();
				$this->load->model('Item_model');
				$issue_details = $post_arr;
				unset($issue_details['data']);

				$job_id_arr = [];
				$issue_id=$post_arr['issue_id'];
				$items_arr = [];
				$total_cost = 0;
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');
				if ($post_arr['data']) {
					# code...


					foreach ($post_arr['data'] as $key => $value) {

						

						// if( !element( $value['bill_number'], $items_arr) ){  
						// 	$items_arr[$value['bill_number']] = $this->Material_model->checkMaterialReceiptByBill($value);
						// }
						$arr = explode(' - ',trim($value['job_order']));
						$item_arr = explode(' - ',trim($value['item']));
						$items_arr['job_orderid']=$arr[0];
						$items_arr['remarks']=$value['remarks'];
						$items_arr['issued_qty']=$value['issued_qty'];
						$items_arr['item_orderid']=$item_arr[0];
						$items_arr['item_id']=$this->Material_model->getItemIdByCode($items_arr['item_orderid']);
						$items_arr['job_orderid']=$this->Material_model->getJobOrderIdByOrderId($items_arr['job_orderid']);
						$material_receipt_items=$this->Item_model->getConsumableReceiptItemDetails($items_arr);	
						$item_cost=$this->Item_model->getItemCode('cost',$items_arr['item_id']);				

						$reciept_items[] = [
							
							'receipt_id' => $material_receipt_items['material_receipt_id'],
							'job_order_id' =>$items_arr['job_orderid'],
							'item_id' =>$items_arr['item_id'],
							'remarks' =>$items_arr['remarks'],

							'issued_qty' => $value['issued_qty'],
							'status' => 'active',
							'date_added' => $today,
							'last_update' => $today,
						];
						$total_cost += $value['issued_qty'] * $item_cost;
					} 
				}
				$issue_details['created_by'] = log_user_id();
				$issue_details['total_issued_qty'] = array_sum( array_column( $reciept_items, 'issued_qty') );
				$issue_details['total_cost'] = $total_cost;
				$issue_details['status'] = 'active';
				$issue_details['date_added'] = $today;
				$issue_details['last_updated'] = $today;
				$issue_data[] = $issue_details;

				// print_r($reciept_details);
				// print_r($reciept_items);
					// print_r($item_details);
				
				$this->Item_model->begin(); 

				$update = $this->Item_model->updateConsumableReturn($issue_details, $issue_id); 
				$update = $this->Item_model->insertReturnConsumableReciept($reciept_items,$issue_id); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($update)
				{
					$this->Item_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Return Items Added', serialize($reciept_items));
					$response['success'] = TRUE;
					$response['msg'] = 'Added Successfully';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Item_model->rollback();
					$response['success'] = TRUE;
					$response['msg'] = 'Error on adding';
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
				$response['msg'] = implode(', ', $this->form_validation->error_array());
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}


	public function remove_issue_item($enc_material_item_id, $reciept_id)
	{
		$material_item_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_material_item_id );	
		
		$details = $this->Item_model->deleteConsumableIssueItem($material_item_id);
		if($details)
		{
			$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Issue Item deleted', $material_item_id);
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'item/edit-issue/'.$reciept_id,True);
		}
		else
		{
			$msg="Error on Deleting Item";
			$this->redirect($msg,'item/edit-issue/'.$reciept_id,false);
		}
		$this->loadView($data);
	}

	public function remove_return_item($enc_material_item_id, $reciept_id)
	{
		$material_item_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_material_item_id );	
		
		$details = $this->Item_model->deleteConsumableReturnItem($material_item_id);
		if($details)
		{
			$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Issue Item deleted', $material_item_id);
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'item/edit-return/'.$reciept_id,True);
		}
		else
		{
			$msg="Error on Deleting Item";
			$this->redirect($msg,'item/edit-return/'.$reciept_id,false);
		}
		$this->loadView($data);
	}

	public function remove_damage_item($enc_material_item_id, $reciept_id)
	{
		$material_item_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_material_item_id );	
		
		$details = $this->Item_model->deleteConsumableDamageItem($material_item_id);
		if($details)
		{
			$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Consumable Issue Item deleted', $material_item_id);
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'item/edit-damage/'.$reciept_id,True);
		}
		else
		{
			$msg="Error on Deleting Item";
			$this->redirect($msg,'item/edit-damage/'.$reciept_id,false);
		}
		$this->loadView($data);
	}

	public  function print_history($enc_id='')
	{ 
		$data['title']='Print Voucher';
		$data['date'] = date('Y-m-d H:i:s');  
		$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		$data['id']=$id;
		$data['enc_id']=$enc_id;
		
		$details= $this->Item_model->getConsumableIssueDetails($id);


// print_r($details);die();

		$data['details']=$details; 
		$this->loadView($data);
		

	}

	public  function damage_history($enc_id='')
	{ 
		$data['title']='Damage Details';
		$data['date'] = date('Y-m-d H:i:s');  
		$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		$data['id']=$id;
		$data['enc_id']=$enc_id;
		
		$details= $this->Item_model->getConsumableDamageDetails($id);


// print_r($details);die();

		$data['details']=$details; 
		$this->loadView($data);
		

	}


	public  function return_history($enc_id='')
	{ 
		$data['title']='Return Details';
		$data['date'] = date('Y-m-d H:i:s');  
		$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		$data['id']=$id;
		$data['enc_id']=$enc_id;
		
		$details= $this->Item_model->getConsumableReturnDetails($id);
		$data['details']=$details; 
		$this->loadView($data);
		

	}


	public function check_consumable_issue_items() {
		
		if ($this->input->is_ajax_request()) {
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			// $this->form_validation->set_rules('material_id', 'Bill Number', 'required');
			$this->form_validation->set_rules('item_id', 'Item', 'required');
			// $this->form_validation->set_rules('job_orderid', 'Job Order', 'required');
			// $this->form_validation->set_rules('project_id', 'Project', 'required');
			$this->form_validation->set_rules('item_remarks', 'Remarks', 'required');
			$this->form_validation->set_rules('item_qty', 'Quantity', 'required|numeric|greater_than[0]');

			if( $this->form_validation->run() ){
				$items_arr = [];
				$post_arr['form_data'] = json_decode($post_arr['form_data']);
				$comming_arr = [
					'0'=>$post_arr['item_qty'],
					// '1'=>$post_arr['material_id']
					'1'=>$post_arr['item_id'],
					// '2'=>$post_arr['job_orderid']
					
				];
				array_push($post_arr['form_data'], $comming_arr);

				foreach ($post_arr['form_data'] as $key => $value) {

					$item = $value[1];
					if(in_array( $item, $items_arr)){

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
				$post_arr['item_id']=$this->input->post('item_id');
				$post_arr1['item_id']=$this->input->post('item_id');
				$post_arr1['issued_qty']=$this->input->post('item_qty');
				$items = $this->Item_model->getConsumableIssueItemDetails($post_arr1);
				// print_r($items);die();
				if( !empty( $items )){
					$response['data'] = $items;
					$response['status'] = TRUE;
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{			
					$response['status'] = FALSE;
					$response['msg'] = 'Item not available';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
			}else{			
				$response['status'] = FALSE;
				$response['msg'] = implode( ', ', $this->form_validation->error_array());
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
