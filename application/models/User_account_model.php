<?php


class User_account_model extends Base_model {

	function __construct() {
		parent::__construct();

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

	public function getAccountDetails($user_id,$category_type)
	{

		$details = array();

		if($category_type == 'all')
		{
			$this->db->select('sum(total_amount) as total_amount,sum(amount_payable) as amount_payable');
			$this->db->select('amount_type_id,date_of_submission,from_id,fund_transfer_type');
			$this->db->from('commission_details');
			$this->db->where('user_id',$user_id);
			$this->db->where('amount_type_id',16);
			$this->db->group_by('date_of_submission');
			$this->db->group_by('from_id');
			$this->db->group_by('fund_transfer_type');
			$this->db->group_by('amount_type_id');
			$query  = $this->db->get();
			foreach($query->result_array() as $row){
				$row["payout_status"] = "NA";

				$row["from_name"] = $this->getUsername($row['from_id']);
				$row["amount_view"] = $this->currency->format($row['amount_payable']);
				$row["category_name"] = $this->getAmountTypeName($row['amount_type_id']);
				if($row["amount_type_id"] ==14){
					$row["payout_status"] = $this->getPayoutStatus($row['payout_ref_id']);
				}

				$details[] =  $row;
			}

		}

		if($category_type == 16)
		{
			$this->db->select('sum(total_amount) as total_amount,sum(amount_payable) as amount_payable');
			$this->db->select('amount_type_id,date_of_submission,from_id,fund_transfer_type');
			$this->db->from('commission_details');
			$this->db->where('user_id',$user_id);
			$this->db->where('amount_type_id',$category_type);
			$this->db->group_by('date_of_submission');
			$this->db->group_by('from_id');
			$this->db->group_by('fund_transfer_type');
			$this->db->group_by('amount_type_id');

		}
		else
		{
			$this->db->select('from_id,total_amount,amount_type_id,amount_payable,payout_ref_id');
			$this->db->select('date_of_submission,fund_transfer_type,transaction_note,paid_status');
			$this->db->from('commission_details');
			$this->db->where('user_id',$user_id);
			$this->db->where('amount_type_id !=',16);

			if($category_type != "all"){
				$this->db->where('amount_type_id',$category_type);
			}
		}
		$query  = $this->db->get();
		foreach($query->result_array() as $row){
			$row["payout_status"] = "NA";

			$row["from_name"] = $this->getUsername($row['from_id']);
			$row["amount_view"] = $this->currency->format($row['amount_payable']);
			$row["category_name"] = $this->getAmountTypeName($row['amount_type_id']);
			if($row["amount_type_id"] ==14){
				$row["payout_status"] = $this->getPayoutStatus($row['payout_ref_id']);
			}
			
			$details[] =  $row;
		}
		if($category_type == 'all')
		{
			foreach ($details as $key => $part) {
				$sort[$key] = strtotime($part['date_of_submission']);
			}
			array_multisort($sort, SORT_ASC, $details);
		}
		$details['user_total_amount'] = $this->getTotalAmountByQuery($user_id,$category_type);
		return $details;
	}

	public function getTotalAmountByQuery($user_id,$category_type)
	{
		$amount_payable = 0;
		$this->db->select_sum('amount_payable');
		$this->db->from('commission_details');
		$this->db->where('user_id',$user_id);
		if($category_type != "all"){
			$this->db->where('amount_type_id',$category_type);
		}
		$query  = $this->db->get();
		foreach($query->result_array() as $row){
			$amount_payable =  $row["amount_payable"];
		}
		return $amount_payable;
	}
	public function nextPackageDetails($package_id)
	{
		$details = array();
		$this->db->select('*');
		$this->db->from('package_details');
		$this->db->where('package_id >',$package_id);
		$this->db->where('status',"yes");
		$query  = $this->db->get();
		$i = 0;
		foreach($query->result_array() as $row){
			$details[$i]['package_id'] =  $row["package_id"];
			$details[$i]['name'] =  $row["name"];
			$details[$i]['package_value'] =  $row["package_value"];
			$details[$i]['pair_value'] =  $row["pair_value"];
			$i++;
		}
		return $details;
	}

	public function upgradePackage($user_id,$post_package_id,$payment_method="free",$date,$current_pack_id)
	{
		$res=FALSE;
		$this->db->set('package_id',$post_package_id);
		$this->db->where('user_id',$user_id);
		$this->db->limit(1);
		$res  = $this->db->update('login_info');

		$this->db->set('current_package_id',$post_package_id);
		$this->db->set('new_package_id',$current_pack_id);
		$this->db->set('user_id',$user_id);
		$this->db->set('payment_method',$payment_method);
		$this->db->set('date',$date);
		$res  = $this->db->insert('package_upgrade_history');

		return $res;
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

	public function	insertBitcoinPaymentRequest($user_id,$used_amount,$description,$serialize_post,$type,$investment_type)
	{
		$this->db->set('user_id',$user_id);
		$this->db->set('amount',$used_amount);
		$this->db->set('status','pending');
		$this->db->set('type',$type);
		$this->db->set('investment_type',$investment_type);
		$this->db->set('payment_date',date('Y-m-d H:i:s'));
		$this->db->set('description',$description);
		$this->db->set('post_array',$serialize_post);
		$res  = $this->db->insert('bitcoin_payment_requests');
		return $res;
	}

	public function getUserInvestedDate($user_id,$min_investment)
	{
		$date = date('Y-m-d');
		$this->db->select("invest_amount,investing_date");
		$this->db->where("user_id",$user_id);
		$this->db->order_by("investing_date",'ASC'); 
		$res=$this->db->get('investment_details');
		$total_amount= 0;
		foreach ($res->result_array() as $row) {
			$invest_amount = $row['invest_amount'];
			$total_amount = $total_amount+$invest_amount;
			if($total_amount>=$min_investment)
				return $row['investing_date'];
		}
		return $date;
	}
}


