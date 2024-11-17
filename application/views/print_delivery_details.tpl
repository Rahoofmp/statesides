			<!DOCTYPE html>
			<html lang="en">
			<head>
			<meta charset="utf-8" />

			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
			<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />   
			<title> {$title} | {$site_details['name']}</title>

			{block name="header"}
			<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
			<link rel="stylesheet" href="{assets_url()}bootv4/css/font-awesome.min.css">
			<link href="{assets_url()}bootv4/css/backoffice.min.css" rel="stylesheet" />
			<link href="{assets_url()}bootv4/css/style.css" rel="stylesheet" />


			{/block}
			</head> 


			{block name="body"}
			<body class="">
			<div class="content">
			<div class="container-fluid">


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
			<div class="col-sm-6 ">
			<div class="pull-right text-right" id="project">
			<strong><i>{$smarty.now|date_format:"%Y-%m-%d %H:%I"}</i></strong>

			<br> 
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

			{form_close()}
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