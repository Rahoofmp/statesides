<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Mail_model extends Base_Model 
{

    public function __construct() {
        parent::__construct();
    } 
    

    public function sendEmails($type = 'notification', $mail_arr = array(), $attachments=[]) 
    {

        // $user_id = $mail_arr["user_id"];
        // $user_email_id = $this->getUserInfoField('email', $user_id);
        $user_email_id =$mail_arr['email'];
        // echo $user_email_id;
        if ( $user_email_id =="" || !SEND_EMAIL)
        {

            $data["message"] = "Mail Settings is off!";
            return $data;
        }
        $this->load->library('Phpmailer', NULL, 'phpmailer');

        $this->load->model("Settings_model");
        $language_name = "english";
        $this->lang->load('mail', $language_name);

        $user_full_name = element('fullname', $mail_arr) ? $mail_arr['fullname'] : null;

        $site_info = $this->getCompanyInformation();
        $attachments = array();
        $attachmentString = element('attachmentString', $mail_arr)? $mail_arr['attachmentString']: '';
        $smtp_data = array();
        $mail_details = $this->getMailSettings();

        if ($mail_details['type'] == "smtp") {

            $smtp_data = array(
                "SMTPAuth" => $mail_details['smtp_authentication'],
                "SMTPSecure" => ($mail_details['smtp_protocol'] == "none") ? "" : $mail_details['smtp_protocol'],
                "Host" => $mail_details['smtp_host'],
                "Port" => $mail_details['smtp_port'],
                "Username" => $mail_details['smtp_username'],
                "Password" => $mail_details['smtp_password'],
                "Timeout" => $mail_details['smtp_timeout'],
                //"SMTPDebug" => 3 //uncomment this line to check for any errors
            );
        }

        if($type == "email_invite" || $type == "auto_responder"){

            $mailBodyDetails = $mail_arr['content'];
            $mail_altbody = html_entity_decode($mail_arr['content']);
        }
        else{

            $content = $this->getEmailContent($type);
            $mail_altbody = html_entity_decode($content);
            $mailBodyDetails = $mail_altbody; 
        }
        $mail_to = array("email" => $user_email_id, "name" => $user_full_name);
        $mail_from = array("email" => $site_info['email'], "name" => $site_info['name']);
        $mail_reply_to = $mail_from;
        $mail_subject = "Notification";

        $mailBodyHeaderDetails = $this->getHeaderDetails($site_info);

        if ($type == "registration") {

            $user_name = $this->getUserName($mail_arr['user_id']);            
            $mailBodyDetails = str_replace("{user_name}", $user_name, $mailBodyDetails);
            $mailBodyDetails = str_replace("{password}", $mail_arr["psw"], $mailBodyDetails);

            $mail_subject = "Welcome to our team";
        } 
        elseif ($type == "customer_registration") {        
            $mailBodyDetails = str_replace("{user_name}", $mail_arr['customer_username'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{password}", $mail_arr["psw"], $mailBodyDetails);

            $mail_subject = "Welcome to our team";
        } 
        elseif ($type == "customer_updation") {        
            $mailBodyDetails = str_replace("{username}", $mail_arr['customer_username'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{name}", $mail_arr["name"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{mobile}", $mail_arr["mobile"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{email}", $mail_arr["email"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{address}", $mail_arr["address"], $mailBodyDetails);

            $mail_subject = "Details Updated";
        } 
        elseif ($type == "customer_job_order_created") { 

            $mailBodyDetails = str_replace("{user_name}", $mail_arr['customer_name'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{enc_job_id}", $mail_arr['enc_job_id'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{assigned_dept}", $mail_arr["assigned_dept"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{assigned_dept}", $mail_arr["assigned_dept"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{order_id}", $mail_arr["order_id"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{job_name}", $mail_arr["name"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{requested_date}", $mail_arr["requested_date"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{project_name}", $mail_arr["project_name"], $mailBodyDetails);

            $mail_subject = "Job Order has been created...!";
        }
        elseif ($type == "admin_job_order_created") { 

            $mailBodyDetails = str_replace("{user_name}", $mail_arr['user_name'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{customer_name}", $mail_arr['customer_name'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{enc_job_id}", $mail_arr['enc_job_id'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{assigned_dept}", $mail_arr["assigned_dept"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{assigned_dept}", $mail_arr["assigned_dept"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{order_id}", $mail_arr["order_id"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{job_name}", $mail_arr["name"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{requested_date}", $mail_arr["requested_date"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{project_name}", $mail_arr["project_name"], $mailBodyDetails);

            $mail_subject = "A New Job Order created successfully...!";
        } 
        elseif ($type == "dept_supervisor_job_order_created") { 

            $mailBodyDetails = str_replace("{user_name}", $mail_arr['supervisor_username'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{customer_name}", $mail_arr['customer_name'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{enc_job_id}", $mail_arr['enc_job_id'], $mailBodyDetails);

            $mailBodyDetails = str_replace("{department_name}", $mail_arr["department_name"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{assigned_dept}", $mail_arr["dept_details"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{order_id}", $mail_arr["order_id"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{job_name}", $mail_arr["name"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{requested_date}", $mail_arr["requested_date"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{project_name}", $mail_arr["project_name"], $mailBodyDetails);

            $mail_subject = "A New Job Order been created in your department...!";
        } 
        elseif ($type == "register_sponsor") {

            $new_user_name =  $mail_arr["user_name"];            
            $mailBodyDetails = str_replace("{user_name}", $new_user_name, $mailBodyDetails);
            $mail_subject = "New Member";
        } 

        else if ($type == "internal_mail") {
            $user_full_name =  $mail_arr["fullname"];            
            $message =  $mail_arr["message"];            
            $mailBodyDetails = str_replace("{fullname}", $user_full_name, $mailBodyDetails);
            $mailBodyDetails = str_replace("{message}", $message, $mailBodyDetails);
            $mail_subject = "News Goldpot";
        } 
        else if ($type == "update_delivery_status") {       
            $mailBodyDetails = str_replace("{delivery_status}", $mail_arr['status'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{fullname}", $mail_arr['fullname'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{project_code}", $mail_arr['project_code'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{project_name}", $mail_arr['project_name'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{delivery_code}", $mail_arr['delivery_code'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{status}", $mail_arr['status'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{delivery_person}", $mail_arr['delivery_person'], $mailBodyDetails);
            
            $mail_subject = $mail_arr['subject'];

        } else if ($type == "package_create") {
            $mailBodyDetails = str_replace("{fullname}", $mail_arr['fullname'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{package}", $mail_arr['package'], $mailBodyDetails);
            $mailBodyDetails = str_replace("{project_name}", $mail_arr['project_name'], $mailBodyDetails);
            $mail_subject = "Package Created";
        }   
        else if ($type == "password") {
            $mailBodyDetails = str_replace("{password}", $mail_arr["password"], $mailBodyDetails);
            $mail_subject = "Password";
        } 
        else if ($type == "forgot_password") {
            $mailBodyDetails = str_replace("{keyword}", $mail_arr["keyword"], $mailBodyDetails);
            $mail_subject = lang("mail_reset_password_confirm");
        }  
        else if ($type == "customer_password") {
            $mailBodyDetails = str_replace("{password}", $mail_arr["password"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{fullname}", $mail_arr["fullname"], $mailBodyDetails);
            $mail_subject = "Customer Password";
        } 
        else if ($type == "customer_forgot_password") {
            $mailBodyDetails = str_replace("{keyword}", $mail_arr["keyword"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{customer_name}", $mail_arr["customer_name"], $mailBodyDetails);
            $mail_subject = 'Customer password reset confirmation';
        } 
        elseif ($type == "email_info") {
            $mailBodyDetails = str_replace("{name}", $mail_arr["name"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{designer}", $mail_arr["designer"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{salesman}", $mail_arr["salesman"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{code}", $mail_arr["code"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{subtotal}", $mail_arr["subtotal"], $mailBodyDetails);
            $mailBodyDetails = str_replace("{images}", $mail_arr["images"], $mailBodyDetails);
           
            // $mailBodyDetails = str_replace("{message}", $mail_arr["message"], $mailBodyDetails);
            
            // $mail_subject = $mail_arr['subject'];
            // print_r($mailBodyDetails);die();

            
        } 
        else if($type == "email_invite" || $type == "auto_responder"){

            $mailBodyDetails = $mail_altbody;
            $mail_subject = lang("mail_invite");

            if($type == "auto_responder")
                $mail_subject = lang("auto_responder");
        }


        $mailBodyDetails = str_replace("{server_ip}", $this->input->server('REMOTE_ADDR'), $mailBodyDetails);
        $mailBodyDetails = str_replace("{fullname}", $user_full_name, $mailBodyDetails);
        $mailBodyDetails = str_replace("{company_name}", $site_info['name'], $mailBodyDetails);
        $mailBodyDetails = str_replace("{company_address}", $site_info['address'], $mailBodyDetails);
        $mailBodyDetails = str_replace("{company_email}", $site_info['email'], $mailBodyDetails);
        $mailBodyDetails = str_replace("{company_phone}", $site_info['phone'], $mailBodyDetails); 

        $mailBodyFooterDetails = $this->getFooterDetails($site_info);
        $mailBodyFooterDetails = str_replace("{user_email}", $user_email_id, $mailBodyFooterDetails);
        $mail_full_content = $mailBodyHeaderDetails . $mailBodyDetails . $mailBodyFooterDetails ;
        // $this->rollback();
        // print_r($mail_full_content);die();

        $send_mail = $this->phpmailer->send_mail($mail_from, $mail_to, $mail_reply_to, $mail_subject, $mail_full_content, $mail_altbody, $mail_details['type'], $smtp_data, $attachments, $attachmentString);


        if (!$send_mail['status']) {
            $data["message"] = "Error: " . $send_mail['ErrorInfo'];
        } else {
            $data["message"] = "Message sent correctly!";
        }
        return $data;
    }

    public function getMailSettings(){
        $smtp_details = array();
        $this->db->select('*');
        $this->db->from('mail_settings');
        $query = $this->db->get();
        foreach($query->result_array() as $row)
        {
            $smtp_details = $row; 
        }
        return $smtp_details;
    }

    public function getHeaderDetails($site_info) {
        $site_logo = $site_info['logo'];
        $company_name = $site_info['name'];

        $mailBodyHeaderDetails = '<!DOCTYPE html PUBLIC >
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>'. $company_name .'</title>
        </head>
        <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
        <center>
        <table>
        <tbody><tr style="margin:0">
        <td class="m_-6385192254922120283container" style="box-sizing:border-box;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;vertical-align:top;display:block;width:100%;max-width:600px;margin:0 auto;padding:0" width="100%" valign="top">

        <div class="m_-6385192254922120283content" style="box-sizing:border-box;display:block;width:100%;margin:0 auto;padding:0">



        <br>
        <table class="m_-6385192254922120283card" style="box-sizing:border-box;border-collapse:collapse;width:100%;background-color:#ffffff;border:1px solid #e6e7e8" width="100%" bgcolor="#ffffff">
        <tbody><tr style="margin:0">
        <td style="box-sizing:border-box;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;vertical-align:top;padding:0" valign="top">

        <table class="m_-6385192254922120283header-twocol" style="box-sizing:border-box;border-collapse:collapse;width:100%;border-bottom-width:1px;border-bottom-color:#e6e7e8;border-bottom-style:solid" width="100%">
        <tbody><tr style="margin:0">
        <td style="box-sizing:border-box;font-family:Open Sans,Helvetica,sans-serif;font-size:11px;vertical-align:middle;padding:20px" width="60%" valign="middle" align="left">
        <img src="'.assets_url().'images/logo/logo.png" alt="'. $company_name .'" style="display:block" class="CToWUd">
        </td>

        <td style="box-sizing:border-box;font-family:Open Sans,Helvetica,sans-serif;font-size:11px;vertical-align:middle;padding:20px" width="40%" valign="middle" align="right">
        <a href="'.base_url().'" style="box-sizing:border-box;color:#53565a;text-decoration:none;text-transform:uppercase;font-size:11px;letter-spacing:1px;line-height:1" target="_blank" >MY ACCOUNT</a>
        </td>

        </tr>
        </tbody></table>
        </td>
        </tr>

        <tr class="m_-6385192254922120283center-on-narrow" style="margin:0">
        <td class="m_-6385192254922120283pad-big" style="box-sizing:border-box;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;vertical-align:top;padding:8%" valign="top">';
        return $mailBodyHeaderDetails;
    }

    function getEmailContent($type='NA'){
        $content  =NULL;
        $this->db->select('content');
        $this->db->where('type', $type);
        $query = $this->db->get('mail_contents');
        foreach ($query->result_array() as $row) {
            $content = $row['content'];
        }

        if ($type == "registration") {
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_congratulation") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{fullname},</strong>
            <br>
            <br>
            <strong>'. lang("mail_getting_started") .' :</strong> '. lang("text_congratulations_on_your_decision") .'
            <br />
            '. lang("text_para_2") .'
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <tbody>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("user_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {user_name}
            </th> 
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            '. lang("password") .'
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {password}
            </th>
            </tr>
            </tbody>
            </table>

            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'. base_url() .'" target="_blank">Access Account</a>
            </h5>
            </p>';
        }
        else if ($type == "customer_registration") {
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_congratulation") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{fullname},</strong>
            <br>
            <br>
            <strong>'. lang("mail_getting_started") .' :</strong> '. lang("text_congratulations_on_your_decision") .'
            <br />
            '. lang("text_para_2") .'
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <tbody>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("user_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {user_name}
            </th> 
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            '. lang("password") .'
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {password}
            </th>
            </tr>
            </tbody>
            </table>

            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'. base_url() .'customer-login" target="_blank">Access Account</a>
            </h5>
            </p>';
        } 
        else if ($type == "customer_updation") {
            $mailBodyDetails = '<h3 style="color:#53565a;">Details Updated </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{name},</strong>
            <br>
            <br>
            <strong>Updated Details are listed here,
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <tbody>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            Customer Username 
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {username}
            </th> 
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            Phone Number
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {mobile}
            </th>
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            Address
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {address}
            </th>
            </tr>
            </tbody>
            </table>

            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'. base_url() .'customer-login" target="_blank">Access Account</a>
            </h5>
            </p>';
        } 
        else if ($type == "customer_job_order_created") {
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_congratulation") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{user_name},</strong>
            <br>
            <br>
            <strong>'. lang("mail_getting_started") .' :</strong> '. lang("text_job_has_been_created_for_project") .'
            <br />
            '. lang("job_order_details") .'
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="0">
            <tbody>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("order_id") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {order_id}
            </th> 
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            '. lang("job_name") .'
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {job_name}
            </th>
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            '. lang("delivery_requested_date") .'
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {requested_date}
            </th>
            </tr>
            </tbody>
            </table>

            <br /><h4>
            '. lang("assigned_dept") .'</h4>
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <tbody>
            <tr>
            <th>#</th>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("department_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >'. lang("short_description").'

            </th> 
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >'. lang("order_description").'

            </th> 
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            '. lang("estimated_working_hrs").'

            
            </th> 
            </tr>

            {assigned_dept}
            </tbody>
            </table>

            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'. base_url() .'customer-job-order-confirmation/{enc_job_id}" target="_blank">Click Here to confirm Your Job Order</a>
            </h5>
            </p>';
        } 
        else if ($type == "admin_job_order_created") {
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_congratulation") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{user_name},</strong>
            <br>
            <br>
            '. lang("text_job_has_been_created_for_project") .'
            <br />
            '. lang("job_order_details") .'
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="0">
            <tbody>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("customer_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {customer_name}
            </th> 
            </tr>
            <tr>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("project_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {project_name}
            </th> 
            </tr>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("order_id") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {order_id}
            </th> 
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            '. lang("job_name") .'
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {job_name}
            </th>
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            '. lang("delivery_requested_date") .'
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {requested_date}
            </th>
            </tr>
            </tbody>
            </table>

            <br /><h4>
            '. lang("assigned_dept") .'</h4>
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <tbody>
            <tr>
            <th>#</th>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("department_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >'. lang("short_description").'

            </th> 
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >'. lang("order_description").'

            </th> 
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            '. lang("estimated_working_hrs").'

            
            </th> 
            </tr>
            
            {assigned_dept}
            </tbody>
            </table>

            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'. base_url() .'admin-job-order-confirmation/{enc_job_id}" target="_blank">Click Here to confirm Your Job Order</a>
            </h5>
            </p>';
        }
        else if ($type == "dept_supervisor_job_order_created") {
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_congratulation") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{user_name},</strong>
            <br>
            <br>
            '. lang("text_job_has_been_created_for_dept") .'
            <br />
            '. lang("job_order_details") .'
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="0">
            <tbody>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("customer_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {customer_name}
            </th> 
            </tr>
            <tr>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("project_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {project_name}
            </th> 
            </tr>
            <tr>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("order_id") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            {order_id}
            </th> 
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            '. lang("job_name") .'
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {job_name}
            </th>
            </tr>
            <tr mc:repeatable="">
            <th  mc:edit="data_table_content00" valign="top">
            '. lang("delivery_requested_date") .'
            </th>
            <th  mc:edit="data_table_content01" valign="top">
            {requested_date}
            </th>
            </tr>
            </tbody>
            </table>

            <br /><h4>
            '. lang("assigned_dept") .'</h4>
            <br />
            <table width="100%" cellspacing="0" cellpadding="10" border="1">
            <tbody>
            <tr>
            <th>#</th>
            <th scope="col"  mc:edit="data_table_heading00" width="25%" valign="top">
            '. lang("department_name") .'
            </th>
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >'. lang("short_description").'

            </th> 
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >'. lang("order_description").'

            </th> 
            <th scope="col" mc:edit="data_table_heading01" width="25%" valign="top" >
            '. lang("estimated_working_hrs").'

            
            </th> 
            </tr>
            
            {assigned_dept}
            </tbody>
            </table>
            </p>';
        } 

        else if ($type == "internal_mail") { 

            $mailBodyDetails = '
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{fullname},</strong>
            <br>
            <br>
            {message}
            <br>
            <br>


            </h5>
            </p>';
        }


        else if ($type == "password") { 
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_password_updated") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{fullname},</strong>
            <br>
            <br>
            '. lang("mail_password_para_1") .'
            <br />
            Your new login password is : <strong>{password}</strong>
            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'. base_url() .'" target="_blank">'. lang("button_login") .'</a>
            </h5></p>';
        } 
        else if ($type == "update_delivery_status") { 
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("project_delivery_details") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{fullname},</strong>


            <br>
            The following packages are updated the status with <strong><i>{delivery_status}</i></strong>
            <br />
            <br>
            Project Details As follows,
            <br>
            <br>Delivery Code:<b>{delivery_code}</b>
            <small><br>Project Code:{project_code}
            <br>Project Name:{project_name}
            <br>Delivery Person:{delivery_person}
            <br>Status:{status}
            </small>
            <br>
            <br>
            * For any support please email us on delivery@pinetreelane.com and we shall contact you back.
            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">

            </h5></p>';
        } 
        else if ($type == "package_create") { 
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("project_package_created") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{fullname},</strong>
            <br>
            <br> '. lang("package") .'   <strong>{package}</strong>
            '. lang("created") .''. lang("project") .'  <strong> {project_name}</strong>
            <br />
            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            </h5></p>';
        }
        else if ($type == "forgot_password") { 
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_reset_password_confirm") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{fullname},</strong>
            <br>
            <br>
            '. lang("mail_forgot_password_para_1") .'
            <br />
            '. lang("mail_forgot_password_para_2") .'
            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'.base_url().'login/reset/{keyword}" target="_blank">'. lang("button_reset_password") .'</a>
            </h5>
            </p>';
        }
        else if ($type == "customer_forgot_password") { 
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_reset_password_confirm") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{customer_name},</strong>
            <br>
            <br>
            '. lang("mail_forgot_password_para_1") .'
            <br />
            '. lang("mail_forgot_password_para_2") .'
            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'.base_url().'login/customer-reset/{keyword}" target="_blank">'. lang("button_reset_password") .'</a>
            </h5>
            </p>';
        }
        else if ($type == "customer_password") { 
            $mailBodyDetails = '<h3 style="color:#53565a;">'. lang("mail_password_updated") .' </h3>
            <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
            '. lang("mail_dear") .' <strong>{fullname},</strong>
            <br>
            <br>
            '. lang("mail_password_para_1") .'
            <br />
            Your new login password is : <strong>{password}</strong>
            <h5 class="m_-4808757542982372404red" style="font-size:11px;letter-spacing:1px;text-transform:uppercase;color:#c01818">
            <a href="'. base_url() .'" target="_blank">'. lang("button_login") .'</a>
            </h5></p>';
        } 
        else if ($type == "email_info") { 
          $mailBodyDetails = '<h3 style="color:#53565a;">  {name},</h3>
          <p style="color:#53565a;line-height:180%;font-family:Open Sans,Helvetica,sans-serif;font-size:14px;font-weight:normal;margin:0 0 15px;padding:0">
          designer: {designer}
          <br>
          salesman : {salesman} 
          <br>
          <p>Total price:{subtotal}</p>
          <br>
          <br>

          <table class="templateDataTable" width="100%" cellspacing="0" cellpadding="10" border="0">

          <tbody>

          <tr>
          <th>Sample Code</th>
          <th>Specification</th>
          <th>Product</th>
          <th>Price</th>
          </tr>
          {code}
          </tbody>
          </table>
          <table class="text-center" width="100%" cellspacing="0" cellpadding="10" border="0" align="center">
          <tbody>
          <tr>
            <td>Attachments : {images}
            </td>
          </tr>
          </tbody>
          </table>
          </p>';
      }
      return $mailBodyDetails;

  }
  public function getFooterDetails($site_info) {

    $mailBodyFooterDetails ='
    </td>
    </tr>
    </tr>
    </tbody></table></div>
    </td>
    </tr>
    </tbody>
    </table>';

    return $mailBodyFooterDetails;
}



    // public function getUserMessages($user_id, $mail_id='') {
    //     $messages = array();
    //     $this->db->select('*');
    //     $this->db->from('internal_mail_details');
    //     $this->db->where('status', 'yes');
    //     $this->db->where('to_user', $user_id);
    //     if($mail_id)
    //         $this->db->where('id', $mail_id);
    //     $this->db->order_by('date', 'desc');
    //     $query = $this->db->get();
    //     foreach ($query->result_array() as $row) 
    //     {
    //         $row['flag'] = 1;
    //         $row['full_name'] = $this->getUserInfoField('first_name', $row['from_user']) . ' '. $this->getUserInfoField('second_name', $row['from_user']);
    //         $row['user_name'] = $this->getUserName($row['from_user']);
    //         $message = stripslashes($row['message']);
    //         $row['message'] = $message;
    //         $messages[] = $row;
    //     }
    //     return $messages;
    // }


    // public function getMessagesSent($user_id) {
    //     $messages = array();
    //     $this->db->select('*');
    //     $this->db->from('internal_mail_details');
    //     $this->db->where('status', 'yes');
    //     $this->db->where('from_user', $user_id);
    //     $this->db->order_by('date', 'desc');
    //     $query = $this->db->get();
    //     $i = 0;
    //     foreach ($query->result_array() as $row) {
    //         $row['user_name'] = $this->getUserName($row['to_user']);
    //         $message = html_entity_decode($row['message']);
    //         $row['message'] = $message;
    //         $messages[] = $row;  
    //     }
    //     return $messages;
    // }


//     public function sendMessage($user_id, $subject, $message, $date, $from_user,$type="") {
//         $data = array(
//             'to_user' => $user_id,
//             'from_user' => $from_user,
//             'subject' => $subject,
//             'message' => $message,
//             'date' => $date,
//             'type' => $type
//         );

// // send email

//         $mail_arr = $data;
//         $mail_arr['user_id'] = $data['to_user'];
//         $mail_arr['fullname'] = $this->getFullName($data['to_user']);
//         $this->sendEmails("internal_mail",$mail_arr);

//         $res = $this->db->insert('internal_mail_details', $data);
//         return $res;
//     }


    // public function deleteMessage($delete_id, $user_id){
    //     $this->db->set('status', 'deleted');
    //     $this->db->where('id', $delete_id);
    //     $this->db->update('internal_mail_details');  
    //     return $this->db->affected_rows();
    // }

public function getReplyMessage($id) {
    $details = array();
    $this->db->select('*');
    $this->db->from('internal_mail_details');
    $this->db->where('id', $id);
    $this->db->where('status', 'yes');
    $query = $this->db->get();
    foreach($query->result_array() as $row){
        $details = $row;
    }
    return $details;
}


public function getAllUsers() {

    $user_arr = array();
    $this->db->select('user_id');
    $this->db->from('login_info');
    $this->db->where('status', '1');
    $this->db->where('user_type !=', 'admin');
        // $this->db->order_by('user_id', 'asc');
    $query = $this->db->get();
    foreach ($query->result() as $row) {
        $user_arr[] = $row->user_id;
    }
    return $user_arr;
}

public function changeReadStatus($table_id){

    $this->db->set('read_status' , 'yes');
    $this->db->where('id' , $table_id);
    $this->db->limit(1);
    $result = $this->db->update('internal_mail_details');
    return $result;

}




}
