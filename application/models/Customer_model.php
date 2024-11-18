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

            $this->db->where('ci.customer_id', $user_name);
        }  

        if( $created_by =  element('created_by', $search_arr) ){

            $this->db->where('ci.created_by', $created_by);
        }  


        $this->db->from('customer_info ci')
        ->join('login_info lis', 'lis.user_id = ci.salesman_id', 'left')
        ->order_by( 'ci.date', 'DESC' )
        ->where( 'ci.status', 'active' );
        $query = $this->db->get();
      
        $details = [] ;
        foreach ($query->result_array() as $row) {
            $row['enc_customerid']=$this->encrypt_decrypt('encrypt',$row['customer_id']);
            $details[] = $row;
        }
        return $details;
    } 

    public function getAllCustomersAjax( $search_arr =[],$count = 0) 
    {
        $row = $search_arr['start'];
        $rowperpage = $search_arr['length'];

        $this->db->select('ci.*');
        $searchValue = $search_arr['search']['value']; 
        if('' != $searchValue) { 
            $where = "(ci.name LIKE '%$searchValue%' 
            OR ci.date LIKE '%$searchValue%'
            OR  ci.customer_username LIKE '%$searchValue%'
            OR ci.email LIKE '%$searchValue%')"; 
            $this->db->where($where);
        }

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
            $this->db->like('ci.customer_id', $user_name);
        }  

        if(element('salesman_id', $search_arr) ){
            $this->db->where('ci.salesman_id', $search_arr['salesman_id']);
        }  

        if( $created_by =  element('created_by', $search_arr) ){
            $this->db->where('ci.created_by', $created_by);
        } 

        $this->db->from('customer_info ci')
        ->join('login_info lis', 'lis.user_id = ci.salesman_id', 'left')
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
}