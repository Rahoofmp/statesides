<?php

require_once APPPATH . 'libraries/JWT.php';

use \Firebase\JWT\JWT;

class Authorization
{
    public static function validateToken($token)
    {
        $CI =& get_instance();
        $CI->load->config('jwt');
        $key = $CI->config->item('jwt_key');
        $algorithm = $CI->config->item('jwt_algorithm'); 
        return JWT::decode($token, $key, array($algorithm));
    }

    public static function generateToken($data)
    {
        $CI =& get_instance();
        $CI->load->config('jwt');
        $key = $CI->config->item('jwt_key');
        $algorithm = $CI->config->item('jwt_algorithm');
        return JWT::encode($data, $key);
    }

    public static function tokenIsExist($headers)
    {
        if($auth = element('Authorizations', $headers)){
            $key = 'Authorizations';
        }else{
            $key = 'authorizations';
        }

        return (array_key_exists($key, $headers)
            && !empty($headers[$key]));
    }
    
    public static function KeyIsExist($headers)
    {
        if($auth = element('api_key', $headers)){
            $key = 'api_key';
        }else{
            $key = 'Api_key';
        }
        
        return (array_key_exists($key, $headers)
            && !empty($headers[$key]));
    }
}
