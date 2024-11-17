<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_model extends Base_model {

    function __construct() {
        parent::__construct();

    }
    public function getCustomerProjectsDetails($customer_id, $items=true)
    {
        $details=[];
        $this->db->select('pj.project_name,pj.customer_name, pj.location,pj.id')
        ->select('pj.email, pj.date project_date, pj.status project_status')
        ->select('c.customer_username,c.name as cus_name,c.mobile as customer_mobile,c.email as customer_email')
        ->from('project pj')
        ->where('pj.customer_name',$customer_id)
        ->join('customer_info c', 'pj.customer_name = c.customer_id');

        $query = $this->db->get();

        foreach($query->result_array() as $row){
            $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );


            $details[] = $row;
        }
        return $details;
    }

    public function insertJobOrder($details='')
    {
        $counter=$details['dept_counter']+1;
        $dept_jobs = [];
        $departments = $this->Base_model->getDepartmentInfo();

        $estimated_working_hrs = 0;

        // print_r($departments);

        $department_cost_sum = 0;
        for ($i=1; $i< $counter; $i++) {  
            $dept_jobs[$i]['department_id'] = $details['department_id'.$i];
            $dept_jobs[$i]['short_description'] = $details['short_description'.$i];
            $dept_jobs[$i]['order_description'] = $details['order_description'.$i];
            $dept_jobs[$i]['estimated_working_hrs'] = $details['estimated_working_hrs'.$i];

            $estimated_working_hrs += $details['estimated_working_hrs'.$i]; 
            
            $department_cost = $details['estimated_working_hrs'.$i] * $departments[$details['department_id'.$i]]['cost_per_hour'];

            $department_cost = round( $department_cost, 2, PHP_ROUND_HALF_DOWN);
            $dept_jobs[$i]['department_cost'] = $department_cost;
            $department_cost_sum += $department_cost;

        }


        $this->db->set('order_id' , $details['order_id'])
        ->set('name' , $details['name'])
        ->set('date' , date('Y-m-d H:i:s'))
        ->set('requested_date' , $details['requested_date'])
        ->set('customer_id' , $details['customer_id'])
        ->set('project_id' , $details['project_id'])
        ->set('estimated_working_hrs' , $estimated_working_hrs)
        ->set('department_cost' , $department_cost_sum);

        $result = $this->db->insert('job_orders');
        if ($result) {
            $job_order_id= $this->db->insert_id();
            $this->insertDepartmentJobs($dept_jobs,$job_order_id);

            return $job_order_id;
        }
        return $result;
    }
    public function updateJobOrder($details='',$id='')
    { 
        $this->db->set('order_id' , $details['order_id']);
        $this->db->set('name' , $details['name']);
        $this->db->set('requested_date' , $details['requested_date']);
        $this->db->set('customer_id' , $details['customer_id']);
        $this->db->set('project_id' , $details['project_id']);
        $this->db->where('id' , $id);
        $result = $this->db->update('job_orders');

        return $result;
    }

    public function createJobs($details='')
    {
      $this->db->set('order_id' , $details['order_id'])
      ->set('name' , $details['name'])
      ->set('date' , date('Y-m-d H:i:s'))
      ->set('requested_date' , $details['requested_date'])
      ->set('customer_id' , $details['customer_id'])
      ->set('project_id' , $details['project_id']);
      $result = $this->db->insert('job_orders');
      $job_order_id= $this->db->insert_id();
      return $job_order_id;
  }

  public function insertDepartmentJobs( $dept_jobs, $job_order_id )
  {
    $result = FALSE;
    foreach ($dept_jobs as $key => $value) {
        $this->db->set('job_order_id' , $job_order_id);
        $this->db->set('department_id' , $value['department_id']);
        $this->db->set('short_description' , $value['short_description']);
        $this->db->set('order_description' , $value['order_description']);
        $this->db->set('estimated_working_hrs' , $value['estimated_working_hrs']);
        $this->db->set('department_cost' , $value['department_cost']);
        $result = $this->db->insert('department_jobs');
        if(!$result){
            return FALSE;
        }
    }


    return $result;
}  
public function getDepartmentDetails($department_id='',$job_order_id='')
{

    $details=array();
    $this->db->select('dj.*, dp.name department_name')
    ->from('department_jobs dj')
    ->join('department dp', 'dp.id = dj.department_id');
    if ($department_id) {
        $this->db->where('dj.department_id',$department_id);
    }
    if ($job_order_id) {
        $this->db->where('dj.job_order_id',$job_order_id);
    }

    $query = $this->db->get();    

    foreach($query->result_array() as $row){
        $row['enc_id']=$this->encrypt_decrypt('encrypt',$row['id']);

        $details[] = $row;
    }
    return $details;
}

