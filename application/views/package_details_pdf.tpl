<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />   
	<title> {$title} | {$site_details['name']}</title>

	{block name=header}
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link rel="stylesheet" href="{assets_url()}bootv4/css/font-awesome.min.css">
	<link href="{assets_url()}bootv4/css/backoffice.min.css" rel="stylesheet" />
	<link href="{assets_url()}bootv4/css/style.css" rel="stylesheet" />

	{/block}
</head>

{block body}
<body class="">
	<div class="content">
		<div class="container-fluid">
			
			<div class="row">  
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


												<img class="media-object img-60 pt-2" src="{base_url('assets/images/package_pic/')}{$details['image']}" alt="Package Image" width="100" >
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
		</div>
	</div>
</body>
{/block}

{block footer} 
<script src="{assets_url()}bootv4/js/core/jquery.min.js"></script>
<script src="{assets_url()}bootv4/js/core/popper.min.js"></script>
<script src="{assets_url()}bootv4/js/core/bootstrap-material-design.min.js"></script>
{/block}
</html>