<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
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


	public function list( $action='', $enc_receipt_id='' )
	{
		if($action && $enc_receipt_id )
		{
			$receipt_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_receipt_id );	
			$details=$this->Material_model->deleteMaterialReceipt($receipt_id);
			$data['material_receipt_id']=$receipt_id;
			$items=$this->Material_model->getMaterialItemDetails($data);
			foreach($items as $row){
				$details=$this->Material_model->deleteMaterialItem($row['id']);
			}
			// print_r($items);die();
			
			if($details)
			{
				$msg="Deleted Successfully";
				$this->redirect($msg,'material/list',True);
			} else {
				$msg="Error on Deletion";
				$this->redirect($msg,'material/list',false);
			}
		}


		$data['title']="Material List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			if(element( 'receipt_id', $post_arr ))	{ 
				$post_arr['bill_number'] = $this->Base_model->getReceiptBillNumber($post_arr['receipt_id']);
			}	 
		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'pending';
		}

		$post_arr['status'] = 'active';
		$data['post_arr'] = $post_arr;
		
		$this->loadView($data);
	}


	public function get_material_list_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$post_arr['items']=true;
			$count_without_filter = $this->Material_model->getMaterialReceiptCount();
			$count_with_filter = $this->Material_model->getMaterialReceiptAjax($post_arr, 1);
			$details = $this->Material_model->getMaterialReceiptAjax( $post_arr,'');
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


	public function check_items() {
		$items_arr = [];
		if ($this->input->is_ajax_request()) {
			$post_arr = $this->input->post();
			
			$this->form_validation->set_rules('job_order_id', 'Job Order', 'required');
			$this->form_validation->set_rules('item_id', 'Item', 'required');
			$this->form_validation->set_rules('item_qty', 'Quantity', 'required|numeric|greater_than[0]');

			if( $this->form_validation->run() ){
				$post_arr['form_data'] = json_decode($post_arr['form_data']);
				$comming_arr = [
					'0'=>$post_arr['item_qty'],
					'1'=>$post_arr['job_order_id'],
					'2'=>$post_arr['item_name'],
					'3'=>$post_arr['job_order_id'].$post_arr['item_name']
				];
				array_push($post_arr['form_data'], $comming_arr);
				$count=count($post_arr['form_data'])-1;


				foreach ($post_arr['form_data'] as $key => $value) {
					if ($key==$count) {
						
						$item=$post_arr['job_order_id'].$post_arr['item_name'];
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
				
				$items = $this->Material_model->checkItemsAssigning($post_arr);
				
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

	function save_items() {

		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_items() ) {
				$post_arr = $this->input->post();

				$reciept_details = $post_arr;
				unset($reciept_details['data']);

				$job_id_arr = [];
				$items_arr = [];
				$total_cost = 0;
				$today = date('Y-m-d H:i:s');

				foreach ($post_arr['data'] as $key => $value) {

					if( !element( $value['job_order_id'], $job_id_arr) ){
						$job_id_arr[$value['job_order_id']] = $value['job_order_id'];
					}

					if( !element( $value['item_code'], $items_arr) ){  
						$items_arr[$value['item_code']] = $this->Material_model->getItemDetails($value);
					}

					$reciept_items[] = [
						'job_order_id' => $job_id_arr[$value['job_order_id']],
						'item_id' => $items_arr[$value['item_code']]['id'],
						'unit' => $items_arr[$value['item_code']]['total_quantity'],
						'cost' => $items_arr[$value['item_code']]['cost'],
						'qty' => $value['qty'],
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
				$receipt_data[] = $reciept_details;
				// print_r($reciept_details);
				// print_r($reciept_items);
					// print_r($item_details);

				$this->Material_model->begin(); 

				$receipt_id = $this->Material_model->insertMaterialReciept($receipt_data, $reciept_items); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($receipt_id)
				{
					$this->Material_model->commit(); 
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
					$this->Material_model->rollback();
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

		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_items() ) {
				$post_arr = $this->input->post();
				$receipt_id=$post_arr['receipt_id'];
				$search_arr= [
					'reciept_id' => $receipt_id,
					'start' => 0,
					'length' => 1,
					'items' => TRUE,
				];
				$material_receipt = $this->Material_model->getMaterialReceiptAjax($search_arr );

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
// print_r($value['job_order_id']);die();
					if( !element( $value['job_order_id'], $job_id_arr) ){
						$job_id_arr[$value['job_order_id']] = $this->Base_model->getJobId($value['job_order_id']);
					}

					if( !element( $value['item_code'], $items_arr) ){  
						$items_arr[$value['item_code']] = $this->Material_model->getItemDetails($value);
					}

					$reciept_items[] = [
						'job_order_id' => $job_id_arr[$value['job_order_id']],
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
				$receipt_data[] = $reciept_details;
				// print_r($reciept_details);
				// print_r($reciept_items);
					// print_r($item_details);

				$this->Material_model->begin(); 

				$update = $this->Material_model->updateAllMaterialReceipt($receipt_data, $reciept_items,$receipt_id); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($update)
				{
					$this->Material_model->commit(); 
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
					$this->Material_model->rollback();
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
				$_POST['data'][$key]['job_order_id'] = $value['1'];
				$_POST['data'][$key]['item_code'] = $value['2'];
				$_POST['data'][$key]['qty'] = $value['6'];
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
			$this->form_validation->set_rules('bill_number', 'Bill Number', 'required|is_unique[material_receipt.bill_number]');
		$this->form_validation->set_rules('name', 'Reciept Name', 'required');
		$this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required|is_exist[supplier_info.id]');
		$res = $this->form_validation->run();

		return $res;
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
		$data['details'] = $this->Material_model->getMaterialReceiptAjax($search_arr );



		if ($this->input->post('update_receipt')) {
			$post_arr=$this->input->post();
			if ($data['details']['bill_number']!=$post_arr['bill_number']) {
				$this->form_validation->set_rules('bill_number', 'Bill Number', 'required|is_unique[material_receipt.bill_number]');

			}
			else{
				$this->form_validation->set_rules('bill_number', 'Bill Number', 'required');
			}
			$this->form_validation->set_rules('name', 'Reciept Name', 'required');
			$this->form_validation->set_rules('supplier_id', 'Supplier ID', 'required|is_exist[supplier_info.id]');
			if ( $this->form_validation->run() ) 
			{
				$this->Material_model->begin();
				$update=$this->Material_model->updateMaterialReceipt($post_arr,$receipt_id); 
				if ($update) {
					$this->Material_model->commit();
					$this->redirect("Material Receipt Updated Successfully","material/edit-receipt/$enc_receipt_id",TRUE);
				}
				else{
					$this->Material_model->rollback();
					$this->redirect("Material Receipt updation Failed","material/edit-receipt/$enc_receipt_id",FALSE);
				}
			}

		}

		$data['title'] = "Receipt Add"; 
		$this->loadView($data);
	}
	

	public function remove_material_item($enc_material_item_id, $reciept_id)
	{
		$material_item_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_material_item_id );	
		
		$details = $this->Material_model->deleteMaterialItem($material_item_id);
		if($details)
		{
			$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Material Receipt Item deleted', $material_item_id);
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'material/edit-receipt/'.$reciept_id,True);
		}
		else
		{
			$msg="Error on Deleting Item";
			$this->redirect($msg,'material/edit-receipt/'.$reciept_id,false);
		}
		$this->loadView($data);
	}

	public function remove_material_reciept($enc_material_item_id, $issue_id)
	{
		$material_item_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_material_item_id );	
		$details = $this->Material_model->deleteMaterialIssueItem($material_item_id);
		if($details)
		{
			$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Material Issue Item deleted', $material_item_id);
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'material/edit-issue/'.$issue_id,True);
		}
		else
		{
			$msg="Error on Deleting Item";
			$this->redirect($msg,'material/edit-issue/'.$issue_id,false);
		}
		$this->loadView($data);
	}

	function issue_add()
	{ 
		$data['voucher_number']=$data['code']=$this->Material_model->getMaxIssueId()+1001;
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
	public function issue_list( $action='', $enc_receipt_id='' )
	{
		if($action && $enc_receipt_id )
		{
			$receipt_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_receipt_id );	
			$details=$this->Material_model->deleteMaterialIssue($receipt_id);
			$items=$this->Material_model->getMaterialIssuedItemDetails($receipt_id);
			foreach($items as $row){
				$delete=$this->Material_model->deleteMaterialIssueItem($row['id']);
			}
			if($details)
			{
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Material Issue  deleted', $receipt_id);
				$msg="Deleted Successfully";
				$this->redirect($msg,'material/issue-list',True);
			} else {
				$msg="Error on Deletion";
				$this->redirect($msg,'material/issue-list',false);
			}
		}


		$data['title']="Material List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			if(element( 'issue_id', $post_arr ))	{ 
				$post_arr['voucher_number'] = $this->Base_model->getVoucherNumber($post_arr['issue_id']);
			}	 
		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'pending';
		}

		$post_arr['status'] = 'active';
		$data['post_arr'] = $post_arr;
		
		$this->loadView($data);
	}
	public function get_material_issue_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			// $post_arr['items']=true;
			$count_without_filter = $this->Material_model->getMaterialIssueCount();
			$count_with_filter = $this->Material_model->getMaterialIssueAjax($post_arr, 1);
			$details = $this->Material_model->getMaterialIssueAjax( $post_arr,'');
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

	public function get_inventory_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			$post_arr['items']=true;
			$count_without_filter = $this->Material_model->getInventoryCount();
			$count_with_filter = $this->Material_model->getInventoryAjax($post_arr,1);
			// print_r($count_with_filter);die();
			$details = $this->Material_model->getInventoryAjax( $post_arr,'');
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
	public function check_material_items() {
		
		if ($this->input->is_ajax_request()) {
			$post_arr = $this->input->post();
			// $this->form_validation->set_rules('material_id', 'Bill Number', 'required');
			$this->form_validation->set_rules('item_id', 'Item', 'required');
			$this->form_validation->set_rules('job_orderid', 'Job Order', 'required');
			$this->form_validation->set_rules('project_id', 'Project', 'required');
			$this->form_validation->set_rules('item_qty', 'Quantity', 'required|numeric|greater_than[0]');

			if( $this->form_validation->run() ){
				$items_arr = [];
				$post_arr['form_data'] = json_decode($post_arr['form_data']);
				$comming_arr = [
					'0'=>$post_arr['item_qty'],
					// '1'=>$post_arr['material_id']
					'1'=>$post_arr['item_id'],
					'2'=>$post_arr['job_orderid']
					
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
				$items = $this->Material_model->getMaterialReceiptItemDetails($post_arr);


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
	function save_issue_items() {

		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_issue_items() ) {
				$post_arr = $this->input->post();
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
					$items_arr['job_orderid']=$arr[0];
					$items_arr['item_orderid']=$item_arr[0];
					$items_arr['item_id']=$this->Material_model->getItemIdByCode($items_arr['item_orderid']);
					$items_arr['job_order_id']=$items_arr['job_orderid']=$this->Material_model->getJobOrderIdByOrderId($items_arr['job_orderid']);
					$material_receipt_items=$this->Material_model->getMaterialReceiptItemDetails($items_arr);	
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
						
						'receipt_id' => $material_receipt_items['material_receipt_id'],
						'job_order_id' =>$items_arr['job_order_id'],
						'item_id' =>$items_arr['item_id'],
						
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
				$issue_data[] = $issue_details;

				// print_r($reciept_details);
				

				$this->Material_model->begin(); 

				$issue_id = $this->Material_model->insertMaterialIssue($issue_data, $reciept_items); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($issue_id)
				{
					$this->Material_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Material Issue Added', serialize($issue_data));
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
					$this->Material_model->rollback();
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
	function update_issue_items() {

		if ($this->input->is_ajax_request() ) {
			
			if ( $this->validate_save_issue_items() ) {
				$post_arr = $this->input->post();


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
						$items_arr['item_orderid']=$item_arr[0];
						$items_arr['item_id']=$this->Material_model->getItemIdByCode($items_arr['item_orderid']);
						$items_arr['job_order_id']=$this->Material_model->getJobOrderIdByOrderId($items_arr['job_orderid']);
						// $material_receipt_items=$this->Material_model->getMaterialReceiptItemDetails($items_arr);	
						$item_cost=$this->Item_model->getItemCode('cost',$items_arr['item_id']);				

						$reciept_items[] = [
							
							// 'receipt_id' => $material_receipt_items['material_receipt_id'],
							'job_order_id' =>$items_arr['job_order_id'],
							'item_id' =>$items_arr['item_id'],

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
				
				$this->Material_model->begin(); 

				$update = $this->Material_model->updateMaterialIssue($issue_details, $issue_id); 
				$update = $this->Material_model->insertIssuedMaterialReciept($reciept_items,$issue_id); 

				// $enc_receipt_id = $this->Base_model->encrypt_decrypt('encrypt', $receipt_id);

				if($update)
				{
					$this->Material_model->commit(); 
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Material Issue Items Added', serialize($reciept_items));
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
					$this->Material_model->rollback();
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
				$_POST['data'][$key]['issued_qty'] = $value['6'];
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

			$this->form_validation->set_rules('voucher_number', 'Voucher Number', 'required|is_unique[material_issue.voucher_number]');
		}
		$this->form_validation->set_rules('voucher_date', 'Voucher Date', 'required');
		
		$res = $this->form_validation->run();

		return $res;
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

		$data['material_data'] = $this->Material_model->getMaterialIssueAjax($search_arr );

		if ($this->input->post('voucher_update')) {
			$voucher_details=$this->input->post();
			$update=$this->Material_model->updateVoucherDetails($voucher_details,$voucher_details['issue_id']);
			if ($update) {
				$this->redirect("Voucher Details Updated Successfully","material/edit-issue/$enc_issue_id",TRUE);
			}
			else
			{

				$this->redirect("Voucher Details updation Failed","material/edit-issue/$enc_issue_id",FALSE);
			}

			
		}

		
		$data['title'] = "Receipt Add"; 
		$this->loadView($data);
	}
	function item_with_name_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$post['job_orderid'] = element('job_orderid', $post) ? $post['job_orderid'] : '';
			$json = $this->Base_model->getMaterialItemAutoComplete($post['q'], '', $post,$post['job_orderid']);
			echo json_encode($json);
		}
	}

}