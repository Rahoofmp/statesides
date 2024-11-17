<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payments_model extends Base_model {

    function __construct() {
        parent::__construct();

    }

    public function getUniqueTransactionId() {
        $date = date('Y-m-d H:i:s');
        $code = $this->getRandStr(9, 9);
        $this->db->set('transaction_id', $code);
        $this->db->set('added_date', $date);
        $this->db->insert('transaction_id');
        return $code;
    }

    public function getRandStr() {
        $key = "";
        $charset = "9876543210";
        $length = 10;
        for ($i = 0; $i < $length; $i++)
            $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];

        $randum_number = $key;
        $this->db->from('transaction_id');
        $this->db->where('transaction_id', $randum_number);
        $count = $this->db->count_all_results();
        if ($count > 0)
            $this->getRandStr();
        else
            return $key;
    }

    public function insertBalAmountDetails($from_user_id, $to_user_id, $trans_amount, $amount_type = '', $transaction_concept = '', $trans_fee = 0, $transaction_id = '') {
        $date = date('Y-m-d H:i:s');

        if ($amount_type != '') {
            $data = array(
                'from_user_id' => $from_user_id,
                'to_user_id' => $to_user_id,
                'amount' => $trans_amount,
                'date' => $date,
                'amount_type' => $amount_type,
                'transaction_concept' => $transaction_concept,
                'trans_fee' => $trans_fee,
                'transaction_id' => $transaction_id
            );
            $query = $this->db->insert('amount_transfer_details', $data);
        } else {
            $data = array(
                'from_user_id' => $from_user_id,
                'to_user_id' => $to_user_id,
                'amount' => $trans_amount,
                'date' => $date,
                'amount_type' => 'user_credit',
                'transaction_concept' => $transaction_concept,
                'trans_fee' => $trans_fee,
                'transaction_id' => $transaction_id
            );
            $query = $this->db->insert('fund_transfer_details', $data);
            $data = array(
                'from_user_id' => $to_user_id,
                'to_user_id' => $from_user_id,
                'amount' => $trans_amount,
                'date' => $date,
                'amount_type' => 'user_debit',
                'transaction_concept' => $transaction_concept,
                'trans_fee' => $trans_fee,
                'transaction_id' => $transaction_id
            );
            $query = $this->db->insert('fund_transfer_details', $data);
        }
    }

    public function addUserBalanceAmount($to_userid, $amount) {
        $this->db->set('wallet', 'ROUND(wallet + ' . $amount . ',2)', FALSE);
        $this->db->where('user_id', $to_userid);
        $query = $this->db->update('user_wallet');
        return $query;
    }

    public function deductUserBalanceAmount($to_userid, $amount) {
        $this->db->set('wallet', 'ROUND(wallet - ' . $amount . ',2)', FALSE);
        $this->db->where('user_id', $to_userid);
        $query = $this->db->update('user_wallet');
        return $query;
    }


    public function getPayoutDetails($user_id="") {
        $payout_details = array();
        $this->db->select('pr.req_id,pr.user_id,pr.requested_date,pr.amount,pr.by_using,pr.transaction_fee,pr.destination,li.user_name');
        $this->db->from('payout_requests AS pr');
        $this->db->join('login_info AS li', 'li.user_id = pr.user_id', 'INNER');
        $this->db->join('user_info AS ud', 'ud.user_id = li.user_id', 'INNER');
        $this->db->where('li.status', 1);
        if($user_id)
            $this->db->where('pr.user_id', $user_id);

        $this->db->where('pr.amount >=', 0);
        $this->db->where('pr.status', "pending");
        $this->db->order_by('pr.requested_date', 'DESC');

        $query = $this->db->get();
        $i = 0;
        foreach ($query->result_array() as $row) {
            $req_id = $row['req_id'];
            $requested_user_id = $row['user_id'];
            $balance_amount = $this->getUserWallet($row['user_id']);
            $requested_amount = $row['amount'];
            $payout_details[$i]['req_id'] = $row['req_id'];
            $payout_details[$i]['user_id'] = $requested_user_id;
            $payout_details[$i]['encrypted'] = $this->Base_model->encrypt_decrypt('encrypt', $requested_user_id);
            $payout_details[$i]['user_name'] = $row['user_name'];
            $payout_details[$i]['full_name'] = $this->Base_model->getFullName($row['user_id']);
            $payout_details[$i]['balance_amount'] = $this->currency->format(($balance_amount));
            $payout_details[$i]['payout_amount'] = $this->currency->format(($requested_amount));
            $payout_details[$i]['transaction_fee'] = $this->currency->format(($row['transaction_fee']));
            $payout_details[$i]['requested_date'] = date('Y/M/d',strtotime($row['requested_date']));
            $payout_details[$i]['by_using'] = $row['by_using'];
            $payout_details[$i]['destination'] = $row['destination'];
            $i++;
        }
        return $payout_details;
    }

    public function getPayoutRequestsbyId($req_id, $status="") {
        $requested =array();
        $this->db->select('amount,user_id,status,transaction_fee,by_using');
        $this->db->where('req_id', $req_id);
        if($status){
            $this->db->where('status', $status);            
        }
        $query = $this->db->get('payout_requests');
        foreach ($query->result_array()AS $row) {
            $requested['amount'] = $row["amount"];
            $requested['user_id'] = $row["user_id"];
            $requested['status'] = $row["status"];
            $requested['transaction_fee'] = $row["transaction_fee"];
            $requested['by_using'] = $row["by_using"];
        }
        return $requested;
    }

    public function updatePayoutReleaseRequest($request_id, $user_id, $payout_release_amount)
    {
        $result = false;
        if ($payout_release_amount > 0) {
            $update_request = false;
            if ($this->isPayoutRequestPending($request_id)) {
                $this->db->set('status', 'released');
                $this->db->set('updated_date', date("Y-m-d H:i:s"));
                $this->db->where('user_id', $user_id);
                $this->db->where('req_id', $request_id);
                $this->db->where('status', 'pending');
                $update_request = $this->db->update('payout_requests');
            }
        }
        return $update_request;
    }

    public function isPayoutRequestPending($request_id) {
        $this->db->where('req_id', $request_id);
        $this->db->where('status', 'pending');
        $count = $this->db->count_all_results('payout_requests');
        return $count;
    }

    public function deletePayoutRequest($del_id, $user_id, $status = 'deleted',$requested_amount) {
        $date = date('Y-m-d H:i:s');
        $this->db->set('status', $status);
        $this->db->set('updated_date', $date);
        $this->db->where('req_id', $del_id);
        $this->db->where("user_id", $user_id);
        $res = $this->db->update('payout_requests');
        if ($res && $requested_amount) {
            $this->addUserBalanceAmount($user_id, $requested_amount);    
        }
        return $res;
    }

    public function getPayoutRequestAmount($del_id) {
        $requested_amount = 0;
        $this->db->select('amount');
        $this->db->where('req_id', $del_id);
        $query = $this->db->get('payout_requests');
        foreach ($query->result_array()AS $row) {
            $amount = $row["amount"];
        }
        return $amount;
    }

    public function getPayoutRequestAmountTransactionFee($del_id) {
        $transaction_fee = 0;
        $this->db->select('transaction_fee');
        $this->db->where('req_id', $del_id);
        $query = $this->db->get('payout_requests');
        foreach ($query->result_array()AS $row) {
            $transaction_fee = $row["transaction_fee"];
        }
        return $transaction_fee;
    }
}