{extends file="layout/base.tpl"}
{block header}
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
{/block}
{block name="body"}  
{if log_user_type()=="admin"}



<div class="row">

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Customers</p>
				<h4 class="card-title">{$count_delivery_notes}</h4>
			</div>
			{if log_user_type() =="admin"}

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}admin/project/project-list" > View Details</a>
				</div>
			</div>
			{/if}
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-icon card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Leads</p>
				<h4 class="card-title">{$pending_delivery_count}</h4>
			</div>
			{if log_user_type() =="admin"}

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}admin/delivery/delivery-list" > View Details</a>
				</div>
			</div>
			{/if}
		</div>
	</div>


	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-success card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Staffs</p>
				<h4 class="card-title">{$staff_count}</h4>
			</div>
			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}admin/delivery/delivery-list" > {lang('text_view_more')}</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Recent Enquiry</h4>
			</div>
			<div class="card-body">
				{if $details}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Code</th>
								<th>Project Name</th>
								<th>Driver</th>
								<th>Vehicle</th>
								<th>Status</th>
								<th>Created On</th>
								<th>Action</th>
								
							</tr>
						</thead>
						<tbody>

							{foreach $details as $v} 

							<tr>
								<td >{counter}</td>
								<td>{$v.code}</td>
								<td>{$v.project_name}</td>
								<td>{$v.driver_name}</td>
								<td>{$v.vehicle}</td>
								<td>{ucfirst($v.status)|replace:'_':' '}</td>
								<td>{$v.date_created}</td>   
								<td class="td-actions text-right ">
									{if $v.status == 'pending'}
									<a rel="tooltip" title="Edit" href="javascript:edit_delivery_note('{$v.enc_id}')" class="btn btn-success btn-link"><i class="material-icons">edit</i></i></a>
									{/if}
									<a rel="tooltip" title="View" href="{base_url()}admin/delivery/delivery_details/{$v.enc_id}" class="btn btn-info btn-link"><i class="material-icons">local_see</i></a>  
								</td> 
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
				<div class="card-footer"> 
					<div class="stats">
						<i class="material-icons">local_offer</i><a href="{base_url()}{log_user_type()}/delivery/delivery-list" > {lang('text_view_more')}</a>
					</div>
				</div>
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Delivery 	Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
			</div>
		</div>
	</div>
</div>


{/if}
{/block}

{block name="footer"}
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>

<script type="text/javascript">
	function edit_project(id)
	{
		swal({
			title: 'Are you sure?',
			text: 'do you want to edit ',
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: 'edit',
			cancelButtonText: 'Cancel Please',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = "{base_url()}{log_user_type()}/project/add_project/"+id; 
			} else {
				swal('{lang("text_cancelled")}','{lang("text_cancelled")}', "error");
			}
		});
	}
	function edit_delivery_note(id)
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
				document.location.href = '{base_url()}' + "admin/delivery/add-delivery-items/"+id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});
	}
</script>

{/block}