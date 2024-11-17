<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery_model extends Base_model {

	function __construct() {
		parent::__construct();

	}


	public function addDeliveryNote($post)
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('project_id', $post['project_id'])
		->set('user_id', $post['user_id'])
		->set('date_created',$date)
		->set('driver_id', $post['driver_id'])
		->set('status','pending');
		
		if( element('vehicle_number', $post) ){
			$this->db->set( 'vehicle', $post['vehicle_number'] );
		}
		$res = $this->db->insert('delivery_notes');

		if($res){
			$delivery_id = $this->db->insert_id();
			$code = $delivery_id+10000;


			$this->load->library('ciqrcode');
			$params['data'] = $code;
			$params['level'] = 'H';
			$params['size'] = 5;
			$params['errorlog'] = FALSE;
			$params['savename'] = './assets/images/qr_code/delivery/'.$code.'.png';
			$this->ciqrcode->generate($params);


			$this->db->set('code', $code); 
			$this->db->where('id', $delivery_id);
			$res = $this->db->update('delivery_notes');

			if($this->db->affected_rows()){
				return $delivery_id;
			}
		}

		return $res;
	}
	public function addItems($post)
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('code', $post['code'])
		->set('name', $post['name'])
		->set('date',$date)
		->set('category', $post['main_category'])
		->set('cost', $post['cost'])
		->set('vat', $post['vat'])
		->set('price', $post['price'])
		->set('total_quantity', $post['total_quantity'])
		->set('type', $post['type']);
		$res = $this->db->insert('items');
		return $this->db->insert_id();
	}


	public function addDeliveryPackages($post)
	{
		$date = date('Y-m-d H:i:s');

		$res = $this->db->set('added_date', $date)
		->set('package_id', $post['package_id'])
		->set('delivery_id', $post['delivery_id'])
		->set('status','pending')
		->insert('delivery_packages');

		return $res;
	}

	public function updateDeliveryStatus($delivery_id, $status)
	{
		$date = date('Y-m-d H:i:s');

		$res = $this->db->where('id', $delivery_id)
		->set('last_updated', $date)
		->set('status', $status);
		if(log_user_type() == 'supervisor'){
			$this->db->set('supervisor_id', log_user_id());
		}
		$this->db->update('delivery_notes');
		// echo $this->db->last_query();
		return $res;
	}
	public function updateDeliveryLocation($location_details,$delivery_id)
	{
		$date = date('Y-m-d H:i:s');

		$res = $this->db->where('id', $delivery_id)
		->set('location_details', $location_details);
		
		$this->db->update('delivery_notes');
		// echo $this->db->last_query();
		return $res;
	}

	public function updateDeliveryPackageStatus( $package_ids, $status, $delivery_id )
	{
		$date = date('Y-m-d H:i:s');
		$res = $this->db->where_in('id', $package_ids)
		->where('delivery_id', $delivery_id)
		->set('status', $status)
		->set('updated_date', $date)
		->update('delivery_packages');

		return $res;
	}


	public function updatePackageStatus( $row_ids, $status )
	{

		$res = $this->db->select('package_id')->where_in('id', $row_ids) ->get('delivery_packages');
		$date = date('Y-m-d H:i:s');
		foreach($res->result_array() as $row)
		{
			$update = $this->db->where('id', $row['package_id'])
			->set('status', $status)
			->set('updated_date', $date)
			->update('project_packages');

			if(!$update){
				return false;
			} 
		}


		return $res;
	}

	public function getDeliveryInfo($delivery_id, $package=false, $project=false,$status='')
	{
		$details=[];
		$this->db->select('dn.*,pp.code as project_code,pp.status as project_package_status')
		->where('dn.id',$delivery_id);

		if($status)
			$this->db->where('dn.status', 'pending');
		$this->db->from('delivery_notes dn')
		->select('li.user_name driver_name ')
		
		->join('login_info li', 'dn.driver_id = li.user_id')
		->join('project_packages pp', 'dn.project_id = pp.project_id');
		

		if($project){
			$this->db->select('pr.project_name, pr.customer_name, c.mobile, pr.email,pr.location,pr.status as project_status , pr.date project_date')
			->select('c.customer_id,c.customer_username,c.name as cus_name,c.mobile as customer_mobile,c.email as customer_email')
			->join('project pr', 'dn.project_id = pr.id')
			->join('customer_info c','c.customer_id = pr.customer_name');
		}

		$res=$this->db->get();
		// print_r($this->db->last_query());
		// die();
		foreach($res->result_array() as $row)
		{
			$row['driver_contact']=$this->getUserInfoField('mobile',$row['driver_id']);
			$row['enc_id']=$this->Base_model->encrypt_decrypt('encrypt',$row['id']);
			$row['status_string']=ucfirst(str_replace('_', ' ', $row['status']));

			

			if($package){
				$row['packages']= $this->getDeliveryPackages($row['id'], 'all', true);

			} 
			
			$details=$row;
		}
		// print_r($details);
		// die();
		return $details;
	}


	public function getDeliveryDetails($post_arr,$user_id=''){
		// print_r($post_arr);die();
		$details = array(); 
		$this->db->select('dn.*')
		->from('delivery_notes dn')
		->select('li.user_name driver_name')
		->join('login_info li', 'dn.driver_id = li.user_id');
		if (element('package_id',$post_arr)) {
			$this->db->where('dp.package_id=',$post_arr['package_id'])
			->join('delivery_packages dp', 'dn.id = dp.delivery_id');
		}
		if (element('delivery_code',$post_arr)) {
			$this->db->where('dn.id=',$post_arr['delivery_code']);
		}

		if ($user_id) {
			$this->db->where('dn.user_id',$user_id);
		}
		if (element('user_id',$post_arr)) {
			$this->db->where('dn.user_id',$post_arr['user_id']);
		}
		if (element('project_id',$post_arr)) {
			$this->db->where('dn.project_id',$post_arr['project_id']);
		}
		if (element('delivery_id',$post_arr)) {
			$this->db->where('dn.id',$post_arr['delivery_id']);
		}
		if (element('status',$post_arr) ) {
			if($post_arr['status']!='all'){
				$this->db->where('dn.status',$post_arr['status']);
			}
		}
		
		if (element('supervisor_id',$post_arr)) {
			$this->db->where('dn.supervisor_id',$post_arr['supervisor_id']);
		}
		

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('dn.date_created >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('dn.date_created <=', $end_date);
		}

		if (element('limit',$post_arr)) {
			$this->db->limit($post_arr['limit']);
		}
		
		if (element('order',$post_arr)) {
			$this->db->order_by($post_arr['order'], $post_arr['order_by']);
		}

		$query = $this->db->get();   
		// echo $this->db->last_query();
		// die();

		foreach($query->result_array() as $row){
			$row['project_name']=$this->getProjectName($row['project_id']);
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['date_created']=date( 'd-m-Y H:i:s', strtotime($row['date_created']));

			if (element('package_id',$post_arr)) { 
				$row['packages']= $this->getDeliveryPackages($row['id'], 'all');
			} 

			$details[] = $row;
		}
		return $details;

	}
	public function getDeliveryDetail($post_arr,$user_id=''){
		// print_r($post_arr);die();
		$details = array(); 
		$this->db->select('dn.*')
		->from('delivery_notes dn')
		->select('li.user_name driver_name')
		->join('login_info li', 'dn.driver_id = li.user_id');

		if (element('package_id',$post_arr)) {
			$this->db->where('dp.package_id=',$post_arr['package_id'])
			->join('delivery_packages dp', 'dn.id = dp.delivery_id');
		}
		if (element('delivery_code',$post_arr)) {
			$this->db->where('dn.id=',$post_arr['delivery_code']);
		}

		if ($user_id) {
			$this->db->where('dn.user_id',$user_id);
		}
		if (element('user_id',$post_arr)) {
			$this->db->where('dn.user_id',$post_arr['user_id']);
		}
		if (element('project_id',$post_arr)) {
			$this->db->where('dn.project_id',$post_arr['project_id']);
		}
		if (element('delivery_id',$post_arr)) {
			$this->db->where('dn.id',$post_arr['delivery_id']);
		}
		if (element('status',$post_arr) ) {
			if($post_arr['status']!='all'){
				$this->db->where('dn.status',$post_arr['status']);
			}
		}
		
		if (element('supervisor_id',$post_arr)) {
			$this->db->where('dn.supervisor_id',$post_arr['supervisor_id']);
		}
		

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('dn.date_created >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('dn.date_created <=', $end_date);
		}

		if (element('limit',$post_arr)) {
			$this->db->limit($post_arr['limit']);
		}
		
		if (element('order',$post_arr)) {
			$this->db->order_by($post_arr['order'], $post_arr['order_by']);
		}
		if(log_user_type() == 'store_keeper'){
			$this->db->where('dn.status!=', 'deleted');
		}
		$query = $this->db->get();   
		// echo $this->db->last_query();
		// die();

		foreach($query->result_array() as $row){
			$row['project_name']=$this->getProjectName($row['project_id']);
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['date_created']=date( 'd-m-Y H:i:s', strtotime($row['date_created']));

			if (element('package_id',$post_arr)) { 
				$row['packages']= $this->getDeliveryPackages($row['id'], 'all');
			} 

			$details[] = $row;
		}
		return $details;

	}


	public function getDeliveryPackages($delivery_id, $status= 'all', $items=false)
	{
		$details=[];
		$this->db->select('dp.*')
		->select('pp.name package_name, pp.code package_code, pp.status package_status')
		->from('delivery_packages dp')
		->where('dp.delivery_id', $delivery_id)
		->join('project_packages pp', 'pp.id = dp.package_id');
		if( $status == 'all'){
			$this->db->where('dp.status!=', 'deleted');
		}
		$query = $this->db->get();
		if( $items){
			$this->load->model('Packages_model');
		}

		foreach($query->result_array() as $row){
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['status_string']=ucfirst(str_replace('_', ' ', $row['status']));

			if( $items){
				$row['items']= $this->Packages_model->getPackageItems($row['package_id']);
			}
			$details[] = $row;
		}
		return $details;
	}



	public function isPackageByCodeNameExist($package_code, $package_name, $project_id) {
		$id = 0 ;
		$this->db->select("id");
		$this->db->from("project_packages");
		$this->db->where('code', $package_code);
		$this->db->where('project_id', $project_id);
		$this->db->where('name', $package_name);
		$this->db->where('status', 'pending');
		$query = $this->db->get();  
		foreach ($query->result()AS $row) {
			$id = $row->id;
		}

		return $id;
	}


	public function removeDeliveryPackage($delivery_package_id, $delivery_id)
	{
		$this->db->set( 'status','deleted' );
		$this->db->where( 'id', $delivery_package_id );
		$this->db->where( 'delivery_id', $delivery_id );
		$res=$this->db->update( 'delivery_packages' );
		// echo $this->db->last_query();
		// die();
		return $res;
	}
	public function removeCategory($category_id)
	{
		$this->db->set( 'status','deleted' );
		$this->db->where( 'id', $category_id );

		$res=$this->db->update( 'category' );
		// echo $this->db->last_query();
		// die();
		return $res;
	}


	public function checkAlreadyPackageAdded($package_id) {
		$this->db->select("COUNT(id) as count");
		$this->db->from("delivery_packages");
		$this->db->where('package_id', $package_id);
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


	public function hasPackageItems($package_id) {
		$this->db->select("COUNT(id) as count");
		$this->db->from("package_items");
		$this->db->where('package_id', $package_id);
		$this->db->where('status!=', 'deleted');

		$query = $this->db->get();

		foreach ($query->result()AS $row) {
			
			if($row->count > 0){
				
				return false;
			}else{
				return true;
			}
		}

	}

	public function getDeliveryIdByCode($code, $status='send_to_delivery') {
		$id = 0 ;
		$this->db->select("id");
		$this->db->from("delivery_notes");
		$this->db->where('code', $code);
		if($status != 'all'){
			$this->db->where('status', $status);
		}
		$query = $this->db->get(); 
		foreach ($query->result()AS $row) {
			$id = $row->id;
		}

		return $id;
	}

	
	public function updateDeliveryNotes($post,$id)
	{
		$this->db->set('project_id', $post['project_id']);
		$this->db->set('vehicle', $post['vehicle']);
		$this->db->set('driver_id', $post['driver']);
		$this->db->where('id',$id);
		$update = $this->db->update('delivery_notes');
		return $update;
	}

	public function deleteDeliveryNotes($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('delivery_notes');

		if($this->db->affected_rows()){

			$this->db->set( 'status','deleted' ); 
			$this->db->where( 'delivery_id', $id );
			$res=$this->db->update( 'delivery_packages' );
			return $res;
		}

		return false;
	}

	public function getDeliveryAjax($post_arr=[],$count=0){
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];

		$details = array(); 

		$this->db->select('dn.*')
		->from('delivery_notes dn')
		->select('li.user_name driver_name')
		->join('login_info li', 'dn.driver_id = li.user_id');

		if (element('search',$post_arr)) {
			$searchValue = $post_arr['search']['value']; // Search value
			if('' != $searchValue) { 
				
				$where = "(dn.project_id LIKE '%$searchValue%' 
				OR dn.driver_id LIKE '%$searchValue%'
				OR dn.vehicle LIKE '%$searchValue%'
				OR dn.status LIKE '%$searchValue%'
				OR dn.date_created LIKE '%$searchValue%'
				OR dn.code LIKE '%$searchValue%'
				OR li.user_name LIKE '%$searchValue%'
			)";


			$this->db->where($where);
		}
	}

	if(!empty($post_arr['order'])) {

		$columnIndex = $post_arr['order'][0]['column'];  
		$columnName = $post_arr['columns'][$columnIndex]['data']; 
		$columnSortOrder = $post_arr['order'][0]['dir'];  
	}
	if (element('package_id',$post_arr)) {
		$this->db->where('dp.package_id=',$post_arr['package_id'])
		->join('delivery_packages dp', 'dn.id = dp.delivery_id');
	}
	if (element('delivery_code',$post_arr)) {
		$this->db->where('dn.id=',$post_arr['delivery_code']);
	}
	if (element('user_id',$post_arr)) {
		$this->db->where('dn.user_id',$post_arr['user_id']);
	}
	if (element('project_id',$post_arr)) {
		$this->db->where('dn.project_id',$post_arr['project_id']);
	}
	if (element('delivery_id',$post_arr)) {
		$this->db->where('dn.id',$post_arr['delivery_id']);
	}
	if (element('status',$post_arr) ) {
		if($post_arr['status']!='all'){
			$this->db->where('dn.status',$post_arr['status']);
		}
	}

	if (element('supervisor_id',$post_arr)) {
		$this->db->where('dn.supervisor_id',$post_arr['supervisor_id']);
	}


	if (element('start_date',$post_arr)) {
		$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
		$this->db->where('dn.date_created >=', $start_date); 
	}

	if (element('end_date',$post_arr)) {
		$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
		$this->db->where('dn.date_created <=', $end_date);
	}

	if (element('limit',$post_arr)) {
		$this->db->limit($post_arr['limit']);
	}

	if (element('order',$post_arr)) {
		$this->db->order_by($post_arr['order'], $post_arr['order_by']);
	}
	else{
		$this->db->order_by('dn.date_created', 'DESC');
	}
	if($count) {
		return $this->db->count_all_results();
	}
	$this->db->limit($rowperpage, $row);
	$query = $this->db->get();   
		// echo $this->db->last_query(); die();
	$i=1;
	foreach($query->result_array() as $row){
		$row['index'] =$post_arr['start']+$i;
		$i++;
		$row['project_name']=$this->getProjectName($row['project_id']);
		$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
		$row['date_created']=date( 'd-m-Y H:i:s', strtotime($row['date_created']));
		$row['last_updated']=date( 'd-m-Y H:i:s', strtotime($row['last_updated']));
		if (element('package_id',$post_arr)) { 
			$row['packages']= $this->getDeliveryPackages($row['id'], 'all');
		} 


		$print_view ='<div id="printdiv" class="printdiv" style="display: none">
		<table border="0" style="width: 100%;">
		<tr>
		<td>';

		$print_view .="<p>{$row['project_name']}</p></td>";
		$print_view .="<p style='font-weight: bold'> {$row['code']} </p>";
		$print_view .="<p>{$row['date_created']}</p></td>";

		$print_view .="<small style='font-weight: bold'> {$row['code']} </small>";
		$print_view .='</td>';

		$print_view .=' <td align="right">' ;

		$print_view .='<table border="0" style="width: 100%;">
		<tr>' ;
		$print_view .="<td align='right'> <img src='".base_url('assets/images/qr_code/delivery/')."{$row['code']}.png' style='max-width: 100px'>
		</td>";
		$print_view .='</tr>
		<tr>' ;
		$print_view .='</tr>
		</table>' ;

		$print_view .='</td>' ;

		$print_view .='</tr>
		</table> 
		</div>' ;
		$row['print_view']=$print_view;


		$details[] = $row;
	}
	return $details;

}

