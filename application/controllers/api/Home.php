<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once ("Api_Controller.php");
class Home extends Api_Controller {

	function __construct() {
		parent::__construct(); 

		$this->load->model(['Zone_model', 'Website_model','Site_model','Base_model','Settings_model','Customer_model','Mail_model','Product_model']); 
	}

	public function states()
	{

		try{

			$country=$this->input->post('country');
			$country_name=$this->Zone_model->IdToCountryName($country);
			$states = $this->Zone_model->getAllZones('', $country);
			$response['success'] = true;
			$response['country_name'] = $country_name;
			$response['states'] = $states;
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	public function all_countries()
	{

		try{

			$countries=$this->Website_model->getAllCountries();
			$response['success'] = true;
			$response['countries'] = $countries;
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}



	public function searchProduct()
	{
		try
		{

			if ($this->input->post()) {

				$term = $this->input->post("term");

				$response['search_products'] = $this->Product_model->getsearchProductDetailsAPI($term);
				$response['success'] = True; 
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}


		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	public function signup()
	{ 
// print_r('werwrwer');
// die();

		try{


			if($this->input->post() && $this->validate_register($this->input->post()) )
			{
				$post_arr = $this->input->post();

				if(ENVIRONMENT == 'production'){

					$post_arr['password'] = $this->Site_model->getRandomString(6,'login_info','password');
				}else{

					$post_arr['password'] = 123456;
				}	

				$post_arr['full_name'] = $post_arr['first_name'].''.$post_arr['last_name'];


				$register = $this->Website_model->userRegistration($post_arr);
				$this->Site_model->begin();

				if($register)
				{
					$mail_arr = array(

						'email' =>$post_arr['email'], 
						'full_name' =>$post_arr['full_name'],
						'password' =>$post_arr['password'],

					);


					$send_mail = $this->Mail_model->sendEmails('registration',$mail_arr);
					$post_arr['user_id'] = $this->Site_model->getUserId($post_arr['email']);
					$ecn_user_id = $this->Site_model->encrypt_decrypt( 'encrypt', $post_arr['user_id'] ); 
					$post_arr['user_type'] = 'user';
					$post_arr['file_name'] = NULL;
					if(!empty($_FILES))
					{

						$config['upload_path'] = './assets/images/files/address';
						$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx';
						$config['max_size'] = '2000';
						$config['remove_spaces'] = true;
						$config['overwrite'] = false;
						$config['encrypt_name'] = TRUE;

						$this->load->library('upload', $config);
						$msg = '';
						if ($this->upload->do_upload()) {
							$image_arr = $this->upload->data();  
							$post_arr['file_name']=$image_arr['file_name'];
						} 

					}

					$insert = $this->Website_model->insertAddress($post_arr);
					$update = $this->Site_model->updateUserLoginField('default_address_id',$insert,$post_arr['user_id']);
					$this->Site_model->commit();

					$msg = lang('registration_success') . ", User ID : {$post_arr['email']} , Password : {$post_arr['password']} ";

					$this->session->set_flashdata('sign_up_flash', $post_arr);
					$response['success'] = true;
					$response['msg'] = 'Signup Completed Successfully';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
				else
				{
					$response['success'] = FALSE;
					$response['msg'] = 'Invalid fields';
					$response['error_msgs'] = $this->form_validation->error_array();
					$this->Site_model->rollback();
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();


				}
			}


		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 


	}


	public function home()
	{  
		try{


			$user_id=false;
			$post_arr=$this->input->post();

			if ($post_arr['status']==1)
			{

				$token = $this->check_header(); 

				$user_id=$token->user_id;
				$wishlist_contents=$this->Website_model->getAllWishlistContentApi($user_id);
				$response['wishlist_count'] = count($wishlist_contents);


			}
			else
			{

				$response['wishlist_count'] =0;
			}


			$response['cart_total_items'] = $this->cart->total_items();

			

			$catID=0;


			if(element('cat_id',$post_arr))
			{

				$catID = $post_arr['cat_id'];
			}

			if(element('order_by',$post_arr))
			{

				$order_by = $post_arr['order_by'];
			}


			// if($this->input->post('delete'))
			// {
			// 	$row_id = $this->input->post('delete');
			// 	$this->cart->remove($row_id);
			// 	if(	$this->cart->remove($row_id))

			// 	{

			// 		$response['success'] = TRUE;
			// 		$response['msg'] = "Item removed from your cart Successfully";


			// 		$this->output
			// 		->set_status_header(200)
			// 		->set_content_type('application/json', 'utf-8')
			// 		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			// 		->_display();
			// 		exit();
			// 	}
			// 	else{

			// 		$response['success'] = FALSE;
			// 		$response['msg'] = "error on removing";

			// 		$this->output
			// 		->set_status_header(200)
			// 		->set_content_type('application/json', 'utf-8')
			// 		->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			// 		->_display();
			// 		exit();
			// 	}

			// }



			$orderby = 1;
			$prime_ids = ['7','8','9','1','3','10','12','13','14']; 
			$category_id=1;

			if( $catID){
				$category_id = $catID;
			}







			$prime_cats = $this->Website_model->getPrimeCategoriesInfo('',$category_id);
			$prime_cats_menu = $this->Website_model->getPrimeCategoriesMenu('',$category_id);



			$bigsale = $this->Settings_model->getBigsales();
			$response['bigsale'] = $bigsale;
			$response['prime_cats'] = $prime_cats;
			$response['prime_cats_menu'] = $prime_cats_menu;
			$response['cat_id'] = $category_id;
			$response['success'] = TRUE;


			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}



	public function add_to_cart()
	{  
		try{

			$total_quantity=0;
			$enc_product_id=0;
			$product_id=0;
			if (array_key_exists('enc_product_id',$this->input->post())) {
				if (element('enc_product_id',$this->input->post())) {
					$enc_product_id=$this->input->post('enc_product_id');
				}
			}
			if($enc_product_id)
			{
				$product_id = $this->Site_model->encrypt_decrypt('decrypt', $enc_product_id);

			}
			if ($product_id) {
				$product_details = $this->Product_model->getProductDetailsApi($product_id);
				if (empty($product_details)) {
					$response['status'] = FALSE;
					$response['msg'] = lang('please_check_product_not_exist');

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}

			}
			else{
				$response['success'] = FALSE;
				$response['msg'] = "Invalid Selection";

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}



			if( element('quantity', $this->input->post()) )
			{
				$post_arr=$this->input->post();
				$quantity=$post_arr['quantity'];
				
				$enc_dimension=$post_arr['dimension_id'];
				$dimension_id=$this->Site_model->encrypt_decrypt('decrypt',$post_arr['dimension_id']);
				$dimension_info = $this->Settings_model->getDemensions($dimension_id);
				$dimension_name = $dimension_info['name'];
			}
			else
			{
				$quantity=1;
				$key =current($product_details['dimensions']);
				$dimension_id = $this->Site_model->encrypt_decrypt('decrypt',$key['enc_id']);
				$dimension_name =$key['name'];

			}	


			$dimension_string = $dimension_name.'_remaining';

			if($product_details[$dimension_string] < $quantity ){
				$response['success'] = FALSE;
				$response['msg'] = lang('insufficient');
				$response['cart_total_items'] = $this->cart->total_items();

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}


			if($product_details['status'] == 'Inactive' ){
				$response['success'] = FALSE;
				$response['msg'] = lang('please_check_product_not_exist');

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}
			$total=$this->cart->total(); 
			$cart = $this->cart->contents();
			foreach($cart as $v)
			{
				if($v['id'] ==$product_details['product_id'])
				{
					$qty = $v['qty'] +  $quantity;
					$dimension =$v['dimension_name'].'_remaining';
					if($dimension == $dimension_string)
					{
						if($product_details[$dimension_string] < $qty)
						{
							$response['success'] = FALSE;
							$response['msg'] = lang('insufficient');
							$response['cart_total_items'] = $this->cart->total_items();

							$this->output
							->set_status_header(200)
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
							->_display();
							exit();
						}
					}
				}

			}
			if( $cart_contents = $this->cart->contents() ){

				$item_ids = array_column( $cart_contents, 'rowid', 'id') ;

			}else{
				$item_ids = [];
			}
			$data = array(

				'id'      => $product_details['product_id'],
				'qty'     => $quantity,
				'price'   => $product_details['amount'],
				'name'    => $product_details['product_name'],
				'count'    => $product_details['total_count'],
				'image'		=>$product_details['image'],
				'dimension_name' => $dimension_name,
				'options' => array(
					'enc_product_id'	=> $product_details['enc_id'],
					'description' => $product_details['description'],
					'dimension' => $dimension_id,
					'enc_dimention_id' => $enc_dimension
// 'color' => $product_details['color_id']
				)

			);
			$this->cart->product_name_safe = false;
			if($this->cart->insert($data))
			{
				$response['success'] = TRUE;
				$response['cart_total_items'] = $this->cart->total_items();

				$cart_contents = $this->cart->contents();


// $this->set('cart_contents',$cart_contents);
// $response['cart_contents'] = $cart_contents;

				$response['msg'] = lang('item_added');

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}else{ 
				$response['success'] = FALSE;
				$response['msg'] = lang('item_delete');

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}
		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 


	}


	public function clear_cart(){

		try{

			$post_arr=$this->input->post();



			$row_id = $post_arr['row_id'];


			if ($row_id == '0') {



				$clear =$this->cart->destroy();


				$response['success']= TRUE;
				$response['msg'] = lang('cart_cleared');

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 

			}

			else
			{




				$this->cart->remove($row_id);

				if(	$this->cart->remove($row_id))

				{

					$response['success']= TRUE;
					$response['msg'] = lang('item_removed');

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}
				else{

					$response['success']= FALSE;
					$response['msg'] = lang('failed');

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}
			}







		}


		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	public function category_products()
	{
		


		try{

			$user_id=false;

			if ($this->input->post('status')==1)
			{




				$token = $this->check_header(); 
				$user_id=$token->user_id;
				$wishlist_contents=$this->Website_model->getAllWishlistContentApi($user_id);
				$response['wishlist_count'] = count($wishlist_contents);


			}
			else
			{

				$response['wishlist_count'] =0;
			}





			// if($user_id)
			// {
			// 	$items = $this->Website_model->getAllWishlistContentApi($user_id);
			// 	$response['wishlist_count'] = count($items);


			// }

			if($this->input->post())
			{
				// $post_arr=$this->input->post();
				// print_r($post_arr);
				// die();
				$order_by =$this->input->post('order_by');
				$category_id =$this->input->post('cat_id');

				$select_arr='pd.product_name,pd.product_id,pd.image,pd.dimension_id,amount,cross_amount,rating';

				if($order_by && $category_id)
				{

					$prime_products = $this ->Product_model->getPrimeProductsApi($order_by,$category_id,$select_arr);

					$response['cart_total_items'] = $this->cart->total_items();


					$response['success'] = TRUE;
					$response['prime_products'] = $prime_products;
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 

				}
				else
				{

					$response['msg'] = 'No products found';
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}


			}
		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 

	}


	public function cart()
	{

		try{

			$user_id=false;
			if ($this->input->post('status')==1)
			{
				$token = $this->check_header(); 
				$user_id=$token->user_id;
				$wishlist_contents=$this->Website_model->getAllWishlistContentApi($user_id);
				$response['wishlist_count'] = count($wishlist_contents);
			}
			else
			{
				$response['wishlist_count'] =0;
			}
			



			if(!$this->cart->contents())
			{
				$response['success']= FALSE;
				$response['cart_total_items'] = $this->cart->total_items(); 
				$response['msg'] = 'Your Cart is Empty....';

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 
			}	



			$cart_contents = $this->cart->contents();
			$order_total = $this->cart->total();
			$quantity=0;
			foreach ( $cart_contents as $key => $item)
			{

				$quantity=$quantity+$item['qty'];

			} 
			if($quantity>2 && value_by_key('get_status')=='active'){
				$price=array();
			// print_r($cart_contents);die();
				foreach ($cart_contents as $key => $item) {
					$price[]=$item['price'];
				}
				$lowest_price=min($price);
				$order_total=$order_total-$lowest_price;
			}


			$this->load->model('Zone_model');
			$states = $this->Zone_model->getAllZones('', '178');

			

			$response['success']= TRUE;
			$response['cart_total_items'] = $this->cart->total_items();
			$response['order_total']= $order_total;
			$response['cart_contents']= $cart_contents;

			

			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit(); 


		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}


	public function cart_slide()
	{
		try{

			if(!$this->cart->contents())
			{
				$response['success']= FALSE;
				$response['msg'] = 'Your Cart is Empty....';
				$response['cart_total'] = $this->cart->total();

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 
			}	

			$cart_contents = $this->cart->contents();
			$filtered_cart_contents = array(); 
			foreach ($cart_contents as $key => $item) {

				$filtered_item = array(
					'name' => $item['name'],
					'qty' => $item['qty'],
					'price' => $item['price'],
					'subtotal' => $item['subtotal']
				);

				$filtered_cart_contents[$key] = $filtered_item;
			}

			$response['cart_contents'] = $filtered_cart_contents;
			$this->load->model('Zone_model');
			$states = $this->Zone_model->getAllZones('', '178');
			$order_total = $this->cart->total();
			$response['success']= TRUE;
			$response['cart_total_items'] = $this->cart->total_items();
			$response['order_total']= $order_total;

			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit(); 


		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}





	public function update_cart()
	{
		try{


			$cart_update=NULL;
			$new_qunatity=NULL;
			$post_arr = $this->input->post();


			
			if($this->input->post())
			{
				$cart = $this->cart->contents();

				if (!$cart) {
					$response['success']=FALSE;
					$response['msg']='Invalid Request';
					$response['cart_total_items'] = $this->cart->total_items();
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}
				$row_id=$post_arr['row_id'];

				$product_info = $this->Product_model->getRequestProductDetails($cart[$row_id]['id']);

				


				if($cart[$row_id]['qty'] > $post_arr['quantity'])
				{
					
					$new_qunatity =$post_arr['total_item']- $post_arr['quantity'];
					
				}

				if($new_qunatity == null)
				{
					$this->cart->remove($row_id);
					$response['success']=True;
					$response['msg']='Item Removed';
					$response['cart_total_items'] = $this->cart->total_items();
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}

				


				$dimension_string = $post_arr['dimension'].'_remaining';



				if($product_info[$dimension_string] < $new_qunatity ){


					$response['success']=FALSE;
					$response['msg']='Insufficient Quantity';
					$response['cart_total_items'] = $this->cart->total_items();
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 

				}
				$data = array(
					'rowid'   => $row_id,
					'qty'     => $new_qunatity,
				);

				$cart_update = $this->cart->update($data);

				
				if(!$cart_update)
				{  
					$response['success']=FALSE;
					$response['msg']='error_on_updating';
					$response['cart_total_items'] = $this->cart->total_items();
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
					
				}
				else
				{  
					$response['success']=True;
					$response['msg']='cart updated';
					$response['cart_total_items'] = $this->cart->total_items();
					$response['cart_updated_product_qty'] = $new_qunatity;

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}

			}
		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	public function default_data()
	{
		try{





			$category_id = '';
			if ($this->input->get('cat_id')) {
				$category_id = $this->input->get('cat_id');
				$this->session->set_userdata('cat_id', $category_id);  
			}


			else{
				$category_id =2;
			}




			if( $this->input->get('bearndeer')){

				$category_id = $this->input->get('bearndeer');
			}
			if( $this->input->get('maternity')){

				$category_id = $this->input->get('maternity');
			}
// print_r($category_id);
// die();
//  print_r('weqwefd');
// die();

			$prime_cats = $this->Website_model->getPrimeCategoriesInfo('',$category_id);
			$prime_cats_menu = $this->Website_model->getPrimeCategoriesMenu('',$category_id);
			$response['success']= TRUE;
			$response['prime_cats']= $prime_cats;
			$response['prime_cats_menu']= $prime_cats_menu;

			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit(); 


		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}


	public function product_quick_view()
	{
		try{

			$enc_product_id=$this->input->post();
			$product_id=null;
// print_r($enc_product_id['enc_productid']);
// die();

			if ($enc_product_id) {

				$product_id = $this->Site_model->encrypt_decrypt('decrypt',$enc_product_id['enc_productid']);
				$this->load->model('Product_model');
				$product_details = $this->Product_model->getProductDetailsApi($product_id);
				$product_images = $product_details['product_images'];
				$product_reviews = $this->Product_model->getProductReviews($product_id);
				$review_count = count($product_reviews);
				$response['success']= TRUE;
				$response['product_details']= $product_details;
				$response['product_images']= $product_images;
				$response['product_reviews']= $product_reviews;
				$response['review_count']= $review_count;


				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 


			}

			if(!$product_id)
			{
				$response['msg'] = 'product not found';
			}

		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	public function offer_products()
	{
		try{

			$user_id=false;
			if ($this->input->post('status')==1)
			{
				$token = $this->check_header(); 
				$user_id=$token->user_id;
				$wishlist_contents=$this->Website_model->getAllWishlistContentApi($user_id);
				$response['wishlist_count'] = count($wishlist_contents);
			}
			else
			{
				$response['wishlist_count'] =0;
			}

			$response['cart_total_items'] = $this->cart->total_items();

			$response['offer_products'] =$this->Product_model->OfferProducts();
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit(); 
		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}




	public function product_view()
	{
		try{

			$user_id=false;
			if ($this->input->post('status')==1)
			{
				$token = $this->check_header(); 
				$user_id=$token->user_id;
				$wishlist_contents=$this->Website_model->getAllWishlistContentApi($user_id);
				$response['wishlist_count'] = count($wishlist_contents);
			}
			else
			{
				$response['wishlist_count'] =0;
			}
			$response['cart_total_items'] = $this->cart->total_items();

			$post_arr=$this->input->post();
			$enc_product_id=$post_arr['enc_product_id'];
			$product_id=false;

			if ($enc_product_id) {


				$product_id = $this->Site_model->encrypt_decrypt('decrypt',$enc_product_id);
				if(!$product_id)
				{
					$response['success']=FALSE;
					$response['msg']="Product not exist";
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}
				else
				{

					$product_details = $this->Product_model->getProductDetailsApi($product_id);
					$response['success']=True;
					$response['product_details']=$product_details;
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}



			}

		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 

	}

	public function add_review()
	{

		try{
			$post_arr = $this->input->post();
			$token = $this->check_header();  
			$user_id=$token->user_id;

			if($this->input->post() && $this->validate_review())
			{
				$post_arr = $this->input->post();
				$enc_product_id=$post_arr['enc_product_id'];
				$post_arr['product_id'] = $this->Site_model->encrypt_decrypt('decrypt',$enc_product_id);
				$post_arr['user_id']=$user_id;

				$site_info = $this->Settings_model->getCompanyInformation();
				$product_details = $this->Product_model->getProductDetails($post_arr['product_id']);


				if( empty($product_details) ){
					$response['msg']="Product not exist";
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}

				$review_added = $this->Website_model->insertProductReview($post_arr);

				if($review_added)
				{  
					$this->Product_model->updateRating($post_arr['product_id'], $post_arr['rating'] );
					$post_arr['product_name'] =  $product_details['product_name'];
					$post_arr['product_code'] =  $product_details['product_code'];
					$post_arr['product_url'] =  base_url()."product-details/$enc_product_id";
					$mail_arr = array(

						'email' =>$site_info['email'], 
						'product_name' =>$post_arr['product_name'],
						'product_code' =>$post_arr['product_code'],
						'product_url' =>$post_arr['product_url'],
						'user_email'=>$post_arr['email'],
						'name'=>$post_arr['name'],
						'review'=>$post_arr['review']


					);
					$send_mail = $this->Mail_model->sendEmails('product_review', $mail_arr);

					$response['msg']="Review added Successfully";
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}

			}
			else
			{
				$response['msg']="Error on Adding";
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}


		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}



	public function blogs()
	{
		try{

			$this->load->model('Blog_model');
			$response['blog']=$this->Blog_model->getBlogApi();
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();

		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 

	}



	public function blog_view()
	{
		try{

			$this->load->model('Blog_model');
			$post_arr=$this->input->post();
			$enc_id=$post_arr['enc_id'];

			if ($enc_id) {

				$id=$this->Site_model->encrypt_decrypt( 'decrypt' ,$enc_id);
				if ($id) 
				{

					$response['blog_details']=$this->Blog_model->getBlogViewApi($id);
					$response['success']=True;
				}
				else
				{

					$response['success']=FALSE;
					$response['msg']="No blogs found";
				}
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 

	}



	public function all_products()
	{
		try{

			$prime_products = $this ->Product_model->getAllProductsApi();
			$response['prime_products']=$prime_products;
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();

		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 

	}



	public function view_category_product()
	{
		try{

			$post_arr=$this->input->post();
			$cat_id=false;
			$main_id=false;
			$filter_params=array();



			if(element('q',$post_arr))
			{
				$post_arr = $this->input->post();
				if(element('cat',$post_arr))
				{
					if($post_arr['cat'] !='all')

					{

						$main_id = $post_arr['cat'];
					}
				}
				$filter_params['name'] = $post_arr['q'];
			}

			if ($post_arr['cat_id']) {

				$cat_id = $post_arr['cat_id'];

			}

			if ($post_arr['main_id']) {

				$main_id = $post_arr['main_id'];

			}

			$product_details6 = $this ->Product_model->getRandomProduct('',6);
			$response['product_details']=$product_details6;




			$post_arr = $this->input->post(); 

			$filter_details = [];
			$filter_details['checkbox_colors'] = element('checkbox_colors', $post_arr) ? $post_arr['checkbox_colors'] : [];
			$filter_details['checkbox_prices'] = element('checkbox_prices', $post_arr) ? $post_arr['checkbox_prices'] : [];
			$filter_details['checkbox_dimension'] = element('checkbox_dimension', $post_arr) ? $post_arr['checkbox_dimension'] : [];

			$filter_details['checkbox_material'] = element('checkbox_material', $post_arr) ? $post_arr['checkbox_material'] : [];

			$response['filter_details']=$filter_details; 

			$filter_details['main_id'] = $main_id;
			$filter_details['cat_id'] = $cat_id;
			$left_menu_type = 'main_category'; 


// print_r($filter_details);
// die();


			if($main_id)
			{ 
				if ($main_id && $cat_id) {  
					$left_menu_type = 'category';  
				} 
				else

				{ 
					$left_menu_type = 'main_category'; 
				} 
				$categories = $this->Website_model->getCategory($main_id, 'main_category'); 


				$response['left_menu_type']=$left_menu_type; 
				$response['category_name']=$this->Site_model->getCategoryName($cat_id); 
				$response['main_category_name']=$this->Site_model->getMainCategoryName($main_id); 
				$description = $this->Product_model->getCategoryDescriptionApi($main_id);
				$response['description']=$description; 
				$response['categories']=$categories; 





			}


			$adv_filter = 'adv_search';
			$selected_filter = 'Advanced Search';
			$adv_filter_arr['adv_search'] 	= 'Advanced Search';
			$adv_filter_arr['low-to-high'] = 'Price-Low To High';
			$adv_filter_arr['high-to-low'] = 'Price-High To Low';
			$adv_filter_arr['new_product'] = 'New Products';

			if( $this->input->post('adv_filter'))
			{
				$adv_filter = $this->input->post('adv_filter');
				$selected_filter = $adv_filter_arr[$adv_filter];
				unset($adv_filter_arr[$adv_filter]);
			}

			$response['adv_filter']=$adv_filter; 
			$response['adv_filter_arr']=$adv_filter_arr; 
			$response['selected_filter']=$selected_filter; 

// $filter_details['limit'] = $filter_params['limit'] = $this->perPage;

			$product_details = $this->Product_model->viewCategoryProductApi( $filter_details['main_id'], $filter_details['cat_id'], $filter_details['checkbox_dimension'], $filter_details['checkbox_material'], $filter_details['checkbox_prices'], $adv_filter, $filter_params );


			$response['product_details']=$product_details; 
			$dimensions_arr = array_column($product_details, 'dimension_id');
			$dimensions_strings =  implode(", ",$dimensions_arr);
			$dimensions_strings =  explode(",",$dimensions_strings);
			$dimensions= $this->Product_model->getDimensionDetails($dimensions_strings);
			$response['dimension_filter']=$dimensions;
			$common_dimensions = array_unique(array_column($dimensions,'name'));
			$response['common_dimensions']=$common_dimensions;

			$materials = $this->Website_model->getMaterialByCategory( $main_id, $cat_id );
			$response['materials_filter']=$materials;

			$prize_filter = $this->Product_model->viewPriceRange($main_id, $cat_id);
			$response['prize_filter']=$prize_filter;
			$response['total_count']=element('count',$prize_filter);

			if($product_details)
			{
				$product_last = end($product_details);
				$response['last_product_id']=$product_last['product_id'];
			}
// $response['perPage']=$this->perPage;
			$categories = $this->Website_model->getCategory($main_id);

// print_r($product_details);
// die();

			$response['filter_details']= $filter_details;
			$response['left_menu_type']= $left_menu_type;

			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();


		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 

	}


	public function my_orders()
	{
// $post_arr = $this->input->post();
		$token = $this->check_header();  
		$user_id=$token->user_id;
		$order_details=array();
		try{
			if(!$user_id)
			{
				$response['msg']="No permission";
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}
// if(element('show_all',$post_arr))
// {
// 	$order_details=$this->Website_model->getUserOrderDetails($user_id);
// }
// else
// {
			$order_details=$this->Website_model->getUserOrderDetails($user_id,4);
// }


			$response['order_details']=$order_details;
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();

		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 

	}


	public function invoice_details()
	{
		$post_arr = $this->input->post();
		$token = $this->check_header();  
		$user_id=$token->user_id;
		$order_details=array();
		try{
			if(!$user_id)
			{
				$response['msg']="No permission";
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}
			if ($post_arr['enc_order_id']) 
			{
				$enc_order_id=$post_arr['enc_order_id'];

				$order_id = $this->Site_model->encrypt_decrypt('decrypt', $enc_order_id);
				$select_arr = 'final_amount, from_wallet, order_id, status, order_date,tracking_id,shipping_method';
				$where_arr = [
					"customer_id" => log_user_id(), 
					'customer_type' => 'user',
					'order_id' => $order_id,
				];
				$order_details = $this->Website_model->getOrderDetailsInfo( $select_arr, $where_arr );
				$response['order_details']=$order_details;

				$select_arr = 'opd.order_id, opd.product_id, opd.quantity, opd.amount, opd.subtotal, pd.product_name, pd.product_code, pd.image, d.name as dimension_name,opd.gift';
				$where_arr = [
					'opd.order_id' => $order_id,
				];
				$order_products = $this->Website_model->getOrderProductsInfo( $select_arr, $where_arr );


				$response['order_products']=$order_products;


				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
			$response['msg']="Invalid request";
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();

		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 

	}





	function validate_review() 
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|required'); 
		$this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|is_unique[product_reviews.email]');
		$this->form_validation->set_rules('rating', 'Rating', 'trim|required|greater_than[0]|less_than_equal_to[5]');
// $this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('review', 'Review', 'trim|required');

		$val_res = $this->form_validation->run(); 
// print_r($this->form_validation->error_array());die();
		return $val_res;
	} 

	public function validate_register($post_arr='')
	{

		$this->form_validation->set_rules('first_name', lang('first_name'), 'trim|required');
		$this->form_validation->set_rules('last_name', lang('last_name'), 'trim|required');
		if(element('email',$post_arr))
		{
			$this->form_validation->set_rules('email', lang('email'),'trim|required|valid_email');
		}
		$this->form_validation->set_rules('address', lang('address'), 'trim|required');
		
// print_r($this->input->post('country'));die();
		if($this->input->post('country')== 99)
			$this->form_validation->set_rules('phone', lang('mobile'), 'trim|required|min_length[10]|max_length[10]');
		else
			$this->form_validation->set_rules('phone', lang('mobile'), 'trim|required|min_length[5]|max_length[10]');
		$this->form_validation->set_rules('country', lang('country'), 'trim|required');
		if($this->input->post('country')== 99)
			$this->form_validation->set_rules('state', lang('state'), 'trim|required');
		else
			$this->form_validation->set_rules('n_state', lang('state'), 'trim|required');
		$this->form_validation->set_rules('city', lang('city'), 'trim|required');
		$this->form_validation->set_rules('district', "District", 'trim|required');
		$this->form_validation->set_rules('pin_code', "Pin code", 'trim|required');
// $this->form_validation->set_rules('token', "", 'callback_check_captcha'); 
// $this->form_validation->set_message('check_captcha', lang('form_must_resubmit'));
// }  
		$result =  $this->form_validation->run(); 
		$errors=$this->form_validation->error_array();

		if ($errors) {
			$response['success']=FALSE;
			$response['errors']=$errors;


			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		}

		
		return $result;
	}

	public function checkout()
	{

		try
		{

			$token = $this->check_header();  
			$user_id=$token->user_id;
			$order_total = $this->cart->total();
			$order_status='normal';
			
			if(!$this->cart->contents()){
				$response['msg']="Your Cart is empty..!";
				$response['success']=false;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();
			}

			$contents = $this->cart->contents();

			$quantity=0;
			foreach ( $contents as $key => $item) {
				$product_details = $this->Product_model->getRequestProductDetails($item['id']);
				if($product_details['total_count'] < $item['qty'] ){


					$response['msg']="Sorry..!Insufficient Quantity of ". $item['name'];
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
				if($product_details['status']=='Inactive' ){

					$response['msg']="Sorry..!". $item['name']." The Product Not Exist.";
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();
				}
				$quantity=$quantity+$item['qty'];
			} 
			$order_total = $this->cart->total();

			$available_address = $this->Website_model->getAvailableAddress($user_id); 


			$country = $available_address[0]['countries'];
			$state = $available_address[0]['state'];


			$service_charge = 30;	
			if($country == 99)
			{


				$state_details = $this->Zone_model->getAllZones($state,$country);
				$shipping_charge = $state_details[0]['shipping_fee'];

			}
			else
			{
				$country_details = $this->Settings_model->getCountryDetails($country);
				$shipping_charge = $country_details['shipping_charge'];

			}
			$order_status='normal';
			if($quantity>2 && value_by_key('get_status')=='active'){
				$price=array();

				foreach ($contents as $key => $item) {
					$price[]=$item['price'];
				}
				$lowest_price=min($price);
				$order_total=$order_total-$lowest_price;
				$order_status='offer';
				foreach ($contents as $key => $item) {
					if($item['price']==$lowest_price){
						$name=$item['name'];
						$response['name']=$name;
						$response['lowest_price']=$lowest_price;

					}
				}
			}

			if($order_total >= 12000)
			{
				$shipping_charge = 0;
			}




			if($this->input->post())
			{
				$post_arr = $this->input->post();
				$post_arr['user_id'] = $user_id;

				
				if(element('address_id1',$post_arr)){
					$address_id=$post_arr['address_id1'];

					$set=$this->Site_model->updateUserLoginField('default_address_id',$post_arr['address_id1'],$user_id);
				}

				$address_id=$this->Site_model->getUserLoginFieldInfo('default_address_id',$user_id);

				

				$cart_contents= $this->cart->contents();
				$total=$this->cart->total();

				$order_id_db=$this->Website_model->insertCheckoutOrders($order_total,date('Y-m-d'),'checkout',$user_id,$cart_contents,$order_status);

				

				$checkout_order_id=$this->Site_model->encrypt_decrypt('encrypt', $order_id_db);


				$response['checkout_order_id']=$checkout_order_id;

				if($post_arr['payment_method']==1){


					$response['msg']="Razorpay";
					$response['success']=True;
				}
				
				else{
					$response['msg']="Please select a payment method";
					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit();

				}
				$response['shipping_charge']=$shipping_charge;
				$response['checkout_order_id']=$checkout_order_id;
				$response['sub_total']=$order_total;
				$response['state']=$state;
				
				$response['country']=$country;
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();


			}
		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		}
	}
	
	public function razor_payment()
	{

		try
		{ 
			$token = $this->check_header();  
			$user_id=$token->user_id;
			if($this->validate_update_payment()){

				$post_arr = $this->input->post();



				$curl = curl_init();

				$this->config->load('ssl');
				$razorpay_config = $this->config->item('razorpay');
				

				$razorpay['auth']  = $razorpay_config[$razorpay_config['method'].'_'.'auth']; 



				curl_setopt_array($curl, array(
					CURLOPT_URL => 'https://api.razorpay.com/v1/payments/'.$post_arr['razorpay_payment_id'],
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => '',
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => 'GET',
					CURLOPT_HTTPHEADER => array(

						'Content-Type: application/json',
						'Authorization: '.$razorpay['auth']
					),
				));
				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response, TRUE);

				// print_r($response);
				// die();

				if($error = element( 'error', $response)){

					$response['success'] = FALSE;
					$response['msg'] = $error['description']; 

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 

				}else{
					$date = date('Y-m-d H:i:s');
					$response['amount'] = $response['amount'] / 100;
					$post_arr['amount'] = $response['amount'];
					$post_arr['payment_details'] = $response;
					$post_arr['paid_by'] = 'user';

					$keep_payment_history_id =  $this->Website_model->addPaymentHistory( $user_id, $post_arr,  $date );

					if($response['status'] == 'captured'){


						$order_id=$post_arr['checkout_order_id'];
						$payment_id=$post_arr['payment_id'];

						$order_id_db=$this->Site_model->encrypt_decrypt('decrypt',$order_id);
						$user_id=$this->Website_model->getDetailsCheckoutOrders('customer_id',$order_id_db);
						$cart_data=$this->Website_model->getDetailsCheckoutOrders('cart_data',$order_id_db);
						$un_cart_data=unserialize($cart_data);

						$order_status=$this->Website_model->getDetailsCheckoutOrders('order_status',$order_id_db);

						$payment_order_id=$this->Website_model->getDetailsPaymentRequest('payment_order_id',$order_id_db);


						$order_total=$this->Website_model->getDetailsCheckoutOrders('total_amount',$order_id_db);
						$user_type=$this->Site_model->getUserType($user_id);

						$address_id = $this->Site_model->getUserLoginFieldInfo('default_address_id',$user_id);
						$address_details = $this->Customer_model->getAddressDetails($address_id);
						if($address_details['countries']== 99)
						{
							$state = $address_details['state'];
							$state_details = $this->Zone_model->getAllZones($state,$address_details['countries']);
							$shipping_charge = $state_details[0]['shipping_fee'];
						}
						else
						{
							$country_details = $this->Settings_model->getCountryDetails($address_details['countries']);
							$shipping_charge  = $country_details['shipping_charge'];
						}
						if($order_total >= 12000)
						{
							$shipping_charge = 0;
						}

						$secret_code=$this->Website_model->getDetailsCheckoutOrders('secret_code',$order_id_db);

						if($secret_code !='NA' && $secret_code != NULL)
						{
							$gift_status = 1;
						}
						else
							$gift_status = 0;

						$order_id = $this->Customer_model->placeOrder($user_id,$un_cart_data, $order_total, "", $user_type, $address_id,$order_id_db ,$gift_status,$shipping_charge,0,'RazorPay',$order_status);
						if($gift_status == 1)
						{
							$this->Website_model->updateSecretCodeMainOrderID($secret_code,$user_id,$order_id);
						}


						$payment = array(

							'amount' => $order_total, 
							'payment_id' => $payment_id, 
							'razorpay_order_id' => $post_arr['razorpay_order_id'] , 
						);

						$insert_payment=$this->Website_model->insertPaymentDetails($user_id,date('Y-m-d'),$cart_data,$payment);


						if($insert_payment)
						{

							$json_response['msg']="Your order placed Successfully...!";
							$json_response['date'] =date('Y-m-d H:i:s');

							$json_response['paid'] = TRUE;
							$json_response['data'] = $response;
							$json_response['success'] = TRUE;
							$this->output
							->set_status_header(200)
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($json_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
							->_display();
							exit(); 		
						}
						else
						{

							$response['status']=FALSE;
							$response['msg']="Error on checkout";

							$this->output
							->set_status_header(200)
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
							->_display();
							exit(); 

						}
					}
					else{

						$response['order_id'] = $post_arr['razorpay_order_id'];
						$response['payment_id'] = $post_arr['razorpay_payment_id'];
						$response['amount'] = $post_arr['amount'] / 100;
						$response['date'] = date( 'Y-m-d H:i', $response['created_at']);
						$response['success'] = TRUE;
						$response['msg'] = 'Payment on Processing'; 

						$this->output
						->set_status_header(200)
						->set_content_type('application/json', 'utf-8')
						->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
						->_display();
						exit(); 
						die();


					}
				}

			}elseif($error = $this->form_validation->error_array()){

				$response['success'] = FALSE;
				$response['msg'] = 'Error you  have performed'; 
				$response['errors'] = array_keys($this->form_validation->error_array());

				$error =  $this->form_validation->error_array();

				$response['error_msgs'] = $error; 

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 
			}
		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		} 
	}

	protected function validate_update_payment() 
	{  
		// $this->form_validation->set_rules( 'razorpay_order_id', 'Order ID', 'required');
		$this->form_validation->set_rules( 'razorpay_payment_id', 'Payment ID', 'required');
		// $this->form_validation->set_rules( 'razorpay_signature', 'Signature', 'required');
		$val_res = $this->form_validation->run();
		return $val_res;
	}

	public function world_line_payment()
	{
		try
		{ 
			$token = $this->check_header();  
			$user_id=$token->user_id;
			if($this->validate_worldline_payment()){

				$post_arr = $this->input->post();

				if($post_arr['checkout_order_id'])
				{
					$order_id_db=$this->Site_model->encrypt_decrypt('decrypt',$post_arr['checkout_order_id']);
					

					$address_id = $this->Site_model->getUserLoginFieldInfo('default_address_id',$user_id);
					$address_details = $this->Customer_model->getAddressDetails($address_id);
					if($address_details['countries']== 99)
					{
						$state = $address_details['state'];
						$state_details = $this->Zone_model->getAllZones($state,$address_details['countries']);
						$shipping_charge = $state_details[0]['shipping_fee'];
					}
					else
					{
						$country_details = $this->Settings_model->getCountryDetails($address_details['countries']);
						$shipping_charge  = $country_details['shipping_charge'];
					}
					$amount=$this->Website_model->getDetailsCheckoutOrders('total_amount',$order_id_db);
					if($amount >= 12000)
					{
						$shipping_charge = 0;
					}


					$amount = $amount + $shipping_charge;
					$amount=$amount*100;

					require_once(APPPATH.'third_party/worldline/AWLMEAPI.php');


					$obj = new AWLMEAPI();

					$reqMsgDTO = new ReqMsgDTO();

					$reqMsgDTO->setMid(MID);
					$order_id=time().rand(0,1000);

					$reqMsgDTO->setOrderId($order_id);

					$reqMsgDTO->setTrnAmt($amount);

					$reqMsgDTO->setTrnRemarks("This txn has to be done ");
					$reqMsgDTO->setMeTransReqType($_REQUEST['meTransReqType']);

					$reqMsgDTO->setEnckey(ENCKEY);

					$reqMsgDTO->setTrnCurrency("INR");

					$reqMsgDTO->setRecurrPeriod($_REQUEST['recurPeriod']);

					$reqMsgDTO->setRecurrDay($_REQUEST['recurDay']);

					$reqMsgDTO->setNoOfRecurring($_REQUEST['numberRecurring']);

					$url=base_url("website/worldline_payment_success/".$id);
					$reqMsgDTO->setResponseUrl($url);

					$reqMsgDTO->setAddField1($_REQUEST['addField1']);
					$reqMsgDTO->setAddField2($_REQUEST['addField2']);
					$reqMsgDTO->setAddField3($_REQUEST['addField3']);
					$reqMsgDTO->setAddField4($_REQUEST['addField4']);
					$reqMsgDTO->setAddField5($_REQUEST['addField5']);
					$reqMsgDTO->setAddField6($_REQUEST['addField6']);
					$reqMsgDTO->setAddField7($_REQUEST['addField7']);
					$reqMsgDTO->setAddField8($_REQUEST['addField8']);


					$merchantRequest = "";
					$mid = "";
					$payment_order_id=$reqMsgDTO->getOrderId();

					$reqMsgDTO = $obj->generateTrnReqMsg($reqMsgDTO);
					if ($reqMsgDTO->getStatusDesc() == "Success"){
						$merchantRequest = $reqMsgDTO->getReqMsg();
						$mid = $reqMsgDTO->getMid();
						$insert=$this->Website_model->insertPaymentRequest($user_id,$amount,$reqMsgDTO,$payment_order_id,$order_id_db);
					}


					$response['merchantRequest'] = $merchantRequest; 
					$response['mid'] = $mid; 

					$this->output
					->set_status_header(200)
					->set_content_type('application/json', 'utf-8')
					->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
					->_display();
					exit(); 
				}
				else
				{
					show_404();
				}
			}
		}

		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		}
	}
	protected function validate_worldline_payment() 
	{  
		$this->form_validation->set_rules( 'checkout_order_id', 'Order ID', 'required');

		$val_res = $this->form_validation->run();
		return $val_res;
	}


	public function insert_order()
	{

		try
		{ 
			$token = $this->check_header();  
			$user_id=$token->user_id;
			$post_arr=$this->input->post();

			$razorpay_id=$post_arr['razorpay_order_id'];
			$checkout_order_id=$post_arr['checkout_order_id'];
			$amount=$post_arr['sub_total'];
			$cart_contents = $this->cart->contents();
			$order_id_db=$this->Site_model->encrypt_decrypt('decrypt',$checkout_order_id);


			$payment_id=$this->Website_model->insertPaymentRequest($user_id,$amount,$cart_contents,$checkout_order_id,$order_id_db,$razorpay_id);

			if ($payment_id) {
				
				$response['payment_id'] = $payment_id; 
				$response['success'] = true; 

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 
			}
			else{

				$response['false'] = true; 

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 
			}



		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		}

	}

	public function verify_order()
	{

		try
		{ 
			$token = $this->check_header();  
			$user_id=$token->user_id;
			$post_arr=$this->input->post();

			$razorpay_id=$post_arr['razor_pay_id'];

			$date=date('Y-m-d H:i:s');   


			$order_id = $this->Website_model->getCheckoutOrderID($razorpay_id);
			$payment_status='success';





			if(!$order_id)
			{
				$response['success'] = FALSE; 

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 

			}
			$order_id_db=$this->Site_model->encrypt_decrypt('decrypt',$order_id);



			$payment_id = $order_id_db;

			$user_id=$this->Website_model->getDetailsCheckoutOrders('customer_id',$order_id_db);

			// print_r($user_id);
			// die();

			

			$cart_data=$this->Website_model->getDetailsCheckoutOrders('cart_data',$order_id_db);
			$un_cart_data=unserialize($cart_data);


			$order_status=$this->Website_model->getDetailsCheckoutOrders('order_status',$order_id_db);



			
			// $sess_array=$this->session->userdata('site_logged_in'); 
			// $sess_array['user_id'] = $user_id;
			// $sess_array['user_name'] = $this->Site_model->userNameToID($user_id);
			// $sess_array['user_type'] = 'user';

			// $this->session->set_userdata('site_logged_in', $sess_array);
			// $this->session->set_userdata('last_login_timestamp', time());
			// $this->session->set_userdata('sign_customer', null);

			$payment_order_id=$this->Website_model->getDetailsPaymentRequest('payment_order_id',$order_id_db);

			$order_total=$this->Website_model->getDetailsCheckoutOrders('total_amount',$order_id_db);


			$user_type=$this->Site_model->getUserType($user_id);
			$address_id=$this->Customer_model->getUserAddresses($user_id);

	

			$address_id = $this->Site_model->getUserLoginFieldInfo('default_address_id',$user_id);
			$address_details = $this->Customer_model->getAddressDetails($address_id);
			if($address_details['countries']== 99)
			{
				$state = $address_details['state'];
				$state_details = $this->Zone_model->getAllZones($state,$address_details['countries']);
				$shipping_charge = $state_details[0]['shipping_fee'];
			}
			else
			{
				$country_details = $this->Settings_model->getCountryDetails($address_details['countries']);
				$shipping_charge  = $country_details['shipping_charge'];
			}
			if($order_total >= 12000)
			{
				$shipping_charge = 0;
			}

			$secret_code=$this->Website_model->getDetailsCheckoutOrders('secret_code',$order_id_db);



			if($secret_code !='NA' && $secret_code != NULL)
			{
				$gift_status = 1;
			}
			else
				$gift_status = 0;




			$order_id = $this->Customer_model->placeOrder($user_id,$un_cart_data, $order_total, "", $user_type, $address_id,$order_id_db ,$gift_status,$shipping_charge,0,'RazorPay',$order_status);


			if($gift_status == 1)
			{
				$this->Website_model->updateSecretCodeMainOrderID($secret_code,$user_id,$order_id);
			}

			$product_details=$this->Customer_model->getOrderDetails($order_id);

			$contents="";
			foreach ($product_details as $v) {
				$contents=$contents . '
				<tr mc:repeatable="">

				<td class="dataTableContent" mc:edit="data_table_content01" valign="top"><a href="'.base_url().'product-details/'.$v['enc_id'].'" style="text-decoration: none;"> '. $v['product_name'] .'</a>
				</td> 
				<td class="dataTableContent" mc:edit="data_table_content01" valign="top">
				<a href="'.base_url().'product-details/'.$v['enc_id'].'"><img src="'.assets_url().'images/files/product/'.$v['image'].'" alt="" height="50" width="50"></a>						
				</td>
				<td class="dataTableContent" mc:edit="data_table_content01" valign="top" style="text-align: center;">
				'. $v['quantity'] .'

				</td>
				<td class="dataTableContent" mc:edit="data_table_content01" valign="top">
				'. cur_format($v['amount']) .'

				</td>
				<td class="dataTableContent" mc:edit="data_table_content01" valign="top">
				'. cur_format($v['subtotal']) .'

				</td>	

				</tr>

				'; 
			} 






			$shipping_address=$this->Customer_model->getAddressDetails($address_id);
			$customer_details = $this->Customer_model->getAllUserDetails($user_id);



			$this->Website_model->updateRazorpayOrderStatus($razorpay_id);
			$response=array();


			$response['payment_status']=$payment_status;
			$response['contents']=$contents;
			$response['email']=$customer_details['email'];
			$response['count']=count($product_details) + 1;
			$response['order_number']="O#".($order_id + 000);
			$response['date']=date('Y-m-d');
			$response['product_details']=$product_details;
			$response['total_amount']=$order_total;
			$response['full_name'] =  $shipping_address['first_name'] ." " .  $shipping_address['last_name'];
			$response['address'] =  $shipping_address['address'];
			if ( element('city', $shipping_address)) {
				$response['address'] .= " , " .  $shipping_address['city'] ;
			}
			$response['address'] .=  " , " .  $shipping_address['pin_code'] ." , " .  $shipping_address['state_name'] ." , ". $shipping_address['country_name'];

			$this->Website_model->commit();
			$drop=$this->cart->destroy();
			$this->load->model('Mail_model');
			$send_mail = $this->Mail_model->sendEmails('order_confirmation', $response ,$product_details); 
			if($send_mail)
			{
				$response['email']="Loacamazon@gmail.com";
				$send_mail_new = $this->Mail_model->sendEmails('order_confirmation_another', $response ,$product_details); 
			}
			$site_info = $this->Settings_model->getCompanyInformation();
			$response['customer_email']=$customer_details['email'];
			$response['enc_order_id']=$this->Site_model->encrypt_decrypt('encrypt',$order_id);

			$messagecontent = "Dear ".$response['full_name'].",Your Order #$order_id placed successfully by Happima.in ,Product will delivered shortly,Thank you for shopping with LOAC CLOTHING & FASHION";
			$mobile = $shipping_address['phone_number'];

			$this->Base_model->sendSMS($user_id,$messagecontent,$mobile);


			if($gift_status == 1)
			{
				$messagecontent = "Hello ".$response['full_name'].",Congratulations,you have successfully applied Secret code of Happima,you will get our Gift item soon,Regards LOAC FASHION";
				$this->Base_model->sendSMS($user_id,$messagecontent,$mobile);
			}


			$payment = array(

				'amount' => $order_total, 
				'payment_id' => $payment_id, 
				'razorpay_order_id' => $razorpay_id , 
			);

			$insert_payment=$this->Website_model->insertPaymentDetails($user_id,date('Y-m-d'),$cart_data,$payment);


			if($insert_payment)
			{

				$enc_order_id= $this->Site_model->encrypt_decrypt('encrypt', $order_id);
				$response['success'] = true; 
				$response['enc_order_id'] = $enc_order_id; 
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 



			}
			else
			{
				$response['success'] = FALSE; 
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit(); 
			}





		}
		catch( Exception $e )
		{
			log_message( 'error', $e->getMessage( ) . ' in ' . $e->getFile() . ':' . $e->getLine() );
		}

	}



}