<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Network extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function genealogy_tree()
	{

		$user_id = $this->input->get('user_id');
		if(!isset($user_id)){ 
			$user_id = log_user_id();
			$user_name = $this->Base_model->getUserName($user_id); 
		}
		else{
			$user_id = $this->Base_model->encrypt_decrypt('decrypt',$user_id);
			$user_name = $this->Base_model->getUserName($user_id);
		}
		if($this->input->post("view_user_tree") && $this->validate_user_search()){

			$post_array = $this->input->post();
			$user_id = $this->Base_model->getUserId($post_array['user_name']);
			$user_name = $this->Base_model->getUserName($user_id);

		}

		$data['title'] =  lang('text_genealogy_tree'); 

		$data['user_name'] = $user_name;  
		$data['user_id'] = $user_id;  

		$this->loadView($data);

	}

	function validate_user_search()
	{
		$this->form_validation->set_rules('user_name', lang('text_user_name'), 'trim|required|is_exist[login_info.user_name]');
		
		$validation_result = $this->form_validation->run();

		return $validation_result;
	}
	
	function view_network() {
		$post_array = $this->input->post(); 
		$user_id = $post_array['user_id'];

		if ($this->Base_model->isUserExist($user_id)) {
			$tree_type = $post_array['tree'];
			if($tree_type != 'plan_tree')
				$entry_no = $post_array['entry_no'];
			else
				$entry_no = 0;
			$this->Network_model->getAllTreeUsers($user_id, $tree_type,$entry_no);

			$data['tree_member'] = $this->Network_model->tree_member; 
			$data["tree_type"] = $tree_type;
			$data['user_id'] = $user_id;
			$this->loadView($data);
		} else {
			echo 'Invalid User Name...';
			die();
		}
	}



	function referral_tree()
	{
		$user_type = log_user_type();
		$user_id = log_user_id();
		$user_name = $this->Base_model->getUserName($user_id);

		if($this->input->post("view_user_tree") && $this->validate_user_search()){

			$post_array = $this->input->post();

			$user_id = $this->Base_model->getUserId($post_array['user_name']);
			$user_name = $post_array['user_name'];
		}


		$data['title'] = lang('text_referral_tree');  
		$data['user_name'] = $user_name;  
		$data['user_id'] = $user_id;  

		$this->loadView($data);
	}

	function matrix_users()
	{

		$user_id = log_user_id();
		$level = 1;
		if($this->input->post("view_user_tree") && $this->validate_user_search()){
			$post_array = $this->input->post(); 
			$user_id = $this->Base_model->getUserId($post_array['user_name']);
			$level = $post_array['level'];
		}

		$downlines = $this->Network_model->getUserDownlines($user_id,$level);
		$data['title']= lang('downlines');	
		$data['user_id']= $user_id;	
		$data['downlines']= $downlines;	
		$data['user_level']= $level;	
		$this->loadView($data);
	}

	function bianry_position()
	{
		$user_id = log_user_id();

		$data['position'] = $this->Network_model->getBinaryPosition($user_id);
		
		if($this->input->post('update_position')){
			$position = $this->input->post('position');

			$result = $this->Network_model->updateBinaryPosition($user_id , $position);

			if($result){
				$this->redirect("Binary position updated successfully.", "network/bianry_position", TRUE);
			}
			else{
				$this->redirect("Error on Binary position updation.", "network/bianry_position", TRUE);
			}
		}

		$data['title']= "Binary Position";	
		$data['user_id']= $user_id;	
		$this->loadView($data);
	}
	

	function board_view($board_no = 1,$entry_no = 1)
	{
		if($board_no)
		{
			$board_no=$this->Base_model->encrypt_decrypt('decrypt',$board_no);
			
		}
		if($entry_no != 1)
		{
			$entry_no=$this->Base_model->encrypt_decrypt('decrypt',$entry_no);
			
		}
		else
			$entry_no = 1;
		$user_id = $this->input->get('user_id');
		if(!isset($user_id)){ 
			$user_id = log_user_id();
			$user_name = log_user_name(); 
		}
		else{
			$user_id = $this->Base_model->encrypt_decrypt('decrypt',$user_id);
			$user_name = $this->Base_model->getUserName($user_id);
		}
		if($this->input->post("view_user_tree") && $this->validate_user_search()){

			$post_array = $this->input->post();
			$user_id = $this->Base_model->getUserId($post_array['user_name']);
			$user_name = $this->Base_model->getUserName($user_id);

		}
		
		$data['title'] =  "Cycle View $board_no"; 
		
		$data['user_name'] = $user_name;  
		$data['user_id'] = $user_id;  
		$data['board_no'] = $board_no; 
		$data['entry_no'] = $entry_no;  

		$this->loadView($data);

	}


	public function board($board_no = 1)
	{
		$data['title']="Board List";
		if($board_no)
		{
			$board_no=$this->Base_model->encrypt_decrypt('decrypt',$board_no);
		}
		if($this->input->post('view_user_tree'))
		{
			$post=$this->input->post();
			$user_id=$this->Base_model->getUserId($post['user_name']);
			$data['details']=$this->Network_model->getBoardDetails($user_id,$board_no);
		}
		$board_no=$this->Base_model->encrypt_decrypt('encrypt',$board_no);
		$data['board_no'] = $board_no;  
		$this->loadView($data);
	}
}