public function getDeliveryCount($customer_id='',$user_type='')
{
	$this->db->select('*');
	$this->db->from('delivery_notes');

	if($user_type=='store_keeper')
	{
		$this->db->where('user_id',$customer_id);
	}
	if($user_type=='supervisor_id')
	{
		$this->db->where('supervisor_id',$customer_id);
	}
	$count = $this->db->count_all_results();
	return $count;
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
public function getMaxCategoryId() {
	$id = NULL;
	$this->db->select_max('id');
	$this->db->from('category');
	$this->db->limit(1);
	$query = $this->db->get();
	foreach ($query->result() as $row) {
		$id = $row->id;
	}
	return $id;
}

public function getAllCategories($id='',$main_category='')
{
	$details=[];
	$this->db->select('*')
	->from('category');
	$this->db->where('status!=','deleted');
	if ($id) {
		$this->db->where('id',$id);

	}
	if ($main_category) {
		$this->db->where('main_category',$main_category);
	}
	$query = $this->db->get();   

	foreach($query->result_array() as $row){
		if ($row['main_category']==0) {
			$row['sub_category_name']=$this->getCategoryName($row['main_category']);
		}
		else
			$row['sub_category_name']=$row['category_name'];

		$row['main_category_name']=$this->getCategoryName($row['main_category']);
		$row['enc_id']=$this->encrypt_decrypt('encrypt',$row['id']);
		$details[]=$row;
	}
	return $details;
}


public function addCategory($post)
{
	$date=date('Y-m-d H:i:s');
	$this->db->set('code', $post['code'])
	->set('category_name', $post['category_name'])
	->set('date',$date);
	if (array_key_exists('category_id', $post)) {
		$this->db->set('main_category',$post['category_id']);
	}else{
		$this->db->set('sort_order', $post['sort_order']);
	}
	$res = $this->db->insert('category');
	return $this->db->insert_id();
}
public function editCategory($post,$id)
{

	$this->db->set('code', $post['code'])
	->set('category_name', $post['category_name'])
	->set('main_category',$post['main_category'])
	->set('sort_order',$post['sort_order']);
	$this->db->where('id',$id);
	$res = $this->db->update('category');
	return $res;
}
public function getCategoryName($id='')
{
	if ($id) {
		$this->db->select('category_name')
		->from('category')
		->where('id',$id);
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			return $row->category_name;
		}
	}
	else
	{
		return '----';
	}
}


