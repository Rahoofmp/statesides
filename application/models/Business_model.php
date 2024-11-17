<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Business_model extends Base_model {

	function __construct() {
		parent::__construct();

	}

	public function isSecurePinValid($user_id,$secure_pin) {
		$flag = false;
		$this->db->select('user_id');
		$this->db->where('user_id', $user_id);
		$this->db->where('secure_pin', $secure_pin);
		$this->db->from('login_info');
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows()) {
			return true;
		}
		return $flag;
	}

	public function getWalletSummery($user_id =NULL) {

		$data = array();
		$this->db->select_sum('amount_payable');
		$this->db->select('view_amt_type');
		$this->db->from('commission_details cd');
		$this->db->join('amount_type at', 'at.id = cd.amount_type_id');
		if ($user_id) {
			$this->db->where('user_id', $user_id);
		}  
		$this->db->group_by('amount_type_id');
		$query = $this->db->get();
		foreach ($query->result_array() as $row) { 
			$data[] = $row; 
		} 
		return $data;
	}

	public function getAccountDetails( $user_id='', $category_type='' )
	{
		$details = array();
		$user_page_total_amount = 0;
		$user_total_amount = 0;
		$this->db->select('cd.from_id, cd.total_amount, cd.amount_type_id, cd.amount_payable, cd.payout_ref_id');
		$this->db->select('cd.date_of_submission, cd.fund_transfer_type, cd.transaction_note');
		$this->db->from('commission_details cd');

		if($user_id){
			$this->db->where( 'cd.user_id', $user_id );
		}

		if( $category_type ){
			$this->db->where('cd.amount_type_id',$category_type);
		}
		$query  = $this->db->get();

		foreach($query->result_array() as $row){
			$row["payout_status"] = "NA";
			
			$user_page_total_amount += $row["amount_payable"];

			$row["user_page_total_amount"] = $this->currency->format($user_page_total_amount);
			$row["from_name"] = $this->Base_model->getUserName($row['from_id']);
			$row["category_name"] = $this->Base_model->getAmountTypeName($row['amount_type_id']);

			if($row["payout_ref_id"] !=0){
				$row["payout_status"] = $this->getPayoutStatus($row['payout_ref_id']);
			}
			
			$details[] =  $row;
		}

		// $details['user_total_amount'] =$this->currency->format($this->getTotalAmountByQuery($user_id,$category_type));
		return $details;
	}

	public function getPayoutStatus($payout_id){
		$payout_status = 'pending';
		$this->db->select('status');
		$this->db->from('payout_requests');
		$this->db->where('req_id',$payout_id);
		$query = $this->db->get();
		foreach($query->result() as $row){
			$payout_status = $row->status;
		}
		return $payout_status;
	}

	public function getTotalAmountByQuery($user_id,$category_id)
	{
		$amount_payable = 0;
		$this->db->select_sum('amount_payable');
		$this->db->from('commission_details');
		$this->db->where('user_id',$user_id);
		if($category_id ){
			$this->db->where('amount_type_id',$category_id);
		}
		$query  = $this->db->get(); 
		foreach($query->result_array() as $row){
			$amount_payable =  $row["amount_payable"];
		}
		return $amount_payable;
	}

	public function getCategoryDetails(){
		$details = array();
		$this->db->select('*');
		$this->db->from('amount_type');
		$this->db->order_by('sort_order');
		$query = $this->db->get();
		foreach ($query->result_array() as $row) {
			$details[] = $row;

		}
		return $details; 
	}

	public function getPayoutDetails($user_id="", $limit = 0, $page = 0,$type='') {
		$data = array();
		$this->db->select('*');
		$this->db->from('payout_requests');
		if($user_id)
			$this->db->where('user_id', $user_id);
		if($type)
			$this->db->where('status', $type);

		$this->db->order_by('requested_date', 'DESC');
		if ($limit != '' || $page != '') {
			$this->db->limit($limit, $page);
		}
		$query = $this->db->get();
		$i = 0;
		$page_no = $page + 1;
		foreach ($query->result_array() as $row) {
			$page_no = str_pad($page_no, 4, '0', STR_PAD_LEFT);
			$data[$i]['page_no'] = $page_no;
			$data[$i]['id'] = $row['req_id'];
			$data[$i]['enc_id'] = $this->encrypt_decrypt("encrypt",$row['req_id']);			
			$data[$i]['amount'] = $row['amount'] - $row['balance_amount'];
			$data[$i]['date'] = date("Y-m-d", strtotime($row['requested_date']));
			$data[$i]['status'] = $row['status'];
			$data[$i]['amount_format']= $this->currency->format(($data[$i]['amount']));
			$data[$i]['user_id'] = $row['user_id'];
			$data[$i]['user_name'] = $this->Base_model->getUserName($row['user_id']);
			$data[$i]['full_name'] = $this->Base_model->getUserInfoField('first_name',$row['user_id']) . $this->Base_model->getUserInfoField('second_name',$row['user_id']);
			$data[$i]['by_using'] = $row['by_using'];
			$data[$i]['destination'] = $row['destination'];
			$i++;
			$page_no = $page_no + 1;
		}
		return $data;
	}

	public function getAllBonusTypes() {
		$details = array();
		$this->db->select('*');
		$this->db->where('show_status','yes');
		$this->db->from('amount_type');
		$this->db->order_by('sort_order');
		$query = $this->db->get();
		foreach ($query->result_array() as $row) {
			$details[] = $row;
		}
		return $details;
	}

	public function getWalletGraphSummery($user_id =NULL) {

		$data = array();
		$this->db->select_sum('amount_payable');
		$this->db->from('commission_details cd');
		if ($user_id) {
			$this->db->where('user_id', $user_id);
		}  
		$this->db->limit(32);
		$this->db->group_by('date_of_submission');
		$query = $this->db->get(); 
		foreach ($query->result_array() as $row) { 
			$data[] = $row['amount_payable']; 
		} 
		// print_r($data);die(); 
		return $data;
	}

	public function getCommissionDetailsCount($user_id, $amount_type_id = 0) {
		$this->db->select('from_id');
		$this->db->from('commission_details');
		if ($user_id) {
			$this->db->where('user_id', $user_id);
		}
		if ($amount_type_id) {
			$this->db->where('amount_type_id', $amount_type_id);
		}
		$this->db->order_by('date_of_submission', 'DESC');
		return $this->db->count_all_results();
	}

	public function getCommissionDetails($user_id, $amount_type_id = 0, $limit = 0, $page = 0) {
		$data = array();
		$this->db->select('date_of_submission,amount_payable,amount_type_id,from_id');
		$this->db->from('commission_details');
		if ($user_id) {
			$this->db->where('user_id', $user_id);
		}
		if ($amount_type_id) {
			$this->db->where('amount_type_id', $amount_type_id);
		}
		$this->db->order_by('date_of_submission', 'ASC');
		if ($limit) {
			$this->db->limit($limit, $page);
		}
		$query = $this->db->get();
		$i = 0;
		$page_no = $page + 1;
		$total = 0;
		foreach ($query->result_array() as $row) {
		//check user status here
			$data[$i]['page_no'] 	= $page_no;
			$data[$i]['date'] 		= $row['date_of_submission'];
			$data[$i]['amount'] 	= $row['amount_payable'];
			$data[$i]['amount_type_id'] = $row['amount_type_id'];
			$data[$i]['amount_type']= $this->Base_model->getAmountTypeName($row['amount_type_id']);
			$data[$i]['amount_format']= $this->currency->format(($row['amount_payable']));
			$data[$i]['from_user'] 	= $this->Base_model->getUserName($row['from_id']);
			$data[$i]['count'] 		= $i + 1;
			$total = $total + $data[$i]['amount'];
			$i++;
			$page_no 				= $page_no + 1;
		}
		if($i > 0)
			$data[$i-1]['total']=$this->currency->format($total);
		return $data;
	}

	public function insertPayoutReleaseRequest($user_id, $payout_amount ,$request_date, $status='',$transaction_amt=0,$release_type='bank',$destination){
		$this->db->set('user_id',$user_id);
		$this->db->set('amount',$payout_amount);
		$this->db->set('transaction_fee',$transaction_amt);
		$this->db->set('requested_date',$request_date);
		$this->db->set('by_using',$release_type);
		$this->db->set('status',$status);
		$this->db->set('destination',$destination);
		$result = $this->db->insert('payout_requests');
		$insert_id = $this->db->insert_id();
		return $insert_id; 
	}



	public function getUpgradeWalletDetails($user_id,$category_type)
	{
		$details = array();
		$user_page_total_amount = 0;
		$user_total_amount = 0;
		$this->db->select('from_id,total_amount,amount_type_id');
		$this->db->select('date_of_submission,fund_transfer_type,transaction_note');
		$this->db->from('upgrade_wallet');
		$this->db->where('user_id',$user_id);
		if($category_type != 'all'){
			$this->db->where('amount_type_id',$category_type);
		}
		$query  = $this->db->get();
		foreach($query->result_array() as $row){
			
			$user_page_total_amount += $row["total_amount"];

			$row["user_page_total_amount"] = $this->currency->format($user_page_total_amount);
			$row["from_name"] = $this->getUserName($row['from_id']);
			$row["amount_view"] = $this->currency->format($row['total_amount']);
			$row["category_name"] = $this->getAmountTypeName($row['amount_type_id']);
			
			$details[] =  $row;
		}

		$details['user_total_amount'] =$this->currency->format($this->getTotalAmountOfUpgradeWallet($user_id,$category_type));
		return $details;
	}

	public function getTotalAmountOfUpgradeWallet($user_id,$category_type)
	{
		$total_amount = 0;
		$this->db->select_sum('total_amount');
		$this->db->from('upgrade_wallet');
		$this->db->where('user_id',$user_id);
		if($category_type != 24){
			$this->db->where('amount_type_id',$category_type);
		}
		$query  = $this->db->get();
		foreach($query->result_array() as $row){
			$total_amount =  $row["total_amount"];
		}
		return $total_amount;
	}


	public function insetRequestePinDetails($post)
	{
		$this->db->set('user_id',$post['user_id']);
		$this->db->set('package_id',$post['package_id']);
		$this->db->set('package_id',$post['package_id']);
		if(array_key_exists('file_name', $post))
		{
			if($post['file_name'])
				$this->db->set('image',$post['file_name']);
		}
		if(array_key_exists('transaction_note', $post))
		{
			$this->db->set('transaction_note',$post['transaction_note']);
		}
		$this->db->set('status','yes');
		$this->db->set('added_date',date('Y-m-d H:i:s'));
		$insert = $this->db->insert('request_epin_details');
		return $insert;
	}

	public function getRequestePinDetails($id='')
	{
		$details = array();
		$this->db->select('*');
		$this->db->from('request_epin_details');
		$this->db->where('status','yes');
		if($id)
		{
			$this->db->where('id',$id);
		}
		$query = $this->db->get();
		foreach($query -> result_array() as $row)
		{
			$row['user_name'] = $this->getUserName($row['user_id']);
			$row['package'] = $this->getCurrentPackageName($row['package_id']);
			$row['full_name'] = $this->getFullName($row['user_id']);
			$row['enc_id'] = $this->encrypt_decrypt('encrypt',$row['id']);
			if($id){
				$details = $row;
			}else{
				$details[] = $row;
			}
		}
		return $details;
	}

	public function updateRequestePinStatus($status,$id)
	{
		$this->db->set('status',$status);
		$this->db->set('updated_date',date('Y-m-d H:i:s'));
		if($status == 'approve')
		{
			$this->db->set('approve_date',date('Y-m-d H:i:s'));
		}
		$this->db->where('id',$id);
		$update = $this->db->update('request_epin_details');
		return $update;
	}

}