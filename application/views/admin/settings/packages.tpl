{extends file="layout/base.tpl"}

{block body}

<div class="row"> 
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-content collapse show">
				<div class="card-body"> 

					<div class="table-responsive">
						<div class="table-responsive">
							<table class="table">
								<thead>
									<tr>
										<th class="text-center">#</th>
										<th>Package Name</th>
										<th>Amount</th>
										<th>Ceiling</th>
										<th>Total Return</th>

										
									</tr>
								</thead>
								<tbody> 
									{foreach from=$package_details item=pack}
									<tr>
										<td class="text-center">{counter}</td>
										<td>{$pack.name}</td>
										<td>{cur_format($pack.package_value)}</td>
										<td>{$pack.ceiling}</td>
										<td>{cur_format($pack.total_return)}</td>
										
										<td class="td-actions text-right"> 
											<a href="{base_url(log_user_type())}/settings/package-form/{$pack.enc_id}" class="btn btn-info btn-round">
												<i class="material-icons">edit</i>
											</a>
										</td>
									</tr>  
									{/foreach}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="d-flex justify-content-center">  
				<ul class="pagination start-links"></ul> 
			</div>
		</div>
	</div>  
</div>

{/block}

{block footer} 
{/block}