public function getMaxJobId() {
    $id = NULL;
    $this->db->select_max('id');
    $this->db->from('job_orders');
    $this->db->limit(1);
    $query = $this->db->get();
    foreach ($query->result() as $row) {
        $id = $row->id;
    }
    return $id;
}
public function getDepartmentJobs( $search_arr=[] )
{
        // print_r($search_arr);die();
    $details=array();

    $this->db->select('dj.*')
    ->select('d.name department_name')
    ->select('jo.order_id, jo.name as job_name, jo.date, jo.requested_date ')
    ->select('jo.admin_status, jo.customer_status ')
    ->select('ci.customer_username')
    ->select('pj.project_name')
    ->from('department_jobs dj')
    ->join('department d', 'd.id = dj.department_id')
    ->join('job_orders jo', 'jo.id = dj.job_order_id')
    ->join('customer_info ci', 'ci.customer_id = jo.customer_id')
    ->join('project pj', 'pj.id = jo.project_id');

    if ( element('job_id', $search_arr ) ) {
        $this->db->where('dj.id', $search_arr['job_id']);
    }
    if ( element('project_name', $search_arr ) ) {
        $this->db->where('pj.project_name', $search_arr['project_name']);
    }
    if(element('job_name',$search_arr)){
        $this->db->where('jo.name',$search_arr['job_name']);
    }
    if (element('delivery_date',$search_arr)) {

        $this->db->where('jo.requested_date =', $search_arr['delivery_date']);
    }


    if (element('job_order_id', $search_arr)) {
        $this->db->where('dj.job_order_id', $search_arr['job_order_id']);
    }
    if (element('department_id', $search_arr)) {
        $this->db->where('dj.department_id', $search_arr['department_id']);
    }

    if (element('customer_id', $search_arr)) {
        $this->db->where('jo.customer_id', $search_arr['customer_id']);
    }
    if (element('order_id', $search_arr)) {
        $this->db->where('jo.order_id', $search_arr['order_id']);
    }

    if (element('order_from', $search_arr)) {
        $this->db->where("DATE_FORMAT(jo.date,'%Y-%m-%d') >=", $search_arr['order_from']);
    }

    if (element('order_to', $search_arr)) {
        $this->db->where("DATE_FORMAT(jo.date,'%Y-%m-%d') <=", $search_arr['order_to']);
    }


    if (element('limit', $search_arr)) {
        $this->db->limit($search_arr['limit']);
    }

    if (element('order_by', $search_arr)) {
        $this->db->order_by($search_arr['order_by'], 'DESC');
    }
    if (element('admin_status', $search_arr)) {
        $this->db->where('jo.admin_status', $search_arr['admin_status']);
    } 
    $query = $this->db->get();
        // echo $this->db->last_query();
        // die();
    foreach($query->result_array() as $row){
        $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['job_order_id'] );
        $details[] = $row;
    }
    return $details;

}

