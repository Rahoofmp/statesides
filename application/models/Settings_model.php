<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends Base_model {

    function __construct() {
        parent::__construct();

    }

    public function updateWebisteInfo($data) {
        $this->db->set('name', $data['website_name']);
        $this->db->set('address', $data['address']);
        $this->db->set('email', $data['email']);
        $this->db->set('phone', $data['phone']);
        $result =  $this->db->update('site_info');
        return $result;
    }

    public function insertActivateHistory($user_id , $renewal_date , $new_renewal_date){

        $submit_date = date('Y-m-d H:i:s');
        $this->db->set('user_id',$user_id);
        $this->db->set('old_date',$renewal_date);
        $this->db->set('new_date',$new_renewal_date);
        $this->db->set('submit_date',$submit_date);
        $result = $this->db->insert('activate_history');
        return $result;

    }  

    public function insertDepartmentMaster($post_arr)
    {
        $this->db->set('dep_id',$post_arr['department_id']);
        $this->db->set('name',$post_arr['department_name']);
        $this->db->set('cost_per_hour',$post_arr['cost_per_hour']);
        $this->db->set('date_added',date('Y-m-d H:i:s'));
        $this->db->set('status',$post_arr['status']);
        $insert = $this->db->insert('department');
        return $insert;
    }

    public function getDepartmentMaster($id='',$search_arr=[] )
    {
        $details = array();
        $this->db->select('*');
        $this->db->from('department');
        if($search_arr)
        {
            if(element('status',$search_arr)=='active') {
                $this->db->where('status',1);
            }else if(element('status',$search_arr)=='inactive')
            {
                $this->db->where('status','0');
            }

            if(element('department_id',$search_arr))
            {
                $this->db->where('id',$search_arr['department_id']);
            }
            
            if(element('dep_id',$search_arr))
            {
                $this->db->where('dep_id',$search_arr['dep_id']);
            }
        }

        if($id)
        {
            $this->db->where('id',$id);
        } 

        $query = $this->db->get();
        foreach($query->result_array() as $row)
        {
            $row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt',$row['id']);
            if($id){

                $details = $row;
            }else{

                $details[] = $row;
            }
        }
        return $details;
    }
    public function getDepartments()
    {
        $details = array();
        $this->db->select('*');
        $this->db->from('department');
        $this->db->where('status',1);

        $query = $this->db->get();
        foreach($query->result_array() as $row)
         $details[]=$row;
     return $details;
 }
 public function getDepartmentID($department_name='') {
    $id = array();
    $this->db->select('dep_id');
    $this->db->from('department');
    if($department_name)
    {

        $this->db->where('name', $department_name);
    }

    $query = $this->db->get();
    foreach ($query->result_array() as $row) {
        $dep_id[]= $row['dep_id'];
    }
    return $dep_id;
}
public function updateDepartmentMaster($post_arr,$id)
{
    $this->db->set('dep_id',$post_arr['department_id']);
    $this->db->set('name',$post_arr['department_name']);
    $this->db->set('cost_per_hour',$post_arr['cost_per_hour']);
    $this->db->set('date_updated',date('Y-m-d H:i:s'));
    $this->db->set('status',$post_arr['status']);
    $this->db->where('id',$id);
    $update = $this->db->update('department');
    return $update;
}

public function updateDepartmentStatus($id)
{
    $this->db->set('status',0);
    $this->db->where('id',$id);
    $update = $this->db->update('department');
    return $update;
}

public function getSubadmin()
{
    $details = array();
    $this->db->select('user_id,user_name');
    $this->db->from('login_info');
    $this->db->where('user_type','supervisor');
    $this->db->where('status',1);
    $query = $this->db->get();
    foreach($query->result_array() as $row)
    {

        $details[] = $row;
        
    }
    return $details;
}

}