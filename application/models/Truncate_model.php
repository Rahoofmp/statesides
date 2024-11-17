<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Truncate_model extends Base_Model {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model("Signup_model");
	}

	public function truncateAll(){
		$return_arr = array();

		$admin_id = log_user_id();
		$admin_name = log_user_name();
		$admin_pass = $this->getLoginInfoField('password', $admin_id);
		$admin_package_id = $this->getLoginInfoField('package_id', $admin_id); 
		$admin_rank_id = $this->getLoginInfoField('user_rank_id', $admin_id); 

		$admin_details = $this->getUserDetail($admin_id);
		$today = date("Y-m-d H:i:s");

		$dbprefix = $this->db->dbprefix;
		$name = $this->db->database;
		$table_list = $this->db->list_tables(); 

		$users_tb = $dbprefix.'login_info';
		if(in_array($users_tb, $table_list))
		{

			$width_ceiling = 1;

			$admin_data = array(
				"user_id" => $admin_id,
				"user_type" => "admin",
				"user_name" => $admin_name,
				"password" => $admin_pass,
				"status" => "1",
				"order_id" => 1,
				"payment_type" => "free_join",
				"joining_date" => $today,
				"left_father" => 1,
				"right_father" => 2,
				"left_sponsor" => 1,
				"right_sponsor" => 2,
				"secure_pin" => 12345678,
				"position" => 0,
				"user_reward_id" => 4,
				"user_rank_id" => $admin_rank_id,
				"package_id" => $admin_package_id
			);
			
			$this->db->truncate("login_info");
			$this->db->query("ALTER TABLE ".$users_tb." AUTO_INCREMENT = $admin_id");	
			$insert_us = $this->db->insert("login_info", $admin_data);

			// $insert_temp_user_id = $this->Signup_model->serverEntryInsert($admin_id, 1);


			if(!$insert_us)
			{	
				$return_arr['status'] = FALSE;
				$return_arr['message'] = lang('login_info_insertion_failed');
				return $return_arr;
			}
		}

		if(in_array($dbprefix.'user_info', $table_list))
		{
			$this->db->truncate("user_info");
			$insert_ud = $this->db->insert('user_info', $admin_details);
			if(!$insert_ud)
			{
				$return_arr['status'] = FALSE;
				$return_arr['message'] = lang('user_info_insertion_failed');
				return $return_arr;
			}
		}

		if(in_array($dbprefix."activate_inactivate_history", $table_list))
		{
			$this->db->truncate("activate_inactivate_history");
		}	

		if(in_array($dbprefix."activity", $table_list))
		{
			$this->db->truncate("activity");
		}   
		    

		if(in_array($dbprefix."delivery_notes", $table_list))
		{
			$this->db->truncate("delivery_notes");
		}   

		if(in_array($dbprefix."delivery_packages", $table_list))
		{
			$this->db->truncate("delivery_packages");
		}   

		if(in_array($dbprefix."documents", $table_list))
		{
			$this->db->truncate("documents");
		}   
		
		if(in_array($dbprefix."employee_info", $table_list))
		{
			$this->db->truncate("employee_info");
		}  

		if(in_array($dbprefix."events", $table_list))
		{
			$this->db->truncate("events");
		}   
		    
		if(in_array($dbprefix."internal_mail_details", $table_list))
		{
			$this->db->truncate("internal_mail_details");
		}
 
		if(in_array($dbprefix."mail_contents", $table_list))
		{
			$this->db->truncate("mail_contents");
		}
		
		if(in_array($dbprefix."mail_details", $table_list))
		{
			$this->db->truncate("mail_details");
		}
		
		if(in_array($dbprefix."news", $table_list))
		{
			$this->db->truncate("news");
		}
		 
		 
		if(in_array($dbprefix."package_items", $table_list))
		{
			$this->db->truncate("package_items");
		}
		 
		if(in_array($dbprefix."password_reset_table", $table_list))
		{
			$this->db->truncate("password_reset_table");
		}
		 
		if(in_array($dbprefix."project", $table_list))
		{
			$this->db->truncate("project");
		}

		if(in_array($dbprefix."project_packages", $table_list))
		{
			$this->db->truncate("project_packages");
		}
		
		if(in_array($dbprefix."username_change_history", $table_list))
		{
			$this->db->truncate("username_change_history");
		}


		//V2 START
		

		if(in_array($dbprefix."customer_info", $table_list))
		{
			$this->db->truncate("customer_info");
		}	

		if(in_array($dbprefix."day_progress", $table_list))
		{
			$this->db->truncate("day_progress");
		}   

		if(in_array($dbprefix."department", $table_list))
		{
			$this->db->truncate("department");
		}   

		if(in_array($dbprefix."department_jobs", $table_list))
		{
			$this->db->truncate("department_jobs");
		}   
 
		if(in_array($dbprefix."job_orders", $table_list))
		{
			$this->db->truncate("job_orders");
		}   
		//V2 END


		
		$return_arr['status'] = TRUE;
		$return_arr['message'] = "Truncate done successfully...";
		return $return_arr;
	}

	function getUserDetail($user_id) {
		$user_details = array();
		$this->db->select("*");
		$this->db->from('user_info');
		$this->db->where('user_id', $user_id);
		$res = $this->db->get();
		foreach ($res->result_array() as $row) {
			$user_details = $row;
		}
		return $user_details;
	}

	public function getAdminOCDetails() {
		$admin_oc_details = array();
		$this->db->from("customer");
		$this->db->where("customer_id", '1');
		$this->db->limit(1);
		$res = $this->db->get();

		foreach ($res->result_array() as $row) {
			$admin_oc_details = $row;
		}
		return $admin_oc_details;
	}

	public function getAdminOCAddressDetails() {
		$admin_oc_add_details = array();
		$this->db->from("address");
		$this->db->where("customer_id", '1');
		$this->db->limit(1);
		$res = $this->db->get();

		foreach ($res->result_array() as $row) {
			$admin_oc_add_details = $row;
		}
		return $admin_oc_add_details;
	}

	public function getStoreUserDetails() {
		$user_det = array();
		$this->db->select("*");
		$this->db->from("login_info");
		$this->db->where('user_id', 1);
		$this->db->or_where('username', 'store_admin');
		$res = $this->db->get();

		foreach ($res->result_array() as $row) {
			$user_det[] = $row;
		}
		return $user_det;
	}

	public function truncatePhase2(){
		$return_arr = array();

		$admin_id = log_user_id();
		$admin_name = log_user_name();
		$admin_pass = $this->getLoginInfoField('password', $admin_id);
		$admin_package_id = $this->getLoginInfoField('package_id', $admin_id); 
		$admin_rank_id = $this->getLoginInfoField('user_rank_id', $admin_id); 

		$admin_details = $this->getUserDetail($admin_id);
		$today = date("Y-m-d H:i:s");

		$dbprefix = $this->db->dbprefix;
		$name = $this->db->database;
		$table_list = $this->db->list_tables(); 


		if(in_array($dbprefix."customer_info", $table_list))
		{
			$this->db->truncate("customer_info");
		}	

		if(in_array($dbprefix."day_progress", $table_list))
		{
			$this->db->truncate("day_progress");
		}   

		if(in_array($dbprefix."department", $table_list))
		{
			$this->db->truncate("department");
		}   

		if(in_array($dbprefix."department_jobs", $table_list))
		{
			$this->db->truncate("department_jobs");
		}   
 
		if(in_array($dbprefix."job_orders", $table_list))
		{
			$this->db->truncate("job_orders");
		}   
 
		
		$return_arr['status'] = TRUE;
		$return_arr['message'] = "Truncate done successfully...";
		return $return_arr;
	}
}
