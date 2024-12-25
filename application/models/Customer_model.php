<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends Base_model {

    function __construct() {
        parent::__construct();

    }
    public function addCustomerDetails($details){

        $this->config->load('bcrypt');
        $this->load->library('bcrypt');
        $hashed_password = $this->bcrypt->hash_password( $details['password'] );
        
        $link = 'http://maps.google.com/?q='.$details['location'];
        $this->db->set('customer_username' , $details['customer_username']);
        $this->db->set('name' , $details['name']);
        $this->db->set('password', $hashed_password ); 
        $this->db->set('mobile' , $details['mobile']);
        $this->db->set('address' , $details['address']);
        $this->db->set('location' , $details['location']);
        $this->db->set('map' , $link);
        $this->db->set('email' , $details['email']);
        $this->db->set('created_by' , $details['created_by']);
        $this->db->set('user_type' , $details['user_type']);
        $this->db->set('customer_photo','nophoto.png');
        $this->db->set('date',date('Y-m-d H:i:s'));

        $this->db->set('organization_type' , $details['organization_type']);
        $this->db->set('salesman_id' , $details['salesman_id']);
        $result = $this->db->insert('customer_info');

        return $result;
    }  
    public function updateCustomerDetails($details,$customer_id){
        $link = 'http://maps.google.com/?q='.$details['location'];
        if ($details['password']) {
            $this->config->load('bcrypt');
            $this->load->library('bcrypt');
            $hashed_password = $this->bcrypt->hash_password( $details['password'] );
            $this->db->set('password', $hashed_password ); 
        }
        
        $this->db->set('customer_username' , $details['customer_username']);
        $this->db->set('name' , $details['name']);
        $this->db->set('mobile' , $details['mobile']);
        $this->db->set('address' , $details['address']);
        $this->db->set('location' , $details['location']);
        $this->db->set('map' , $link);
        $this->db->set('salesman_id' , $details['salesman_id']);
        $this->db->set('email' , $details['email']);
        $this->db->where('customer_id' , $customer_id);
        $this->db->set('user_type' , $details['user_type']);

        $result = $this->db->update('customer_info');

        return $result;
    }
    // public function getAllCustomerIdAuto($term) {

    //     $output = [];
    //     $this->db->select('customer_id,customer_username');
    //     $this->db->from('customer_info');
    //     $this->db->where('status', 'active');
    //     $this->db->where("customer_username LIKE '%$term%'");
    //     $this->db->limit(10);

    //     $res = $this->db->get();

    //     foreach($res->result_array() as $row) {
    //         $output[] = ['id'=>$row['customer_id'], 
    //         'text'=>$row['customer_username']];
    //     }

    //     return $output;
    // }
    public function getAllCustomers( $search_arr =[] ) 
    {
        $this->db->select('ci.*')
        ->select('lis.user_name as salesman_name');

        if( $name =  element('name', $search_arr) ){

            $this->db->like('ci.name', $name);
        }

        if( $email =  element('email', $search_arr) ){

            $this->db->like('ci.email', $email);
        }
        if( $mobile =  element('mobile', $search_arr) ){

            $this->db->like('ci.mobile', $mobile);
        }

        if( $user_name =  element('customer_username', $search_arr) ){

            $this->db->where('ci.id', $user_name);
        }  

        if( $created_by =  element('created_by', $search_arr) ){

            $this->db->where('ci.created_by', $created_by);
        }  


        $this->db->from('customer_info ci')
        ->join('login_info lis', 'lis.user_id = ci.salesman_id', 'left')
        ->order_by( 'ci.date', 'DESC' )
        ->where( 'ci.status', 'pending' );
        $query = $this->db->get();
        // print_r($this->db->last_query());
        // die();

        $details = [] ;
        foreach ($query->result_array() as $row) {
            $row['enc_customerid']=$this->encrypt_decrypt('encrypt',$row['id']);
            $row['source_user']=$this->Base_model->getSourceName($row['source_id']);
            $row['country_name']=$this->Base_model->getCountryName($row['country']);
            $details[] = $row;
        }
        return $details;
    } 

    public function getAllCustomersAjax( $search_arr =[],$count = 0) 
    {

        $subadmin=false;
        if (log_user_type()=='supervisor') {
            $subadmin=log_user_id();

        }
        $row = $search_arr['start'];
        $rowperpage = $search_arr['length'];

        $this->db->select('ci.*');
        $this->db->select('sd.source_name');
        $searchValue = $search_arr['search']['value']; 
        if('' != $searchValue) { 
            $where = "(ci.firstname LIKE '%$searchValue%' 
            OR ci.date LIKE '%$searchValue%'
            OR  ci.firstname LIKE '%$searchValue%'
            OR ci.email LIKE '%$searchValue%')"; 
            $this->db->where($where);
        }

        if( $name =  element('name', $search_arr) ){
            $this->db->like('ci.name', $name);
        }


        if( $enquiry =  element('enquiry', $search_arr) ){
            $this->db->like('ci.enquiry_status', $enquiry);
        }

        if( $email =  element('email', $search_arr) ){
            $this->db->like('ci.email', $email);
        }

        if( $mobile =  element('mobile', $search_arr) ){
            $this->db->like('ci.mobile', $mobile);
        }

        if( $user_name =  element('firstname', $search_arr) ){
            $this->db->like('ci.firstname', $user_name);
        }  

        if(element('salesman_id', $search_arr) ){
            $this->db->where('ci.salesman_id', $search_arr['salesman_id']);
        }  

        if( $created_by =  element('created_by', $search_arr) ){
            $this->db->where('ci.created_by', $created_by);
        } 

        if( $source_id =  element('source_id', $search_arr) ){
            $this->db->where('ci.source_id', $source_id);
        } 

        if( $country =  element('country', $search_arr) ){
            $this->db->where('ci.country', $country);
        } 

        if ($subadmin) {
         $this->db->where('lis.sub_id',$subadmin);
     }

     $this->db->from('customer_info ci')
     ->join('login_info lis', 'lis.user_id = ci.salesman_id', 'left')
     ->join('source_details sd', 'ci.source_id = sd.id', 'left')
     ->order_by( 'ci.created_date', 'DESC' )
     ->where( 'ci.status', 'pending' );


     if($count) {
        return $this->db->count_all_results();
    }
    $this->db->limit($rowperpage, $row);
    $query = $this->db->get(); 


    $details = [] ;
    $i=1;
    foreach ($query->result_array() as $row) {

        $row['index'] =$search_arr['start']+$i;
        $row['source_user_name'] =$this->Base_model->getSourceName($row['source_id']);
        $row['fullname'] =$row['firstname'].' '.$row['lastname'];
        $row['enc_customerid']=$this->encrypt_decrypt('encrypt',$row['id']);
        $row['date'] = date('Y-m-d',strtotime($row['date']));
        $details[] = $row;
        $i++;
    }

    return $details;
}

