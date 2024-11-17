<?php
class Cron_jobs_model extends Base_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('Calculation_model');
        $this->load->model('Mail_model');
        $this->load->model('Settings_model');
        $this->load->model('Signup_model');
        $this->load->model('Network_model');
        $this->load->model('Member_model');

    }

    public function insertCronHistory($cron_name) {
        $this->db->set("cron", $cron_name);
        $this->db->set('start_time', date("Y-m-d H:i:s"));
        $this->db->set("status", "started");
        $this->db->insert('cron_history');
        $cron_id = $this->db->insert_id();
        return $cron_id;
    }

    public function updateCronHistory($cron_id, $status) {
        $this->db->set("status", $status);
        $this->db->set('end_time', date("Y-m-d H:i:s"));
        $this->db->where('id', $cron_id);
        $this->db->limit(1);
        $this->db->update('cron_history');
        return TRUE;
    }


    public function isCronAlreadyRun($date,$cron_name) {
        $count = 0;
        $this->db->select("count(id) as count");
        $this->db->where('cron', $cron_name);
        $this->db->like('start_time', $date, "after");
        $this->db->where('status', "finished");
        $this->db->from("cron_history");
        $query = $this->db->get();
        foreach ($query->result() AS $row) {
            $count = $row->count;
        }

        if($count > 0)
            return TRUE;
        else
            return FALSE;            
    }

    public function lastCronTime($cron) {
        $admin_id = $this->Base_model->getAdminId();
        $cron_time = $this->Base_model->getLoginInfoField('joining_date', $admin_id);
        $this->db->select("start_time");
        $this->db->where('cron', $cron);
        $this->db->where('status', "finished");
        $this->db->from("cron_history");
        $query = $this->db->get();
        foreach ($query->result()AS $row) {
            $cron_time = $row->start_time;
        }
        return $cron_time;
    }
    public function cronRunCount($date,$cron_name) {
        $count = 0;
        $this->db->select("count(id) as count");
        $this->db->where('cron', $cron_name);
        $this->db->like('start_time', $date, "after");
        $this->db->from("cron_history");
        $query = $this->db->get();
        foreach ($query->result() AS $row) {
            $count = $row->count;
        }

        return $count;            
    }
    public function insertActivationHistory($user_id,$status,$date_of_submission,$type) {
        $this->db->set("new_status", $status);
        $this->db->set("user_id", $user_id);
        $this->db->set("date", $date_of_submission);
        $this->db->set("qualified_by", $type);
        $this->db->set("current_status", 0);
        $res = $this->db->insert('activate_inactivate_history');
        return $res;
    }


    public function StatusChange()
    {


        $notes=array();
        $this->db->select("pp.*")
        // ->where('pp.date_created >=', '2021-12-31 23:59:59')
        // // ->where('pp.date_created <=', '2022--31 23:59:59')
        ->where_in('ci.customer_id', [ 76, 104,116, 96, 51, 88, 106, 97, 72, 114 ])
        ->where('pp.status', 'pending')
        ->from("project_packages pp")
        ->join('project p', 'p.id = pp.project_id')
        ->join('customer_info ci', 'ci.customer_id = p.customer_name');

        $query = $this->db->get(); 

        $date = date('Y-m-d H:i:s'); 
        
        print_r($query->result_array());
        $this->rollback();
        die();

        foreach ($query->result_array()AS $row) {
            $notes[]=$row;

            $this->db->set('status', 'delivered')
            ->set('updated_date', $date)
            ->where('id',$row['id']);
            $res= $this->db->update('project_packages'); 
            if(!$res){
                return FALSE;
            }

        } 
        return TRUE;
    } 
}