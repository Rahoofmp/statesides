<?php 
class Calculation_model extends Base_model {

    function __construct() {
        parent::__construct();
        $this->load->model('Signup_model');

    }

    public function calculatePairCommission($placement_id, $position, $joining_date, $total_amount) 
    {
        $max_depth = 10000;
        $upline_ids = $this->getAllUserUpline($placement_id, $max_depth, 1, value_by_key('mlm_plan'), $position);
        foreach ($upline_ids as $index => $users) 
        {
            $user_id = $users['user_id'];
            $user_level = $users['user_level'];
            $position = $users['child_position'];
            $left_carry = $users['left_carry'] ;
            $right_carry = $users['right_carry'];

            if($position == 1){ 
                $left_carry += $total_amount;
            }elseif($position == 2){ 
                $right_carry += $total_amount;
            }

            $pv_updated = $this->updateUserPv($user_id, $total_amount, $position,$joining_date);

        }
        return TRUE;
    }



    public function calculatePairBonusMidnight($user_id, $left_carry, $right_carry, $joining_date,$amount_type_id) 
    {
        $pair_value = value_by_key('pair_value');
        $pair_price = value_by_key('pair_price');
        $package_id = $this->getUserPackageID($user_id);
        $package_prair_price = $this->getPackageBinaryBonus($package_id);
        $pair_ceiling = $this->getPackageBinaryCap($package_id);
        $pair_details = $this->calculatePair($user_id, $pair_value, $left_carry, $right_carry);

        if($pair_details['count'] > 0){
            $pair_amount = $pair_details['count'] *$package_prair_price/100;
            $paired_pv = $pair_details['paired_point'];
            $get_amount = $this->getAchivedBinaryBonus($user_id);

            if($pair_ceiling == 0)
                $pair_ceiling = value_by_key('pair_ceiling');            

            if($get_amount < $pair_ceiling)
            {
                $paired_pv_left = $paired_pv;
                $paired_pv_right = $paired_pv;
                $max_commission_pv = $pair_ceiling*$package_prair_price;
                if($paired_pv >= $max_commission_pv)
                {
                    if($paired_pv == $left_carry)
                    {
                        $paired_pv_left = $paired_pv;
                        $paired_pv_right = $max_commission_pv;
                    }
                    else
                    {
                        $paired_pv_left = $max_commission_pv;
                        $paired_pv_right = $paired_pv;
                    }

                }
                if(!$this->updateUserCarryPv($user_id, $paired_pv_left,$paired_pv_right,$joining_date)){
                    return FLASE;
                }
                $tot_get_amount = $get_amount + $pair_amount;
                if($tot_get_amount > $pair_ceiling)
                {
                    $pair_amount = $pair_ceiling - $get_amount;
                }
                $paid_status = "yes";

                $commission_distributed = $this->insertCommissionDetails($user_id, $pair_amount, 0, $amount_type_id, $joining_date, $user_id, $paired_pv, $left_carry, $right_carry,"","credit",0,$paid_status);

                if(!$commission_distributed)
                {
                    return FALSE;
                }
            }
        }

        return TRUE;
    }
    public function getPackageBinaryBonus($package_id) {

        $binary_bonus = 0;
        $this->db->select("binary_bonus");
        $this->db->where("package_id",$package_id);
        $res=$this->db->get('package_details');
        foreach ($res->result() as $row) {
            $binary_bonus = $row->binary_bonus;
        }
        return $binary_bonus;
    }
    public function getPackageBinaryCap($package_id) {

        $binary_cap = 0;
        $this->db->select("binary_cap");
        $this->db->where("package_id",$package_id);
        $res=$this->db->get('package_details');
        foreach ($res->result() as $row) {
            $binary_cap = $row->binary_cap;
        }
        return $binary_cap;
    }

    public function calculatePair($user_id, $pair_value, $left_carry, $right_carry) 
    {  
        $pair_details['count'] = 0;
        $min_carry = min($left_carry, $right_carry);
        $pair_ceiling = value_by_key('pair_ceiling');
        if($min_carry >= $pair_value && $pair_value && $pair_ceiling){
            $pair_details['count'] = floor($min_carry / $pair_value);
            $pair_details['paired_point'] = $min_carry;
        }
        return $pair_details;
    }


