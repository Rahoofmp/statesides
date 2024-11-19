<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Packages_model extends Base_model {

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


	public function insertProjectPackages($post_arr='')
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('project_id',$post_arr['name']);
		$this->db->set('user_id',$post_arr['user_id']);
		$this->db->set('name',$post_arr['package']);
		$this->db->set('location',$post_arr['package_location']);
		$this->db->set('date_created',$date);
		$this->db->set('type_id',$post_arr['area_master']);
		$this->db->set('item_id',$post_arr['item']);
		$this->db->set('status','pending');
		$this->db->set('code','0');
		if($post_arr['file_name']){
			$this->db->set('image', $post_arr['file_name']);
		}
		$res = $this->db->insert('project_packages');
// print_r($res);die();
		if($res){
			$package_id = $this->db->insert_id();
			$code = $package_id+10000;

			$this->load->library('ciqrcode');
			$params['data'] = $code;
			$params['level'] = 'H';
			$params['size'] = 5;
			$params['errorlog'] = FALSE;
			$params['savename'] = './assets/images/qr_code/package/'.$code.'.png';
			$this->ciqrcode->generate($params);


			$this->db->set('code', $code); 
			$this->db->where('id', $package_id);
			$res = $this->db->update('project_packages');

			if($this->db->affected_rows()){
				return $package_id;
			}
		}

		return $res;
	}

	public function insertPackageItems( $package_id, $post)
	{
		$date=date('Y-m-d H:i:s'); 

		$this->db->set('package_id', $package_id);
		$this->db->set('serial_no', $post['serial_no']); 
		$this->db->set('name', $post['name']);
		$this->db->set('qty', $post['qty']);
		$this->db->set('date_addedd',$date);
		$res=$this->db->insert('package_items');
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
	public function getCustomerName($project_id)
	{
		$details='';
		$this->db->select('customer_name');
		$this->db->where('id',$project_id);
		$res=$this->db->get('project');
		foreach ($res->result() as $row)
		{
			$details=$row->customer_name;
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

	public function getPackageDetails($post_arr,$limit=''){
		$details = array(); 


		$this->db->select('pp.*')
		->select('pj.project_name, pj.customer_name')
		->from('project_packages pp')
		->where('pp.status!=','deleted')
		->join('project pj','pj.id = pp.project_id');

		if (element('packager_id',$post_arr)) {
			$this->db->where('pp.user_id',$post_arr['packager_id']);
		}if ($limit) {
			$this->db->limit($limit);
		}
		if (element('package_id',$post_arr)) {
			$this->db->where('pp.id',$post_arr['package_id']);
		}
		if (element('project_id',$post_arr)) {
			$this->db->where('pp.project_id',$post_arr['project_id']);
		}
		if (element('status',$post_arr)) {
			if($post_arr['status']!='all'){
				$this->db->where('pp.status',$post_arr['status']);
			}
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('pp.date_created >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('pp.date_created <=', $end_date);
		}

		if (element('order',$post_arr)) {
			$this->db->order_by("pp.{$post_arr['order']}", $post_arr['order_by']);
		}
		
		$query = $this->db->get();  

		foreach($query->result_array() as $row){
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['date_created']= date('d-m-Y H:i:s', strtotime($row['date_created']));

			if (element('items',$post_arr)==true) {
				$row['items']= $this->getPackageItems($row['id']);
			}
			
			$details[] = $row;
		}
		return $details;

	}
	public function getPackageDetail($post_arr,$limit=''){
		$details = array(); 


		$this->db->select('pp.*')
		->select('pj.project_name, pj.customer_name')
		->from('project_packages pp')
		
		->join('project pj','pj.id = pp.project_id');

		if (element('packager_id',$post_arr)) {
			$this->db->where('pp.user_id',$post_arr['packager_id']);
		}if ($limit) {
			$this->db->limit($limit);
		}
		if (element('package_id',$post_arr)) {
			$this->db->where('pp.id',$post_arr['package_id']);
		}
		if (element('project_id',$post_arr)) {
			$this->db->where('pp.project_id',$post_arr['project_id']);
		}
		if (element('status',$post_arr)) {
			if($post_arr['status']!='all'){
				$this->db->where('pp.status',$post_arr['status']);
			}
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('pp.date_created >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('pp.date_created <=', $end_date);
		}

		if (element('order',$post_arr)) {
			$this->db->order_by("pp.{$post_arr['order']}", $post_arr['order_by']);
		}
		
		$query = $this->db->get();  

		foreach($query->result_array() as $row){
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['date_created']= date('d-m-Y H:i:s', strtotime($row['date_created']));

			if (element('items',$post_arr)==true) {
				$row['items']= $this->getPackageItems($row['id']);
			}
			
			$details[] = $row;
		}
		return $details;

	}
	public function getPackageDetailAjax($post_arr=[],$count=''){
		
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];
		$this->load->model('Item_model');

		$this->db->select('pp.*')
		->select('pj.project_name, pj.customer_name')
		->from('project_packages pp')
		
		->join('project pj','pj.id = pp.project_id');

		if (element('packager_id',$post_arr)) {
			$this->db->where('pp.user_id',$post_arr['packager_id']);
		}
		if (element('package_id',$post_arr)) {
			$this->db->where('pp.id',$post_arr['package_id']);
		}
		if (element('project_id',$post_arr)) {
			$this->db->where('pp.project_id',$post_arr['project_id']);
		}
		if (element('type_id',$post_arr)) {
			$this->db->where('pp.type_id',$post_arr['type_id']);
		}
		if (element('item_id',$post_arr)) {
			$this->db->where('pp.item_id',$post_arr['item_id']);
		}
		if (element('status',$post_arr)) {
			if($post_arr['status']!='all'){
				$this->db->where('pp.status',$post_arr['status']);
			}
		}

		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('pp.date_created >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('pp.date_created <=', $end_date);
		}

		if (element('order',$post_arr)) {
			
			$this->db->order_by("pp.{$post_arr['order']}", $post_arr['order_by']);
		}

		if (element('search',$post_arr)) {
			$searchValue = $post_arr['search']['value'];
			if('' != $searchValue) { 

				$where = "(name LIKE '%$searchValue%' 
				OR code LIKE '%$searchValue%'
				OR date_created LIKE '%$searchValue%'
				OR project_name LIKE '%$searchValue%'
				OR pp.status LIKE '%$searchValue%' )";


				$this->db->where($where);
			}
		}

		if($count) {
			return $this->db->count_all_results();
		}

		$this->db->limit($rowperpage, $row);
		$query = $this->db->get(); 
		$i=1;
		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['item']= $this->Item_model->getItemCode( 'code', $row['item_id'] );
			$row['area_master']= $this->getAreaMaster( 'name', $row['type_id'] );
			$row['date_created']= date('d-m-Y H:i:s', strtotime($row['date_created']));


			$print_view = '<div id="printdiv" class="printdiv" style="display: none">';
			$print_view .= '<table border="0" style="width: 100%;"><tr>';
			
			$print_view .= '<td>';
			$print_view.="<img src='".base_url('assets/images/logo/print_logo.png')."' style='max-width: 150px'>";
			$print_view .= "<p>{$row['customer_name']}</p>";
			$print_view .= "<p>{$row['project_name']}</p>";
			$print_view .= "<p>{$row['name']}</p>";
			$print_view .= "<p>{$row['date_created']}</p>";
			$print_view .= "<p>{$row['location']}</p><span class='clearfix'></span>";
			$print_view .= "<p><small style='font-weight: bold'> {$row['code']} </small></p><span class='clearfix'></span>";
			$print_view.="<img src='".base_url('assets/images/qr_code/package/')."{$row['code']}.png' style='max-width: 100px'>";
			$print_view.="<img src='".base_url('assets/images/package_pic/').$row['image']."'  style='max-width: 100px'>";
			$print_view .= '</td>';
			

			if (element('items',$post_arr)==true) {
				$row['items']= $this->getPackageItems($row['id']);

				$print_view .= '<td rowspan="1"  style="vertical-align: top;">';
				$print_view.='<table class="items"  style="width: 100%;" align="top" >';
				$print_view.='<thead>';
				$print_view.='<tr align="top">';
				$print_view.='<th align="left">Code</th>';
				$print_view.='<th align="left">Item</th>';
				$print_view.='<th align="right">Qty</th>';
				$print_view.='</tr>';
				$print_view.='</thead>';
				$print_view.='<tbody >';
				foreach ($row['items'] as $key => $item) {

					$print_view.='<tr >';
					$print_view.="<td align='left'>{$item['serial_no']}</td>";
					$print_view.="<td align='left'>{$item['name']}</td>";
					$print_view.="<td align='right'>{$item['qty']}</td>";
					$print_view.='<tr >'; 

				}

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
	public function getPackageCount()
	{
		$this->db->select('*');
		$this->db->from('project_packages');
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getPackageItems($id)
	{
		$details=[];
		$this->db->select('pi.*')
		->where('pi.status', 'active')
		->where('pi.package_id',$id)
		->from('package_items pi');  
		$res=$this->db->get();
		// echo $this->db->last_query();;
		foreach($res->result_array() as $row)
		{
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['date_addedd']= date('d-m-Y H:i:s', strtotime($row['date_addedd']));

			$details[]=$row;
		}
		return $details;
	}

	public function getPackagesDetails($id, $items=true)
	{
		$details='';
		$this->load->model('Item_model');
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
			$row['item']= $this->Item_model->getItemCode( 'code', $row['item_id'] );
			$row['area_master']= $this->getAreaMaster( 'name', $row['type_id'] );
			$row['customer_name']= $this->Base_model->getFullName($row['user_id'] );
			$row['mobile']= $this->Base_model->getUserInfoField('mobile',$row['user_id'] );

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

	public function deletePackageItem($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('package_items');
		// echo $this->db->last_query();
		// die();
		return $res;
	}


	public function updatePackages($id,$post_arr='')
	{
		$date=date('Y-m-d');
		$this->db->set('project_id',$post_arr['project_id']);
		$this->db->set('name',$post_arr['package']);
		$this->db->set('location',$post_arr['package_location']);
		$this->db->set('updated_date',$date);
		$this->db->set('type_id',$post_arr['area_master']);
		$this->db->set('item_id',$post_arr['item']);
		if($post_arr['file_name']){
			$this->db->set('image', $post_arr['file_name']);
		}
		$this->db->where('id',$id);
		$res=$this->db->update('project_packages');
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

	public function insertTypeMaster($post_arr){
		$date =date('Y-m-d');
		$this->db->set('name',$post_arr['name']);
		$this->db->set('date',$date);
		$result = $this->db->insert('type_master');
		return $result;


	}

	public function getTypeMasterDetails($id='')
	{
		$details =[];
		$this->db->select('*');
		if($id)
		{
			$this->db->where('id',$id);
		}
		$this->db->where('status',1);
		$this->db->from('type_master');
		$res = $this->db->get();
		foreach($res->result_array() as $row)
		{
			$row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt',$row['id']);
			if($id)
			{
				return $row;
			}
			$details[] = $row;
		}
		return $details;
	}

	public function updateTypeMaster($post_arr='',$id='')
	{
		if($post_arr)
		{
			$this->db->set('name',$post_arr['name']);
			$this->db->where('id',$post_arr['id']);

		}
		if($id)
		{
			$this->db->set('status',0);
			$this->db->where('id',$id);
		}
		
		$result = $this->db->update('type_master');
		return $result;
	}

	public function getAreaMaster($field='',$id) {

		$details=null;
		$this->db->select($field)
		->where('id', $id)
		->where('status',1)
		->from('type_master');  
		$res=$this->db->get();
		// echo $this->db->last_query();;
		foreach($res->result() as $row)
		{
			return $row->$field;
		}
		return $details;
	}

	public function createLeads($post_arr='')
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('salesman_id',$post_arr['sales_man']);
		$this->db->set('firstname',$post_arr['first_name']);
		$this->db->set('lastname',$post_arr['last_name']);
		$this->db->set('gender',$post_arr['gender']);
		$this->db->set('email',$post_arr['email']);
		$this->db->set('mobile',$post_arr['mobile']);
		$this->db->set('date',$post_arr['date']);
		$this->db->set('due_amount',$post_arr['due_amount']);
		$this->db->set('total_amount',$post_arr['total_amount']);
		$this->db->set('advance',$post_arr['advance_amount']);
		$this->db->set('age',$post_arr['age']);
		$this->db->set('current_job',$post_arr['current_job']);
		$this->db->set('created_date',$date);

		if (element('ss_cirtifcate',$post_arr)) {
			
			$this->db->set('sslc_certificate',$post_arr['ss_cirtifcate']);
		}   

		if (element('police_clearence',$post_arr)) {
			
			$this->db->set('police_certificate',$post_arr['police_clearence']);
		}

		if (element('job_cirtificate',$post_arr)) {
			
			$this->db->set('job_cirtificate',$post_arr['job_cirtificate']);
		}

		if (element('passport_copy',$post_arr)) {
			
			$this->db->set('passport_copy',$post_arr['passport_copy']);
		}

		if (element('dob_certificate',$post_arr)) {
			
			$this->db->set('dob_certificate',$post_arr['dob_certificate']);
		}
		
		$result = $this->db->insert('customer_info');

		return $result;
		
	}

}
