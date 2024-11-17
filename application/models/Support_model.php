<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Support_model extends Base_model {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getSupportSettings($settings_name, $status=1){
		$details = array();
		$this->db->select('*');
		$this->db->where('status', $status);
		$query = $this->db->get('support_'.$settings_name);
		foreach ($query->result_array() as $row) {
			$details[] = $row;
		}
		return $details;
	}

	public function checkPriorityExist($priority_id) {
		$flag = false;
		$this->db->select('id');
		$this->db->from('support_priority');
		$this->db->where('id', $priority_id);
		$this->db->where('status', 1);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows()) {
			return true;
		}
		return $flag;
	}

	public function checkTopicExist($topic_id) {
		$flag = false;
		$this->db->select('id');
		$this->db->from('support_topic');
		$this->db->where('id', $topic_id);
		$this->db->where('status', 1);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows()) {
			return true;
		}
		return $flag;
	}

	public function checkTicketExist($ticket_id) {
		$flag = false;
		$this->db->select('ticket_id');
		$this->db->from('support_ticket');
		$this->db->where('ticket_id', $ticket_id);
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows()) {
			return true;
		}
		return $flag;
	}

	public function insertTicket($data){
		$ticket_details = array(
			'status' => FALSE,
			'ticket_id' => 0,
			'ticket_name' => '',
		);
		$current_time = date('Y-m-d H:i:s');
		$ticket_prefix = value_by_key('ticket_prefix');
		$this->db->set('owner_id', $data['user_id']);
		$this->db->set('priority_id', $data['priority']);
		$this->db->set('status_id', 1);
		$this->db->set('topic_id', $data['topic']);
		$this->db->set('subject', $data['subject']);
		$this->db->set('created_date', $current_time);
		$this->db->set('last_updated', $current_time);
		$this->db->set('read_status', 0);
		$inserted = $this->db->insert('support_ticket');
		if($inserted){

			$ticket_id = $this->db->insert_id();
			$this->db->set('name', $ticket_prefix.$ticket_id);
			$this->db->where('ticket_id', $ticket_id);
			$this->db->update('support_ticket');

			$this->db->set('ticket_id', $ticket_id);
			$this->db->set('user_id', $data['user_id']);
			$this->db->set('message', $data['message']);
			$this->db->set('date', $current_time);
			$inserted = $this->db->insert('support_ticket_activity');
			if($inserted){
				$ticket_details = array(
					'status' => TRUE,
					'ticket_id' => $ticket_id,
					'ticket_name' => $ticket_prefix.$ticket_id,
				);	
			}
		}
		return $ticket_details;
	}

	public function updateTicketActivities($data){
		$this->db->set('ticket_id', $data['ticket_id']);
		$this->db->set('user_id', $data['user_id']);
		$this->db->set('message', $data['message']);
		$this->db->set('date', $data['date']);
		$inserted = $this->db->insert('support_ticket_activity');
		if($inserted){
			$this->db->set('last_updated', $data['date']);
			$this->db->where('ticket_id', $data['ticket_id']);
			$this->db->update('support_ticket');
		}
		return $this->db->affected_rows();
	}

	public function getAllTickets($ticket_id = '', $owner_id = '', $status_arr= array(), $priority_arr= array(), $topic_arr= array() ){
		$details = array();
		$this->db->select('stks.*');
		$this->db->select('sp.name priority_name');
		$this->db->select('ss.name status_name');
		$this->db->select('st.name topic_name');
		if($ticket_id){
			$this->db->where('stks.ticket_id', $ticket_id);			
		}
		if($owner_id){
			$this->db->where('stks.owner_id', $owner_id);			
		}
		if(!empty($status_arr)){
			$this->db->where_in('stks.status_id', $status_arr);			
		}
		if(!empty($priority_arr)){
			$this->db->where_in('stks.priority_id', $priority_arr);			
		}
		if(!empty($topic_arr)){
			$this->db->where_in('stks.topic_id', $topic_arr);			
		}
		$this->db->from('support_ticket stks');
		$this->db->join('support_priority sp', 'stks.priority_id = sp.id');
		$this->db->join('support_status ss', 'stks.status_id = ss.id');
		$this->db->join('support_topic st', 'stks.topic_id = st.id');
		$this->db->order_by('stks.last_updated', 'DESC');
		$query = $this->db->get();
		 //echo $this->db->last_query();die();
		foreach ($query->result_array() as $row) {
			$row['user_name'] = $this->getUserName($row['owner_id']);
			$row['full_name'] = $this->getUserInfoField('first_name', $row['owner_id']) . ' '. $this->getUserInfoField('second_name', $row['owner_id']);
			$details[] = $row;
		}
		return $details;
	}

	public function getTicketUserid($ticket_id){
		$owner_id = NULL;
		$this->db->select('owner_id');
		$this->db->where('ticket_id', $ticket_id);
		$this->db->from('support_ticket');
		$query = $this->db->get();
		foreach ($query->result_array() as $row) {
			$owner_id = $row['owner_id'];
		}
		return $owner_id;
	}

	public function ticketIdToName($ticket_id){
		$ticket_name = NULL;
		$this->db->select('name');
		$this->db->where('ticket_id', $ticket_id);
		$this->db->from('support_ticket');
		$query = $this->db->get();
		foreach ($query->result_array() as $row) {
			$ticket_name = $row['name'];
		}
		return $ticket_name;
	}

	public function ticketNameToId($ticket_name){
		$ticket_id = 0;
		$this->db->select('ticket_id');
		$this->db->where('name', $ticket_name);
		$this->db->from('support_ticket');
		$query = $this->db->get();
		// echo $this->db->last_query();die();
		foreach ($query->result_array() as $row) {
			$ticket_id = $row['ticket_id'];
		}
		return $ticket_id;
	}

	public function getTicketStatusToid($status_name){
		$status_id = NULL;
		$this->db->select('id');
		$this->db->where('name', $status_name);
		$this->db->from('support_status');
		$query = $this->db->get();
		foreach ($query->result_array() as $row) {
			$status_id = $row['id'];
		}
		return $status_id;
	}

	public function getTicketPriorityToid($priority_name){
		$priority_id = NULL;
		$this->db->select('id');
		$this->db->where('name', $priority_name);
		$this->db->from('support_priority');
		$query = $this->db->get();
		foreach ($query->result_array() as $row) {
			$priority_id = $row['id'];
		}
		return $priority_id;
	}

	public function getTicketActivity($ticket_id, $limit = 0, $page = 0){
		$details = array();
		$this->db->select('sta.*');
		$this->db->select('li.user_name as replied_user');
		$this->db->where('sta.ticket_id', $ticket_id);		
		$this->db->from('support_ticket_activity as sta');
		$this->db->order_by('sta.date', 'DESC');
		$this->db->join('login_info as li', 'li.user_id = sta.user_id');

		if ($limit) {
			$this->db->limit($limit, $page);
		}
		$query = $this->db->get();
		foreach ($query->result_array() as $row) {
			$details[] = $row;
		}
		return $details;
	}

	public function getGraphValues($user_id ='')
	{
		$details = $total_tickets = $open_tickets = $resolved_tickets = array();
		$open_id = $this->getTicketStatusToid('Open');
		$resolved_id = $this->getTicketStatusToid('Resolved');

		for($i=1; $i<=12; $i++)
		{
			$start_date = date("Y-$i-01 00:00:00");
			$end_date = date("Y-$i-31 23:59:59");
			$total_count = $this->getTicketCountByType($user_id, '', '', $start_date, $end_date);
			$total_tickets[$i]['total_count'] = $total_count; 
		}
		$details['total_tickets'] = $total_tickets;

		for($i=6; $i>=0; $i--)
		{
			$time = strtotime("$i days ago");
			$day_string = date('D', $time);
			$start_date = date("Y-m-d 00:00:00", $time);
			$end_date = date("Y-m-d 23:59:59", $time);

			$open_count = $this->getTicketCountByType($user_id, 'status_id', $open_id, $start_date, $end_date);
			$open_tickets[$i]['xAxis'] = $day_string; 
			$open_tickets[$i]['yAxis'] = $open_count; 

			$resolved_count = $this->getTicketCountByType($user_id, 'status_id', $resolved_id, $start_date, $end_date);
			$resolved_tickets[$i]['xAxis'] = $day_string; 
			$resolved_tickets[$i]['yAxis'] = $resolved_count; 
		}
		$details['open_tickets'] = $open_tickets;
		$details['resolved_tickets'] = $resolved_tickets;

		return $details;
	}

	public function getTicketCountByType($user_id='', $type='', $type_id='', $start_date = '', $end_date =''){
		$this->db->from('support_ticket');
		if($start_date){
			$this->db->where('last_updated >=', $start_date);
		}
		if($end_date){
			$this->db->where('last_updated <=', $end_date);
		}
		if($type)
			$this->db->where($type, $type_id);
		if($user_id)
			$this->db->where('owner_id', $user_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function updateTicketStatus($data, $status_id){

		$update_array = array();
		foreach ($data as $ticket_id) {
			$update_array[] = array(
				'ticket_id' => $ticket_id,
				'status_id' => $status_id
			);
		}
		$this->db->update_batch('support_ticket', $update_array, 'ticket_id');
		return $this->db->count_all_results();
	}

}
