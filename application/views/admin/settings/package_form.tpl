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
					<label for="name">Package Name</label>
					<input type="text" id="name" class="form-control" name="name" value="{$package[0]['name']}" autocomplete="off">
					{form_error('name')}
				</div>  
				<div class="form-group">
					<label for="value">Amount</label>
					<input type="text" id="value" class="form-control" name="value" value="{$package[0]['package_value']}">
					{form_error('value')}
				</div>  
				<div class="form-group">
					<label for="ceiling_value">Ceiling</label>
					<input type="text" id="ceiling_value" class="form-control" name="ceiling_value" value="{$package[0]['ceiling']}">
					{form_error('ceiling_value')}
				</div>  
				<div class="form-group">
					<label for="total_return">Total Return</label>
					<input type="text" id="total_return" class="form-control" name="total_return" value="{$package[0]['total_return']}">
					{form_error('total_return')}
				</div>  
				<div class="form-group">
					<input type="hidden" id="package_id" class="form-control" name="package_id" value="{$package[0]['pack_id']}">
				
				</div> 
				
				
			</div> 
			<div class="card-footer pull-right">
			
				<button type="submit" class="btn btn-fill btn-rose " name="update_package" value="update">Update Package</button>

			</div> 
			{form_close()}
		</div>
	</div>
</div> 
{/block}