public function insertDepartmentJobOrder( $details=[], $job_order_id )
{
    $time_difference=0;
    $this->db->set('job_order_id' , $job_order_id);
    $this->db->set('department_id' , $details['department_id']);
    $this->db->set('short_description' , $details['short_description']);
    $this->db->set('order_description' , $details['order_description']);
    $this->db->set('estimated_working_hrs' , $details['estimated_working_hrs']);
            // $this->db->set('actual_working_hrs' , $details['actual_working_hrs']);
    $this->db->set('time_difference' , $time_difference);
    $result = $this->db->insert('department_jobs');
    return $result;

}

public function updateDepartmentJob( $details=[] )
{

    $time_difference=0;

    $this->db->where('job_order_id' , $details['job_id'])
    ->where('department_id' , $details['department_id'])
    ->set('short_description' , $details['short_description'])
    ->set('order_description' , $details['order_description'])
    ->set('estimated_working_hrs' , $details['estimated_working_hrs'])
    ->set('time_difference' , $time_difference);
    $result = $this->db->update('department_jobs');
// echo $this->db->last_query();die();
    if($result)
    {

        $this->db->where('id' , $details['job_id'])
        ->set("estimated_working_hrs" , "estimated_working_hrs - ".$details['estimated_working']."+".$details['estimated_working_hrs'], FALSE);
        $result = $this->db->update('job_orders');

    }

    return $result;

}
public function updateDepartmentJobDetail( $details=[] )
{

    $time_difference=0;

    $this->db->where('job_order_id' , $details['job_id'])
    ->where('department_id' , $details['department_id'])
    ->set('short_description' , $details['short_description'])
    ->set('order_description' , $details['order_description'])
    ->set('estimated_working_hrs' , $details['estimated_working_hrs'])
    ->set('time_difference' , $time_difference);
    $result = $this->db->update('department_jobs');
// echo $this->db->last_query();die();


    return $result;

}
public function getAllJobOrders( $id='', $search_arr=[])
{
    $details=array();
    $this->load->model('Project_model');
    $this->db->select('*')
    ->from('job_orders');
    if ($id) {
        $this->db->where('id',$id);
    }


    $department_id = '';
    if ($search_arr) { 

        if (element('project_id',$search_arr)) {

            $this->db->where('project_id',$search_arr['project_id']);

        }
        if (element('customer_id',$search_arr)) {

            $this->db->where('customer_id',$search_arr['customer_id']);

        }
        if (element('order_id',$search_arr)) {

            $this->db->where('order_id',$search_arr['order_id']);
        }  
        if(element('job_name',$search_arr)){
            $this->db->where('name',$search_arr['job_name']);
        }
        if (element('admin_status',$search_arr)) {

            $this->db->where('admin_status',$search_arr['admin_status']);
        } 
        if (element('customer_status',$search_arr)) {

            $this->db->where('customer_status',$search_arr['customer_status']);
        } 
        if (element('start_date',$search_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($search_arr['start_date'])); 
            $this->db->where('date >=', $start_date); 
        }

        if (element('end_date',$search_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($search_arr['end_date']));  
            $this->db->where('date <=', $end_date);
        } 
        if (element('delivery_date',$search_arr)) {

            $this->db->where('requested_date =', $search_arr['delivery_date']);
        }

        if (element('department_id',$search_arr)) {
            $department_id = $search_arr['department_id'];
        } 

        if (element('order_by',$search_arr)) {
            $this->db->order_by('id',$search_arr['order_by']);
        } 
        if(element('limit',$search_arr)){
            $this->db->limit( $search_arr['limit'] );
        }

    }

    $query = $this->db->get();   


    foreach($query->result_array() as $row){
        $row['project_name']=$this->getProjectName($row['project_id']);
        $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
        $row['customer_name']=$this->Project_model->getCustomerName($row['customer_id']);
        $row['department']=$this->getDepartmentDetails($department_id,$row['id']);
        $details[] = $row;
    }
    return $details;

}


public function getOrderCount($customer_id='')
{
    $this->db->select('*');
    $this->db->from('job_orders');
    if($customer_id)
    {
        $this->db->where('customer_id',$customer_id);
    }
    $count = $this->db->count_all_results();
    return $count;
}
public function getPendingOrderCount($customer_id='')
{
    $this->db->select('*');
    $this->db->from('job_orders');
    if($customer_id)
    {
        $this->db->where('customer_id',$customer_id);
    }
    $this->db->where('customer_status','pending');

    $count = $this->db->count_all_results();
    return $count;
}

