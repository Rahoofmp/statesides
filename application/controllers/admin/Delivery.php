<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Delivery extends Base_Controller {

	function __construct()
	{
		parent::__construct(); 	
	}

	function create()
	{  
		$data['title'] = lang('create');  


		if($this->input->post('submit') && $this->validate_create())
		{
			$post=$this->input->post();


			$this->Delivery_model->begin();

			$post['user_id'] = log_user_id();


			$result = $this->Delivery_model->addDeliveryNote($post);
			
			if($result)
			{
				$package_id = $this->Base_model->encrypt_decrypt('encrypt', $result);
				$this->Delivery_model->commit();

				$msg = "Added Successfully";
				$this->redirect($msg,'delivery/add-delivery-items/'.$package_id,True);
			}
			else
			{
				$this->Delivery_model->rollback();
				$msg = "Error on adding";
				$this->redirect($msg,'delivery/create',False);
			}

		}
		// print_r($this->form_validation->error_array());


		$this->loadView($data);
	}
	function create_category($enc_id='')
	{  
		$data['title'] = "Create Category";  
		$data['code']=$this->Delivery_model->getMaxCategoryId()+1001;
		if ($enc_id) {
			$data['title'] = "Edit Category";  
			$data['main_categories']=$this->Base_model->getMainCategory('');
			

			$id=$this->Base_model->encrypt_decrypt('decrypt',$enc_id);
			$data['id']=$id;
			$data['edit_category']=element(0,$this->Delivery_model->getAllCategories($id));
			// print_r($data['edit_category']);
			// die();
		}
		if($this->input->post('submit')=='add_category' && $this->validate_create_category())
		{
			
			$post=$this->input->post();



			$this->Delivery_model->begin();

			$post['user_id'] = log_user_id();


			$result = $this->Delivery_model->addCategory($post);
			
			if($result)
			{

				$this->Delivery_model->commit();

				$msg = "Added Successfully";
				$this->redirect($msg,'delivery/create-category',True);
			}
			else
			{
				$this->Delivery_model->rollback();
				$msg = "Error on adding";
				$this->redirect($msg,'delivery/create-category',False);
			}

		}
		if($this->input->post('submit')=='update_category' && $this->validate_create_category())
		{
			$post=$this->input->post();
			if (array_key_exists('set_main_category', $post)) {
				$post['main_category']=0; 
				// $post['sort_order']=$post['sort_order'];
			}
			else{ 
				$post['sort_order']=0; 
				$post['main_category']=$post['edit_category_id'];

			}


			$this->Delivery_model->begin();

			$post['user_id'] = log_user_id();


			$result = $this->Delivery_model->editCategory($post,$id);
			
			if($result)
			{

				$this->Delivery_model->commit();

				$msg = "Updated Successfully";
				$this->redirect($msg,"delivery/create-category/$enc_id",True);
			}
			else
			{
				$this->Delivery_model->rollback();
				$msg = "Error on updating";
				$this->redirect($msg,"delivery/create-category/$enc_id",False);
			}

		}
		// print_r($this->form_validation->error_array());


		$this->loadView($data);
	}
	private function validate_create_category() 
	{
		$post=$this->input->post();
	 
		$this->form_validation->set_rules('code', 'Category Code', 'trim|required');

		$this->form_validation->set_rules('category_name', 'Category Name', 'required');
		if (!array_key_exists('set_main_category', $post)) {
			if ($post['submit']=='add_category') {
				
				$this->form_validation->set_rules('category_id', 'Category ID', 'required');
			}
			else{
				$this->form_validation->set_rules('edit_category_id', 'Category ID', 'required');
			}
		}else{
			$this->form_validation->set_rules('sort_order', 'Sort Order', 'required|numeric');
		}
 
		$result =  $this->form_validation->run();
		 
		return $result;
	}
	function list_category()
	{  
		$data['title'] = "Create Category";  
		$data['categories']=$this->Delivery_model->getAllCategories();
		$this->loadView($data);
	}
	private function validate_create() 
	{

		$this->form_validation->set_rules('project_id', 'Project Name', 'trim|required|is_exist[project.id]');


		// if(element( 'by_package_name', $this->input->post())){
		// 	$_POST['package_id'] = $this->Base_model->getPackageIdByName($this->input->post('package_name')); 
		// 	$this->form_validation->set_rules('package_name', 'Package Name', 'required|callback_checkPackageNameExist');
		// }else{
		// 	$_POST['package_id'] = $this->Base_model->getPackageIdByCode($this->input->post('package_name')); 
		// 	$this->form_validation->set_rules('package_name', 'Package Code', 'required|integer|greater_than[0]|callback_checkPackageCodeExist');
		// 	$this->form_validation->set_message('integer', 'Package code must me an number');
		// }

		$this->form_validation->set_rules('driver_id', 'Driver', 'required|callback_checkDriverExist');

		$result =  $this->form_validation->run();

		return $result;
	}


	public function checkPackageNameExist($package_id) {

		$exist = $this->Base_model->isPackageNameExist($package_id);

		$this->form_validation->set_message('checkPackageNameExist', 'Package Name not Exist');

		if($exist){
			return TRUE;
		}
		return FALSE;
	}

	public function checkDriverExist($driver_id) {

		$exist = $this->Base_model->isUserExist($driver_id, 'driver');

		$this->form_validation->set_message('checkDriverExist', 'Driver not Exist');

		if($exist){
			return TRUE;
		}
		return FALSE;
	}

	public  function checkPackageCodeExist($package_id) {

		$exist = $this->Base_model->isPackageCodeExist($package_id);

		$this->form_validation->set_message('checkPackageCodeExist', 'Package Code not Exist');

		if($exist){
			return TRUE;
		}
		return FALSE;
	}

	public  function delivery_details($enc_id='')
	{
		$data['title']='Delivery Details';
		$post_arr['packages'] = true;
		$data['enc_id']=$enc_id;
		if($enc_id){

			$_POST['delivery_id'] = $delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );


			if($delivery_id){
				$data['delivery_id']=$delivery_id;

				$this->form_validation->set_rules('delivery_id', 'Delivery Note', 'required|callback_checkDeliveryIdExist[all]');
				$this->form_validation->set_rules('status', 'Status', 'required|in_list[send_to_delivery,delivered]');

				$data['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );
				// unset($data['details']['packages'][0]);
				if($this->form_validation->run() && $this->input->post('update_status')){
					$post_arr = $this->input->post();
					
					$this->load->model('Mail_model');
					$this->Delivery_model->begin();
					$update_delivery_status = $this->Delivery_model->updateDeliveryStatus( $delivery_id, $post_arr['status'] );

					$mail_arr = array(
						'email' => $data['details']['email'],
						'fullname' => $data['details']['cus_name'],
						'user_id' => $data['details']['user_id'],
						'delivery_code' => $data['details']['code'],
						'project_code' => $data['details']['project_code'],
						'project_name' => $data['details']['project_name'],
						'status' => ucfirst(str_replace('_', ' ', $post_arr['status'])),
						'delivery_person' => $data['details']['driver_name'],
					);

					if( $post_arr['status'] == 'send_to_delivery' ){
						$delivery_package_row_ids = array_column($data['details']['packages'], 'id');
						$mail_arr['subject'] = 'Packages Send To Delivery';
						$mail_arr['status'] = 'Out For Delivery';
					}else{			
						$mail_arr['subject'] = 'Packages Delivered';	

						if( element('delivery_package', $post_arr) ){
							$delivery_package_row_ids = array_keys($post_arr['delivery_package']);
						}else{
							$delivery_package_row_ids = [];

						}
					}

					if(empty($delivery_package_row_ids)){
						$this->Delivery_model->rollback(); 
						$msg="Failed, packages are empty... Please add atleast one package";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, FALSE);
					}
					$update_delivery_package_status = $this->Delivery_model->updateDeliveryPackageStatus( $delivery_package_row_ids, $post_arr['status'], $delivery_id );


					$update_package_status = $this->Delivery_model->updatePackageStatus( $delivery_package_row_ids, $post_arr['status'] );

					if($update_delivery_status && $update_package_status && $update_delivery_package_status)
					{ 					
						$this->Delivery_model->commit(); 
						$data['details']['status'] = $mail_arr['status'];
						$data['details']['project_package_status'] = $mail_arr['status'];
						$mail_arr['attachmentString'] = $this->generatePdf($data['details']);	

						$mail_send=$this->Mail_model->sendEmails("update_delivery_status",$mail_arr);   

						$msg="Udpated Successfully";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, TRUE);
					} else {

						$this->Delivery_model->rollback();

						$msg="Failed, please try again...";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, FALSE);
					}

				}


				$this->loadView($data);
			}else{
				show_error('Invalid Delivery Id', '404' );
			}
		}else{
			show_error('Delivery Id not exist', '404' );
		}
	}



	function generatePdf($data){
		$this->load->library('Pdf');

		$project_package_status = ucfirst($data['project_package_status']);
		$delivery_status = str_replace( '_', ' ' , ucfirst($data['status']));


		$tableContent = '';
		$package_count=0;
		$row_count =0;
		foreach ($data['packages'] as $key => $v) {

			$row_count = count($data['packages'][$key]['items']);
			$package_count = $row_count;

			$tableContent .= '<tr style="font-size: 10px">';

			$row_count_count = $row_count;
			if (count($data['packages'][$key]['items']) >= 2){
				$rowspan =  count($data['packages'][$key]['items']) > 0 ? count($data['packages'][$key]['items']) : 1;
				// $rowspan= $rowspan+1;
				// $tableContent .= '<td rowspan="'.$rowspan.'" >' ;
				// $tableContent .= $key+1; 
				// $tableContent .= '</td>';

				$tableContent .= '<td rowspan="'.$rowspan.'" style="border-bottom: 1px solid #222">' ;
				$tableContent .= '<strong>'. ($key+1) .'.</strong>P-Code: '.$v['package_code'].' <br>'; 
				$tableContent .= '<small>Name: <span>'. $v['package_name'] .'</span></small><br>'; 
				$tableContent .= '<small>Status: <span>'. str_replace( '_', ' ' , ucfirst($v['status'])) .'</span></small><br>'; 
				$tableContent .= '</td>';


				foreach ($data['packages'][$key]['items'] as $p_key => $pi) {

					if($p_key == 0){

						$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['serial_no'] .'</td>';
						$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['name'] .'</td>';
						$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['qty'] .'</td>';

						$row_count = $row_count -1;
						$tableContent .= '</tr>';


					}else{
						$tableContent .= '<tr style="font-size: 10px">';

						$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['serial_no'] .'</td>';
						$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['name'] .'</td>';
						$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['qty'] .'</td>';

						$row_count = $row_count -1;
						$tableContent .= '</tr>';

					}

				}
				$package_count= $package_count+1;
			}else{				

				$rowspan =  $row_count > 0 ? $row_count : 1;
				$rowspan= $rowspan+2;


				$tableContent .= '<td rowspan="'.$row_count_count.'" style="border-bottom: 1px solid #222">' ;
				$tableContent .= '<strong>'. ($key+1) .'.</strong>P-Code: '.$v['package_code'].' <br>'; 
				$tableContent .= '<small>Name: <span>'. $v['package_name'] .'</span></small><br>'; 
				$tableContent .= '<small>Status: <span>'. str_replace( '_', ' ' , ucfirst($v['status'])) .'</span></small><br>'; 
				$tableContent .= '</td>';

				foreach ($data['packages'][$key]['items'] as $p_key => $pi) {
					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['serial_no'] .'</td>';
					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['name'] .'</td>';
					$tableContent .= '<td style="border-bottom: 1px solid #222">' .$pi['qty'] .'</td>';
				}
				$tableContent .= '</tr>';
			}

		}

		$site_logo = base_url('assets/images/logo/logo_pdf.png');
		$delivery_qr = base_url('assets/images/qr_code/delivery/10459').'.png';
		$project_qr = base_url('assets/images/qr_code/project/9'). '.png';
		// $delivery_qr = base_url('assets/images/qr_code/delivery/').$data['code']. '.png';
		// $project_qr = base_url('assets/images/qr_code/project/').$data['project_id']. '.png';




		$html = '
		<style>
		table, tr, td {
			padding: 15px;
		}
		</style>
		<table >
		<tbody>
		<tr>
		<td><img src="'.$site_logo.'" height="60px"/><br/>
		<strong>'.$this->data["site_details"]["name"].'</strong> 
		<small>hello@pinetreelane.com</small><br/>
		<small>'.$this->data[ 'site_details' ]['phone'].'</small>
		<p><strong>DELIVERY NOTE</strong></p>
		</td>
		<td align="right">
		<strong>CUSTOMER DETAILS</strong>
		<br/>
		Name: '.$data['cus_name'].'<br/>
		Email: '.$data['customer_email'].'<br/>
		Mobile: '.$data['customer_mobile'].'<br/>
		<img src="'.$project_qr.'" height="60px"/><br/>
		</td>
		</tr>
		</tbody>
		</table>
		';

		$html .= '
		<table>
		<tbody> 
		<tr >
		<td>
		<strong>PROJECT DETAILS</strong><br/>
		Code: '.$data['project_code'].' 
		<br/>
		Name: '.$data['project_name'].' 
		<br/>
		Status: '.$project_package_status.' 
		</td>

		<td align="right">
		<strong>DELIVERY DETAILS</strong><br/>
		Delivery Code: '.$data['code'].' <br/>
		Delivery Person: '.$data['driver_name'].' <br/>
		Vehicle: '.$data['vehicle'].'  <br/>
		Status: '.$delivery_status.'  <br/>
		<img src="'.$delivery_qr.'" height="60px"/><br/>
		</td>
		</tr>
		</tbody>
		</table>
		';

		$html .= '
		<table>
		<thead>
		<tr style="font-weight:bold; background-color:#dbdbdb" >
		<th>Package</th>
		<th>Code</th>
		<th>Item name</th> 
		<th>Quantity</th> 
		</tr>
		</thead>
		<tbody>';

		
		$html .= $tableContent;
		$html .= '
		</tbody>
		</table>';


		$html .= '
		<table>
		<tbody>
		<tr>
		<td>
		<h2>Thank you for your business.</h2><br/>
		<strong>Terms and conditions:<br/></strong>
		This is a computer generated delivery note. Goods once dispatched are to be considered in good condition unless company is notified within 3 day\'s of receipt .
		<br/>
		<br/>
		For 
		<br/>
		Pine Tree Lane Furniture Manufacturing LLC
		</td>
		</tr>
		</tbody>
		</table>
		';

		// echo $html;
		// $this->Delivery_model->rollback(); 
		// die();
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


		$pdf->SetMargins(-1, 0, -1);

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$pdf->setFontSubsetting(true);

		$fontname = TCPDF_FONTS::addTTFfont('./assets/ubuntu.ttf', 'TrueTypeUnicode', '', 96);
		$pdf->SetFont($fontname, '', 10);
		$pdf->AddPage();

		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);

		// $attachmentString= $pdf->Output('Delivery-details.pdf', 'I');	
		return $attachmentString= $pdf->Output('Delivery-details.pdf', 'S');

	}


	// function generatePdf($data){
	// 	$this->load->library('Pdf');
	// 	$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	// 	$tcpdf->SetCreator(PDF_CREATOR);
	// 	$tcpdf->SetAuthor('Pine Tree');
	// 	$tcpdf->SetTitle('Delivery Details');
	// 	$tcpdf->SetSubject('Delivery');
	// 	$tcpdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// 	$tcpdf->setFooterData(array(0,65,0), array(0,65,127));

	// 	$tcpdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	// 	$tcpdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// 	$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// 	$tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	// 	$tcpdf->SetHeaderMargin(1);
	// 	$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// 	$tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// 	$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// 	if (@file_exists(dirname(__FILE__).'/lang/eng.php'))
	// 	{
	// 		require_once(dirname(__FILE__).'/lang/eng.php');
	// 		$tcpdf->setLanguageArray($l);
	// 	}

	// 	$tcpdf->setFontSubsetting(true);

	// 	$tcpdf->AddPage();

	// 	$tcpdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,197,198), 'opacity'=>1, 'blend_mode'=>'Normal'));

	// 	$project_package_status = ucfirst($data['project_package_status']);
	// 	$delivery_status = str_replace( '_', ' ' , ucfirst($data['status']));


	// 	$tableContent = '';
	// 	$package_count=0;
	// 	$row_count =0;
	// 	foreach ($data['packages'] as $key => $v) {

	// 		$row_count = count($data['packages'][$key]['items']);
	// 		$package_count = $row_count;

	// 		$tableContent .= '<tr style="font-size: 10px">';

	// 		$row_count_count = $row_count;
	// 		if (count($data['packages'][$key]['items']) >= 2){
	// 			$rowspan =  count($data['packages'][$key]['items']) > 0 ? count($data['packages'][$key]['items']) : 1;
	// 			$rowspan= $rowspan+1;
	// 			$tableContent .= '<td rowspan="'.$rowspan.'" align="center">' ;
	// 			$tableContent .= $key+1; 
	// 			$tableContent .= '</td>';

	// 			$tableContent .= '<td rowspan="'.$rowspan.'" align="center">' ;
	// 			$tableContent .= 'Code: '.$v['package_code'].' <br>'; 
	// 			$tableContent .= '<small>Name: <span>'. $v['package_name'] .'</span></small><br>'; 
	// 			$tableContent .= '<small>Status: <span>'. str_replace( '_', ' ' , ucfirst($v['status'])) .'</span></small><br>'; 
	// 			$tableContent .= '</td>';


	// 			foreach ($data['packages'][$key]['items'] as $p_key => $pi) {

	// 				if($p_key == 0){

	// 					$tableContent .= '<td>' .$pi['serial_no'] .'</td>';
	// 					$tableContent .= '<td>' .$pi['name'] .'</td>';
	// 					$tableContent .= '<td>' .$pi['qty'] .'</td>';

	// 					$row_count = $row_count -1;
	// 					$tableContent .= '</tr>';


	// 				}else{
	// 					$tableContent .= '<tr style="font-size: 10px">';

	// 					$tableContent .= '<td>' .$pi['serial_no'] .'</td>';
	// 					$tableContent .= '<td>' .$pi['name'] .'</td>';
	// 					$tableContent .= '<td>' .$pi['qty'] .'</td>';

	// 					$row_count = $row_count -1;
	// 					$tableContent .= '</tr>';

	// 				}

	// 			}
	// 			$package_count= $package_count+1;
	// 		}else{				

	// 			$rowspan =  $row_count > 0 ? $row_count : 1;
	// 			$rowspan= $rowspan+2;
	// 			$tableContent .= '<td rowspan="'.$row_count_count.'" align="center">' ;
	// 			$tableContent .= $key+1; 
	// 			$tableContent .= '</td>';

	// 			$tableContent .= '<td rowspan="'.$row_count_count.'" align="center">' ;
	// 			$tableContent .= 'Code: '.$rowspan.'-----'.$v['package_code'].' <br>'; 
	// 			$tableContent .= '<small>Name: <span>'. $v['package_name'] .'</span></small><br>'; 
	// 			$tableContent .= '<small>Status: <span>'. str_replace( '_', ' ' , ucfirst($v['status'])) .'</span></small><br>'; 
	// 			$tableContent .= '</td>';

	// 			foreach ($data['packages'][$key]['items'] as $p_key => $pi) {
	// 				$tableContent .= '<td>' .$pi['serial_no'] .'</td>';
	// 				$tableContent .= '<td>' .$pi['name'] .'</td>';
	// 				$tableContent .= '<td>' .$pi['qty'] .'</td>';
	// 			}
	// 			$tableContent .= '</tr>';
	// 		}

	// 	}

	// 	$site_logo = base_url('assets/images/logo/').$this->data[ 'site_details' ]['logo'];
	// 	$project_qr = base_url('assets/images/qr_code/project/').$data['project_id']. '.png';
	// 	$delivery_qr = base_url('assets/images/qr_code/delivery/').$data['code']. '.png';

	// 	$set_html = <<<EOD
	// 	<table>
	// 	<thead >
	// 	</thead>
	// 	<tbody>
	// 	<tr>
	// 	<td>
	// 	<img class="media-object img-60 " src="{$site_logo}">
	// 	<br>
	// 	<span style="font-size:12px">
	// 	{$this->data[ 'site_details' ]['name']}
	// 	</span><br>
	// 	<span style="font-size:9px">
	// 	hello@pinetreelane.com
	// 	</span><br>
	// 	<span style="font-size:9px">
	// 	{$this->data[ 'site_details' ]['phone']}
	// 	</span>
	// 	</td>
	// 	<td align= "right">
	// 	<span style="font-size:12px">
	// 	CUSTOMER DETAILS
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Name: {$data['cus_name']}
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Email : {$data['customer_email']} 
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Mobile : {$data['customer_mobile']}       
	// 	</span> 
	// 	<img src="{$project_qr}" width="60" style="max-width: 100px">
	// 	</td>
	// 	</tr>
	// 	<hr >
	// 	<tr>
	// 	<td >
	// 	<span style="font-size:12px">
	// 	PROJECT DETAILS
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Code: {$data['project_code']}   
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Name: {$data['project_name']}      
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Status: {$project_package_status}         
	// 	</span> 
	// 	</td>
	// 	<td align= "right">
	// 	<span style="font-size:12px">
	// 	DELIVERY DETAILS
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Delivery Code: {$data['code']} 
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Delivery Person: {$data['driver_name']}  
	// 	</span>
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Vehicle: {$data['vehicle']}        
	// 	</span> 
	// 	<br>
	// 	<span style="font-size:9px">
	// 	Status: {$delivery_status}        
	// 	</span> 
	// 	<img width="60" style="max-width: 100px"src="{$delivery_qr}">
	// 	</td>
	// 	</tr> 
	// 	<tr>
	// 	<td colspan="2">
	// 	<table style="margin-top: 20px;" border="1" border-color="#e32727">
	// 	<thead class="bg-light">
	// 	<tr style="background-color: #f8f9fa !important">
	// 	<th style="margin:30px"> Sl. No: </th>
	// 	<th style="margin:30px"> Package </th>
	// 	<th style="margin:30px"> Code </th>
	// 	<th style="margin:30px"> Items  </th>
	// 	<th style="margin:30px"> Quantity  </th>
	// 	</tr>
	// 	</thead>
	// 	<tbody>
	// 	{$tableContent}
	// 	</tbody>
	// 	</table>
	// 	</td>
	// 	</tr>
	// 	</tbody>
	// 	</table>
	// 	EOD;

	// 	// echo $set_html;
	// 	// die();
	// 	$tcpdf->writeHTMLCell(0, 0, '', '', $set_html, 0, 1, 0, true, '', true);

	// 	return $attachmentString= $tcpdf->Output('Delivery-details.pdf', 'S');



	// }


	public  function print_delivery_details($enc_id='')
	{
		$data['title']='Delivery Details';
		$post_arr['packages'] = true;
		$data['enc_id']=$enc_id;
		if($enc_id){
			$_POST['delivery_id'] = $delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );
			if($delivery_id){
				$this->form_validation->set_rules('delivery_id', 'Delivery Note', 'required|callback_checkDeliveryIdExist[all]');
				$this->form_validation->set_rules('status', 'Status', 'required|in_list[send_to_delivery,delivered]');

				$data['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true );
				// unset($data['details']['packages'][0]);
				if($this->form_validation->run() && $this->input->post('update_status')){
					$post_arr = $this->input->post();

					$this->load->model('Mail_model');
					$this->Delivery_model->begin();
					$update_delivery_status = $this->Delivery_model->updateDeliveryStatus( $delivery_id, $post_arr['status'] );

					$mail_arr = array(
						'email' => $data['details']['email'],
						'fullname' => $data['details']['cus_name'],
						'user_id' => $data['details']['user_id'],
						'delivery_code' => $data['details']['code'],
						'project_code' => $data['details']['project_code'],
						'project_name' => $data['details']['project_name'],
						'status' => ucfirst(str_replace('_', ' ', $post_arr['status'])),
						'delivery_person' => $data['details']['driver_name'],
					);

					if( $post_arr['status'] == 'send_to_delivery' ){
						$delivery_package_row_ids = array_column($data['details']['packages'], 'id');
						$mail_arr['subject'] = 'Packages Send To Delivery';
					}else{			
						$mail_arr['subject'] = 'Packages Delivered';	

						if( element('delivery_package', $post_arr) ){
							$delivery_package_row_ids = array_keys($post_arr['delivery_package']);
						}else{
							$delivery_package_row_ids = [];

						}
					}

					if(empty($delivery_package_row_ids)){
						$this->Delivery_model->rollback(); 
						$msg="Failed, packages are empty... Please add atleast one package";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, FALSE);
					}
					$update_delivery_package_status = $this->Delivery_model->updateDeliveryPackageStatus( $delivery_package_row_ids, $post_arr['status'], $delivery_id );


					$update_package_status = $this->Delivery_model->updatePackageStatus( $delivery_package_row_ids, $post_arr['status'] );

					if($update_delivery_status && $update_package_status && $update_delivery_package_status)
					{
						$mail_send=$this->Mail_model->sendEmails("update_delivery_status",$mail_arr);

						$this->Delivery_model->commit();

						$msg="Udpated Successfully";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, TRUE);
					} else {

						$this->Delivery_model->rollback();

						$msg="Failed, please try again...";
						$this->redirect($msg,'delivery/delivery-details/'.$enc_id, FALSE);
					}

				}


				$this->loadView($data);
			}else{
				show_error('Invalid Delivery Id', '404' );
			}
		}else{
			show_error('Delivery Id not exist', '404' );
		}
	}

	public  function show_qrcode($enc_id='')
	{
		$data['title']='Delivery Details';
		$this->loadView($data);
	}

	public function add_delivery_items($enc_id='')
	{
		$data['title']="Delivery Details";

		$data['enc_id']=$enc_id;

		$delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_id );

		$data['details'] = $this->Delivery_model->getDeliveryInfo( $delivery_id, true, true,'pending');

		$this->loadView($data);
	}




	public function getDeliveryPackages()
	{	
		if($this->validate_delivery_package()){

			$this->load->model('Packages_model');
			$response['package_info'] = $this->Packages_model->getPackagesDetails($this->input->post('package_id'), false);

			$response['success'] = TRUE;
			$response['msg'] = 'succces';
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();

		}else{

			$response['success'] = FALSE;
			$response['msg'] = implode( ', ', $this->form_validation->error_array());
			$this->output
			->set_status_header(200)
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
			->_display();
			exit();
		} 

	}

	function validate_delivery_package() 
	{
		$_POST['id'] =  $this->Base_model->encrypt_decrypt('decrypt', $_POST['id']);

		$this->form_validation->set_rules('id', 'Delivery Id', 'trim|required|is_exist[delivery_notes.id]');


		$_POST['data'] = json_decode($this->input->post('data'));

		if(element( 'by_package_name', $this->input->post()) == 'true'){
			$_POST['package_id'] = $this->Base_model->getPackageIdByName($this->input->post('package'), $this->input->post('project_id')); 

			$package_list = implode( ',', array_column( $_POST['data'], '2') );
			$this->form_validation->set_rules('package', 'Package Name', 'required|differ_list['. $package_list .']|callback_checkPackageNameExist');
		}else{
			$_POST['package_id'] = $this->Base_model->getPackageIdByCode($this->input->post('package')); 
			$package_list = implode( ',', array_column( $_POST['data'], '1') );

			$this->form_validation->set_rules('package', 'Package Code', 'required|integer|greater_than[0]|callback_checkPackageCodeExist|differ_list['. $package_list .']');
			$this->form_validation->set_message('integer', 'Package code must me an number');
		}

		$this->form_validation->set_rules('package_id', 'Package', 'required|integer|greater_than[0]|callback_checkPackageAlreadyAdded|callback_checkHasPackageItems');

		$result =  $this->form_validation->run();

		return $result;
	}

	public function checkPackageAlreadyAdded($package_id) {

		$exist = $this->Delivery_model->checkAlreadyPackageAdded($package_id);

		$this->form_validation->set_message('checkPackageAlreadyAdded', 'Package already choosed for delivery');

		if(!$exist){
			return TRUE;
		}
		return FALSE;
	}

	public function checkHasPackageItems($package_id) {

		$exist = $this->Delivery_model->hasPackageItems($package_id);

		$this->form_validation->set_message('checkHasPackageItems', 'Add atleaset one item to the package');

		if(!$exist){
			return TRUE;
		}
		return FALSE;
	}

	function save_package_items() {

		if ($this->input->is_ajax_request() ) {

			if ( $this->validate_save_items() ) {

				$post = $this->input->post(); 
				$this->Delivery_model->begin();


				foreach ($post['data'] as $key => $value) {

					$package_id = $this->Delivery_model->isPackageByCodeNameExist($value['package_code'], $value['package_name'], $post['project_id'] );

					$already_added = $this->Delivery_model->checkAlreadyPackageAdded( $package_id );


					if( !$already_added ){

						$post['package_id'] = $package_id;

						if(!$package_id ){

							$this->Delivery_model->rollback();
							$response['success'] = FALSE;
							$response['msg'] = 'Failed..! '. $value['package_name'] .' : package not exist';
							$this->set_session_flash_data( $response['msg'], $response['success']  );
							$this->output
							->set_status_header(200)
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
							->_display();
							exit();
						}

						$inserted = $this->Delivery_model->addDeliveryPackages($post);

						if(!$inserted ){

							$this->Delivery_model->rollback();
							$response['success'] = FALSE;
							$response['msg'] = 'Failed..! Please try again';
							$this->set_session_flash_data( $response['msg'], $response['success']  );
							$this->output
							->set_status_header(200)
							->set_content_type('application/json', 'utf-8')
							->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
							->_display();
							exit();
						}
					}
				} 
				$update = $this->Delivery_model->updateDeliveryNotes($post,$post['delivery_id']);
				$this->Delivery_model->commit();
				$response['success'] = TRUE;
				$response['msg'] = 'Successfully added the items...!';
				$this->set_session_flash_data( $response['msg'], $response['success']  );

				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}else{
				$response['success'] = FALSE;
				$response['msg'] = 'Failed..! Please check the inputs';
				$this->set_session_flash_data( $response['msg'], $response['success']  );
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}
	function save_location_details() {

		if ($this->input->is_ajax_request() ) {

			

			$post = $this->input->post(); 
			$this->Delivery_model->begin();

			$delivery_id=$post['id'];
			$location_details=$post['location_details'];
			
			$update = $this->Delivery_model->updateDeliveryLocation($location_details,$delivery_id);
			if ($update) {

				
				$this->Delivery_model->commit();
				$response['success'] = TRUE;
				$response['msg'] = 'Location Details updated!';
				$this->set_session_flash_data( $response['msg'], $response['success']  );
				
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
			else{
				$response['success'] = FALSE;
				$response['msg'] = 'Failed..! Please check the inputs';
				$this->set_session_flash_data( $response['msg'], $response['success']  );
				$this->output
				->set_status_header(200)
				->set_content_type('application/json', 'utf-8')
				->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
				->_display();
				exit();

			}
		}
	}

	private function validate_save_items() { 

		if(element('data', $this->input->post())){
			$_POST['data'] = json_decode($this->input->post('data'));
			$_POST['delivery_id'] =  $this->Base_model->encrypt_decrypt('decrypt', $_POST['id']); 
			unset($_POST['id']);

			foreach ($_POST['data'] as $key => $value) {
				$_POST['data'][$key]['package_code'] = $value['1'];
				$_POST['data'][$key]['package_name'] = $value['2']; 
				unset($_POST['data'][$key]['0']);
				unset($_POST['data'][$key]['1']);
				unset($_POST['data'][$key]['2']);
				unset($_POST['data'][$key]['3']);
				unset($_POST['data'][$key]['4']);

				$this->form_validation->set_rules('data[]', 'Data', 'required'); 
			}
		}else{
			$this->form_validation->set_rules('data', 'Data', 'required'); 
		}
		$this->form_validation->set_rules('delivery_id', 'Delivery ID', 'required|callback_checkDeliveryIdExist');
		$this->form_validation->set_rules('project_id', 'Project ID', 'required|is_exist[project.id]');
		$res = $this->form_validation->run();

		return $res;
	}

	public function checkDeliveryIdExist($delivery_id,$status='pending') {


		$exist = $this->Base_model->isDeliveryIdExist($delivery_id,$status);

		$this->form_validation->set_message('checkDeliveryIdExist', 'Delivery note not Exist');

		if($exist){
			return TRUE;
		}
		return FALSE;
	}


	public function delivery_list($action='',$id='')
	{

		$data['title']="Delivery List";

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']);

			if(element( 'package_id', $post_arr ))				
				$post_arr['package_name'] = $this->Base_model->getPackageName($post_arr['package_id']);

		}else{
			$post_arr['limit'] = '10';
			$post_arr['status'] = 'pending';
		}

		$post_arr['order'] = 'id';
		$post_arr['order_by'] = 'DESC';

		$post_arr['packages'] = true;

		// $data['details'] = $this->Delivery_model->getDeliveryDetails($post_arr);
		$data['post_arr'] = $post_arr;
		// print_r($data['details']);
		// die();
		if($action)
		{
			$data['id']=$this->Base_model->encrypt_decrypt( 'decrypt', $id );	
			$this->Delivery_model->begin();
			$details=$this->Delivery_model->deleteDeliveryNotes($data['id']);

			if($details)
			{
				$this->Delivery_model->commit();
				$msg="Deleted Successfully";
				$this->redirect($msg,'delivery/delivery-list',True);
			}
			else
			{
				$this->Delivery_model->rollback();
				$msg="Error on Deletion";
				$this->redirect($msg,'delivery/delivery-list',false);
			}
		}

		$this->loadView($data);
	}

	public function Reports()
	{

		$data['title']="Reports";
		$post_arr =[];
		if ($this->input->post('search')) {
			$post_arr = $this->input->post(); 
			$this->load->model('Packages_model');
			if(element( 'supervisor_id', $post_arr ))				
				$post_arr['supervisor_name'] = $this->Packages_model->getUserName($post_arr['supervisor_id']);
			if(element( 'project_id', $post_arr ))				
				$post_arr['project_name'] = $this->Base_model->getProjectName($post_arr['project_id']);
			if(element( 'package_id', $post_arr ))				
				$post_arr['package_name'] = $this->Base_model->getPackageName($post_arr['package_id']);
			if(element( 'delivery_code', $post_arr ))				
				$post_arr['code'] = $this->Base_model->getDeliveryCode($post_arr['delivery_code']);

			$data['details'] = $this->Delivery_model->getDeliveryDetails($post_arr);
		// print_r($data['details']);die();
			$data['post_arr'] = $post_arr;
		}		

		$this->loadView($data);
	}


	public function remove_package($enc_delivery_package_id, $enc_delivery_id)
	{
		$delivery_package_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_delivery_package_id);	
		$delivery_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_delivery_id );	

		$details = $this->Delivery_model->removeDeliveryPackage($delivery_package_id, $delivery_id);
		if($details)
		{
			$msg="Package Removed Successfully";
			$this->redirect($msg,'delivery/add-delivery-items/'.$enc_delivery_id, true);
		}
		else
		{
			$msg="Error on Removing package";
			$this->redirect($msg,'delivery/add-delivery-items/'.$enc_delivery_id, false);
		}
		$this->loadView($data);
	}
	public  function read_delivery_code()
	{ 
		$data['title']='Read the delivery code';

		if ($this->input->post('search')) {
			$post_arr = $this->input->post();

			$delivery_id = $this->Delivery_model->getDeliveryIdByCode($post_arr['delivery_code']);

			if($delivery_id){
				$enc_id = $this->Base_model->encrypt_decrypt( 'encrypt', $delivery_id );

				$this->redirect('','delivery/delivery-details/'.$enc_id, FALSE);
			}else{

				$msg="Invalid code";
				$this->redirect($msg, 'delivery/read-delivery-code/', FALSE);
			}

		} 
		$this->loadView($data);

	}


	public function get_delivery_ajax() {
		if ($this->input->is_ajax_request()) {
			$draw = $this->input->post('draw');
			$post_arr = $this->input->post();

			$post_arr['packages'] = true;
			$count_without_filter = $this->Delivery_model->getDeliveryCount();
			$count_with_filter = $this->Delivery_model->getDeliveryAjax($post_arr,1);
			$result_data = $this->Delivery_model->getDeliveryAjax($post_arr);
			$response = array(
				"draw" => intval($draw),
				"iTotalRecords" => $count_without_filter,
				"iTotalDisplayRecords" => $count_with_filter,
				"aaData" => $result_data,
			);

			echo json_encode($response);
		}
	}
	public function delete_category($enc_id)
	{
		$category_id = $this->Base_model->encrypt_decrypt( 'decrypt', $enc_id);	
		

		$details = $this->Delivery_model->removeCategory($category_id);
		if($details)
		{
			$msg="Category Removed Successfully";
			$this->redirect($msg,'delivery/list-category', true);
		}
		else
		{
			$msg="Error on Removing package";
			$this->redirect($msg,'delivery/list-category', false);
		}

	}
}
