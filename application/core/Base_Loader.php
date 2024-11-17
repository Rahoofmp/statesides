<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Loader extends CI_Loader {

    public function view( $template='', $data = array(), $return = FALSE )
    {
        $CI =& get_instance();
        
        if (in_array($CI->router->class, $CI->software->COMMON_PAGES)) {
            if($template){
                $CI->smarty->view("$template" . '.tpl', $data, $return);
            } else
            {
                $CI->smarty->view($CI->router->class . '/' . $CI->router->method . '.tpl', $data, $return);
            }
        } else {
            if($template){
                $CI->smarty->view("$template" . '.tpl', $data, $return);
            } else
            {
                $CI->smarty->view(log_user_type()."/" . $CI->router->class . '/' . $CI->router->method . '.tpl', $data, $return);
            }
        }
    }
}