function generatePdf($data){
	$this->load->library('Pdf');

	$project_package_status = str_replace( '_', ' ' , ucfirst($data['project_package_status']));
	$delivery_status = str_replace( '_', ' ' , ucfirst($data['status']));


	$tableContent = '';
	$package_count=0;
	$row_count =0;
	foreach ($data['packages'] as $key => $v) {

		$row_count = count($data['packages'][$key]['items']);
		$package_count = $row_count;

		$tableContent .= '<tr style="font-size: 10px">';

		$row_count_count = $row_count;
		if (count($data['packages'][$key]['items']) >= 2){
			$rowspan =  count($data['packages'][$key]['items']) > 0 ? count($data['packages'][$key]['items']) : 1;
				// $rowspan= $rowspan+1;
				// $tableContent .= '<td rowspan="'.$rowspan.'" >' ;
				// $tableContent .= $key+1; 
				// $tableContent .= '</td>';

			$tableContent .= '<td rowspan="'.$rowspan.'" style="border-bottom: 1px solid #222">' ;
			$tableContent .= '<strong>'. ($key+1) .'.</strong>P-Code: '.$v['package_code'].' <br>'; 
			$tableContent .= '<small>Name: <span>'. $v['package_name'] .'</span></small><br>'; 
			$tableContent .= '<small>Status: <span>'. str_replace( '_', ' ' , ucfirst($v['status'])) .'</span></small><br>'; 
			$tableContent .= '</td>';


			foreach ($data['packages'][$key]['items'] as $p_key => $pi) {

				if($p_key == 0){

					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['serial_no'] .'</td>';
					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['name'] .'</td>';
					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['qty'] .'</td>';

					$row_count = $row_count -1;
					$tableContent .= '</tr>';


				}else{
					$tableContent .= '<tr style="font-size: 10px">';

					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['serial_no'] .'</td>';
					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['name'] .'</td>';
					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['qty'] .'</td>';

					$row_count = $row_count -1;
					$tableContent .= '</tr>';

				}

			}
			$package_count= $package_count+1;
		}else{				

			$rowspan =  $row_count > 0 ? $row_count : 1;
			$rowspan= $rowspan+2;


			$tableContent .= '<td rowspan="'.$row_count_count.'" style="border-bottom: 1px solid #222">' ;
			$tableContent .= '<strong>'. ($key+1) .'.</strong>P-Code: '.$v['package_code'].' <br>'; 
			$tableContent .= '<small>Name: <span>'. $v['package_name'] .'</span></small><br>'; 
			$tableContent .= '<small>Status: <span>'. str_replace( '_', ' ' , ucfirst($v['status'])) .'</span></small><br>'; 
			$tableContent .= '</td>';

			foreach ($data['packages'][$key]['items'] as $p_key => $pi) {
				$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['serial_no'] .'</td>';
				$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['name'] .'</td>';
				$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['qty'] .'</td>';
			}
			$tableContent .= '</tr>';
		}

	}

	$site_logo = base_url('assets/images/logo/logo_pdf.png');
	$delivery_qr = base_url('assets/images/qr_code/delivery/10459').'.png';
	$project_qr = base_url('assets/images/qr_code/project/9'). '.png';
		// $delivery_qr = base_url('assets/images/qr_code/delivery/').$data['code']. '.png';
		// $project_qr = base_url('assets/images/qr_code/project/').$data['project_id']. '.png';




	$html = '
	<style>
	table, tr, td {
		padding: 15px;
	}
	</style>
	<table >
	<tbody>
	<tr>
	<td><img src="'.$site_logo.'" height="60px"/><br/>
	<strong>'.$this->data["site_details"]["name"].'</strong> 
	<small>hello@pinetreelane.com</small><br/>
	<small>'.$this->data[ 'site_details' ]['phone'].'</small>
	<p><strong>DELIVERY NOTE</strong></p>
	</td>

	<td align="right">
	<strong>CUSTOMER DETAILS</strong>
	<br/>
	Name: '.$data['cus_name'].'<br/>
	Email: '.$data['customer_email'].'<br/>
	Mobile: '.$data['customer_mobile'].'<br/>
	<img src="'.$project_qr.'" height="60px"/><br/>
	</td>
	</tr>
	</tbody>
	</table>
	';


	$html .= '
	<table>
	<tbody>
	<tr>
	<td>
	<strong>PROJECT DETAILS</strong><br/>
	Code: '.$data['project_code'].' 
	<br/>
	Name: '.$data['project_name'].' 
	<br/>
	Status: '.$project_package_status.' 
	</td>

	<td align="right">
	<strong>DELIVERY DETAILS</strong><br/>
	Delivery Code: '.$data['code'].' <br/>
	Delivery Person: '.$data['driver_name'].' <br/>
	Vehicle: '.$data['vehicle'].'  <br/>
	Status: '.$delivery_status.'  <br/>
	<img src="'.$delivery_qr.'" height="60px"/><br/>
	</td>
	</tr>
	</tbody>
	</table>
	';

	$html .= '
	<table>
	<thead>
	<tr style="font-weight:bold; background-color:#dbdbdb" >
	<th>Package</th>
	<th>Code</th>
	<th>Item name</th> 
	<th>Quantity</th> 
	</tr>
	</thead>
	<tbody>';


	$html .= $tableContent;
	$html .= '
	</tbody>
	</table>';


	$html .= '
	<table>
	<tbody>
	<tr>
	<td>
	<h2>Thank you for your business.</h2><br/>
	<strong>Terms and conditions:<br/></strong>
	This is a computer generated delivery note. Goods once dispatched are to be considered in good condition unless company is notified within 3 day\'s of receipt .
	<br/>
	<br/>
	For 
	<br/>
	Pine Tree Lane Furniture Manufacturing LLC
	</td>
	</tr>
	</tbody>
	</table>
	';


	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	$pdf->SetMargins(-1, 0, -1);

	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf->setFontSubsetting(true);

	$fontname = TCPDF_FONTS::addTTFfont('./assets/ubuntu.ttf', 'TrueTypeUnicode', '', 96);
	$pdf->SetFont($fontname, '', 10);
	$pdf->AddPage();

	$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);

		// $attachmentString= $pdf->Output('Delivery-details.pdf', 'I');	
	return $attachmentString= $pdf->Output('Delivery-details.pdf', 'S');

}

