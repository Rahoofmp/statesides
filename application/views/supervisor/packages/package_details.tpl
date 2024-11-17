{extends file="layout/base.tpl"}
 

{block body}

<div class="row">  
	<div class="col-sm-12 hidden-print text-center no-print"> 
		<div class="card">  
			<div class="card-body"> 
				<a class="btn btn-github" href="{base_url(log_user_type())}/packages/read-package-code">
					<span class="btn-label">
						<i class="material-icons">keyboard_return</i>
					</span>
					Read QR code
				</a>

				<button class="btn btn-success" onClick="printQrCode(this)">
					<span class="btn-label">
						<i class="material-icons">grid_view</i>
					</span>
					Print Code
				</button>
				{include file="{log_user_type()}/packages/print_qrcode.tpl"}


				<button class="btn btn-info" onclick="javascript:window.print();">
					<span class="btn-label">
						<i class="material-icons">print</i>
					</span>
					Print
				</button> 
			</div>

		</div> 
	</div>  
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="invoice">
					<div class="row">
						<div class="col-sm-8">
							<div class="media">
								<div class="media-left">
									<img class="media-object img-60 " src="{assets_url()}/images/logo/{$site_details['logo']}" >
									<span class="clearfix"></span>
									
									<img class="media-object img-60 pt-2" src="{base_url('assets/images/package_pic/')}{$details.image}" alt="Package Image" width="100" >
								</div>
								<div class="media-body ml-4">
									<h5 class="media-heading mt-2 mb-2">{$site_details['name']}</h5>
									<p>{$site_details['email']}<br><span>{$site_details['phone']}</span></p>
								</div>
							</div> 
						</div>
						<div class="col-sm-4">
							<div class="pull-right text-right" id="project">
								<h6>Customer Details</h6>
								<p>Name: {$details['customer_name']}
									<br>

									Email :  {$details['email']}
									<br>
									Mobile : {$details['mobile']}
									<br> 
								</p>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-7">
							<div class="row">
								<div class="col-3">

									<img src="{base_url('assets/images/qr_code/package/')}{$details.code}.png" style="max-width: 100px" class=" mt-2">

								</div>
								<div class="col-9">
									<h6>Package  Details</h6>
									<div class="media">

										<div class="media-body m-l-20">

											<small>Code: {$details['code']} </small>  
											<span class="clearfix"></span>
											<small>Name: {$details['name']} </small> 
											<span class="clearfix"></span>
											<small>Created: {$details['date_created']}</small>  
											<span class="clearfix"></span>
											<small>Status: {$details['status']}</small>  
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="col-sm-5">
							<div class="pull-right text-right" id="project">
								<h6>Project Details</h6>
								<small>Code: {$details['code']}</small>  
								<span class="clearfix"></span>
								<small>Name: {$details['project_name']} </small>  
								<span class="clearfix"></span>
								<small> Status: {$details['project_status']} </small>
								<span class="clearfix"></span>
							</div>
						</div>
					</div>
					<div class="table-responsive invoice-table mt-4" id="table">	
						<table class="table">
							<thead class="bg-light">

								<tr>
									<th > Sl. No: </th> 
									<th > Code. </th>
									<th > Items  </th>
									<th > Quantity  </th> 
								</tr>
							</thead>
							<tbody>  
								{$total_qty=0}
								{foreach from=$details['items'] item=v key=key name=name}


								<tr>
									<td >
										{counter} 

									</td>  
									<td>
										{$v.serial_no}
									</td> 
									<td>
										{$v.name}
									</td> 
									<td>
										{$v.qty}
									</td> 

								</tr>
								{$total_qty=$total_qty+$v.qty}
								{/foreach}			
								<tr class="border">
									<th colspan="2" class="text-center">
									Total</th>
									<th > {{counter}-1} </th>
									<th>{$total_qty}</th>

								</tr>
							</tbody>
						</table>
					</div> 
				</div>
			</div>
		</div>
	</div>
</div>

{/block}

{block footer}  
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
{/block}