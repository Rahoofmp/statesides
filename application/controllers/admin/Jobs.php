<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}
	function create_job()
	{ 

		$details  = $post_arr=[];
		
		if( $this->input->post('add_job')&& $this->validate_create_jobs($this->input->post()) )
		{
			$post_arr=$this->input->post();
			$this->Jobs_model->begin();
			$post_arr['customer_name']=$this->Base_model->getCustomerInfoField('customer_username',$post_arr['customer_id']);

			$ins=$this->Jobs_model->insertJobOrder($post_arr);
			if ($ins) {
				$this->load->model('Mail_model');

				$assigned_dept='';
				$post_arr['enc_job_id']=$this->Base_model->encrypt_decrypt('encrypt',$ins);
				$post_arr['project_name']=$this->Base_model->getProjectName($post_arr['project_id']);

				$counter=$post_arr['dept_counter']+1;
				
				for ($i=1; $i< $counter; $i++) { 
					$department_name=$this->Base_model->getDepartmentName($post_arr['department_id'.$i]);
					$dept='
					<tr>
					<td class="text-center">'.$i.'</td>
					<td class="text-center">'.$department_name.'</td>
					<td class="text-center">'.$post_arr['short_description'.$i].'</td>
					<td class="text-center">'.$post_arr['order_description'.$i].'</td>

					<td class="td-actions text-center">'. $post_arr['estimated_working_hrs'.$i].'</td>
					</tr>  
					';
					$supervisor=$this->Base_model->getDepartmentSupervisor($post_arr['department_id'.$i]);
					if ($supervisor) {
						$post_arr['department_name']=$department_name;
						$post_arr['email']=$this->Base_model->getUserInfoField('email',$supervisor);
						$post_arr['dept_details']=$dept;
						$post_arr['supervisor_username']=$this->Base_model->getUserName($supervisor);
						$send_mail = $this->Mail_model->sendEmails('dept_supervisor_job_order_created', $post_arr);
					}
					$assigned_dept.=$dept;

				}

				$post_arr['email']=$this->Base_model->getCustomerInfoField('email',$post_arr['customer_id']);
				

				$post_arr['assigned_dept']=$assigned_dept;
				
				// $send_mail = $this->Mail_model->sendEmails('customer_job_order_created', $post_arr);
				$post_arr['email']=$this->Base_model->getUserInfoField('email',log_user_id());
				$post_arr['user_name']=log_user_name();
				$send_mail = $this->Mail_model->sendEmails('admin_job_order_created', $post_arr);

				$this->Jobs_model->commit();
				$this->redirect("Job Successfully Added","jobs/create-job",TRUE);
			}
			else{
				$this->Jobs_model->rollback();
				$this->redirect("Job Creation failed ",'jobs/create-job',FALSE);

			}

			// print_r($post_arr);
			// die();
			
		}
		$data['order_id']=$this->Jobs_model->getMaxJobId()+1001;
		$this->load->model("Settings_model");
		$data['departments'] = $this->Settings_model->getDepartments();
		// print_r($data['departments']);die();
		$data['details'] = $details; 

		$data['title'] = "Job Order"; 
		$this->loadView($data);
	}


	
	function edit_job($enc_id='')
	{ 
		$data['tab']=1;
		if ($enc_id) {
			$id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$data['jobs']=element(0,$this->Jobs_model->getAllJobOrders($id));
			$data['dept_ids']=array_column($data['jobs']['department'], 'department_id');

			// print_r($data['dept_ids']);
			// die();

			$data['id']=$id;
			$data['enc_id']=$enc_id;

		}
		$details  = $post_arr=[];
		if( $this->input->post('update_dept_job') && $this->validate_create_jobs($this->input->post()) )
		{
			$post_arr = $this->input->post();
			$jobs=$data['jobs'];
			$order_id=$jobs['order_id'];
			$post_arr['order_id']=$order_id;
			// print_r($post_arr['order_id']);die();
			$this->Jobs_model->begin();
			$update = $this->Jobs_model->updateJobOrder($post_arr,$id);
			if ($update) {
				$this->Base_model->insertIntoActivityHistory($post_arr['customer_id'], log_user_id(), 'job order Updated',serialize($post_arr));
				$this->Jobs_model->commit();
				$this->redirect("Job Updated Added","jobs/edit-job/$enc_id",TRUE);
			}
			else{
				$this->Jobs_model->rollback();
				$this->redirect("Job Updation failed ","jobs/edit-job/$enc_id",FALSE);

			}
			$data['tab']=2;
		}
		
		$this->load->model("Settings_model");
		// $data['departments'] = $this->Jobs_model->getCustomerProjectsDetails($data['jobs']['customer_id']);
		$data['departments'] = $this->Settings_model->getDepartments();
		$data['details'] = $details; 

		// print_r($data['departments']);die();
		$data['title'] = "Job Order"; 
		$this->loadView($data);
	}
	public function order_details($enc_id='')
	{
		$data['title'] = 'Order Details';
		$job_id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		$data['job_details'] = $this->Jobs_model->getJobOrderDetails($job_id);
		// print_r($data['job_details']);die();
		$this->loadView($data);
	}
	function update_day_progress(){
		if ($this->input->post('today_progress') && $this->validate_edit_day_progress() ) {
			$post_arr = $this->input->post();
			$this->Jobs_model->begin();
			$updated = $this->Jobs_model->updateDayProgress($post_arr);

			$this->session->set_flashdata('job_order_id', $post_arr['job_order_id']);
			$this->session->set_flashdata('department_id', $post_arr['department_id']);
			// $data['job_order_id']=$post_arr['job_order_id'];
			// $data['department_id']=$post_arr['department_id'];
			
			if ($updated) {
				$this->Jobs_model->commit();
				$this->redirect("Day progress updated","jobs/day-progress", TRUE );
			} else{
				$this->Jobs_model->rollback();
				$this->redirect("Updating day progress is failed ","jobs/day-progress",FALSE);
			}
		}
	}

	function delete_day_progres($action='',$enc_id='',$enc_job_id='',$enc_department_id='')
	{
		if($action == 'delete')
		{
			$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$job_order_id = $this->Base_model->encrypt_decrypt('decrypt',$enc_job_id);
			$department_id = $this->Base_model->encrypt_decrypt('decrypt',$enc_department_id);

			$this->Jobs_model->begin();			
			$delete = $this->Jobs_model->delete_day_progress($id);

			if($delete){
				$this->Jobs_model->commit();
				$msg = 'Successfully deleted';

				$this->session->set_flashdata('department_id',$department_id);
				$this->session->set_flashdata('job_order_id',$job_order_id);

				$this->redirect($msg,'jobs/day-progress',TRUE);

			}else{
				$this->Jobs_model->rollback();
				$msg = 'Failed, please try again..';

				$this->session->set_flashdata('department_id',$department_id);
				$this->session->set_flashdata('job_order_id',$job_order_id);

				$this->redirect($msg,'jobs/day-progress', FALSE);

			}  
		}
	}
	public  function job_list()
	{
		$data['title']="Job List";
		
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			if(element('project_id',$post_arr))
			{

				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']);
			}
			if(element('customer_id',$post_arr))
			{
				$this->load->model('Project_model');
				$post_arr['cus_name'] = $this->Project_model->getCustomerName($post_arr['customer_id']); 
			}

		} else{
			$post_arr['limit'] = '10';
			$post_arr['order_by'] = 'DESC';
		}
		$data['post_arr']=$post_arr;
		// $data['job_list']=$this->Jobs_model->getAllJobOrders('',$post_arr);


		$this->loadView($data);
	}	

	private function validate_create_jobs($post_arr='')
	{
		$this->form_validation->set_rules('order_id', 'Job Order ID', 'trim|required');
		$this->form_validation->set_rules('project_id', 'Project', 'trim|required');
		$this->form_validation->set_rules('name', 'Job Name', 'trim|required');
		$this->form_validation->set_rules('customer_id', 'Customer', 'trim|required');
		$this->form_validation->set_rules('requested_date', 'Job Requested Date', 'trim|required');
		if (array_key_exists('add_job', $post_arr)) {

			$this->form_validation->set_rules('department_id1', 'Project', 'trim|required');
			$this->form_validation->set_rules('short_description1', 'Short Description', 'trim|required');
			$this->form_validation->set_rules('order_description1', 'Job Order Description', 'trim|required');
			$this->form_validation->set_rules('estimated_working_hrs1', 'Estimated Duration', 'trim|required');
			// $this->form_validation->set_rules('actual_working_hrs1', 'Actual Working Hours', 'trim|required');
		}
		// }
		

		$result =  $this->form_validation->run();
		return $result;
	}
	private function validate_dept($post_arr='')
	{

		$this->form_validation->set_rules('department_id', 'Project', 'trim|required');
		$this->form_validation->set_rules('short_description', 'Short Description', 'trim|required');
		$this->form_validation->set_rules('order_description', 'Job Order Description', 'trim|required');
		$this->form_validation->set_rules('estimated_working_hrs', 'Estimated Duration', 'trim|required');
		// $this->form_validation->set_rules('actual_working_hrs', 'Actual Working Hours', 'trim|required');

		

		$result =  $this->form_validation->run();
		return $result;
	}


	private function validate_order_approval()
	{

		$this->form_validation->set_rules('order_id[]', 'Order Id', 'trim|required');
		$this->form_validation->set_rules('admin_status', 'Status', 'trim|required|in_list[confirm,reject]');
		$result =  $this->form_validation->run();
		return $result;
	}

	public function order_approval()
	{
		$data['title'] = 'Order Approval';
		if ($this->input->post('search')) {
			$post_arr = $this->input->post();
			if(element('project_id',$post_arr))
			{

				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']);
			}
			if(element('customer_name',$post_arr))
			{
				$this->load->model('Project_model');
				$post_arr['cus_name'] = $this->Project_model->getCustomerName($post_arr['customer_name']); 
			} 
		}else{
			$post_arr['limit'] = '10';
			
		}
		$post_arr['admin_status'] = 'pending';
		$data['post_arr'] = $post_arr; 

		$data['job_list']=$this->Jobs_model->getAllOrderApprovalDetailsAjax( $post_arr);
		if($this->input->post('change_status') && $this->validate_order_approval())
		{
			$post = $this->input->post();
			$update_data = [];
			foreach ($post['order_id'] as $key => $job_id) {
				$temp_data['id'] = $job_id;
				$temp_data['admin_status'] = $post['admin_status'];
				$update_data[] = $temp_data;
			} 

			if($update_data){	
				$update = $this->Jobs_model->updateJobStatus($update_data);

				if($update)
				{
					$msg = 'Admin Status updated successfully';
					$this->redirect($msg,'jobs/order-approval',TRUE);
				}else{
					$msg = 'Error on admin status updation';
					$this->redirect($msg,'jobs/order-approval',FALSE);
				}
			}
			
		}
		$this->loadView($data);
	}
	function day_progress()
	{
		$data['title']="Job List";
		$this->load->model('Jobs_model');
		$job_order_name = NULL;
		$job_order_id = NULL;
		$data['department_id']=NULL;
		$data['department']=NULL;
		if ($this->input->post('search') ) {

			$this->form_validation->set_rules('job_order_name', 'Job Order ID', 'trim|required|is_exist[job_orders.order_id]');


			if (element('job_order_name',$this->input->post())) {
				$data['job_order_name'] = $this->input->post('job_order_name');
				$data['job_order_id']= $this->Base_model->getJobId($data['job_order_name']);
			}

			if (element('department',$this->input->post())) {
				$data['department'] = $this->input->post('department');
				$data['department_name']=$this->Base_model->getDepartmentName($data['department']);
				
			}
			else
			{
				$data['department']=0;
			}
			if ($this->form_validation->run()) { 

				$data['job_details']= $this->Jobs_model->getJobOrderDetails( $data['job_order_id']);
			// print_r($data['job_order_id'] );die();
				$search_arr['job_order_id'] = $data['job_order_id'];
				$search_arr['department_id'] = $data['department'];
				$data['job_day_progress'] = $this->Jobs_model->getJobDayProgress( $search_arr);

				// print_r($data['department_id']);
				// print_r($data['department_name']);
				// // print_r($data['job_day_progress']);
				// die();
				$data['progress_color'] = ['danger', 'success', 'info', 'warning'];
			} 


		}elseif(
			$this->session->flashdata('job_order_id'))
		{
			// $department_id = $this->session->flashdata('department_id');
			$job_order_id = $this->session->flashdata('job_order_id');
			$job_order_name= $this->Base_model->getJobOrderId($job_order_id);

			$data['job_details']= $this->Jobs_model->getJobOrderDetails( $job_order_id ); 
			// $search_arr['department_id'] = $department_id;
			$search_arr['job_order_id'] = $job_order_id;

			$data['job_day_progress'] = $this->Jobs_model->getJobDayProgress( $search_arr); 
			$data['job_order_name']=$job_order_name ;
			$data['job_order_id']=$job_order_id ;

			// $department_name= $this->Base_model->getDepartmentName($department_id);
			// $data['department_name'] = $department_name ;
			// $data['department_id'] = $department_id ;

			$data['progress_color'] = ['danger', 'success', 'info', 'warning'];
		}

		if ($this->input->post('today_progress')) {

			$post_arr = $this->input->post();
			// print_r($post_arr);die();
			if($this->validate_day_progress())
			{

				if(element('department',$post_arr))
				{

					$post_arr['department_id']=$post_arr['department'];
			// $department_name=$this->Base_model->getDepartmentName($post_arr['department_id']);

				}
			// print_r($post_arr);die();
				$post_arr['job_order_id']= $this->Base_model->getJobId($post_arr['job_order_name']);
			// $data['department']=$post_arr['department'];
				$this->Jobs_model->begin();
				$inserted = $this->Jobs_model->insertDayProgress($post_arr);
 	// die();
				if ($inserted) {
					$this->Jobs_model->commit();
					$this->session->set_flashdata('job_order_id', $post_arr['job_order_id']);
				// $this->session->set_flashdata('department_id', $post_arr['department_id']);
					$this->redirect("Day progress is added","jobs/day-progress", TRUE );
				} else{
					$this->Jobs_model->rollback();
					$this->session->set_flashdata('job_order_id', $post_arr['job_order_id']);
					$this->session->set_flashdata('department_id', $post_arr['department']);
					$this->redirect("Adding day progress is failed ","jobs/day-progress",FALSE);
				}
			}
			else
			{

				$post_arr['job_order_id']= $this->Base_model->getJobId($post_arr['job_order_name']);
				$this->session->set_flashdata('job_order_id', $post_arr['job_order_id']);
				// $this->session->set_flashdata('department_id', $post_arr['department_id']);
				$this->redirect("Adding day progress is failed ","jobs/day-progress",FALSE);
			}

			
			// $data['department']=$post_arr['department'];

		} 
		
		if ($this->input->post('change_status')) {

			$post_arr = $this->input->post();
			if($this->validate_dept_job_status())
			{
				$post_arr['department_id']=$post_arr['department_change_id'];
				$this->Jobs_model->begin();
				$updated = $this->Jobs_model->updateDepartmentWorkStatus($post_arr);
				if ($updated) {
					$this->Jobs_model->commit();
					$this->session->set_flashdata('job_order_id', $post_arr['job_order_id']);
					$this->session->set_flashdata('department_id', $post_arr['department_id']);
					$this->redirect("Status Updated","jobs/day-progress", TRUE );
				} else{
					$this->Jobs_model->rollback();
					$this->session->set_flashdata('job_order_id', $post_arr['job_order_id']);
				// $this->session->set_flashdata('department_id', $post_arr['department_id']);
					$this->redirect(" Status Updation  is failed ","jobs/day-progress",FALSE);
				}

			}

			// $post_arr['job_order_id']= $this->Base_model->getJobId($post_arr['job_order_name']);

			else 
			{
				$this->session->set_flashdata('job_order_id', $post_arr['job_order_id']);
				$this->redirect(" Status Updation  is failed","jobs/day-progress",FALSE);
			}

			
		} 
		
		// $data['job_order_name']= $job_order_name;
		// $data['job_order_id']= $job_order_id;
		$this->loadView($data);
	} 

	function edit_day_progress()
	{
		if ($this->input->is_ajax_request() && $this->input->method() == 'post') {
			$data['title']="Job List";
			$post_arr = $this->input->post(); 
			$data['job_order_id'] = $post_arr['job_order_id'];
			// print_r($data['job_order_id']);die();
			$data['progress_id'] = $post_arr['progress_id']; 
			$data['department_id'] = $post_arr['department_id']; 
			$data['day_progress'] = $this->Jobs_model->getJobDayProgress( $data,'' );
			$response['success'] = TRUE; 
			$response['html'] = 
			$this->smarty->view("admin/jobs/edit_day_progress.tpl", $data, TRUE);
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}else{
			die('No direct Access');
		} 
	} 
	private function validate_edit_day_progress()
	{ 
		$this->form_validation->set_rules('job_order_id', 'Job Order ID', 'trim|required|is_exist[job_orders.id]');
		$this->form_validation->set_rules('progress_id', 'Day Progress ID', 'trim|required|is_exist[day_progress.id]');
		$this->form_validation->set_rules('department_id', 'Department ID', 'trim|required|is_exist[department.id]');
		$this->form_validation->set_rules('today_status', 'Today Status', 'trim|required');
		$this->form_validation->set_rules('employees_worked', 'Employees Worked', 'trim|required');
		$this->form_validation->set_rules('worked_time', 'Worked time', 'trim|required|numeric|integer');
		$result =  $this->form_validation->run();
		return $result;
	}
	private function validate_day_progress()
	{
		$this->form_validation->set_rules('job_order_name', 'Job Order ID', 'trim|required|is_exist[job_orders.order_id]');
		$this->form_validation->set_rules('department', 'Department Name', 'trim|required|is_exist[department.id]');
		$this->form_validation->set_rules('today_status', 'Today Status', 'trim|required');
		$this->form_validation->set_rules('employees_worked', 'Employees Worked', 'trim|required');
		$this->form_validation->set_rules('worked_time', 'Worked time', 'trim|required|numeric|integer');
		// $this->form_validation->set_rules('time_span', 'Estimated Duration', 'trim|required'); 
		$result =  $this->form_validation->run();
		return $result;
	}
	private function validate_dept_job_status()
	{
		
		$this->form_validation->set_rules('department_change_id', 'Department Name', 'trim|required|is_exist[department.id]');
		$this->form_validation->set_rules('status', 'Department Status', 'trim|required');

		// $this->form_validation->set_rules('time_span', 'Estimated Duration', 'trim|required'); 
		
		$result =  $this->form_validation->run();
		// print_r($this->form_validation->error_array()); 		die();
		return $result;
	}
	function project_ajax() {

		if ($this->input->is_ajax_request()) {
			$post = $this->input->post();
			// print_r($post);
			// die();
			$post['q'] = element('q', $post) ? $post['q'] : '';
			$json = $this->Jobs_model->getCustomerProjectsAjax($post);
			echo json_encode($json);
		}
	}

	function save_departments() {
		if ($this->input->is_ajax_request() ) {
			$estimated_working_hrs = 0;
			$department_cost_sum = 0;

			if ( $this->validate_save_dept() ) {
				$post = $this->input->post();

				$departments = $this->Base_model->getDepartmentInfo();

				foreach ($post['data'] as $key => $value) {
					$post['data'][$key]['department_name']=$post['data'][$key]['department_id'];
					$post['data'][$key]['department_id']=$this->Base_model->getDepartmentID($value['department_id']);
					$estimated_working_hrs += $value['estimated_working_hrs'];


					$department_cost = $value['estimated_working_hrs']* $departments[$post['data'][$key]['department_id']]['cost_per_hour'];

					$department_cost = round( $department_cost, 2, PHP_ROUND_HALF_DOWN);

					$post['data'][$key]['department_cost'] = $department_cost;

					$department_cost_sum += $department_cost;

				} 

				$this->Jobs_model->begin();
				$inserted = $this->Jobs_model->insertDepartmentJobs( $post['data'], $post['package_id'] );

				if($inserted){
					//send mail
					$jobs=element(0,$this->Jobs_model->getAllJobOrders($post['package_id']));


					$this->load->model('Mail_model');
					$this->load->model('Project_model');

					

					foreach ($post['data'] as $key => $value) {
						$value['customer_name']=$this->Project_model->getCustomerName($jobs['customer_id']);
						$value['requested_date']=$jobs['requested_date'];
						$value['name']=$jobs['name'];
						$value['order_id']=$jobs['order_id'];
						$value['enc_job_id']=$this->Base_model->encrypt_decrypt('encrypt',$post['package_id']);
						$value['project_name']=$this->Base_model->getProjectName($jobs['project_id']);
						$department_name=$value['department_name'];
						$dept='
						<tr>
						<td class="text-center">1</td>
						<td class="text-center">'.$department_name.'</td>
						<td class="text-center">'.$value['short_description'].'</td>
						<td class="text-center">'.$value['order_description'].'</td>

						<td class="td-actions text-center">'. $value['estimated_working_hrs'].'</td>
						</tr>  
						';
						$supervisor=$this->Base_model->getDepartmentSupervisor($value['department_id']);
						if ($supervisor) {
							$value['department_name']=$department_name;
							$value['email']=$this->Base_model->getUserInfoField('email',$supervisor);
							$value['dept_details']=$dept;
							$value['supervisor_username']=$this->Base_model->getUserName($supervisor);
							$send_mail = $this->Mail_model->sendEmails('dept_supervisor_job_order_created', $value);
						}
						

					}
					
					$udpate_data = [
						'estimated_working_hrs' => $estimated_working_hrs,
						'department_cost' => $department_cost_sum,
						'job_order_id' => $post['package_id'],
					] ;


					$update_estimated_hrs=$this->Jobs_model->updateDepartmentJobOrderInfo( $udpate_data );

					$this->Jobs_model->commit();
					$response['success'] = TRUE;
					$response['msg'] = 'Successfully added the items...!';
					$this->set_session_flash_data( $response['msg'], $response['success']  );

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}else{
					$this->Jobs_model->rollback();
					$response['success'] = FALSE;
					$response['msg'] = 'Failed..! Please try again';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 

				}

			}else{
				$response['success'] = FALSE;
				$response['msg'] = implode(', ', $this->form_validation->error_array());
				$this->set_session_flash_data( $response['msg'], $response['success']  );
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}
	function save_departments_jobs() {
		if ($this->input->is_ajax_request() ) {
			$estimated_working_hrs = 0;
			$department_cost_sum = 0;

			if ( $this->validate_save_dept_jobs() ) {
				$post = $this->input->post();

				$this->Jobs_model->begin();
				$post['customer_name']=$this->Base_model->getCustomerInfoField('customer_username',$post['customer_id']);

				$ins=$this->Jobs_model->createJobs($post);
				if ($ins) {
					$post['package_id']=$ins;
					$this->load->model('Mail_model');

					$assigned_dept='';
					$post['enc_job_id']=$this->Base_model->encrypt_decrypt('encrypt',$ins);
					$post['project_name']=$this->Base_model->getProjectName($post['project_id']);
					$departments = $this->Base_model->getDepartmentInfo();
					foreach ($post['data'] as $key => $value) {
						$post['data'][$key]['department_name']=$post['data'][$key]['department_id'];
						$post['data'][$key]['department_id']=$this->Base_model->getDepartmentID($value['department_id']);
						$estimated_working_hrs += $value['estimated_working_hrs'];

						$department_cost = $value['estimated_working_hrs']* $departments[$post['data'][$key]['department_id']]['cost_per_hour'];

						$department_cost = round( $department_cost, 2, PHP_ROUND_HALF_DOWN);

						$post['data'][$key]['department_cost'] = $department_cost;

						$department_cost_sum += $department_cost;

					} 
					$inserted = $this->Jobs_model->insertDepartmentJobs( $post['data'], $post['package_id'] );

					if($inserted){

						$jobs=element(0,$this->Jobs_model->getAllJobOrders($post['package_id']));


						$this->load->model('Mail_model');
						$this->load->model('Project_model');
						$assigned_dept='';


						foreach ($post['data'] as $key => $value) 
						{
							$value['customer_name']=$this->Project_model->getCustomerName($jobs['customer_id']);
							$value['requested_date']=$jobs['requested_date'];
							$value['name']=$jobs['name'];
							$value['order_id']=$jobs['order_id'];
							$value['enc_job_id']=$this->Base_model->encrypt_decrypt('encrypt',$post['package_id']);
							$value['project_name']=$this->Base_model->getProjectName($jobs['project_id']);
							$department_name=$value['department_name'];
							$dept='
							<tr>
							<td class="text-center">1</td>
							<td class="text-center">'.$department_name.'</td>
							<td class="text-center">'.$value['short_description'].'</td>
							<td class="text-center">'.$value['order_description'].'</td>

							<td class="td-actions text-center">'. $value['estimated_working_hrs'].'</td>
							</tr>  
							';
							$assigned_dept.=$dept;
							$supervisor=$this->Base_model->getDepartmentSupervisor($value['department_id']);
							if ($supervisor) {
								$value['department_name']=$department_name;
								$value['email']=$this->Base_model->getUserInfoField('email',$supervisor);
								$value['dept_details']=$dept;
								$value['supervisor_username']=$this->Base_model->getUserName($supervisor);
								$send_mail = $this->Mail_model->sendEmails('dept_supervisor_job_order_created', $value);
							}


						}

						$udpate_data = [
							'estimated_working_hrs' => $estimated_working_hrs,
							'department_cost' => $department_cost_sum,
							'job_order_id' => $post['package_id'],
						] ;


						$update_estimated_hrs=$this->Jobs_model->updateDepartmentJobOrderInfo( $udpate_data );
						$this->Jobs_model->commit();
						$post['email']=$this->Base_model->getCustomerInfoField('email',$post['customer_id']);


						$post['assigned_dept']=$assigned_dept;

						// $send_mail = $this->Mail_model->sendEmails('customer_job_order_created', $post);
						$post['email']=$this->Base_model->getUserInfoField('email',log_user_id());
						$post['user_name']=log_user_name();
						$send_mail = $this->Mail_model->sendEmails('admin_job_order_created', $post);

						$response['success'] = TRUE;
						$response['msg'] = 'Successfully added the items...!';
						$this->set_session_flash_data( $response['msg'], $response['success']);

						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit();

					}else{
						$this->Jobs_model->rollback();
						$response['success'] = FALSE;
						$response['msg'] = 'Failed..! Please try again';
						$this->set_session_flash_data( $response['msg'], $response['success']  );
						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit(); 

					}
				}else{
					$this->Jobs_model->rollback();
					$response['success'] = FALSE;
					$response['msg'] = 'Failed..! Please try again';
					$this->set_session_flash_data( $response['msg'], $response['success']  );
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 


				}
			}	
		}
	}

	private function validate_save_dept() { 
		
		if(element('data', $this->input->post())){
			$_POST['data'] = json_decode($this->input->post('data'));
			$_POST['package_id'] =  $this->Base_model->encrypt_decrypt('decrypt', $_POST['id']);
			unset($_POST['id']);
			foreach ($_POST['data'] as $key => $value) {
				$_POST['data'][$key]['department_id'] = $value['1'];
				$_POST['data'][$key]['short_description'] = $value['2'];
				$_POST['data'][$key]['order_description'] = $value['3'];
				$_POST['data'][$key]['estimated_working_hrs'] = $value['4'];
				unset($_POST['data'][$key]['0']);
				unset($_POST['data'][$key]['1']);
				unset($_POST['data'][$key]['2']);
				unset($_POST['data'][$key]['3']);
				unset($_POST['data'][$key]['4']);
				unset($_POST['data'][$key]['5']);

				$department_id_index = 'department_id'.$key;
				$short_description_index = 'short_description'.$key;
				$order_description_index = 'order_description'.$key;
				$estimated_working_hrs_index = 'estimated_working_hrs'.$key;

				$_POST[$department_id_index] = $value['1'];
				$_POST[$short_description_index] = $value['2'];
				$_POST[$order_description_index] = $value['3'];
				$_POST[$estimated_working_hrs_index] = $value['4']; 

				$this->form_validation->set_rules('data[]', 'Data', 'required'); 

				$this->form_validation->set_rules( $department_id_index, "Department", 'trim|required|is_exist[department.name]');

				$this->form_validation->set_rules( $short_description_index, "Short description", 'trim|required');
				$this->form_validation->set_rules( $order_description_index, "Order description", 'trim|required');

				$this->form_validation->set_rules( $estimated_working_hrs_index, "Working Hours", 'trim|required|numeric|greater_than[0]');

			}
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}
		
		$res = $this->form_validation->run();

		return $res;
	}

	private function validate_save_dept_jobs() { 
		
		if(element('data', $this->input->post())){
			$_POST['data'] = json_decode($this->input->post('data'));
			foreach ($_POST['data'] as $key => $value) {
				$_POST['data'][$key]['department_id'] = $value['1'];
				$_POST['data'][$key]['short_description'] = $value['2'];
				$_POST['data'][$key]['order_description'] = $value['3'];
				$_POST['data'][$key]['estimated_working_hrs'] = $value['4'];
				unset($_POST['data'][$key]['0']);
				unset($_POST['data'][$key]['1']);
				unset($_POST['data'][$key]['2']);
				unset($_POST['data'][$key]['3']);
				unset($_POST['data'][$key]['4']);
				unset($_POST['data'][$key]['5']);

				$department_id_index = 'department_id'.$key;
				$short_description_index = 'short_description'.$key;
				$order_description_index = 'order_description'.$key;
				$estimated_working_hrs_index = 'estimated_working_hrs'.$key;

				$_POST[$department_id_index] = $value['1'];
				$_POST[$short_description_index] = $value['2'];
				$_POST[$order_description_index] = $value['3'];
				$_POST[$estimated_working_hrs_index] = $value['4']; 

				$this->form_validation->set_rules('data[]', 'Data', 'required'); 

				$this->form_validation->set_rules( $department_id_index, "Department", 'trim|required|is_exist[department.name]');

				$this->form_validation->set_rules( $short_description_index, "Short description", 'trim|required');
				$this->form_validation->set_rules( $order_description_index, "Order description", 'trim|required');

				$this->form_validation->set_rules( $estimated_working_hrs_index, "Working Hours", 'trim|required|numeric|greater_than[0]');

			}
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}
		// $name=$_POST['data2']['name'];
		// $order_id=$_POST['data2']['order_id'];
		// $project_id=$_POST['data2']['project_id'];
		// $customer_id=$_POST['data2']['customer_id'];
		// $requested_date=$_POST['data2']['requested_date'];
		$this->form_validation->set_rules('order_id','Order ID', 'trim|required|is_unique[job_orders.order_id]');
		$this->form_validation->set_rules('name', 'Job Order ID', 'trim|required');
		$this->form_validation->set_rules('project_id', 'Project', 'trim|required');
		$this->form_validation->set_rules('name', 'Job Name', 'trim|required');
		$this->form_validation->set_rules('customer_id', 'Customer', 'trim|required');
		$this->form_validation->set_rules('requested_date', 'Job Requested Date', 'trim|required');
		
		$res = $this->form_validation->run();

		return $res;
	}

	function day_progress_list($job_order_id='',$department_id='')
	{
		$data['title']="Job Progress List";

		$search_arr['job_order_id'] = $job_order_id;
		$search_arr['department_id'] = $department_id;

		$data['job_day_progress'] = $this->Jobs_model->getJobDayProgress( $search_arr, '');

		$data['progress_color'] = ['danger', 'success', 'info', 'warning'];
		$data['job_order_id'] = $search_arr['job_order_id'];
		$data['department_id'] = $search_arr['department_id'];

		$this->session->set_flashdata( 'job_order_id', $data['job_order_id'] );
		$this->session->set_flashdata( 'department_id', $data['department_id'] );

		$this->loadView($data);

	}
	function delete_department_job($enc_id,$dept_id){
		if ($enc_id && $dept_id) {

			$job_order_id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$departments_job_id=$this->Base_model->encrypt_decrypt('decrypt',$dept_id);
			
			$select = 'estimated_working_hrs, department_cost';
			$select_arr = $this->Jobs_model->getDepartmentJobsField( $select, $departments_job_id );

			$data['estimated_working_hrs'] = -$select_arr['estimated_working_hrs'];
			$data['department_cost'] = -$select_arr['department_cost'];
			$data['job_order_id'] = $job_order_id;

			$this->Jobs_model->begin();
			$del = $this->Jobs_model->deleteDepartmentJob( $departments_job_id );
			$update_departments_info = $this->Jobs_model->updateDepartmentJobOrderInfo( $data );
			if ($del && $update_departments_info) {
				$this->Jobs_model->commit();
				$this->redirect("Department Job deleted Successfully","jobs/edit-job/$enc_id",TRUE);
			}else{
				$this->Jobs_model->rollback();
				$this->redirect("Deletion Failed...!please try again later ","jobs/edit-job/$enc_id",false);

			}
		}

	}
	function edit_dept_job($enc_id,$dept_id)
	{ 
		
		if ($enc_id && $dept_id) {
			$job_id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$dept_id=$this->Base_model->encrypt_decrypt('decrypt',$dept_id);

			$this->load->model('Settings_model');

			$select = 'estimated_working_hrs, short_description,order_description,department_id';
			$select_arr= $this->Jobs_model->getDepartmentJobsField( $select, $dept_id );
			$data['select_arr']=$select_arr;
			$data['departments'] = $this->Settings_model->getDepartmentMaster();
			// print_r($data['select_arr']);die();
			


		}
		
		if( $this->input->post('update_dept_job'))
		{
			$post_arr = $this->input->post();
			$post_arr['job_id']=$job_id;
			$post_arr['department_id']=$select_arr['department_id'];
             // print_r($post_arr);die();
			$update = $this->Jobs_model->updateDepartmentJobDetail($post_arr);
            // die();

			if ($update) {
				
				$this->redirect("DepartmentJob Details Updated","jobs/edit-job/$enc_id",TRUE);
			}
			else{
				
				$this->redirect("DepartmentJob Details Updation failed ","jobs/edit-job/$enc_id",FALSE);

			}
			
		}
		
		
		$data['title'] = "Job Order"; 
		$this->loadView($data);
	}


	public function get_job_ajax() {
		
		
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();
			$count_without_filter = $this->Jobs_model->getJobCount();
			$count_with_filter = $this->Jobs_model->getAllJobOrder($post_arr,1);
			$result_data = $this->Jobs_model->getAllJobOrder($post_arr,'');
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
			// print_r($response); die();
			
		}
	}

}






