{extends file="layout/base.tpl"}

{block body}

<div class="row"> 
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-content collapse show">
				<div class="card-body"> 

					{form_open('', '')}
					{foreach $residual_details as $v} 
					<div class="form-group">
						<label for="value">Level {$v.residual_id} (flat)</label>
						<input type="text" class="form-control" id="level{$v.residual_id}" name="level{$v.residual_id}" value="{$v.bonus}" >
					</div>
					{/foreach}
					
					<div class="card-footer pull-right">
						<button type="submit" class="btn btn-fill btn-rose " name="update_residual" value="update">Update Level Bonus</button>
					</div>
					{form_close()}

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