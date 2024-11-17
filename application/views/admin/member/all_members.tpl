{extends file="layout/base.tpl"}

{block header} 
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />

{/block}


{block body}
<style type="text/css">
	.my-group .form-control{
		width:50%;
	} 
</style>
<div class="row">
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-content collapse show">
				<div class="card-body">
					{form_open('','class="form" ')}
					<div class="form-body">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<select type="text" id="user_name" class="form-control user_ajax" name="user_name"></select>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="first_name">{lang('first_name')}</label>
									<input type="text" id="first_name" class="form-control" name="first_name" autocomplete="off" value="{$search_arr['first_name']}">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="email">{lang('email')}</label>
									<input type="text" id="email" class="form-control" name="email" autocomplete="off" value="{$search_arr['email']}">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<select class="selectpicker" data-size="7" data-style="select-with-transition" title="User type" id="user_type" name="user_type">
										<option value="admin" {set_select( 'user_type', 'admin')}>Admin</option>
										
										<option value="supervisor" {set_select( 'user_type', 'supervisor')}>Supervisor</option>
										
										<option value="salesman" {set_select( 'user_type', 'salesman')}>Salesman</option>
										
									</select> 
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="mobile">{lang('mobile')}</label>
									<input type="text" id="email" class="form-control" name="mobile" autocomplete="off" value="{$search_arr['mobile']}">
								</div>
							</div>
						</div>
						<div class="row mt-2"> 
							<div class="col-sm-6"> 
								<button type="submit" class="btn btn-primary col-sm-6" name="submit" value="filter">
									<i class="fa fa-filter"></i> Filter
								</button>
							</div>
							<div class="col-sm-6"> 
								<button type="submit" class="btn btn-warning col-sm-6  pull-right" name="submit" value="reset">
									<i class="fa fa-refresh"></i> Reset
								</button>  
							</div>
						</div>
					</div>
					{form_close()}
				</div>
			</div>
		</div>
	</div>
	{if $details}
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">{lang('details')}</h4>
			</div> 
			<div class="card-body">
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-blue-grey bg-lighten-4">
							<tr>
								<th>#</th> 
								<th>{lang('username')}</th>
								<th>{lang('firstname')}</th>
								<th>{lang('email')}</th>
								<th>{lang('mobile')}</th>

								<th>{lang('registerd_on')}</th>  
								<th>User type</th>  
								<th class="text-center">{lang('action')}</th>   
							</tr>
						</thead>
						<tbody> 
							{foreach from=$details item=v}

							<tr>
								<td>{counter}</td>
								<td>{$v.user_name}</td>  
								<td>{$v.first_name}</td>  
								<td>{$v.email}</td>  
								<td>{$v.mobile}</td>  

								<td>{$v.joining_date|date_format:"%d-%m-%Y"}</td>
								<td>
									{if $v.user_type == 'packager'} <span class="badge badge-info">Packager</span> 
									{elseif $v.user_type == 'driver'} <span class="badge badge-primary">Driver</span> 
									{elseif $v.user_type == 'supervisor'} <span class="badge badge-danger">Supervisor</span>
									{elseif $v.user_type == 'store_keeper'} <span class="badge badge-success">Store Keeper</span>
									{elseif $v.user_type == 'admin'} <span class="badge badge-warning">Admin</span>
									{elseif $v.user_type == 'dept_supervisor'} <span class="badge badge-secondary">Dep.Supervisor</span>
									{elseif $v.user_type == 'salesman'} <span class="badge badge-secondary">Salesman</span>
									{elseif $v.user_type == 'designer'} <span class="badge badge-secondary">Designer</span>
									{elseif $v.user_type == 'purchaser'} <span class="badge badge-secondary">Purchaser</span>
									{elseif $v.user_type == 'workers'} <span class="badge badge-secondary">Workers</span>
									{/if}

								</td>        
								<td class="td-actions text-center"> 

									<a href="{base_url('admin/member/profile?user_name=')}{$v.user_name}" rel="tooltip" class="btn btn-info btn-link" target="_blank" title="Profile {$v.user_name}">
										<i class="material-icons">person</i>
									</a> 

								</td>  
							</tr>
							{/foreach}  
						</tbody>
					</table>
				</div>
			</div> 
		</div>
		<div class="d-flex justify-content-center">  
			<ul class="pagination start-links"></ul> 
		</div>
	</div>
	{/if}
</div> 

{/block}

{block footer}
<script src="{assets_url('vendors/js/pagination/jquery.twbsPagination.min.js')}"></script>  
<script src="{assets_url('js/scripts/pagination/pagination.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>


<script type="text/javascript">

	$(document).ready(function(){ 

		$('.user_ajax').select2({

			placeholder: 'Select a user',
			ajax: {
				url:'{base_url()}admin/packages/user_ajax',

				type: 'post',
				dataType: 'json',
				delay:250,
				processResults: function(data) {
					return {
						results: data
					};
				}
			},

		});

	});  
</script>
{/block}