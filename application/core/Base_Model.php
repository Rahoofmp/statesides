<?php  
class Base_Model extends CI_Model 
{

    function __construct()
    {   
        parent::__construct();
    }

    public function begin() {
        $this->db->trans_start();
    }

    public function commit() {
        $this->db->trans_commit();
    }

    public function rollback() {
        $this->db->trans_rollback();
    }

    public function redirect($message, $page_to_reload, $message_type = false) {
        $details = array();
        $redirect_message["flashdata"] = $message;
        $redirect_message["type"] = $message_type;
        $redirect_message["box"] = true;
        $this->session->set_flashdata('redirect_message', $redirect_message);
        redirect($page_to_reload, 'refresh');
    }

    public function getUserDetails($user_id, $select_arr ='*') 
    {
        $user_details = array(); 
        $this->db->select($select_arr);
        $this->db->from("user_info");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get();
        $this->load->model('Zone_model');

        foreach ($query->result_array() as $row) 
        {
            if( element( 'user_photo', $row )){
                $img_path = $this->config->item('assets_folder').'/images/profile_pic/'. $row['user_photo'] ;
                if (!file_exists( $img_path)) {
                    $row['user_photo'] = 'nophoto.png';
                } 
            }

            return $row;
        }

        return $user_details;
    }
    public function getCustomerDetails($customer_id, $select_arr ='*') 
    {
        $user_details = array(); 
        $this->db->select($select_arr);
        $this->db->from("customer_info");
        $this->db->where("customer_id", $customer_id);
        $query = $this->db->get();

        foreach ($query->result_array() as $row) 
        {
// if( element( 'user_photo', $row )){
//     $img_path = $this->config->item('assets_folder').'/images/profile_pic/'. $row['user_photo'] ;
//     if (!file_exists( $img_path)) {
//         $row['user_photo'] = 'nophoto.png';
//     } 
// }
            $row['user_photo'] = 'nophoto.png';

            return $row;
        }

        return $user_details;
    }

