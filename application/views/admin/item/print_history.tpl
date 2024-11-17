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
	tfoot {
		display: table-footer-group;
		vertical-align: middle;
		border-color: inherit;
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
								<address>
									<p class="mb-0"> {$site_details['name']}</p>
									<p class="mb-0"> {$site_details['address']} </p>
									<p class="mb-0">hello@pinetreelane.com</p>
									<strong><i>{$smarty.now|date_format}</i></strong>
								</address>
							</div>
						</div>
						<div class="col-sm-4">
							<img src="{assets_url()}/images/logo/print_logo.png" width="250px;">
						</div>
						<div class="col-sm-4">
							<div class="pull-right text-right" id="project" style="background-image:url('../../../assets/images/print-bg.png');background-repeat: no-repeat;
							background-position: right top; min-height: 139px;width: 100%;">  </div>
						</div>
					</div>
					<hr>



					<div class="row">

						<div class="col-sm-9">
							<div class="pull-left text-left" id="project">
								<h6>Voucher Details</h6>

								<div class="media">
									<p>Voucher Number: {$details.voucher_number}
										<br>
										Voucher Date :  {$details.voucher_date}
										<br>
										Allocated Qty: {$details.allocated_qty}
										<br> 
										Created On : {$details.date_added}
										<br> 
									</p>
								</div>
							</div>
						</div>

						<div class="col-sm-3 pull-right">
							<h6> Details</h6>
							<div class="media">
								<p>Requested By : {$details.requested_name}
									<br>
									Issued By : {log_user_name()}
									<br>
									Received By : {$details.receiver_name}
									<br> 

								</p>
							</div>
						</div> 
					</div>
					
					<div class="table-responsive invoice-table mt-4" id="table">	



						<table class="table mb-4" style="table-layout: fixed;">
							<thead class="bg-light">

								<tr>
									{* <th class="text-center" style="width: 60px;">Item Code</th> *}
									<th class="text-center" style="width: 100px;">Item Name</th>
									<th class="text-center" style="width: 100px;">Allocated Qty</th>
									<th class="text-center" style="width: 100px;">Issued Quantity</th>
									<th class="text-center" style="width: 100px;">Difference</th>
									<th class="text-center" style="width: 100px;">Added Date</th>

								</tr>
							</thead>
							<tbody>  


								{foreach $details['items'] as $v}
								<tr>
									{* <td class="text-center">{counter}</td> *}
									<td class="text-center">{$v.item_name}</td>
									<td class="text-center">{$v.allocated_qty}</td>	
									<td class="text-center">{$v.issued_qty}</td>		
									<td class="text-center">{$v.difference}</td>	
									<td class="text-center">{$v.date_added}</td>	

								</tr>

								{$sub_total_price = $sub_total_price+$item.vat_inclusive}
								{$total_price_above = $total_price_above+$item.total_price}


								{/foreach}


							</tbody>

						</table>


					</div> 
					<hr> 

				</div>

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