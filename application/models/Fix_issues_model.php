<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Fix_issues_model extends Base_model {

    function __construct() {
        parent::__construct();

    }


    public function runUpdateDeliveryNotes()
    {
        $details = array();
        $query =$this->db->query("SELECT * FROM `delivery_notes` WHERE `date_created` < '2021-10-23 00:00:00' AND `status` LIKE 'send_to_delivery'");

        $date = date('Y-m-d H:i:s');
        $status = 'delivered';
        foreach($query->result_array() as $row)
        {
            $res = $this->db->where('delivery_id', $row['id'])            
            ->set('status', $status)
            ->set('updated_date', $date)
            ->update('delivery_packages');
            if(!$res){
                return False;
            }


            $packages_res = $this->db->select('package_id')->where('delivery_id', $row['id']) ->get('delivery_packages'); 
            foreach($packages_res->result_array() as $packages)
            {
                $update = $this->db->where('id', $packages['package_id'])
                ->set('status', $status)
                ->set('updated_date', $date)
                ->update('project_packages');

                if(!$update){
                    return false;
                } 
            }



            $res = $this->db->where('id', $row['id'])
            ->set('status', $status); 
            $res = $this->db->update('delivery_notes');
            if(!$res){
                return False;
            }

        }
        return true;
    }


    public function getCustomerDetailsFromProject()
    {
        $details = array();
        $this->db->select('id,customer_name,email,mobile,location,map');
        $this->db->from('project');
        $query = $this->db->get();
        foreach($query->result_array() as $row)
        {
            $details[] = $row;
        }
        return $details;
    }

    public function insertCustomerInfo($details)
    {
        foreach($details as $key => $value)
        {
            $this->config->load('bcrypt');
            $this->load->library('bcrypt');
            $hashed_customer_password = $this->bcrypt->hash_password($value['mobile']);
            $this->db->set('customer_username',str_replace(' ', '', $value['mobile']));
            $this->db->set('user_type','customer');
            $this->db->set('password',$hashed_customer_password);
            $this->db->set('name',$value['customer_name']);
            $this->db->set('mobile',$value['mobile']);
            $this->db->set('location',$value['location']);
            $this->db->set('map',$value['map']);
            $this->db->set('email',$value['email']);
            $this->db->set('date',date('Y-m-d H:i:s'));
            $insert = $this->db->insert('customer_info');
            if($insert)
            {
                $this->db->set('customer_name',$this->db->insert_id());
                $this->db->where('id',$value['id']);
                $insert = $this->db->update('project');
            }
        }

        if($insert){
            $this->db->query("ALTER TABLE `project` DROP `mobile`;"); 
        }
        return $insert;
    }

    public function runNewQueries()
    { 
        $this->db->query("
            CREATE TABLE `customer_info` (
                `customer_id` int NOT NULL,
                `customer_username` text NOT NULL,
                `user_type` varchar(500) NOT NULL DEFAULT 'customer',
                `password` text NOT NULL,
                `name` varchar(100) NOT NULL,
                `mobile` varchar(100) NOT NULL,
                `address` text NOT NULL,
                `location` varchar(250) NOT NULL,
                `map` text NOT NULL,
                `email` varchar(100) NOT NULL,
                `date` datetime NOT NULL,
                `status` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'active',
                `default_lang` int NOT NULL DEFAULT '1'
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

        $this->db->query('ALTER TABLE `customer_info` ADD PRIMARY KEY (`customer_id`);');

        $this->db->query('ALTER TABLE `customer_info` MODIFY `customer_id` int NOT NULL AUTO_INCREMENT;');



        $this->db->query("CREATE TABLE `department_jobs` (
            `id` int(11) NOT NULL,
            `job_order_id` int(11) NOT NULL,
            `department_id` int(11) NOT NULL,
            `short_description` text NOT NULL,
            `order_description` text NOT NULL,
            `estimated_working_hrs` double NOT NULL,
            `actual_working_hrs` double NOT NULL,
            `time_difference` double NOT NULL,
            `department_cost` double NOT NULL,
            `work_status` varchar(400) NOT NULL DEFAULT 'pending' 
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

        $this->db->query("ALTER TABLE `department_jobs` ADD PRIMARY KEY (`id`);");
        $this->db->query("ALTER TABLE `department_jobs` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT");

        // $this->db->query("ALTER TABLE `menu` ADD `perm_customer` INT(11) NOT NULL DEFAULT '0' AFTER `perm_pre_user`");
        // $this->db->query("ALTER TABLE `menu` ADD `perm_dept_supervisor` INT(11) NOT NULL DEFAULT '0' AFTER `perm_customer`; ");
        
        $this->db->query("CREATE TABLE `job_orders` ( 
            `id` int(11) NOT NULL, `order_id` int(11) NOT NULL, 
            `name` varchar(500) NOT NULL, 
            `date` datetime NOT NULL, 
            `requested_date` date NOT NULL, 
            `customer_id` int(11) NOT NULL, 
            `project_id` int(11) NOT NULL, 
            `estimated_working_hrs` double NOT NULL, 
            `actual_workin_hrs` double NOT NULL,  
            `time_difference` double NOT NULL ,
            `department_cost` double NOT NULL ,
            `admin_status` varchar(250) NOT NULL DEFAULT 'pending',
            `customer_status` varchar(250) NOT NULL DEFAULT 'pending',
            `work_status` varchar(250) NOT NULL DEFAULT 'pending'
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

        // $this->db->query("ALTER TABLE `job_orders` CHANGE `admin_status` `admin_status` VARCHAR(250)  NOT NULL DEFAULT 'pending'; "); 
        $this->db->query('ALTER TABLE `job_orders` ADD PRIMARY KEY (`id`)');
        $this->db->query('ALTER TABLE `job_orders`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');

        $this->db->query("UPDATE `menu` SET `perm_customer` = '1' WHERE `menu`.`id` = 105");
        $this->db->query("UPDATE `menu` SET `perm_customer` = '1' WHERE `menu`.`id` = 99;");

        $this->db->query("UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 118");
        $this->db->query("UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 119");
        $this->db->query("UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 126;");

        $this->db->query("ALTER TABLE `customer_info` ADD `created_by` INT(11) NOT NULL AFTER `default_lang`; ");

        $this->db->query("UPDATE `menu` SET `en` = 'Dashboard', `parent_id` = '99', `link` = 'project/index', `order` = '1' WHERE `menu`.`id` = 120; ");
        $this->db->query("UPDATE `menu` SET `perm_admin` = '1' WHERE `menu`.`id` = 120; ");
        $this->db->query("UPDATE `menu` SET `order` = '2' WHERE `menu`.`id` = 100; ");

        $this->db->query("UPDATE `menu` SET `link` = 'jobs/orders' WHERE `menu`.`id` = 122;");

        $this->db->query("UPDATE `menu` SET `link` = 'jobs/order-approval' WHERE `menu`.`id` = 123;  ");


        $this->db->query("UPDATE `menu` SET `perm_customer` = '1' WHERE `menu`.`id` = 16;");
        $this->db->query("UPDATE `menu` SET `perm_customer` = '1' WHERE `menu`.`id` = 17;");
        $this->db->query("DELETE FROM `menu` WHERE `menu`.`id` = 124");

        $this->db->query("UPDATE `menu` SET `perm_customer` = '1' WHERE `menu`.`id` = 17;");

        $this->db->query("UPDATE `menu` SET `perm_dept_supervisor` = '1' WHERE `menu`.`id` = 128; ");

        $this->db->query("UPDATE `menu` SET `perm_dept_supervisor` = '1' WHERE `menu`.`id` = 1; ");
        $this->db->query("UPDATE `menu` SET `perm_dept_supervisor` = '1' WHERE `menu`.`id` = 2;  ");

        $this->db->query("UPDATE `menu` SET `perm_dept_supervisor` = '1' WHERE `menu`.`id` = 14; ");
        $this->db->query("UPDATE `menu` SET `perm_dept_supervisor` = '1' WHERE `menu`.`id` = 17; ");
        $this->db->query("UPDATE `menu` SET `perm_dept_supervisor` = '1' WHERE `menu`.`id` = 16;");
        $this->db->query("UPDATE `menu` SET `perm_dept_supervisor` = '1' WHERE `menu`.`id` = 129; ");
        $this->db->query("UPDATE `menu` SET `perm_dept_supervisor` = '1' WHERE `menu`.`id` = 130;  ");


        

        $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (131, 'Day progress', '128', 'jobs/day-progress', 'dashboard', '1', '0', '0', '0', '0', '0', '1', '0', '0', '3', NULL, 'site'); ");
        

        $this->db->query("CREATE TABLE `day_progress`(
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `job_order_id` INT(11) NOT NULL,
            `department_id` INT(11) NOT NULL,
            `today_status` TEXT NOT NULL,
            `worked_in_min` DOUBLE NOT NULL,
            `date_added` DATETIME NOT NULL,
            `date_last_updated` DATETIME NOT NULL,
            PRIMARY KEY(`id`)
        ) ENGINE = InnoDB DEFAULT CHARSET=latin1;"); 


        $this->db->query("ALTER TABLE `password_reset_table` ADD `customer_id` INT NOT NULL AFTER `user_id`;"); 
        $this->db->query("ALTER TABLE `customer_info` ADD `customer_photo` TEXT NOT NULL AFTER `email`;"); 
        $this->db->query("ALTER TABLE `day_progress` ADD `employees_worked` VARCHAR(200) NOT NULL AFTER `worked_in_min`;"); 

        $this->db->query("UPDATE `menu` SET `perm_admin` = '1' WHERE `menu`.`id` = 131;"); 
        

        $this->db->query("ALTER TABLE `project` ADD `estimated_cost` INT(11) NOT NULL AFTER `status`; "); 
        $this->db->query("ALTER TABLE `project` ADD `estimated_value` INT(11) NOT NULL AFTER `estimated_cost`;"); 
        $this->db->query("ALTER TABLE `project` ADD `estimated_duration` INT(11) NOT NULL AFTER `estimated_value`;"); 
        $this->db->query("ALTER TABLE `project` ADD `start_date` DATE NOT NULL AFTER `estimated_duration`;"); 
        $this->db->query("ALTER TABLE `project` ADD `end_date` DATE NOT NULL AFTER `start_date`; "); 
        
        $this->db->query("CREATE TABLE `department` (
            `id` int NOT NULL,
            `name` varchar(200) NOT NULL,
            `dep_id` varchar(150) NOT NULL,
            `cost_per_hour` int NOT NULL,
            `date_added` datetime NOT NULL,
            `date_updated` datetime NOT NULL,
            `status` int NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;"); 

        $this->db->query("ALTER TABLE `department` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `department`MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1; ");

        $this->db->query("ALTER TABLE `login_info` ADD `department_id` INT(11) NOT NULL DEFAULT '0' AFTER `joining_date`; ");
        $this->db->query("ALTER TABLE `login_info` ADD `reg_log_id` INT(11) NOT NULL DEFAULT '0' AFTER `department_id`; ");


        return TRUE;
    }

    public function runNewQueriesThird()
    { 

        $this->db->query("START TRANSACTION");

        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (138, 'Supplier Master', '#', '', 'supervised_user_circle', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site'), (NULL, 'Supplier\'s List', '136', 'member/supplier-list', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site'),(NULL, 'Create New', '136', 'member/add-supplier', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', NULL, 'site');");
        $this->db->query("CREATE TABLE `vat` (`id` int NOT NULL,`name` varchar(300) NOT NULL,  `value` int NOT NULL,`status` varchar(300) NOT NULL DEFAULT '1',`date` datetime NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;");

        $this->db->query("ALTER TABLE `vat` ADD PRIMARY KEY (`id`);");
        $this->db->query("ALTER TABLE `vat` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;");

        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (136, 'Item Master', '#', '', 'supervised_user_circle', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site');");
        
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (NULL, 'VAT', '136', 'member/create_vat', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '6', NULL, 'site');");


        $this->db->query("CREATE TABLE `supplier_info` (
          `id` int NOT NULL,
          `user_name` text NOT NULL,
          `name` varchar(100) NOT NULL,
          `mobile` varchar(100) NOT NULL,
          `address` text NOT NULL,
          `contact_person` text NOT NULL,
          `email` varchar(250) NOT NULL,
          `date` datetime NOT NULL,
          `status` varchar(250) NOT NULL DEFAULT '1'  ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
        $this->db->query("ALTER TABLE `supplier_info` ADD PRIMARY KEY (`id`);");
        $this->db->query("ALTER TABLE `supplier_info` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1");

        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (138, 'Supplier Master', '#', '', 'supervised_user_circle', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site')");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (138, 'Supplier Master', '#', '', 'supervised_user_circle', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site')");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (NULL, 'Create New', '138', 'member/add-supplier', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', NULL, 'site')");




        // $this->db->query("CREATE TABLE `category` (`id` int(11) NOT NULL,`code` text NOT NULL,`category_name` text NOT NULL,`main_category` int(11) NOT NULL DEFAULT 0,`sort_order` INT(11) NOT NULL DEFAULT '0', `date` datetime NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
        // $this->db->query("ALTER TABLE `category` ADD PRIMARY KEY (`id`)");
        // $this->db->query("ALTER TABLE `category` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (NULL, 'List', '138', 'member/supplier-list', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site')");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (141, 'Category Master', '#', '', 'category ', '1', '1', '0', '0', '0', '0', '0', '0', '0', '3', NULL, 'site');");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (NULL, 'Create New', '141', 'delivery/create-category', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', NULL, 'site'),(NULL, 'List All', '141', 'delivery/list-category', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site'); ");





        $this->db->query("CREATE TABLE `material_receipt` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `bill_number` VARCHAR(99) NOT NULL , `name` VARCHAR(250) NOT NULL , `supplier_id` INT(11) NOT NULL ,`created_by` INT(11) NOT NULL , `total_qty` INT(11) NOT NULL , `total_cost` DOUBLE NOT NULL , `status` VARCHAR(20) NOT NULL DEFAULT 'active', `date_added` DATETIME NOT NULL , `last_updated` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; ");

        $this->db->query("CREATE TABLE `material_receipt_items` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `material_receipt_id` INT(11) NOT NULL , `job_order_id` INT(11) NOT NULL , `item_id` INT(11) NOT NULL , `unit` INT(11) NOT NULL , `cost` DOUBLE NOT NULL , `qty` INT(11) NOT NULL , `status` VARCHAR(11) NOT NULL DEFAULT 'active' , `date_added` DATETIME NOT NULL , `last_update` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB; ");

        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (144, 'Material Master', '#', '', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '5', NULL, 'site'); ");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (NULL, 'Receipt Add', '144', 'material/receipt-add', '', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', '', 'site'); ");
        



        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (146, 'Add ', '136', 'item/create-items', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', NULL, 'site')");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (147, 'List All', '136', 'item/list-items', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site')");

        $this->db->query("CREATE TABLE `item_images` (
            `id` int(11) NOT NULL,
            `item_id` int(11) NOT NULL,
            `image` text NOT NULL,
            `date` datetime NOT NULL,
            `status` int(11) NOT NULL DEFAULT 1
        ) ENGINE=InnoDB ; ");
        $this->db->query("ALTER TABLE `item_images` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `item_images`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");


        $this->db->query("CREATE TABLE `items` (
          `id` int(11) NOT NULL,
          `code` VARCHAR(250) NOT NULL,
          `name` text NOT NULL,
          `category` bigint(20) NOT NULL,
          `cost` DOUBLE NOT NULL COMMENT 'item cost',
          `vat` double NOT NULL,
          `price` DOUBLE NOT NULL COMMENT 'selling price',
          `unit` VARCHAR(250) NOT NULL,
          `total_quantity` INT(250) NOT NULL,
          `type` varchar(450) NOT NULL,
          `date` datetime NOT NULL,
          `status` int(11) NOT NULL DEFAULT 1
      ) ENGINE=InnoDB");
        $this->db->query("ALTER TABLE `items` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `items` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT");



        $this->db->query("CREATE TABLE `category` (
          `id` int(11) NOT NULL,
          `code` text NOT NULL,
          `category_name` text NOT NULL,
          `main_category` int(11) NOT NULL DEFAULT 0,
          `date` datetime NOT NULL,
          `sort_order` INT(11) NOT NULL DEFAULT '0',
          `status` varchar(400) NOT NULL DEFAULT 'active'
      ) ENGINE=InnoDB");
        $this->db->query("ALTER TABLE `category` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `category` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT");

        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (148, 'List', '144', 'material/list', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site') ");


        if (!file_exists('./assets/images/items')) {
            mkdir('./assets/images/items', 0755, true);
        }

        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (149, 'Sales quotation', '#', '#', 'receipt', '1', '1', '0', '1', '0', '1', '0', '0', '1', '4', NULL, 'site');");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (150, 'List All', '149', 'sales/list-sales', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site');");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (151, 'Sales Quotation', '149', 'sales/sales-quotation', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', NULL, 'site');");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (152, 'Revision', '149', 'sales/revision', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '3', NULL, 'site');");

        $this->db->query("CREATE TABLE `sales_items` (
          `id` int(11) NOT NULL,
          `sales_id` int(11) NOT NULL,
          `item_id` int(11) NOT NULL,
          `quantity` bigint(20) NOT NULL,
          `total_price` double NOT NULL,
          `date_added` datetime NOT NULL,
          `status` varchar(250) NOT NULL,
          `note` TEXT NOT NULL
      ) ENGINE=InnoDB");
        $this->db->query("ALTER TABLE `sales_items` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `sales_items`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT");

        $this->db->query("CREATE TABLE `sales_quotation` (
          `id` int(11) NOT NULL,
          `subject` VARCHAR(250) NOT NULL, 
          `code` bigint(20) NOT NULL,
          `date` DATE NOT NULL,
          `customer_id` int(11) NOT NULL,
          `salesperson` text NOT NULL,
          `vat` int(11) NOT NULL,
          `status` varchar(450) NOT NULL,
          `total_items` DOUBLE NOT NULL ,
          `total_qty` DOUBLE NOT NULL,
          `total_amount` DOUBLE NOT NULL ,
          `discount_by_amount` double NOT NULL,
          `discount_by_percentage` double NOT NULL,
          `terms_conditions` text NOT NULL,
          `tc_type` VARCHAR(400) NOT NULL, 
          `payment_terms_id` INT NOT NULL,
          `created_date` DATETIME NOT NULL DEFAULT '2000-12-10 00:00:00'
      ) ENGINE=InnoDB");
        $this->db->query("ALTER TABLE `sales_quotation` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `sales_quotation` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT");


        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (153, 'Material Issue', '#', '', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '5', NULL, 'site')");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (154, 'Issue Add', '153', 'material/issue-add', '', '1', '1', '0', '0', '0', '0', '0', '0', '0', '1', '', 'site')");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (155, 'List', '153', 'material/issue-list', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '2', NULL, 'site')");

        $this->db->query("CREATE TABLE `material_issue` (
          `id` int(11) NOT NULL,
          `voucher_number` varchar(250) NOT NULL,
          `voucher_date` datetime NOT NULL,
          `created_by` int(11) NOT NULL,
          `total_issued_qty` bigint(20) NOT NULL,
          `total_cost` double NOT NULL,
          `status` varchar(20) NOT NULL DEFAULT 'active',
          `date_added` datetime NOT NULL,
          `last_updated` datetime NOT NULL
      ) ENGINE=InnoDB");
        $this->db->query("ALTER TABLE `material_issue` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `material_issue` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT");

        $this->db->query("CREATE TABLE `material_issue_receipts` (
          `id` int(11) NOT NULL,
          `issue_id` int(11) NOT NULL,
          `receipt_id` int(11) NOT NULL,
          `issued_qty` bigint(20) NOT NULL,
          `status` varchar(11) NOT NULL DEFAULT 'active',
          `date_added` datetime NOT NULL,
          `last_update` datetime NOT NULL
      ) ENGINE=InnoDB");
        $this->db->query("ALTER TABLE `material_issue_receipts` ADD PRIMARY KEY (`id`)");
        $this->db->query("ALTER TABLE `material_issue_receipts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT");
        $this->db->query("ALTER TABLE `customer_info` ADD `organization_type` VARCHAR(500) NOT NULL DEFAULT 'Individual' AFTER `created_by`");        
        $this->db->query("ALTER TABLE `customer_info` ADD `salesman_id` VARCHAR(120) NOT NULL AFTER `organization_type`;");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (156, 'Inventory', '32', 'report/inventory', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '1', '5', NULL, 'site')");
        // $this->db->query("INSERT INTO `menu` (`id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `order`, `target`, `type`) VALUES (157, 'Inventry Dashboard', '136', 'item/index', 'engineering', '1', '1', '1', '0', '0', '0', '0', '0', '0', '1', NULL, 'site')");
        $this->db->query("ALTER TABLE `items` ADD `sub_category` BIGINT NOT NULL AFTER `category`;");
        // $this->db->query("ALTER TABLE `delivery_notes` ADD `last_updated` DATETIME NOT NULL DEFAULT '1973-01-01 00:00:00' AFTER `date_created`; ");
        // $this->db->query("UPDATE `menu` SET `icon` = 'local_shipping' WHERE `menu`.`id` = 107; ");
        $this->db->query("ALTER TABLE `material_issue_receipts` ADD `job_order_id` INT NOT NULL AFTER `receipt_id`, ADD `item_id` INT NOT NULL AFTER `job_order_id`;");
        $this->db->query("ALTER TABLE `sales_quotation` ADD `type` VARCHAR(230) NOT NULL DEFAULT 'quotation' AFTER `created_date`; ");
        $this->db->query("ALTER TABLE `sales_quotation`  ADD `note` VARCHAR(230) NOT NULL AFTER `created_date`;");
        $this->db->query("ALTER TABLE `sales_items` ADD `vat_inclusive` DOUBLE NOT NULL AFTER `total_price`;");
        $this->db->query("ALTER TABLE `sales_quotation` ADD `total_vat_inclusive` DOUBLE NOT NULL AFTER `total_amount`;");

        // $this->db->query("UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 144; UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 145; UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 148; UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 153; UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 154; UPDATE `menu` SET `perm_store_keeper` = '1' WHERE `menu`.`id` = 155;");
        // $this->db->query("ALTER TABLE `menu` ADD `perm_salesman` INT NOT NULL AFTER `perm_user`;");
        // $this->db->query("UPDATE `menu` SET `perm_salesman` = '1' WHERE `menu`.`id` = 1;UPDATE `menu` SET `perm_salesman` = '1' WHERE `menu`.`id` = 2;UPDATE `menu` SET `perm_salesman` = '1' WHERE `menu`.`id` = 149; UPDATE `menu` SET `perm_salesman` = '1' WHERE `menu`.`id` = 150; UPDATE `menu` SET `perm_salesman` = '1' WHERE `menu`.`id` = 151;UPDATE `menu` SET `perm_salesman` = '1' WHERE `menu`.`id` = 118; UPDATE `menu` SET `perm_salesman` = '1' WHERE `menu`.`id` = 119; UPDATE `menu` SET `perm_salesman` = '1' WHERE `menu`.`id` = 126;");
        // $this->db->query("INSERT INTO `menu`( `id`, `en`, `parent_id`, `link`, `icon`, `status`, `perm_admin`, `perm_customer`, `perm_store_keeper`, `perm_packager`, `perm_supervisor`, `perm_dept_supervisor`, `perm_pre_user`, `perm_user`, `perm_salesman`, `order`, `target`, `type` ) VALUES( NULL, 'T&C ', '149', 'sales/create-tc', 'dashboard', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '3', NULL, 'site' )");



        $this->db->query("CREATE TABLE `terms_conditions` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `terms_conditions` text NOT NULL,
            `name` VARCHAR(200) NOT NULL,
            `tc_type` varchar(400) NOT NULL,
            `created_by` int(11) NOT NULL,
            `date` datetime NOT NULL, 
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB");

        return $this->db->query("COMMIT");
        

    }

    public function runNewQueriesFourth()
    { 



        // if (!file_exists('./assets/images/qr_image')) {
        //     mkdir('./assets/images/qr_image', 0755, true);
        //     copy('./assets/images/qr_code/index.html', './assets/images/qr_image/index.html');
        //     copy('./assets/images/package_pic/no-image.png', './assets/images/qr_image/no-image.png');
        // }


        if (!file_exists('./assets/images/qr_code/sample')) {
            mkdir('./assets/images/qr_code/sample', 0755, true);
            copy('./assets/images/qr_code/index.html', './assets/images/qr_code/sample/index.html');
            copy('./assets/images/package_pic/no-image.png', './assets/images/qr_code/sample/no-image.png');
        }
        if (!file_exists('./assets/images/qr_code/meeting')) {
            mkdir('./assets/images/qr_code/meeting', 0755, true);
            copy('./assets/images/qr_code/index.html', './assets/images/qr_code/meeting/index.html');
            copy('./assets/images/package_pic/no-image.png', './assets/images/qr_code/meeting/no-image.png');
        }
        if (!file_exists('./assets/images/meeting')) {
            mkdir('./assets/images/meeting', 0755, true);
            copy('./assets/images/qr_code/index.html', './assets/images/meeting/index.html');
            copy('./assets/images/package_pic/no-image.png', './assets/images/meeting/no-image.png');
        }
    
        if (!file_exists('./assets/images/sample')) {
            mkdir('./assets/images/sample', 0755, true);
            copy('./assets/images/qr_code/index.html', './assets/images/sample/index.html');
            copy('./assets/images/package_pic/no-image.png', './assets/images/sample/no-image.png');
        }


        $this->db->query("CREATE TABLE `sample` (
          `id` int NOT NULL  AUTO_INCREMENT,
          `code` varchar(250) NOT NULL,
          `name` text NOT NULL,
          `category` bigint NOT NULL,
          `cost` double NOT NULL COMMENT 'item cost',
          `price` double NOT NULL COMMENT 'selling price',
          `unit` varchar(250) NOT NULL,
          `total_quantity` int NOT NULL,
          `date` datetime NOT NULL,
          `status` int NOT NULL DEFAULT '1',
          `brand` varchar(20) NOT NULL,
          `origin` varchar(20) NOT NULL,
          `item_id` int NOT NULL,
          `created_by` int NOT NULL,
          `supplier` int NOT NULL,
          `size` varchar(20) NOT NULL,
          `paint_code` varchar(20) NOT NULL,
          `type` varchar(30) NOT NULL,
          `grade` varchar(30) NOT NULL,
          PRIMARY KEY (`id`)
      ) ENGINE=InnoDB");

        $this->db->query("CREATE TABLE `sample_images` (
          `id` int NOT NULL AUTO_INCREMENT,
          `sample_id` int NOT NULL,
          `image` text NOT NULL,
          `date` datetime NOT NULL,
          `status` int NOT NULL DEFAULT '1',
          PRIMARY KEY (`id`)
      ) ENGINE=InnoDB");


        $this->db->query("CREATE TABLE `meeting_mint_sample` (
          `id` int NOT NULL AUTO_INCREMENT,
          `meeting_id` int NOT NULL,
          `sample_id` int NOT NULL,
          `quantity` bigint NOT NULL,
          `total_price` double NOT NULL,
          `date_added` datetime NOT NULL,
          `status` varchar(250) NOT NULL,
          `note` text NOT NULL,
          `price` double NOT NULL,
          `activity_id` int DEFAULT '100',
          `name` varchar(20) NOT NULL,
          PRIMARY KEY (`id`)
      ) ENGINE=InnoDB");


        $this->db->query("CREATE TABLE `meeting_mint` (
          `id` int NOT NULL AUTO_INCREMENT,
          `code` bigint NOT NULL,
          `customer_id` int NOT NULL,
          `total_items` double NOT NULL,
          `total_qty` double NOT NULL,
          `total_amount` double NOT NULL,
          `total_vat_inclusive` double NOT NULL,
          `discount_by_amount` double NOT NULL,
          `discount_by_percentage` double NOT NULL,
          `created_date` datetime NOT NULL DEFAULT '2000-12-10 00:00:00',
          `created_by` varchar(110) NOT NULL,
          `user_id` int NOT NULL,
          `sales_id` int NOT NULL  COMMENT 'salesman_id',
          `status` varchar(10) NOT NULL,
          `ad_note` text NOT NULL,
          PRIMARY KEY (`id`)
      ) ENGINE=InnoDB;");
        

        $this->db->query("CREATE TABLE `meeting_images` (
            `id` int NOT NULL AUTO_INCREMENT, 
            `meeting_id` int NOT NULL,
            `image` text NOT NULL,
            `date` datetime NOT NULL,
            `status` int NOT NULL DEFAULT '1',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB;");

        // $this->db->query("ALTER TABLE `project_packages` ADD `type_id` INT NOT NULL AFTER `updated_date`, ADD `item_id` INT NOT NULL AFTER `type_id`;");

        // $this->db->query("CREATE TABLE `type_master` (
        //     `id` int NOT NULL AUTO_INCREMENT,
        //     `name` varchar(300) NOT NULL,
        //     `status` varchar(30) NOT NULL DEFAULT '1',
        //     `date` datetime NOT NULL,
        //     PRIMARY KEY (`id`)
        // ) ENGINE=InnoDB");
return true;
    }
}