public function getAllOrderDetailsAjax($post_arr=[], $count = 0,$customer_id='') {


    $row = $post_arr['start'];
    $rowperpage = $post_arr['length'];

    $details = array();
    $this->db->select('j.*');
    $this->db->select('c.customer_username,c.name as customer_name,p.project_name');
    $this->db->from('job_orders as j');
    $this->db->join('customer_info c','c.customer_id = j.customer_id');
    $this->db->join('project p','p.id = j.project_id');
    if($customer_id){
        $this->db->where('j.customer_id',$customer_id);
    }

        $searchValue = $post_arr['search']['value']; // Search value
        if('' != $searchValue) { 

            $where = "(j.name LIKE '%$searchValue%' 
            OR j.date LIKE '%$searchValue%'
            OR j.estimated_working_hrs LIKE '%$searchValue%'
            OR c.customer_username LIKE '%$searchValue%'
            OR j.actual_workin_hrs LIKE '%$searchValue%')";


            $this->db->where($where);
        }

        

        if(!empty($post_arr['order'])) {
            $columnIndex = $post_arr['order'][0]['column'];  
            $columnName = $post_arr['columns'][$columnIndex]['data']; 
            $columnSortOrder = $post_arr['order'][0]['dir'];  
        }

        if(!empty($post_arr['order']) && '' != $columnName) {

            $this->db->order_by($columnName, $columnSortOrder);

        } else {
            $this->db->order_by('j.date' , 'DESC');
        }

        if(element('order_id',$post_arr))
        {
            $this->db->where('j.order_id',$post_arr['order_id']);
        }  
        if(element('project_id',$post_arr))
        {
            $this->db->where('j.project_id',$post_arr['project_id']);
        }
        if(element('status',$post_arr))
        {
            $this->db->where('j.customer_status',$post_arr['status']);
        }
        if(element('order_name',$post_arr))
        {
            $this->db->where('j.name',$post_arr['order_name']);
        }
        if(element('start_date',$post_arr))
        {
            $post_arr['start_date'] = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
            $this->db->where('j.date >=',$post_arr['start_date']);
        }
        if(element('end_date',$post_arr)){
            $post_arr['end_date'] = date("Y-m-d 23:59:59", strtotime($post_arr['end_date'])); 
            $this->db->where('j.date <=',$post_arr['end_date']);
        }
        if($count) {
            return $this->db->count_all_results();
        }

        $this->db->limit($rowperpage, $row);
        $query = $this->db->get();

        $i=1;

        foreach($query->result_array() as $row){
            $row['index'] = $i++;
            $row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt', $row['id']);
            $row['base_url'] = base_url();
            $row['date'] = date('Y-m-d',strtotime($row['date']));
            $row['requested_date'] = date('Y-m-d',strtotime($row['requested_date']));
            $row['department_cost_format'] = $this->currency->format($row['department_cost']);
            $details[] = $row;
        }
        return $details;
    }

    public function getJobOrderDetails($job_id='', $department_id='')
    {
        $details = array();
        $this->db->select('j.*');
        $this->db->select('c.customer_username,c.name customer_name,c.email customer_email,c.mobile customer_mobile');
        $this->db->select('p.project_name,p.email project_email');
        $this->db->from('job_orders j');
        $this->db->join('customer_info c','c.customer_id = j.customer_id');
        $this->db->join('project p','p.id = j.project_id');
        if($job_id)
        {
            $this->db->where('j.id',$job_id);
        }
        $query = $this->db->get();
        foreach($query->result_array() as $row)
        {
            $row['job_orders'] = $this->Jobs_model->getDepartmentJobsDetails($row['id'], '', $department_id);

            $details = $row;
        }
        return $details;
    }

    public function getDepartmentJobsDetails($job_order_id='',$id='', $department_id='')
    {
        $details = array();
        $this->db->select('dj.*');
        $this->db->select('d.name department_name');
        $this->db->from('department_jobs dj');
        $this->db->join('department d','d.id = dj.department_id');
        if($id)
        {
            $this->db->where('dj.id',$id);
        }if($job_order_id){

            $this->db->where('dj.job_order_id',$job_order_id);
        }
        if($department_id){

            $this->db->where('dj.department_id',$department_id);
        }
        $get = $this->db->get();
        foreach($get->result_array() as $row)
        {
            if($id){
                $details = $row;
            }else{
                $details[] = $row;
            }
        }
        return $details;
    }
    public function getDepartmentJobDetails($job_order_id,$department_id)
    {
     $details = array();
     $this->db->select('*');

     $this->db->from('department_jobs');
     if($job_order_id){

        $this->db->where('job_order_id',$job_order_id);
    }
    if($department_id){

        $this->db->where('department_id',$department_id);
    }
    $get = $this->db->get();
    foreach($get->result_array() as $row)
    {
        $details = $row;
    }
    return $details;
}

