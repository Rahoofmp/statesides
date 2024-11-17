<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends Base_model {

	function __construct() {
		parent::__construct();

	}

	public function addItems($post)
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('code', $post['code'])
		->set('name', $post['name'])
		->set('note', $post['note'])
		->set('date',$date)
		->set('category', $post['main_category'])
		->set('sub_category', $post['sub_category'])
		->set('cost', $post['cost'])
		->set('vat', $post['vat'])
		->set('price', $post['price'])
		->set('unit', $post['unit'])
		->set('total_quantity', $post['total_quantity'])
		->set('type', $post['type']);

		$res = $this->db->insert('items');
		return $this->db->insert_id();
	}
	public function updateItems($post,$id)
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('code', $post['code'])
		->set('name', $post['name'])
		->set('note', $post['note'])
		->set('date',$date)
		->set('category', $post['main_category'])
		->set('cost', $post['cost'])
		->set('vat', $post['vat'])
		->set('price', $post['price'])
		->set('total_quantity', $post['total_quantity'])
		->set('unit', $post['unit'])
		->set('type', $post['type'])
		->where('id',$id);

		$res = $this->db->update('items');
		return $res;
	}
	public function getMaxItemId() {
		$id = NULL;
		$this->db->select_max('id');
		$this->db->from('items');
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$id = $row->id;
		}
		return $id;
	}

	public function addItemImages($item_id='',$image='')
	{
		// print_r($item_id);die();
		$this->db->set('item_id',$item_id)
		->set('image',$image)
		->set('date',date('Y-m-d H:i:s'));
		return $this->db->insert('item_images');
	}
	

	public function getAllItemDetails($id='', $images=true)
	{
		$this->load->model('Delivery_model');
		$details=[];
		$this->db->select('it.*,c.category_name,c.main_category,v.name as vat_name,v.value')
		->from('items it')
		->join('category c', 'it.category = c.id')
		->join('vat v', 'it.vat = v.id')
		->where('it.status!=','0');

		if ($id) {
			$this->db->where('it.id',$id);
		}
		$res=$this->db->get();

		// echo $this->db->last_query();
		// die();

		foreach($res->result_array() as $row)
		{
			$row['enc_id']=$this->encrypt_decrypt('encrypt',$row['id']);
			if ($row['main_category']!=0) {
				$row['sub_category_name']=$row['category_name'];
				$row['category_name']=$this->Delivery_model->getCategoryName($row['main_category']);
			}
			else{
				$row['sub_category_name']=$this->Delivery_model->getCategoryName($row['main_category']);
			}
			
			if($images == true){
				$row['item_images']=$this->getItemImages($row['id']);
			}

			if($id){
				
				return $row;
			}

			$details[]=$row;
		}
		return $details;
	}

	public function getItemImages($item_id='')
	{

		$this->db->select('*')
		->from('item_images');
		if ($item_id) {
			$this->db->where('item_id',$item_id);
		}

		$res=$this->db->get();
		$details=[];
		foreach($res->result_array() as $row)
		{
			$details[]=$row;
		}

		return $details;

	}
	public function deleteItem($id)
	{
		$this->db->set('status','0');
		$this->db->where('id',$id);
		$res=$this->db->update('items');

		if($this->db->affected_rows()){
			return $res;
		}

		return false;
	}

	public function deleteItemImage($image_id='')
	{
		// print_r($image_id);die();
		$this->db->where('id',$image_id);
		$res=$this->db->delete('item_images');
		// ->where('id',$image_id);
		return $res;
	}
	public function getItemCode($field='',$id)
	{
		$details=null;
		$this->db->select($field)
		->where('id', $id)
		->where('status',1)
		->from('items');  
		$res=$this->db->get();
		// echo $this->db->last_query();;
		foreach($res->result() as $row)
		{
			return $row->$field;
		}
		return $details;
	}
	public function getSubCategoryAjax($search_arr,$status='pending')
	{
        // print_r($search_arr);die();
		$details=[];
		$this->db->select('category_name as text,id')
		->from('category');

		if (element('main_category',$search_arr)) {
			$this->db->where('main_category',$search_arr['main_category']);
		}
		if ($status!='all') {
			$this->db->where('status',$status);
		}
		$query = $this->db->get();
		foreach($query->result_array() as $row){
			$details[] = $row;
		}
		return $details;
	}
	public function checkHasSubCategory($main_category) {
		$this->db->select("COUNT(id) as count");
		$this->db->from("category");
		$this->db->where('main_category', $main_category);
		$this->db->where('status!=', 'deleted');

		$query = $this->db->get();
		
		foreach ($query->result()AS $row) {
			if($row->count > 0){
				return true;
			}else{
				return false;
			}
		}

	}


	public function getItemsCount()
	{
		$this->db->select('id');
		$this->db->from('items');
		$count = $this->db->count_all_results();
		return $count;
	}

	public function getItemsAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];


		$this->db->select('it.*')
		->select('ct.category_name, ct.main_category')
		->select('v.name as vat_name,v.value as vat_value')
		->from('items it')        
		->join('vat v','it.vat = v.id')
		->join('category ct','ct.id = it.category');

		if (element('item_id',$post_arr)) { 
			$this->db->where('it.id', $post_arr['item_id']);
		}

		if ( element( 'category_id', $post_arr) ) {
			$this->db->where('it.category', $post_arr['category_id'] );
		}

		if ( element( 'type', $post_arr) != 'all' ) {
			$this->db->where('it.type', $post_arr['type'] );
		} 

		if ( element( 'status', $post_arr) != 'all' ) {
			$this->db->where('it.status', $post_arr['status'] );
		}


		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

			$where = "(it.code LIKE '%$searchValue%' 
			OR it.name LIKE '%$searchValue%'
			OR ct.category_name LIKE '%$searchValue%'
			OR it.type LIKE '%$searchValue%' )";
			$this->db->where($where);
		}

		if($count) {
			return $this->db->count_all_results();
		}

		$this->db->limit($rowperpage, $row);
		$this->db->order_by('it.id', 'DESC');
		$query = $this->db->get();  
