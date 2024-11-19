<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Member_model extends Base_model {

    function __construct() {
        parent::__construct();

    }

    public function updateUserProfile($user_details, $user_id) {

        $this->db->set('first_name', $user_details['first_name']);

        $this->db->set('mobile', $user_details['mobile']);
        if(array_key_exists('facebook', $user_details)){
            $this->db->set('facebook', $user_details['facebook']);
        }
        if(array_key_exists('email', $user_details)){

            $this->db->set('email', $user_details['email']);
        } 
        if(array_key_exists('gplus', $user_details)){
            $this->db->set('gplus', $user_details['gplus']);
        }
        if(array_key_exists('twitter', $user_details)){
            $this->db->set('twitter', $user_details['twitter']);
        }
        if(array_key_exists('instagram', $user_details)){
            $this->db->set('instagram', $user_details['instagram']);
        }
        if(array_key_exists('linkedin', $user_details)){
            $this->db->set('linkedin', $user_details['linkedin']);
        }
        if($user_details['file_name'])
            $this->db->set('user_photo', $user_details['file_name']);
        $this->db->where('user_id',$user_id);
        $result = $this->db->update('user_info'); 
        return $result;    
    }

    public function updateUserProfileImageApi($user_details, $user_id) {


        if($user_details['file_name'])
            $this->db->set('user_photo', $user_details['file_name']);
        $this->db->where('user_id',$user_id);
        $result = $this->db->update('user_info'); 
        return $result;    
    }

    public function updateUserName($new_user_name, $user_id ,$old_user_name) {
        $this->db->set('user_name', $new_user_name);
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $this->db->update('login_info');

        $res2 = false;
        if($this->db->affected_rows()){

            $this->db->set('old_username', $old_user_name);
            $this->db->set('new_username', $new_user_name);
            $this->db->set('user_id', $user_id);
            $this->db->set('modified_date', date("Y-m-d H:i:s"));
            $res2 = $this->db->insert('username_change_history');
        }

        return $res2;
    }

    public function updatePassword($new_pwd, $user_id) {
        $this->db->set('password', $new_pwd);
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $res = $this->db->update('login_info');
        return $res;
    }

    public function updateSecurityPin($new_pin, $user_id) {
        $this->db->set('secure_pin', $new_pin);
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $this->db->update('login_info');
        $this->db->last_query();
        return $this->db->affected_rows();
    }

    public function getAllMembers( $search_arr =[] ) 
    {
        $this->db->select('li.user_name, first_name,mobile, email, status, joining_date, li.user_id,li.user_type');

        if( $first_name =  element('first_name', $search_arr) ){

            $this->db->like('ui.first_name', $first_name);
        }

        if( $email =  element('email', $search_arr) ){

            $this->db->like('ui.email', $email);
        }
        if( $mobile =  element('mobile', $search_arr) ){

            $this->db->like('ui.mobile', $mobile);
        }

        if( $user_name =  element('user_name', $search_arr) ){

            $this->db->like('li.user_name', $user_name);
        }  
        if( $user_type =  element('user_type', $search_arr) ){

            $this->db->where('li.user_type', $user_type);
        }  

        $this->db->from('login_info li')
        ->join('user_info ui', 'li.user_id = ui.user_id')
        ->order_by( 'joining_date', 'DESC' )
        ->where( 'status', '1' );
        $query = $this->db->get();
        
        $details = [] ;
        foreach ($query->result_array() as $row) {

            switch ( $row['status'] ) {
                case '0':
                $row['status']  = lang("inactive");  
                break;
                case 'block':
                $row['status']  = lang("blocked");  
                break;
                default:
                $row['status']  = lang("active");
                break;
            };

            $details[] = $row;

        }
        return $details;
    }


    public function updatePayeerStatus($ref_id , $status= ''){
        $date = date('Y-m-d H:i:s');
        $this->db->set('status' , $status);
        $this->db->set('date' , $date);
        $this->db->where('id' , $ref_id);
        $result = $this->db->update('payeer_payment_details');
        return $result;

    }

    public function checkOrderAlreadyExist($ref_payment_id){

        $this->db->from('payeer_payment_details');
        $this->db->where('id' , $ref_payment_id);
        $this->db->where('status' , 'SUCESS_RENEWAL');
        $count = $this->db->count_all_results();
        return  $count; 

    }

    public function upgradeUserPackage($user_id , $new_package_id){

        $this->db->set('package_id',$new_package_id);
        $this->db->where('user_id',$user_id);
        $this->db->limit(1);
        $result =  $this->db->update('login_info');
        return $result;
    }

    public function insertUpgradeHistory($user_id , $package_id ,$new_package_id , $payment_method){

        $admin_user_id = $this->Base_model->getAdminId();
        $sponsor_id = $this->Base_model->getSponsorID($user_id);
        $date = date('Y-m-d H:i:s');
        $this->db->set('user_id' , $user_id);
        $this->db->set('new_package' , $new_package_id);
        $this->db->set('method' , $payment_method);
        $this->db->set('date' , $date);
        $result = $this->db->insert('package_upgrade_history');

        $board_sponsor_id = $this->getBoardSponsorId($user_id,$new_package_id);
        

        if($board_sponsor_id == $sponsor_id)
            $sponsor_order_id = $this->getBoardAutoId($board_sponsor_id,$new_package_id,'DESC');
        else
            $sponsor_order_id = $this->getBoardAutoId($board_sponsor_id,$new_package_id,'DESC');


        $placement_details = $this->getPlacementBoard($board_sponsor_id,$new_package_id,$sponsor_order_id);

        if(!$placement_details)
        {
            $this->Member_model->rollback();
            $this->redirect("Invalid board placement,Please try again","member/account-upgrade", FALSE);
        }
        $res = $this->insertBoardDetails($user_id,$placement_details,$date,$new_package_id);


        
        $width_ceiling = value_by_key('width_ceiling');
        if($placement_details['position'] == $width_ceiling)
        {

            $refill = $this->autoBoardRefoll($placement_details,$new_package_id,$admin_user_id,$board_sponsor_id,$date,$user_id );
            

        }

        return $result;

    }


    public function autoBoardRefoll($placement_details,$new_package_id,$admin_user_id,$board_sponsor_id,$date,$user_id)
    {


        $upline = $placement_details['id'];
        $upline_order_id = $placement_details['order_id'];
        $user_type =  $this->getUserType($upline);
        if($user_type == 'user')
        {
            $reentry_status_father_id = $this->getBoardReEntryStatus($upline,$upline_order_id,$new_package_id,$admin_user_id);
            if($reentry_status_father_id)
            {


                $board_reentry_id = $reentry_status_father_id['father_id'];

                $ceiling = $this->getPackageCeiling($new_package_id);
                $split_count = $this->getUserDailySplitCount($board_reentry_id,$new_package_id);
                $total_amount_bonus = $this->Signup_model->getPackageAmount($new_package_id);

                if($split_count <= $ceiling)
                {
                    $sp_count = $this->getSponsorCount($board_reentry_id);
                    if($sp_count >= 2)
                        $bonus_percentage = 100;
                    else
                        $bonus_percentage = 50;

                    $total_return = $this->getPackageBonusReturn($new_package_id);

                    $total_bonus = $total_return*$bonus_percentage/100;
                    $amount_type_id = $this->getAmountTypeIdByString('board');
                    $this->Calculation_model->insertCommissionDetails($board_reentry_id, $total_bonus, 0, $amount_type_id, $date, $user_id, $new_package_id, $total_return, $sp_count);

                }
                else
                {
                    $total_bonus = $total_amount_bonus;
                    $amount_type_id = $this->getAmountTypeIdByString('board');
                    $this->Calculation_model->insertCommissionDetails($board_reentry_id, $total_bonus, 0, $amount_type_id, $date, $user_id, $new_package_id, $split_count, $ceiling);
                }



                $board_sponsor_id = $this->getBoardSponsorId($board_reentry_id,$new_package_id);
                $sponsor_order_id = $this->getBoardAutoId($board_sponsor_id,$new_package_id,'DESC');
                $board_father_id = $reentry_status_father_id['father_id'];
                $fath_order_id = $reentry_status_father_id['father_auto_id'];



                $placement_details = $this->getPlacementBoard($board_sponsor_id,$new_package_id,$sponsor_order_id);

                if(!$placement_details)
                {
                    $this->Member_model->rollback();
                    $this->redirect("Invalid board placement,Please try again","member/account-upgrade", FALSE);
                }
                $res = $this->insertBoardDetails($board_reentry_id,$placement_details,$date,$new_package_id);


                $this->db->set('user_id' , $board_reentry_id);
                $this->db->set('new_package' , $new_package_id);
                $this->db->set('method' , 'auto');
                $this->db->set('date' , $date);
                $result = $this->db->insert('package_upgrade_history');



                $amount_type_id = $this->getAmountTypeIdByString('re_entry');
                $this->Calculation_model->insertCommissionDetails($board_reentry_id, -$total_amount_bonus, 0, $amount_type_id, $date, $board_reentry_id, $new_package_id, $board_reentry_id, 0,'','debit');


                $width_ceiling = value_by_key('width_ceiling');
                if($placement_details['position'] == $width_ceiling)
                {

                    $refill = $this->autoBoardRefoll($placement_details,$new_package_id,$admin_user_id,$board_sponsor_id,$date,$user_id );

                }


            }
        }


        return TRUE;
    }


    public function getUserDailySplitCount($user_id,$package_id) 
    {
        $admin_id = $this->getAdminId();
        $sponsor_id = $this->getSponsorID($user_id);
        if($sponsor_id == $admin_id)
            return 0;
        $cnt = 0;
        $this->db->select("count(id) as cnt");
        $this->db->where('new_package', $package_id);
        $this->db->where('user_id', $user_id);
        //$this->db->where('method', 'auto');
        $this->db->like('date', date('Y-m-d'));
        $this->db->from("package_upgrade_history");
        $query = $this->db->get();
        foreach ($query->result() AS $row) {
            $cnt = $row->cnt;
        }
        return $cnt;
    }
    public function getPackageBonusReturn($package_id) 
    {
        $total_return = 0;
        $this->db->select("total_return");
        $this->db->where('package_id', $package_id);
        $this->db->from("package_details");
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() AS $row) {
            $total_return = $row->total_return;
        }
        return $total_return;
    }

    public function getPackageCeiling($package_id) 
    {
        $ceiling = 0;
        $this->db->select("ceiling");
        $this->db->where('package_id', $package_id);
        $this->db->from("package_details");
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() AS $row) {
            $ceiling = $row->ceiling;
        }
        return $ceiling;
    }

    public function getBoardReEntryStatus($upline,$upline_order_id,$board_no,$admin_id)
    {
        $downlineuser_cnt = 0;
        $father_id = 0;
        $details = array();
        $this->db->select("father_auto_id");
        $this->db->select("father_id");
        $this->db->from("board$board_no");
        $this->db->where('id', $upline_order_id);
        $this->db->limit(1);
        $res = $this->db->get();
        foreach ($res->result_array() AS $row) {
            $father_auto_id = $row['father_auto_id'];
            $father_id = $row['father_id'];

            $details['father_auto_id'] =  $row['father_auto_id'];
            $details['father_id'] =  $row['father_id'];
        }
        //if($father_id  != $admin_id)
        //{
        $this->db->select("user_id");
        $this->db->select("id");
        $this->db->from("board$board_no");
        $this->db->where('father_id', $father_id);
        // $this->db->where('father_auto_id', $father_auto_id);
        $res1 = $this->db->get();
        foreach ($res1->result_array() AS $row1) {
            $downlineuser_cnt++;

            $this->db->select("user_id");
            $this->db->select("id");
            $this->db->from("board$board_no");
            $this->db->where('father_id', $row1['user_id']);
            $this->db->where('father_auto_id',  $row1['id']);
            $res2 = $this->db->get();
            foreach ($res2->result_array() AS $row2) {
                $downlineuser_cnt++;

            }
        }
        //}
        if($father_id  == $admin_id)
            $width_ceiling = 10000;
        else
            $width_ceiling = value_by_key('width_ceiling');
        $max_cnt = $width_ceiling+($width_ceiling*$width_ceiling);
        if($downlineuser_cnt == $max_cnt)
            return $details;
        else
            return FALSE;
    }


    public function getPlacementBoard($sponsor_id,$board_no=1,$order_id=0) {
        $user["0"]['user_id'] = $sponsor_id;
        $user["0"]['order_id'] = $order_id;
        $sponser_arr = $this->checkPosition($user,$board_no);
        return $sponser_arr;
    }

    public function checkPosition($downlineuser,$board_no) {

        $p = 0;
        $child_arr = array();
        for ($i = 0; $i < count($downlineuser); $i++) {
            $sponsor_id = $downlineuser["$i"]['user_id'];
            $order_id = $downlineuser["$i"]['order_id'];
            $this->db->select("id");
            $this->db->select("user_id");
            $this->db->select("position");
            $this->db->from("board$board_no");
            $this->db->where('father_id', $sponsor_id);
            $this->db->where('father_auto_id', $order_id);
            $this->db->order_by('id', 'ASC');
            $res = $this->db->get();
            $row_count = $res->num_rows();
            if ($row_count > 0) {
                foreach ($res->result_array() as $row) {

                    $admin_id = log_user_id();
                    if($sponsor_id == $admin_id)
                        $width_ceiling = 10000;
                    else
                        $width_ceiling = value_by_key('width_ceiling');

                    if ($row_count < $width_ceiling) {
                        $sponsor['id'] = $sponsor_id;
                        $sponsor['position'] = $row_count + 1;
                        $order_id = $sponsor['order_id'] = $order_id;
                        return $sponsor;
                    }
                    else {
                        $child_arr[$p]['order_id'] = $row["id"];
                        $child_arr[$p]['user_id'] = $row["user_id"];
                        $p++;
                    }
                }
            } else {
               // die();
                $sponsor['id'] = $sponsor_id;
                $sponsor['position'] = 1;
                $sponsor['order_id'] = $this->getBoardAutoId($sponsor_id,$board_no,'DESC');
                return $sponsor;
            }
        }

        if (count($child_arr) > 0) {
            $position = $this->checkPosition($child_arr,$board_no);
            return $position;
        }
    }

    public function insertBoardDetails($user_id,$placement_details,$date,$board_no=1)
    {
        $entry_no = $this->getEntryCount($user_id,$board_no);
        $entry_no = $entry_no+1;

        $this->db->set('entry_no',$entry_no);
        $this->db->set('user_id',$user_id);
        $this->db->set('father_id',$placement_details['id']);
        $this->db->set('position',$placement_details['position']);
        $this->db->set('father_auto_id',$placement_details['order_id']);
        $this->db->set('entry_date',$date);
        $this->db->insert("board$board_no");
    }


    public function getEntryCount($user_id,$board_no) {
        $cnt = 0;
        $this->db->select("count(user_id) as cnt");
        $this->db->where("user_id",$user_id);
        $this->db->from("board$board_no");

        $query = $this->db->get();

        foreach ($query->result() AS $row) {
            $cnt = $row->cnt;
        }
        return $cnt;

    }

    public function getBoardSponsorId($user_id,$board_no) {

        $sponsor_id = $this->getSponsorID($user_id);
        if(!$sponsor_id)
            return 0;
        $this->db->select("user_id");
        $this->db->from("board$board_no");
        $this->db->where('user_id', $sponsor_id);
        $res = $this->db->get();
        $row_count = $res->num_rows();
        if ($row_count > 0) {
            return $sponsor_id;
        } else {
            return $this->getBoardSponsorId($sponsor_id,$board_no);
        }

    }

    public function getBoardUplineId($user_id,$board_no,$order_id=0) {
        $array = array();
        $this->db->select("father_auto_id as order_id");
        $this->db->select("user_id");
        $this->db->from("board$board_no");
        $this->db->where('id', $order_id);

        $query = $this->db->get();

        foreach ($query->result_array() AS $row) {
            $array = $row;
        }
        return $array;

    }

    public function reNewPackage($user_id){
        $this->db->set('double_status','no');
        $this->db->where('user_id',$user_id);
        $result = $this->db->update('commission_details');
        return $result;
    } 


    public function insertRenewalHistory($user_id , $package_id){
        $date = date('Y-m-d H:i:s');
        $this->db->set('user_id',$user_id);
        $this->db->set('package_id',$package_id);
        $this->db->set('date',$date);
        $result = $this->db->insert('renewal_history');
        return $result;
    }

    public function getPackagePairValue($package_id) 
    {
        $pair_value = 0;
        $this->db->select("pair_value");
        $this->db->where('package_id', $package_id);
        $this->db->from("package_details");
        $query = $this->db->get();
        foreach ($query->result() AS $row) {
            $pair_value = $row->pair_value;
        }
        return $pair_value;
    }
    public function getBoardAutoId($user_id,$board_no,$order_by='ASC') 
    {
        $id = 0;
        $this->db->select("id");
        $this->db->from("board$board_no");
        $this->db->where('user_id', $user_id);
        $this->db->order_by('id', $order_by);
        $this->db->limit(1);

        $res = $this->db->get();
        foreach ($res->result() AS $row) {
            $id = $row->id;
        }
        return $id;
    }



    public function insertpaymentDetails($custName,$custEmail,$cardNumber,$cardCVC,$cardExpMonth,$cardExpYear,$itemName,$itemNumber,$itemPrice,$paidCurrency,$balanceTransaction,$paymentStatus,$paymentDate){


        $this->db->set('cust_name',$custName);
        $this->db->set('cust_email',$custEmail);
        $this->db->set('card_number',$this->Base_model->encrypt_decrypt('encrypt',$cardNumber));
        $this->db->set('card_cvc',$this->Base_model->encrypt_decrypt('encrypt',$cardCVC));
        $this->db->set('card_exp_month',$this->Base_model->encrypt_decrypt('encrypt',$cardExpMonth));
        $this->db->set('card_exp_year',$this->Base_model->encrypt_decrypt('encrypt',$cardExpYear));
        $this->db->set('item_name',$itemName);
        $this->db->set('item_number',$itemNumber);
        $this->db->set('item_price',$itemPrice);
        $this->db->set('item_price_currency',$paidCurrency);
        $this->db->set('paid_amount',$balanceTransaction);
        $this->db->set('paid_amount_currency',$paidCurrency);
        $this->db->set('payment_status',$paymentStatus);
        $this->db->set('created',$paymentDate);
        $this->db->set('modified',$paymentDate);
        $this->db->set('user_id',log_user_id());
        $this->db->insert('strip_transactions');
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function getUserAtivePackages($user_id) {

        $details = array();
        $this->db->select('new_package');
        $this->db->from('package_upgrade_history');
        if($user_id){
            $this->db->where('user_id', $user_id);
        }
        $res = $this->db->get();

        foreach ($res->result_array() as $key => $row) {

            $details[] = $row['new_package'];
        }

        return $details;
    }

    public function insertKyc($post,$user_id)
    {
        $date=date('Y-m-d');
        $this->db->set('kyc',$post['image']);
        $this->db->set('date',$date);
        $this->db->set('user_id',$user_id);
        $this->db->set('status','pending');
        $insert= $this->db->insert('kyc_details');
       // echo $this->db->last_query(); die();
        return $insert;
    }

    public function getKycDetails($user_id='')
    {
        $details=[];
        $this->db->select('*');
        $this->db->where('status','pending');
        if($user_id)
            $this->db->where('user_id',$user_id);
        $res=$this->db->get('kyc_details');
        foreach($res->result_array() as $row)
        {
            $row['user_name'] = $this->Base_model->getUserName($row['user_id']);
            $details[]=$row;
        }
        return $details;
    }
    public function checkKYCExist($user_id){

        $count = 0;
        $this->db->from('kyc_details');
        $this->db->where('user_id',$user_id);

        $count = $this->db->count_all_results();
        return $count;
    }
    public function updateuserInfo($kyc,$user_id)
    {
        $this->db->set('aadhaar',$kyc['kyc']);
        $this->db->where('user_id',$user_id);
        $res=$this->db->update('user_info');

        return $res;
    }
    public function updateLoginInfo($status,$user_id)
    {
        $this->db->set('kyc_status',$status);
        $this->db->where('user_id',$user_id);
        $res=$this->db->update('login_info');

        return $res;
    }
    public function updateLoginInfoDepartment($user_id,$department_id)
    {
        $this->db->set('department_id',$department_id);
        $this->db->where('user_id',$user_id);
        $res=$this->db->update('login_info');

        return $res;
    }

    function updateKYCfiles($post_arr,$user_id) 
    {
        $date = date('Y-m-d H:i:s');
        $this->db->set('update_date',$date);
        if($post_arr['image'])

            $this->db->set('kyc',$post_arr['image']);  

        $this->db->set('status','pending');
        $this->db->where('user_id', $user_id);
        $res = $this->db->update('kyc_details');
        return $res;
    } 
    public function changeKYCVerificationStatus($post,$status)
    {

        $date=date('Y-m-d H:i:s');
        $this->db->set('status',$status);
        $this->db->set('reason',$post['reason']);
        $this->db->set('update_date',$date);
        $this->db->where('id',$post['kyc_id']);
        $this->db->limit(1);
        $result = $this->db->update('kyc_details');
        return $result;
    }

    public function getPackage($sponsor_id)
    {
        $details=[];
        $this->db->select('new_package');
        $this->db->from('package_upgrade_history');
        $this->db->where('user_id',$sponsor_id);
        $res=$this->db->get();
        
        foreach($res->result_array() as $row)
        {

            $details[]=$row['new_package'];
        }
        return $details;

    }
    
    public function getVatDetails($id='')
    {
        $details =[];
        $this->db->select('*');
        if($id)
        {
            $this->db->where('id',$id);
        }
        $this->db->where('status',1);
        $this->db->from('vat');
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

    public function updateCustomerProfile($post_arr,$customer_id)
    {
        $this->db->set('name',$post_arr['customer_name']);
        $this->db->set('email',$post_arr['email']);
        $this->db->set('mobile',$post_arr['mobile']);
        $this->db->set('address',$post_arr['address']);
        if($post_arr['file_name'])
        {
            $this->db->set('customer_photo',$post_arr['file_name']);
        }
        $this->db->where('customer_id',$customer_id);
        $update = $this->db->update('customer_info');
        return $update;
    }

    public function updateCustomerPassword($new_pwd, $customer_id) {
        $this->db->set('password', $new_pwd);
        $this->db->where('customer_id', $customer_id);
        $this->db->limit(1);
        $res = $this->db->update('customer_info');
        return $res;
    }
    public function addSupplierDetails($details){


        $this->db->set('user_name' , $details['user_name']);
        $this->db->set('name' , $details['name']);
        $this->db->set('mobile' , $details['mobile']);
        $this->db->set('address' , $details['address']);
        $this->db->set('email' , $details['email']);
        $this->db->set('contact_person' , $details['contact_person']);
        $this->db->set('date',date('Y-m-d H:i:s'));

        $result = $this->db->insert('supplier_info');

        return $result;
    } 
    public function insertVat($post_arr){
        $date =date('Y-m-d');
        $this->db->set('name',$post_arr['name']);
        $this->db->set('value',$post_arr['value']);
        $this->db->set('date',$date);
        $result = $this->db->insert('vat');
        return $result;


    }
    public function getAllSuppliersAjax($search_arr =[],$count =''){
        $row = $search_arr['start'];
        $rowperpage = $search_arr['length'];
        $this->db->select('*');
        $searchValue = $search_arr['search']['value']; 
        if(''!= $searchValue) {
            $where = "(name LIKE '%$searchValue%'
            OR user_name LIKE '%$searchValue%' )";
            $this->db->where($where);
        }
        if( $user_name =  element('user_name', $search_arr) ){
            $this->db->like('user_name', $user_name);
        }
        if( $name =  element('name', $search_arr) ){
            $this->db->like('name', $name);
        }
        if( $email =  element('email', $search_arr) ){
            $this->db->like('email', $email);
        }
        if( $mobile =  element('mobile', $search_arr) ){

            $this->db->like('mobile', $mobile);
        } 
        if( $mobile =  element('mobile', $search_arr) ){

            $this->db->like('mobile', $mobile);
        }
        $this->db->from('supplier_info')
        ->order_by( 'date', 'DESC' )
        ->where( 'status', 1 );
        if($count) {
            return $this->db->count_all_results();
        }
        $this->db->limit($rowperpage, $row);
        $query = $this->db->get();
        $details = [] ;
        $i=1;
        foreach ($query->result_array() as $row) {
            $row['index'] =$search_arr['start']+$i;
            $row['enc_user_name'] = $this->Base_model->encrypt_decrypt('encrypt',$row['user_name']);
            $details[] = $row;
            $i++;
        }
        return $details;
    } 
    public function getAllSuppliers($search_arr =[]){

        $this->db->select('*');
        if( $user_name =  element('user_name', $search_arr) ){
            $this->db->like('user_name', $user_name);
        }
        if( $name =  element('name', $search_arr) ){
            $this->db->like('name', $name);
        }
        if( $email =  element('email', $search_arr) ){
            $this->db->like('email', $email);
        }
        if( $mobile =  element('mobile', $search_arr) ){

            $this->db->like('mobile', $mobile);
        } 
        if( $mobile =  element('mobile', $search_arr) ){

            $this->db->like('mobile', $mobile);
        }
        $this->db->from('supplier_info')
        ->order_by( 'date', 'DESC' )
        ->where( 'status', 1 );

        $query = $this->db->get();
        $details = [] ;

        foreach ($query->result_array() as $row) {
            $row['enc_user_name'] = $this->Base_model->encrypt_decrypt('encrypt',$row['user_name']);
            if(element('user_name', $search_arr) ){
                return $row;
            }
            $details[] = $row;
        }
        return $details;
    } 
    public function getCount()
    {
        $this->db->select('*');
        $this->db->from('supplier_info');
        $count = $this->db->count_all_results();
        return $count;
    }
    public function updateSupplierDetails($details, $user_name)
    {
        $this->db->set('name' , $details['name']);
        $this->db->set('mobile' , $details['mobile']);
        $this->db->set('address' , $details['address']);
        $this->db->set('contact_person' , $details['contact_person']);
        $this->db->set('email' , $details['email']);
        $this->db->set('user_name' , $details['user_name']);
        $this->db->where('user_name' , $user_name);

        $result = $this->db->update('supplier_info');

        return $result;

    }
    public function updateVat($post_arr='',$id='')
    {
        if($post_arr)
        {
            $this->db->set('name',$post_arr['name']);
            $this->db->set('value',$post_arr['value']);
            $this->db->where('id',$post_arr['id']);

        }
        if($id)
        {
            $this->db->set('status',0);
            $this->db->where('id',$id);
        }
        
        $result = $this->db->update('vat');
        return $result;
    }
//     public function EmailDetails($user_name)
//     {
//      $details='';
//      $this->db->select('email');
//      $this->db->where('user_name' , $user_name);
//      $this->db->from('supplier_info');
//      $res=$this->db->get();
//      foreach($res->result_array() as $row)
//      {
//         $details=$row['email'];
//     }
//     return $details;

// }

    public function EmailDetails($user_name)
    {
       $details='';
       $this->db->select('email');
       $this->db->where('user_name' , $user_name);
       $this->db->from('supplier_info');
       $this->db->where("product_name LIKE '%{$filter_params['search_products']}%'");

       $res=$this->db->get();
       foreach($res->result_array() as $row)
       {
        $details=$row['email'];
    }
    return $details;

}

public function getcurrentreminders($user_id)
{
   $details=array();
   $this->db->select('*');
   $this->db->where('user_id' , $user_id);
   $this->db->where('status' , 'pending');
   $this->db->from('reminders');
   $this->db->order_by('date ASC');
   $this->db->limit('10');
   $res=$this->db->get();
   foreach($res->result_array() as $row)
   {
     $details[]=$row;
 }
 return $details;

}

public function getTodayreminders($user_id,$date)
{
   $details=array();
   $this->db->select('*');
   $this->db->where('user_id' , $user_id);
   $this->db->where('date' , $date);
   $this->db->where('status' , 'pending');
   $this->db->from('reminders');
   $res=$this->db->get();
   foreach($res->result_array() as $row)
   {
     $details=$row;
 }
 return $details;

}

public function createReminder($post_arr){

    $date = date('Y-m-d H:i:s');
    $this->db->set('user_id' , $post_arr['user_id']);
    $this->db->set('message' , $post_arr['message']);
    $this->db->set('date' , $post_arr['date']);
    $this->db->set('created_date' , $date);
    $res=$this->db->insert('reminders');
    return $res;
}


}