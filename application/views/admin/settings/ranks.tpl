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
										<th>{lang('name')}</th>
										<th>{lang('status')}</th>
										<th>Total bonus</th> 
										<th>Reward($)</th> 
										<th class="text-right">Actions</th>
									</tr>
								</thead>
								<tbody> 
									{foreach from=$ranks item=rank}
									<tr>
										<td class="text-center">{counter}</td>
										<td>{$rank.name}</td>
										<td>
											{if $rank.status == '1' }
											{lang('active')}
											{else}
											{lang('inactive')}
											{/if}
										</td>
										<td>{$rank.total_bonus}</td> 
										<td>{$rank.reward}</td> 
										<td class="td-actions text-right"> 
											<a href="{base_url(log_user_type())}/settings/rank-form/{$rank.enc_id}" class="btn btn-info btn-round">
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