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
        $this->db->set('status',1);
        $insert = $this->db->insert('department');
        return $insert;
    }

    public function getDepartmentMaster($id='',$exclude=[])
    {
        $details = array();
        $this->db->select('*');
        $this->db->from('department');
        if($id)
        {
            $this->db->where('id',$id);
        }
        if ($exclude) {
            # code...
            $this->db->where_not_in('id',$exclude);
        }
        $this->db->where('status',1);
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

    public function updateDepartmentMaster($post_arr,$id)
    {
        $this->db->set('dep_id',$post_arr['department_id']);
        $this->db->set('name',$post_arr['department_name']);
        $this->db->set('cost_per_hour',$post_arr['cost_per_hour']);
        $this->db->set('date_updated',date('Y-m-d H:i:s'));
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

}