public function getOrderCount()
{
    $this->db->select('*');
    $this->db->from('customer_info');
    $count = $this->db->count_all_results();
    return $count;
}
public function check_exists($field='',$data='')
{
    $count = 0;
    $this->db->select("COUNT($field) as count");
    $this->db->from("customer_info");
    $this->db->where($field, $data);
    $query = $this->db->get();
    foreach ($query->result() AS $row) {
        $count = $row->count;
    }
    return $count;
}

public function getEmailtoCustomerID($email)
{
    $customer_id = 0;
    $this->db->select('customer_id');
    $this->db->from('customer_info');
    $this->db->where('email',$email);
    $get = $this->db->get();
    foreach($get->result() as $row)
    {
        $customer_id = $row->customer_id;
    }
    return $customer_id;
}


public function updateLead($post_arr='')
{

    $date=date('Y-m-d H:i:s');

    $this->db->set('firstname',$post_arr['first_name']);
    $this->db->set('salesman_id',$post_arr['salesman_id']);
    $this->db->set('lastname',$post_arr['last_name']);
    $this->db->set('gender',$post_arr['gender']);
    $this->db->set('email',$post_arr['email']);
    $this->db->set('mobile',$post_arr['mobile']);
    $this->db->set('advance',$post_arr['advance_amount']);

    if (element('total_amount',$post_arr)) {
        $this->db->set('total_amount',$post_arr['total_amount']);

    }
    if (element('due_amount',$post_arr)) {

        $this->db->set('due_amount',$post_arr['due_amount']);
    }

    $this->db->set('date',$post_arr['date']);
    $this->db->set('immigration_status',$post_arr['emmigration']);

    $this->db->set('age',$post_arr['age']);
    $this->db->set('current_job',$post_arr['current_job']);
    $this->db->set('enquiry_status',$post_arr['enquiry_status']);

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

if (element('country',$post_arr)) {

    $this->db->set('country',$post_arr['country']);
}

if (element('dob_certificate',$post_arr)) {

   $this->db->set('dob_certificate',$post_arr['dob_certificate']);
}
$this->db->where('id',$post_arr['id']);


$result = $this->db->update('customer_info');

return $result;

}
}