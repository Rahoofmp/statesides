<?php  
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Software {

    protected $CI;
    
    public $LANG_ID =1;
    public $LANG_CODE = NULL;

    public $COMMON_PAGES;
    public $NO_LANG_CLASS;
    public $NO_LOGIN_PAGES;
    public $NO_MODEL_CLASS_PAGES;

    protected $LOGGED_IN_ARR = array();


    function __construct()
    {
        $this->CI =& get_instance();
        empty($config) OR $this->initialize($config);

        log_message('info', 'Software Class Initialized');         

        $this->COMMON_PAGES = [ 'login', 'signup', 'backup', 'referral', 'jsloader' , 'website','cron_jobs' ]; 
        $this->NO_LANG_CLASS = [ 'fix_issues', 'cron_jobs', 'autocomplete', 'truncate', 'backup', 'jsloader', 'auth', 'referral', 'home', 'users' ];
        $this->NO_LOGIN_PAGES = [ 'login', 'backup',  'fix_issues', 'website' ];
          $this->NO_MODEL_CLASS_PAGES = [ 'autocomplete', 'jsloader','referral', 'auth', 'home', 'users', ];
        

        if(isset($this->CI->session->userdata['site_logged_in']) && $this->CI->session->userdata['site_logged_in']){ 
            $this->LOGGED_IN_ARR = $this->CI->session->userdata('site_logged_in');
        }
    }
    public function log_user_id(){
        $log_user_id = element('user_id', $this->LOGGED_IN_ARR) ? $this->LOGGED_IN_ARR['user_id'] : NULL;
        // if($log_user_id > 0 && $log_user_id < 100)
        //     $log_user_id = 100;
        return $log_user_id;
    }

    public function log_customer_id(){
        $log_customer_id = element('user_id', $this->LOGGED_IN_ARR) ? $this->LOGGED_IN_ARR['user_id'] : NULL;
        return $log_customer_id;
    }
 
    public function log_user_name(){
        return element('user_name', $this->LOGGED_IN_ARR) ? $this->LOGGED_IN_ARR['user_name'] : NULL;
    }

    public function log_user_type(){
        return element('user_type', $this->LOGGED_IN_ARR) ? $this->LOGGED_IN_ARR['user_type'] : NULL;
    }

    public function admin_user_id(){
        return element('admin_user_id', $this->LOGGED_IN_ARR) ? 100 : NULL;
    }

    public function log_lang_id(){
        return element('lang_id', $this->LOGGED_IN_ARR) ? $this->LOGGED_IN_ARR['lang_id'] : NULL;
    }

    public function log_dept_id(){ 
        return element('department_id', $this->LOGGED_IN_ARR) ? $this->LOGGED_IN_ARR['department_id'] : NULL;
    }

    public function theme_folder($user_type){
        return $this->getSettingValueByKey($user_type."_theme_folder");
    }

    public function getSettingValueByKey($key){
        $value = NULL;
        $this->CI->db->select("value");
        $this->CI->db->where("key", $key);
        $query = $this->CI->db->get("settings");

        foreach ($query->result_array() as $result) {
            $value = $result["value"];
        }
        return $value;
    }

    public function getSettingsByCode($code){
        $result = array();
        $this->CI->db->select("*");
        $this->CI->db->where("code", $code);
        $query = $this->CI->db->get("settings");
        foreach ($query->result_array() as $row) {
            $result[] = array( 
                "setting_id" => $row["setting_id"],
                "key" => $row["key"],
                "value" => $row["value"],
                "data" => json_decode($row["data"]),
            );
        }
        return $result;
    }

    public function getSettingsByKey($key){
        $result = array();
        $this->CI->db->select("*");
        $this->CI->db->where("key", $key);
        $query = $this->CI->db->get("settings");

        foreach ($query->result_array() as $row) {
            $result = array( 
                "setting_id" => $row["setting_id"],
                "code" => $row["code"],
                "value" => $row["value"],
                "data" => unserialize($row["data"]),
            );
        }
        return $result;
    } 
} 