public function getOrderApprovalCount($customer_id='')
{
    $this->db->select('j.*');
    $this->db->select('dj.id');
    $this->db->from('job_orders j');
    $this->db->join('department_jobs dj','dj.job_order_id = j.id');
    if($customer_id)
    {
        $this->db->where('j.customer_id',$customer_id);
    }
    $this->db->where('j.customer_status','pending');
    $count = $this->db->count_all_results();
    return $count;
}


public function getAllOrderApprovalDetailsAjax($post_arr=[]) {

    $details = array();
    $this->db->select('j.*');
    $this->db->select('c.customer_username,c.name as customer_name,p.project_name,j.customer_status');
    $this->db->from('job_orders as j');
    $this->db->join('customer_info c','c.customer_id = j.customer_id');
    $this->db->join('project p','p.id = j.project_id');
    if(element('customer_id',$post_arr)){
        $this->db->where('j.customer_id',$post_arr['customer_id']);
    }
    if(element('project_id',$post_arr)){
        $this->db->where('j.project_id',$post_arr['project_id']);
    }
    if(element('order_id',$post_arr)){
        $this->db->where('j.order_id',$post_arr['order_id']);
    } 
    if(element('customer_name',$post_arr)){
        $this->db->where('j.customer_id',$post_arr['customer_name']);
    } 
    if(element('job_name',$post_arr)){
        $this->db->where('j.name',$post_arr['job_name']);
    }
    if(element('status',$post_arr)){
        $this->db->where('j.customer_status',$post_arr['status']);
    }
    if(element('admin_status',$post_arr)){
        $this->db->where('j.admin_status',$post_arr['admin_status']);
    }
    if(element('start_date',$post_arr))
    {
        $post_arr['start_date'] = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
        $this->db->where('j.date >=',$post_arr['start_date']);
    }
    if(element('end_date',$post_arr)){
        $post_arr['end_date'] = date("Y-m-d 23:59:59", strtotime($post_arr['end_date'])); 
        $this->db->where('j.date <=',$post_arr['end_date']);
    }
    if(element('requested_date',$post_arr)){
        $this->db->where('j.requested_date <=',$post_arr['requested_date']);
    }

    if(element('limit',$post_arr)){
        $this->db->limit( $post_arr['limit'] );
    }

    $query = $this->db->get();
    foreach($query->result_array() as $row){
        $row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt', $row['id']);
        $row['date'] =date("Y-m-d ", strtotime($row['date'])); 
        $details[] = $row;
    }
    return $details;
}

public function changeDepartmentJobsStatus($status,$id)
{
    $this->db->set('customer_status',$status);
    $this->db->where('id',$id);
    $update = $this->db->update('department_jobs');
    return $update;
}

