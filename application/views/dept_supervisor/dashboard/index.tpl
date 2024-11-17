{extends file="layout/base.tpl"}
{block header} 
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">

{/block}
{block name="body"}   

<div class="row">


	{* <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-warning card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Projects</p> <h4 class="card-title">{$project_count}</h4>
			</div> 
			
		</div>
	</div>
 *}
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-success card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Job Order</p>
				<h4 class="card-title">{$job_order_count}</h4>
			</div>
			{if log_user_type() =="admin"}

			{* <div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}admin/member/all-members" > View Details</a>
				</div>
			</div> *}
			{/if}
		</div>
	</div>

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-danger card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Pending Job Orders</p>
				<h4 class="card-title">{$pending_job_order}</h4>
			</div>
			{* {if log_user_type() =="admin"}

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}admin/member/all-members" > View Details</a>
				</div>
			</div>
			{/if} *}
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
				<h4 class="card-title">Recent Job Orders</h4>
			</div>
			<div class="card-body">
				{if $job_list}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Order ID</th>
								<th>Order Name</th>
								<th>Order Date</th>
								<th>Delivery Date</th>
								<th>Customer</th>
								<th>Project</th>
								
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>

							{foreach $job_list as $v} 

							<tr>
								<td >{counter}</td>
								<td>{$v.order_id}</td>
								<td>{$v.job_name}</td>
								<td>{$v.date|date_format}</td>
								<td>{$v.requested_date}</td>
								<td>{$v.customer_username}</td>
								<td>{$v.project_name}</td>
							
						<td class="td-actions text-right ">
									<a rel="tooltip" title="Edit" href="javascript:edit_job_order('{$v.enc_id}')" class="btn btn-dribbble btn-link"><i class="material-icons">edit</i></i></a><br>
									<a rel="tooltip" title="View" href="{base_url(log_user_type())}/report/order-details/{$v.enc_id}" class="btn btn-info btn-link" target="_blank"><i class="material-icons">visibility</i></i></a>
								</td>     
							</tr>
							{/foreach}

						</tbody>
					</table>
				</div>
				<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}dept_supervisor/jobs/job_list" > View More</a>
				</div>
			</div>
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Job Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
			</div>
		</div>
	</div>
</div> 

{*  
<div class="row">

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Delivery Notes</p>
				<h4 class="card-title">{$count_delivery_notes}</h4>
			</div> 
			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url(log_user_type())}/delivery/delivery-list" > View Details</a>
				</div>
			</div> 
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-icon card-header-icon">
				<div class="card-icon">
					<i class="material-icons">cloud_done</i>
				</div>
				<p class="card-category">Pending Deliveries</p>
				<h4 class="card-title">{$pending_delivery_count}</h4>
			</div> 

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url(log_user_type())}/delivery/delivery-list" > View Details</a>
				</div>
			</div> 
		</div>
	</div>


	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-success card-header-icon">
				<div class="card-icon">
					<i class="material-icons">message</i>
				</div>
				<p class="card-category">Send To Delivery</p>
				<h4 class="card-title">{$sendto_delivery_count}</h4>
			</div>
			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url(log_user_type())}/delivery/delivery-list" > {lang('text_view_more')}</a>
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
				<h4 class="card-title">Recent Delivery Notes(Latest 10)</h4>
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

 *}

{/block}

{block name="footer"}
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{{ assets_url('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script> 
<script>
	
	function edit_job_order(id)
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
				document.location.href = '{base_url(log_user_type())}' + "/jobs/edit-job/"+id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});
	}
	</script>

{* <script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('js/form-wizard.js')}"></script>
<script src="{assets_url('js/page-js/registration.js')}"></script>
<script src="{assets_url('js/clipboard.min.js')}"></script>

<script>
	$(document).ready(function() {

		md.initDashboardPageCharts();
	});

	(function(){
		new Clipboard('#copy-button');
	})();
</script> *}
{/block}