    public function getUserLoginInfo($user_id, $type = 'user') {

        $user_details = array();
        $this->db->select('*');
        $this->db->from("login_info");
        $this->db->where("user_id", $user_id);
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $user_details = $row;
        }
        return $user_details;
    }  

    public function getUserId($username) {
        $user_id = 0;
        $this->db->select('user_id');
        $this->db->from('login_info');
        $this->db->where('user_name', $username);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $user_id = $row->user_id;
        }
        return $user_id;
    }
    public function getUserType($user_id) {
        $user_type = "user";
        $this->db->select('user_type');
        $this->db->from('login_info');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $user_type = $row->user_type;
        }
        return $user_type;
    }

    public function getUserName($user_id) {
        $user_name = NULL;
        $this->db->select('user_name');
        $this->db->from('login_info');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $user_name = $row->user_name;
        }
        return $user_name;
    }

    public function getProjectName($project_id) {
        $name = NULL;
        $this->db->select('project_name');
        $this->db->from('project');
        $this->db->where('id', $project_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $name = $row->project_name;
        }
        return $name;
    }
    public function getPackageName($package_id,$user_id='') {
        $user_name = NULL;
        $this->db->select('name');
        $this->db->from('project_packages');
        $this->db->where('id', $package_id);
// if($user_id){
//     $this->db->where('user_id', $user_id);
// }
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $user_name = $row->name;
        }
        return $user_name;
    } 
    public function getDeliveryCode($delivery_id,$user_id='') {
        $code = NULL;
        $this->db->select('code');
        $this->db->from('delivery_notes');
        $this->db->where('id', $delivery_id);
        if($user_id){
            $this->db->where('user_id', $user_id);
        }
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $code = $row->code;
        }
        return $code;
    }

    public function getPackageProjectID($package_id) {
        $project_id = NULL;
        $this->db->select('project_id');
        $this->db->from('project_packages');
        $this->db->where('id', $package_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $project_id = $row->project_id;
        }
        return $project_id;
    }

    public function getUserStatus($user_id) {
        $status = 0;
        $this->db->select('status');
        $this->db->from('login_info');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $status = $row->status;
        }
        return $status;
    }

    public function insertIntoActivityHistory($user_id, $done_by, $purpose='NA', $data = array())
    {
        $date = date('Y-m-d H:i:s');
        $this->db->set('user_id',$user_id);
        $this->db->set('ip',$this->input->server('REMOTE_ADDR'));
        $this->db->set('done_by',$done_by);
        $this->db->set('date',$date);
        if($data)
            $this->db->set('data',$data);
        $this->db->set('activity',$purpose);
        $result = $this->db->insert('activity');
        return $result;
    }  

    public function getUnreadMessages($user_id,$type=''){
        $details = array();
        $this->db->select('*');
        $this->db->where('to_user',$user_id);
        $this->db->where('read_status','no');
        $this->db->order_by('date', 'DESC');
        $this->db->from('internal_mail_details');
        if($type == 'header'){
            $this->db->limit(5);
        }
        $query = $this->db->get();
        $i = 0;
        foreach($query->result_array() as $row){
            $limit_message = substr($row['message'], 0, 25);
            $details[$i]  = $row;
            $details[$i]['limitted_message'] = $limit_message;
            $details[$i]['user_name'] = $this->getUserName($row['from_user']);
            $details[$i]['user_photo'] = $this->getUserProfilePic($user_id);
            $i++;  
        }
        return $details;

    }

    public function isUsernameValid($user_name) {
        $flag = false;
        $this->db->select('user_id');
        $this->db->from('login_info');
        $this->db->where('user_name', $user_name);
        $this->db->where('status', "1");
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows()) {
            return true;
        }
        return $flag;
    }   

    public function checkEmailExist($email) {
        $count = 0;
        $this->db->select("COUNT(email) as count");
        $this->db->from("user_info");
        $this->db->where('email', $email);
        $query = $this->db->get();
        foreach ($query->result() AS $row) {
            $count = $row->count;
        }
        return $count;
    }

    public function isUserExist($user_id, $user_type='') {
        $count = 0 ;
        $this->db->select("COUNT(user_id) as count");
        $this->db->from("login_info");
        $this->db->where('user_id', $user_id);
        if( $user_type ){
            $this->db->where('user_type', $user_type);
        }
        $this->db->where('status', '1');
        $this->db->where('status !=', 'server');
        $query = $this->db->get();
        foreach ($query->result()AS $row) {
            $count = $row->count;
        }

        return $count;
    }

    public function isDeliveryIdExist($delivery_id, $status='pending') {
        $count = 0 ;
        $this->db->select("COUNT(id) as count");
        $this->db->from("delivery_notes");
        $this->db->where('id', $delivery_id);
        if($status != 'all'){
            $this->db->where('status', $status);
        }
        $query = $this->db->get(); 
        foreach ($query->result()AS $row) {
            $count = $row->count;
        }

        return $count;
    }

    public function isPackageExist($package_id) {
        $count = 0 ;
        $this->db->select("COUNT(id) as count");
        $this->db->from("project_packages");
        $this->db->where('id', $package_id);
        $this->db->where('status', 'pending');
        $query = $this->db->get(); 
        foreach ($query->result()AS $row) {
            $count = $row->count;
        }

        return $count;
    }
    public function isPackageNameExist($package_name) {
        $count = 0 ;
        $this->db->select("COUNT(id) as count");
        $this->db->from("project_packages");
        $this->db->where('name', $package_name);
        $this->db->where('status', 'pending');
        $query = $this->db->get();  
        foreach ($query->result()AS $row) {
            $count = $row->count;
        }

        return $count;
    }

    public function isPackageCodeExist($package_code) {
        $count = 0 ;
        $this->db->select("COUNT(id) as count");
        $this->db->from("project_packages");
        $this->db->where('code', $package_code);
        $this->db->where('status', 'pending');
        $query = $this->db->get(); 
        foreach ($query->result()AS $row) {
            $count = $row->count;
        }

        return $count;
    }
    public function getDepartmentAuto($term='',$department_id='') {

        $output = [];
        $this->db->distinct(true)->select('dept.name,dept.id')
        ->from('department dept');

        if($term)
            $this->db->where("dept.name LIKE '$term%'");
        $this->db->limit(10);
// ->order_by('dept.id','ASC');
        $res = $this->db->get();


        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 

            'text'=>$row['name']];
        }

        return $output;
    }

    public function getDepartmentJobAuto($post) 
    {
        $output = [];
        $this->db->distinct(true)->select('dj.department_id,d.name')
        ->from('department_jobs dj');
        $this->db->where('dj.job_order_id',$post['job_order_id']);  
        $this->db->where("d.name LIKE '{$post['search']}%'");
        $this->db->join('department d', ' dj.department_id = d.id ');
        $this->db->limit(10);
        $res = $this->db->get();

        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['department_id'], 
            'text'=>$row['name']];
        }

        return $output;

    }

    public function getPackageIdByName($name, $project_id='') {
        $id = NULL ;
        $this->db->select("id");
        $this->db->from("project_packages");
        $this->db->where('name', $name); 
        if($project_id){
            $this->db->where('project_id', $project_id); 
        }
        $query = $this->db->get(); 
        foreach ($query->result()AS $row) {
            $id = $row->id;
        }

        return $id;
    }

    public function getPackageIdByCode($package_code) {
        $id = 0 ;
        $this->db->select("id");
        $this->db->from("project_packages");
        $this->db->where('code', $package_code); 
        $query = $this->db->get(); 
        foreach ($query->result()AS $row) {
            $id = $row->id;
        }

        return $id;
    }

    public function getPackageItemIdByName($name) {
        $id = NULL ;
        $this->db->select("id");
        $this->db->from("package_items");
        $this->db->where('name', $name); 
        $query = $this->db->get(); 
        foreach ($query->result()AS $row) {
            $id = $row->id;
        }

        return $id;
    }

    public function getPackageItemIdByCode($item_code) {
        $id = 0 ;
        $this->db->select("id");
        $this->db->from("package_items");
        $this->db->where('code', $package_code); 
        $query = $this->db->get(); 
        foreach ($query->result()AS $row) {
            $id = $row->id;
        }

        return $id;
    }

    public function isPackageItemNameExist($item_name, $package_id='') {
        $count = 0 ;
        $this->db->select("COUNT(id) as count")
        ->from("package_items")
        ->where('name', $item_name)
        ->where('status', 'active');
        if($package_id){            
            $this->db->where('package_id', $package_id);
        }

        $query = $this->db->get();  
        foreach ($query->result()AS $row) {
            $count = $row->count;
        }

        return $count;
    }

    public function getRandomString($length,$check_table,$field_name) {

        $key = NULL;
        $charset = "0123456789";
        for ($i = 0; $i < $length; $i++)
            $key .= $charset[(mt_rand(0, (strlen($charset) - 1)))];
        $randum_id = $key;

        if($field_name == 'user_name' && $check_table == 'login_info'){
            $user_name_prefix = value_by_key('user_name_prefix');
            $user_name_postfix = value_by_key('user_name_postfix');
            $randum_id = $user_name_prefix . $randum_id . $user_name_postfix;
        }

        $this->db->select('*');
        $this->db->from("$check_table");
        $this->db->where("$field_name", $randum_id);
        $query = $this->db->get();
        $count = $query->num_rows();
        if ($count == 0){
            return $key;
        }
        else
            return $this->getRandomString($length,$check_table,$field_name);
    }



    public function getLanguageDetails($language_id="", $type='site_perm', $status = 1)
    {
        $langauge_details = array();
        $this->db->select("*");

        if($status != '-1'){
            $this->db->where($type, $status);
        }

        if($language_id){   
            $this->db->where("language_id", $language_id);
            $this->db->limit(1);
        }
        $query = $this->db->get("language");

        foreach ($query->result_array() as $result) {

            $result['encrypted_id'] = $this->encrypt_decrypt('encrypt', $result['language_id']);
            if($language_id){ 
                return  $result;
            }else{
                $langauge_details[] = $result;
            }
        }
        return $langauge_details;
    }

    public function getfilteredUsers($keyword, $limit = 20)
    {
        $result = array();

        $this->db->select("user_name");
        $this->db->where("status !=","server");
        $this->db->where("user_type !=","admin");
        if($keyword !="." && $keyword !="/")
            $this->db->like("user_name", $keyword, "after");
        $this->db->from("login_info");
        $this->db->limit($limit);
        $this->db->order_by("joining_date",'ASC');
        $this->db->order_by("user_name",'ASC');
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $result[]["result"] = $row["user_name"];
        }
        return $result;
    }  

    public function encrypt_decrypt($action, $string) 
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_key = '123';
        $secret_iv = '123';
