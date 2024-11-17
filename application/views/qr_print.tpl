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


 			<div id="printdiv" class="printdiv">
 				<style type="text/css">

 					@media print {
 						table tr td p,table tr td { 
 							font-size:12px;
 							padding: 0px;
 							margin: 0px;
 							margin-top:5px; 
 						}

 					}
 				</style>
 				<table border="0" style="width: 100%;">
 					<tr>
 						<td>				
 							<img src="{assets_url()}/images/logo/print_logo.png" width="130px" >
 							<p>{$details['customer_name']}</p>				
 							<p>{$details['project_name']}</p>				
 							<p>{$details['name']}</p>				
 							<p> {$details.date_created} </p>
 							<span class="clearfix"></span>
 							<p><small style="font-weight: bold"> {$details.code} </small></p>
 							<span class="clearfix"></span>
 							<img src="{base_url('assets/images/qr_code/package/')}{$details.code}.png" style="max-width: 100px">
 							<img src="{assets_url('images/package_pic/')}{$details['image']}"  style="max-width: 100px"> 
 						</td>

 						<td rowspan="1"  style="vertical-align: top;">
 							<table class="items"  style="width: 100%;" align="top">
 								<thead>
 									<tr>
 										<th align="left">Code</th>
 										<th align="left">Item</th>
 										<th align="right">Qty</th>
 									</tr>
 								</thead>
 								<tbody>
 									{foreach from=$details['items'] item=item}
 									<tr >
 										<td align="left">{$item.serial_no}</td>
 										<td align="left">{$item.name}</td>
 										<td align="right">{$item.qty}</td>
 									</tr>
 									{/foreach}
 								</tbody>
 							</table>
 						</td>		
 					</tr>
 					<tr>
 						<td colspan="2" align="center">
 							<p ><small > www.pinetreelane.com </small></p>
 						</td>
 					</tr>
 				</table> 
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