public function updateCustomerJobStatus($status,$id)
{
    $this->db->set('customer_status',$status);
        // $this->db->set('customer_approved_date',date('Y-m-d H:i:s'));

    $this->db->where('id',$id);
    $update = $this->db->update('job_orders');
    return $update;
} 


public function updateAdminJobStatus($status,$id)
{
    $this->db->set('admin_status',$status);
    $this->db->set('date',date('Y-m-d H:i:s'));
    $this->db->where('id',$id);
    $update = $this->db->update('job_orders');
    return $update;
}

public function updateJobStatus($data)
{
    $update = $this->db->update_batch('job_orders', $data, 'id');
    return $update;
} 

public function getCustomerProjectsAjax($search_arr,$status='pending')
{
        // print_r($search_arr);die();
    $details=[];
    $this->db->select('project_name as text,id')
    ->from('project');
    if (element('customer_id',$search_arr)) {
        $this->db->where('customer_name',$search_arr['customer_id']);
    }
    if (element('project_id',$search_arr)) {
        $this->db->where('id',$search_arr['project_id']);
    }
    if ($status!='all') {
        $this->db->where('status',$status);
    }
    $query = $this->db->get();
    foreach($query->result_array() as $row){
        $details[] = $row;
    }
    return $details;
}

public function getJobDayProgress($search_arr=[],$limit='')
{
    $details=array();
    $this->db->select('*')
    ->order_by('id','DESC')
    ->from('day_progress'); 
    if ($search_arr) { 

        if (element( 'department_id', $search_arr)) {
            $this->db->where('department_id', $search_arr['department_id']);
        }

        if (element( 'progress_id', $search_arr)) {
            $this->db->where('id', $search_arr['progress_id']);
        }

        if (element( 'job_order_id', $search_arr)) {
            $this->db->where('job_order_id', $search_arr['job_order_id']);
        } 

        if (element( 'start_date', $search_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($search_arr['start_date']));
            $this->db->where('date_added >=', $start_date); 
        }

        if (element( 'end_date', $search_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($search_arr['end_date']));  
            $this->db->where('date_added <=', $end_date);
        } 
    }
    if($limit)
    {
        $this->db->limit($limit);
    }
    $query = $this->db->get();   

    foreach($query->result_array() as $row){
        $row['enc_id']=$this->Base_model->encrypt_decrypt('encrypt',$row['id']);
        $row['enc_job_id']=$this->Base_model->encrypt_decrypt('encrypt',$row['job_order_id']);
        $row['enc_department_id']=$this->Base_model->encrypt_decrypt('encrypt',$row['department_id']);
        $row['department_name']=$this->Base_model->getDepartmentName($row['department_id']);
            // $row['date_added']=date("Y-m-d", strtotime($row['date_added']));
        if (element( 'progress_id', $search_arr)) {
            return $row;       
        }

        $details[] = $row;
    }
    return $details;

}
public function delete_day_progress($id)
{ 

    $search_arr['progress_id'] = $id; 
    $day_progress = $this->getJobDayProgress( $search_arr ); 


    $this->db->where('id', $id); 
    $result = $this->db->delete('day_progress'); 

    if($result){ 
        if($day_progress){
            $this->db->where('job_order_id' , $day_progress['job_order_id'])
            ->where('department_id' , $day_progress['department_id'])
            ->set("actual_working_hrs" , "actual_working_hrs - ".$day_progress['worked_in_min'] , FALSE);
            $result = $this->db->update('department_jobs');

            if($result){
                $this->db->where('id' , $day_progress['job_order_id'])
                ->set("actual_workin_hrs" , "actual_workin_hrs - ".$day_progress['worked_in_min'], FALSE);
                $result = $this->db->update('job_orders');
            }

            return $result;
        }
    }
    return FALSE;

}


public function deleteDepartmentJob($id)
{ 
    $this->db->where('id', $id); 
    return $this->db->delete('department_jobs'); 
}


