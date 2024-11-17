<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Base_Controller 
{

	public function index()
	{ 
		$id = $this->input->get('id');
		if($id)
		{

			$extra = base_url()."signup/index/$id";
			header("Location: $extra");
			exit;
		}

		$data['title'] = lang('button_login'); 

		if($this->input->post("login") && $this->authenticate_user()){
			$user_name = $this->input->post("user_name"); 
			$user_id = $this->Base_model->getUserId($user_name); 

			$this->form_validation->set_rules('password', lang("text_password") , 'callback_check_database'); 
			$this->form_validation->set_message('check_database', lang('failed_there_is_no_user_exist')); 

			if ( $this->form_validation->run() ) 
			{ 
				$this->Base_model->insertIntoActivityHistory($user_id, $user_id, 'login');
				if($this->session->userdata('site_logged_in')['user_type'] == 'supervisor'){
					$this->redirect("", "delivery/read-delivery-code", true);
				}else{

					$this->redirect("", "dashboard", true);
				}

			}else {
				if($user_id){
					$this->Base_model->insertIntoActivityHistory($user_id, $user_id, 'login_attempt');
				}
			}
		}

		$this->loadView($data);
	}
	public function customer_login()
	{ 
		if (log_user_type()) {
			$this->redirect("", "dashboard", true);
		}
		$id = $this->input->get('id');
		if($id)
		{

			$extra = base_url()."signup/index/$id";
			header("Location: $extra");
			exit;
		}

		$data['title'] = lang('button_login'); 

		if($this->input->post("login") && $this->authenticate_user()){
			$user_name = $this->input->post("user_name"); 
			$user_id = $this->Base_model->getUserId($user_name); 

			$this->form_validation->set_rules('password', lang("text_password") , 'callback_check_customer_database');
			$this->form_validation->set_message('callback_check_customer_database', "Ooops!...Invalid username and password, Please try again.. "); 

			if ( $this->form_validation->run() ) 
			{ 
				$this->Base_model->insertIntoActivityHistory($user_id, $user_id, 'customer-login');
				if($this->session->userdata('site_logged_in')['user_type'] == 'supervisor'){
					$this->redirect("", "delivery/read-delivery-code", true);
				}else{
					$this->redirect("", "dashboard", true);
				}

			}else {
				if($user_id){
					$this->Base_model->insertIntoActivityHistory($user_id, $user_id, 'login_attempt');
				}
			}
		}

		$this->loadView($data);
	}

	private function authenticate_user() 
	{ 
		$this->form_validation->set_rules('user_name',  lang("text_username") , 'trim|required|strip_tags|htmlentities');
		$this->form_validation->set_rules('password', lang("text_password") , 'trim|required|strip_tags');

		$val_res = $this->form_validation->run();
		return $val_res;
	}

	function check_database() 
	{
		$flag = false; 
		$login_details = $this->input->post();  
		$user_name = $login_details['user_name'];
		$password = $login_details['password'];

		$login_details = $this->security->xss_clean( $login_details );

		$login_result = $this->Login_model->login( $user_name, $password );
		if ($login_result) { 
			$log_user_type = $login_result[0]->user_type;
			if( ( $log_user_type != 'admin' ) && $this->MAINTENANCE_MODE == 1){
				$this->redirect( '', 'under-maintenance', false );
			}

			$this->Login_model->setUserSessionDatas($login_result);
			$this->session->set_userdata('site_social_login', NULL);
			$flag = true;
		} else {
			$flag = false;
		}
		return $flag;
	}
	function check_customer_database() 
	{
		$flag = false; 
		$login_details = $this->input->post();  
		$user_name = $login_details['user_name'];
		$password = $login_details['password'];

		$login_details = $this->security->xss_clean( $login_details );

		$login_result = $this->Login_model->customer_login( $user_name, $password );


		if ($login_result) { 
			$log_user_type = $login_result[0]->user_type;
			if( ( $log_user_type != 'admin' ) && $this->MAINTENANCE_MODE == 1){
				$this->redirect( '', 'under-maintenance', false );
			}

			$this->Login_model->setUserSessionDatas($login_result);
			$this->session->set_userdata('site_social_login', NULL);
			$flag = true;
		} else {
			$this->form_validation->set_message(  __FUNCTION__ , lang('failed_there_is_no_user_exist'));
			return FALSE;
			
		}
		return $flag;
	}

	function logout() {

		$user_type = '';
		
		if ($this->hasSession()) { 
			$user_id = log_user_id();
			$this->Base_model->insertIntoActivityHistory( $user_id, $user_id,'logout' ); 
			$user_type=log_user_type();
		}

		foreach ($this->session->userdata as $key => $value) {
			if (strpos($key, 'site_') === 0) {
				$this->session->unset_userdata($key);
			}
		}
		if ($user_type=='customer') {
			$path = "login/customer-login";          
		}else
		$path = "login";          
		
		$msg = lang('successfully_logged_out');
		$this->redirect("$msg", $path, true);
	}

	public function session_out()
	{
		if($this->hasSession()){
			$this->redirect("", "dashboard", FALSE);
		}

		if( ! element( 'site_timeout_sess' ,$this->session->userdata())){
			$this->redirect("", "dashboard", FALSE);
		}

		$timeout_sess =  $this->session->userdata( 'site_timeout_sess' );

		$select_arr = ['user_photo'];
		$user_details = $this->Base_model->getUserDetails( $timeout_sess[ 'user_id'], $select_arr );  

		$data['title'] = lang('unlock'); 
		$timeout_sess['user_photo'] = $user_details['user_photo'];
		$data['timeout'] = $timeout_sess;

		if( $this->input->post("login") && $this->validate_timeout( ) ){
			$this->redirect( '', "dashboard", true );
		} 
		if( $this->input->post( 'logout' )){
			$this->redirect( '', "logout", true );
		} 
		$this->loadView($data);
	}

	private function validate_timeout() 
	{
		$this->form_validation->set_rules('password', lang("password") , 'trim|required|strip_tags|callback_valid_password'); 
		$this->form_validation->set_message('valid_password', lang('incorrect_password_entered')); 
		$val_res = $this->form_validation->run(); 
		return $val_res;
	}

	public function valid_password() 
	{
		$flag = false; 
		$password = $this->input->post('password');
		$login_details = $this->security->xss_clean( $password );

		$timeout_sess =  $this->session->userdata( 'site_timeout_sess' );
		$login_result = $this->Login_model->login( $timeout_sess['user_name'], $password );

		if ($login_result) {
			$this->Login_model->setUserSessionDatas($login_result);
			$this->session->set_userdata('site_social_login', NULL);
			$this->session->unset_userdata( 'site_timeout_sess' );
			$flag = true;
		}
		return $flag;
	}

	public function under_maintenance()
	{
		$data['title'] = lang('under_maintenance');  
		$this->loadView($data);
	}
	
	public function forgot()
	{
		if($this->hasSession()){
			$this->redirect("", "dashboard", FALSE);
		}

		if ($this->input->post('forgot') && $this->verify_forgot_pass()) {
			$post_arr = $this->input->post(); 
			$user_name = $post_arr["user_name"];
			$email = $post_arr["email"];
			$user_id = $this->Base_model->getUserId($user_name);

			$user_email = $this->Base_model->getUserInfoField( 'email', $user_id );

			if ($user_email == $email) {   
				$keyword = $this->Login_model->getKeyWord($user_id);

				$mail_arr = array(
					'user_id' => $user_id,
					'keyword' => $keyword,
					'email' => $email,
				);
				$this->load->model('Mail_model');
				$this->Mail_model->sendEmails('forgot_password', $mail_arr); 

				$this->redirect( lang('please_check_mail_for_reset_password'), "login", TRUE);
			} else { 
				$this->redirect( lang('failed_user_email_not_match'), 'forgot', FALSE);
			}
		} 
		$data['title'] = lang('forgot');  
		$this->loadView($data);
	}

	private function verify_forgot_pass() { 
		$this->form_validation->set_rules('user_name', lang('username'), 'trim|required|is_exist[login_info.user_name]');
		$this->form_validation->set_rules('email', lang('email'), 'trim|required|is_exist[user_info.email]');
		$result =  $this->form_validation->run();
		return $result;
	}


	function reset($keyword_encode ="") 
	{
		if($this->hasSession()){
			$this->redirect("", "dashboard", FALSE);
		}
		if($keyword_encode == "" || !$this->Login_model->keywordAvailable($keyword_encode))
		{
			$msg = lang('text_invalid_url');
			$this->redirect($msg, 'login.php', FALSE);
		}

		if ($this->input->post('reset_password') && $this->validate_reset_pass()) {
			$post_arr = $this->input->post();

			$keyword = $post_arr["keyword_encode"];
			$new_password = $post_arr["new_password"];
			$confirm_password = $post_arr["confirm_password"];
			if($keyword == "" || !$this->Login_model->keywordAvailable($keyword))
			{
				$msg = lang('text_invalid_url');
				$this->redirect($msg, 'login', FALSE);
			}
			$user_id = $this->Login_model->getUserIdFromKeyword($keyword);
			if($user_id == "")
			{
				$msg = lang('text_invalid_url');
				$this->redirect($msg, 'login', FALSE);
			}
			else
			{
				$this->load->model('Member_model');
				$this->config->load('bcrypt');
				$this->load->library('bcrypt');
				$hashed_password = $this->bcrypt->hash_password($confirm_password);

				$update = $this->Member_model->updatePassword($hashed_password, $user_id);
				$res = $this->Login_model->updateKeywordStatus($user_id,$keyword);
                //$res1 = $this->Member_model->sendPasswordMail($user_id,$new_password);

				if ($update) {
					$this->Base_model->insertIntoActivityHistory($user_id, $user_id,'reset_password');              
					$msg = lang('text_password_updated_successfully');
					$this->redirect($msg, 'login.php', TRUE);
				} else {
					$msg = lang('text_error_on_password_updation');
					$this->redirect($msg, 'login.php', FALSE);
				}
			}
		}     

		$data["keyword_encode"]= $keyword_encode;
		$data["title"] = lang('button_reset');
		$this->loadView($data);       
	}

	function validate_reset_pass() {

		$password_length = value_by_key('password_length');

		$this->form_validation->set_rules('new_password', lang('new_password'), 'trim|required|min_length['. $password_length .']|alpha_numeric');
		$this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'trim|required|min_length['. $password_length .']|matches[new_password]');
		$result =  $this->form_validation->run();
		return $result;
	}


	public function insert_contact()
	{
		header('Content-type: application/json');
		$post_arr = $this->input->post();
		$admin_user_id = $this->Base_model->getAdminId();
		$name = $post_arr['name'];
		$mobile = $post_arr['mobile'];
		$email_id = $post_arr['email'];
		$subject = $post_arr['subject'];
		$message = $post_arr['message'];

		$mail_message = "You have got new contact request:<br><br>
		Name : $name<br>
		Mobile : $mobile<br>
		Email ID : $email_id<br>
		Subject : $subject<br>
		Message : $message<br><br>
		";

		$this->load->model('Mail_model');
		$mail_arr = array(
			'user_id' => $admin_user_id,
			'mail_message' => $mail_message,
		);
		$res = $this->Mail_model->sendEmails("contact_form",$mail_arr);

		if($res)
		{
			$status = array(
				'type'=>'success',
				'message'=>'Your email has been sent,our team will contact you soon!'
			);
		}
		else
		{
			$status = array(
				'type'=>'error',
				'message'=>'Error on sending contact us details,please try again..!'
			);
		}
		echo json_encode($status);
		die;
	}


	function check_captcha() 
	{ 
		$recaptcha = $this->input->post('g-recaptcha-response');
		$this->load->library('recaptcha');
		if (!empty($recaptcha)) 
		{
			$response = $this->recaptcha->verifyResponse($recaptcha);
			if (isset($response['success']) && $response['success'] === true){
				return true;
			}
		}
		return false;
	}
	public function customer_job_order_confirmation($enc_id='')
	{
		$status='pending';
		$this->load->model('Jobs_model');
		if ($enc_id) {
			$id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$data['jobs']=element(0,$this->Jobs_model->getAllJobOrders($id));
			$data['id']=$id;

		}
		if ($this->input->post('submit')) {
			if ($this->input->post('submit')=='confirm') {
				$status='approved';
			}
			else if($this->input->post('submit')=='reject'){
				$status='rejected';
			}
			$res=$this->Jobs_model->updateCustomerJobStatus($status,$id);
			if ($res) {
				$customer_status=$this->Jobs_model->getJobOrderField('customer_status',$id);
				$admin_status=$this->Jobs_model->getJobOrderField('admin_status',$id);
				if($customer_status=='approved' && $admin_status=='approved'){
					$update_ws=$this->Jobs_model->updateWorkStatus('approved',$id);
					$update_ds=$this->Jobs_model->updateDeptWorkStatus('progressing',$id);
				}
				$this->redirect("Job Order status updated successfully","login",TRUE);
			}
			else
				$this->redirect("Error On changing Job Order status ","login",FALSE);
		}

		$data['title'] = "Job Order Confirmation"; 
		$this->loadView($data);
	}
	public function admin_job_order_confirmation($enc_id='')
	{
		$status='pending';
		$this->load->model('Jobs_model');
		if ($enc_id) {
			$id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$data['jobs']=element(0,$this->Jobs_model->getAllJobOrders($id));
			$data['id']=$id;

		}
		if ($this->input->post('submit')) {
			if ($this->input->post('submit')=='confirm') {
				$status='approved';
			}
			else if($this->input->post('submit')=='reject'){
				$status='rejected';
			}
			$res=$this->Jobs_model->updateAdminJobStatus($status,$id);
			if ($res) {
				$customer_status=$this->Jobs_model->getJobOrderField('customer_status',$id);
				$admin_status=$this->Jobs_model->getJobOrderField('admin_status',$id);
				if($customer_status=='approved' && $admin_status=='approved'){
					$update_ws=$this->Jobs_model->updateWorkStatus('approved',$id);
					$update_ds=$this->Jobs_model->updateDeptWorkStatus('progressing',$id);
				}
				$this->redirect("Job Order status updated successfully","login",TRUE);
			}
			else
				$this->redirect("Error On changing Job Order status ","login",FALSE);
		}

		$data['title'] = "Job Order Confirmation"; 
		$this->loadView($data);
	}

	public function customer_forgot()
	{
		$data['title'] = 'Forgot Password';
		if($this->hasSession()){
			$this->redirect("", "dashboard", FALSE);
		}
		$this->load->model('Customer_model');

		if ($this->input->post('forgot') && $this->validate_customer_forgot_password()) {
			$post_arr = $this->input->post(); 
			$email = $post_arr["email"];
			$email_exist = $this->Customer_model->check_exists('email',$post_arr['email']);
			if ($email_exist) {   
				$customer_id = $this->Customer_model->getEmailtoCustomerID($post_arr['email']);
				if($customer_id){
					$keyword = $this->Login_model->getKeyWordForgot($customer_id);
					$customer_name = $this->Base_model->getCustomerInfoField('name',$customer_id);
					$mail_arr = array(
						'customer_id' => $customer_id,
						'keyword' => $keyword,
						'email' => $email,
						'customer_name' => $customer_name,
					);
					$this->load->model('Mail_model');
					$this->Mail_model->sendEmails('customer_forgot_password', $mail_arr); 

					$this->redirect( lang('please_check_mail_for_reset_password'), "login/customer-login", TRUE);
				} else { 
					$this->redirect( 'Customer not exist', 'login/customer-forgot', FALSE);
				}
			}else { 
				$this->redirect( 'Customer Email not exist', 'login/customer-forgot', FALSE);
			}
		} 
		$this->loadView($data);
	}

	public function validate_customer_forgot_password()
	{
		$this->form_validation->set_rules('email','E-mail','trim|required|valid_email');
		$result = $this->form_validation->run();
		return $result;
	}

	function customer_reset($keyword_encode ="") 
	{
		if($this->hasSession()){
			$this->redirect("", "dashboard", FALSE);
		}
		if($keyword_encode == "" || !$this->Login_model->keywordAvailable($keyword_encode))
		{
			$msg = lang('text_invalid_url');
			$this->redirect($msg, 'login/customer-login', FALSE);
		}

		if ($this->input->post('reset_password') && $this->validate_reset_customer_pass()) {
			$post_arr = $this->input->post();

			$keyword = $post_arr["keyword_encode"];
			$new_password = $post_arr["new_password"];
			$confirm_password = $post_arr["confirm_password"];
			if($keyword == "" || !$this->Login_model->keywordAvailable($keyword))
			{
				$msg = lang('text_invalid_url');
				$this->redirect($msg, 'login/customer-login', FALSE);
			}
			$customer_id = $this->Login_model->getCustomerIdFromKeyword($keyword);
			if($customer_id == "")
			{
				$msg = lang('text_invalid_url');
				$this->redirect($msg, 'login/customer-login', FALSE);
			}
			else
			{
				$this->load->model('Member_model');
				$this->config->load('bcrypt');
				$this->load->library('bcrypt');
				$hashed_password = $this->bcrypt->hash_password($confirm_password);
				
				$update = $this->Member_model->updateCustomerPassword($hashed_password, $customer_id);
				$res = $this->Login_model->updateCustomerKeywordStatus($customer_id,$keyword);
                //$res1 = $this->Member_model->sendPasswordMail($user_id,$new_password);

				if ($update) {
					$this->Base_model->insertIntoActivityHistory($customer_id, $customer_id,'customer_reset_password');  
					$msg = lang('text_password_updated_successfully');
					$this->redirect($msg, 'login/customer-login', TRUE);
				} else {
					$msg = lang('text_error_on_password_updation');
					$this->redirect($msg, 'login/customer-login', FALSE);
				}
			}
		}     

		$data["keyword_encode"]= $keyword_encode;
		$data["title"] = lang('button_reset');
		$this->loadView($data);       
	}

	function validate_reset_customer_pass() {

		$password_length = value_by_key('password_length');

		$this->form_validation->set_rules('new_password', lang('new_password'), 'trim|required|min_length['. $password_length .']|alpha_numeric');
		$this->form_validation->set_rules('confirm_password', lang('confirm_password'), 'trim|required|min_length['. $password_length .']|matches[new_password]');
		$result =  $this->form_validation->run();
		return $result;
	}


	function generatePdf(){
		$this->load->library('Pdf');
		$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		
		$tcpdf->SetCreator(PDF_CREATOR);
		$tcpdf->SetAuthor('Pine Tree');
		$tcpdf->SetTitle('Delivery Details');
		$tcpdf->SetSubject('Delivery');
		$tcpdf->SetKeywords('TCPDF, PDF, example, test, guide');
		
		$tcpdf->setFooterData(array(0,65,0), array(0,65,127));

		$tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		$tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$tcpdf->SetHeaderMargin(1);
		$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		$tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		if (@file_exists(dirname(__FILE__).'/lang/eng.php'))
		{
			require_once(dirname(__FILE__).'/lang/eng.php');
			$tcpdf->setLanguageArray($l);
		}

		$tcpdf->setFontSubsetting(true);

		$tcpdf->AddPage();

		$tcpdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,197,198), 'opacity'=>1, 'blend_mode'=>'Normal'));

		$site_logo = base_url('assets/images/logo/').$this->data[ 'site_details' ]['logo'];

		// $set_html = <<<EOD
		// <table> 
		// <tbody> 
		// <tr>
		// <td >
		// <span style="font-size:12px">
		// PROJECT DETAILS
		// </span>
		// <br>
		// <span style="font-size:9px">
		// Code: 12321   
		// </span>
		// <br>
		// <span style="font-size:9px">
		// Name: fsadfsda
		// </span>
		// <br>
		// <span style="font-size:9px">
		// Status: 1vfdsa
		// </span> 
		// </td>
		// <td align= "right">
		// <span style="font-size:12px">
		// DELIVERY DETAILS
		// </span>
		// <br>
		// <span style="font-size:9px">
		// Delivery Code: 12321
		// </span>
		// <br>
		// <span style="font-size:9px">
		// Delivery Person: vscsa
		// </span>
		// <br>
		// <span style="font-size:9px">
		// Vehicle: 123e  
		// </span> 
		// <br>
		// <span style="font-size:9px">
		// Status: vfdsa
		// </span> 
		// </td>
		// </tr> 
		// </tbody>
		// </table>
		// EOD;

		// echo $set_html;
		// die();
		$tcpdf->writeHTMLCell(0, 0, '', '', $set_html, 0, 1, 0, true, '', true);

		return $attachmentString= $tcpdf->Output('Delivery-details.pdf', 'I');



	}
	
	public  function showroom_items_print($enc_id='')
	{ 
		$data['title']='Meeting Minutes';
		$data['date'] = date('Y-m-d H:i:s');  
		$this->load->model('Sample_model');
		$id = $this->Base_model->encrypt_decrypt('decrypt',$enc_id);
		$data['id']=$id;
		$data['enc_id']=$enc_id;
		$search_arr = [
			'ordering_category' => true,
		]; 
		$details= $this->Login_model->getPrintMeetingDetails($id,$search_arr);
		// print_r($details);die();
				// print_r($details);die();
		if($enc_id){
			if($this->input->post('submit')=='submit'){
				$contents="";
				$images="";
				$sub_total=0;
				foreach ($details[0]['items'] as $v) {
					$contents=$contents . '
					<tr mc:repeatable="">
					<td>
					'.$v['code'] .'
					</td>
					<td>
					'.$v['note'] .'
					</td>
					<td>
					'.$v['category_name'] .'
					</td>
					<td>
					'.$v['lprice'] .'
					</td>
					<td>

					</tr>
					'; 
					$sub_total = $sub_total + $v['sprice'];
				} 

				$attachments = [];
				// print_r($details[0]['images']);die();
				foreach ($details[0]['images'] as $file) {
					array_push($attachments, './assets/images/meeting/'.$file['image']);
				}
				// print_r($attachments);die();
				$mail_arr = array(
					'email' => $details[0]['customer_email'],
					'name' => $details[0]['og_name'],
					'designer' => $details[0]['user_name_string'],
					'salesman' => $details[0]['salesman_name'],
					'code' =>$contents,
					'subtotal' =>$sub_total,
					'images' =>$images,


				);
				$this->load->model('Mail_model');
				
				$send = $this->Mail_model->sendEmails('email_info', $mail_arr, $attachments);
				if($send)
				{

					// $this->redirect(", We connect you soon", "Showroom/meeting-mint", true);
					$msg = "Email sended Successfully";
					$this->redirect($msg,'showroom/meeting-mint',True);

				}
				else{
					// $this->redirect("Failed, Please try again", "Showroom/meeting-mint", false);
					$msg = "";

					$this->redirect($msg,'showroom/meeting-mint',false);

				}

			}
			if(element('0',$details)){
				$data['details']=$details; 
			}
		// print_r($details);die();

		}   
		$this->loadView($data);
	}
	
	public  function sample_master_details($enc_id)
	{ 
		$data['id']=$id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id); 
		$this->load->model('Sample_model');
		$info = $this->Sample_model->getAllSampleDetails($id);
		if( !empty($info) ){

			$info['lprice']=price_code($info['price']);
			$data['title'] = 'Sample Master Details';

		}else{
			$data['title'] = 'No details found';
		}
		$data['info'] = $info;
		$this->loadView($data);
		
		$this->loadView($data);
	}
}
