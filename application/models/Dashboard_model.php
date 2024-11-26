<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends Base_model {

	function __construct() {
		parent::__construct();

	}
	public function getCustomerinfo($enquiry_status = "") {
		
		$this->db->from('customer_info');
		$this->db->where('enquiry_status', $enquiry_status); 
		
		$count = $this->db->count_all_results();
		
		return $count;
	}
	public function getUserCount($type=""){
		$this->db->from('login_info');
		
		$this->db->where('status' , 1);
		$count = $this->db->count_all_results();	
		return $count;
	}
	
	public function getTotalProjectCount($user_id='',$status='',$customer_id='')
	{
		$this->db->select('id');
		$this->db->from('project');
		if ($user_id) {
			$this->db->where('user_id',$user_id);
		}
		if ($customer_id) {
			$this->db->where('customer_name', $customer_id );
		}
		if($status)
		{
			$this->db->where('status',$status);
		}
		$res = $this->db->get()->num_rows();
		return $res;

	}

	public function getAdvanceCustomers() {
		$this->db->select(); 
		$this->db->from('customer_info'); 
		$this->db->where('advance !=', 500); 
		$res = $this->db->get()->num_rows(); 
		return $res; 
	}
		public function getFullPaidcustomers() {
			$this->db->select(); 
			$this->db->from('customer_info'); 
			$this->db->where('total_amount >=', 500); 
			$res = $this->db->get()->num_rows(); 
			return $res; 
		}
		
	
	public function getImmigrationCount() {
		$this->db->select();
		$this->db->from('customer_info');
		$this->db->where('immigration_status', 'approved'); 
		$res = $this->db->get()->num_rows(); 
		return $res; 
	}
	
	public function getTotalJobOrderCount($order_id='',$status='')
	{
		$this->db->select('id');
		$this->db->from('job_orders');
		if ($order_id) {
			$this->db->where('order_id',$order_id);
		}
		
		if($status)
		{
			$this->db->where('customer_status',$status);
		}
		$res = $this->db->get()->num_rows();
		return $res;

	}
	public function getAllEnquiryDetailsCount(){
		$this->db->select('id');
		$this->db->from('customer_info');
		$res = $this->db->get()->num_rows();
		
		return $res;

	}
	public function getTotalDeliveryNotesCount($user_id='',$sup_id='')
	{
		$this->db->select('id');
		$this->db->from('delivery_notes');
		if ($user_id) {
			$this->db->where('user_id',$user_id);
		}
		if ($sup_id) {
			$this->db->where('supervisor_id',$sup_id);
		}
		$res = $this->db->get()->num_rows();
		return $res;

	}
	public function getDeliveryCount($user_id='',$status="")
	{

		$this->db->select('id');
		$this->db->from('delivery_notes');

		if ($user_id) {
			$this->db->where('user_id',$user_id);
		}
		if ($status) {
			$this->db->where('status' , $status);
		}
		$res = $this->db->get()->num_rows();
		
		// echo $this->db->last_query();
		// die();

		return $res;

	}

	public function getRecentProjectName($user_id='')
	{
		$details=array();
		$this->db->select('p.*');
		$this->db->select('c.customer_username,c.name as cus_name,c.mobile as customer_mobile,c.email as customer_email');
		if ($user_id) {
			$this->db->where('p.user_id',$user_id);
		}
		$this->db->order_by('p.id','DESC');
		$this->db->from('project p');
		$this->db->join('customer_info c','p.customer_name = c.customer_id');
		$this->db->limit(10);
		$res=$this->db->get();
		foreach ($res->result_array() as $row)
		{
			$row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt',$row['id']);
			$row['date']=date( 'd-m-Y H:i:s', strtotime($row['date']));
			$details[]=$row;
		}
		return $details;
	} 
	public function getRecentDeliveries($user_id='',$sup_id='')
	{
		$details=array();
		$this->db->select('dn.*')
		->select('li.user_name driver_name')
		->join('login_info li', 'dn.driver_id = li.user_id');
		if ($user_id) {
			$this->db->where('dn.user_id',$user_id);
		}
		if ($sup_id) {
			$this->db->where('dn.supervisor_id',$sup_id);
		}

		$this->db->order_by('dn.id','DESC');
		$this->db->where('dn.status !=','deleted');
		$this->db->limit(10);
		$res=$this->db->get('delivery_notes dn');
		$this->load->model('Packages_model');
		foreach ($res->result_array() as $row)
		{
			$row['project_name']=$this->Packages_model->getProjectName($row['project_id']);
			$row['date_created']=date( 'd-m-Y H:i:s', strtotime($row['date_created']));
			$row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt',$row['id']);
			$row['status_string']=ucfirst(str_replace('_', ' ', $row['status']));
			$details[]=$row;
		}
		return $details;
	}
	public function getCustomerRecentDeliveryNotes($customer_id='',$sup_id='',$limit=10)
	{
		$details=array();
		$this->db->select('dn.*')
		->select('p.project_name')
		->join('project p', 'dn.project_id = p.id');
		if ($customer_id) {
			$this->db->where('p.customer_name',$customer_id);
		}
		if ($sup_id) {
			$this->db->where('dn.supervisor_id',$sup_id);
		}

		$this->db->order_by('dn.id','DESC');
		$this->db->where('dn.status !=','deleted');
		$this->db->limit($limit);
		$res=$this->db->get('delivery_notes dn');
		$this->load->model('Packages_model');
		foreach ($res->result_array() as $row)
		{
			$row['date_created']=date( 'd-m-Y H:i:s', strtotime($row['date_created']));
			$row['driver_name']=$this->getUserName($row['driver_id']);
			$row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt',$row['id']);
			$details[]=$row;
		}
		return $details;
	}

	public function getRecentProjectDetails($user_id='')
	{
		$details=array();
		$this->db->select('p.*');
		$this->db->select('c.customer_username,c.name as cus_name,c.mobile as customer_mobile,c.email as customer_email');
		$this->db->select('j.project_id,j.estimated_working_hrs,j.name job_name,j.actual_workin_hrs');
		$this->db->select('dj.work_status');
		if ($user_id) {
			$this->db->where('p.user_id',$user_id);
		}
		$this->db->order_by('p.id','DESC');
		$this->db->from('project p');
		$this->db->join('customer_info c','p.customer_name = c.customer_id');
		$this->db->join('job_orders j','j.project_id = p.id');
		$this->db->join('department_jobs dj','dj.job_order_id = j.id');
		$this->db->limit(10);
		$res=$this->db->get();
		foreach ($res->result_array() as $row)
		{
			$row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt',$row['id']);
			$row['date']=date( 'd-m-Y H:i:s', strtotime($row['date']));
			$details[]=$row;
		}
		return $details;
	}
}