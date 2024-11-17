<?php  defined('BASEPATH') OR exit('No direct script access allowed');


class Network_model extends Base_model {
	
	public $tooltip_data = array();
	public $tree_member;
	public $go_to_upline = NULL;
	public $level_li_count = array();

	function __construct() {
		parent::__construct();
		$this->tree_member ="";
	}


	public function getAllTreeUsers($user_id, $tree_type = "plan_tree",$entry_no = 1) {	
		if(log_user_type() == 'admin')
			$this->TREE_LEVEL = 6;	
		else
			$this->TREE_LEVEL = value_by_key("tree_level");	
		if($tree_type == 'board1' || $tree_type == 'board2' || $tree_type == 'board3' || $tree_type == 'board4' || $tree_type == 'board5' || $tree_type == 'board6' || $tree_type == 'board7')
		{
			$order_id = $this->getBoardTableAutoId($user_id,$tree_type,$entry_no);
		}
		else
			$order_id = 0;


		$this->generateTree($user_id, $tree_type,0,$order_id,1);

	}


	public function getBoardTableAutoId($user_id,$tree_type,$entry_no=1) {
		$id = 1;
		$this->db->select('id');
		$this->db->from($tree_type);
		$this->db->where('user_id', $user_id);
		$this->db->where('entry_no', $entry_no);
		$this->db->order_by('id', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$id = $row->id;
		}
		return $id;
	}


	public function generateTree($user_id, $tree_type, $level = 0,$order_id=0,$entry_no = 1) {

		$member_arr = [];
		
		if ($level < $this->TREE_LEVEL) {

			$member_arr = $this->getTreeUserDetails($user_id,$tree_type);
			// print_r($member_arr);
			// die();

			$child_nodes = $this->getUserChildNodes($user_id, $tree_type,$order_id);
			$child_count = count($child_nodes);
			
			$this->tree_member .= '
			<div class="ne-item">';

			$this->setUplineButton( $member_arr['user_id'], $member_arr['father_id'], $tree_type, $level );

			$this->tree_member .= ( $child_count ) ? '
			<div class="ne-item-parent popover_wrapper">' : '';

			if( $member_arr['status'] == 'server'){
				$this->tree_member .= $this->createNullNode($member_arr);
			}else{
				$this->tree_member .= $this->createTreeNode($member_arr, $tree_type, $child_count,$entry_no);
			}
			
			$this->getTooltipData( $member_arr ); 


			$this->tree_member .= ( $child_count ) ? '</div>' : '';

			if ( $child_count ) {
				$new_level = $level + 1;

				if ($new_level < $this->TREE_LEVEL ) {
					$this->tree_member .= '
					<div class="ne-item-children">';
					foreach ($child_nodes as $child_id) {

						$this->tree_member .= '
						<div class="ne-item-child">'; 

						if($tree_type == 'board1' || $tree_type == 'board2' || $tree_type == 'board3' || $tree_type == 'board4' || $tree_type == 'board5' || $tree_type == 'board6' || $tree_type == 'board7')
						{
							$this->generateTree($child_id['user_id'], $tree_type, $new_level ,$child_id['id'],$child_id['entry_no']);
						}
						else
						{
							$this->generateTree($child_id, $tree_type, $new_level );

						}
						
						$this->tree_member .= '
						</div>';

					}
					$this->tree_member .= '
					</div>';
				}

			}
			$this->tree_member .= '
			</div>';

		}

		return $this->tree_member;
	}
	
	public function createTreeNode( $member_arr, $tree_type, $child_count=0,$entry_no=1 ){
		$user_name = $member_arr['user_name'];
		if($entry_no > 1)
		{
			$user_name = $user_name.'_'.($entry_no-1);
			$user_link = "";
		}
		else
		{
			$user_link = 'onClick="generateTree('. $member_arr['user_id'] .', '. "'". log_user_type()."'".', '. "'". $tree_type ."'".','.$entry_no.')"' ;
		}


		if(log_user_type() == 'user')
			$user_link = "";


		$this->tree_member .= '
		<a href="javascript:void()" '. $user_link. '>
		<div class="person">
		<img src="'.assets_url().'images/tree/'. $member_arr['user_photo'] .'" alt="'. $member_arr['user_name'] .'">
		<p class="name">
		'. $user_name .'
		</p>
		</div>
		</a>'; 
	}
	
	public function createNullNode( $member_arr, $child_count=0 ){

		//$user_link = base_url().'signup/index/'. $member_arr['parent_name'] ;
		$user_link = "#";


		$this->tree_member .= '
		<a href="'. $user_link. '">
		<div class="person">
		<img src="'.assets_url().'images/tree/plus.png" alt="'. lang('text_add_member') .' title="'. lang('text_add_member') .'">
		</div>
		</a>'; 
	}

