<?php  defined('BASEPATH') OR exit('No direct script access allowed');
class Sample_model extends Base_model {

	function __construct() {
		parent::__construct();

	}
	public function addSample($post)
	{
		// print_r($post);die();
		$date=date('Y-m-d H:i:s');
		$this->db->set('code', $post['code'])
		->set('name', $post['name'])
		->set('date',$date)
		->set('category', $post['main_category'])
		->set('cost', $post['cost'])
		->set('price', $post['price'])
		->set('brand', $post['brand'])
		->set('status', '1')
		->set('origin', $post['origin'])
		->set('paint_code', $post['paint_code'])
		->set('size', $post['size'])
		->set('type', $post['type'])
		->set('grade', $post['grade']);
		if(log_user_type()=='admin')
		{
			$this->db->set('supplier',$post['supplier']);
		}
		$this->db->set('created_by', log_user_id());
		$this->db->insert('sample');
		return $this->db->insert_id();
		
	}
	public function getSampleCount()
	{
		$this->db->select('id');
		$this->db->from('sample');
		$count = $this->db->count_all_results();
		return $count;
	}
	public function getSampleAjax($post_arr=[],$count=''){
		$details = array(); 
		$row = $post_arr['start'];
		$rowperpage = $post_arr['length'];
		$this->db->select('it.*')
		->select('ct.category_name, ct.main_category')
		->from('sample it')        
		->join('category ct','ct.id = it.category');

		if (element('item_id',$post_arr)) { 
			$this->db->where('it.id', $post_arr['item_id']);
		}
		// if (element('salesman_id',$post_arr)) { 
		// 	$this->db->where('it.created_by', $post_arr['salesman_id']);
		// }
		if (element('storekeeper_id',$post_arr)) { 
			$this->db->where('it.created_by', $post_arr['storekeeper_id']);
		}
		if (element('purchaser_id',$post_arr)) { 
			$this->db->where('it.created_by', $post_arr['purchaser_id']);
		}
		if ( element( 'category_id', $post_arr) ) {
			$this->db->where('it.category', $post_arr['category_id'] );
		}
		if ( element( 'status', $post_arr) != 'all' ) {
			$this->db->where('it.status', $post_arr['status'] );
		}
		$searchValue = element('search', $post_arr) ? (element('value', $post_arr['search'] ) ? $post_arr['search']['value']: FALSE ): FALSE;
		if($searchValue) { 

            // $searchValue = $post_arr['search']['value'];

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
			$row['name']=strtoupper($row['name']);
			$row['code']=strtoupper($row['code']);
			$row['paint_code']=strtoupper($row['paint_code']);
			$row['category_name']=strtoupper($row['category_name']);
			$row['price_code']= price_code($row['price']); 
			$img_path = './assets/images/qr_code/sample/'. $row['code'].'.png' ;

// 			if (!file_exists( $img_path)) { 
				$tempDir = './assets/images/qr_code/sample/';  
				$codeContents = base_url('login/sample-master-details/
					'.$row['enc_id']);
				QRcode::png($codeContents, $tempDir.''.$row['code'].'.png', QR_ECLEVEL_L, 5);				
// 			}

			$row['images']= $this->getItemImages($row['id']);
// 			$print_view = '<div id="printdiv" class="printdiv" style="display: none">';
// 			$print_view .= '<table border="0" style="width: 100%;"><tr>';
// 			$print_view .= '<td>';
// 			$print_view .= "<p><h3><b>SAMPLE APPROVAL</b></h3></p>";
// 			$print_view .= "<p><b>{$row['name']}</b></p>";
// 			$print_view .= "<p><b>{$row['code']}</b></p>";
// 			$print_view .= "<p><b>{$row['paint_code']}</b></p>";
// 			$print_view .= "<p><b>{$row['category_name']}</b></p>";
// 			$print_view .= "<p><b>{$row['price_code']}</b></p>";
// 			$print_view .= "<td   style='vertical-align: top;'>";
// 			$print_view .= '<table border="0" style="width: 100%;"><tr>';
// 			$print_view .= '<thead>';
// 			$print_view .= '<br>';
// 			$print_view .= '<br>';
// 			$print_view .= "<th align='left'><img src='".assets_url('images/logo/')."print_logo.png'  style='max-width: 200px' ></th>";
// 			$print_view .= '<tr align="left">';
// 			$print_view .= "<th><img src='".base_url('assets/images/qr_code/sample/')."{$row['code']}.png' style='max-width: 180px ' ></th>";
// 			$print_view .= '</tbody>';
// 			$print_view.='</thead>';
// 			$print_view.='</table>';
// 			$print_view.='</td>';
// 			$print_view.='</tr>';
// 			$print_view .= '</tr>';
// 			$print_view.=  '<td colspan="2" align="center">
// 			<p ><small > www.pinetreelane.com </small></p>
// 			</td>';
// 			$print_view .= '</tr></table>';

// 			$print_view .= '</div>';


            	$print_view = '<div id="printdiv" class="printdiv" style="display: none;padding-top:0;margin : 0;">';
			$print_view .= '<table border="0" style="width: 100%;padding-top:0;margin : 0;"><tr>';
			$print_view .= "<td   style='vertical-align: top;'>";
			$print_view .= "<h5 style='margin : 0; padding-top:0;padding-left:0;'>SAMPLE APPROVAL</h5>";
			$print_view .= "<h6 style='margin : 0; padding-top:0;'>{$row['name']}</h6>";
			$print_view .= "<h6 style='margin : 0; padding-top:0;'>{$row['code']}</h6>";
			$print_view .= "<h6 style='margin : 0; padding-top:0;'>{$row['paint_code']}</h6>";
			$print_view .= "<h6 style='margin : 0; padding-top:0;>{$row['category_name']}</h6>";
			$print_view .= "<h6 style='margin : 0; padding-top:0;'>{$row['price_code']}</h6>";
			$print_view .= "<h6 style='margin : 0px; padding-top:0;text-align:center;'>www.pinetreelane.com</h6>";
			$print_view .= "<td   style='vertical-align: top;'>";
			$print_view .= '<table border="0" style="width: 100%;">';
			$print_view .= '<thead>';
			$print_view .= "<th align='right'><img src='".assets_url('images/logo/')."print_logo.png'  style='max-width: 100px;padding-top:0;' ></th>";
			$print_view .= '<tr align="right">';
			$print_view .= "<th><img src='".base_url('assets/images/qr_code/sample/')."{$row['code']}.png' style='max-width: 80px;padding-top:0;' ></th>";
			$print_view .= '</tr>';
			$print_view.='</thead>';
			$print_view.='</table>';
			$print_view.='</tr>';
			$print_view.='</td>';
			
			
			$print_view .= '</tr></table>';
			

			$print_view .= '</div>';

			$row['print_view']= $print_view;

			$details[] = $row;
			$i++;
		}
		
		// print_r($details);die();
		return $details;

	}
	public function updateSample($post,$id)
	{
		$date=date('Y-m-d H:i:s');
		$this->db->set('name', $post['name'])
		->set('date',$date)
		->set('category', $post['main_category'])
		->set('cost', $post['cost'])
		->set('price', $post['price'])
		->set('brand', $post['brand'])
		->set('origin', $post['origin'])
		->set('paint_code', $post['paint_code'])
		->set('size', $post['size'])
		->set('type', $post['type'])
		->set('grade', $post['grade']);
		if(log_user_type()=='admin')
		{
			$this->db->set('supplier',$post['supplier']);
		}		
		$this->db->where('id',$id);
		$res = $this->db->update('sample');
		return $res;
	}
	public function getAllSampleDetails($id='', $images=true)
	{
		$this->load->model('Delivery_model');
		$details=[];
		$this->db->select('it.*,c.category_name,c.main_category')
		->from('sample it')
		->join('category c', 'it.category = c.id')
		->where('it.status!=','0');

		if ($id) {
			$this->db->where('it.id',$id)->limit(1);
		}
		$res=$this->db->get();
		// echo $this->db->last_query();
		// die();
		foreach($res->result_array() as $row)
		{
			// print_r($row['id']);
			$row['enc_id']=$this->encrypt_decrypt('encrypt',$row['id']);
			if ($row['main_category']!=0) {
				$row['sub_category_name']=$row['category_name'];
				$row['category_name']=$this->Delivery_model->getCategoryName($row['main_category']);
			}
			else{
				$row['sub_category_name']=$this->Delivery_model->getCategoryName($row['main_category']);
			}
			$row['supplier_name']=$this->Base_model->getSupplierName($row['supplier']);
			if($images == true){
				$row['item_images']=$this->getItemImages($row['id']);
			}

			if($id){
				
				return $row;
			}

			$details[]=$row;
		}
		return $details;
	}
	public function deleteSample($id)
	{
		$this->db->set('status','0');
		$this->db->where('id',$id);
		$res=$this->db->update('sample');

		if($this->db->affected_rows()){
			return $res;
		}

		return false;
	}
	public function getSampleCode($field='',$id)
	{
		$details=[];
		$this->db->select($field)
		->where('id', $id)
		->where('status',1)
		->from('sample');  
		$res=$this->db->get();
		// echo $this->db->last_query();;
		foreach($res->result() as $row)
		{
			return $row->$field;
		}
		return $details;
	}
	public function addSampleImages($sample_id='',$image='')
	{
		// print_r($item_id);die();
		$this->db->set('sample_id',$sample_id)
		->set('image',$image)
		->set('date',date('Y-m-d H:i:s'));
		return $this->db->insert('sample_images');
	}
	public function UpdateSampleImages($sample_id='',$image='')
	{
		// print_r($image);die();
		$this->db->where('sample_id',$sample_id)
		->set('image',$image);
		return $this->db->update('sample_images');
	}
	public function deleteItemImage($image_id='')
	{
		// print_r($image_id);die();
		$this->db->set('status',0);
		$this->db->where('id',$image_id);
		$res=$this->db->update('sample_images');

		// ->where('id',$image_id);
		return $res;
	}
	public function getItemImages($sample_id='')
	{

		$this->db->select('image,id');
		$this->db->where('status',1)
		->from('sample_images');
		
		if ($sample_id) {
			$this->db->where('sample_id',$sample_id);
		}

		$res=$this->db->get();
		// echo $this->db->last_query();die();
		$details=null;
		foreach($res->result() as $row)
		{
			$details[]=$row;
		}
		return $details;


	}
	public function getAllSampleItemsDetails($post_arr)
	{
		$details = array(); 
		$this->db->select('i.*, c.category_name, c.code category_code')
		->from('sample i')
		->join('category c', 'c.id = i.category');

		if ( element( 'code', $post_arr ) ) {
			$this->db->where( 'i.code', $post_arr['code']);
		}

        // if ( element( 'material_receipt_id', $post_arr ) ) {
        //     $this->db->where( 'i.material_receipt_id', $post_arr['material_receipt_id']);
        // }

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

			if ( element( 'item_id', $post_arr ) ||  element( 'code', $post_arr ) ) {
				return $row;
			}

			$details[] = $row;
		}
		return $details;

	}
	public function getSampleCodeAuto() {
		$id = NULL;
		$this->db->select_max('id');
		$this->db->from('sample');
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$id = $row->id;
		}
		return $id;
	}
	
}