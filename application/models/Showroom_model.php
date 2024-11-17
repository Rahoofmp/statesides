<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Showroom_model extends Base_model {

	function __construct() {
		parent::__construct();

	}

	public function insertMeeting($post_arr)
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('code',$post_arr['code']);
		$this->db->set('created_date',$date);
		$this->db->set('customer_id',$post_arr['customer_name']);
		$this->db->set('user_id',implode(",",$post_arr['user_name']));
		$this->db->set('sales_id',$post_arr['sales_name']);
		$this->db->set('status','active');
		$this->db->set('created_by',log_user_id());
		$res = $this->db->insert('meeting_mint');

		return $this->db->insert_id();
	}
	public function deleteSalesQuotation($id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('meeting_mint');
		return $res;
	}
	public function getMeetingCount()
	{
		$this->db->select('*');
		$this->db->from('meeting_mint');
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getMeetingDetailAjax($post_arr=[],$count=''){
// print_r($post_arr);die();
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];
		$this->db->select('s.*')
		->select('s.status as status')
		->select('ci.customer_username as customer_name')
		->select('li.user_name as salesman_name')
		->from('meeting_mint s')
		->join('customer_info ci','ci.customer_id = s.customer_id')
		->join('login_info li','li.user_id = s.sales_id');

		if(element('user_id',$post_arr)){
			$this->db->where('s.created_by',$post_arr['user_id']);
		}
		if (element('meeting_id',$post_arr)) {
			$this->db->where('s.id',$post_arr['meeting_id']);
		}
		if (element('salesman_id',$post_arr)) {
			$this->db->where('s.sales_id',$post_arr['salesman_id']);
		}

		if (element('designer_id',$post_arr)) {
			$this->db->where('s.created_by',$post_arr['designer_id']);
		}
		
		if (element('customer_id',$post_arr)) {
			$this->db->where('s.customer_id',$post_arr['customer_id']);
		}

		if (element('type',$post_arr)) {
			if($post_arr['type']!='all'){
				$this->db->where('s.type',$post_arr['type']);
			}
		}
		if (element('status',$post_arr)) {
			if($post_arr['status']!='all'){
				$this->db->where('s.status',$post_arr['status']);
			}
		}
		if (element('start_date',$post_arr)) {
			$start_date = date("Y-m-d 00:00:00", strtotime($post_arr['start_date'])); 
			$this->db->where('s.created_date >=', $start_date); 
		}

		if (element('end_date',$post_arr)) {
			$end_date = date("Y-m-d 23:59:59", strtotime($post_arr['end_date']));  
			$this->db->where('s.created_date <=', $end_date);
		}
		if (element('order',$post_arr)) {
			$this->db->order_by("s.{$post_arr['order']}", $post_arr['order_by']);
		}
		$searchValue = $post_arr['search']['value'];
		if('' != $searchValue) {
			$where = "(code LIKE '%$searchValue%'
			OR date LIKE '%$searchValue%'
			OR s.status LIKE '%$searchValue%' )";
			$this->db->where($where);
		}
		if($count) {
			return $this->db->count_all_results();
		}
		$this->db->limit($rowperpage, $row);
		$query = $this->db->get(); 
		$i=1;
		foreach($query->result_array() as $row){	
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );

			$img_path = './assets/images/qr_code/meeting/'. $row['code'].'.png' ;

			if (!file_exists( $img_path)) { 
				$tempDir = './assets/images/qr_code/meeting/';  
				$codeContents = base_url('login/showroom-items-print/'.$row['enc_id']);
				QRcode::png($codeContents, $tempDir.''.$row['code'].'.png', QR_ECLEVEL_L, 5);				
			}

			$row['date_added']= date('d-m-Y', strtotime($row['created_date']));
			$row['index'] =$post_arr['start']+$i;
			$print_view = '<div id="printdiv" class="printdiv" style="display: none">';
			$print_view .= '<table border="0" style="width: 100%;"><tr>';
			$print_view .= '<td>';
			$print_view .= "<p><h3>MeetingMint</h3></p>";
			// $print_view .= "<p>{$row['name']}</p>";
			// $print_view .= "<p>{$row['code']}</p>";
			// $print_view .= "<p>{$row['paint_code']}</p>";
			// $print_view .= "<p>{$row['category_name']}</p>";
			$print_view .= "<td   style='vertical-align: top;'>";
			$print_view .= '<table border="0" style="width: 100%;"><tr>';
			$print_view .= '<thead>';
			$print_view .= '<br>';
			$print_view .= '<br>';
			$print_view .= "<th align='left'><img src='".assets_url('images/logo/')."print_logo.png'  style='max-width: 100px' ></th>";
			$print_view .= '<tr align="left">';
			$print_view .= "<th><img src='".base_url('assets/images/qr_code/meeting/')."{$row['code']}.png' style='max-width: 100px ' ></th>";
			$print_view .= '</tbody>';
			$print_view.='</thead>';
			$print_view.='</table>';
			$print_view.='</td>';
			$print_view.='</tr>';
			$print_view .= '</tr>';
			$print_view.=  '<td colspan="2" align="center">
			<p ><small > www.pinetreelane.com </small></p>
			</td>';
			$print_view .= '</tr></table>';

			$print_view .= '</div>';

			$row['print_view']= $print_view;

			$details[] = $row;
			$i++;
		}
		return $details;

	}
	public function getSampleAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];
		$this->db->select('it.*')
		->select('ct.category_name, ct.main_category')
		->from('sample it')        
		->join('vat v','it.vat = v.id')
		->join('category ct','ct.id = it.category');

		if (element('item_id',$post_arr)) { 
			$this->db->where('it.id', $post_arr['item_id']);
		}
		if ( element( 'category_id', $post_arr) ) {
			$this->db->where('it.category', $post_arr['category_id'] );
		}
		if ( element( 'status', $post_arr) != 'all' ) {
			$this->db->where('it.status', $post_arr['status'] );
		}
		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 
			$where = "(it.code LIKE '%$searchValue%' 
			OR it.name LIKE '%$searchValue%'
			OR ct.category_name LIKE '%$searchValue%')";
			$this->db->where($where);
		}
		if($count) {
			return $this->db->count_all_results();
		}
		$this->db->limit($rowperpage, $row);
		$this->db->order_by('it.id', 'DESC');
		$query = $this->db->get();  
		$i=1;
		foreach($query->result_array() as $row){
			$row['index'] =$post_arr['start']+$i;
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['images']= $this->getItemImages($row['id']);
			$details[] = $row;
			$i++;
		}
		return $details;

	}
	public function getMaxMeetingId() {
		$id = NULL;
		$this->db->select_max('id');
		$this->db->from('meeting_mint');
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$id = $row->id;
		}
		return $id;
	}
	public function insertSampleItems( $meeting_id, $post)
	{

		$this->db->set('meeting_id', $meeting_id);
		$this->db->set('sample_id', $post['sample_id']); 
		$this->db->set('date_added', $post['date']); 
		$this->db->set('note', $post['note']); 
		$this->db->set('price', $post['price']); 
		$this->db->set('status','yes');
		$this->db->set('activity_id',log_user_id()); 
		$res=$this->db->insert('meeting_mint_sample');
		return $res;
	}

	public function updateMeetingSamples($id,$post_arr=[])
	{
		$this->db->set('total_items', 'total_items + ' . $post_arr['total_items'], FALSE)
		->set('total_amount', 'total_amount + ' . $post_arr['total_amount'], FALSE);
		if(element('ad_note',$post_arr)){
			$this->db->set('ad_note',$post_arr['ad_note']);
		}
		$this->db->where('id',$id);
		$res=$this->db->update('meeting_mint'); 
		// echo $this->db->last_query();
		return $res;
	}
	public function getMeetingDetails($id='',$post_arr=[]){
		$details = array(); 
		$this->db->select('s.*')
		->select('s.status as status')	
		->select('ci.customer_username as customer_name,ci.email as customer_email,ci.mobile as customer_mobile,ci.address as customer_address')
		->select('li.user_name as user_name')
		->from('meeting_mint s')
		->join('login_info li','li.user_id = s.user_id', 'left')
		->join('customer_info ci','ci.customer_id = s.customer_id', 'left');
		if ($id) {
			$this->db->where('s.id',$id);
		}

		$query = $this->db->get();  
		foreach($query->result_array() as $row){
			$row['salesman_name']= $this->Base_model->getSalesName($row['sales_id']);
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['id'] );
			$row['items']= $this->getSampleMeetingItems($row['id'], $post_arr);
			$row['images']= $this->getMeetingImages($row['id']);
			$row['enc_customerid']=$this->encrypt_decrypt('encrypt',$row['customer_id']);
			$users = explode(",", $row['user_id']);
			$count=count($users);
			for($i=0 ; $i<$count; $i++)
			{
				$row['user_name_string'][$users[$i]]=$this->Base_model->getUserName($users[$i]);
			}
			$row['count']=$count;
			$details[] = $row;
		}
		return $details;

	}
	public function getSampleMeetingItems($id, $search_arr=[] )
	{
		$this->load->model('Sample_model');
		$details=[];
		$this->db->select('si.*,si.note as spec,si.price as sprice,si.id as sample_meeting_id')
		->select('it.*,c.category_name')
		->join('sample it', 'it.id = si.sample_id')
		->join('category c', 'it.category = c.id');
		$this->db->where('si.status', 'yes')
		->where('si.meeting_id',$id)
		->from('meeting_mint_sample si');  
		$res=$this->db->get();
		foreach($res->result_array() as $row)
		{
			$row['enc_id']= $this->Base_model->encrypt_decrypt( 'encrypt', $row['sample_meeting_id'] );
			$row['date_added']= date('d-m-Y H:i:s', strtotime($row['date_added']));
			$row['item_images']=$this->getItemImages($row['sample_id']);	
			$row['lprice']=price_code($row['sprice']); 

			$details[]=$row;
		}
		return $details;
	}
	
	public function getItemImages($sample_id='')
	{
		$this->db->select('image')
		->from('sample_images');
		if ($sample_id) {
			$this->db->where('sample_id',$sample_id);
		}
		$res=$this->db->get();
		$details=null;
		foreach($res->result() as $row)
		{
			$details=$row->image;
			return $details;
		}
	}
	public function gettMeetingSampleDetails($id)
	{
		$details=array();
		$this->db->select('*')
		->from('meeting_mint_sample')
		->where('id',$id);
		$query=$this->db->get();
		foreach ($query->result_array() as $row) {
			$details=$row;
		}
		return $details;
	}
	public function deleteMeetingMintSample($id,$meeting_id)
	{
		$this->db->set('status','deleted');
		$this->db->where('id',$id);
		$res=$this->db->update('meeting_mint_sample');
		if($res){
			$this->TotalItems($id,$meeting_id);
		}
		return $res;
	}
	public function TotalItems($id,$meeting_id)
	{
		$this->db->set('total_items','ROUND(total_items -1,2)', FALSE);
		$this->db->where('id',$meeting_id);

		$res=$this->db->update('meeting_mint');
		return $res;
	}

	public function updateSampleMeetingMint($id,$post_arr)
	{
		$this->db->set('customer_id',$post_arr['customer_name']);
		$this->db->set('user_id',$post_arr['user_name']);
		$this->db->set('sales_id',$post_arr['sales_name']);
		$this->db->set('status','active');
		$this->db->set('ad_note',$post_arr['ad_note']);
		$this->db->where('id',$id);

		$res = $this->db->update('meeting_mint');
		return $res;
	}

	public function updateMeetingMintNote($id,$post_arr)
	{ 
		// print_r($post_arr);die();
		$this->db->set('ad_note',$post_arr['ad_note']);
		$this->db->where('id',$id); 
		$res = $this->db->update('meeting_mint'); 
		return $res;
	}


	public function getMeetingMintNote($id)
	{ 
		$ad_note = '';
		$this->db->select("ad_note");
		$this->db->from("meeting_mint");
		$this->db->where('id', $id);
		$query = $this->db->get(); 
		foreach ($query->result()AS $row) {
			$ad_note = $row->ad_note;
		}

		return $ad_note; 
	}

	

	public function getShowroomIdByCode($code, $customer_id='') {
		$id = 0 ;
		$this->db->select("id")
		->from("meeting_mint")
		->where('code', $code);
		if($customer_id){
			$this->db->where('customer_id', $customer_id);
		}
		$query = $this->db->get(); 
		foreach ($query->result()AS $row) {
			$id = $row->id;
		}

		return $id;
	}

	public function addMeetingImages($meeting_id='',$image='')
	{
		// print_r($meeting_id);die();
		$this->db->set('meeting_id',$meeting_id)
		->set('image',$image)
		->set('date',date('Y-m-d H:i:s'));
		return $this->db->insert('meeting_images');
	}
	public function getMeetingImages($meeting_id='')
	{
		$this->db->select('image,id')
		->from('meeting_images');
		$this->db->where('status',1);
		if ($meeting_id) {
			$this->db->where('meeting_id',$meeting_id);
		}
		$res=$this->db->get();
		$details=[];
		// echo $this->db->last_query();die();
		foreach($res->result_array() as $row)
		{
			$details[]=$row;
		}
		return $details;
	}
	public function deleteMeetingImages($image_id)
	{
		$this->db->set('status',0);
		$this->db->where('id',$image_id);
		$res=$this->db->update('meeting_images');
		// echo $this->db->last_query();die();
		return $res;
	}

}