    public function getAllUserUpline($user_id, $depth, $i = 1, $mlm_plan, $position='', $upline_ids = array()) 
    { 
        $this->db->select('father_id');
        $this->db->select('left_carry, right_carry');
        $this->db->select('user_id, status, position');
        $this->db->from('login_info');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();

        foreach ($query->result_array() as $row) 
        {
            $placement_id = $row['father_id'];
            if($row['user_id'])
            {
                $upline_ids[$i]['user_id'] = $row['user_id'];
                $upline_ids[$i]['position'] = $row['position'];
                $upline_ids[$i]['child_position'] = $position;
                $upline_ids[$i]['user_level'] = $i;
                if($mlm_plan == 'Binary'){
                    $upline_ids[$i]['left_carry'] = $row['left_carry'];
                    $upline_ids[$i]['right_carry'] = $row['right_carry'];
                }
                $position = $row['position'];
                $i++;
            }
        }
        if($placement_id && ($depth >= $i || $depth == 0)){
            $upline_ids = $this->getAllUserUpline($placement_id, $depth, $i, $mlm_plan, $position, $upline_ids);
        }
        return $upline_ids;
    }


    public function insertCommissionDetails($user_id, $total_amount, $level, $amount_type_id, $date_of_submission, $from_user_id = '', $package_id = 0, $package_pv = 0, $package_amount = 0 , $transaction_note= "" ,$fund_transfer_type = "credit",$insert_id = 0,$paid_status='yes') {

        $amount_payable = $total_amount ;


        if ($amount_payable) {
            $result = FALSE;
            $sponsor_id = $this->Base_model->getSponsorID($user_id);
            $this->db->set('user_id', $user_id);
            $this->db->set('sponsor_id', $sponsor_id);
            $this->db->set('from_id', $from_user_id);
            $this->db->set('total_amount', $amount_payable);
            $this->db->set('amount_payable', $amount_payable);
            $this->db->set('service_charge', 0);
            $this->db->set('date_of_submission', $date_of_submission);
            $this->db->set('user_level', $level);
            $this->db->set('amount_type_id', $amount_type_id);
            $this->db->set('package_id', $package_id);
            $this->db->set('pair_value', $package_pv);
            $this->db->set('package_value', $package_amount);
            $this->db->set('transaction_note', $transaction_note);
            $this->db->set('fund_transfer_type', $fund_transfer_type);
            $this->db->set('payout_ref_id', $insert_id);
            $this->db->set('paid_status', $paid_status);
            $this->db->set('flush_out_pair', $total_amount);
            $result = $this->db->insert('commission_details');
            if(($result && $paid_status=='yes') || ($result && $amount_type_id == 4))
            {
                if($amount_type_id != 14 && $amount_type_id != 15){

                    $this->Base_model->addUserWalletAmount($user_id, $amount_payable);

                }

            }
            return $result;
        }
        return TRUE;
    }

    public function insertReferalAmount($referal_id, $referal_amount, $from_user_id, $from_level = 0, $date) {
        $result = TRUE;
        if ($referal_id != "") {

            $amount_type_id = $this->Base_model->getAmountTypeIdByString("referral");

            $result = $this->insertCommissionDetails($referal_id, $referal_amount, $from_level, $amount_type_id, $date, $from_user_id);

        }
        return $result;
    }

    public function updateUserPv($user_ids, $pair_value, $position,$date) 
    {     

        $pv_status = "yes";
//history
        if($position == '1'){
            $this->db->set('left_carry', $pair_value);
            $this->db->set('right_carry', 0);
        }elseif ($position == '2') {
            $this->db->set('left_carry', 0);
            $this->db->set('right_carry', $pair_value);
        }
        $this->db->set('user_id', $user_ids);
        $this->db->set('date', $date);
        $this->db->set('type', 'credit');
        $this->db->set('status', $pv_status);
        $pv_updated = $this->db->insert('pair_details');

        if($position == '1'){
            $this->db->set('left_total', 'left_total + ' . $pair_value, FALSE);
            $this->db->set('left_carry', 'left_carry + ' . $pair_value, FALSE);
        }elseif ($position == '2') {
            $this->db->set('right_total', 'right_total + ' . $pair_value, FALSE);
            $this->db->set('right_carry', 'right_carry + ' . $pair_value, FALSE);
        }
        $this->db->where('user_id', $user_ids);
        $this->db->limit(1);
        $pv_updated = $this->db->update('login_info');
        return $pv_updated;
    }

