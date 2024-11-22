<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Backup Controller
*
* @package     My Club Trading
* @subpackage  Backup
* @category    Model
* @author      My Club Trading Team
* @link        myclubtrading.com
*/
class Website_model extends Base_Model {
	
	function __construct()
	{
		parent::__construct();
	}

	public function customerRegister($post_arr='')
	{
		$date=date('Y-m-d H:i:s');
		
		$this->db->set('firstname',$post_arr['first_name']);
		$this->db->set('lastname',$post_arr['last_name']);
		$this->db->set('gender',$post_arr['gender']);
		$this->db->set('email',$post_arr['email']);
		$this->db->set('mobile',$post_arr['mobile']);
		// $this->db->set('date',$post_arr['date']);
		$this->db->set('age',$post_arr['age']);

		if (element('current_job',$post_arr)) {
			$this->db->set('current_job',$post_arr['current_job']);
			
		}
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

		
		$this->db->insert('customer_info');
		$result=$this->db->insert_id();
		

		return $result;
		
	}
}