public function getDeliveryListApi($post_arr,$count='')
{
	

	$details = array(); 
	

	$this->db->select('dn.*')
	->from('delivery_notes dn')
	->select('li.user_name driver_name')
	->join('login_info li', 'dn.driver_id = li.user_id');

	if (element('search',$post_arr)) {
		$searchValue = $post_arr['search']['value']; 
		if('' != $searchValue) { 

			$where = "(dn.project_id LIKE '%$searchValue%' 
			OR dn.driver_id LIKE '%$searchValue%'
			OR dn.vehicle LIKE '%$searchValue%'
			OR dn.status LIKE '%$searchValue%'
			OR dn.date_created LIKE '%$searchValue%'
			OR dn.code LIKE '%$searchValue%'
			OR li.user_name LIKE '%$searchValue%'
		)";


		$this->db->where($where);
	}
}

if(!empty($post_arr['order'])) {

	$columnIndex = $post_arr['order'][0]['column'];  
	$columnName = $post_arr['columns'][$columnIndex]['data']; 
	$columnSortOrder = $post_arr['order'][0]['dir'];  
}
if (element('package_id',$post_arr)) {
	$this->db->where('dp.package_id=',$post_arr['package_id'])
	->join('delivery_packages dp', 'dn.id = dp.delivery_id');
}
if (element('delivery_code',$post_arr)) {
	$this->db->where('dn.id=',$post_arr['delivery_code']);
}
if (element('user_id',$post_arr)) {
	$this->db->where('dn.user_id',$post_arr['user_id']);
}
if (element('project_id',$post_arr)) {
	$this->db->where('dn.project_id',$post_arr['project_id']);
}
if (element('delivery_id',$post_arr)) {
	$this->db->where('dn.id',$post_arr['delivery_id']);
}
if (element('status',$post_arr) ) {
	if($post_arr['status']!='all'){
		$this->db->where('dn.status',$post_arr['status']);
	}
}

