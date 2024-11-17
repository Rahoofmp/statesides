<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fix_issues extends Base_Controller 
{

	function __construct() {
		parent::__construct(); 
		
	}
	public function run_queries()
	{
		$this->Fix_issues_model->begin();
 		$insert_customer = $this->Fix_issues_model->runNewQueries();
		if($insert_customer)
		{
			$this->Fix_issues_model->commit();
			echo "Finished";die();
		}else{
			$this->Fix_issues_model->rollback();
			echo "Failed";die();
		}

	}

	public function insert_customer_details()
	{
		$this->Fix_issues_model->begin();
		$customer_details = $this->Fix_issues_model->getCustomerDetailsFromProject();
		$insert_customer = $this->Fix_issues_model->insertCustomerInfo($customer_details);
		if($insert_customer)
		{
			$this->Fix_issues_model->commit();
			echo "Finished";die();
		}else{
			$this->Fix_issues_model->rollback();
			echo "Failed";die();
		}

	}


	public function run_queries_third()
	{
		$this->Fix_issues_model->begin();
 		$insert_customer = $this->Fix_issues_model->runNewQueriesThird();
		if($insert_customer)
		{
			$this->Fix_issues_model->commit();
			echo "Finished";die();
		}else{
			$this->Fix_issues_model->rollback();
			echo "Failed";die();
		}

	}

	public function update_delivery_notes()
	{
		$this->Fix_issues_model->begin();
 		$insert_customer = $this->Fix_issues_model->runUpdateDeliveryNotes();
		if($insert_customer)
		{
			$this->Fix_issues_model->commit();
			echo "Finished";die();
		}else{
			$this->Fix_issues_model->rollback();
			echo "Failed";die();
		}

	}
	public function run_queries_fourth()
	{
		$this->Fix_issues_model->begin();
 		$insert_customer = $this->Fix_issues_model->runNewQueriesFourth();
		if($insert_customer)
		{
			$this->Fix_issues_model->commit();
			echo "Finished";die();
		}else{
			$this->Fix_issues_model->rollback();
			echo "Failed";die();
		}

	}


}

