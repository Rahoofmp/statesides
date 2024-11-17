<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Report_model extends Base_model {

	function __construct() {
		parent::__construct();

	}

	public function getMemberCount()
	{
		$count = 0;
		$this->db->select('*');
		$this->db->from('login_info');
		
		$count = $this->db->count_all_results();
		return $count;
	}

	public function getMembersDetails($post_arr=[], $count = 0) {


		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];

		$details = array();
		$this->db->select('li.user_id,li.user_name,li.status,li.sponsor_id,li.user_type,li.joining_date,li.package_id,ui.first_name,ui.mobile,ui.email');
		$this->db->from('login_info as li');
		$this->db->join('user_info as ui ','li.user_id = ui.user_id');
		$this->db->order_by('li.joining_date','DESC');
		

        $searchValue = $post_arr['search']['value']; // Search value
        if('' != $searchValue) { 

        	$where = "(li.user_id LIKE '%$searchValue%' 
        	OR li.user_name LIKE '%$searchValue%'
        	OR li.status LIKE '%$searchValue%'
        	OR li.user_type LIKE '%$searchValue%'
        	OR ui.first_name LIKE '%$searchValue%'
        	OR ui.mobile LIKE '%$searchValue%'
        	OR ui.email LIKE '%$searchValue%'
        	OR li.joining_date LIKE '%$searchValue%')";


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
        	$this->db->order_by('li.joining_date','DESC');
        }

        
        if(element('from_date',$post_arr))
        {
        	$post_arr['from_date'] = date("Y-m-d 00:00:00", strtotime($post_arr['from_date'])); 
        	$this->db->where('li.joining_date >=',$post_arr['from_date']);
        }
        if(element('end_date',$post_arr)){
        	$post_arr['end_date'] = date("Y-m-d 23:59:59", strtotime($post_arr['end_date'])); 
        	$this->db->where('li.joining_date <=',$post_arr['end_date']);
        }
        if(element('user_type',$post_arr)){
        	$this->db->where('li.user_type <=',$post_arr['user_type']);
        }
        if($count) {
        	return $this->db->count_all_results();
        }

        $this->db->limit($rowperpage, $row);
        $query = $this->db->get();

        $i=1;

        foreach($query->result_array() as $row){
        	$row['index'] = $i++;
        	$row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt', $row['user_id']);
        	$row['base_url'] = base_url();
        	$row['sponsor_name'] = $this->getUserName($row['sponsor_id']);
        	$row['full_name'] = $this->getFullName( $row['user_id'] );

        	$row['joining_date'] =  date('d/M/Y H:i',strtotime($row['joining_date']));

        	switch ( $row['status'] ) {
        		case '0':
        		$row['status']        = lang("inactive");  
        		break;

        		case '2':
        		$row['status']        = lang("blocked");  
        		break;

        		case '1':
        		$row['status']        = lang("active");  
        		break;

        		default:
        		$row['status']        = lang("active");
        		break;
        	};

        	$details[] = $row;
        }
        return $details;
    }

    public function getProjectCount($customer_name='')
    {
    	$count = 0 ;
    	$this->db->select('*');
    	$this->db->from('project');
    	
    	if ($customer_name) {
    		$this->db->where('customer_name',$customer_name);
    	}
    	
    	$count = $this->db->count_all_results();
    	return $count;
    } 
    public function getItemCount()
    {
        $count = 0 ;
        $this->db->select('*');
        $this->db->from('items');
        
        // if ($category_id) {
        //     $this->db->where('category',$category_id);
        // }
        
        $count = $this->db->count_all_results();
        return $count;
    }

    public function getProjectDtailsAjax($post_arr=[], $count = 0) {


    	$row = $post_arr['start'];
    	$rowperpage = $post_arr['length'];

    	$details = array();
    	$this->db->select('p.*');
    	$this->db->select('c.customer_username,c.name cus_name,c.mobile customer_mobile,c.email customer_email');
    	$this->db->from('project p');
    	$this->db->join('customer_info c','c.customer_id = p.customer_name');


        $searchValue = $post_arr['search']['value']; // Search value
        if('' != $searchValue) { 

        	$where = "(p.project_name LIKE '%$searchValue%' 
        	OR p.estimated_cost LIKE '%$searchValue%'
        	OR p.estimated_value LIKE '%$searchValue%'
        	OR p.estimated_duration LIKE '%$searchValue%'
        	OR c.customer_username LIKE '%$searchValue%')";


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
        	$this->db->order_by('p.date','DESC');
        }

        if (element('project_id', $post_arr )) {
        	$this->db->where('p.id', $post_arr['project_id'] );
        }
        if (element('customer_name',$post_arr)) {
        	$this->db->where('p.customer_name',$post_arr['customer_name']);
        }

        if (element('mobile',$post_arr)) {
        	$this->db->where('p.mobile',$post_arr['mobile']);
        }

        if (element('email',$post_arr)) {
        	$this->db->where('p.email',$post_arr['email']);
        }

        if (element('status',$post_arr)) {
        	$this->db->where('p.status', $post_arr['status']);
        }


        if (element('start_date',$post_arr)) {
        	$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
        	$this->db->where('p.date >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
        	$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
        	$this->db->where('p.date <=', $end_date);
        }
        if($count) {
        	return $this->db->count_all_results();
        }

        $this->db->limit($rowperpage, $row);
        $query = $this->db->get();

        $i=1;

        foreach($query->result_array() as $row){
        	$row['index'] = $post_arr['start']+$i++;
        	$row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt', $row['id']);
        	$row['base_url'] = base_url();
        	$row['estimated_cost'] = cur_format($row['estimated_cost']);
        	$row['estimated_value'] = cur_format($row['estimated_value']);
        	$row['date'] = $row['date'];
        	

        	$details[] = $row;
        }
        return $details;
    }

    public function getItemDtailsAjax($post_arr=[], $count = 0) {


        $row = $post_arr['start'];
        $rowperpage = $post_arr['length'];
        $details=[];
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        $this->db->select('it.*,c.category_name,v.name as vat_name,v.value');
        $this->db->select('SUM(mri.qty) - SUM(mir.issued_qty) as stock');
        $this->db->from('items it')
        ->join('category c', 'it.category = c.id')
        ->join('material_receipt_items mri', 'mri.item_id = it.id AND mri.status="active"','left')
        ->join('material_issue_receipts mir', 'mir.item_id = mri.item_id AND mir.receipt_id = mri.material_receipt_id AND mri.status="active"','left')

        ->join('vat v', 'it.vat = v.id');
        $this->db->where('it.status!=','0');

        $searchValue = $post_arr['search']['value']; // Search value
        if('' != $searchValue) { 

            $where = "(it.code LIKE '%$searchValue%' 
            OR c.category_name LIKE '%$searchValue%'
            OR v.name LIKE '%$searchValue%'
            OR it.name LIKE '%$searchValue%'
            OR it.type LIKE '%$searchValue%')";


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
            $this->db->order_by('it.date','DESC');
        }

        if (element('item_id', $post_arr )) {
            $this->db->where('it.id', $post_arr['item_id'] );
        }
        // if (element('customer_name',$post_arr)) {
        //     $this->db->where('p.customer_name',$post_arr['customer_name']);
        // }

        if (element('category_id',$post_arr)) {
            $this->db->where('it.category',$post_arr['category_id']);
        }


        if (element('stock_qty', $post_arr )) {
            if($post_arr['stock_qty']==1){

                $this->db->having('stock!=',0);
                

            }
            elseif($post_arr['stock_qty']==3){
               $this->db->having('stock>',0);

           }
       }

       if (element('start_date',$post_arr)) {
        $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
        $this->db->where('it.date >=', $start_date); 
    }

    if (element('end_date',$post_arr)) {
        $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
        $this->db->where('it.date <=', $end_date);
    }
    $this->db->group_by('it.id');

    if($count) {
        return $this->db->count_all_results();
    }

    $this->db->limit($rowperpage, $row);

    $query = $this->db->get();

        // echo $this->db->last_query($query);die();
    $i=1;

    foreach($query->result_array() as $row){

       $row['index'] = $post_arr['start']+$i++;
       $row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt', $row['id']);
       $row['base_url'] = base_url();
       $row['date'] = $row['date'];

       if($row['stock']==NULL){
        $row['stock']=0;

    }

    $details[] = $row;
}
return $details;
}

