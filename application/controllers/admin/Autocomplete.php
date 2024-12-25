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
			// print_r($post);
			// die();
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
	function project_report_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getProjectIdAuto($post['q'],'',$status='all');
			echo json_encode($json);
		}
	}
	function packager_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getPackagerIdAuto($post['q']);
			echo json_encode($json);
		}
	}
	function supervisor_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getSupervisorIdAuto($post['q']);
			echo json_encode($json);
		}
	}
	
	function deliverycode_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getDeliveryCodeAuto($post['q']);
			echo json_encode($json);
		}
	}
	function salesman_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getSalesmanIdAuto($post['q']);
			echo json_encode($json);
		}
	}

	function source_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getSourceAuto($post['q']);
			echo json_encode($json);
		}
	}

	function salesman_by_customer_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post(); 
			$json = $this->Base_model->getCustomerSalesmanIdAuto($post);
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

	function customer_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getCustomerIdAuto($post['q']);
			echo json_encode($json);
		}
	}
	function job_orderid_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getJobOrderIdAuto($post['q']);
			echo json_encode($json);
		}
	}


	function customer_project_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);
			// die();
			$post['project_id'] = array_key_exists('project_id', $post) ? $post['project_id'] : '';
			
			$json = $this->Base_model->getCustomerProjects($post);
			echo json_encode($json);
		}
	}
	
	function department_name_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post); die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getDepartmentAuto($post['q']);
			// print_r($json );die();
			echo json_encode($json);
		}
	}
	function department_job_name_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['search'] = element('search', $post) ? $post['search'] : '';
			$post['job_order_name'] = element('job_order_name', $post) ? $post['job_order_name'] : '';
			$post['job_order_id']= $this->Base_model->getJobId($post['job_order_name']);
			$json = $this->Base_model->getDepartmentJobAuto($post);
			echo json_encode($json);
		}
	}
	function department_ajax() {

		if ($this->input->is_ajax_request()) {
			$this->load->model("Settings_model");
			$json = $this->Settings_model->getDepartmentMaster();
			
			echo json_encode($json);
		}
	}

	function category_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getAllCategory($post['q']);
			echo json_encode($json);
		}
	} 

	function supplier_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getSupplierAjax($post['q']);
			echo json_encode($json);
		}
	}

	function material_receipt_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getMaterialReceiptAutoComplete($post['q']);
			echo json_encode($json);
		}
	}
	function item_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getItemAutoComplete($post['q']);
			echo json_encode($json);
		}
	}
	function sales_code_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getSalesAutoComplete($post['q']);
			echo json_encode($json);
		}
	}
	function item_with_name_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getItemAutoComplete($post['q'], '', $post);
			echo json_encode($json);
		}
	}function sample_with_name_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getSampleAutoComplete($post['q'], '', $post);
			// print_r($json);die();
			echo json_encode($json);
		}
	}
	function job_orderid_with_name_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$post['project_id'] = element('project_id', $post) ? $post['project_id'] : '';
			$json = $this->Base_model->getJobOrderIdNameAuto($post['q'], '', $post,$post['project_id']);
			echo json_encode($json);
		}
	}
	function terms_conditions_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post(); 
			$json = $this->Base_model->getTermsConditions($post);
			echo json_encode($json);
		}
	}
	function meeting_code_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getMeetingAutoComplete($post['q']);
			echo json_encode($json);
		}
	}
	
	function user_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);
			// die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getDesigneTypeAuto($post['q']);
			echo json_encode($json);
		}
	}
	function sales_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);
			// die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getSalesmanTypeAuto($post['q']);
			echo json_encode($json);
		}
	}

	function type_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getTypeMasterIdAuto($post['q']);
			echo json_encode($json);
		}
	}

	function consumable_receipt_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getConsumableReceiptAutoComplete($post['q']);
			echo json_encode($json);
		}
	}


	function consumable_issue_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getConsumableIssueAutoComplete($post['q']);
			echo json_encode($json);
		}
	}


	function consumable_damage_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getConsumableDamageAutoComplete($post['q']);
			echo json_encode($json);
		}
	}

	function consumable_return_ajax() {
		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getConsumableReturnAutoComplete($post['q']);
			echo json_encode($json);
		}
	}

	function employee_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getEmployeeAjax($post['q']);
			echo json_encode($json);
		}
	}


	function receipt_employee_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getReceiptEmployeeAjax($post['q']);
			echo json_encode($json);
		}
	}

	function return_employee_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Base_model->getReturnEmployeeAjax($post['q']);
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