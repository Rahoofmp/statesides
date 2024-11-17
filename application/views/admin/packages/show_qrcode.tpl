 
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
				<p>{$v.customer_name}</p>
				<p>{$v.project_name}</p>
				<p>{$v.name}</p>
				<p>{$v.date_created}</p>
				<p>{$v.location}</p>
				<span class="clearfix"></span>
				<p><small style="font-weight: bold"> {$v.code} </small></p>
				<span class="clearfix"></span>
				<img src="{base_url('assets/images/qr_code/package/')}{$v.code}.png" style="max-width: 100px">
				<img src="{base_url('assets/images/package_pic/')}{$v.image}"  style="max-width: 100px">

			</td>

			<td rowspan="1"  style="vertical-align: top;">
				<table class="items"  style="width: 100%;" align="top" >
					<thead>
						<tr align="top">
							<th align="left">Code</th>
							<th align="left">Item</th>
							<th align="right">Qty</th>
						</tr>
					</thead>
					<tbody >
						{foreach from=$v['items'] item=item}
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
