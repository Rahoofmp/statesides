{extends file="layout/base.tpl"}

{block name="body"}   
 
<div class="row">

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Enquiries Created</p>
				<h4 class="card-title">{$enquires}</h4>
			</div> 
			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url(log_user_type())}/packages/list-leads" > View Details</a>
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
				<p class="card-category">Total Leads</p>
				<h4 class="card-title">{$leads}</h4>
			</div>
			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url(log_user_type())}/packages/list-leads" > View Details</a>
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
				<p class="card-category">Totoal Customers</p>
				<h4 class="card-title">{$customers}</h4>
			</div> 

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url(log_user_type())}/packages/list-leads" > View Details</a>
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
				<h4 class="card-title">Recent Leads(Latest 10)</h4>
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
							<i class="fa fa-exclamation"> No Delivery 	Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
			</div>
		</div>



{/block}

{block name="footer"}
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>
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