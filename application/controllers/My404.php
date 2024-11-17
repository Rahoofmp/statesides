<?php
class My404 extends Base_Controller
{
   public function __construct()
   {
       parent::__construct();
   }
   public function index()
   {
       $this->output->set_status_header('404');
       $data['title'] = '404 Error';
       $data['site_details'] = $this->data['site_details'];
       $this->load->view('err404', $data);    
   }
}