public function getJobsCount($customer_id='')
{
   $count = 0 ;
   $this->db->select('*');
   $this->db->from('job_orders');
   if($customer_id)
   {
    $this->db->where('customer_id',$customer_id);
}

$count = $this->db->count_all_results();
return $count;
}

public function getJobsDtailsAjax($post_arr=[], $count = 0) {

   $row = $post_arr['start'];
   $rowperpage = $post_arr['length'];

   $details = array();
   $this->db->select('j.*,c.customer_username,p.project_name');
   $this->db->from('job_orders as j');
   $this->db->join('customer_info as c','c.customer_id = j.customer_id');
   $this->db->join('project as p','p.id = j.project_id');

        $searchValue = $post_arr['search']['value']; // Search value
        if('' != $searchValue) { 

        	$where = "(c.customer_username LIKE '%$searchValue%' 
        	OR p.project_name LIKE '%$searchValue%'
        	OR j.estimated_working_hrs LIKE '%$searchValue%'
        	OR j.actual_working_hours LIKE '%$searchValue%'
        	OR j.admin_status LIKE '%$searchValue%'
        	OR j.customer_status LIKE '%$searchValue%')";


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
        	$this->db->order_by('j.id','DESC');
        }

        
        if (element('customer_id',$post_arr)) {

        	$this->db->where('j.customer_id',$post_arr['customer_id']);

        }
        if (element('order_id',$post_arr)) {

        	$this->db->where('j.order_id',$post_arr['order_id']);
        }  
        
        if (element('admin_status',$post_arr)) {

        	$this->db->where('j.admin_status',$post_arr['admin_status']);
        } 
        if (element('customer_status',$post_arr)) {

        	$this->db->where('j.customer_status',$post_arr['customer_status']);
        } 
        if (element('start_date',$post_arr)) {
        	$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
        	$this->db->where('j.date >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
        	$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
        	$this->db->where('j.date <=', $end_date);
        } 
        if (element('delivery_date',$post_arr)) {

        	$this->db->where('j.requested_date =', $post_arr['delivery_date']);
        }

        if($count) {
        	return $this->db->count_all_results();
        }

        $this->db->limit($rowperpage, $row);
        $query = $this->db->get();
        $i=1;

        foreach($query->result_array() as $row){
            $row['index'] = $post_arr['start']+$i++;
            $row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt', $row['id']);
            $row['base_url'] = base_url();
            $row['date'] =  date('Y-m-d',strtotime($row['date']));
            $row['requested_date'] =  date('Y-m-d',strtotime($row['requested_date']));
            $row['department_cost'] = cur_format($row['department_cost']);
            $details[] = $row;
        }
        return $details;
    }

    public function getDeptJobsCount($department_id='')
    {
    	$count = 0 ;
    	$this->db->select('dj.*');
    	$this->db->select('jo.customer_id');
    	$this->db->from('department_jobs dj');
    	$this->db->join('job_orders jo', 'jo.id = dj.job_order_id');
    	if ($department_id) {
    		$this->db->where('dj.department_id', $department_id);
    	}
    	$count = $this->db->count_all_results();
    	return $count;
    }

    public function getDeptJobsDtailsAjax($post_arr=[], $count = 0) {

    	$row = $post_arr['start'];
    	$rowperpage = $post_arr['length'];

    	$details = array();
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
    	
        $searchValue = $post_arr['search']['value']; // Search value
        if('' != $searchValue) { 

        	$where = "(jo.order_id LIKE '%$searchValue%' 
        	OR pj.project_name LIKE '%$searchValue%'
        	OR jo.name LIKE '%$searchValue%'
        	OR ci.customer_username LIKE '%$searchValue%'
        	OR jo.date LIKE '%$searchValue%'
        	OR jo.requested_date LIKE '%$searchValue%')";


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
        	$this->db->order_by('dj.id','DESC');
        }

        
        if (element('department_id', $post_arr)) {
        	$this->db->where('dj.department_id', $post_arr['department_id']);
        }

        if (element('customer_id', $post_arr)) {
        	$this->db->where('jo.customer_id', $post_arr['customer_id']);
        }
        if (element('order_id', $post_arr)) {
        	$this->db->where('jo.order_id', $post_arr['order_id']);
        }

        if (element('start_date', $post_arr)) {
        	$this->db->where("DATE_FORMAT(jo.date,'%Y-%m-%d') >=", $post_arr['start_date']);
        }

        if (element('end_date', $post_arr)) {
        	$this->db->where("DATE_FORMAT(jo.date,'%Y-%m-%d') <=", $post_arr['end_date']);
        }

        if($count) {
        	return $this->db->count_all_results();
        }

        $this->db->limit($rowperpage, $row);
        $query = $this->db->get();
        $i=1;

        foreach($query->result_array() as $row){
            $row['index'] = $post_arr['start']+$i++;
            $row['enc_id'] = $this->Base_model->encrypt_decrypt('encrypt', $row['id']);
            $row['enc_job_order_id'] = $this->Base_model->encrypt_decrypt('encrypt', $row['job_order_id']);
            $row['base_url'] = base_url();
            $row['date'] =  date('Y-m-d',strtotime($row['date']));
            $row['requested_date'] =  date('Y-m-d',strtotime($row['requested_date']));
            $row['department_cost'] = cur_format($row['department_cost']);
            $details[] = $row;
        }
        return $details;
    }

    public function getFundTransferDetails( $from_id ='', $start_date ='',$end_date='')
    {

    	$amount_type_id = $this->getAmountTypeIdByString('fund_transfer');

    	$details = array();
    	$this->db->select('cd.user_id, total_amount, date_of_submission, transaction_note')
    	->select('li.user_name as transfer_from')
    	->from('commission_details cd')
    	->where('cd.amount_type_id', $amount_type_id)
    	->where('cd.fund_transfer_type', 'credit')
    	->join('login_info li', 'li.user_id = cd.from_id')
    	->order_by( 'date_of_submission', 'DESC' );

    	if( $from_id ){
    		$this->db->where('from_id', $from_id );
    	}

    	if( $start_date ){
    		$start_date = date("Y-m-d 00:00:00", strtotime($start_date)); 
    		$this->db->where('date_of_submission >=', $start_date); 
    	}

    	if( $end_date ){ 
    		$end_date = date("Y-m-d 23:59:59", strtotime($end_date));
    		$this->db->where('date_of_submission <=', $end_date);
    	}

    	$query = $this->db->get();  
    	foreach($query->result_array() as $row){ 
    		$row['transfer_to'] = $this->getUserName($row['user_id']);

    		$row['date_of_submission'] =  date('d/M/Y H:i',strtotime($row['date_of_submission']));


    		$details[] = $row;
    	}  
    	return $details;

    }


    public function getCommissionDetails($user_id ='',$start_date ='',$end_date='',$amount_type='all')
    {
    	$details = array();

    	$this->db->select('amount_type_id , user_id , total_amount');
    	$this->db->from('commission_details'); 

    	if($user_id !=''){
    		$this->db->where('user_id',$user_id);
    	}

    	if($amount_type !='all'){
    		$this->db->where('amount_type_id',$amount_type);
    	}

    	if($start_date !='' && $end_date !=''){
    		$start_date = date("Y-m-d 00:00:00", strtotime($start_date));
    		$end_date = date("Y-m-d 23:59:59", strtotime($end_date)); 
    		$this->db->where('date_of_submission >=', $start_date);
    		$this->db->where('date_of_submission <=', $end_date); 
    	}

    	$query = $this->db->get();

    	foreach($query->result_array() as $row){

    		$row['user_name'] = $this->getUserName($row['user_id']);
    		$sponsor_id = $this->getSponsorID($row['user_id']);
    		if($sponsor_id == 0){
    			$row['sponsor_name'] = "NA";
    		}
    		else{
    			$row['sponsor_name'] = $this->getUserName($sponsor_id);
    		}
    		$row['full_name'] = $this->getUserInfoField('first_name', $row['user_id']).' '.$this->getUserInfoField('second_name', $row['user_id']);
    		$row['user_email'] = $this->getUserInfoField('email',$row['user_id']);
    		$row['amount_type_name'] = $this->getAmountTypeName($row['amount_type_id']);

    		$join_date =  $this->getLoginInfoField('joining_date',$row['user_id']);
    		$row['join_date'] =  date('d/M/y',strtotime($join_date)); 
    		$details[] = $row;
    	}  
    	return $details;

    }





    public function getRecruterDetails($user_id ='', $start_date ='',$end_date='')
    {
    	$details = array();
    	$this->db->select('user_id, user_name, status, sponsor_id, joining_date')
    	->from('login_info')
    	->order_by( 'joining_date', 'DESC' );

    	if( $user_id ){
    		$this->db->where('sponsor_id', $user_id );
    	}

    	if( $start_date ){
    		$start_date = date("Y-m-d 00:00:00", strtotime($start_date)); 
    		$this->db->where('joining_date >=', $start_date); 
    	}

    	if( $end_date ){ 
    		$end_date = date("Y-m-d 23:59:59", strtotime($end_date));  
    		$this->db->where('joining_date <=', $end_date);
    	}

    	$query = $this->db->get(); 
    	foreach($query->result_array() as $row){ 
    		$row['sponsor_name'] = $this->getUserName($row['sponsor_id']);
    		$row['full_name'] = $this->getFullName( $row['user_id'] );

    		$row['email'] = $this->getUserInfoField( 'email', $row['user_id'] );
    		$row['joining_date'] =  date('d/M/Y H:i',strtotime($row['joining_date']));

    		switch ( $row['status'] ) {
    			case '0':
    			$row['status']        = lang("inactive");  
    			break;

    			case '2':
    			$row['status']        = lang("blocked");  
    			break;

    			case '1':
    			$row['status']        = lang("active");  
    			break;

    			default:
    			$row['status']        = lang("active");
    			break;
    		};

    		$details[] = $row;
    	} 
    	return $details;

    }

    public function getVoucherDetails()
    {
    	$details = array();
    	$this->db->from('package_upgrade_history');
    	$this->db->order_by('date','DESC');
    	$query = $this->db->get(); 

    	foreach($query->result_array() as $row){
    		$package_id = $row['new_package'];
    		$user_id = $row['user_id'];
    		$cnt = $this->getSponsorCount($user_id,$package_id);
    		if($cnt >= 6)
    		{
    			$row['user_name'] = $this->getUserName($row['user_id']);
    			$row['full_name'] = $this->getFullName($row['user_id']);
    			$row['user_email'] = $this->getUserInfoField("email",$row['user_id']);
    			$row['mobile'] = $this->getUserInfoField("mobile",$row['user_id']);
    			$row['package_name'] = $this->getCurrentPackageName($row['new_package']);
    			$row['referral_count'] = $cnt;				
    			$details[] = $row;
    		}
    	} 
    	return $details;

    }



    public function getSalesDetails($user_id ='',$start_date ='',$end_date='',$payment_method='all')
    {
    	$this->load->model('Signup_model');
    	$details = array();
    	$this->db->from('package_upgrade_history');
    	if($user_id !=''){
    		$this->db->where('user_id',$user_id);
    	}
    	if($payment_method !='all'){
    		$this->db->where('method',$payment_method);
    	}
    	if($start_date !='' && $end_date !=''){
    		$start_date = date("Y-m-d 00:00:00", strtotime($start_date));
    		$end_date = date("Y-m-d 23:59:59", strtotime($end_date)); 
    		$this->db->where('date >=', $start_date);
    		$this->db->where('date <=', $end_date);
    	}
    	$this->db->order_by('date','DESC');

    	$query = $this->db->get(); 

    	foreach($query->result_array() as $row){
    		$row['user_name'] = $this->getUserName($row['user_id']);
    		$row['full_name'] = $this->getFullName($row['user_id']);
    		$row['user_email'] = $this->getUserInfoField("email",$row['user_id']);
    		$row['mobile'] = $this->getUserInfoField("mobile",$row['user_id']);
    		$row['package_name'] = $this->getCurrentPackageName($row['new_package']);
    		$row['package_amount'] = $this->Signup_model->getPackageAmount($row['new_package']);
    		$row['package_fee'] = $this->getPackageAdminFee($row['new_package']);
    		$details[] = $row;
    	} 
    	return $details;

    }

    public function getPackageAdminFee($package_id){

    	$admin_fee = 0;
    	$this->db->select('admin_fee');
    	$this->db->from('package_details');
    	$this->db->where('package_id' , $package_id);
    	$this->db->limit(1);
    	$query = $this->db->get();
    	foreach($query->result() as $row){
    		$admin_fee = $row->admin_fee;
    	}
    	return $admin_fee;
    } 

    public function getUpgradedPackages($start_date='',$end_date='',$user_id='',$payment_method='all')
    {

    	$this->load->model('Signup_model');
    	$details=[];
    	$this->db->select('*');
    	$this->db->from('package_upgrade_history');
    	if($user_id !=''){
    		$this->db->where('user_id',$user_id);
    	}
    	if($payment_method !='all'){
    		$this->db->where('method',$payment_method);
    	}
    	if($start_date !='' && $end_date !=''){
    		$start_date = date("Y-m-d 00:00:00", strtotime($start_date));
    		$end_date = date("Y-m-d 23:59:59", strtotime($end_date)); 
    		$this->db->where('date >=', $start_date);
    		$this->db->where('date <=', $end_date);
    	}
    	$res=$this->db->get();
		 // echo $this->db->last_query(); die();
    	foreach($res->result_array() as $row)
    	{
    		$row['user_name'] = $this->getUserName($row['user_id']);
    		$row['full_name'] = $this->getFullName($row['user_id']);
    		$row['package_name'] = $this->getCurrentPackageName($row['new_package']);
    		$row['package_amount'] = $this->Signup_model->getPackageAmount($row['new_package']);
    		$details[]=$row;
    	}
    	return $details;

    }

    public function getRequestePinReport($user_id='',$start_date='',$end_date='',$status='')
    {
    	$details = array();
    	$this->db->select('re.*');
    	$this->db->select('l.user_name,u.first_name,u.second_name,p.name');
    	$this->db->from('request_epin_details as re');
    	$this->db->join('login_info as l','l.user_id = re.user_id');
    	$this->db->join('user_info as u','u.user_id = re.user_id');
    	$this->db->join('package_details as p','p.package_id = re.package_id');
    	if($user_id)
    	{
    		$this->db->where('re.user_id',$user_id);
    	}
    	if($start_date !='' && $end_date != '')
    	{
    		$start_date = date('Y-m-d 00:00:00',strtotime($start_date));
    		$end_date = date('Y-m-d 23:59:59',strtotime($end_date));
    		$this->db->where('re.added_date >=',$start_date );
    		$this->db->where('re.added_date <=',$end_date);
    	}
    	if($status != 'all')
    	{
    		$this->db->where('re.status',$status);
    	}
    	$query = $this->db->get();
    	foreach($query->result_array() as $row)
    	{
    		$details[] = $row;
    	}
    	return $details;
    }

    public function getPackagesCount($packager_id='')
    {
        $count = 0 ;
        $this->db->select('*');
        $this->db->from('project_packages');
        $this->db->where('status !=','deleted');

        if ($packager_id) {
            $this->db->where('user_id',$packager_id);
        }
        
        $count = $this->db->count_all_results();
        return $count;
    }

    public function getPackageDtailsAjax($post_arr=[], $count = 0) {

        $row = $post_arr['start'];
        $rowperpage = $post_arr['length'];

        $details = array();
        $details=array();

        $this->db->select('pp.*')
        ->select('pj.project_name, pj.customer_name')
        ->from('project_packages pp')
        ->where('pp.status!=','deleted')
        ->join('project pj','pj.id = pp.project_id');
        
        $searchValue = $post_arr['search']['value']; // Search value
        if('' != $searchValue) { 

            $where = "(pp.name LIKE '%$searchValue%' 
            OR pj.project_name LIKE '%$searchValue%'
            OR pp.status LIKE '%$searchValue%')";


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
            $this->db->order_by('pp.id','DESC');
        }

        

        if (element('packager_id',$post_arr)) {
            $this->db->where('pp.user_id',$post_arr['packager_id']);
        }if (element('package_id',$post_arr)) {
            $this->db->where('pp.id',$post_arr['package_id']);
        }
        if (element('project_id',$post_arr)) {
            $this->db->where('pp.project_id',$post_arr['project_id']);
        }
        if (element('status',$post_arr)) {
            if($post_arr['status']!='all'){
                $this->db->where('pp.status',$post_arr['status']);
            }
        }

        if (element('start_date',$post_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
            $this->db->where('pp.date_created >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
            $this->db->where('pp.date_created <=', $end_date);
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
            $row['assets_url'] = assets_url();
            $row['date_created']= date('d-m-Y H:i:s', strtotime($row['date_created']));

            if (element('items',$post_arr)==true) {
                $row['items']= $this->getPackageItems($row['id']);
            }
            $print_view ='<div id="printdiv" class="printdiv" style="display: none">
            <table border="0" style="width: 100%;">
            <tr>
            <td>';

            $print_view .="<p>{$row['project_name']}</p></td>";
            $print_view .="<p style='font-weight: bold'> {$row['code']} </p>";
            $print_view .="<p>{$row['date_created']}</p></td>";

            $print_view .="<small style='font-weight: bold'> {$row['code']} </small>";
            $print_view .='</td>';

            $print_view .=' <td align="right">' ;

            $print_view .='<table border="0" style="width: 100%;">
            <tr>' ;
            $print_view .="<td align='right'> <img src='".base_url('assets/images/qr_code/package/')."{$row['code']}.png' style='max-width: 100px'>
            </td>";
            $print_view .='</tr>
            <tr>' ;
            $print_view .='</tr>
            </table>' ;

            $print_view .='</td>' ;

            $print_view .='</tr>
            </table>
            </div>' ;
            $row['print_view']=$print_view;

            $details[] = $row;
        }
        return $details;
    }



}