	public function getTooltipData( $member_arr ){
		if( $member_arr['status'] != 'server'){
			$this->tree_member .= '
			<div class="popover_content">
			<p class="popover_message">'. $member_arr['first_name'] . ' '.  $member_arr['second_name'] .'</p> 
			<table class="table">
			' ;
			if($member_arr['parent_name']){
				$this->tree_member .= '
				<tr>
				<th>Parent</th>
				<td>'. $member_arr['parent_name'] .'</td>
				</tr>
				' ;
			}
			if($member_arr['sponsor_name']){
				$this->tree_member .= '
				<tr>
				<th>Sponsor</th>
				<td>'. $member_arr['sponsor_name'] .'</td>
				</tr>
				' ; 
			}

			/*if($member_arr['package_name']){
				$this->tree_member .= '
				<tr>
				<th>Package</th>
				<td>'. $member_arr['package_name'] .'</td>
				</tr>
				' ;
			}*/
						
			$this->tree_member .= '
			<tr>
			<th>Join date</th>
			<td>'. $member_arr['joining_date'] .'</td>
			</tr>
			' ; 
			
			$this->tree_member .= '
			</table>  
			</div>
			' ;
		}
	}

	public function setUplineButton( $user_id, $father_id, $tree_type, $level ){

		

		/*if ($level < 1) {
			$button = '<a href="javascript:generateTree('. $father_id .', '."'". log_user_type() ."'". ', '. "'".$tree_type ."'".')" class="btn go-upline">'.lang("text_go_to_upline") .' <i class="fa  fa-arrow-circle-o-up"></i></a>';


			if (log_user_type() != 'employee') { 
				//root user
				if ($user_id != log_user_id()) {
					$this->tree_member .= $button;
				}
			} else {
				//root user
				if ($user_id != $this->Base_model->getAdminId()) {
					$this->tree_member .= $button;
				}
			}
		} */
	}

	public function getTreeUserDetails($user_id,$tree_type) {
		$mlm_plan = value_by_key('mlm_plan');

		$select_arr = ['user_name', 'user_type', 'status', 'position', 'father_id', 'sponsor_id', 'package_id', 'joining_date', 'user_rank_id'];

		if ( $mlm_plan == "Binary") {
			array_push($select_arr, 'left_carry');
			array_push($select_arr, 'left_total');
			array_push($select_arr, 'right_carry');
			array_push($select_arr, 'right_total');
		}

		$member_arr = $this->Base_model->getUserLoginInfo($user_id, $select_arr);

		$member_arr["user_id"] = $user_id;
		$member_arr["user_rank"] = "NA";

		if ($member_arr["status"] != "server") {

			$user_select_arr = ['user_photo', 'email', 'first_name', 'second_name'];

			$user_info = $this->Base_model->getUserDetails($user_id, $user_select_arr);
			$member_arr = array_merge( $member_arr, $user_info );
			if($tree_type == "plan_tree")
			{
				if ($member_arr["package_id"]) {
					$member_arr["user_photo"] = 'active.png';
				}
				else
					$member_arr["user_photo"] = 'inactive.png';
			}
			else
			{
				$sponsor_id = $member_arr['sponsor_id'];
				if($user_id == log_user_id())
				{
					$member_arr["user_photo"] = 'active.png';
				}
				elseif($sponsor_id == log_user_id())
				{
					$member_arr["user_photo"] = 'active.png';
				}
				else
					$member_arr["user_photo"] = 'nonreferral.png';

			}


			if (value_by_key('rank_status') && $member_arr['user_rank_id']) {
				$member_arr["user_rank"] = $this->Base_model->getUserRankName( $user_id);
			}else{
				$member_arr["user_rank"] = "NA";
			}

			// if (value_by_key('package_status') == "yes" && $member_arr['package_id']) {
			if ($member_arr['package_id'] && $member_arr['package_id'] > 0  ) {
				
				$member_arr["package_name"] = $this->Base_model->getCurrentPackageName($member_arr['package_id']);
			}else{
				$member_arr["package_name"] = "NA"; 
			}

		}else{
			$member_arr["user_photo"] = "no-user.png";
		}

		$member_arr["sponsor_name"] = $this->Base_model->getUserName( $member_arr["sponsor_id"] );
		$member_arr["parent_name"] = $this->Base_model->getUserName( $member_arr["father_id"] );
		$member_arr["referal_count"] = $this->Base_model->getSponsorCount( $user_id );

		return $member_arr;
	}