public function insertDayProgress($details)
{

    $now = date('Y-m-d H:i:s');

    $worked_in_min = $details['worked_time'];
        // if( element( 'time_span', $details) ){
        //     $worked_in_min = $details['worked_time']*60;
        // }
    

    $this->db->set('today_status' , $details['today_status'])
    ->set('worked_in_min' , $worked_in_min)
    ->set('employees_worked' , $details['employees_worked'])
    ->set('date_added' , $now)
    ->set('department_id' , $details['department_id'])
    ->set('job_order_id' , $details['job_order_id']);
    $result = $this->db->insert('day_progress');


    if($result){
        $this->db->where('job_order_id' , $details['job_order_id'])
        ->where('department_id' , $details['department_id'])
        ->set("actual_working_hrs" , "actual_working_hrs + ".$worked_in_min, FALSE);
        $result = $this->db->update('department_jobs');
        
        if($result){
            $this->db->where('id' , $details['job_order_id'])
            ->set("actual_workin_hrs" , "actual_workin_hrs + ".$worked_in_min, FALSE);
            $result = $this->db->update('job_orders');
        }
        return $result;
    }
    return $result;
}
public function updateDayProgress($details)
{ 

    $day_progress = [];

    $today = date('Y-m-d');
    $now = date('Y-m-d H:i:s'); 
    
    $search_arr['progress_id'] = $details['progress_id']; 
    $day_progress = $this->getJobDayProgress( $search_arr ); 


    $worked_in_min = $details['worked_time']; 
    if( element( 'time_span', $details) ){    
        $worked_in_min = $details['worked_time']*60;
    }
    $employees_worked=$details['employees_worked'];
    $this->db->set('today_status' , $details['today_status'])
    ->set('worked_in_min' , $worked_in_min)
    ->set('employees_worked' , $employees_worked)
    ->set('date_added' , $now)

    ->where('id' , $details['progress_id'])
    ->where('job_order_id' , $details['job_order_id'])
    ->where('department_id' , $details['department_id']);
    $result = $this->db->update('day_progress');

    if($result){ 
        if($day_progress){
            $this->db->where('job_order_id' , $details['job_order_id'])
            ->where('department_id' , $details['department_id'])
            ->set("actual_working_hrs" , "actual_working_hrs - ".$day_progress['worked_in_min'] . " + " .$worked_in_min , FALSE);
            $result = $this->db->update('department_jobs');

            if($result){
                $this->db->where('id' , $details['job_order_id'])
                ->set("actual_workin_hrs" , "actual_workin_hrs - ".$day_progress['worked_in_min']. " + " .$worked_in_min, FALSE);
                $result = $this->db->update('job_orders');
            }

            return $result;


        }

    }

    
    return $result;
}

public function updateDepartmentJobOrderInfo($data){
    $this->db->where('id' , $data['job_order_id'])
    ->set("estimated_working_hrs" , "estimated_working_hrs + ".$data['estimated_working_hrs'], FALSE)
    ->set("department_cost" , "department_cost + ".$data['department_cost'], FALSE);
    $result = $this->db->update('job_orders');
    return $result;
} 
public function updateDepartmentWorkStatus($data){
    $this->db->where('job_order_id' , $data['job_order_id'])
    ->where('department_id' , $data['department_id'])

    ->set("work_status" ,$data['status']);
    $result = $this->db->update('department_jobs');
    return $result;
}                       

public function getDepartmentJobsField($select='*',$id)
{
    $this->db->select($select);
    $this->db->where('id', $id);
    $this->db->from('department_jobs');
    $query = $this->db->get();
    foreach ($query->result_array() as $row) {
        $row['department_name']=$this->Base_model->getDepartmentName($row['department_id']);
        return $row;
    }
    return array();
}
public function getJobOrderField($select='*',$id)
{
    $this->db->select($select);
    $this->db->where('id', $id);
    $this->db->from('job_orders');
    $query = $this->db->get();
    foreach ($query->result_array() as $row) {
        return $row;
    }
    return array();
}
public function updateWorkStatus($status,$id)
{
    $this->db->set('work_status',$status);
        // $this->db->set('customer_approved_date',date('Y-m-d H:i:s'));

    $this->db->where('id',$id);
    $update = $this->db->update('job_orders');
    return $update;
} 
public function updateDeptWorkStatus($status,$id)
{
    $this->db->set('work_status',$status);
        // $this->db->set('customer_approved_date',date('Y-m-d H:i:s'));

    $this->db->where('job_order_id',$id);
    $update = $this->db->update('department_jobs');
    return $update;
} 

