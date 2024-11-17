<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function sales_quotation($action='',$id='')
	{

		$data['title'] = "Sales Quotation"; 

		$this->load->model('Member_model');
		$data['vat'] = $this->Member_model->getVatDetails();
		$data['code']=$this->Sales_model->getMaxSalesId()+1001;

		if($this->input->post('submit') && $this->validate_add_sales())
		{
			$post_arr=$this->input->post();

			$this->Sales_model->begin();
			
			$post_arr['salesperson'] = log_user_id();

			$id=$this->Sales_model->insertSalesQuotation($post_arr);
			if ($id) {
				$this->Sales_model->commit();
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Sales Quotation Added', serialize($post_arr));

				$enc_id=$this->Base_model->encrypt_decrypt('encrypt',$id);
				$this->redirect('sales Quotation Added Successfully',"sales/add-items/$enc_id",TRUE);
			}
			else{
				$this->Sales_model->rollback();
				$this->redirect("Insertion Failed","sales/sales-quotation",FALSE);
			}
		}	
		if($action=='delete')
		{

			$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );	
			$details=$this->Sales_model->deleteSalesmanQuotation($data['id']);
			if($details)
			{
				$msg="Deleted Successfully</small>";
				$this->redirect($msg,'sales/list-sales',True);
			}
			else
			{
				$msg="Error on Deletion, <small>Drafted quotation can only delete";
				$this->redirect($msg,'sales/list-sales',false);
			}
		}	
		print_r($this->form_validation->error_array());
		$this->loadView($data);
	}

	function add_items($enc_id='',$action='',$id='')
	{

		$data['title'] = "Sales Quotation"; 

		$this->load->model('Member_model');
		$this->load->model('Item_model');
		if ($enc_id) {
			$data['enc_id']=$enc_id;
			$sales_id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		}
		else{
			$this->redirect("Invalid Address","sales/sales-quotation",FALSE);
		}
		$data['vat'] = $this->Member_model->getVatDetails();
		$data['items'] = $this->Item_model->getAllItemDetails();

		$this->loadView($data);
	}

	function edit_sales($id)
	{
		$data['title'] = "Edit Sales"; 
		$this->load->model('Member_model');

		$data['enc_id']= $id;
		$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );
		$data['details']= element(0,$this->Sales_model->getSalesDetails($data['id']));
