<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once ("Api_Controller.php"); 

class Auth extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        
    }

    public function login()
    {   

        $dataPost = $this->input->post();

        if($this->validate_login()){

            $this->lang->load('login', $this->LANGUAGE);

            $this->load->model('Login_model');
            $user = $this->Login_model->login($dataPost['user_name'], $dataPost['password']); 

            $user = json_decode(json_encode($user[0]), true);

            if($user){

                $tokenData = array();
                $user_id=$user['user_id'];
                $tokenData['user_id'] = $user_id;

                $user_photo=$this->Base_model->getUserInfoField('user_photo',$user_id);


                $user['avatar'] = assets_url('images/profile_pic/').$user_photo; 

                unset($user['user_id']); 
                unset($user['password']); 

                $response['success'] = TRUE;
                $response['msg'] = lang('login_success'); 

                $user['token'] = Authorization::generateToken($tokenData);
                // $delete = $this->cart->destroy();
                $response['data'] = $user;
                $this->output
                ->set_status_header(200)
                ->set_content_type('application/json', 'utf-8')
                ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                ->_display();
                exit(); 



            }

            $response['success'] = false;
            $response['msg'] = lang('invalid_user_name_or_password');

            $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
            exit(); 
        }
        elseif($error_array = $this->form_validation->error_array()){ 

            $response['success']= false;
            $response['msg'] = join(", ",$error_array);

            $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
            exit(); 



        }
        else{
            $response['success'] = false; 
            $response['msg'] = 'Unauthorized';

            $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
            exit();
        }
    }

    function validate_login() 
    {

        $this->form_validation->set_rules('user_name',  lang('username') , 'trim|required');
        $this->form_validation->set_rules('password', lang('password')  , 'trim|required|strip_tags');

        $val_res = $this->form_validation->run();
        return $val_res;
    }



   
}
