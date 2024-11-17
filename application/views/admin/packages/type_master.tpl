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
									<input type="text" name="name" id="name" {if $id} value="{$master_detail['name']}"{else}placeholder="Name" {/if}class=" form-control col-12">
									<input type="text" name="id" value="{$master_detail['id']}" hidden="">
									{form_error('name')}
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-2"> 
						<div class="col-sm-6"> 
							{if $id}
							<button type="submit" class="btn btn-primary col-sm-6" name="update" value="filter">
								<i class="fa fa-arrow-circle-right"></i> Update
							</button>
							
							{else}
							<button type="submit" class="btn btn-primary col-sm-6" name="create" value="filter">
								<i class="fa fa-arrow-circle-right"></i> Create
							</button>
							{/if}
						</div>

					</div>
				</div>
				{form_close()}
			</div>
		</div>
	</div>
</div>
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
										<th class="text-center">Name</th>
										<th class="text-center">Action</th>
										
										
									</tr>
								</thead>
								<tbody> 
									{foreach from=$details item=v}
									<tr>
										<td class="text-center">{counter}</td>
										<td class="text-center">{$v.name}</td>
										<td class="td-actions text-center"> 
											<a rel="tooltip" title="Edit" href="javascript:edit_vat('{$v.enc_id}')" class="btn btn-success btn-link"><i class="material-icons">edit</i></i></a>

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
	function edit_vat(id)
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
				document.location.href = '{base_url()}' + "admin/packages/type_master/"+id; 
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
				document.location.href = '{base_url()}' +"admin/packages/type_master/"+id+"/delete"; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});

	}
</script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>

{/block}