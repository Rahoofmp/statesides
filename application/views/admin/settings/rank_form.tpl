{extends file="layout/base.tpl"}

{block body}
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">{$title}</h4>
			</div> 
			{form_open('', '')} 
			<div class="card-body">
				<div class="form-group">
					<label for="name">{lang('name')}</label>
					<input type="text" id="name" class="form-control" name="name" value="{$rank['name']}">
					{form_error('name')}
				</div>  
				<div class="form-group">
					<label for="total_bonus">Total Bonus</label>
					<input type="text" id="total_bonus" class="form-control" name="total_bonus" value="{$rank['total_bonus']}">
					{form_error('total_bonus')}
				</div>  

				<div class="form-group">
					<label for="reward">Reward</label>
					<input type="text" id="reward" class="form-control" name="reward" value="{$rank['reward']}">
					{form_error('reward')}
				</div>  
				
				<div class="form-group">
					<label for="order">{lang('order')}</label>
					<input type="text" id="sort_order" class="form-control" name="sort_order" value="{$rank['sort_order']}">
					{form_error('sort_order')}
				</div>   
				<div class="form-group"> 
					{form_dropdown('status', $status_arr, $rank['status'], 'class="form-control"')}
					{form_error('status')}
				</div>  
			</div> 
			<div class="card-footer pull-right">
				{if $rank_enc_id}
				<button type="submit" class="btn btn-fill btn-rose " name="rank" value="update">{lang('button_update')}</button>
				{else}
				<button type="submit" class="btn btn-fill btn-rose " name="rank" value="insert">{lang('button_submit')}</button>

				{/if}
				
			</div>
			{form_close()}
		</div>
	</div>
</div> 
{/block}
