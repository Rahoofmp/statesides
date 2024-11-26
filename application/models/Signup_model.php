<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Signup_model extends Base_Model {

    function __construct() {
        parent::__construct();
    } 



    public function registrationProcess($register) {

        $return_data = array(
            'username'   => $register['user_name'],
            'status' => false
        );

        $register['joining_date'] = date('Y-m-d H:i:s');

        $new_user_id = $this->insertLoginInfo($register);

        if ($new_user_id) {
            $register['user_id'] = $new_user_id;
            $insert_user_info = $this->insertUserInfo($register);
        }

        if ( $new_user_id && $insert_user_info ) {

            $email_alerts_arr = $this->software->getSettingsByKey('register'); 

            if(value_by_key('register') && $email_alerts_arr['code'] == 'e_mail_alert')
            {
                $this->load->model('Mail_model'); 
                $register['fullname'] = $register['name'];

                $send_mail = $this->Mail_model->sendEmails('registration', $register);
            }
            $return_data['username'] = $register['user_name'];
            $return_data['password']  = $register['psw'];
            $return_data['user_id'] = $register['user_id'];
            $return_data['status'] = true;
        }


        return $return_data;
    }

    public function insertUserInfo($register) { 

        $data = array(
            'user_id' => $register['user_id'], 
            'first_name' => $register['name'],
            'email' => $register['email'],
            'mobile' => $register['mobile']
        );
        $result = $this->db->insert('user_info', $data);
        if ($result) {
            $serialized_data = serialize($data);
            $result = $this->insertIntoActivityHistory($register['user_id'], log_user_id(),'user registered',$serialized_data);
        }
        return $result;
    } 



    public function insertLoginInfo($register) {
        $this->config->load('bcrypt');
        $this->load->library('bcrypt');
        $hashed_password = $this->bcrypt->hash_password( $register['psw'] );

        $this->db->set('user_name', $register['user_name']);
        $this->db->set('password', $hashed_password );  
        $this->db->set('joining_date', $register['joining_date']); 
        $this->db->set('user_type',$register['user_type']);
        if (array_key_exists('subadmin', $register)) {
            $this->db->set('sub_id',$register['subadmin']);
        }
        $this->db->set('reg_log_id',log_user_id());
        $this->db->set('status', '1');

        $result = $this->db->insert('login_info');
        return $this->db->insert_id(); 
    }


    public function isUserAvailable($user_name,$package_id=0) {
        $this->db->select("COUNT(user_id) as count");
        $this->db->from("login_info");
        $this->db->where('user_name', $user_name);
        if($package_id)
            $this->db->where('package_id >=', $package_id);
        $this->db->where('status', 1);
        $this->db->where('status !=', 0);
        $query = $this->db->get();
        foreach ($query->result()AS $row) {
            return $count = $row->count;
        }
        return 0;
    }


    public function checkMobileExist($post) {

        $count = 0;
        $this->db->select("COUNT(ui.mobile) as count");
        $this->db->join('login_info li','ui.user_id=li.user_id');
        $this->db->from("user_info ui");
        $this->db->where('ui.mobile', $post['mobile']);
        $this->db->where('li.user_type', $post['user_type']);
        $query = $this->db->get();
        foreach ($query->result() AS $row) {
            $count = $row->count;
        }
        return $count;
    }

    public function checkEmailExist($post) {
        $count = 0;
        $this->db->select("COUNT(ui.email) as count");
        $this->db->join('login_info li','ui.user_id=li.user_id');
        $this->db->from("user_info ui");
        $this->db->where('ui.email', $post['email']);
        $this->db->where('li.user_type', $post['user_type']);
        $query = $this->db->get();

        foreach ($query->result() AS $row) {
            $count = $row->count;
        }
        return $count;
    }


}

