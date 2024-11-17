<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Packages extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}
  

	public  function package_details($enc_id)
	{
		$data['title']='Package Details';
		if($enc_id)
		{
			$id=$this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );
			$data['details']= $this->Packages_model->getPackagesDetails($id,true);
		}
		$this->loadView($data);
	} 

	public  function read_package_code()
	{ 
		$data['title']='Read the package code';

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			$package_id = $this->Packages_model->getpackageIdByCode($post_arr['package_code']);
		

			if($package_id){
				$enc_id = $this->Base_model->encrypt_decrypt( 'encrypt', $package_id );

				$this->redirect('','packages/package-details/'.$enc_id, FALSE);
			}else{

				$msg="Invalid code";
				$this->redirect($msg, 'packages/read-package-code/', FALSE);
			}

		}
		$this->loadView($data);

	}


	

}
