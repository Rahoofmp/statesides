{block name="header"}
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
<link rel="stylesheet" href="{assets_url()}bootv4/css/font-awesome.min.css">
<link href="{assets_url()}bootv4/css/backoffice.min.css" rel="stylesheet" />
<link href="{assets_url()}bootv4/css/style.css" rel="stylesheet" />
<link rel = "stylesheet" type = "text/css" media = "print" href = "mystyle.css">
{/block}
{block body} 
<style type="text/css">
/*
	@media screen {
		div.pageFooter {
			display: none;
		}
	}*/

	@media print {
		.noPrint{
			display:none;
		}
		.bg-secondary {
			background-color: #6c757d !important;
		} 
	} 


	#pageFooter {
		display: table-footer-group;
	}

	/*#pageFooter:after {
		counter-increment: page;
		content: counter(page);
	}*/
	@media screen {
		/*div.pageFooter {
			display: none;
		}*/
	}
	@media print {
		div.pageFooter:after {
			position: fixed;
			bottom: 0;
			counter-increment: page;
			content: counter(page);
			
		}
		.bg-secondary {
			background-color: #6c757d !important;
		}
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="invoice">
					<div class="row">
						<div class="col-sm-4">
							<div class="pull-left text-left" id="project">
							</div>
						</div>
						<div class="col-sm-4">
							<h4 class="text-center" style="margin-top: 20px;">
								<b>SHOWROOM MANAGEMENT APPLICATION</b></h4>
							</div>

						</div>
						<hr>
						<p>REFNO:</p>
						<hr>
						<div class="table-responsive invoice-table mt-4" id="table">
							<table class="table mb-4" style="table-layout: fixed;">
								<div class="row">

									{foreach $details as $v}
									
									<div class="col-sm-4">
										<div class="pull-left text-left" id="project">
											<h6>Customer Details</h6>

											<div class="media">
												<p>Name: {$v.customer_name}
													<br>
													Email :  {$v.customer_email}
													<br>
													Mob : {$v.customer_mobile}
													<br> 
													{$v.customer_address}
													<br> 
												</p>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<h6>Sales</h6>
										<div class="media">
											<p>{$v.salesman_name}
											</p>
										</div>
									</div> 
									<div class="col-sm-4">
										<h6>Designer</h6>
										<div class="media">
											<p>{$v.user_name_string}
											</p>
										</div>
									</div> 
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-9">
										<div class="pull-left text-left">
											<h6>ATTACHMENT</h6>

										</div>
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-sm-9">
										<div class="pull-left text-left" id="project">
											<h6>HAND SKETCH</h6>

										</div>
									</div>
									<div class="col-sm-3 pull-right">
										<h6>QR CODE SCAN</h6>

									</div> 
								</div>
								<hr>
								<thead class="bg-light">
									<tr>
										<th style="width: 60px;">Sl.No</th>
										<th>Sample Code</th>
										<th>Specification</th>
										<th>Product</th>
										<th>Price</th>
									</tr>
								</thead>
								<tbody>  
									{$sub_total_price=0} 
									{$total=0}
									{foreach from=$v['items'] key=category item=items}
									<tr>
										<td>{counter}</td>
										<td>{$items.code}</td>
										<td>{$items.note}</td>
										<td>{$items.name}</td>
										<td>{$items.lprice}
										</td>
									</tr>
									{$sub_total_price = $sub_total_price+$items.sprice}
									{/foreach}
								</tbody>
								<tfoot>
									<tr class="bg-light"> 
										<th class="text-center" colspan="4"><strong>Total Price for the above</strong></th>
										<th> {$sub_total_price|string_format:"%.2f"}</th>
									</tr>
								</tfoot>
							</table>					
							{/foreach}
							
						</div> 	
						<hr>
						<p>Additional Note</p>
						<div class="row text-center">

							<div class="col-sm-6">
								{* <hr>	 *}

								<p class="mb-0"></p>
								<p class="mb-0"><b></b></p>
							</div>
							<div class="col-sm-6">
								{* <hr>	 *}


							</div>

						</div> 		
						<hr>
						<div class="row">
							<div class="col-sm-4 text-left">	
								<p class="mb-0"> {$site_details['name']}</p>
								<p>Plot 598-1132</p>
							</div>
							<div class="col-sm-4 text-center">	
								<p class="mb-0">Dubai Investment Park 1</p>
								<p>Dubai, UAE</p>
								{* <span id="content">	
									<div id="pageFooter">Page </div>	
								</span>	 *}
								{* <div class="pageFooter"></div> *}

							</div>
							<div class="col-sm-4 text-right">	
								<p class="mb-0">T +971 481 00 433</p>
								<p class="mb-0">www.pinetreelane.com</p>
								<p>hello@pinetreelane.com</p>
							</div>
							<div class="col-sm-12 text-center">	
								<p class="font-weight-bold"> Auto generated quote does not require signature</p>
							</div>
						</div>
					</div>
					<input type="hidden" name="id" id="id" value="{$id}"> 
					<input type="hidden" name="enc_id" id="enc_id" value="{$enc_id}"> 
					<input type="hidden" name="tc_id" id="tc_id" value="{$v.terms_conditions}"> 
					<button class="btn btn-info col-sm-12 noPrint" onclick="printDiv()">Print</button>
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
	<link rel="stylesheet" href="css/style.css"/>

	<script type="text/javascript"> 
		function printDiv() {  

			window.print(); 
			window.close();

		} 
	</script>

	{/block}