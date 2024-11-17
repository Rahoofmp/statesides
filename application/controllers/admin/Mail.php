<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mail extends Base_Controller {

    function __construct()
    {
        parent::__construct();
    }

    function inbox() {

        $data['title'] = lang('text_mail_management');
        $messages = $this->Mail_model->getUserMessages(log_user_id()); 
        $data['messages'] = $messages;

        $this->loadView($data);
    }

    function compose_mail() { 

        $data['title'] = lang('text_compose_mail');
        $from_id = log_user_id();
        $date = date('Y-m-d H:i:s');  
        $result = FALSE;
        if ($this->input->post('send') && $this->validate_compose_mail()) {

            $data = $this->input->post(); 

            $subject = $data['subject']; 
            $message = htmlentities($data['message']);
            $message =  nl2br($message);
            $mail_status = ($this->input->post('status', TRUE));

            if ($mail_status == 'single') {

                $type = 'single';
                $data['sent_to'] = 'single';
                $user_name = $data['user_id'];
                $invalid_users = NULL;

                if($to_user_id = $this->Base_model->getUserId($user_name))
                {
                    $result = $this->Mail_model->sendMessage($to_user_id,  $subject, $message, $date, $from_id, $type);
                }
            }
            else if ($mail_status == 'all') {
                $data['sent_to'] = 'all';
                $type = 'all';

                $active_users = $this->Mail_model->getAllUsers(); 

                foreach ($active_users as $index => $to_user_id) {  
                    $result = $this->Mail_model->sendMessage($to_user_id, $subject, $message, $date, $from_id, $type);
                }
            }
            if ($result) { 
                $data = serialize($data);
                
                $this->Base_model->insertIntoActivityHistory($from_id, $from_id, $type, $data);

                $msg = lang('message_send_successfully');
                $this->redirect($msg, "mail/compose-mail", TRUE);

            } else {
                $msg = lang('error_on_message_sending');
                $this->redirect($msg, "mail/compose-mail", TRUE);
            }
        }
        $this->loadView($data);

    }


    function validate_compose_mail() { 

        $mail_status = ($this->input->post('status', TRUE));
        
        $this->form_validation->set_rules('status', lang('status'), 'trim|required|strip_tags');

        if ($mail_status == "multiple") {
            $this->form_validation->set_rules('user_names', lang('username'), 'trim|required|strip_tags');
        }
        $this->form_validation->set_rules('subject', lang('subject'), 'trim|required|strip_tags');
        $this->form_validation->set_rules('message', lang('message'), 'trim|required');
        $validate_form = $this->form_validation->run(); 
        return $validate_form;
    }

    function read_message() {
        $message_id = $this->input->post('message_id');
        $messages = $this->Mail_model->getUserMessages(log_user_id(), $message_id);

        $data['messages'] = $messages;


        $result = $this->Mail_model->updateMessageStatus($message_id);
        $this->loadView($data);
    }

    function deleteMessage($message_id,$path = "mail-sent") { 
        if ($this->Mail_model->deleteMessage($message_id, log_user_id())) {  
            $this->redirect(lang('message_deleted_successfully'), "mail/$path", TRUE);
        } else { 
            $this->redirect(lang('message_deletion_failed'), "mail/$path", TRUE);
        } 
    }

    function mail_sent() {
        $sent_messages = $this->Mail_model->getMessagesSent(log_user_id()); 
        $data['sent_messages'] = $sent_messages;
        $data['title'] = lang('text_mail_management');
        $this->loadView($data);
    }

    function reply_mail($mail_id = '') 
    {

        $data['title'] = lang('text_mail_reply');
        $mail_details = $this->Mail_model->getReplyMessage($mail_id); 
        if(count($mail_details) <= 0) {            
            $this->redirect(lang('invalid_mail_id'), 'mail/inbox', FALSE);
        }
        $reply_user_id = $mail_details['from_user'];
        $reply_user = $this->Base_model->getUserName($reply_user_id);
        $reply_msg = $mail_details['subject']; 
        $data['reply_user'] = $reply_user; 
        if (preg_match('/([\w\-]+\:[\w\-]+)/', $reply_msg)) {
            $string = explode(':', $reply_msg);
            $reply_msg = $string[1];
        }
        $reply_msg = str_replace('%20', ' ', $reply_msg);
        $reply_msg = trim($reply_msg); 
        $data['reply_msg'] = $reply_msg;
        $log_user_id = log_user_id();
        if ($this->input->post('send') && $this->validate_reply_mail()) {
            $user_name = $this->input->post('user_id1');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');
            $message = addslashes($message);
            $user_id = $this->Base_model->getUserId($user_name);
            $date = date('Y-m-d H:i:s');

            $res = $this->Mail_model->sendMessage($user_id, $subject, $message, $date,$log_user_id,'single');
            $msg = '';
            if ($res) {
                $msg = lang('message_send_successfully');
                $this->redirect($msg, 'mail/inbox', TRUE);
            } else {
                $msg = lang('error_on_message_sending');
                $this->redirect($msg, 'mail/reply_mail', FALSE);
            }
        }
        $this->loadView($data);
    }

    function validate_reply_mail() {
        $this->form_validation->set_rules('subject', lang('subject'), 'trim|required|strip_tags');
        $this->form_validation->set_rules('message', lang('message'), 'trim|required');
        $validate_form = $this->form_validation->run();
        return $validate_form;
    }

    public function readEmail(){
        $result = FALSE;
        $post_arr = $this->input->post();
        $table_id = $post_arr['user_id'];
        $this->Mail_model->changeReadStatus($table_id);
        echo $table_id;
    }

}
