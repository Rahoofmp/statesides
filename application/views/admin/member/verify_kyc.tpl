{extends file="layout/base.tpl"}

{block body}
<div class="row">
	<div class="col">
		<div class="card"> 
			<div class="card-header pb-0">
				<h4 class="card-title">Verify KYC</h4>
				<hr>
			</div>
			<div class="table-responsive"> 

				<table class="table table-hover" id="sample-table-1">
					<tr>
						<th>#</th>
						<th>User Name</th>
						<th>ID PROOF</th>

						<th>Status</th>
						<th>Reason Comments</th>
						<th>Action</th>
					</tr>

					{foreach $details as $v}
					{form_open_multipart("",'class="form form-horizontal"' )} 
					<tr>
						<td class="center">{counter}</td>
						<td class="center">{$v.user_name}</td>
						<td class="hidden-xs">  
							<a href="{assets_url()}images/kyc/{$v.kyc}" target="_blank" title="View Image"><i class="fa fa-eye"></i></a> | 
							<a href="{assets_url()}images/kyc/{$v.kyc}" download="" title="Download"><i class="fa fa-download"></i></a>
						</td> 

						<td class="hidden-xs"> {ucFirst($v.status)}<input type="hidden" name="type" id="type" value="{$v.status}">
						</td>
						<td>
							<textarea class="textarea" name="reason" id="reason" required></textarea>
						</td>

						<input type="hidden" name="kyc_id" id="kyc_id" value="{$v.id}">

						<td >  

							<button class="btn btn-success pull-left" type="submit" id="verify_kyc" name="verify_kyc" value="{$v.user_id}">
								{lang('verify')} <i class="fa fa-arrow-circle-left"></i>
							</button> 
							<button class="btn btn-danger pull-right" type="submit" id="un_verify_kyc" name="un_verify_kyc" value="{$v.user_id}">
								{lang('reject')}<i class="fa fa-arrow-circle-right"></i>
							</button>


						</td>

					</tr>

					{form_close()}
					{/foreach} 
				</table>
			</div>
		</div>  
	</div> 
	{/block}





