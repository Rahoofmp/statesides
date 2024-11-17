 
<div id="printdiv" class="printdiv" style="display: none">
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
				<p> {$details.package_location} </p>
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