    public function updateUserCarryPv($user_id, $paired_pv,$date) 
    {  
        $this->db->set('left_carry', 'left_carry - ' . $paired_pv, FALSE);
        $this->db->set('right_carry', 'right_carry - ' . $paired_pv, FALSE);
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $carry_updated = $this->db->update('login_info');

//history
        $this->db->set('left_carry', $paired_pv);
        $this->db->set('right_carry', $paired_pv);
        $this->db->set('user_id', $user_id);
        $this->db->set('date', $date);
        $this->db->set('type', 'debit');
        $this->db->set('status', 'paired');
        $pv_updated = $this->db->insert('pair_details');

        return $carry_updated;
    }

    public function getAchivedBinaryBonus($user_id)
    {
        $total_amount = 0;
        $pair_ceiling_type = value_by_key('pair_ceiling_type');
        $amount_type_id = $this->Base_model->getAmountTypeIdByString("binary_commission");
        $this->db->select_sum('total_amount');
        $this->db->where('amount_type_id',$amount_type_id);
        $this->db->where('user_id',$user_id);
        if($pair_ceiling_type == "daily")
        {
            $date1 = date('Y-m-d 00:00:00');
            $date2 = date('Y-m-d 23:59:59');
            $where = " date_of_submission between '$date1' and '$date2' ";
            $this->db->where($where);
        }
        $query = $this->db->get('commission_details');
        foreach ($query->result() as $row) 
        {
            $total_amount = $row->total_amount;
        }
        return $total_amount;
    }


    function updateCumulativePoints($new_user_id,$point) 
    {

        $mlm_plan = value_by_key('mlm_plan');
        $father_id = $this->getFatherID($new_user_id);
        $upline_user_arr = $this->getAllUserUpline($father_id,  10000, 1, $mlm_plan);

        $total_len = count($upline_user_arr);
        $this->updatePersonalCumulativePoints($new_user_id, $point);

        for ($i = 1; $i <= $total_len; $i++) 
        {
            $user_id = $upline_user_arr[$i]["user_id"];
            $this->updateUserCumulativePoints($user_id, $point);
        }
        return TRUE;
    }

    public function updatePersonalCumulativePoints($user_id, $point) {
        $this->db->set('personal_points', 'personal_points + ' . $point, FALSE);
        $this->db->where('user_id', $user_id);
        $query = $this->db->update('login_info');

        return $query;
    }
    public function updateUserCumulativePoints($user_id, $point) {

        $this->db->set('cumulative_points', 'cumulative_points + ' . $point, FALSE);
        $this->db->where('user_id', $user_id);
        $query = $this->db->update('login_info');

        return $query;
    }


    public function insertInToUpgradeWalletDetails($user_id, $total_amount, $amount_type_id, $date_of_submission, $from_user = '', $transaction_note= "" ,$fund_transfer_type = "credit") {

        if ($total_amount) {
            $result = FALSE;
            $this->db->set('user_id', $user_id);
            $this->db->set('from_id', $from_user);
            $this->db->set('total_amount', $total_amount);
            $this->db->set('date_of_submission', $date_of_submission);
            $this->db->set('amount_type_id', $amount_type_id);
            $this->db->set('transaction_note', $transaction_note);
            $this->db->set('fund_transfer_type', $fund_transfer_type);

            $result = $this->db->insert('upgrade_wallet');
            if ($result) {
                $this->addUpgradeWalletAmount($user_id, $total_amount);
            }

            return $result;
        }
        return TRUE;
    }

    public function addUpgradeWalletAmount($to_userid, $amount) {
        $this->db->set('upgrade_wallet', 'ROUND(upgrade_wallet + ' . $amount. ',2)', FALSE);
        $this->db->where('user_id', $to_userid);
        $query = $this->db->update('user_wallet');
        return $query;
    }
    public function addCutdownWalletAmount($to_userid, $amount) {
        $this->db->set('cut_down', 'ROUND(cut_down + ' . $amount. ',2)', FALSE);
        $this->db->where('user_id', $to_userid);
        $query = $this->db->update('user_wallet');
        return $query;
    }

}