<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Wallet_model extends Base_model {

    function __construct() {
        parent::__construct();

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
			$data[$i]['user_name'] = $this->getUserName($row['user_id']);
			$data[$i]['full_name'] = $this->getUserInfoField('first_name',$row['user_id']).' '.$this->getUserInfoField('second_name',$row['user_id']);
            $data[$i]['by_using'] = $row['by_using'];
            $data[$i]['destination'] = $row['destination'];
			$i++;
			$page_no = $page_no + 1;
		}
		return $data;
	}

}