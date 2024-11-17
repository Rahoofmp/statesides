<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class Zone_model extends Base_model {

    function __construct() {
        parent::__construct();

    }

    public function getAllCountries($country_id = '') {
        $details = array();
        $this->db->select('*');
        if($country_id){
            $this->db->where('country_id', $country_id);
        }
        $this->db->from('countries');
        $query = $this->db->get();
        $i = 0;
        foreach ($query->result_array() as $row) {
            $details[$i]['country_id'] = $row['country_id'];
            $details[$i]['country_name'] = $row['country_name'];
            $details[$i]['country_code'] = $row['country_code'];
            $details[$i]['phone_code'] = $row['phone_code'];
            $i++;
        }
        return $details;
    }

    public function IdToCountryName($country_id) {
        $country_name = NULL;
        $this->db->select("country_name");
        $this->db->from("countries");
        $this->db->where("country_id", $country_id);
        $this->db->limit(1);
        $res = $this->db->get();
        foreach ($res->result() as $row) {
            $country_name = $row->country_name;
        }
        return $country_name;
    }
    
    public function NameToCountryId($country_name) {
        $country_id = NULL;
        $this->db->select("country_id");
        $this->db->from("countries");
        $this->db->where("country_name", $country_name);
        $this->db->limit(1);
        $res = $this->db->get();
        foreach ($res->result() as $row) {
            $country_id = $row->country_id;
        }
        return $country_id;
    }
    
}


