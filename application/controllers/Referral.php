<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Referral extends Base_Controller 
{

	public function index($user_name,$leg=1)
	{ 
		if(($leg !=1 && $leg != 2) || $user_name == "")
		{
			$this->redirect('Invalid url...!','login');
		}
		if($user_name)
		{
			$user_id = $this->Base_model->getUserId($user_name);
			if($user_id)
			{
				$this->redirect('',"signup/index/$user_name/$leg");

			}
			else
			{

				$this->redirect('Invalid User name...!','login');
			}
		}
		else
		{
			$this->redirect('Invalid User name...!','login');
		}
		die();
	}

}
