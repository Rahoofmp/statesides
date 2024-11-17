<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_model extends Base_model {

	function __construct() {
		parent::__construct();

	}

	public function getUserIdAuto($term) {

		$output = [];
		$this->db->select('user_id,user_name');
		$this->db->from('login_info');
		$this->db->where('status', 1);
		$this->db->where("user_name LIKE '%$term%'");
		$this->db->limit(10);

		$res = $this->db->get();

		foreach($res->result_array() as $row) {
			$output[] = ['id'=>$row['user_id'], 
			'text'=>$row['user_name']];
		}

		return $output;
	}


	public function insertSalesRivision($post_arr='')
	{
		$date = date('Y-m-d H:i:s');

		$this->db->set('code',$post_arr['code'])
		->set('date',$post_arr['date'])
		->set('created_date',$date)
		->set('customer_id',$post_arr['customer_id'])
		->set('salesperson',$post_arr['salesperson'])
		->set('vat',$post_arr['vat'])
		->set('status',$post_arr['status'])
		->set('terms_conditions',$post_arr['terms_conditions'])
		->set('discount_by_amount',$post_arr['discount_by_amount'])
		->set('discount_by_percentage',$post_arr['discount_by_percentage'])
		->set('total_items',$post_arr['total_items'])
		->set('total_qty',$post_arr['total_qty'])
		->set('total_amount',$post_arr['total_amount'])
		->set('type',$post_arr['type'])
		->set('created_by',log_user_id())
		->set('note',$post_arr['note']); 


		$res = $this->db->insert('sales_quotation');

		return $this->db->insert_id();
	}

	public function insertSalesQuotation($post_arr='')
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('code',$post_arr['code']);
		$this->db->set('date',$post_arr['date']);
		$this->db->set('created_date',$date);
		$this->db->set('customer_id',$post_arr['customer_name']);
		$this->db->set('salesperson',$post_arr['salesperson']);
		$this->db->set('subject',$post_arr['subject']);
		$this->db->set('status',$post_arr['status']);
		// $this->db->set('tc_type',$post_arr['tc_type']); 
		if(element('payment_terms_id', $post_arr))
			$this->db->set('payment_terms_id',$post_arr['payment_terms_id']); 

		$this->db->set('terms_conditions',$post_arr['normal_terms_id']);
		$this->db->set('created_by',log_user_id());

		if(element('type', $post_arr)){
			$this->db->set('type',$post_arr['type']);
		}
		if(element('note', $post_arr)){
			$this->db->set('note',$post_arr['note']);
		}

		$res = $this->db->insert('sales_quotation');

		return $this->db->insert_id();
	}

	public function insertTermsConditions($post_arr=[])
	{
		$date=date('Y-m-d H:i:s');

		$this->db->set('name',$post_arr['name']);
		$this->db->set('tc_type', $post_arr['tc_type']);
		$this->db->set('created_by',log_user_id());
		$this->db->set('date',$date);
		$this->db->set('terms_conditions',$post_arr['terms_conditions']);


		$res = $this->db->insert('terms_conditions');

		return $this->db->insert_id();
	}

	public function updateTermsConditions($post_arr=[])
	{
		$date=date('Y-m-d H:i:s');

		$this->db->set('date',$date);
		$this->db->set('name',$post_arr['name']);
		$this->db->set('terms_conditions',$post_arr['terms_conditions']);
		$this->db->set('tc_type',$post_arr['tc_type']);
		$this->db->where('id',$post_arr['id']);
		$res = $this->db->update('terms_conditions');
		return $this->db->affected_rows();
	}
	
	public function updateSalesQuotation($id,$post_arr='')
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('date',$post_arr['date']);
		$this->db->set('subject',$post_arr['subject']);
		$this->db->set('customer_id',$post_arr['customer_name']);
		$this->db->set('salesperson',$post_arr['salesperson']);
		$this->db->set('vat',$post_arr['total_vat']); 

		if((element('payment_terms_id', $post_arr)))
			$this->db->set('payment_terms_id',$post_arr['payment_terms_id']);
		$this->db->set('terms_conditions',$post_arr['normal_terms_id']);

		$this->db->set('status',$post_arr['status']);
		$this->db->where('id',$id);


		$res = $this->db->update('sales_quotation');

		
		return $res;
	}

	public function insertSalesItems( $sales_id, $post)
	{

		$this->db->set('sales_id', $sales_id);
		$this->db->set('item_id', $post['item_id']); 
		$this->db->set('quantity', $post['quantity']);
		$this->db->set('total_price', $post['total_price']);
		$this->db->set('vat_inclusive', $post['vat_inclusive']);
		$this->db->set('date_added', $post['date']); 
		$this->db->set('note', $post['note']); 
		$this->db->set('price', $post['price']); 
		$this->db->set('status','yes');
		$this->db->set('activity_id',log_user_id());
		$res=$this->db->insert('sales_items');
		return $res;
	}

	public function getProjectInfo($package_id)
	{
		$details=[];
		$this->db->select('*');
		$this->db->where('id',$package_id);
		$res=$this->db->get('project_packages');
		foreach($res->result_array() as $row)
		{
			$row['project_name']=$this->getProjectName($row['project_id']);
			$details[]=$row;
		}
		return $details;
	}

	public function getAllTC($id='')
	{
		$details=[];
		$this->db->select('*');
		if($id)
			$this->db->where('id',$id);
		$res=$this->db->get('terms_conditions');
		foreach($res->result_array() as $row)
		{
			$row['enc_id'] = $this->encrypt_decrypt('encrypt', $row['id']);
			if($id){
				return $row;
			}
			$details[]=$row;
		}
		return $details;
	}

	public function getProjectDetails($project_id)
	{
		$details=array();
		$this->db->select('*');
		$this->db->where('id',$project_id);
		$res=$this->db->get('project');
		foreach($res->result_array() as $row)
		{
			$details=$row;
		}
		return $details;
	}

	public function getProjectName($project_id)
	{
		$details=[];
		$this->db->select('project_name');
		$this->db->where('id',$project_id);
		$res=$this->db->get('project');
		foreach ($res->result() as $row)
		{
			$details=$row->project_name;
		}
		return $details;
	}
	public function getCustomerName($customer_id)
	{
		$details='';
		$this->db->select('customer_username');
		$this->db->where('customer_id',$customer_id);
		$res=$this->db->get('customer_info');
		foreach ($res->result() as $row)
		{
			$details=$row->customer_username;
		}
		return $details;
	} 
	// public function getPackageName($package_id)
	// {
	// 	$this->db->select('name');
	// 	$this->db->where('id',$package_id);
	// 	$res=$this->db->get('project_packages');
	// 	foreach ($res->result() as $row)
	// 	{
	// 		$details=$row->name;
	// 	}
	// 	return $details;
	// } 

	// public function insertPackageItems($post,$package_id)
	// {
	// 	foreach($post['tn_firstname'] as $key  => $value){
	// 		$date = date('Y-m-d H:i:s');
	// 		if($post['tn_lastname'][$key])
	// 		{
	// 			$total_amount=$post['tn_firstname'][$key]*$post['tn_lastname'][$key];
	// 			$this->db->set('package_id',$package_id);
	// 			$this->db->set('name',$post['tn_name'][$key]);
	// 			$this->db->set('qty',$post['tn_firstname'][$key]);
	// 			$this->db->set('amount',$post['tn_lastname'][$key]);
	// 			$this->db->set('total_amount',$total_amount);
	// 			$this->db->set('date_addedd',$date);
	// 			$res=$this->db->insert('package_items');
	// 		}

	// 	}
	// 	return $res;
	// }

	// public function getSalesDetails($post_arr,$limit=''){
	// 	$details = array(); 


	// 	$this->db->select('s.*')
	// 	->select('pj.project_name, pj.customer_name')
	// 	->from('project_packages pp')
	// 	->where('pp.status!=','deleted')
	// 	->join('project pj','pj.id = pp.project_id');

	// 	if (element('packager_id',$post_arr)) {
	// 		$this->db->where('pp.user_id',$post_arr['packager_id']);
	// 	}if ($limit) {
	// 		$this->db->limit($limit);
	// 	}
	// 	if (element('package_id',$post_arr)) {
	// 		$this->db->where('pp.id',$post_arr['package_id']);
	// 	}
	// 	if (element('project_id',$post_arr)) {
	// 		$this->db->where('pp.project_id',$post_arr['project_id']);
	// 	}
	// 	if (element('status',$post_arr)) {
	// 		if($post_arr['status']!='all'){
	// 			$this->db->where('pp.status',$post_arr['status']);
	// 		}
	// 	}

	// 	if (element('start_date',$post_arr)) {
	// 		$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
	// 		$this->db->where('pp.date_created >=', $start_date); 
	// 	}

	// 	if (element('end_date',$post_arr)) {
	// 		$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
	// 		$this->db->where('pp.date_created <=', $end_date);
	// 	}

	// 	if (element('order',$post_arr)) {
	// 		$this->db->order_by("pp.{$post_arr['order']}", $post_arr['order_by']);
	// 	}

	// 	$query = $this->db->get();  

	// 	foreach($query->result_array() as $row){
	// 		$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
	// 		$row['date_created']= date('d-m-Y H:i:s', strtotime($row['date_created']));

	// 		if (element('items',$post_arr)==true) {
	// 			$row['items']= $this->getPackageItems($row['id']);
	// 		}

	// 		$details[] = $row;
	// 	}
	// 	return $details;

	// }
	public function getSalesDetails($id='',$post_arr=[]){
		$details = array(); 

		$this->db->select('s.*')
		->select('s.status as sales_status')
		->select('li.user_name as salesman_name')
		->select('tcp.name as payment_name, tcp.terms_conditions as payment_terms_conditions')
		->select('tcn.name as normal_payment_name, tcn.terms_conditions as normal_terms_conditions')
		->select('ci.customer_username as customer_name,ci.email as customer_email,ci.mobile as customer_mobile,ci.address as customer_address,ci.name as customer_full_name')
		// ->select('v.name as vat_name')
		// ->select('it.name as item_name')
		->from('sales_quotation s')
		
		// ->join('sales_items si','si.sales_id = s.id')
		// ->join('vat v','s.vat = v.id')
		->join('login_info li','li.user_id = s.salesperson', 'left')
		->join('terms_conditions tcp','tcp.id = s.payment_terms_id', 'left')
		->join('terms_conditions tcn','tcn.id = s.terms_conditions', 'left')
		->join('customer_info ci','ci.customer_id = s.customer_id', 'left')
		// ->select('CONCAT(ui.first_name,ui.second_name) as salesman_names')
		->select('ui.first_name as salesman_names,ui.mobile as salesman_mobile')
		->select('ui.email')
		->join('user_info ui','ui.user_id = s.salesperson', 'left');
		// ->join('items it','it.code =s.code', 'left');

		if ($id) {
			$this->db->where('s.id',$id);
		}

		$query = $this->db->get();  
		// echo $this->db->last_query($query);die();
		foreach($query->result_array() as $row){
			// print_r($row['id']);die();
			// if ($row['payment_terms_id']) {
			// 	$row['payment_terms_conditions']=element('terms_conditions',$this->Sales_model->getTermsConditions($row['payment_terms_id']));
			// }
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['date_created']= date('d-m-Y H:i:s', strtotime($row['date']));
			$row['total_vat_amount']=$row['total_vat_inclusive']-$row['total_amount'];
			$row['items']= $this->getSalesItems($row['id'], $post_arr);
			$details[] = $row;
		}
		// print_r($details);
		// die();
		return $details;

	}
	public function getSalesDetailAjax($post_arr=[],$count='',$user_id=''){
		// print_r($post_arr);die();
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];

		$this->db->select('s.*')
		->select('s.status as sales_status')
		// ->select('si.*')
		// ->select('v.name as vat_name, v.value as vat')
		->from('sales_quotation s');
		
		// ->join('sales_items si','si.sales_id = s.id')
		// ->join('vat v','s.vat = v.id');
		if($user_id){
			$this->db->where('s.created_by',$user_id);
		}
		if (element('sales_id',$post_arr)) {
			$this->db->where('s.id',$post_arr['sales_id']);
		}
		if (element('type',$post_arr)) {
			if($post_arr['type']!='all'){
				$this->db->where('s.type',$post_arr['type']);
			}
		}
		// if (element('package_id',$post_arr)) {
		// 	$this->db->where('s.id',$post_arr['package_id']);
		// }
		// if (element('project_id',$post_arr)) {
		// 	$this->db->where('s.project_id',$post_arr['project_id']);
		// }
		if (element('status',$post_arr)) {
			if($post_arr['status']!='all'){
				$this->db->where('s.status',$post_arr['status']);
			}
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('s.date >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('s.date <=', $end_date);
		}

		if (element('order',$post_arr)) {
			$this->db->order_by("s.{$post_arr['order']}", $post_arr['order_by']);
		}

		$searchValue = $post_arr['search']['value'];
		if('' != $searchValue) { 

			$where = "(code LIKE '%$searchValue%'
			OR date LIKE '%$searchValue%'
			
			OR s.status LIKE '%$searchValue%' )";


			$this->db->where($where);
		}

		if($count) {
			return $this->db->count_all_results();
		}

		$this->db->limit($rowperpage, $row);
		$query = $this->db->get();  
		// echo $this->db->last_query();
		// die();
		$i=1;
		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['date_created']= date('d-m-Y H:i:s', strtotime($row['date']));
			$row['customer_name']= $this->getCustomerName($row['customer_id']);
			$row['salesman_name']= $this->getUserName($row['salesperson']);



			$print_view = '<div id="printdiv" class="printdiv" style="display: none">';
			$print_view .= '<table border="0" style="width: 100%;"><tr>';
			
			$print_view .= '<td>';
			// $print_view .= "<p>{$row['customer_name']}</p>";
			// $print_view .= "<p>{$row['project_name']}</p>";
			// $print_view .= "<p>{$row['name']}</p>";
			// $print_view .= "<p>{$row['date_created']}</p>";
			// $print_view .= "<p>{$row['location']}</p><span class='clearfix'></span>";
			// $print_view .= "<p><small style='font-weight: bold'> {$row['code']} </small></p><span class='clearfix'></span>";
			// $print_view.="<img src='".base_url('assets/images/qr_code/package/')."{$row['code']}.png' style='max-width: 100px'>";
			// $print_view.="<img src='".base_url('assets/images/package_pic/').$row['image']."'  style='max-width: 100px'>";
			$print_view .= '</td>';
			

			if (element('items',$post_arr)==true) {
				// $row['items']= $this->getPackageItems($row['id']);

				// $print_view .= '<td rowspan="1"  style="vertical-align: top;">';
				// $print_view.='<table class="items"  style="width: 100%;" align="top" >';
				// $print_view.='<thead>';
				// $print_view.='<tr align="top">';
				// $print_view.='<th align="left">Code</th>';
				// $print_view.='<th align="left">Item</th>';
				// $print_view.='<th align="right">Qty</th>';
				// $print_view.='</tr>';
				// $print_view.='</thead>';
				// $print_view.='<tbody >';
				// foreach ($row['items'] as $key => $item) {

				$print_view.='<tr >';
					// $print_view.="<td align='left'>{$item['serial_no']}</td>";
					// $print_view.="<td align='left'>{$item['name']}</td>";
					// $print_view.="<td align='right'>{$item['qty']}</td>";
				$print_view.='<tr >'; 

				// }

				$print_view.='</tbody>';
				$print_view.='</table>';
				$print_view.='</td>';
			}




			$print_view.='</tr>';
			$print_view .= '</tr>';
			$print_view.=  '<td colspan="2" align="center">
			<p ><small > www.pinetreelane.com </small></p>
			</td>';
			$print_view .= '</tr></table>';
			
			$print_view .= '</div>';
			
			$row['print_view']= $print_view;

			$details[] = $row;
			$i++;
		}
		return $details;

	}
	public function getSalesCount()
	{
		$this->db->select('*');
		$this->db->from('sales_quotation');
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getSalesItems($id, $search_arr=[] )
	{
		// print_r($id);die();
		$this->load->model('Item_model');
		$details=[];
		$this->db->select('si.*,si.note as spec,si.price as sprice,si.id as sales_item_id,v.name as vat,v.value as vat_perc')

		->select('it.*,c.category_name')
		->join('items it', 'it.id = si.item_id')
		->join('category c', 'it.category = c.id')
		->join('vat v', 'it.vat = v.id');

		if( element('ordering_category', $search_arr)){
			$this->db->order_by('category', 'ASC');
		}

		$this->db->where('si.status', 'yes')

		->where('it.status!=','0')

		->where('si.sales_id',$id)
		->from('sales_items si');  
		$res=$this->db->get();
		// echo $this->db->last_query();die();
		foreach($res->result_array() as $row)
		{
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['sales_item_id'] );
			$row['date_added']= date('d-m-Y H:i:s', strtotime($row['date_added']));
			$row['vat_perc_amount']=$row['total_price']*$row['vat_perc']/100;
			$row['item_images']=$this->Item_model->getItemImages($row['item_id']);
			

			$details[]=$row;
		}
		return $details;
	}
	public function getAllItemOrderDetails($id)
	{
		$this->load->model('Item_model');
		$details=[];
		$this->db->select('si.*,si.id as sales_item_id')

		->select('it.*,c.category_name,v.name as vat_name,v.value')
		->select('sq.*')
		->join('items it', 'it.id = si.item_id')
		->join('sales_quotation sq', 'sq.id = si.sales_id')
		->join('category c', 'it.category = c.id')
		->join('vat v', 'it.vat = v.id');

		$this->db->where('si.status', 'yes')

		->where('it.status!=','0')

		->where('si.item_id',$id)
		->from('sales_items si');  
		$res=$this->db->get();
		// echo $this->db->last_query();;
		foreach($res->result_array() as $row)
		{
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['sales_item_id'] );
			$row['date_added']= date('d-m-Y H:i:s', strtotime($row['date_added']));
			$row['item_images']=$this->Item_model->getItemImages($row['item_id']);
			
			$row['customer_name']= $this->getCustomerName($row['customer_id']);

			$details[]=$row;
		}
		return $details;
	}
	public function getItemFieldByCode($field='',$code)
	{
		$details=[];
		$this->db->select($field)
		->where('code', $code)
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

	public function getPackagesDetails($id, $items=true)
	{
		$details='';
		$this->db->select('pp.*,pp.location as package_location')
		->from('project_packages pp')
		->where('pp.id',$id)

		->select('pj.project_name,pj.customer_name, pj.location')
		->select('pj.email, pj.date project_date, pj.status project_status')
		->select('c.customer_username,c.name as cus_name,c.mobile as customer_mobile,c.email as customer_email')
		->join('project pj', 'pp.project_id = pj.id')
		->join('customer_info c', 'pj.customer_name = c.customer_id');

		$query = $this->db->get();

		foreach($query->result_array() as $row){
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['date_created']= date('d-m-Y H:i:s', strtotime($row['date_created']));

			if( $items){
				$row['items']= $this->getPackageItems($row['id']);
			}
			$details = $row;
		}
		return $details;
	}

	public function deletePackages($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('project_packages');
		return $res;
	}

	public function checkDeliveryPackage($package_id) {
		$count = 0;
		$this->db->select("count(id) as count");
		$this->db->where('package_id', $package_id); 
		$this->db->where('status!=', 'deleted'); 
		$this->db->from("delivery_packages");
		$query = $this->db->get();
		foreach ($query->result() AS $row) {
			$count = $row->count;
		}

		return $count;            
	}

	public function deleteSalesItem($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('sales_items');
		if($res){
			
		}
		// echo $this->db->last_query();
		// die();
		return $res;
	}
	public function gettSalesItemDetails($id)
	{
		$details=array();
		$this->db->select('*')
		->from('sales_items')
		->where('id',$id);
		$query=$this->db->get();
		foreach ($query->result_array() as $row) {
			$details=$row;
		}
		return $details;
	}


	public function updateItemDiscount($id,$post_arr=[])
	{
		$date=date('Y-m-d');
		
		$this->db->set('discount_by_amount',round($post_arr['by_amount'],2))
		->set('discount_by_percentage',round($post_arr['by_percentage'],2))
		->set('total_items', 'total_items + ' . $post_arr['total_items'], FALSE)
		->set('vat', 'vat + ' . $post_arr['total_vat'], FALSE)
		->set('total_qty', 'total_qty + ' . $post_arr['total_qty'], FALSE)
		->set('total_amount', 'total_amount + ' . $post_arr['total_amount'], FALSE)
		->set('total_vat_inclusive', 'total_vat_inclusive + ' . $post_arr['total_vat_inclusive'], FALSE);
		
		
		$this->db->where('id',$id);
		$res=$this->db->update('sales_quotation'); 
		return $res;
	}

	public function getProjectId($package_id)
	{
		$this->db->select('project_id');
		$this->db->where('id',$package_id);
		$res=$this->db->get('project_packages');
		foreach ($res->result() as $row)
		{
			$details=$row->project_id;
		}
		return $details;
	} 
	public function getUserTypeId($user_type)
	{
		$this->db->select('user_id');
		$this->db->where('user_type',$user_type);
		$res=$this->db->get('login_info');
		foreach ($res->result() as $row)
		{
			$details=$row->user_id;
		}
		return $details;
	} 
	public function getProjectIds($project_name)
	{
		$details='';
		$this->db->select('id');
		$this->db->where('project_name',$project_name);
		$res=$this->db->get('project');
		foreach ($res->result() as $row)
		{
			$details=$row->id;
		}
		return $details;
	} 

	public function getpackageIdByCode($code) {
		$id = 0 ;
		$this->db->select("id");
		$this->db->from("project_packages");
		$this->db->where('code', $code);

		$query = $this->db->get(); 
		foreach ($query->result()AS $row) {
			$id = $row->id;
		}

		return $id;
	}
	public function getMaxSalesId() {
		$id = NULL;
		$this->db->select_max('id');
		$this->db->from('sales_quotation');
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$id = $row->id;
		}
		return $id;
	}

	public function getTotalItemAmount($sales_id)
	{
		$total_price = 0;
		$this->db->select_sum('total_price');

		$this->db->where('status ','yes');
		$this->db->where('sales_id',$sales_id);

		$query = $this->db->get('sales_items');
		foreach ($query->result() as $row) 
		{
			$total_price = $row->total_price;
		}
		if ($total_price) {
			return $total_price;
		}
		else
			return 0;
	}
	public function getTotalItem($item_id='')
	{
		$quantity = 0;
		$this->db->select_sum('quantity');

		$this->db->where('status ','yes');
		if ($item_id) {
			$this->db->where('item_id',$item_id);

		}

		$query = $this->db->get('sales_items');
		foreach ($query->result() as $row) 
		{
			$quantity = $row->quantity;
		}
		if ($quantity) {
			return $quantity;
		}
		else
			return 0;
	}
	public function updateSalesStatus($id)
	{
		$this->db->set('status','approved');
		$this->db->where('id',$id);

		$query=$this->db->update('sales_quotation');
		// echo $this->db->last_query($query);die();
		return $query;
	}

	public function checkItemDuplicate($item_codes, $sales_id) {
		$count = 0 ;
		$this->db->select("COUNT(i.id) as count");
		$this->db->from("items i");
		$this->db->where_in('i.code', $item_codes);
		$this->db->where('i.type', 'finished_item');
		$this->db->where('si.sales_id', $sales_id);
		$this->db->join('sales_items si', 'si.item_id = i.id');
		$query = $this->db->get(); 
		foreach ($query->result()AS $row) {
			$count = $row->count;
		}

		return $count;
	}
	public function updateTermsCondditions($id,$terms_conditions='')
	{

		$this->db->set('terms_conditions',$terms_conditions);
		$this->db->where('id',$id);


		$res = $this->db->update('sales_quotation');

		
		return $res;
	}
	public function updatePaymentTermsCondditions($id,$terms_conditions='')
	{

		$this->db->set('terms_conditions',$terms_conditions);
		$this->db->where('id',$id);


		$res = $this->db->update('terms_conditions');

		
		return $res;
	}
	public function getTermsConditions($id='',$tc_type='')
	{
		$details=array();
		$this->db->select('*');
		$this->db->from('terms_conditions');
		if ($id) {
			$this->db->where('id',$id);
		}
		if ($tc_type) {
			$this->db->where('tc_type',$tc_type);
		}

		$query = $this->db->get(); 
		foreach ($query->result_array()AS $row) {
			if ($id) {
				$details=$row;
			}
			else
				$details[] = $row;
		}

		return $details;

	}
	public function deleteSalesQuotation($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('sales_quotation');
		return $res;
	}
	public function deleteSalesmanQuotation($id)
	{

		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$this->db->where('status','draft');
		$res=$this->db->update('sales_quotation');
		return $this->db->affected_rows();
	}
	public function getSalesDetailsCount($id='')
	{
		$count='';
		$this->db->select('COUNT(sales_id) as count');
		$this->db->from('sales_items');
		$this->db->where('sales_id',$id);
		$res = $this->db->get();
		foreach($res->result_array() as $row) {
			$count = $row['count'];
		}

		return $count;
	}
	public function getSumOfSalesItems($id='')
	{
		$details = array(); 
		$this->db->select('SUM(total_price)as total_price,SUM(vat_inclusive)as vat_inclusive,SUM(quantity)as quantity');
		$this->db->from('sales_items');
		$this->db->where('sales_id',$id);
		$res = $this->db->get();
		foreach($res->result_array() as $row) {
			$details = $row;
		}

		return $details;
	}
	public function getVatTotal($id='')
	{
		$details = array(); 
		$this->db->select('si.item_id,it.vat,vt.value');
		$this->db->from('sales_items as si');
		$this->db->join('items as it','it.id=si.item_id');
		$this->db->join('vat as vt','vt.id=it.vat');
		$this->db->where('si.sales_id',$id);
		$res = $this->db->get();
		$vat=0;
		foreach($res->result_array() as $row) {
			$vat=$vat+$row['value'];
			$details[] = $row;
		}
		$details['vat_total']=$vat;
		return $details;
	}


	public function UpdateSalesItems($post)
	{

		$this->db->set('quantity', $post['quantity']);
		$this->db->set('note', $post['spec1']); 
		$this->db->set('total_price', ($post['price']*$post['quantity'])); 
		$this->db->set('vat_inclusive', (($post['price']*$post['quantity'])+($post['price']*$post['quantity']*$post['vat'])/100)); 
		$this->db->where('item_id',$post['item_id']);
		$res=$this->db->update('sales_items');
		// echo $this->db->last_query();die();
		return $res;
	}


}
