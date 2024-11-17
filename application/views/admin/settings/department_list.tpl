{extends file="layout/base.tpl"}
{block header}
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
{/block}

{block body}

<div class="row"> 
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-content collapse show">
				<div class="card-body">
					{form_open('','class="form" ')}
					<div class="form-body">
						<div class="row">

							<div class="col-sm-4">
								<div class="form-group">
									<select type="text" id="department_id" class="form-control deptname_ajax" name="department_id">
										{if $post_arr['department_id']}
										<option value="{$post_arr['department_id']}">{$post_arr['department_name']}</option>
										{/if} 
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<input type="text" name="dep_id" id="dep_id" placeholder="Department ID" class=" form-control col-12">
									{* <select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Dep Id" id="dep_id" name="dep_id" >
										{foreach from=$dep_id item=item}
										<option value="{$item}">{$item}</option>
										{/foreach}
									</select> *}
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Status" id="status" name="status" >

										<option value="active" {if $post_arr['status'] == 'active'}selected{/if}>Active </option>
										<option value="inactive"{if $post_arr['status'] == 'inactive'}selected{/if}>InActive </option>

									</select>
								</div>
								{form_error('status')}
							</div>
							
							
						</div>
						<div class="row mt-2"> 
							<div class="col-sm-6"> 
								<button type="submit" class="btn btn-primary col-sm-6" name="submit" value="filter">
									<i class="fa fa-filter"></i> Filter
								</button>
							</div>
							<div class="col-sm-6"> 
								<button type="reset" class="btn btn-warning col-sm-6  pull-right" name="submit" value="reset">
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
										<th class="text-center">Department ID</th>
										<th class="text-center">Department Name</th>
										<th class="text-center">Deprtment cost per hour</th><th class="text-center">Status</th>
										<th class="text-center">Action</th>
										
										
									</tr>
								</thead>
								<tbody> 
									{foreach from=$department_details item=v}
									<tr>
										<td class="text-center">{counter}</td>
										<td class="text-center">{$v.dep_id}</td>
										<td class="text-center">{$v.name}</td>
										<td class="text-center">{cur_format($v.cost_per_hour)}</td>
										{if $v.status== 1}
										<td class="text-center">Active</td>
										{else}
										<td class="text-center">InActive</td>
										{/if}
										<td class="td-actions text-center"> 
											<a rel="tooltip" title="Edit" href="javascript:edit_department('{$v.enc_id}')" class="btn btn-success btn-link"><i class="material-icons">edit</i></i></a>

											<a rel="tooltip" title="Delete" href="javascript:delete_department('{$v.enc_id}')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>
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
<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
<script type="text/javascript">
	function edit_department(id)
	{
		swal({
			title:'{lang('text_are_you_sure')}',
			text: "You will not recover",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: '{lang('text_yes_update_it')}',
			cancelButtonText: '{lang('text_no_cancel_please')}',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = '{base_url()}' + "admin/settings/department-master/"+id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});
	}

	function delete_department(id)
	{

		swal({
			title:'{lang('text_are_you_sure')}',
			text:"You Will not recover",
			type:"warning",
			showCancelButton:true,
			confirmButtonColor:"#DD6B55", 
			confirmButtonText: '{lang('text_yes_delete_it')}',
			cancelButtonText: '{lang('text_no_cancel_please')}',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = '{base_url()}' +"admin/settings/department-list/delete/"+id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});

	}
</script>
<script type="text/javascript">

	$(document).ready(function(){ 

		$('.deptname_ajax').select2({

			placeholder: 'Select Department',
			ajax: {
				url:'{base_url(log_user_type())}/autocomplete/department_name_ajax',

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
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
{/block}