public function getAllJobOrder( $post_arr=[],$count=0)
{

    $row = $post_arr['start'];
    $rowperpage = $post_arr['length'];

    $details=array();
    $this->load->model('Project_model');
    $this->db->select('*')
    ->from('job_orders');
    $department_id = '';

        $searchValue = $post_arr['search']['value']; // Search value
        if('' != $searchValue) { 

           $where = "(project_id LIKE '%$searchValue%' 
           OR customer_id LIKE '%$searchValue%'
           OR order_id LIKE '%$searchValue%'
           OR job_name LIKE '%$searchValue%'
           OR admin_status LIKE '%$searchValue%';
           OR status LIKE '%$searchValue%';
           OR start_date LIKE '%$searchValue%';
           OR requested_date LIKE '%$searchValue%';
           OR end_date LIKE '%$searchValue%' )";


           $this->db->where($where);
       }

       if(!empty($post_arr['order'])) {

        $columnIndex = $post_arr['order'][0]['column'];  
        $columnName = $post_arr['columns'][$columnIndex]['data']; 
        $columnSortOrder = $post_arr['order'][0]['dir'];  
    }
    if ($post_arr) { 

        if (element('project_id',$post_arr)) {

            $this->db->where('project_id',$post_arr['project_id']);

        }
        if (element('customer_id',$post_arr)) {

            $this->db->where('customer_id',$post_arr['customer_id']);

        }
        if (element('order_id',$post_arr)) {

            $this->db->where('order_id',$post_arr['order_id']);
        }  
        if(element('job_name',$post_arr)){
            $this->db->where('name',$post_arr['job_name']);
        }
        if (element('admin_status',$post_arr)) {

            $this->db->where('admin_status',$post_arr['admin_status']);
        } 
        if (element('customer_status',$post_arr)) {

            $this->db->where('customer_status',$post_arr['customer_status']);
        } 
        if (element('start_date',$post_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
            $this->db->where('date >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
            $this->db->where('date <=', $end_date);
        } 
        if (element('delivery_date',$post_arr)) {

            $this->db->where('requested_date =', $post_arr['delivery_date']);
        }

        if (element('department_id',$post_arr)) {
            $department_id = $post_arr['department_id'];
        } 

        if (element('order_by',$post_arr)) {
            $this->db->order_by($post_arr['order'],$post_arr['order_by']);
        } 
        if(element('limit',$post_arr)){
            $this->db->limit( $post_arr['limit'] );
        }
        if($count) {
            return $this->db->count_all_results();
        }

    }
    $this->db->limit($rowperpage, $row);
    $query = $this->db->get();   
    $i=1;
    foreach($query->result_array() as $row){
        $row['index'] =$post_arr['start']+$i;
        $i++;
        $row['project_name']=$this->getProjectName($row['project_id']);
        $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
        $row['customer_name']=$this->Project_model->getCustomerName($row['customer_id']);
        $row['amount']=cur_format($row['department_cost']);
        $row['estimated_working_hrs']=$row['estimated_working_hrs'].'Hrs';
        $row['actual_workin_hrs']=$row['actual_workin_hrs'].'Hrs';
        $row['department']=$this->getDepartmentDetails($department_id,$row['id']);
        // $row['order_id']=$row['order_id'] .'('.$row['name'].')';
        
        $details[] = $row;
    }
    return $details;
}


public function getJobCount()
{
    $this->db->select('*');
    $this->db->from('job_orders');
    $count = $this->db->count_all_results();
    return $count;
}

}