// print_r($data['details']);
// die();
		$data['vat'] = $this->Member_model->getVatDetails(); 
		if(!empty($data['details']['items'])){

			$data['total_vat'] = array_sum(array_column($data['details']['items'], 'vat_perc')); 
		}else{
			$data['total_vat'] = 0;
		}
		if($this->input->post('update'))
		{
			$post_arr=$this->input->post();

			$post_arr['salesperson'] = log_user_id();
			$post_arr['total_vat'] = $data['total_vat'];

			if($data['details']['status'] == 'approved'){
				$post_arr['date'] = $data['details']['date'];
				$post_arr['subject'] = $data['details']['subject'];
				$post_arr['customer_name'] = $data['details']['customer_id'];
				$post_arr['payment_terms_id'] = $data['details']['payment_terms_id'];
				$post_arr['normal_terms_id'] = $data['details']['terms_conditions'];
			}
			// print_r($post_arr);
			// die();
			$update=$this->Sales_model->updateSalesQuotation($data['id'],$post_arr);

			if($update)
			{
				$msg="Updated Successfully";
				$this->redirect($msg,'sales/edit-sales/'.$id,True);
			}
			else
			{
				$msg="Error on updation";
				$this->redirect($msg,'sales/edit-sales/'.$id,False);
			}
		} 

		$this->loadView($data);
	}

	function get_item_details() {


		$this->load->model('Item_model');
		if ($this->input->post('id')) {


			
			$post_arr = $this->input->post();

			$response['item_info'] = $this->Item_model->getAllItemDetails($this->input->post('id'));
				// echo $this->db->last_query();
				// print_r($response['item_info']);
				// die();

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


	function get_items() {


		$this->load->model('Item_model');
		if ($this->input->post('id')) {
			$quantity=$this->input->post('quantity');
			$this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|greater_than[0]');
			$this->form_validation->set_rules('rate', 'Rate', 'required|numeric|greater_than[0]');


			if( $this->form_validation->run() ){
				$post_arr = $this->input->post();


				$items_arr = [];
				$post_arr['form_data'] = json_decode($post_arr['form_data']);
				$comming_arr = [
					'2'=>$post_arr['item_name'],
				];
				array_push($post_arr['form_data'], $comming_arr);


				$response['item_info'] = $this->Item_model->getAllItemDetails($this->input->post('id'),$images=true);


				if ($response['item_info']) { 
					$response['item_info']['total_price'] = $quantity*$post_arr['rate'];
					$response['item_info']['inclusive_vat'] = $response['item_info']['total_price']+($response['item_info']['total_price']*$response['item_info']['value']/100);

					if(empty($response['item_info']['item_images'])){
						array_push($response['item_info']['item_images'], 'no-image.png');
					}

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


	function validate_add_sales() 
	{

		$this->form_validation->set_rules('subject', 'Subject', 'required');
		$this->form_validation->set_rules('code', 'Sales No:', 'required');
		$this->form_validation->set_rules('date', 'Quotation Date', 'required');
		$this->form_validation->set_rules('customer_name', 'Customer Name', 'required');
		$this->form_validation->set_rules('status', 'status', 'required|in_list[draft,submitted_for_approval]'); 

		$this->form_validation->set_rules('payment_terms_id', 'Payment T&C', 'required');
		$this->form_validation->set_rules('normal_terms_id', 'Normal T&C', 'required');

		$result =  $this->form_validation->run();
		return $result;
	}

	public function add_package_items($enc_id='')
	{
		$data['title']="Project Details";

		$data['enc_id']=$enc_id;

		$package_id=$this->Base_model->encrypt_decrypt('decrypt', $enc_id);
		$project_details=$this->Sales_model->getProjectInfo($package_id);

		if($this->input->post('submit'))
		{
			$post=$this->input->post();
// $insert=$this->Sales_model->insertPackageItems($post,$package_id);
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

	public function list_sales($action='',$id='')
	{

		$data['title']="Sales Quotation List";
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			if(element( 'sales_id', $post_arr ))				
				$post_arr['sales_code'] = $this->Base_model->getItemName($post_arr['sales_id']);

		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'all';
			$post_arr['type'] = 'all';
		}

		$post_arr['items'] = TRUE;
		$post_arr['order'] = 'id';
		$post_arr['order_by'] = 'DESC';


		$data['post_arr'] = $post_arr;

		if($action)
		{
			$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );	

			if($this->Sales_model->checkDeliveryPackage($data['id'])){

				$msg = "Failed..! Pacakge is added on a Delivery Note";
				$this->redirect($msg,'packages/package-list',false);
			}


			$details=$this->Sales_model->deletePackages($data['id']);
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

	
	public function create_revision($enc_id)
	{ 
		$enc_id=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );

		$details = element(0,$this->Sales_model->getSalesDetails($enc_id));

		if(!empty($details)){
			unset($details['id']);
			$details['note'] = 'R-'.$details['code']; 
			$details['code'] = $this->Sales_model->getMaxSalesId()+1001;
			$details['date'] = date('Y-m-d');
			$today = date('Y-m-d H:i:s');
			$details['status'] = 'draft';
			$details['type'] = 'revision';

			$this->Sales_model->begin(); 
			$quotation_id = $this->Sales_model->insertSalesRivision($details);

			if ($quotation_id) {
				foreach ($details['items'] as $key => $value) {
					$row['item_id'] = $value['item_id'];
					$row['quantity'] = $value['quantity'];
					$row['note'] = $value['note'];
					$row['total_price'] = $value['total_price'];
					$row['price'] = $value['price'];
					$row['vat_inclusive'] = $value['vat_inclusive'];
					$row['date'] = $today;

					$inserted = $this->Sales_model->insertSalesItems( $quotation_id, $row );
					if(!$inserted){
						$this->Sales_model->rollback();
						$this->redirect("Items adding failed","sales/sales-quotation",FALSE);

					}
				} 

				$this->Sales_model->commit(); 
				$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Sales Revision Added', serialize($details));
				$enc_id = $this->Base_model->encrypt_decrypt( 'encrypt', $quotation_id );
				$this->redirect('Sales Revision Added Successfully',"sales/edit-sales/$enc_id",TRUE);
			}
			else{
				$this->Sales_model->rollback();
				$this->redirect("Revision creating failed, please try again","sales/sales-quotation",FALSE);
			}



		}else{

			$msg="Invalid Sales Quotation";
			$this->redirect($msg,'sales/sales-quotation',false);
		}

		die();
	} 
	public  function package_details($enc_id='')
	{
		$data['title']='Package Details';


		if($enc_id){
			$id=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );

			$data['details']= $this->Sales_model->getPackagesDetails($id, true);
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
				$post_arr['packager_name'] = $this->Sales_model->getUserName($post_arr['packager_id']);
			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']);

			if(element( 'package_id', $post_arr ))				
				$post_arr['package_name'] = $this->Base_model->getPackageName($post_arr['package_id']);

		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'pending';
		}

		$data['project'] = $this->Sales_model->getPackageDetails($post_arr);
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



	function add_sales_items() {

		if ($this->input->is_ajax_request() ) {
			$id=$this->Base_model->encrypt_decrypt('decrypt', $this->input->post('id'));
			$count=$this->Sales_model->getSalesDetailsCount($id);
			if ( $this->validate_save_items() ) {
				$post = $this->input->post();

				$total_items = 0;
				$total_qty = 0;
				$total_amount = 0;
				$total_vat = 0;
				$avg_vat = 0;
				$total_vat_inclusive = 0;
				$this->load->model('Material_model');

				$this->Sales_model->begin();
				$date=date('Y-m-d H:i:s'); 

				foreach ($post['data'] as $key => $value) {
					$search_arr = [
						'item_code' => $value['item_code'],
					];
					$item_details=$this->Material_model->getItemDetails($search_arr);
					$total_items++; 
					$total_qty += $value['quantity']; 
					// $value['total_price'] = ( $value['quantity']*$value['unit_price'] ); 
					$value['price'] = $value['total_price']/$value['quantity']; 
					$value['item_id'] = $item_details['id'];
					$value['vat_inclusive'] =$value['total_vat_inclusive']; 
					$value['vat_value']=$this->Base_model->getVatValue($item_details['vat']);
					$value['note']= explode("</strong>", $value['item_name']);
					$removed=array_shift($value['note']);
					$value['note']=implode(" ",$value['note']);
					$value['date'] = $date; 
					$total_amount += $value['total_price'];  
					$total_vat_inclusive += $value['total_vat_inclusive'];  
					$total_vat += $value['vat_value'];  
					
					$inserted = $this->Sales_model->insertSalesItems( $post['sales_id'], $value );

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
				$post['total_qty'] = $total_qty; 
				$post['total_amount'] = $total_amount; 
				$post['total_vat'] = $total_vat; 
				$post['total_vat_inclusive'] = $total_vat_inclusive; 

				if($this->Sales_model->updateItemDiscount($post['sales_id'],$post))
				{

					$this->Sales_model->commit();
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Sales Items Added', serialize($post));
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
					$this->Sales_model->rollback();
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

			}elseif($count){
				$post = $this->input->post();
				$details=$this->Sales_model->getSumOfSalesItems($id);
				$vat=$this->Sales_model->getVatTotal($id);
				$post['total_items'] = $count; 
				$post['total_qty'] = $details['quantity']; 
				$post['total_amount'] = $details['total_price']; 
				$post['total_vat'] = $vat['vat_total']; 
				$post['total_vat_inclusive'] = $details['vat_inclusive']; 
				if($this->Sales_model->updateItemDiscount($id,$post))
				{

					$this->Sales_model->commit();
					$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Discount Added', serialize($post));
					$response['success'] = TRUE;
					$response['msg'] = 'Successfully added the Discount...!';
					$this->set_session_flash_data( $response['msg'], $response['success']  );

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Sales_model->rollback();
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
				$response['msg'] = 'There is No New Items..!';
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

			$_POST['sales_id'] =  $this->Base_model->encrypt_decrypt('decrypt', $_POST['id']);
			$_POST['by_amount'] =   $_POST['by_amount'];
			$_POST['by_percentage'] =   $_POST['by_percentage'];
			unset($_POST['id']);

			foreach ($_POST['data'] as $key => $value) {

				$_POST['data'][$key]['item_code'] = $value['1'];
				$_POST['data'][$key]['item_name'] = $value['2'];
				$_POST['data'][$key]['unit_price'] = $value['3'];
				$_POST['data'][$key]['vat'] = $value['4'];
				$_POST['data'][$key]['quantity'] = $value['5'];
				$_POST['data'][$key]['total_price'] = $value['6'];
				$_POST['data'][$key]['total_vat_inclusive'] = $value['7'];

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

			$this->form_validation->set_rules('sales_id', 'Item', 'required|callback_checkSalesItemDuplicate'); 
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}

		$res = $this->form_validation->run();
		return $res;
	}

	public  function checkSalesItemDuplicate() {
// print_r();
		$item_codes = array_column($_POST['data'], 'item_code');

// die();
		$exist = $this->Sales_model->checkItemDuplicate($item_codes, $this->input->post('sales_id'));

		$this->form_validation->set_message('checkSalesItemDuplicate', 'Item is already added');

		if($exist){
			return FALSE;
		}
		return TRUE;
	}

	public  function checkUPackageExist($package_id) {

		$exist = $this->Base_model->isPackageExist($package_id);

		$this->form_validation->set_message('checkUPackageExist', 'Package Id not Exist');

		if($exist){
			return TRUE;
		}
		return FALSE;
	}

	public function remove_sales_item($id, $sales_id,$path="edit-sales")
	{
		die();
		$this->load->model('Item_model');
		$post_arr=array();
		$item_id = $this->Base_model->encrypt_decrypt( 'decrypt', $id );	
		$sales_dec_id = $this->Base_model->encrypt_decrypt( 'decrypt', $sales_id );	
		$item_details=$this->Sales_model->gettSalesItemDetails($item_id);

		$sales_details = element(0,$this->Sales_model->getSalesDetails($sales_dec_id));
		$post_arr['total_items'] =-1;
		$post_arr['total_qty'] =-$item_details['quantity'];
		$post_arr['total_amount'] =-$item_details['total_price'];
		$post_arr['total_vat_inclusive'] =-$item_details['vat_inclusive'];
		$post_arr['by_percentage'] =$sales_details['discount_by_percentage'];
		$post_arr['by_amount'] = (($sales_details['total_vat_inclusive']-$item_details['vat_inclusive'])*$sales_details['discount_by_percentage'])/100;


		$items_vat = array_column($sales_details['items'], 'vat_perc', 'sales_item_id');
		$post_arr['total_vat'] = '-'. $items_vat[$item_id];

		$this->Sales_model->begin();
		$details = $this->Sales_model->deleteSalesItem($item_id);

		if($details)
		{
			$this->Sales_model->commit();
			$this->Sales_model->updateItemDiscount($sales_dec_id,$post_arr);
			$this->Base_model->insertIntoActivityHistory(log_user_id(), log_user_id(),'Sales Item Deleted', $sales_dec_id);
			$msg="Item Deleted Successfully";
			$this->redirect($msg,'sales/'.$path.'/'.$sales_id,True);
		} else {
			$this->Sales_model->rollback();
			$msg="Error on Deleting Item";
			$this->redirect($msg,'sales/edit-sales/'.$sales_id,false);
		}
		$this->loadView($data);
	}



	public  function read_package_code()
	{ 
		$data['title']='Read the package code';

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			$package_id = $this->Sales_model->getpackageIdByCode($post_arr['package_code']);
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

	public function get_sales_list_ajax() {
		$user_id=log_user_id();
// print_r($user_id);die();
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$post_arr['items']=true;
			$post_arr['order']= 'id';
			$post_arr['order_by']= 'DESC';
			$count_without_filter = $this->Sales_model->getSalesCount();
			$count_with_filter = $this->Sales_model->getSalesDetailAjax($post_arr, 1);
			$details = $this->Sales_model->getSalesDetailAjax( $post_arr,'',$user_id);
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

// public  function print_details($enc_id='')
// { 
// 	$data['title']='print_details';
// 	$data['date']=date('Y-m-d H:i:s'); 
// 	$this->load->model('Customer_model');
// 	// print_r($enc_id);die();
// 	$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
// 	$details= $this->Sales_model->getSalesDetails($id);
// 	print_r($details);die();
// 	$name=$this->Item_model->getAllItemDetails($id);
// 	$item_name=$name['name'];
// 	$customer_id=$details[0]['customer_id'];
// 	$data['details']=$details;
// 	$data['item_name']=$item_name;
// 	// print_r($item_name);die();
// 	$this->loadView($data);

// }

	public  function print_details($enc_id='')
	{ 
		$data['title']='Sales Quotation';
		$data['date'] = date('Y-m-d H:i:s');  
		$this->load->model('Item_model');
		$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		$data['id']=$id;
		$data['enc_id']=$enc_id;
		$search_arr = [
			'ordering_category' => true,
		]; 
		$details= $this->Sales_model->getSalesDetails($id,$search_arr);


		if(element('0',$details)){

			$temp_items = [];
			foreach($details['0']['items'] as $item)	{
				$temp_items[$item['category_name']][] = $item;
			}
			$details['0']['items'] = $temp_items; 

			$data['details']=$details; 
			$this->loadView($data);
		}

	}
	public function approve_sales($enc_id)
	{
		$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		$status=$this->Sales_model->updateSalesStatus($id);
		if($status){
			$msg = "Status Updated Successfully";
			$this->redirect($msg,'sales/list-sales',True);
		}
		else{
			$msg = "Error on Status Updation";
			$this->redirect($msg,'sales/list-sales',FALSE);
		}

	}
	function save_terms_conditions() {

		if ($this->input->is_ajax_request() ) {



			$post = $this->input->post(); 
			$this->Sales_model->begin();

			$id=$post['id'];
			$terms_conditions=$post['tc'];

			$update = $this->Sales_model->updateTermsCondditions($id,$terms_conditions);
			if ($update) {


				$this->Sales_model->commit();
				$response['success'] = TRUE;
				$response['msg'] = 'Terms Conditions updated!';
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


	function edit_items()
	{
		$this->load->model('Item_model');
		if ($this->input->is_ajax_request() && $this->input->method() == 'post') {
			
			$post_arr = $this->input->post(); 
			// print_r($post_arr);die();
			$data['id1']=$post_arr['id'];
			$data['id'] = $this->Base_model->encrypt_decrypt('decrypt',$post_arr['id']); 
			$data['item_id']=$post_arr['item_id'];
			// print_r($data['id']);die();
			$details= $this->Sales_model->getSalesDetails($data['id']);

// print_r($details);
// die();
			if(element('0',$details)){

				$temp_items = [];
				foreach($details['0']['items'] as $item)	{
					if($item['item_id']==$data['item_id'])
						$temp_items= $item;
				}
			}
			// 	$details['0']['items'] = $temp_items; 

			$data['details']=$temp_items; 
// 			print_r($details);
// die();
			// print_r( $data['details']);die();
			$response['success'] = TRUE; 
			$response['html'] = 
			$this->smarty->view("salesman/sales/edit_items.tpl", $data, TRUE);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}else{
			die('No direct Access');
		} 
	} 


	function update_sales(){

		if ($this->input->post('edit_item') && $this->validate_sales() ) {
			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			$this->Sales_model->begin();
			$updated = $this->Sales_model->UpdateSalesItems($post_arr);


			if ($updated) {
				$this->Sales_model->commit();
				$this->redirect("Sales Items  updated","sales/edit-sales/{$post_arr['id']}", TRUE );
			} else{
				$this->Sales_model->rollback();
				$this->redirect("Updating Sales Items failed ","sales/edit-sales/{$post_arr['id']}",FALSE);
			}
		}
	}

	private function validate_sales()
	{ 
		$this->form_validation->set_rules('spec1', 'Description', 'trim|required');
		$this->form_validation->set_rules('quantity', 'Quantity', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');

		$result =  $this->form_validation->run();
		return $result;
	}


}
