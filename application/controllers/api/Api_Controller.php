<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api_Controller extends CI_Controller{

	public $LANGUAGE = 'english';
	function __construct()
	{
		parent::__construct(); 
		$version = '4';
		
		if($this->input->method() === 'post' || $this->router->method == 'profile_image'){

			$_POST = json_decode($this->input->raw_input_stream,true);
		}elseif($this->input->method() === 'get') {

			$_GET = json_decode($this->input->raw_input_stream,true);
		}

		$this->load->helper('authorization');
		
		if( $lang = $this->input->post_get('lang') )
		{ 
			$this->LANGUAGE = $lang;
		}
                // die($this->LANGUAGE);

		
		if( $this->input->post_get('version') )
		{
			if( $version !== $this->input->post_get( 'version' ) )
			{
				$response['success'] = FALSE;
				$response['msg'] = 'Please update your app. Otherwise you cannot perform this action..';
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}
		}
	}

	public function check_header()
	{        
		$headers = $this->input->request_headers(); 


		if (Authorization::KeyIsExist($headers)) {
			$api_key = element('Api_key', $headers) ? $headers['Api_key'] : $headers['api_key'];  

			
			if ($api_key) {  
				
				if (API_TOKEN == $api_key) {

					if (Authorization::tokenIsExist($headers)) {
						$auth = element('authorizations', $headers) ? $headers['authorizations'] : $headers['Authorizations'];



						if ($auth) {

							$token = Authorization::validateToken($auth);

							

							$user_exist = $this->Base_model->isUserExist($token->user_id);

							if ($token != false && $user_exist) {
								return $token;
							}else{

								$response['success'] = FALSE;
								$response['msg'] = lang('please_contact_admin');

								$this->output
								->set_status_header(200)
								->set_content_type('application/json', 'utf-8')
								->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
								->_display();
								exit();
							}
						}
					}  
				}else{

					$response['success'] = FALSE;
					$response['msg'] = lang('please_contact_admin');

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
			}
		}  
		
		$response['success'] = FALSE;
		$response['msg'] = 'Forbidden';

		$this->output
		->set_status_header(200)
		->set_content_type('application/json', 'utf-8')
		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
		->_display();
		exit();

	}

}
