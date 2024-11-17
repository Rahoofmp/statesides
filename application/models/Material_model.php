<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Material_model extends Base_model {

    function __construct() {
        parent::__construct();

    }

    public function insertMaterialReciept($details, $items)
    {


        // print_r($receipt);
        // die();
        $res = $this->db->insert_batch('material_receipt', $details);
        $receipt_id = $this->db->insert_id(); 

        if($receipt_id){
            if($this->Material_model->insertMaterialItems($items, $receipt_id)){
                return $receipt_id;
            }else{
                return  FALSE;
            } 
        }else{
            return  FALSE;
        }

    }
    public function updateAllMaterialReceipt($details, $items,$receipt_id)
    {


        // print_r($receipt);
        // die();
        $res = $this->db->update_batch('material_receipt', $details,'id');


        if($res){
            if($this->Material_model->insertMaterialItems($items, $receipt_id)){
                return $receipt_id;
            }else{
                return  FALSE;
            } 
        }else{
            return  FALSE;
        }

    }
    public function insertMaterialIssue($details, $items)
    {

        // print_r($receipt);
        // die();
        $res = $this->db->insert_batch('material_issue', $details);
        $receipt_id = $this->db->insert_id(); 

        if($receipt_id){
            if($this->Material_model->insertIssuedMaterialReciept($items, $receipt_id)){
                return $receipt_id;
            }else{
                return  FALSE;
            } 
        }else{
            return  FALSE;
        }

    }

    public function updateMaterialIssue($details, $id)
    {

        // print_r($receipt);
        // die();
        $this->db->set('voucher_date',$details['voucher_date'])
        ->set('voucher_number',$details['voucher_number'])
        ->set('last_updated',$details['last_updated'])
        ->set('total_issued_qty', 'ROUND(total_issued_qty + ' . $details['total_issued_qty']. ',2)', FALSE)
        ->set('total_cost', 'ROUND(total_cost + ' . $details['total_cost']. ',2)', FALSE)
        ->where('id',$id);
        
        $res = $this->db->update('material_issue');
        return $res;
    }
    public function updateMaterialReceipt($details, $id)
    {


        $this->db->set('bill_number',$details['bill_number'])
        ->set('name',$details['name'])
        ->set('supplier_id',$details['supplier_id'])
        ->where('id',$id);
        
        $res = $this->db->update('material_receipt');

        return $res;
    }

    public function updateVoucherDetails($details, $id)
    {

        // print_r($receipt);
        // die();
        $this->db->set('voucher_date',$details['voucher_date'])
        ->set('voucher_number',$details['voucher_number'])
        ->set('last_updated',date('Y-m-d H:i:s'))
        ->where('id',$id);
        
        $res = $this->db->update('material_issue');
        return $res;
    }

    public function insertIssuedMaterialReciept($items, $issue_id)
    {

        foreach ($items as $key => $value) {
            $items[$key]['issue_id'] = $issue_id;
        }
        $receipt_id =  $this->db->insert_batch('material_issue_receipts', $items); 
        if($receipt_id){
            return TRUE;
        }else{
            return  FALSE;
        }
    }
    public function insertMaterialItems($items, $receipt_id)
    {

        foreach ($items as $key => $value) {
            $items[$key]['material_receipt_id'] = $receipt_id;
        }
        $receipt_id =  $this->db->insert_batch('material_receipt_items', $items); 
        
        if($receipt_id){
            if($this->Material_model->deductItemStock($items)){
                return TRUE;
            }else{
                return  FALSE;
            }
        }else{
            return  FALSE;
        }
    }

    public function deductItemStock($items) {
        foreach ($items as $key => $value) {
            $this->db->set('total_quantity', 'total_quantity - ' . $value['qty'] , FALSE);
            $this->db->where('id', $value['item_id'] );
            $query = $this->db->update('items');
            if(!$query){
                return  FALSE;
            }

        }

        return $query;
    }


    public function getReceiptInfo($search_arr=[])
    {
        $details=[];
        $this->db->select('mr.*, si.user_name as supplier_user_name')
        ->where('mr.id',$receipt_id)
        ->join('supplier_info si', 'mr.supplier_id = si.id');

        $res = $this->db->get('material_receipt mr');

        foreach($res->result_array() as $row)
        {
            if($receipt_id){
                return $row;
            }
            $details[]=$row;

        }
        return $details;
    }

    public function getMaterialReceiptCount()
    {
        $this->db->select('id');
        $this->db->from('material_receipt');
        $count = $this->db->count_all_results();
        return $count;
    }
    public function getMaterialIssueCount()
    {
        $this->db->select('id');
        $this->db->from('material_issue');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function getInventoryCount()
    {
        $this->db->select('id');
        $this->db->from('material_issue');   
        $count = $this->db->count_all_results();
        // print_r($count);die();
        return $count;
    }

    public function getMaterialReceiptAjax($post_arr=[],$count=''){
        $details = array(); 
        $row = $post_arr['start'];
        $rowperpage = $post_arr['length'];


        $this->db->select('mr.*,jo.project_id')
        ->select('si.user_name as supplier_user_name, si.name as supplier_name, si.email')
        ->join('material_receipt_items mri','mri.material_receipt_id=mr.id')
        ->join('job_orders jo','jo.id = mri.job_order_id')    
        ->join('supplier_info si','si.id = mr.supplier_id')
        ->from('material_receipt mr') ;

        if (element('reciept_id',$post_arr)) { 
            $this->db->where('mr.id', $post_arr['reciept_id']);
        }
        if (element('project_id',$post_arr)) { 
            $this->db->where('jo.project_id', $post_arr['project_id']);
        }

        if ( element( 'supplier_user_name', $post_arr) ) {
            $this->db->where('si.user_name', $post_arr['supplier_user_name'] );
        }

        if ( element( 'bill_number', $post_arr) ) {
            $this->db->where('mr.bill_number', $post_arr['bill_number'] );
        }

        if ( element( 'name', $post_arr) ) {
            $this->db->where('mr.name', $post_arr['name'] );
        }
        if ( element( 'status', $post_arr) ) {
            $this->db->where('mr.status', $post_arr['status'] );
        }

        if (element('start_date',$post_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
            $this->db->where('mr.date_added >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
            $this->db->where('mr.date_added <=', $end_date);
        }


        $searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
        if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

            $where = "(mr.bill_number LIKE '%$searchValue%' 
            OR si.user_name LIKE '%$searchValue%'
            OR mr.name LIKE '%$searchValue%'
            OR mr.date_added LIKE '%$searchValue%' )";
            $this->db->where($where);
        }

        $this->db->group_by('mr.id');
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        if($count) {
            return $this->db->count_all_results();
        }
        $this->db->limit($rowperpage, $row);
        $this->db->order_by('mr.id', 'DESC');
        $query = $this->db->get();
        // echo $this->db->last_query();die();  

        $i=1;

        foreach($query->result_array() as $row){
            $row['index'] =$post_arr['start']+$i;
            $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           

            if (element('items',$post_arr)==true) {
                $search_arr['material_receipt_id'] = $row['id'];
                $row['items']= $this->getMaterialItemDetails($search_arr);

            }
            if (element('reciept_id',$post_arr)) { 
                return $row;
            }

            $details[] = $row;
            $i++;
        }
        return $details;

    }
    public function getMaterialIssueAjax($post_arr=[],$count=''){
        $details = array(); 
        $row = $post_arr['start'];
        $rowperpage = $post_arr['length'];

        $this->db->select('mi.*,mir.job_order_id,jo.project_id')
        ->join('material_issue_receipts mir','mir.issue_id = mi.id')
        ->join('job_orders jo','jo.id = mir.job_order_id')
        ->from('material_issue mi');        


        if (element('issue_id',$post_arr)) { 
            $this->db->where('mi.id', $post_arr['issue_id']);
        }

        if ( element( 'project_id', $post_arr) ) {
            $this->db->where('jo.project_id', $post_arr['project_id'] );
        }


        if ( element( 'voucher_number', $post_arr) ) {
            $this->db->where('mi.voucher_number', $post_arr['voucher_number'] );
        }

        if ( element( 'voucher_date', $post_arr) ) {
            $this->db->where('mi.voucher_date', $post_arr['voucher_date'] );
        }
        if ( element( 'status', $post_arr) ) {
            $this->db->where('mi.status', $post_arr['status'] );
        }

        if (element('start_date',$post_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
            $this->db->where('mi.date_added >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
            $this->db->where('mr.date_added <=', $end_date);
        }


        $searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
        if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

            $where = "(mi.voucher_number LIKE '%$searchValue%' 


            OR mi.date_added LIKE '%$searchValue%' )";
            $this->db->where($where);
        }

        if($count) {
            return $this->db->count_all_results();
        }

        $this->db->limit($rowperpage, $row);
        $this->db->order_by('mi.id', 'DESC');
        $query = $this->db->get();  
        // echo $this->db->last_query();die();
        $i=1;

        foreach($query->result_array() as $row){
            $row['index'] =$post_arr['start']+$i;
            $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
            $row['allocated_qty']=$this->getTotalAllocatedQty($row['id']);           
            $row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
            if ($row['difference']<0) {
                $row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
            }          
            else {
                $row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
            }          

            // if (element('items',$post_arr)==true) {
            //     $search_arr['material_receipt_id'] = $row['id'];

            // }
            $row['items']= $this->getMaterialIssuedItemDetails($row['id']);
            if (element('issue_id',$post_arr)) { 
                return $row;
            }

            $details[] = $row;
            $i++;
        }
        return $details;

    }


    public function getInventoryAjax($post_arr,$count=''){
        $details = array(); 
        $row = $post_arr['start'];
        $rowperpage = $post_arr['length'];
        // print_r($rowperpage);die();

        $this->db->select('mi.*')
        ->from('material_issue mi')      
        ->join('material_issue_receipts mr', ' mi.id  = mr.issue_id ')
        ->join('job_orders jo', ' jo.id  = mr.job_order_id ')
        ->join('items it', ' it.id  = mr.item_id ')
        ->join('project p', ' p.id  = jo.project_id ');
        if (element('project_id',$post_arr)) { 
            $this->db->where('p.id', $post_arr['project_id']);
        }
        $this->db->limit($rowperpage, $row);
        $this->db->order_by('mi.id', 'DESC');
        if($count) {
            return $this->db->count_all_results();
        }
        $query = $this->db->get();  
        // echo $this->db->last_query($query);die();
        $i=1;

        foreach($query->result_array() as $row){
            $row['index'] =$post_arr['start']+$i;
            $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
            $row['allocated_qty']=$this->getTotalAllocatedQty($row['id']);           
            $row['difference']=  $row['allocated_qty']-$row['total_issued_qty']; 
            if ($row['difference']<0) {
                $row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
            }          
            else {
                $row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
            }          

            $details[] = $row;
            $i++;


        }
        return $details;

    }


    public function checkItemsAssigning($data)
    {
        $details=[];
        $this->db->select( 'name, code, cost, total_quantity as unit' )
        ->where( 'id', $data['item_id'] )
        // ->where( 'total_quantity >=', $data['item_qty'] )
        ->limit( '1' );
        $res = $this->db->get('items');

        foreach($res->result_array() as $row)
        {   
            $details = $row;

        }
        return $details;
    }
    public function checkMaterialItemsAssigning($data)
    {

        $details=[];
        $this->db->select( 'name, bill_number, total_cost, total_qty as unit' )
        ->where( 'id', $data['material_id'] )
        // ->where( 'total_qty >=', $data['item_qty'] )
        ->limit( '1' );
        $res = $this->db->get('material_receipt');

        foreach($res->result_array() as $row)
        {   

            $details = $row;

        }
        return $details;
    }
    public function checkMaterialReceiptByBill($data)
    {

        $details=[];
        $this->db->select( '*' )
        ->where( 'bill_number', $data['bill_number'] )
        // ->where( 'total_qty >=', $data['item_qty'] )
        ->limit( '1' );
        $res = $this->db->get('material_receipt');

        foreach($res->result_array() as $row)
        {   

            $details = $row;

        }
        return $details;
    }


    public function getMaterialItemDetails($post_arr){
        // print_r($post_arr);die();
        $details = array(); 
        $this->db->select('mri.*')
        ->select('i.code item_code, i.name item_name, i.price, i.type')
        ->select('jo.order_id job_order_id')
        ->from('material_receipt_items mri')
        ->join('items i', 'i.id = mri.item_id')
        ->join('job_orders jo', 'jo.id = mri.job_order_id', 'LEFT')
        ->where( 'mri.status', 'active');

        if ( element( 'job_order_id', $post_arr ) ) {
            $this->db->where( 'mri.job_order_id', $post_arr['job_order_id']);
        }

        if ( element( 'material_receipt_id', $post_arr ) ) {
            $this->db->where( 'mri.material_receipt_id', $post_arr['material_receipt_id']);
        }

        if ( element( 'item_id', $post_arr ) ) {
            $this->db->where( 'mri.item_id', $post_arr['item_id']);
        }

        if ( element( 'item_type', $post_arr ) ) {
            $this->db->where_in( 'i.type', $post_arr['item_type']);
        }

        if (element('start_date',$post_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
            $this->db->where('mri.date_added >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
            $this->db->where('mri.date_added <=', $end_date);
        }

        $query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

        foreach($query->result_array() as $row){

            $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
            if ( element( 'id', $post_arr ) ) {
                return $row;
            }

            $details[] = $row;
        }
        return $details;

    }
    public function getMaterialReceiptItemDetails($post_arr){
        // print_r($post_arr);die();
        $details = array(); 
        $this->db->select('mri.*')
        ->select('mr.name')
        // ->select('i.code item_code, i.name item_name, i.price, i.type')
        // ->select('jo.order_id job_order_id')
        ->from('material_receipt_items mri')
        ->join('material_receipt mr', 'mr.id = mri.material_receipt_id')

        ->where( 'mri.status', 'active');

        if ( element( 'job_orderid', $post_arr ) ) {
            $this->db->where( 'mri.job_order_id', $post_arr['job_orderid']);
        }

        if ( element( 'material_receipt_id', $post_arr ) ) {
            $this->db->where( 'mri.material_receipt_id', $post_arr['material_receipt_id']);
        }

        if ( element( 'item_id', $post_arr ) ) {
            $this->db->where( 'mri.item_id', $post_arr['item_id']);
        }

        // if (element('start_date',$post_arr)) {
        //     $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
        //     $this->db->where('mri.date_added >=', $start_date); 
        // }

        // if (element('end_date',$post_arr)) {
        //     $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
        //     $this->db->where('mri.date_added <=', $end_date);
        // }
        $this->db->limit(1);
        $query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

        foreach($query->result_array() as $row){

            $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );         
            $row['issued_qty']= $this->getIssuedQty($row['material_receipt_id'],$row['job_order_id'],$row['item_id']);  
            $row['difference']=$row['qty']-$row['issued_qty'];       
            // $row['issued_qty']= 0;         
            $details = $row;
        }
        return $details;

    }
    public function getIssuedQty($receipt_id='',$job_order_id='',$item_id='')
    {
        $count = 0 ;
        $this->db->select_sum("issued_qty");
        $this->db->from('material_issue_receipts');
        $this->db->where('receipt_id', $receipt_id);
        $this->db->where('job_order_id', $job_order_id);
        $this->db->where('item_id', $item_id);

        $query = $this->db->get(); 
        foreach ($query->result() AS $row) {

            return $row->issued_qty;
        }
    }
    public function getMaterialItem($item_id){
        // print_r($post_arr);die();
        $details = array(); 
        $this->db->select('*')
        ->from('material_receipt_items')
        ->where( 'status', 'active')
        ->where('id',$item_id);
        $query = $this->db->get();   

        foreach($query->result_array() as $row){


            $details=$row;

        }
        return $details;

    }
    public function getMaterialIssuedItemDetails($issue_id='',$post_arr=''){
        // print_r($post_arr);die();
        $details = array(); 
        $this->db->select('mir.*')
        ->select('mr.*, si.user_name as supplier_user_name')
        ->select('mir.*')
        ->from('material_issue_receipts mir')
        ->where('mir.issue_id',$issue_id)
        ->join('material_receipt mr', 'mir.receipt_id = mr.id')
        ->join('supplier_info si', 'mr.supplier_id = si.id')
        ->where('mir.status', 'active');
        $query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

        foreach($query->result_array() as $row){

            $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );           
            $row['job_order']= $this->Base_model->getJobOrderId($row['job_order_id'] );           
            $row['item_name']= $this->Base_model->getItemNameById($row['item_id']);
            $row['allocated_qty']=$this->getTotalAllocatedQty($row['issue_id']);           
            $row['difference']=  $row['allocated_qty']-$row['issued_qty']; 
            if ($row['difference']<0) {
                $row['diff']='<span class="neg-difference">'.$row['difference'].'<span>';
            }          
            else {
                $row['diff']='<span class="pos-difference">'.$row['difference'].'<span>';
            }                 
            // if ( element( 'id', $post_arr ) ) {
            //     return $row;
            // }

            $details[] = $row;
        }
        return $details;

    }

    public function getItemDetails($post_arr){
        // print_r($post_arr);die();
        $details = array(); 
        $this->db->select('i.*, c.category_name, c.code category_code')
        ->from('items i')
        ->join('category c', 'c.id = i.category');

        if ( element( 'item_code', $post_arr ) ) {
            $this->db->where( 'i.code', $post_arr['item_code']);
        }

        if ( element( 'material_receipt_id', $post_arr ) ) {
            $this->db->where( 'i.material_receipt_id', $post_arr['material_receipt_id']);
        }

        if ( element( 'item_id', $post_arr ) ) {
            $this->db->where( 'i.id', $post_arr['item_id']);
        }

        if (element('status',$post_arr) ) {
            if($post_arr['status']!='all'){
                $this->db->where('dn.status',$post_arr['status']);
            }
        }

        if (element('start_date',$post_arr)) {
            $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
            $this->db->where('i.date >=', $start_date); 
        }

        if (element('end_date',$post_arr)) {
            $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
            $this->db->where('i.date <=', $end_date);
        }


        if (element('limit',$post_arr)) {
            $this->db->limit($post_arr['limit']);
        }

        if (element('order',$post_arr)) {
            $this->db->order_by($post_arr['order'], $post_arr['order_by']);
        }

        $query = $this->db->get();   
        // echo $this->db->last_query();
        // die();

        foreach($query->result_array() as $row){

            if ( element( 'item_id', $post_arr ) ||  element( 'item_code', $post_arr ) ) {
                return $row;
            }

            $details[] = $row;
        }
        return $details;

    }

    public function deleteMaterialItem($id)
    {
        $item_details=$this->getMaterialItem($id);
        $total_cost=$item_details['qty']*$item_details['cost'];

        $this->db->set('status','deleted');
        $this->db->where('id',$id);
        $res=$this->db->update('material_receipt_items');
        // echo $this->db->last_query();
        if ($res) {
            $this->db->set('total_qty', 'ROUND(total_qty- ' . $item_details['qty']. ',2)', FALSE);
            $this->db->set('total_cost', 'ROUND(total_cost- ' . $total_cost. ',2)', FALSE);
            $this->db->where('id',$item_details['material_receipt_id']);
            $ded=$this->db->update('material_receipt');

        }
        // die();
        return $res;
    }
    public function deleteMaterialIssueItem($id)
    {
        $this->db->set('status','deleted');
        $this->db->where('id',$id);
        $res=$this->db->update('material_issue_receipts');
        // echo $this->db->last_query();
        // die();
        return $res;
    }
    public function deleteMaterialReceipt($id)
    {
        $this->db->set('status','deleted');
        $this->db->where('id',$id);
        $res=$this->db->update('material_receipt');
        return $res;
    }

    public function deleteMaterialIssue($id)
    {
        $this->db->set('status','deleted');
        $this->db->where('id',$id);
        $res=$this->db->update('material_issue');
        return $res;
    }
    public function getTotalAllocatedQty($issue_id='')
    {
        $this->db->select_sum('mr.total_qty')
        ->from('material_receipt mr')
        // ->from('material_receipt_items mri')
        ->join('material_issue_receipts mir', 'mr.id = mir.receipt_id');

        if ($issue_id) {
            $this->db->where('mir.issue_id',$issue_id);
        }
        $query  = $this->db->get(); 
        foreach($query->result_array() as $row){
            $total_qty =  $row["total_qty"];
        }
        return $total_qty;


    }
    public function checkUnique($table_name,$field,$value) {
        $count = 0 ;
        $this->db->select("COUNT(id) as count");
        $this->db->from($table_name);
        $this->db->where($field, $value);

        $query = $this->db->get(); 
        foreach ($query->result()AS $row) {
            $count = $row->count;
        }

        return $count;
    }
    public function getItemIdByCode($code) {
        $count = 0 ;
        $this->db->select("id");
        $this->db->from('items');
        $this->db->where('code', $code);

        $query = $this->db->get(); 
        foreach ($query->result() AS $row) {

            return $row->id;
        }

    }
    public function getJobOrderIdByOrderId($order_id) {
        $count = 0 ;
        $this->db->select("id");
        $this->db->from('job_orders');
        $this->db->where('order_id', $order_id);

        $query = $this->db->get(); 
        foreach ($query->result() AS $row) {

            return $row->id;
        }

    }

    public function getMaxIssueId() {
        $id = NULL;
        $this->db->select_max('id');
        $this->db->from('material_issue');
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }
   // public function getMaterialReceiptItemDetails($post_arr){
   //      // print_r($post_arr);die();
   //      $details = array(); 
   //      $this->db->select('mri.*')
   //      ->select('mr.name')
   //      // ->select('i.code item_code, i.name item_name, i.price, i.type')
   //      // ->select('jo.order_id job_order_id')
   //      ->from('material_receipt_items mri')
   //      ->join('material_receipt mr', 'mr.id = mri.material_receipt_id')

   //      ->where( 'mri.status', 'active');

   //      if ( element( 'job_orderid', $post_arr ) ) {
   //          $this->db->where( 'mri.job_order_id', $post_arr['job_orderid']);
   //      }

   //      if ( element( 'material_receipt_id', $post_arr ) ) {
   //          $this->db->where( 'mri.material_receipt_id', $post_arr['material_receipt_id']);
   //      }

   //      if ( element( 'item_id', $post_arr ) ) {
   //          $this->db->where( 'mri.item_id', $post_arr['item_id']);
   //      }

   //      // if (element('start_date',$post_arr)) {
   //      //     $start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
   //      //     $this->db->where('mri.date_added >=', $start_date); 
   //      // }

   //      // if (element('end_date',$post_arr)) {
   //      //     $end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
   //      //     $this->db->where('mri.date_added <=', $end_date);
   //      // }
   //      $this->db->limit(1);
   //      $query = $this->db->get();   
   //      // echo $this->db->last_query();
   //      // die();

   //      foreach($query->result_array() as $row){

   //          $row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );         
   //          $row['issued_qty']= $this->getIssuedQty($row['material_receipt_id'],$row['job_order_id'],$row['item_id']);  
   //          $row['difference']=$row['qty']-$row['issued_qty'];       
   //          // $row['issued_qty']= 0;         
   //          $details = $row;
   //      }
   //      return $details;

   //  }

   

}