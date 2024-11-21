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
				<h4 class="card-title">{$customers}</h4>
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
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Leads</p>
				<h4 class="card-title">{$total_leads}</h4>
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
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Staff</p>
				<h4 class="card-title">{$staffs}</h4>
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
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Immigration Approved Customers</p>
				<h4 class="card-title">{$immigration_approved}</h4>
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
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Advanced Paid Customers</p>
				<h4 class="card-title">{$advance_customers}</h4>
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
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Full Paid Customers</p>
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
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Recent Leads</h4>
			</div>
			<div class="card-body">
				{if $details}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Name</th>
								<th>Enquiry Status</th>
								<th>Immigration Status</th>
								<th>Mobile</th>
								<th>Email</th>
								<th>Due Date</th>
								<!-- <th>Action</th> -->
								
							</tr>
						</thead>
						<tbody>

							{foreach $details as $v} 

							<tr>
								<td >{counter}</td>
								<td>{$v.firstname}</td>
								<td>{$v.enquiry_status}</td>
								<td>{$v.immigration_status}</td>
								<td>{$v.mobile}</td>
								<td>{$v.email}</td>
								<td>{$v.date}</td>
								
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
				<div class="card-footer"> 
					<div class="stats">
						<i class="material-icons">local_offer</i><a href="{base_url()}{log_user_type()}/packages/list-leads" > {lang('text_view_more')}</a>
					</div>
				</div>
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
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