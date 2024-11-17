<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller 
{
    public $data = array();      
    public $SUPPORT_MENU = NULL; 
    public $MAINTENANCE_TEXT = NULL; 
    public $MAINTENANCE_MODE = 0; 

    public $LANG_ARR = array();
    public $LOGIN_LANG_ARR = array();
    public $LOGIN_LANG_ID = NULL;

    function __construct()
    {
        parent::__construct();

        if( current_uri() == 'login/index' && $this->hasSession() ){

            if($this->session->userdata('site_logged_in')['user_type'] == 'supervisor'){
                $this->redirect("", "delivery/read-delivery-code", true);
            }else{
                $this->redirect("", "dashboard", true);
            } 
        }

        $this->set_default_data();

        $this->set_logged_user_data();

        $this->set_flash_message();

        $this->checkUserLogged();

    }

    function set_default_data() { 

        $site_details = array(); 
        $site_details = $this->Base_model->getCompanyInformation();
        $this->data[ 'site_details' ] = $site_details;

        $this->MAINTENANCE_MODE = $site_details["maintenance_mode"]; 
        $this->data[ 'MAINTENANCE_MODE' ] = $this->MAINTENANCE_MODE;

        $this->MAINTENANCE_TEXT = $site_details["maintenance_mode_text"];
        $this->data[ 'MAINTENANCE_TEXT' ] = $this->MAINTENANCE_TEXT;

        $this->data['unread_mail'] = $this->Base_model->getUnreadMails();

        if(element('default_login_lang_id', $this->session->userdata)){
            $this->LOGIN_LANG_ID = $this->session->userdata('default_login_lang_id');
        }else{
            $this->LOGIN_LANG_ID = $site_details["login_lang"];
        } 
    }

    function set_logged_user_data() {

        if ($this->hasSession()) {  

            $this->data[ 'SIDE_MENU' ] = $this->Base_model->getSideMenu( log_user_type() );

            if($this->router->class == 'support'){
                $this->data[ 'SUPPORT_MENU' ] = $this->Base_model->getSideMenu(log_user_type(), 'support');
            } 

            $this->data[ 'logout_url' ] = base_url().'login/logout';
            if(log_user_type()  == 'customer'){

                $select_arr = ['name', 'email', 'mobile'];
                $user_details = $this->Base_model->getCustomerDetails(log_user_id(), $select_arr ); 

            }else{
                $select_arr = ['first_name', 'second_name', 'user_photo', 'email', 'mobile','facebook', 'instagram', 'twitter'];
                $user_details = $this->Base_model->getUserDetails(log_user_id(), $select_arr ); 
                if(log_user_type()  == 'dept_supervisor'){
                    $user_details['department_name'] = $this->Base_model->getDepartmentName(log_dept_id());   
                }
            };
// print_r($user_details);

//             die();
            $this->lang->load('common', 'english');
            $this->data[ 'profile_pic' ] = $user_details['user_photo'];  
            $this->data[ 'user_details' ] = $user_details;    
        }
    }

    function set_flash_message() {
        $flash_message = $this->session->flashdata('redirect_message');
        if (isset($flash_message)) {
            $this->data[ 'flashdata' ] = $flash_message["flashdata"];
            $this->data[ 'type' ] = $flash_message["type"];
            $this->data[ 'box' ] = $flash_message["box"]; 
        } else {
            $this->data[ 'box' ] = FALSE; 
        }
    }

    function set_session_flash_data($message, $message_type ) {
        $redirect_message["flashdata"] = $message;
        $redirect_message["type"] = $message_type;
        $redirect_message["box"] = true;
        $this->session->set_flashdata('redirect_message', $redirect_message);
    }

    function hasSession() {
        $flag = !empty($this->session->userdata['site_logged_in']) ? true : false;
        return $flag;
    }

    function loadView( $data ) {
        $data = array_merge( $this->data, $data );
        $this->load->view( '' , $data );
    }

    function redirect($message, $page_to_reload, $message_type = false, $redirect_message = array()) 
    {
        $redirect_message["flashdata"] = $message;
        $redirect_message["type"] = $message_type;
        $redirect_message["box"] = true;
        $this->session->set_flashdata('redirect_message', $redirect_message);

        $path = base_url();

        $split_pages = explode("/", $page_to_reload);
        $controller_name = $split_pages[0];

        if (in_array($controller_name, $this->software->COMMON_PAGES)) {
            $path .= $page_to_reload;
            redirect("$path", 'refresh');
            exit();
        } else {
            if ($this->hasSession()) {
                $user_type = $this->session->userdata['site_logged_in']['user_type'];

                if ($user_type=='supervisor') {
                   $user_type='sub-admin';
                }
                if ($user_type == "admin" || $user_type == "employee") {
                    $path .= "admin/" . $page_to_reload;
                } 
              

                else {
                    $path .= $user_type."/" . $page_to_reload;
                }
                redirect("$path", 'refresh');
                exit();
            } else {
                if (in_array($controller_name, $this->software->NO_LOGIN_PAGES)) {
                    $path .= $page_to_reload;
                    redirect("$path", 'refresh');
                    exit();
                } else {
                    $path .= "login";
                    redirect("$path", 'refresh');
                    exit();
                }
            }
        }
    }


    function checkUserLogged() {

        if ($this->hasSession() && !in_array($this->router->class, $this->software->NO_LOGIN_PAGES)) {
            // in sec
            $timeout = 30000;
            if ((time() - $this->session->userdata['last_login']) > $timeout)
            {
                $this->set_time_out();
            } else {
                $this->session->set_userdata( 'last_login', time() );
            }
        } else {
         if(!in_array($this->router->class, $this->software->NO_LOGIN_PAGES))
         {
            $this->set_time_out();
        }
    }
    return true;
}

private function set_time_out(){
    $logged_in = $this->session->userdata('site_logged_in');

    $this->session->unset_userdata('site_logged_in');

    $timeout_sess = array();

    if($this->hasSession()){

        $timeout_sess = array(
            'user_name' => $logged_in['user_name'],
            'user_id' => $logged_in['user_id']
        );
    }

    $this->session->set_userdata( 'site_timeout_sess', $timeout_sess );
    $this->redirect('', 'session-out', false );
}
}
