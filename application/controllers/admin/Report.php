<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function members()
	{  
		$data['title'] = lang('members'); 
		$post_arr = $this->input->post();
		$post_arr['start_date'] = element( 'start_date',$post_arr ) ? $post_arr['start_date'] : date('Y-m-01');
		$post_arr['end_date'] = element( 'end_date',$post_arr ) ? $post_arr['end_date'] : date('Y-m-t'); 
		$data['post_arr'] = $post_arr; 

		$this->loadView($data);
	}

	public function get_member_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Report_model->getMemberCount();
			$count_with_filter = $this->Report_model->getMembersDetails($post_arr, 1);
			$result_data = $this->Report_model->getMembersDetails($post_arr);
			
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
		}
	}

	function projects()
	{
		$data['title']="Project List";
		$post_arr = $this->input->post();
		
		$data['post_arr'] = $post_arr;

		$this->loadView($data);
	}
	function inventory()
	{
		$data['title']="Inventory List";
		$this->load->model('Delivery_model');
		$this->load->model('Item_model');
		$post_arr = $this->input->post();
		if (element('item_id',$post_arr)) {
			
			$data['item_code']=$this->Item_model->getItemCode('code',$post_arr['item_id']);
		}
		$data['post_arr'] = $post_arr;

		$data['categories']=$this->Delivery_model->getAllCategories();

		$this->loadView($data);
	}

	public function get_project_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Report_model->getProjectCount();
			$count_with_filter = $this->Report_model->getProjectDtailsAjax($post_arr, 1);
			$result_data = $this->Report_model->getProjectDtailsAjax($post_arr);

			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
		}
	}
	public function get_item_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Report_model->getItemCount();
			$count_with_filter = $this->Report_model->getItemDtailsAjax($post_arr, 1);
			$result_data = $this->Report_model->getItemDtailsAjax($post_arr);

			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
		}
	}

	public  function jobs()
	{
		$data['title']="Jobs";
		$this->load->model('Jobs_model');
		$this->load->model('Project_model');

		$post_arr = $this->input->post();
		
		if ($this->input->post('search')) {
			if(element('customer_id',$post_arr))
				$post_arr['customer_name'] = $this->Project_model->getCustomerName($post_arr['customer_id']);

		}
		$data['post_arr'] = $post_arr;
		$this->loadView($data);
	}	

	public function get_jobs_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Report_model->getJobsCount();
			$count_with_filter = $this->Report_model->getJobsDtailsAjax($post_arr, 1);
			$result_data = $this->Report_model->getJobsDtailsAjax($post_arr);
			
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
		}
	}

	function dept_job_list( )
	{
		$data['title']="Job List";
		$this->load->model(['Jobs_model', 'Project_model']); 
		$post_arr = $this->input->post();
		

		if ($this->input->post('search')) { 
			if(element('customer_id',$post_arr))
				$post_arr['customer_name'] = $this->Project_model->getCustomerName($post_arr['customer_id']);  
			
			if(element('department_id',$post_arr))
				$post_arr['department_name'] = $this->Base_model->getDepartmentName($post_arr['department_id']);  
			
		} 
		$data['post_arr'] = $post_arr;
		$this->loadView($data);
	} 

	public function get_dept_jobs_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Report_model->getDeptJobsCount();
			$count_with_filter = $this->Report_model->getDeptJobsDtailsAjax($post_arr, 1);
			$result_data = $this->Report_model->getDeptJobsDtailsAjax($post_arr);
			
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
		}
	}

}
