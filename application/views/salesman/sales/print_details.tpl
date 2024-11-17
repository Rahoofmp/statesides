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
						<div class="col-sm-12">
							<p><strong>Subject: {$details[0]['subject']}</strong></p> 
							<p> Dear Sir/Mam,</p>
							<p>Thanks you for your valid inquiry. We are pleased to quote our best price as follows: </p>
						</div>
					</div>
					<hr>

					<div class="row">

						{foreach $details as $v}
						<div class="col-sm-9">
							<div class="pull-left text-left" id="project">
								<h6>Customer Details</h6>

								<div class="media">
									<p>Name: {$v.customer_full_name}
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

						<div class="col-sm-3 pull-right">
							<h6>Sales Details</h6>
							<div class="media">
								<p>Quote No. : {$v.code}
									<br>
									Sales : {$v.salesman_names}
									<br>
									Status : {ucfirst($v.sales_status)}
									<br> 

								</p>
							</div>
						</div> 
					</div>

					<div class="table-responsive invoice-table mt-4" id="table">									
						{$total_above=0} 
						{$total_amt=0}
						{$count = 0}
						{foreach from=$v['items'] key=category item=categories}

						<div class="col-sm-12 text-center text-light bg-secondary pt-2 pb-2 mb-3 text-uppercase font-weight-bold"> {$category} 

						</div>
						<table class="table mb-4" style="table-layout: fixed;">
							<thead class="bg-light">

								<tr>
									<th class="text-center" style="width: 60px;">S.No</th>
									<th >Description</th>
									<th style="width: 70px;">Unit</th>
									<th class="text-center" style="width: 100px;">Quantity</th>
									<th class="text-center" style="width: 100px;">Unit Rate</th>
									<th class="text-center" style="width: 100px;">VAT</th>
									<th class="text-center" style="width: 100px;">Total</th>

								</tr>
							</thead>
							<tbody>  
								{$sub_total_price=0} 
								{$total_price_above=0} 
								{$total=0}

								{foreach from=$categories item=item}
								<tr>
									<td class="text-center">{counter}</td>
									<td >
										{$item.name}<br>
										{if $item.spec}
										<small>

											<b> </b>{$item.spec}
										</small>
										{/if}
									</td>
									<td style="width: 70px;">{$item.unit}</td>
									<td class="text-center">{$item.quantity}</td>		
									{* <td class="text-center">{$item.sprice|string_format:"%.2f"}</td> *}
									<td class="text-center">{number_format($item.sprice,2)}</td>	
									<td class="text-center">{number_format($item.vat_perc_amount,2)}</td>	
									<td class="text-center">{number_format($item.vat_inclusive,2)}</td>
								</tr>

								{$sub_total_price = $sub_total_price+$item.vat_inclusive}
								{$total_price_above = $total_price_above+$item.total_price}


								{/foreach}
								{$total_amt = $total_amt + $sub_total_price}
								{$total_above = $total_above + $total_price_above}

							</tbody>
							{* {if ( $count == count( $v['items'] ) - 1)}
							<tfoot>
								<tr> 
									<th colspan="3"></th>
									<th colspan="2"><strong>Total Price for the above</strong></th>
									<th class="text-right" colspan="2">{number_format($total_above,2)}</th>

								</tr>
							</tfoot>
							{/if} *}
						</table>
						{$count = $count + 1}
						{/foreach}

						<table class="table">

							<tbody> 
								<tr>  
									<td class="float-right">
										<table >

											<tbody> 
												<tr> 
													<th></th>
													<th></th>
													<th></th>
													<th colspan="5"></th>
													<th colspan="5"><strong>Total Price for the above</strong></th>
													<th class="text-right" >{number_format($total_above,2)}</th>

												</tr>
												{if $v.discount_by_amount}
												{* <tr >
													<th></th>
													<th></th>
													<th></th>
													<th colspan="5"></th>
													<th colspan="5"></th>
													<th>Discount(%)</th>
													<th class="text-right">{$v.discount_by_percentage}%</th>
												</tr> *}
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th colspan="5"></th>
													<th colspan="5">Discount</th>
													<th class="text-right">{cur_format($v.discount_by_amount)}</th>
												</tr>
												{/if}
												<tr>
													<th></th>
													<th></th>
													<th></th>
													<th colspan="5"  ></th>
													<th  colspan="5" style="width: 212px;">Taxable Amount</th>
													{$taxable_amount=$total_above-$v.discount_by_amount}
													<th class="text-right" colspan="2">{cur_format($taxable_amount)}</th>
												</tr>

												<tr >
													<th></th>
													<th></th>
													<th></th>
													<th colspan="5"></th>
													<th colspan="5">VAT</th>
													{$vat=$taxable_amount * 5/100}
													<th class="text-right">{cur_format($vat)}</th>
												</tr>
												
												
												<tr >
													<th></th>
													<th></th>
													<th></th>
													<th colspan="5"></th>
													<th  colspan="5">Total Amount</th>
													<th class="text-right" >{cur_format($vat+$taxable_amount)}</th>
												</tr>

											</tbody>
										</table>
									</td>
								</tr> 
								<tr> 
									<td>
										{if $v.payment_terms_id}
										<h4>Payment Terms:</h4>
										<p>{$v.payment_terms_conditions}</p>
										{/if}
										{if $v.terms_conditions}
										<h4>Normal Terms:</h4>
										<p>{$v.normal_terms_conditions}</p>
										{/if}								
									</td> 
								</tr> 
							</tbody>
						</table>
						<div>
						</div>
						{/foreach}
					</div> 
					<hr> 
					<p>Best Regards</p>
					<div class="row text-center">

						<div class="col-sm-6">
							{* <hr>	 *}

							<p class="mb-0">Sales by:</p>
							<p class="mb-0"><b> {$details[0]['salesman_names']} </b></p>
							<p class="mb-0"><b> {$details[0]['email']} </b></p>
							<p class="mb-0"><b> {$details[0]['salesman_mobile']} </b></p>
						</div>
						<div class="col-sm-6">
							{* <hr>	 *}

							<p class="mb-0">Client</p> 
							<p><b>{$v.customer_full_name}</b></p>
							<p><b>{$v.customer_email} </b></p>

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