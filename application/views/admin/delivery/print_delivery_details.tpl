{block name="header"}
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
<link rel="stylesheet" href="{assets_url()}bootv4/css/font-awesome.min.css">
<link href="{assets_url()}bootv4/css/backoffice.min.css" rel="stylesheet" />
<link href="{assets_url()}bootv4/css/style.css" rel="stylesheet" />
{/block}
{block body} 
<style type="text/css">


@media print {
	background-color: white;
	height: 100%;
	width: 100%;
	position: fixed;
	top: 0;
	left: 0;
	margin: 0;
	padding: 15px;
	font-size: 14px;
	line-height: 18px;
}


</style>

<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="invoice">


					<div class="row">
						<div class="col-sm-6">
							<div class="media">
								<div class="media-left">
									<img class="media-object img-60 " src="{assets_url()}/images/logo/{$site_details['logo']}" >
									<h5 class="media-heading mt-2 mb-2">{$site_details['name']}</h5>
									<p>hello@pinetreelane.com<br><span>{$site_details['phone']}</span></p>
								</div> 
							</div>
							<!-- End Info-->
						</div>
						<div class="col-sm-6">
							<div class="pull-right text-right" id="project">
								<strong><i>{$smarty.now|date_format:"%Y-%m-%d %H:%I"}</i></strong>

								<br> 
								<h6>Customer Details</h6>
								<div class="media">
									<p>Name: {$details['cus_name']}
										<br>

										Email :  {$details['customer_email']}
										<br>
										Mobile : {$details['customer_mobile']}
										<br> 
									</p>
									<div class="media-right">
										<img src="{base_url('assets/images/qr_code/project/')}{$details['project_id']}.png" style="max-width: 75px">
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">


						<div class="col-sm-8">
							<div class="pull-left text-left" id="project">
								<h6>Project Details</h6>

								<small>Code: {$details['project_code']}</small>  
								<span class="clearfix"></span>
								<small>Name: {$details['project_name']} </small>  
								<span class="clearfix"></span>
								<small> Status:  {ucfirst($details['project_package_status'])|replace:'_':' '}</small>
								<span class="clearfix"></span>
								<br>
								<h6>Location Details</h6>									
								<small>{$details['location_details']}</small>
							</div>
						</div>

						<div class="col-sm-4">
							<h6>Delivery  Details</h6>
							<div class="media">

								

								<div class="media-body m-l-20">
									<small>Delivery Code: {$details['code']} </small>  
									<span class="clearfix"></span>
									<small>Delivery Person: {$details['driver_name']} </small> 
									<span class="clearfix"></span>
									<small>Vehicle: {$details['vehicle']}</small> 
									<span class="clearfix"></span> 
									<small> Status: {ucfirst($details['status'])|replace:'_':' '}</small>

									<span class="clearfix"></span> 
									<small> Driver Contact Number: {$details['driver_contact']}</small>
									<span class="clearfix"></span> 
									<small> OPS Number:  +971569007690</small>
								</div>
								<div class="media-left">
									<img src="{base_url('assets/images/qr_code/delivery/')}{$details['code']}.png" style="max-width: 100px">
								</div>
							</div>
						</div> 

					</div>
					{form_open('','')}

					<div class="table-responsive invoice-table mt-4" id="table">	
						<table class="table">
							<thead class="bg-light">

								<tr>
									<th > Sl. No: </th>
									<th > Package </th>
									<th > Code </th>
									<th > Items  </th>
									<th > Quantity  </th>
								</tr>
							</thead>
							<tbody>  
								{$package_count=0}
								{* {$item_count = 1} *}
								{* {$total_qty =0} *}
								{$row_count =0}
								{foreach from=$details['packages'] item=v key=key  }

								<tr>
									{$row_count = count($details['packages'][$key]['items'])}
									{$package_count = $row_count}

									<td {if $row_count > 0}rowspan="{$row_count}"{/if}>
										{if $v.status == 'send_to_delivery'}
										<div class="form-check">
											<label class="form-check-label">
												<input class="form-check-input" type="checkbox" checked value="1" name="delivery_package[{$v.id}]">
												{counter}
												<span class="form-check-sign">
													<span class="check"></span>
												</span>
											</label>
										</div>
										{else}
										{counter}
										{/if}
									</td> 

									<td {if $row_count > 0}rowspan="{$row_count}"{/if}>
										P-Code: {$v.package_code}
										<span class="clearfix"></span>
										<small>
											Name: <span> {$v.package_name}</span>
										</small>
										<span class="clearfix"></span>
										<small>
											Status: <span> {ucfirst($v.status)|replace:'_':' '}</span>
										</small>
									</td> 

									{foreach from=$details['packages'][$key]['items'] item=pi }

									{if $row_count >= 0 && $row_count != $package_count}
									<tr>
										{/if}

										<td>{$pi.serial_no}</td> 
										<td> {$pi.name} </td> 
										<td> {$pi.qty} </td> 

										{$row_count = $row_count -1}
										{if $row_count == 0}
									</tr>
									{/if}


									{* {$total_qty = $total_qty + $pi.qty} *}
									{/foreach} 

									{* {$item_count= $item_count+1} *}
									{$package_count= $package_count+1}


									{/foreach}
									<tfoot>  
										<tr class="border">
											{* <th class="text-center" colspan="3">Total</th>
											<th>{count($details['packages'])}</th> 	
											<th> {$total_qty}</th> *}
										</tr>
									</tfoot>  
								</tbody>
							</table>
						</div> 

						{form_close()}
					</div>
				</div>
			</div>
		</div>
	</div> 

	{/block}

	{block footer}    
	<script src="{assets_url()}bootv4/js/core/jquery.min.js"></script>
	<script src="{assets_url()}bootv4/js/core/popper.min.js"></script>
	<script src="{assets_url()}bootv4/js/core/bootstrap-material-design.min.js"></script>
	<script src="{assets_url()}bootv4/js/plugins/perfect-scrollbar.jquery.min.js"></script> 

	<script src="{assets_url()}bootv4/js/plugins/jquery.validate.min.js"></script>

	<script src="{assets_url()}bootv4/js/core/material-dashboard.min.js?v=2.1.0" type="text/javascript"></script>
	<script type="text/javascript" src="{base_url()}jsloader/lang_common.js" ></script>

	<script>	
		function printQrCode(ele) {
			var windowUrl = 'about:blank'
			var uniqueName = new Date();
			var windowName = 'Print' + uniqueName.getTime();

			var myPrintContent = $(ele).parent().find('.printdiv')[0];
			var myPrintWindow = window.open(windowUrl, windowName, 'left=300,top=100,width=400,height=400');
			myPrintWindow.document.write(myPrintContent.innerHTML);
			myPrintWindow.document.close();
			myPrintWindow.focus();
			myPrintWindow.print();
			myPrintWindow.close();    
			return false;
		}
	</script>
	<script type="text/javascript">
		window.onload = function() { 
			window.print(); 
			window.close();
			// window.onafterprint = back;	
		}
		function back() {
			window.history.back();
		}
	</script>
	{/block}