// echo $this->db->last_query();
// die();
		$i=1;

		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           

			if (element('images',$post_arr)==true) { 
				$row['images']= $this->getItemImages($row['id']);

			} 
			$details[] = $row;
			$i++;
		}
		return $details;

	}


	public function getItemNote($id)
	{
		$details=[];
		$this->db->select('it.note')
		->from('items it')
		->where('it.status!=','0')
		->where('it.id',$id) ;
		$res=$this->db->get();

		foreach($res->result_array() as $row)
		{  
			return $row['note']; 
		}
		return $details;
	}




	public function getItemReceiptAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];


		$this->db->select('jo.project_id,mr.*,it.*')
		->select('si.user_name as supplier_user_name, si.name as supplier_name, si.email')
		->join('consumable_receipt_items mri','mri.material_receipt_id=mr.id','left')
		->join('job_orders jo','jo.id = mri.job_order_id','left')    
		->join('items it','it.id=mri.item_id')
		->join('supplier_info si','si.id = mr.supplier_id','left')
		->from('consumable_receipt mr') ;
		$where2 = "(it.type='consumables' OR it.type='tools' )";
		$this->db->where($where2);
		if (element('reciept_id',$post_arr)) { 
			$this->db->where('mr.id', $post_arr['reciept_id']);
		}
		if (element('project_id',$post_arr)) { 
			$this->db->where('jo.project_id', $post_arr['project_id']);
		}

		if ( element( 'supplier_user_name', $post_arr) ) {
			$this->db->where('si.user_name', $post_arr['supplier_user_name'] );
		}

		if ( element( 'bill_number', $post_arr) ) {
			$this->db->where('mr.bill_number', $post_arr['bill_number'] );
		}

		if ( element( 'name', $post_arr) ) {
			$this->db->where('mr.name', $post_arr['name'] );
		}
		if ( element( 'status', $post_arr) ) {
			$this->db->where('mr.status', $post_arr['status'] );
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('mr.date_added >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('mr.date_added <=', $end_date);
		}


		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

			$where = "(mr.bill_number LIKE '%$searchValue%' 
			OR si.user_name LIKE '%$searchValue%'
			OR mr.name LIKE '%$searchValue%'
			OR mr.date_added LIKE '%$searchValue%' )";
			$this->db->where($where);
		}

		// $this->db->group_by('mr.id');
		// $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
		if($count) {
			return $this->db->count_all_results();
		}
		$this->db->limit($rowperpage, $row);
		$this->db->order_by('mr.id', 'DESC');
		$query = $this->db->get();
		// echo $this->db->last_query();die();  

		$i=1;

		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           


			$details[] = $row;
			$i++;
		}
		return $details;

	}


	public function getItemReceiptCount()
	{
		$this->db->select('id');
		$this->db->from('material_receipt');
		$count = $this->db->count_all_results();
		return $count;
	}

	public function insertConsumableReturn($details, $items)
	{

        // print_r($items);
        // die();
		$res = $this->db->insert_batch('consumable_return', $details);
		$receipt_id = $this->db->insert_id(); 

		if($receipt_id){
			if($this->Item_model->insertReturnConsumableReciept($items, $receipt_id)){
				return $receipt_id;
			}else{
				return  FALSE;
			} 
		}else{
			return  FALSE;
		}

	}
	public function insertReturnConsumableReciept($items, $issue_id)
	{

		foreach ($items as $key => $value) {
			$items[$key]['issue_id'] = $issue_id;
		}
		$receipt_id =  $this->db->insert_batch('consumable_return_receipts', $items); 
		
		if($receipt_id){
			if($this->Item_model->addItemStock($items)){
				$this->addReceiptStock($items);
				$this->addIssueStock($items);
				return TRUE;
			}else{
				return  FALSE;
			}
		}else{
			return  FALSE;
		}
	}
	public function addItemStock($items) {
		foreach ($items as $key => $value) {
		// print_r($items);die();
			
			if(element('issued_qty',$value))
				$this->db->set('total_quantity', 'total_quantity + ' . $value['issued_qty'] , FALSE);
			else
				$this->db->set('total_quantity', 'total_quantity + ' .$value['qty'] , FALSE);
			$this->db->where('id', $value['item_id'] );
			$query = $this->db->update('items');
			if(!$query){
				return  FALSE;
			}

		}

		return $query;
	}

	public function deductItemStock($items) {
		foreach ($items as $key => $value) {
			if(element('qty',$value))
				$this->db->set('total_quantity', 'total_quantity - ' . $value['qty'] , FALSE);
			else
				$this->db->set('total_quantity', 'total_quantity - ' . $value['issued_qty'] , FALSE);
			$this->db->where('id', $value['item_id'] );
			$query = $this->db->update('items');
			if(!$query){
				return  FALSE;
			}

		}

		return $query;
	}


	public function insertConsumableDamage($details, $items)
	{

		$res = $this->db->insert_batch('consumable_damage', $details);
		$receipt_id = $this->db->insert_id(); 

		if($receipt_id){
			if($this->Item_model->insertDamageConsumableReciept($items, $receipt_id)){
				return $receipt_id;
			}else{
				return  FALSE;
			} 
		}else{
			return  FALSE;
		}

	}
	public function insertDamageConsumableReciept($items, $issue_id)
	{

		foreach ($items as $key => $value) {
			$items[$key]['issue_id'] = $issue_id;
		}
		$receipt_id =  $this->db->insert_batch('consumable_damage_receipts', $items); 
		if($receipt_id){
			if($this->Item_model->deductItemStock($items)){
				$this->addReceiptStock($items);
				$this->addIssueStock($items);
				return TRUE;
			}else{
				return  FALSE;
			}
		}else{
			return  FALSE;
		}
	}
	public function getMaxReturnId() {
		$id = NULL;
		$this->db->select_max('id');
		$this->db->from('consumable_return');
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$id = $row->id;
		}
		return $id;
	}
	public function getMaxDamageId() {
		$id = NULL;
		$this->db->select_max('id');
		$this->db->from('consumable_damage');
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$id = $row->id;
		}
		return $id;
	}


	public function getReturnBillNumber($issue_id) {
		$voucher_number = NULL;
		$this->db->select('voucher_number');
		$this->db->from('consumable_return');
		$this->db->where('id', $issue_id);
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$voucher_number = $row->voucher_number;
		}
		return $voucher_number;
	}

	public function getMaterialReturnCount()
	{
		$this->db->select('id');
		$this->db->from('consumable_return');
		$count = $this->db->count_all_results();
		return $count;
	}

	public function getMaterialReturnAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];

		$this->db->select('ci.*,li.user_name as receiver_name,le.user_name as return_name')
		->from('consumable_return ci')
		->join('login_info le','le.user_id=ci.return_by','left')
		// ->join('consumable_return_receipts cr','cr.issue_id=ci.id','left')
		->join('login_info li','li.user_id=ci.received_by','left');        


		if (element('issue_id',$post_arr)) { 
			$this->db->where('ci.id', $post_arr['issue_id']);
		}

		if ( element( 'project_id', $post_arr) ) {
			$this->db->where('jo.project_id', $post_arr['project_id'] );
		}


		if ( element( 'voucher_number', $post_arr) ) {
			$this->db->where('ci.voucher_number', $post_arr['voucher_number'] );
		}

		if ( element( 'voucher_date', $post_arr) ) {
			$this->db->where('ci.voucher_date', $post_arr['voucher_date'] );
		}
		if ( element( 'status', $post_arr) ) {
			$this->db->where('ci.status', $post_arr['status'] );
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('ci.date_added >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('ci.date_added <=', $end_date);
		}


		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

			$where = "(ci.voucher_number LIKE '%$searchValue%' 


			OR ci.date_added LIKE '%$searchValue%' )";
			$this->db->where($where);
		}

		if($count) {
			return $this->db->count_all_results();
		}

		$this->db->limit($rowperpage, $row);
		$this->db->order_by('ci.id', 'DESC');
		$query = $this->db->get();  
        // echo $this->db->last_query();die();
		$i=1;

		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['allocated_qty']=$this->getIssueTotalAllocatedQty($row['issue_id']);           
			$row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}          

            // if (element('items',$post_arr)==true) {
            //     $search_arr['material_receipt_id'] = $row['id'];

            // }
			$row['items']= $this->getMaterialReturnItemDetails($row['id']);
			if (element('issue_id',$post_arr)) { 
				return $row;
			}

			$details[] = $row;
			$i++;
		}
		return $details;

	}




	public function getReturnTotalAllocatedQty($issue_id='')
	{
		$this->db->select_sum('cr.total_issued_qty')
		->from('consumable_return cr')
        // ->from('material_receipt_items mri')
		->join('consumable_return_receipts cir', 'cr.id = cir.issue_id');

		if ($issue_id) {
			$this->db->where('cir.issue_id',$issue_id);
		}
		$query  = $this->db->get(); 
		// echo $this->db->last_query();die();
		foreach($query->result_array() as $row){
			$total_qty =  $row["total_issued_qty"];
		}
		return $total_qty;


	}


	public function getMaterialReturnItemDetails($issue_id='',$post_arr=''){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('cir.*,cr.issue_id as issued_id')
		->select('cr.*')
		->from('consumable_return_receipts cir')
		->where('cir.issue_id',$issue_id)
		->join('consumable_return cr', 'cir.issue_id = cr.id')
		// ->join('supplier_info si', 'cr.supplier_id = si.id')
		->where('cir.status', 'active');
		$query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['job_order']= $this->Base_model->getJobOrderId($row['job_order_id'] );           
			$row['item_name']= $this->Base_model->getItemNameById($row['item_id']);
			$row['allocated_qty']=$this->getIssueTotalAllocatedQty($row['issued_id']);           
			$row['difference']=  $row['allocated_qty']-$row['issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}                 
            // if ( element( 'id', $post_arr ) ) {
            //     return $row;
            // }

			$details[] = $row;
		}
		return $details;

	}




	public function getMaterialDamageCount()
	{
		$this->db->select('id');
		$this->db->from('consumable_damage');
		$count = $this->db->count_all_results();
		return $count;
	}

	public function getMaterialDamageAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];

		$this->db->select('ci.*,li.user_name as voucher_entered_name,ln.user_name as reported_name,le.user_name as damager_name')
		->from('consumable_damage ci')
		// ->join('consumable_damage_receipts cir','cir.issue_id=ci.id')
		->join('login_info le','le.user_id=ci.damaged_lost_by','left')     
		->join('login_info ln','ln.user_id=ci.reported_by','left')   
		->join('login_info li','li.user_id=ci.voucher_entered_by','left');        


		if (element('issue_id',$post_arr)) { 
			$this->db->where('ci.id', $post_arr['issue_id']);
		}

		if ( element( 'project_id', $post_arr) ) {
			$this->db->where('jo.project_id', $post_arr['project_id'] );
		}


		if ( element( 'voucher_number', $post_arr) ) {
			$this->db->where('ci.voucher_number', $post_arr['voucher_number'] );
		}

		if ( element( 'voucher_date', $post_arr) ) {
			$this->db->where('ci.voucher_date', $post_arr['voucher_date'] );
		}
		if ( element( 'status', $post_arr) ) {
			$this->db->where('ci.status', $post_arr['status'] );
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('ci.date_added >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('ci.date_added <=', $end_date);
		}


		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

			$where = "(ci.voucher_number LIKE '%$searchValue%' 


			OR ci.date_added LIKE '%$searchValue%' )";
			$this->db->where($where);
		}

		if($count) {
			return $this->db->count_all_results();
		}

		$this->db->limit($rowperpage, $row);
		$this->db->order_by('ci.id', 'DESC');
		$query = $this->db->get();  
        // echo $this->db->last_query();die();
		$i=1;

		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['allocated_qty']=$this->getIssueTotalAllocatedQty($row['issue_id']);           
			$row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}          

            // if (element('items',$post_arr)==true) {
            //     $search_arr['material_receipt_id'] = $row['id'];

            // }
			$row['items']= $this->getMaterialDamageItemDetails($row['id']);
			if (element('issue_id',$post_arr)) { 
				return $row;
			}

			$details[] = $row;
			$i++;
		}
		return $details;

	}



	public function getDamageTotalAllocatedQty($issue_id='')
	{
		$this->db->select_sum('cr.total_issued_qty')
		->from('consumable_damage cr')
        // ->from('material_receipt_items mri')
		->join('consumable_damage_receipts cir', 'cr.issue_id = cir.receipt_id');

		if ($issue_id) {
			$this->db->where('cir.receipt_id',$issue_id);
		}
		$query  = $this->db->get(); 
		// echo $this->db->last_query();die();
		foreach($query->result_array() as $row){
			$total_qty =  $row["total_issued_qty"];
		}
		return $total_qty;


	}


	public function getMaterialDamageItemDetails($issue_id='',$post_arr=''){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('cir.*,cr.issue_id as issued_id')
		->select('cr.*')
		->from('consumable_damage_receipts cir')
		->where('cir.issue_id',$issue_id)
		->join('consumable_damage cr', 'cir.issue_id = cr.id')
		// ->join('supplier_info si', 'cr.supplier_id = si.id')
		->where('cir.status', 'active');
		$query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['job_order']= $this->Base_model->getJobOrderId($row['job_order_id'] );           
			$row['item_name']= $this->Base_model->getItemNameById($row['item_id']);
			$row['allocated_qty']=$this->getIssueTotalAllocatedQty($row['issued_id']);           
			$row['difference']=  $row['allocated_qty']-$row['issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}                 
            // if ( element( 'id', $post_arr ) ) {
            //     return $row;
            // }

			$details[] = $row;
		}
		return $details;

	}
	public function getDamageBillNumber($issue_id) {
		$voucher_number = NULL;
		$this->db->select('voucher_number');
		$this->db->from('consumable_damage');
		$this->db->where('id', $issue_id);
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$voucher_number = $row->voucher_number;
		}
		return $voucher_number;
	}
	public function updateVoucherReturnDetails($details, $id)
	{

        // print_r($receipt);
        // die();
		$this->db->set('voucher_date',$details['voucher_date'])
		->set('voucher_number',$details['voucher_number'])
		->set('return_by',$details['return_by'])
		->set('received_by',$details['received_by'])
		->set('last_updated',date('Y-m-d H:i:s'))
		->where('id',$id);

		$res = $this->db->update('consumable_return');
		return $res;
	}


	public function insertConsumableReciept($details, $items)
	{


        // print_r($receipt);
        // die();
		$res = $this->db->insert_batch('consumable_receipt', $details);
		$receipt_id = $this->db->insert_id(); 

		if($receipt_id){
			if($this->insertConsumableItems($items, $receipt_id)){
				return $receipt_id;
			}else{
				return  FALSE;
			} 
		}else{
			return  FALSE;
		}

	}

	public function insertConsumableItems($items, $receipt_id)
	{
		foreach ($items as $key => $value) {
			$items[$key]['material_receipt_id'] = $receipt_id;
		}
		$receipt_id =  $this->db->insert_batch('consumable_receipt_items', $items); 

		if($receipt_id){
			if($this->Item_model->addItemStock($items)){
				return TRUE;
			}else{
				return  FALSE;
			}
		}else{
			return  FALSE;
		}
	}

	public function getConsumableReceiptCount()
	{
		$this->db->select('id');
		$this->db->from('consumable_receipt');
		$count = $this->db->count_all_results();
		return $count;
	}



	// public function getConsumableReceiptAjax($post_arr=[],$count=''){
	// 	$details = array(); 
	// 	$row = $post_arr['start'];
	// 	$rowperpage = $post_arr['length'];


	// 	$this->db->select('cr.*,jo.project_id')
	// 	->select('si.user_name as supplier_user_name, si.name as supplier_name, si.email,li.user_name as employee_name')
	// 	->join('consumable_receipt_items cri','cri.material_receipt_id=cr.id')
	// 	->join('job_orders jo','jo.id = cri.job_order_id')    
	// 	->join('supplier_info si','si.id = cr.supplier_id')
	// 	->join('login_info li','li.user_id = cr.name','left')
	// 	->from('consumable_receipt cr') ;

	// 	if (element('reciept_id',$post_arr)) { 
	// 		$this->db->where('cr.id', $post_arr['reciept_id']);
	// 	}
	// 	if (element('project_id',$post_arr)) { 
	// 		$this->db->where('jo.project_id', $post_arr['project_id']);
	// 	}

	// 	if ( element( 'supplier_user_name', $post_arr) ) {
	// 		$this->db->where('si.user_name', $post_arr['supplier_user_name'] );
	// 	}

	// 	if ( element( 'bill_number', $post_arr) ) {
	// 		$this->db->where('cr.bill_number', $post_arr['bill_number'] );
	// 	}

	// 	// if ( element( 'name', $post_arr) ) {
	// 	// 	$this->db->where('cr.name', $post_arr['name'] );
	// 	// }
	// 	if ( element( 'name', $post_arr) ) {
	// 		$this->db->where('cr.name', $post_arr['name'] );
	// 	}
	// 	if ( element( 'status', $post_arr) ) {
	// 		$this->db->where('cr.status', $post_arr['status'] );
	// 	}

	// 	if (element('start_date',$post_arr)) {
	// 		$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
	// 		$this->db->where('cr.date_added >=', $start_date); 
	// 	}

	// 	if (element('end_date',$post_arr)) {
	// 		$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
	// 		$this->db->where('cr.date_added <=', $end_date);
	// 	}


	// 	$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
	// 	if($searchValue) { 

    //         // $searchValue = $post_arr['search']['value'];

	// 		$where = "(cr.bill_number LIKE '%$searchValue%' 
	// 		OR si.user_name LIKE '%$searchValue%'
	// 		OR cr.name LIKE '%$searchValue%'
	// 		OR cr.date_added LIKE '%$searchValue%' )";
	// 		$this->db->where($where);
	// 	}

	// 	$this->db->group_by('cr.id');
	// 	$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
	// 	if($count) {
	// 		return $this->db->count_all_results();
	// 	}
	// 	$this->db->limit($rowperpage, $row);
	// 	$this->db->order_by('cr.id', 'DESC');
	// 	$query = $this->db->get();
    //     // echo $this->db->last_query();die();  

	// 	$i=1;

	// 	foreach($query->result_array() as $row){
	// 		$row['index'] =$post_arr['start']+$i;
	// 		$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           

	// 		if (element('items',$post_arr)==true) {
	// 			$search_arr['material_receipt_id'] = $row['id'];
	// 			$row['items']= $this->getConsumableItemDetails($search_arr);

	// 		}
	// 		if (element('reciept_id',$post_arr)) { 
	// 			return $row;
	// 		}

	// 		$details[] = $row;
	// 		$i++;
	// 	}
	// 	return $details;

	// }

	public function getConsumableReceiptAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];


		$this->db->select('cr.*')
		->select('si.user_name as supplier_user_name, si.name as supplier_name, si.email,li.user_name as employee_name')
		->join('consumable_receipt_items cri','cri.material_receipt_id=cr.id')
		// ->join('job_orders jo','jo.id = cri.job_order_id')    
		->join('supplier_info si','si.id = cr.supplier_id')
		->join('login_info li','li.user_id = cr.name','left')
		->from('consumable_receipt cr') ;

		if (element('reciept_id',$post_arr)) { 
			$this->db->where('cr.id', $post_arr['reciept_id']);
		}
		if (element('project_id',$post_arr)) { 
			$this->db->where('jo.project_id', $post_arr['project_id']);
		}

		if ( element( 'supplier_user_name', $post_arr) ) {
			$this->db->where('si.user_name', $post_arr['supplier_user_name'] );
		}

		if ( element( 'bill_number', $post_arr) ) {
			$this->db->where('cr.bill_number', $post_arr['bill_number'] );
		}

		if ( element( 'name', $post_arr) ) {
			$this->db->where('cr.name', $post_arr['name'] );
		}
		if ( element( 'status', $post_arr) ) {
			$this->db->where('cr.status', $post_arr['status'] );
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('cr.date_added >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('cr.date_added <=', $end_date);
		}


		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

			$where = "(cr.bill_number LIKE '%$searchValue%' 
			OR si.user_name LIKE '%$searchValue%'
			OR cr.name LIKE '%$searchValue%'
			OR cr.date_added LIKE '%$searchValue%' )";
			$this->db->where($where);
		}

		$this->db->group_by('cr.id');
		$this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
		if($count) {
			return $this->db->count_all_results();
		}
		$this->db->limit($rowperpage, $row);
		$this->db->order_by('cr.id', 'DESC');
		$query = $this->db->get();
        // echo $this->db->last_query();die();  

		$i=1;

		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           

			if (element('items',$post_arr)==true) {
				$search_arr['material_receipt_id'] = $row['id'];
				$row['items']= $this->getConsumableItemDetails($search_arr);

			}
			if (element('reciept_id',$post_arr)) { 
				return $row;
			}

			$details[] = $row;
			$i++;
		}
		return $details;

	}


	public function getConsumableItemDetails($post_arr){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('cri.*')
		->select('i.code item_code, i.name item_name, i.price, i.type')
		->select('jo.order_id job_order_id')
		->from('consumable_receipt_items cri')
		->join('items i', 'i.id = cri.item_id')
		->join('job_orders jo', 'jo.id = cri.job_order_id', 'LEFT')
		->where( 'cri.status', 'active');

		if ( element( 'job_order_id', $post_arr ) ) {
			$this->db->where( 'cri.job_order_id', $post_arr['job_order_id']);
		}

		if ( element( 'material_receipt_id', $post_arr ) ) {
			$this->db->where( 'cri.material_receipt_id', $post_arr['material_receipt_id']);
		}

		if ( element( 'item_id', $post_arr ) ) {
			$this->db->where( 'cri.item_id', $post_arr['item_id']);
		}

		if ( element( 'item_type', $post_arr ) ) {
			$this->db->where_in( 'i.type', $post_arr['item_type']);
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('cri.date_added >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('cri.date_added <=', $end_date);
		}

		$query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			if ( element( 'id', $post_arr ) ) {
				return $row;
			}

			$details[] = $row;
		}
		return $details;

	}
	public function deleteConsumableReceipt($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('consumable_receipt');
		return $res;
	}


	public function getConsuableItem($item_id){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('*')
		->from('consumable_receipt_items')
		->where( 'status', 'active')
		->where('id',$item_id);
		$query = $this->db->get();   

		foreach($query->result_array() as $row){


			$details=$row;

		}
		return $details;

	}
	public function deleteConsumableItem($id)
	{
		$item_details=$this->getConsuableItem($id);
		$total_cost=$item_details['qty']*$item_details['cost'];

		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('consumable_receipt_items');
        // echo $this->db->last_query();
		if ($res) {
			$this->db->set('total_qty', 'ROUND(total_qty- ' . $item_details['qty']. ',2)', FALSE);
			$this->db->set('total_cost', 'ROUND(total_cost- ' . $total_cost. ',2)', FALSE);
			$this->db->where('id',$item_details['material_receipt_id']);
			$ded=$this->db->update('consumable_receipt');

		}
        // die();
		return $res;
	}


	public function updateConsumableReceipt($details, $id)
	{


		$this->db->set('bill_number',$details['bill_number'])
		->set('name',$details['employee_id'])
		->set('supplier_id',$details['supplier_id'])
		->where('id',$id);

		$res = $this->db->update('consumable_receipt');

		return $res;
	}

	public function updateAllConsumableReceipt($details, $items,$receipt_id)
	{


        // print_r($receipt);
        // die();
		$res = $this->db->update_batch('consumable_receipt', $details,'id');


		if($res){
			if($this->Item_model->insertConsumableItems($items, $receipt_id)){
				return $receipt_id;
			}else{
				return  FALSE;
			} 
		}else{
			return  FALSE;
		}

	}


	public function getMaxConsumableIssueId() {
		$id = NULL;
		$this->db->select_max('id');
		$this->db->from('consumable_issue');
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$id = $row->id;
		}
		return $id;
	}

	public function getConsumableReceiptItemDetails($post_arr){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('cri.*')
		->select('cr.name')
		// ->select('i.total_quantity qty')
        // ->select('jo.order_id job_order_id')
		->from('consumable_receipt_items cri')
		->join('consumable_receipt cr', 'cr.id = cri.material_receipt_id')
		// ->join('items i', 'i.id = cri.item_id')

		->where( 'cri.status', 'active');

		// if ( element( 'job_orderid', $post_arr ) ) {
		// 	$this->db->where( 'cri.job_order_id', $post_arr['job_orderid']);
		// }

		if ( element( 'material_receipt_id', $post_arr ) ) {
			$this->db->where( 'cri.material_receipt_id', $post_arr['material_receipt_id']);
		}

		if ( element( 'item_id', $post_arr ) ) {
			$this->db->where( 'cri.item_id', $post_arr['item_id']);
		}

        // if (element('start_date',$post_arr)) {
        //     $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
        //     $this->db->where('mri.date_added >=', $start_date); 
        // }

        // if (element('end_date',$post_arr)) {
        //     $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
        //     $this->db->where('mri.date_added <=', $end_date);
        // }
		$this->db->limit(1);
		$query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );         
			// $row['issued_qty']= $this->getIssuedQty($row['material_receipt_id'],$row['job_order_id'],$row['item_id']);  
			$row['issued_qty']=$post_arr['issued_qty'];
			$row['difference']=$row['qty']-$post_arr['issued_qty'];       
            // $row['issued_qty']= 0;         
			$details = $row;
		}
		return $details;

	}
	public function getIssuedQty($receipt_id='',$job_order_id='',$item_id='')
	{
		$count = 0 ;
		$this->db->select_sum("issued_qty");
		$this->db->from('consumable_issue_receipts');
		$this->db->where('receipt_id', $receipt_id);
		$this->db->where('job_order_id', $job_order_id);
		$this->db->where('item_id', $item_id);

		$query = $this->db->get(); 
		// echo $this->db->last_query();die();
		foreach ($query->result() AS $row) {

			return $row->issued_qty;
		}
	}
	public function insertConsumableIssue($details, $items)
	{

        // print_r($receipt);
        // die();
		$res = $this->db->insert_batch('consumable_issue', $details);
		$receipt_id = $this->db->insert_id(); 

		if($receipt_id){
			if($this->Item_model->insertIssuedConsumableReciept($items, $receipt_id)){
				return $receipt_id;
			}else{
				return  FALSE;
			} 
		}else{
			return  FALSE;
		}

	}


	public function insertIssuedConsumableReciept($items, $issue_id)
	{

		foreach ($items as $key => $value) {
			$items[$key]['issue_id'] = $issue_id;
		}
		$receipt_id =  $this->db->insert_batch('consumable_issue_receipts', $items); 
		if($receipt_id){
			if($this->Item_model->deductItemStock($items)){
				$this->deductIssueStock($items);
				return TRUE;
			}else{
				return  FALSE;
			}
		}else{
			return  FALSE;
		}
	}

	public function getConsumableIssueCount()
	{
		$this->db->select('id');
		$this->db->from('consumable_issue');
		$count = $this->db->count_all_results();
		return $count;
	}



	public function getConsumableIssueAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];

		$this->db->select('ci.*,li.user_name as receiver_name,le.user_name as requested_name')
		->from('consumable_issue ci')
		->join('login_info le','le.user_id=ci.requested_by','left')
		->join('login_info li','li.user_id=ci.received_by','left');      


		if (element('issue_id',$post_arr)) { 
			$this->db->where('ci.id', $post_arr['issue_id']);
		}

		if ( element( 'project_id', $post_arr) ) {
			$this->db->where('jo.project_id', $post_arr['project_id'] );
		}


		if ( element( 'voucher_number', $post_arr) ) {
			$this->db->where('ci.voucher_number', $post_arr['voucher_number'] );
		}

		if ( element( 'voucher_date', $post_arr) ) {
			$this->db->where('ci.voucher_date', $post_arr['voucher_date'] );
		}
		if ( element( 'status', $post_arr) ) {
			$this->db->where('ci.status', $post_arr['status'] );
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('ci.date_added >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('ci.date_added <=', $end_date);
		}


		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

			$where = "(ci.voucher_number LIKE '%$searchValue%' 


			OR ci.date_added LIKE '%$searchValue%' )";
			$this->db->where($where);
		}

		if($count) {
			return $this->db->count_all_results();
		}

		$this->db->limit($rowperpage, $row);
		$this->db->order_by('ci.id', 'DESC');
		$query = $this->db->get();  
        // echo $this->db->last_query();die();
		$i=1;

		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['allocated_qty']=$this->getTotalAllocatedQty($row['id']);      
			$row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}          

            // if (element('items',$post_arr)==true) {
            //     $search_arr['material_receipt_id'] = $row['id'];

            // }
			$row['items']= $this->getConsumableIssuedItemDetails($row['id']);
			if (element('issue_id',$post_arr)) { 
				return $row;
			}

			$details[] = $row;
			$i++;
		}
		return $details;

	}

	public function getTotalAllocatedQty($issue_id='')
	{
		$this->db->select_sum('cr.total_qty')
		->from('consumable_receipt cr')
        // ->from('material_receipt_items mri')
		->join('consumable_issue_receipts cir', 'cr.id = cir.receipt_id');
		// ->join('items i', 'i.id = cir.item_id');

		if ($issue_id) {
			$this->db->where('cir.issue_id',$issue_id);
		}
		$query  = $this->db->get();
		foreach($query->result_array() as $row){
			$total_qty =  $row["total_qty"];
		}
		return $total_qty;


	}
	public function getItemQty($id='')
	{
		$this->db->select_sum('total_quantity')
		->from('items')
		->where('id',$id);
		$query  = $this->db->get(); 
		foreach($query->result_array() as $row){
			$total_qty =  $row["total_quantity"];
		}
		return $total_qty;


	}




	public function deleteConsumableIssue($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('consumable_issue');
		return $res;
	}


	public function getConsumableIssuedItemDetails($issue_id='',$post_arr=''){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('cir.*')
		->select('cr.*, si.user_name as supplier_user_name')
		->select('cir.*')
		->from('consumable_issue_receipts cir')
		->join('consumable_receipt cr', 'cir.receipt_id = cr.id')
		->join('supplier_info si', 'cr.supplier_id = si.id')
		->where('cir.status', 'active');
		if($issue_id)
			$this->db->where('cir.issue_id',$issue_id);

		$query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

		foreach($query->result_array() as $row){


			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['job_order']= $this->Base_model->getJobOrderId($row['job_order_id'] );           
			$row['item_name']= $this->Base_model->getItemNameById($row['item_id']);
			$row['allocated_qty']=$this->getTotalAllocatedQty($row['issued_qty']);           
			$row['difference']=  $row['allocated_qty']-$row['issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}                 
            // if ( element( 'id', $post_arr ) ) {
            //     return $row;
            // }

			$details[] = $row;

		}
		return $details;

	}


	public function deleteConsumableIssueItem($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('consumable_issue_receipts');
        // echo $this->db->last_query();
        // die();
		return $res;
	}

	public function updateVoucherDetails($details, $id)
	{

        // print_r($receipt);
        // die();
		$this->db->set('voucher_date',$details['voucher_date'])
		->set('voucher_number',$details['voucher_number'])
		->set('issued_by',$details['issued_by'])
		->set('requested_by',$details['requested_by'])
		->set('received_by',$details['received_by'])
		->set('last_updated',date('Y-m-d H:i:s'))
		->where('id',$id);

		$res = $this->db->update('consumable_issue');
		return $res;
	}


	public function updateConsumableIssue($details, $id)
	{

        // print_r($receipt);
        // die();
		$this->db->set('voucher_date',$details['voucher_date'])
		->set('voucher_number',$details['voucher_number'])
		->set('last_updated',$details['last_updated'])
		->set('total_issued_qty', 'ROUND(total_issued_qty + ' . $details['total_issued_qty']. ',2)', FALSE)
		->set('total_cost', 'ROUND(total_cost + ' . $details['total_cost']. ',2)', FALSE)
		->where('id',$id);

		$res = $this->db->update('consumable_issue');
		return $res;
	}






	public function deleteConsumableReturn($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('consumable_return');
		return $res;
	}


	public function getConsumableReturnItemDetails($issue_id='',$post_arr=''){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('cir.*')
		->select('cr.*, si.user_name as supplier_user_name')
		->select('cir.*')
		->from('consumable_return_receipts cir')
		->where('cir.issue_id',$issue_id)
		->join('consumable_receipt cr', 'cir.receipt_id = cr.id')
		->join('supplier_info si', 'cr.supplier_id = si.id')
		->where('cir.status', 'active');
		$query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['job_order']= $this->Base_model->getJobOrderId($row['job_order_id'] );           
			$row['item_name']= $this->Base_model->getItemNameById($row['item_id']);
			$row['allocated_qty']=$this->getTotalAllocatedQty($row['receipt_id']);           
			$row['difference']=  $row['allocated_qty']-$row['issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}                 
            // if ( element( 'id', $post_arr ) ) {
            //     return $row;
            // }

			$details[] = $row;
		}
		return $details;

	}


	public function deleteConsumableReturnItem($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('consumable_return_receipts');
        // echo $this->db->last_query();
        // die();
		return $res;
	}





	public function deleteConsumableDamage($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('consumable_damage');
		return $res;
	}


	public function getConsumableDamageItemDetails($issue_id='',$post_arr=''){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('cir.*')
		->select('cr.*, si.user_name as supplier_user_name')
		->select('cir.*')
		->from('consumable_damage_receipts cir')
		->where('cir.issue_id',$issue_id)
		->join('consumable_receipt cr', 'cir.receipt_id = cr.id')
		->join('supplier_info si', 'cr.supplier_id = si.id')
		->where('cir.status', 'active');
		$query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['job_order']= $this->Base_model->getJobOrderId($row['job_order_id'] );           
			$row['item_name']= $this->Base_model->getItemNameById($row['item_id']);
			$row['allocated_qty']=$this->getTotalAllocatedQty($row['issue_id']);           
			$row['difference']=  $row['allocated_qty']-$row['issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}                 
            // if ( element( 'id', $post_arr ) ) {
            //     return $row;
            // }

			$details[] = $row;
		}
		return $details;

	}


	public function deleteConsumableDamageItem($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('consumable_damage_receipts');
        // echo $this->db->last_query();
        // die();
		return $res;
	}

	public function getConsumableDamageAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];

		$this->db->select('ci.*,cir.job_order_id,jo.project_id,li.user_name as voucher_entered_name,ln.user_name as reported_name,le.user_name as damager_name')
		->join('consumable_damage_receipts cir','cir.issue_id = ci.id')
		->join('job_orders jo','jo.id = cir.job_order_id')
		->join('login_info le','le.user_id=ci.damaged_lost_by','left')     
		->join('login_info ln','ln.user_id=ci.reported_by','left')   
		->join('login_info li','li.user_id=ci.voucher_entered_by','left')
		->from('consumable_damage ci');        


		if (element('issue_id',$post_arr)) { 
			$this->db->where('ci.id', $post_arr['issue_id']);
		}

		if ( element( 'project_id', $post_arr) ) {
			$this->db->where('jo.project_id', $post_arr['project_id'] );
		}


		if ( element( 'voucher_number', $post_arr) ) {
			$this->db->where('ci.voucher_number', $post_arr['voucher_number'] );
		}

		if ( element( 'voucher_date', $post_arr) ) {
			$this->db->where('ci.voucher_date', $post_arr['voucher_date'] );
		}
		if ( element( 'status', $post_arr) ) {
			$this->db->where('ci.status', $post_arr['status'] );
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('ci.date_added >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('cr.date_added <=', $end_date);
		}


		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

			$where = "(ci.voucher_number LIKE '%$searchValue%' 


			OR ci.date_added LIKE '%$searchValue%' )";
			$this->db->where($where);
		}

		if($count) {
			return $this->db->count_all_results();
		}

		$this->db->limit($rowperpage, $row);
		$this->db->order_by('ci.id', 'DESC');
		$query = $this->db->get();  
        // echo $this->db->last_query();die();
		$i=1;

		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['allocated_qty']=$this->getDamageTotalAllocatedQty($row['id']);           
			$row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}          

            // if (element('items',$post_arr)==true) {
            //     $search_arr['material_receipt_id'] = $row['id'];

            // }
			$row['items']= $this->getMaterialDamageItemDetails($row['id']);
			if (element('issue_id',$post_arr)) { 
				return $row;
			}

			$details[] = $row;
			$i++;
		}
		return $details;

	}

	public function getTotalAllocatedDamageQty($issue_id='')
	{
		$this->db->select_sum('cr.total_qty')
		->from('consumable_receipt cr')
        // ->from('material_receipt_items mri')
		->join('consumable_damage_receipts cir', 'cr.id = cir.receipt_id');

		if ($issue_id) {
			$this->db->where('cir.issue_id',$issue_id);
		}
		$query  = $this->db->get(); 
		foreach($query->result_array() as $row){
			$total_qty =  $row["total_qty"];
		}
		return $total_qty;


	}
	public function updateVoucherDamageDetails($details, $id)
	{

        // print_r($receipt);
        // die();
		$this->db->set('voucher_date',$details['voucher_date'])
		->set('voucher_number',$details['voucher_number'])

		->set('reported_by',$details['reported_by'])
		->set('voucher_type',$details['voucher_type'])
		->set('damaged_lost_by',$details['damaged_lost_by'])
		->set('voucher_entered_by',$details['voucher_entered_by'])
		->set('last_updated',date('Y-m-d H:i:s'))
		->where('id',$id);

		$res = $this->db->update('consumable_damage');
		return $res;
	}

	public function updateConsumableDamage($details, $id)
	{

        // print_r($receipt);
        // die();
		$this->db->set('voucher_date',$details['voucher_date'])
		->set('voucher_number',$details['voucher_number'])
		->set('last_updated',$details['last_updated'])
		->set('total_issued_qty', 'ROUND(total_issued_qty + ' . $details['total_issued_qty']. ',2)', FALSE)
		->set('total_cost', 'ROUND(total_cost + ' . $details['total_cost']. ',2)', FALSE)
		->where('id',$id);

		$res = $this->db->update('consumable_damage');
		return $res;
	}

	public function updateConsumableReturn($details, $id)
	{

        // print_r($receipt);
        // die();
		$this->db->set('voucher_date',$details['voucher_date'])
		->set('voucher_number',$details['voucher_number'])
		->set('last_updated',$details['last_updated'])
		->set('total_issued_qty', 'ROUND(total_issued_qty + ' . $details['total_issued_qty']. ',2)', FALSE)
		->set('total_cost', 'ROUND(total_cost + ' . $details['total_cost']. ',2)', FALSE)
		->where('id',$id);

		$res = $this->db->update('consumable_return');
		return $res;
	}

	public function getIssueBillNumber($issue_id) {
		$voucher_number = NULL;
		$this->db->select('voucher_number');
		$this->db->from('consumable_issue');
		$this->db->where('id', $issue_id);
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$voucher_number = $row->voucher_number;
		}
		return $voucher_number;
	}


	public function getConsumableIssueDetails($issue_id){
		$details = array(); 


		$this->db->select('ci.*,li.user_name as receiver_name,le.user_name as requested_name')
		->from('consumable_issue ci')
		->join('login_info le','le.user_id=ci.requested_by','left')
		->join('login_info li','li.user_id=ci.received_by','left');      


		if ($issue_id) { 
			$this->db->where('ci.id',$issue_id);
		}
		$this->db->order_by('ci.id', 'DESC');
		$query = $this->db->get();  
		foreach($query->result_array() as $row){
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['allocated_qty']=$this->getTotalAllocatedQty($row['id']);           
			$row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}          

            // if (element('items',$post_arr)==true) {
            //     $search_arr['material_receipt_id'] = $row['id'];

            // }
			$row['items']= $this->getConsumableIssuedItemDetails($row['id']);
			if ($issue_id) { 
				return $row;
			}

			$details[] = $row;

		}
		return $details;

	}



	public function getConsumableDamageDetails($issue_id){
		$details = array(); 
		$this->db->select('ci.*,cir.job_order_id,jo.project_id')
		->join('consumable_damage_receipts cir','cir.issue_id = ci.id')
		->join('job_orders jo','jo.id = cir.job_order_id')
		->from('consumable_damage ci');        
		$this->db->where('ci.id', $issue_id);
		$this->db->order_by('ci.id', 'DESC');
		$query = $this->db->get();  
        // echo $this->db->last_query();die();
		$i=1;

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['allocated_qty']=$this->getDamageTotalAllocatedQty($row['id']);           
			$row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}          

			$row['items']= $this->getMaterialDamageItemDetails($row['id']);
			if ($issue_id) { 
				return $row;
			}

			$details[] = $row;

		}
		return $details;

	}


	public function getConsumableReturnDetails($issue_id){
		$details = array(); 

		$this->db->select('ci.*,li.user_name as receiver_name,le.user_name as return_by')
		->from('consumable_return ci')
		->join('login_info le','le.user_id=ci.return_by')
		->join('login_info li','li.user_id=ci.received_by');        
		$this->db->where('ci.id', $issue_id);
		$this->db->order_by('ci.id', 'DESC');
		$query = $this->db->get();  
        // echo $this->db->last_query();die();

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
			$row['allocated_qty']=$this->getReturnTotalAllocatedQty($row['id']);           
			$row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
			if ($row['difference']<0) {
				$row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
			}          
			else {
				$row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
			}          

            // if (element('items',$post_arr)==true) {
            //     $search_arr['material_receipt_id'] = $row['id'];

            // }
			$row['items']= $this->getMaterialReturnItemDetails($row['id']);
			if ($issue_id) { 
				return $row;
			}

			$details[] = $row;
		}
		return $details;

	}
	public function getConsumableIssueItemDetails($post_arr){
        // print_r($post_arr);die();
		$details = array(); 
		$this->db->select('cri.*,cri.issued_qty as total_issued_qty')
		->from('consumable_issue_receipts cri')
		->join('consumable_issue cr', 'cr.id = cri.issue_id')

		->where( 'cri.status', 'active');

		if ( element( 'job_orderid', $post_arr ) ) {
			$this->db->where( 'cri.job_order_id', $post_arr['job_orderid']);
		}

		// if ( element( 'material_receipt_id', $post_arr ) ) {
		// 	$this->db->where( 'cri.material_receipt_id', $post_arr['material_receipt_id']);
		// }

		if ( element( 'item_id', $post_arr ) ) {
			$this->db->where( 'cri.item_id', $post_arr['item_id']);
		}

        // if (element('start_date',$post_arr)) {
        //     $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
        //     $this->db->where('mri.date_added >=', $start_date); 
        // }

        // if (element('end_date',$post_arr)) {
        //     $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
        //     $this->db->where('mri.date_added <=', $end_date);
        // }
        $this->db->order_by('id','DESC');
		$this->db->limit(1);
		$query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

		foreach($query->result_array() as $row){

			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );         
			// $row['issued_qty']= $this->getIssuedQty($row['material_receipt_id'],$row['job_order_id'],$row['item_id']);  
			$row['issued_qty']=$post_arr['issued_qty'];
			$row['difference']=$row['total_issued_qty']-$post_arr['issued_qty'];       
            // $row['issued_qty']= 0;         
			$details = $row;
		}
		return $details;

	}

	public function getIssueTotalAllocatedQty($issue_id='')
	{
		$this->db->select_sum('cr.total_issued_qty')
		->from('consumable_issue cr')
        // ->from('material_receipt_items mri')
		->join('consumable_issue_receipts cir', 'cr.id = cir.issue_id');

		if ($issue_id) {
			$this->db->where('cir.issue_id',$issue_id);
		}
		$query  = $this->db->get(); 
		// echo $this->db->last_query();die();
		foreach($query->result_array() as $row){
			$total_qty =  $row["total_issued_qty"];
		}
		return $total_qty;


	}
	public function addReceiptStock($items) {
		// print_r($items);die();
		foreach ($items as $key => $value) {

			$this->db->set('qty', 'qty +' . $value['issued_qty'] , FALSE);
			// $this->db->where('material_receipt_id', $value['receipt_id'] );
			$this->db->where('item_id', $value['item_id'] );
			$query = $this->db->update('consumable_receipt_items');
			if(!$query){
				return  FALSE;
			}

		}

		return $query;
	}

	public function addIssueStock($items) {
		foreach ($items as $key => $value) {

			$this->db->set('issued_qty', 'issued_qty -' . $value['issued_qty'] , FALSE);
			// $this->db->where('material_receipt_id', $value['receipt_id'] );
			$this->db->where('item_id', $value['item_id'] );
			$query = $this->db->update('consumable_issue_receipts');
			if(!$query){
				return  FALSE;
			}

		}

		return $query;
	}

	public function deductIssueStock($items) {
		foreach ($items as $key => $value) {

			$this->db->set('qty', 'qty -' . $value['issued_qty'] , FALSE);
			// $this->db->where('id', $value['receipt_id'] );
			$this->db->where('item_id', $value['item_id'] );
			$query = $this->db->update('consumable_receipt_items');
			if(!$query){
				return  FALSE;
			}

		}

		return $query;
	}


	



}