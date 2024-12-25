<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Autocomplete extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function user_filter()
	{ 
		$keyword = $this->input->post('keyword'); 
		$user_arr = $this->Base_model->getfilteredUsers($keyword,20);
		echo json_encode($user_arr);
	}
	function project_filter()
	{ 
		$keyword = $this->input->post('keyword'); 
		$project_arr = $this->Base_model->getfilteredProject($keyword,20);
		echo json_encode($project_arr);
	}


	function package_filter()
	{ 
		$keyword = $this->input->post('keyword'); 
		$package_arr = $this->Base_model->getfilteredPackage($keyword,20);
		echo json_encode($package_arr);
	}

	function project_package_filter()
	{ 
		$keyword = $this->input->post('keyword'); 
		$project_id = $this->input->post('project_id'); 
		$package_arr = $this->Base_model->getfilteredProjectPackage($keyword, $project_id, 20);
		echo json_encode($package_arr);
	}
	
	function package_items_filter()
	{ 
		$keyword = $this->input->post('keyword'); 
		$project_arr = $this->Base_model->getfilteredPackageItems($keyword,20);
		echo json_encode($project_arr);
	}

	function package_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();

			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getPackageIdAjax($post['q']);
			echo json_encode($json);
		}
	}


	function project_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getProjectIdAuto($post['q']);
			echo json_encode($json);
		}
	}

	function driver_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getDriverIdAuto($post['q']);
			echo json_encode($json);
		}
	}

	function superSalesman_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getSalesmanIdSub($post['q']);
			echo json_encode($json);
		}
	}

	function countryNames_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getCountryAuto($post['q']);
			echo json_encode($json);
		}
	}

}