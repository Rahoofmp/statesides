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



 			<div id="printdiv" class="printdiv" >
 				<table border="0" style="width: 100%;">
 					<tr>
 						<td>				
 							<p>{$details['project_name']}</p>

 							
 							{foreach from=$details['packages'] item=pack}
 							<p><small>{$pack.package_code} - {$pack.package_name} </small></p>
 							{/foreach}
 							<small style="font-weight: bold"> {$details.code} </small>
 							<p > {$details.date_created} </p>

 						</td>
 						<td align="right">
 							<table border='0' style="width: 100%;">
 								<tr>
 									<td align="right"> <img src="{base_url('assets/images/qr_code/delivery/')}{$details.code}.png" style="max-width: 100px">
 									</td>
 								</tr>

 								<tr>
 									<td align="right"> <img src="{base_url('assets/images/logo/')}{$site_details['logo']}"  style="max-width: 100px">

 									</td>
 								</tr>
 							</table>


 						</td>
 					</tr>
 				</table> 
 			</div>
 		</body>
 		{/block}

 		{block footer} 
 		<script src="{assets_url()}bootv4/js/core/jquery.min.js"></script>
 		<script src="{assets_url()}bootv4/js/core/popper.min.js"></script>
 		<script src="{assets_url()}bootv4/js/core/bootstrap-material-design.min.js"></script>
 		{/block}
 		</html>