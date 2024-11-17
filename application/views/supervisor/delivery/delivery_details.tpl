{extends file="layout/base.tpl"}


{block body} 

<div class="row no-print"> 

	<div class="col-sm-12 text-center hidden-print"> 
		<div class="card">  
			<div class="card-body"> 
				<a class="btn btn-github" href="{base_url(log_user_type())}/delivery/delivery-list">
					<span class="btn-label">
						<i class="material-icons">keyboard_return</i>
					</span>
					Back To List
				</a>

				<button class="btn btn-success" onClick="printQrCode(this)">
					<span class="btn-label">
						<i class="material-icons">grid_view</i>
					</span>
					Print Code
				</button>
				{include file="{log_user_type()}/delivery/print_qrcode.tpl"}


				<!-- <button class="btn btn-info" onclick="javascript:window.print();">
					<span class="btn-label">
						<i class="material-icons">print</i>
					</span>
					Print
				</button> -->
					<a href="{base_url()}{log_user_type()}/delivery/print_delivery_details/{$enc_id}" class="btn btn-info" >
					<span class="btn-label">
						<i class="material-icons">print</i>
					</span>
					Print
				</a>  
			</div>

		</div> 
	</div> 
</div>  
<div class="row">
	<div class="col-sm-12">
			{if form_error('delivery_id')}<div class="alert alert-warning">{form_error('delivery_id', '<p>')}</div>{/if}
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
						<div class="col-sm-6 ">
							<div class="pull-right text-right" id="project">
								<h6>Customer Details</h6>
								<div class="media">
									<p>Name: {$details['customer_name']}
										<br>

										Email :  {$details['email']}
										<br>
										Mobile : {$details['mobile']}
										<br> 


									</p>
									<div class="media-right">
										<img src="{base_url('assets/images/qr_code/project/')}{$details['project_id']}.png" style="max-width: 100px">
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
									<textarea name="location_details" id="location_details">{$details['location_details']}</textarea> 
									<input type="hidden" name="delivery_id" id="delivery_id" value="{$delivery_id}">
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
								{$item_count = 1}
								{$total_qty =0}
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
										Code: {$v.package_code}
										<span class="clearfix"></span>
										<small>
											Name: <span> {$v.package_name}</span>
										</small>
										<span class="clearfix"></span>
										<small>
											Status: <span> {ucfirst($v.status)|replace:'_':' '}</span>
										</small>
									</td> 

									{foreach from=$details['packages'][$key]['items'] item=v }

									{if $row_count > 0 && $row_count != $package_count}
									<tr>
										{/if}

										<td>{$v.serial_no}</td> 
										<td> {$v.name} </td> 
										<td> {$v.qty} </td> 

										{$row_count = $row_count -1}
										{if $row_count == 0}
									</tr>
									{/if}

									{$total_qty = $total_qty + $v.qty}
									{/foreach} 


									{$item_count= $item_count+1}
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
						<div class="no-print mt-4">
							<div class="row">  
								<div class="col-md-6" style="max-width: 500px;">
									<div class="form-group">
										<select class="selectpicker" data-size="7" data-style="select-with-transition" title="Select Status" name="status" id="status" >
											<option value='delivered' {set_select('status', 'delivered')}>Delivered</option>
											<option value='partially_delivered' {set_select('status', 'partially_delivered')}>Partially delivered</option>
										</select>
										{form_error('status')}
									</div>
								</div>


								<div class="col-md-4"> 
									<button type="submit" class="btn btn-primary" name="update_status" value="update">
										<span class="material-icons">done_all</span> {lang('button_update')}
									</button> 
								</div>
							</div>
						</div>
						{form_close()}
					</div>
				</div>
			</div>
		</div>
	</div> 

	{/block}

	{block footer}    
	<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 


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
					$("#location_details").on('blur', (function() {

				var loc=$(this).val();
				var id=$("#delivery_id").val();
				$.ajax({
					type:'POST',
					url:"{base_url('supervisor/delivery/save-location-details')}",
					data: { id: id,  location_details: loc,  } ,
					dataType:'json',
				})
				.done(function( response ) { 

					url  = "{base_url('supervisor/delivery/delivery-details')}";
					if(response.success){
						alert('Location Details Updated');
					}else{
						alert("Updation failed");
						
					}
				}); 
			}));
	</script>
	{/block}