if (element('supervisor_id',$post_arr)) {
	$this->db->where('dn.supervisor_id',$post_arr['supervisor_id']);
}


if (element('start_date',$post_arr)) {
	$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
	$this->db->where('dn.date_created >=', $start_date); 
}

if (element('end_date',$post_arr)) {
	$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
	$this->db->where('dn.date_created <=', $end_date);
}

if (element('limit1', $post_arr)){
	$this->db->limit($post_arr['limit1'],$post_arr['page']);
}

if (element('limit',$post_arr)) {
	$this->db->limit($post_arr['limit']);
}

if (element('order',$post_arr)) {
	$this->db->order_by($post_arr['order'], $post_arr['order_by']);
}
else{
	$this->db->order_by('dn.date_created', 'DESC');
}
if($count) {
	return $this->db->count_all_results();
}

$query = $this->db->get();   
		// echo $this->db->last_query(); die();

foreach($query->result_array() as $row){
	
	
	$row['project_name']=$this->getProjectName($row['project_id']);
	$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
	$row['date_created']=date( 'd-m-Y H:i:s', strtotime($row['date_created']));
	$row['last_updated']=date( 'd-m-Y H:i:s', strtotime($row['last_updated']));
	

	if (element('status',$post_arr) ) {
		
		$row['status_string']=ucfirst(str_replace('_', ' ', $row['status']));
		
	}

	if (element('package_id',$post_arr)) { 
		$row['packages']= $this->getDeliveryPackages($row['id'], 'all');
	} 


	$details[] = $row;
}
return $details;
}

