 
<div id="printdiv" class="printdiv" style="display: none">
	<table border="0" style="width: 100%;">
		<tr>
			<td>				
				<p>{$v.project_name}</p>
				{* {foreach from=$v.packages item=pack}
				<p><small>{$pack.package_code} - {$pack.package_name} </small></p>
				{/foreach} *}
				<p style="font-weight: bold"> {$v.code} </p>
				<p > {$v.date_created} </p>

			</td>
			<td align="right">
				<table border='0' style="width: 100%;">

					<tr>
						<td align="right"> <img src="{base_url('assets/images/logo/')}{$site_details['logo']}"  style="max-width: 100px">

						</td>
					</tr>
					<tr>
						<td align="right"> <img src="{base_url('assets/images/qr_code/delivery/')}{$v.code}.png" style="max-width: 100px">
						</td>
					</tr>

				</table>


			</td>
		</tr>
	</table> 
</div>
