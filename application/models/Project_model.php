<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Project_model extends Base_model {

    function __construct() {
        parent::__construct();

    }

    public function insertMarketDetails($details , $market_image){

        $date = date('Y-m-d H:i:s');
        $this->db->set('pair' , $details['pair']);
        $this->db->set('percentual' , $details['percentual']);
        $this->db->set('sort_order' , $details['sort_order']);
        $this->db->set('bot' , $details['bot']);
        $this->db->set('date' , $details['date']);
        $this->db->set('exchange' , $market_image);
        $this->db->set('added_date' , $date);
        $this->db->set('updated_date' , $date);
        $result = $this->db->insert('market_details');
        return $result;
    } 
    public function insertProjectDetails($details){

        $link = 'http://maps.google.com/?q='.$details['location'];
        $this->db->set('user_id' , $details['user_id']);
        $this->db->set('project_name' , $details['project_name']);
        $this->db->set('customer_name' , $details['customer_name']);
        // $this->db->set('mobile' , $details['mobile']);
        $this->db->set('location' , $details['location']);
        $this->db->set('map' , $link);
        $this->db->set('email' , $details['email']);
        $this->db->set('estimated_cost' , $details['estimated_cost']);
        $this->db->set('estimated_value' , $details['estimated_value']);
        $this->db->set('estimated_duration' , $details['estimated_duration']);
        $this->db->set('status' , $details['status']);
        $this->db->set('start_date' , $details['start_date']);
        $this->db->set('end_date' , $details['end_date']);
        $this->db->set('date',date('Y-m-d H:i:s'));

        $result = $this->db->insert('project');

        if($result){
            $project_id = $this->db->insert_id();
            
            $this->load->library('ciqrcode');
            $params['data'] = $link;
            $params['level'] = 'H';
            $params['size'] = 5;
            $params['errorlog'] = FALSE;
            $params['savename'] = './assets/images/qr_code/project/'.$project_id.'.png';
            $this->ciqrcode->generate($params);
        }
        return $result;
    }

    public function updateProjectDetails($post_arr){
        $link = 'http://maps.google.com/?q='.$post_arr['location'];
        $this->db->set('project_name' , $post_arr['project_name']);
        $this->db->set('customer_name' , $post_arr['customer_name']);
        // $this->db->set('mobile' , $post_arr['mobile']);
        $this->db->set('location' , $post_arr['location']);
        $this->db->set('email' , $post_arr['email']);
        $this->db->set('map' , $link);
        $this->db->set('estimated_cost' , $post_arr['estimated_cost']);
        $this->db->set('estimated_value' , $post_arr['estimated_value']);
        $this->db->set('estimated_duration' , $post_arr['estimated_duration']);
        $this->db->set('status' , $post_arr['status']);
        $this->db->set('start_date' , $post_arr['start_date']);
        $this->db->set('end_date' , $post_arr['end_date']);
        $this->db->where('id' , $post_arr['id']);
        $result = $this->db->update('project');

        return $result;

    }
    public function getMarketDetails(){

        $details = array();
        $this->db->select('*');
        $this->db->from('market_details');
        $this->db->where('status' , 'yes');
        $this->db->order_by('sort_order');
        $query = $this->db->get();
        foreach($query->result_array() as $row){
            $details[] = $row;
        }
        return $details;

    }
    public function getProjectDetails($post_arr){

        $details = array(); 
        $this->db->select('p.*');
        $this->db->select('c.customer_username,c.name cus_name,c.mobile customer_mobile,c.email customer_email');
        $this->db->from('project p');
        $this->db->join('customer_info c','c.customer_id = p.customer_name');

        if (element('project_id', $post_arr )) {
            $this->db->where('p.id', $post_arr['project_id'] );
        }
        if (element('customer_name',$post_arr)) {
            $this->db->where('p.customer_name',$post_arr['customer_name']);
        }

        if (element('mobile',$post_arr)) {
            $this->db->where('p.mobile',$post_arr['mobile']);
        }

        if (element('email',$post_arr)) {
            $this->db->where('p.email',$post_arr['email']);
        }

        if (element('status',$post_arr)) {
            $this->db->where('p.status', $post_arr['status']);
        }


        if (element('start_date',$post_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
            $this->db->where('p.date >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
            $this->db->where('p.date <=', $end_date);
        }

        if (element('limit',$post_arr)) {
            $this->db->limit($post_arr['limit']);
        }

        $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
        foreach($query->result_array() as $row){
            $row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt',$row['id']);
            // $row['date']=date( 'd-m-Y H:i:s', strtotime($row['date']));

            $details[] = $row;
        }
        return $details;

    }

    public function deleteMarket($market_id){
        $this->db->set('status' , 'deleted');
        $this->db->where('id' , $market_id);
        $this->db->limit(1);
        $result = $this->db->update('market_details');
        return $result;
    } 



    public function activatemarket($market_id){
        $this->db->set('status' , 'yes');
        $this->db->where('id' , $course_id);
        $this->db->limit(1);
        $result = $this->db->update('add_courses');
        return $result;
    } 


    public function geteditDetails($edit_id){
        $details = array();
        $this->db->select('*');
        $this->db->from('market_details');
        $this->db->where('id' , $edit_id);
        $this->db->order_by('sort_order');
        $this->db->limit(1);
        $query = $this->db->get();
        foreach($query->result_array() as $row){
            $details = $row;
        }
        return $details;
    }

    public function updateMarketDetails($details,$market_image , $edit_id){
        $date = date('Y-m-d H:i:s');

        $this->db->set('pair' , $details['pair']);
        $this->db->set('percentual' , $details['percentual']);
        $this->db->set('sort_order' , $details['sort_order']);
        $this->db->set('bot' , $details['bot']);
        $this->db->set('date' , $details['date']);
        if($market_image != ""){
            $this->db->set('exchange' , $market_image);
        }

        $this->db->set('updated_date' , $date);
        $this->db->where('id' , $edit_id);
        $result = $this->db->update('market_details');
        return $result;

    }




    public function insertGalleryImages( $data )
    { 
        return $this->db->insert_batch('course_chapter_details', $data); 
    }


    public  function updateStatus($id,$status)
    {
        $this->db->set('status',$status);
        $this->db->where('id',$id);
        $res= $this->db->update('project');
        return $res;
    }

    public function getCustomerName($customer_id='')
    {
        $details = null;
        $this->db->select('customer_username');
        $this->db->from('customer_info');
        $this->db->where('status','active');
        
        $this->db->where('customer_id',$customer_id);
        
        $query  =$this->db->get();
        foreach($query->result() as $row)
        {
            $details = $row->customer_username;
            
        }
        return $details;
    }

    public function getProjectJobs($project_id='')
    {
        $this->load->model('Jobs_model');
        $details = array();
        $this->db->select('jo.*');
        $this->db->select('c.customer_username,c.name cus_name, c.email cus_email,c.mobile cus_mobile,c.map');
        
        $this->db->from('job_orders jo');
        $this->db->join('customer_info c','c.customer_id = jo.customer_id');
        
        if($project_id)
        {
            $this->db->where('jo.project_id',$project_id);
        }
        $get = $this->db->get();
        foreach($get->result_array() as $row)
        {
      // $spent_time=array_column($row,'actual_workin_hrs');
      // $estm_time=array_column($row,'estimated_working_hrs');
            $row['time_difference'] = $row['actual_workin_hrs'] - $row['estimated_working_hrs'] ;
            $row['department_jobs'] = $this->Jobs_model->getDepartmentJobsDetails($row['id']);
            $row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt',$row['id']);
            $details[] = $row;
        }
        return $details;
    }



}