public function getDeliveryListApiCount($post_arr,$count='')
{

	$details = array(); 
	

	$this->db->select('dn.*')
	->from('delivery_notes dn')
	->select('li.user_name driver_name')
	->join('login_info li', 'dn.driver_id = li.user_id');

	if (element('search',$post_arr)) {
		$searchValue = $post_arr['search']['value']; 
		if('' != $searchValue) { 

			$where = "(dn.project_id LIKE '%$searchValue%' 
			OR dn.driver_id LIKE '%$searchValue%'
			OR dn.vehicle LIKE '%$searchValue%'
			OR dn.status LIKE '%$searchValue%'
			OR dn.date_created LIKE '%$searchValue%'
			OR dn.code LIKE '%$searchValue%'
			OR li.user_name LIKE '%$searchValue%'
		)";


		$this->db->where($where);
	}
}

if(!empty($post_arr['order'])) {

	$columnIndex = $post_arr['order'][0]['column'];  
	$columnName = $post_arr['columns'][$columnIndex]['data']; 
	$columnSortOrder = $post_arr['order'][0]['dir'];  
}
if (element('package_id',$post_arr)) {
	$this->db->where('dp.package_id=',$post_arr['package_id'])
	->join('delivery_packages dp', 'dn.id = dp.delivery_id');
}
if (element('delivery_code',$post_arr)) {
	$this->db->where('dn.id=',$post_arr['delivery_code']);
}
if (element('user_id',$post_arr)) {
	$this->db->where('dn.user_id',$post_arr['user_id']);
}
if (element('project_id',$post_arr)) {
	$this->db->where('dn.project_id',$post_arr['project_id']);
}
if (element('delivery_id',$post_arr)) {
	$this->db->where('dn.id',$post_arr['delivery_id']);
}
if (element('status',$post_arr) ) {
	if($post_arr['status']!='all'){
		$this->db->where('dn.status',$post_arr['status']);
	}
}

if (element('supervisor_id',$post_arr)) {
	$this->db->where('dn.supervisor_id',$post_arr['supervisor_id']);
}


if (element('start_date',$post_arr)) {
	$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
	$this->db->where('dn.date_created >=', $start_date); 
}

if (element('end_date',$post_arr)) {
	$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
	$this->db->where('dn.date_created <=', $end_date);
}

if (element('limit1', $post_arr)){
	$this->db->limit($post_arr['limit1'],$post_arr['page']);
}

if (element('limit',$post_arr)) {
	$this->db->limit($post_arr['limit']);
}

if (element('order',$post_arr)) {
	$this->db->order_by($post_arr['order'], $post_arr['order_by']);
}
else{
	$this->db->order_by('dn.date_created', 'DESC');
}
if($count) {
	return $this->db->count_all_results();
}

$query = $this->db->count_all_results();   

return $query;
}


}