	public function getUserChildNodes($user_id, $type = "plan_tree",$order_id=0) {
		$child_nodes = array();

		if ($user_id) {
			$this->db->select('DISTINCT(user_id)');
			if ($type == "sponsor_tree") {
				$this->db->where("sponsor_id", $user_id);
				$this->db->where("status !=", "server");
			} elseif($type== "plan_tree") {
				$this->db->where("father_id", $user_id);
			}
			else
			{
				$this->db->select('id');
				$this->db->select('entry_no');
				$this->db->where("father_id", $user_id);
				$this->db->where("father_auto_id", $order_id);
			}
			//$this->db->order_by("position", "ASC");
			if($type == "plan_tree")
				$query = $this->db->get("login_info");
			else
				$query = $this->db->get("$type");

			foreach ($query->result_array() AS $rows) {
				if($type == 'board1' || $type == 'board2' || $type == 'board3' || $type == 'board4' || $type == 'board5' || $type == 'board6' || $type == 'board7')
				{
					$child_nodes[] = $rows;
				}
				else
				{
					$child_nodes[] = $rows['user_id'];

				}
			}
		}
		return $child_nodes;
	}

	public function getUserLeftAndRight($user_id, $type) {
		$this->db->select("left_$type, right_$type");
		$this->db->where('user_id', $user_id);
		$result = $this->db->get('login_info');
		$result = $result->result_array();
		return $result[0];
	}


	public function getRefferedUsers($user_id) {
		$package_color = array(
			1 => "#dff0d8",
			2 => "#dae3f4",
			3 => "#c7feb1",
			4 => "#dfc6e9",
			5 => "#dfc6e9",
			6 => "#faf2cc",
			7 => "#f2dede",
			8 => "#f2dede",
			9 => "#f2dede"
		);
		$result = array();
		$this->db->select("user_name, user_id, father_id, user_level");
		$this->db->where('sponsor_id', $user_id);
		$query = $this->db->get('login_info');
		foreach ($query->result_array() as $row) {
			$row["full_name"] = $this->getFullName($row["user_id"]);
			$row["invested_amount"] = $this->getUserTotalInvetments($row["user_id"]);
			$row["father_name"] = $this->getUserName($row["father_id"]);
			$row["father_full_name"] = $this->getFullName($row["father_id"]);
			$row["encoded_user_id"] = $this->encrypt_decrypt('encrypt', $row["user_id"]);


			$row["background_color"] = "#f3f9f0";				


			$result[] = $row;
		}
		return $result;
	}

	public function getUserPosition($user_id) {
		$position = 1;
		$this->db->select('position');
		$this->db->from('login_info');
		$this->db->where('user_id', $user_id);
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$position = $row->position;
		}
		return $position;
	}


	public function getUserDownlines($user_id,$level) {

		$result = array();
		$this->load->model('Cron_jobs_model');
		$level_users = $this->Cron_jobs_model->getLevelUsers($user_id,$level);

		foreach ($level_users as $row) {
			$details = array();

			$father_id = $this->Base_model->getFatherID($row);
			$details['user_id'] = $row;
			$details["user_name"] = $this->Base_model->getUserName($row);
			$details["father_name"] = $this->Base_model->getUserName($father_id);
			$details["full_name"] = $this->Base_model->getFullName($row);
			$details["rank_name"] = $this->Base_model->getUserRankName($row);
			$details["joining_date"] = $this->Base_model->getLoginInfoField('joining_date', $row);
			$details["encoded_user_id"] = $this->Base_model->encrypt_decrypt('encrypt', $row);

			$result[] = $details;
		}
		return $result;
	}

	public function getBinaryPosition($user_id){
		$default_leg = 1;
		$this->db->select('default_leg');
		$this->db->from('login_info');
		$this->db->where('user_id' , $user_id);
		$this->db->limit(1);
		$query = $this->db->get();
		foreach ($query->result() as $row) {
			$default_leg = $row->default_leg;
		}
		return $default_leg;
	}

	public function updateBinaryPosition($user_id , $position){

		$this->db->set('default_leg' , $position);
		$this->db->where('user_id' , $user_id);
		$this->db->limit(1);
		$result = $this->db->update('login_info');
		return $result;
	}
	public function getBoardDetails($user_id='',$board_no='')
	{
		$details=[];
		$this->db->select('*');
		$this->db->from("board$board_no");
		$this->db->where('user_id',$user_id);
		$res=$this->db->get();
		foreach($res->result_array() as $row)
		{
			$row['user_id_enc'] = $this->Base_model->encrypt_decrypt('encrypt',$row['user_id']);
			$row['user_name'] = $this->Base_model->getUserName($row['user_id']);
			$row['package_value']=$this->Network_model->getPackageValue($board_no);
			$row['entry_no'] = $this->Base_model->encrypt_decrypt('encrypt',$row['entry_no']);
			// $row['board_no'] = $this->Base_model->encrypt_decrypt('encrypt',$board_no);
			
			$details[]=$row;
		}
		return $details;
	}

	public function getPackageValue($board_no)
	{
		$details='';
		$this->db->select('package_value');
		$this->db->from('package_details');
		$this->db->where('package_id',$board_no);
		$res=$this->db->get();
		foreach($res->result() as $row)
		{
			$details=$row->package_value;
		}
		return $details;
	}

}