// hash
        $key = hash('sha256', $secret_key);

        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }  

    function getLoginInfoField($field_name, $user_id){

        $field_value = NULL;
        $this->db->select($field_name);
        $this->db->where('user_id', $user_id);
        $this->db->from('login_info');
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $field_value = $row->$field_name;
        }
        return $field_value;
    }

    function getUserInfoField($field_name, $user_id){

        $field_value = NULL;
        $this->db->select($field_name);
        $this->db->where('user_id', $user_id);
        $this->db->from('user_info');
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $field_value = $row->$field_name;
        }
        return $field_value;
    }

    function getLangCode( $language_id, $type='site_perm', $status = TRUE){
        $lang_code = "en";
        $this->db->select("code");
        $this->db->where($type, $status);
        $this->db->where("language_id", $language_id);
        $this->db->order_by("sort_order");
        $query = $this->db->get("language");
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) 
            {
                $lang_code = $row['code'];
            }            
        }
        return $lang_code;
    }

    function getSideMenu($user_type, $type= 'site', $status ='1')
    {
        $LANG_CODE = $this->getLangCode($this->software->LANG_ID);
        $permission_type = 'perm_' . $user_type;
        $path_root = base_url() . $user_type."/";
        $current_url = current_uri();
        $menu_arr = [];

        if ( $user_type == 'privileged_user' ) {
            $permission_type = 'perm_pre_user';
        }

        $this->db->select( '*' );
        if($status != 'all')
            $this->db->where( 'status', $status );
        $this->db->where( $permission_type, "1" );
        $this->db->where( 'type', $type );
        $this->db->where( 'parent_id', '#' );
        $this->db->order_by( 'order' );
        $query = $this->db->get( 'menu' );
        $all_menu = $query->result_array();

        $common_pages = ['logout'];
        $common_pages =  array_merge( $common_pages, $this->software->COMMON_PAGES );

        foreach ( $all_menu as $index => $menu ) 
        {
            $is_selected = null;
            $menu_link = $menu['link'];

            $split_pages = explode("/", $menu_link);
            $controller = $split_pages[0]; 
            if ( $menu_link == $current_url ) {
                $is_selected = 'active';
            }   

            $menu_link = str_replace('_', '-', $menu_link );
            if( in_array( $controller, $common_pages ) )
            {   
                $menu_link = base_url() . $menu_link;
            }else{
                $menu_link = $path_root . $menu_link;                
            }


            $menu["link"] = $menu_link;
            $menu["is_selected"] = $is_selected;
            $menu["text"] = $menu[$LANG_CODE];
            $menu["submenu"] = $this->getSubmenus( $menu['id'], $type, $permission_type, $status, $LANG_CODE, $current_url, $path_root );

            $arr = array_column( $menu["submenu"],'id', 'is_selected');

            if( array_key_exists( 'active', $arr )){
                $menu["is_selected"] = 'active';
            } 
            array_push( $menu_arr, $menu );
        } 
        return $menu_arr;
    }

    private function getSubmenus( $menu_id, $type, $permission_type, $status , $lang_code ='en', $current_url, $path_root )
    {
        $menu_arr = [];
        $this->db->select( '*' );
        if($status != 'all')
            $this->db->where( 'status', $status );
        $this->db->where( $permission_type, "1" );
        $this->db->where( 'type', $type );
        $this->db->where( 'parent_id', $menu_id );
        $this->db->order_by( 'order' );
        $query = $this->db->get( 'menu' );

        foreach ($query->result_array() as $key => $menu) {
            $is_selected = null;
            $menu_link = $menu['link'];
            $split_pages = explode("/", $menu_link);
            $controller = $split_pages[0];  

            $current_url = str_replace('_', '-', $current_url );
            if ( $menu_link == $current_url ) {
                $is_selected = 'active';
            }              

            if( in_array( $controller, $this->software->COMMON_PAGES ) )
            {   
                $menu_link = base_url() . $menu_link;
            }else{
                $menu_link = $path_root . $menu_link;                
            }

            $menu["link"] = $menu_link;
            $menu["is_selected"] = $is_selected;
            $menu["text"] = $menu[$lang_code];
            if($menu['id'] == 52 || $menu['id'] == 54 || $menu['id'] == 76)
            {
                if($this->session->userdata['site_logged_in']['user_id'] >= 100)
                    array_push( $menu_arr, $menu );
            }
            else
                array_push( $menu_arr, $menu );
        }  
        return $menu_arr;
    }

    public function getCompanyInformation() {
        $details = array();
        $this->db->select("*");
        $this->db->from("site_info");
        $res = $this->db->get();
        foreach ($res->result_array() as $row) { 
            $details = $row;

            $logo_path = $this->config->item('assets_folder').'/images/logo/'. $row['logo'] ;
            if (!file_exists( $logo_path ) ) {
                $details['logo'] = 'default_logo.png';
            }

            $favicon_path = $this->config->item('assets_folder').'/images/logo/'. $row['logo'] ;
            if (!file_exists('assets/images/logo/' . $row['favicon'])) {
                $details['favicon'] = 'favicon.ico';
            }
            $details['email'] = $row['email'];
            $details['phone'] = $row['phone'];
            $details['address'] = $row['address'];
            $details['name'] = $row['name'];

        }
        return $details;
    }

    public function updateCompanyInformation($data) {
        $this->db->set('company_name', $data['company_name']);
        $this->db->set('company_address', $data['company_address']);
        $this->db->set('email', $data['email']);
        $this->db->set('phone', $data['phone']);
        $this->db->set('country_id', $data['country_id']);
        $this->db->set('default_lang', $data['default_lang']);
        $this->db->set('login_lang', $data['login_lang']);
        $this->db->set('currency_id', $data['currency_id']);
        $this->db->set('maintenance_mode', $data['maintenance_mode']);
        if($data['maintenance_mode']){
            $this->db->set('maintenance_mode_text', $data['maintenance_mode_text']);
        }
        if($data['logo']){
            $this->db->set('logo', $data['logo']);
        }
        if($data['favicon']){
            $this->db->set('favicon', $data['favicon']);
        }
        $result =  $this->db->update('company_information');
        return $result;

    }



    public function getFullName($user_id) {
        $name = '';
        $this->db->select('first_name, second_name');
        $this->db->from('user_info');
        $this->db->where('user_id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();

        foreach ($query->result() as $row) {
            $name = $row->first_name. ' '.  $row->second_name;
        }
        return $name;
    }


    public function getAdminId() {
        $user_id = NULL;
        $this->db->select('user_id');
        $this->db->where('user_type', 'admin');
        $this->db->order_by('user_id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get('login_info');
        foreach ($query->result() as $row) {
            $user_id = $row->user_id;
        }
        return $user_id;
    }

    public function getUnreadMails(){

        $log_user_id = log_user_id();
        $this->db->from('internal_mail_details');
        $this->db->where('to_user',$log_user_id);
        $this->db->where('read_status','no');
        $count = $this->db->count_all_results();
        return $count;
    }

    public function getfilteredPackage($keyword, $limit = 20)
    {
        $result = array();
        $this->db->select("name");
        if($keyword !="." && $keyword !="/")
            $this->db->like("name", $keyword, "after");
        $this->db->from("project_packages");
        $this->db->where("status", 'pending');
        $this->db->limit($limit);
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $result[]["result"] = $row["name"];
        }
        return $result;
    }  


    public function getfilteredProjectPackage($keyword, $project_id, $limit = 20)
    {
        $result = array();
        $this->db->select("name");
        if($keyword !="." && $keyword !="/")
            $this->db->like("name", $keyword, "after");
        $this->db->from("project_packages");
        $this->db->where("status", 'pending');
        $this->db->where("project_id", $project_id);
        $this->db->limit($limit);
        $query = $this->db->get(); 
        foreach ($query->result_array() as $row) {
            $result[]["result"] = $row["name"];
        }
        return $result;
    }  


    public function getfilteredPackageItems($keyword, $limit = 20)
    {
        $result = array();
        $this->db->distinct("name");
        if($keyword !="." && $keyword !="/")
            $this->db->like("name", $keyword, "after");
        $this->db->from("package_items");
        $this->db->where("status", 'active');
        $this->db->limit($limit);
        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            $result[]["result"] = $row["name"];
        }
        return $result;
    }  

    public function getPackageIdAjax($term='', $user_id='') {

        $output = [];
        $this->db->select('id,name');
        $this->db->from('project_packages');
        if($term){
            $this->db->where("name LIKE '$term%'");
            $this->db->or_where("code LIKE '$term%'");
        }
        $this->db->limit(10);
        $this->db->order_by('name','ASC');
        if($user_id)
            $this->db->where("user_id", $user_id);
        $res = $this->db->get();

        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 
            'text'=>$row['name']];
        }

        return $output;
    }

    public function getProjectIdAuto($term='', $user_id='',$status='pending', $customer_id='') {

        $output =[];
        $this->db->select('id, project_name')
        ->from('project pr');

        if($customer_id){ 
            $this->db->where("customer_name", $customer_id);
        }
        if($user_id)
            $this->db->where("user_id", $user_id);
        if($user_id)
            $this->db->where("user_id", $user_id);
        if($term)
            $this->db->where("project_name LIKE '%$term%'"); 
        if($status!='all')
        {
            $this->db->where("status" , $status);  
        }
        $this->db->limit(10);
        $this->db->order_by('project_name','ASC');
        $res = $this->db->get();
// echo $this->db->last_query(); die();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 
            'text'=>$row['project_name']];
        }

        return $output;
    }
    public function getPackagerIdAuto($term='', $user_id='') {

        $output = [];
        $this->db->select('user_id,user_name');
        $this->db->from('login_info');
        $this->db->where('user_type', 'packager');
        if($user_id)
            $this->db->where("user_id", $user_id);
        if($term)
            $this->db->where("user_name LIKE '$term%'");
        $this->db->limit(10);
        $this->db->order_by('user_name','ASC');
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 
            'text'=>$row['user_name']];
        }

        return $output;
    } 
    public function getSupervisorIdAuto($term='', $user_id='') {

        $output = [];
        $this->db->select('user_id,user_name');
        $this->db->from('login_info');
        $this->db->where('user_type', 'supervisor');

        if($user_id)
            $this->db->where("user_id", $user_id);
        if($term)
            $this->db->where("user_name LIKE '$term%'");
        $this->db->limit(10);
        $this->db->order_by('user_name','ASC');
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 
            'text'=>$row['user_name']];
        }

        return $output;
    }

    public function getDeliveryCodeAuto($term='',$user_id='') {

        $output = [];
        $this->db->select('code,id');
        $this->db->from('delivery_notes');
        if($term)
        {
            $this->db->where("code LIKE '$term%'");
        }
        if($user_id){
            $this->db->where("user_id",$user_id);
        }
        $this->db->limit(10);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 

            'text'=>$row['code']];
        }

        return $output;
    }

    public function getDriverIdAuto($term='') {

        $output = [];
        $this->db->select('user_id,user_name');
        $this->db->from('login_info');
        $this->db->where('status', 1);
        $this->db->where('user_type', 'driver');
        if($term)
            $this->db->where("user_name LIKE '$term%'");
        $this->db->limit(10);
        $this->db->order_by('user_name','ASC');
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 

            'text'=>$row['user_name']];
        }

        return $output;
    }
    public function getSalesmanIdAuto($term='') {

        $output = [];
        $this->db->select('user_id,user_name');
        $this->db->from('login_info');
        $this->db->where('status', 1);
        $this->db->where('user_type', 'salesman');
        if($term)
            $this->db->where("user_name LIKE '$term%'");
        $this->db->limit(10);
        $this->db->order_by('user_name','ASC');
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 

            'text'=>$row['user_name']];
        }

        return $output;
    }

    public function getCustomerSalesmanIdAuto($search_arr=[]) {

        $output = [];
        $this->db->select('li.user_id,li.user_name');
        $this->db->from('customer_info ci');
        $this->db->join('login_info li', 'li.user_id = ci.salesman_id');
        $this->db->where('li.status', 1);
        $this->db->where('li.user_type', 'salesman');
        if(element('customer_id', $search_arr))
            $this->db->where("ci.customer_id", $search_arr['customer_id']);

        if(element('customer_name', $search_arr))
            $this->db->like("ci.customer_username", $search_arr['customer_name'], 'after');
            // $this->db->like("ci.customer_username LIKE '$term%'");

        $this->db->limit(10);
        $this->db->order_by('li.user_name','ASC');
        $res = $this->db->get();
        foreach($res->result_array() as $row) {


            if(element('type', $search_arr) == 'ajax' && element('customer_id', $search_arr)){
                return $row;
            }

            $output[] = [
                'id'=>$row['user_id'], 
                'text'=>$row['user_name']
            ];
        }

        return $output;
    }


    public function getCustomerIdAuto($term='',$created_by='',$salesman_id='') {

        $output = [];
        $this->db->select('customer_id,customer_username');
        $this->db->from('customer_info');
        $this->db->where('status', 'active');
        if($term)
            $this->db->where("customer_username LIKE '$term%'"); 
        if($created_by){
            $where = "(created_by = {$created_by})";
            $this->db->where($where); 
        }
        if($salesman_id){
            $where = "salesman_id = {$salesman_id}";
            $this->db->where($where); 
        }
        $this->db->order_by('customer_username','ASC');
        $this->db->limit(10);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['customer_id'], 

            'text'=>$row['customer_username']];
        }

        return $output;
        // print_r($output);die();
    }


    public function getItemId($code) {
        $id = NULL;
        $this->db->select('id');
        $this->db->from('items');
        $this->db->where('code', $code);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }
    public function getItemName($id) {

        $code = '';
        $this->db->select('code');
        $this->db->from('items');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        // echo $this->db->last_query($query);die();
        foreach ($query->result() as $row) {
            $code = $row->code;
        }
        return $code;
    }
    
    public function getMeetingCode($id) {

        $code = '';
        $this->db->select('code');
        $this->db->from('meeting_mint');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        // echo $this->db->last_query($query);die();
        foreach ($query->result() as $row) {
            $code = $row->code;
        }
        return $code;
    }
    
    public function getJobId($order_id) {
        $id = NULL;
        $this->db->select('id');
        $this->db->from('job_orders');
        $this->db->where('order_id', $order_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }

    public function getJobOrderId($job_id) {
        $order_id = NULL;
        $this->db->select('order_id');
        $this->db->from('job_orders');
        $this->db->where('id', $job_id);
        $this->db->limit(1);

        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $order_id = $row->order_id;
        }
        return $order_id;
    }

    public function getJobOrderIdAuto($term='', $department_id='', $customer_id='') {

        $output = [];
        $this->db->distinct(true)->select('jo.order_id, jo.name')
        ->from('job_orders jo');

        if($department_id){
            $this->db->join('department_jobs dj', 'dj.job_order_id = jo.id')
            ->where("dj.department_id", $department_id);
        }

        if($customer_id){
            $this->db->where("jo.customer_id", $customer_id);
        }

        if($term)
            $this->db->where("jo.order_id LIKE '%$term%' or jo.name LIKE '%$term%'");
            // $this->db->where("jo.order_id LIKE '$term%'");

        $this->db->limit(20)
        ->order_by('jo.order_id','ASC');
        $res = $this->db->get();


        foreach($res->result_array() as $row) {

            $output[] = [
                'id'=>$row['order_id'], 
                'text'=>$row['order_id'] . ' - ' . $row['name']
            ];
        }

        return $output;
    }

    public function getDepartmentName($department_id) {
        $name = NULL;
        $this->db->select('name');
        $this->db->from('department');
        $this->db->where('id', $department_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $name = $row->name;
        }
        return $name;
    }

    public function getDepartmentID($department_name) {
        $id = NULL;
        $this->db->select('id');
        $this->db->from('department');
        $this->db->where('name', $department_name);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }

    function getCustomerInfoField($field_name, $customer_id){
        $field_value = NULL;
        $this->db->select($field_name);
        $this->db->where('customer_id', $customer_id);
        $this->db->from('customer_info');
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $field_value = $row->$field_name;
        }
        return $field_value;
    }

    public function getCustomerProjects($search_arr=[])
    {

        $details=[];
        $this->db->select('project_name as text, id')
        ->from('project');

        if (element('customer_id', $search_arr)) {
            $this->db->where('customer_name', $search_arr['customer_id']);
        }
        if ( element( 'qry', $search_arr ) ) {
            $key = $search_arr['qry'];
            $this->db->where("customer_username LIKE '$key%'");
        }

// $this->db->where('customer_name',$customer_id);
        $query = $this->db->get();
        foreach($query->result_array() as $row){
            $details[] = $row; 
        }
        return $details;
    }

    public function getDepartmentSupervisor($department_id) {   
        $user_id = 0;   
        $this->db->select('user_id');   
        $this->db->from('login_info');  
        $this->db->where('department_id', $department_id);  
        $this->db->where('user_type', 'dept_supervisor');   
        $this->db->limit(1);    
        $query = $this->db->get();  
        foreach ($query->result() as $row) {    
            $user_id = $row->user_id;   
        }   
        return $user_id;    
    }

    public function getDepartmentInfo($department_id='') {
        $details = [];
        $this->db->select('*')
        ->from('department');

        if($department_id){   
            $this->db->where('id', $department_id)
            ->limit(1);
        }

        $query = $this->db->get();
        foreach ($query->result_array() as $row) {
            if($department_id){   
                return $row;
            }
            $details[$row['id']] = $row;
        }
        return $details;
    }
    public function getMainCategory($term='') {

        $output = [];
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where('status','active');

        if($term)
        {
            $this->db->where("category_name LIKE '$term%'");
        }
        $this->db->where('main_category',0);
        $this->db->limit(10);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 

            'text'=>$row['category_name']];
        }

        return $output;
    }
    public function getCategoryName($category_id) {

        $output = null;
        $this->db->select('category_name');
        $this->db->from('category');  
        $this->db->where('id',$category_id);
        $this->db->limit(1);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output = $row['category_name'];
        }

        return $output;
    }
    public function getAllCategory($term='') {

        $output = [];
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where('status','active');

        if($term)
        {
            $this->db->where("category_name LIKE '$term%'");
        }
        // $this->db->where('main_category',0);
        $this->db->limit(10);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 

            'text'=>$row['category_name']];
        }

        return $output;
    }

    public function getSupplierAjax($term='', $user_id='') {

        $output = [];
        $this->db->select('id, user_name')
        ->from('supplier_info')
        ->limit(10)
        ->order_by('user_name','ASC');

        if($term)
            $this->db->where("user_name LIKE '$term%'");
        if($user_id)
            $this->db->where("user_id", $user_id);
        $res = $this->db->get();

        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 
            'text'=>$row['user_name']];
        }

        return $output;
    }

    public function getSupplierName($user_id) {
        $user_name = NULL;
        $this->db->select('user_name');
        $this->db->from('supplier_info');
        $this->db->where('id', $user_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $user_name = $row->user_name;
        }
        return $user_name;
    }

    public function getMaterialReceiptId($bill_number) {
        $id = NULL;
        $this->db->select('id');
        $this->db->from('material_receipt');
        $this->db->where('bill_number', $bill_number);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $id = $row->id;
        }
        return $id;
    }

    public function getReceiptBillNumber($receipt_id) {
        $bill_number = NULL;
        $this->db->select('bill_number');
        $this->db->from('material_receipt');
        $this->db->where('id', $receipt_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $bill_number = $row->bill_number;
        }
        return $bill_number;
    }

    public function getMaterialReceiptAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, bill_number as text')
        ->from('material_receipt')
        ->limit(10)
        ->order_by('bill_number','ASC');

        if($term)
            $this->db->where("bill_number LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();

        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }

    public function getItemAutoComplete($term='', $id='',$search_arr=[]) {

        $output = [];
        $this->db->select('id, code as text, name')
        ->from('items')
        ->limit(10)
        ->where('status', '1')
        ->order_by('code','ASC');

        if($id)
            $this->db->where("id", $id); 

        if(element('type', $search_arr)){
            $this->db->where("type", $search_arr['type']); 

        }

        if(element('items_arr', $search_arr)){
            $this->db->where_in("type", $search_arr['items_arr']); 

        }
        $like = '(';
        if($term){
            $like .= "code LIKE '%$term%'";
        }

        if(element('with_name', $search_arr)){
            if($term){
                $like .= " OR ";
            }
            $like .= "name LIKE '%$term%'";
        }
        $like .= ')';

        if($like){
            $this->db->where("$like");
        }



        $res = $this->db->get();
        // echo $this->db->last_query();
        // die();
        foreach($res->result_array() as $row) {

            if(element('with_name', $search_arr)){
                $row['text'] .= ' - '. $row['name'];
            }
            $output[] = $row;
        }

        return $output;
    }
    public function getSampleAutoComplete($term='', $id='',$search_arr=[]) {

        $output = [];
        $this->db->select('id, code as text, name')
        ->from('sample')
        ->limit(10)
        ->where('status', '1')
        ->order_by('code','ASC');
        if($id)
            $this->db->where("id", $id); 
        $like = '(';
        if($term){
            $like .= "code LIKE '%$term%'";
        }
        if(element('with_name', $search_arr)){
            if($term){
                $like .= " OR ";
            }
            $like .= "name LIKE '%$term%'";
        }
        $like .= ')';

        if($like){
            $this->db->where("$like");
        }
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            if(element('with_name', $search_arr)){
                $row['text'] .= ' - '. $row['name'];
            }
            $output[] = $row;
        }
        return $output;
    }

    public function getSalesAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, code as text')
        ->from('sales_quotation')
        ->limit(10)
        ->order_by('code','ASC');

        if($term)
            $this->db->where("code LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();

        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }
    public function getJobOrderIdNameAuto($term='', $id='',$search_arr=[],$project_id='') {

        $output = [];
        $this->db->select('id, order_id as text, name')
        ->from('job_orders')
        ->limit(10)
        ->order_by('order_id','ASC');

        $like = '';
        if($term){
            $like .= "order_id LIKE '$term%'";
        }
        if ($project_id) {
            $this->db->where("project_id", $project_id); 
            
        }
        if(element('with_name', $search_arr)){
            if($term){
                $like .= " OR ";
            }
            $like .= "name LIKE '$term%'";
        }

        if($like){
            $this->db->where("$like");
        }

        if($id)
            $this->db->where("id", $id); 

        $res = $this->db->get();
        // echo $this->db->last_query();
        // die();
        foreach($res->result_array() as $row) {

            if(element('with_name', $search_arr)){
                $row['text'] .= ' - '. $row['name'];
            }
            $output[] = $row;
        }

        return $output;
    }
    public function getMaterialItemAutoComplete($term='', $id='',$search_arr=[],$job_orderid='') {

        $output = [];
        $this->db->select('i.id, i.code as text, i.name')
        ->distinct(true)->select('mri.item_id')
        ->from('items as i')
        ->join('material_receipt_items as mri','mri.item_id=i.id', 'left')
        ->limit(10)
        ->order_by('code','ASC');

        if(element('i.type', $search_arr)){
            $this->db->where("i.type", $search_arr['type']); 
        }

        if($job_orderid){
            $this->db->where('mri.job_order_id',$job_orderid);
        }

        $like = '(';
        if($term){
            $like .= "code LIKE '%$term%'";
        }

        if(element('with_name', $search_arr)){
            if($term){
                $like .= " OR ";
            }

            $like .= "name LIKE '%$term%'";
        }
        $like .= ')';

        if($like){
            $this->db->where("$like");
        }

        if($id)
            $this->db->where("id", $id); 

        $res = $this->db->get();
        // echo $this->db->last_query();
        // die();
        foreach($res->result_array() as $row) {

            if(element('with_name', $search_arr)){
                $row['text'] .= ' - '. $row['name'];
            }
            $output[] = $row;
        }

        return $output;
    }
    public function getItemNameById($id) {
        // print_r($id);die();
        $code = '';
        $this->db->select('code,name');
        $this->db->from('items');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        //  echo $this->db->last_query($query);
        // die();
        foreach ($query->result_array() as $row) {
            $code = $row['code'].' - '.$row['name'];
        }
        return $code;
    }
    function getVatValue($id){

        $value = NULL;
        $this->db->select('value');
        $this->db->where('id', $id);
        $this->db->from('vat');
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $value = $row->value;
        }
        return $value;
    }

    public function getTermsConditions($search_arr=[]) {

        $output = [];
        $this->db->select('id,name');
        $this->db->from('terms_conditions');

        if(element('tc_type', $search_arr))
            $this->db->where("tc_type", $search_arr['tc_type']);
        $this->db->limit(10);
        $this->db->order_by('date','DESC');
        $res = $this->db->get();
        // echo $this->db->last_query();
        // die();
        foreach($res->result_array() as $row) {  
            $output[] = [   
                'id'=>$row['id'], 
                'text'=> $row['name']
            ];
            
        }

        return $output;
    }
    public function getMeetingAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, code as text')
        ->from('meeting_mint')
        ->limit(10)
        ->order_by('code','ASC');

        if($term)
            $this->db->where("code LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();

        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }
    public function getMeetingMintAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, code as text,created_by');
        $this->db->where('created_by',log_user_id());
        $this->db->from('meeting_mint');

        $this->db->limit(10);
        $this->db->order_by('code','ASC');

        if($term)
            $this->db->where("code LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();
  // echo $this->db->last_query();
  //       die();
        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }
    public function getDesigneTypeAuto($term) {

        $output = [];
        $this->db->select('user_id,user_name,user_type');
        $this->db->from('login_info');
        $this->db->where('status', 1);
        $this->db->where('user_type', 'designer');
        $this->db->where("user_name LIKE '%$term%'");
        $this->db->limit(10);

        $res = $this->db->get();

        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 
            'text'=>$row['user_name']];
        }
        return $output;
        // print_r($output);die();
    }
    public function getSalesmanTypeAuto($term) {

        $output = [];
        $this->db->select('user_id,user_name,user_type');
        $this->db->from('login_info');
        $this->db->where('status', 1);
        $this->db->where('user_type', 'salesman');
        $this->db->where("user_name LIKE '%$term%'");
        $this->db->limit(10);

        $res = $this->db->get();

        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 
            'text'=>$row['user_name']];
        }
        return $output;
    }
    public function getCustomerName($customer_id) {

        $output = null;
        $this->db->select('customer_username');
        $this->db->from('customer_info');  
        $this->db->where('customer_id',$customer_id);
        $this->db->limit(1);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output = $row['customer_username'];
        }

        return $output;
    }
    public function getSalesName($user_id) {

        $output = null;
        $this->db->select('user_name');
        $this->db->from('login_info');  
        $this->db->where('user_id',$user_id);
        $this->db->limit(1);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output = $row['user_name'];
        }

        return $output;
    }
    public function getMeetingSampleCode($id) {

        $code = '';
        $this->db->select('code');
        $this->db->from('meeting_mint');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $query = $this->db->get();
        // echo $this->db->last_query($query);die();
        foreach ($query->result() as $row) {
            $code = $row->code;
        }
        return $code;
    }
    public function getMeetingsAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, code as text,created_by');
        $this->db->where('customer_id',log_user_id());
        $this->db->from('meeting_mint');

        $this->db->limit(10);
        $this->db->order_by('code','ASC');

        if($term)
            $this->db->where("code LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();
  // echo $this->db->last_query();
  //       die();
        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }

    public function getTypeMasterIdAuto($term='') {

        $output = [];
        $this->db->select('id,name');
        $this->db->from('type_master');
        $this->db->where('status', 1);
        if($term)
            $this->db->where("name LIKE '$term%'");
        $this->db->limit(10);
        $this->db->order_by('name','ASC');
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 

            'text'=>$row['name']];
        }

        return $output;
    }

    public function getConsumableReceiptBillNumber($receipt_id) {
        $bill_number = NULL;
        $this->db->select('bill_number');
        $this->db->from('consumable_receipt');
        $this->db->where('id', $receipt_id);
        $this->db->limit(1);
        $query = $this->db->get();
        foreach ($query->result() as $row) {
            $bill_number = $row->bill_number;
        }
        return $bill_number;
    }



    public function getConsumableReceiptAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, bill_number as text')
        ->from('consumable_receipt')
        ->limit(10)
        ->order_by('bill_number','ASC');

        if($term)
            $this->db->where("bill_number LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }

    public function getConsumableIssueAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, voucher_number as text')
        ->from('consumable_issue')
        ->limit(10)
        ->order_by('voucher_number','ASC');

        if($term)
            $this->db->where("voucher_number LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }


    public function getConsumableDamageAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, voucher_number as text')
        ->from('consumable_damage')
        ->limit(10)
        ->order_by('voucher_number','ASC');

        if($term)
            $this->db->where("voucher_number LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }

    public function getConsumableReturnAutoComplete($term='', $id='') {

        $output = [];
        $this->db->select('id, voucher_number as text')
        ->from('consumable_return')
        ->limit(10)
        ->order_by('voucher_number','ASC');

        if($term)
            $this->db->where("voucher_number LIKE '$term%'");
        if($id)
            $this->db->where("id", $id);
        $res = $this->db->get();
        foreach($res->result_array() as $row) {
            $output[] = $row;
        }

        return $output;
    }
    public function getEmployeeAjax($term='', $user_id='') {

        $output = [];
        $this->db->select('user_id, user_name')
        ->from('login_info')
        ->where("user_type = 'workers' OR user_type = 'supervisor'")
        // $where .= " OR user_type = 'supervisor')";
        // $this->db->where($where);

        ->limit(10)
        ->order_by('user_name','ASC');

        if($term)
            $this->db->where("user_name LIKE '$term%'");
        if($user_id)
            $this->db->where("user_id", $user_id);
        $res = $this->db->get();
        // echo $this->db->last_query();die();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 
            'text'=>$row['user_name']];
        }

        return $output;
    }
    public function getReceiptEmployeeAjax($term='', $user_id='') {

        $output = [];
        $this->db->select('user_id, user_name')
        ->from('login_info')
        ->where("user_type = 'workers' OR user_type = 'store_keeper'")
        // $where .= " OR user_type = 'supervisor')";
        // $this->db->where($where);

        ->limit(10)
        ->order_by('user_name','ASC');

        if($term)
            $this->db->where("user_name LIKE '$term%'");
        if($user_id)
            $this->db->where("user_id", $user_id);
        $res = $this->db->get();
        // echo $this->db->last_query();die();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 
            'text'=>$row['user_name']];
        }

        return $output;
    }


     public function getReturnEmployeeAjax($term='', $user_id='') {

        $output = [];
        $this->db->select('user_id, user_name')
        ->from('login_info')
        ->where("user_type = 'workers' OR user_type = 'supervisor'")
        // $where .= " OR user_type = 'supervisor')";
        // $this->db->where($where);

        ->limit(10)
        ->order_by('user_name','ASC');

        if($term)
            $this->db->where("user_name LIKE '$term%'");
        if($user_id)
            $this->db->where("user_id", $user_id);
        $res = $this->db->get();
        // echo $this->db->last_query();die();
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['user_id'], 
            'text'=>$row['user_name']];
        }

        return $output;
    }
    
    public function getProjectNames($term='') {

        $output =[];
        $this->db->select('id, name')
        ->from('project_packages');
        $this->db->where("name LIKE '%$term%'"); 
        $this->db->where("status !=" , 'deleted');  
        $this->db->limit(10);
        $this->db->order_by('name','ASC');
        $res = $this->db->get();
  
        foreach($res->result_array() as $row) {
            $output[] = ['id'=>$row['id'], 
            'text'=>$row['name']];
        }

        return $output;
    }

}

