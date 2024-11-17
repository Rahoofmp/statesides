{extends file="layout/base.tpl"}
{block header}
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">

<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/jquery-confirm.min.css') }">
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/extensions/fixedColumns.dataTables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/select.dataTables.min.css')}">

{/block}
<style type="text/css">
.my-group .form-control{
	width:50%;
} 
@media print {
	table tr td p,table tr td { 
		font-size:12px;
		padding: 0px;
		margin: 0px;
		margin-top:5px; 
	}

}
table.dataTable thead > tr > th.sorting{
	text-align: left !important;
</style>
{block name="body"}  
<div class="row "> 
	<div class="col-sm-12 hidden-print"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					{form_open('','')}
					<div class="form-body">
						<div class="row"> 
							<div class="col-md-8">
								<div class="form-group">
									<select id="project_id" name="project_id" class="project_ajax form-control" >
										{if $post_arr['project_id']}
										<option value="{$post_arr['project_id']}">{$post_arr['project_name']}</option>
										{/if} 
									</select> 
								</div>
							</div>

							<div class="col-md-4"> 
								<button type="submit" class="btn btn-primary col-md-12" name="search" value="search">
									<i class="fa fa-filter"></i> {lang('button_filter')}
								</button> 
							</div>
						</div>
					</div>
					{form_close()}
				</div>
			</div>
		</div> 
	</div> 
</div> 
{if $post_arr['project_id']}

<div class="row">
	<div class="col-sm-6">
		<div class="card card-stats">
			<div class="card-header card-header-warning card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Orders</p> <h4 class="card-title">{$project_count}</h4><p class="card-category">Total Est. Time</p>
				<h4 class="card-title">{$total_est_time} Hrs</h4><p class="card-category">Total Spent Time</p>
				<h4 class="card-title">{$total_spent_time} Hrs</h4>

			</div> 
			
		</div>
	</div>
	<div class="col-sm-6">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Time Difference</p>
				<h4 class="card-title"> 

					{if $total_time_difference>0}
					<i class="material-icons">add</i>
					{elseif $total_time_difference<0} 
					<i class="material-icons">remove</i>
					{/if}
					<span class="font-weight-bold" style="font-size: 34px;"> {abs($total_time_difference)}</span>Hrs
				</h4>
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
	<div class="col-md-6">

		<div class="row">
			<div class="col-md-12">
				<div class="card"> 
					<div class="card-body">
						<div id="accordion" role="tablist">
							<div class="card-collapse">
								<div class="card-header" role="tab" id="headingOne">
									<h5 class="mb-0">
										<a data-toggle="collapse" href="#collapseProjectDetails" aria-expanded="false" aria-controls="collapseProjectDetails" class="collapsed">
											Project Details
											<i class="material-icons">keyboard_arrow_down</i>
										</a>
									</h5>
								</div>
								<div id="collapseProjectDetails" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
									<div class="card-body">
										<div class="table-responsive"> 
											<table class="table table-hover">
												{foreach $project_details as $v}
												<thead>
												</thead>
												<tbody> 
													<tr>
														<td >Project Name: </td>
														<td>{$v.project_name}</td> 
													</tr>
													<tr>
														<td >Status: </td>
														<td>{if $v.status == 'draft'} Draft {elseif $v.status == 'pending'} pending {else} Inactive{/if}</td> 
													</tr>
													<tr>
														<td >Estimated Cost: </td>
														<td>{cur_format($v.estimated_cost)}</td> 
													</tr>
													<tr>
														<td>Estimated Duration: </td>
														<td>{$v.estimated_duration} Hrs</td> 
													</tr>
													<tr>
														<td>Estimated Value: </td>
														<td>{$v.estimated_value}</td> 
													</tr>
													<tr>
														<td>Date: </td>
														<td>{$v.date|date_format:"%Y - %m - %d"}</td> 
													</tr>
													<tr>
														<td>E-mail: </td>
														<td>{$v.email}</td> 
													</tr>
													<tr>
														<td>Duration Date</td>
														<td>{$v.start_date}<br>/{$v.end_date}</td> 
													</tr>

												</tbody>
												{/foreach}
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
	<div class="col-md-6">

		<div class="row">
			<div class="col-md-12">
				<div class="card"> 

					<div class="card-body">
						<div id="accordion2" role="tablist">
							<div class="card-collapse">
								<div class="card-header" role="tab" id="headingTwo">
									<h5 class="mb-0">
										<a data-toggle="collapse" href="#collapseCustomerDetails" aria-expanded="false" aria-controls="collapseCustomerDetails" class="collapsed">
											Customer Details
											<i class="material-icons">keyboard_arrow_down</i>
										</a>
									</h5>
								</div>
								<div id="collapseCustomerDetails" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion2" style="">
									<div class="card-body ">
										<div class="table-responsive">
											<table class="table table-hover">
												{foreach $project_details as $v}
												<thead>
												</thead>
												<tbody> 
													<tr>
														<td >Username: </td>
														<td>{$v.customer_username}</td> 
													</tr>
													<tr>
														<td >Name: </td>
														<td>{$v.cus_name}</td> 
													</tr>
													<tr>
														<td >Mobile Number: </td>
														<td>{$v.customer_mobile}</td> 
													</tr>
													<tr>
														<td>E-mail: </td>
														<td>{$v.customer_email}</td> 
													</tr>

												</tbody>
												{/foreach}
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
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
				<h4 class="card-title">Project Jobs</h4>
			</div>
			<div class="card-body">
				{if $project_jobs}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning normal">
							<tr>
								<th class="text-center">#</th>
								<th>Order ID</th> 
								<th>Order Name</th>
								<th>Date Created</th>
								<th>Estimated Time</th>
								<th>Spent Time</th>
								<th>Time Difference</th>
								<th>Status</th>
								
								
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							{$i=1}
							{foreach $project_jobs as $v}

							<tr>
								<td >{$i++}</td>
								<td>{$v.order_id}</td>
								<td>{$v.name}</td>
								<td>{$v.date|date_format:"%Y - %m - %d"}</td> 
								
								<td>
									{$v.estimated_working_hrs} Hrs
								</td>
								<td> {$v.actual_workin_hrs} Hrs
								</td>
								<td>
									{$v.time_difference} Hrs
								</td>
								<td>

									Admin: {if $v.admin_status == 'pending'}<span class="badge badge-pill badge-warning">pending</span>{elseif $v.admin_status == 'reject'}<span class="badge badge-pill badge-danger">Rejected</span>{elseif $v.admin_status == 'confirm'}<span class="badge badge-pill badge-success">Approved</span>{/if}

									<span class="clearfix"></span>

									Cust: {if $v.customer_status == 'pending'}<span class="badge badge-pill badge-warning">Pedning</span>{elseif $v.customer_status == 'reject'}<span class="badge badge-pill badge-danger">Rejected</span>{elseif $v.customer_status == 'approved'}<span class="badge badge-pill badge-success">Approved</span>{else}<span class="badge badge-pill badge-success">{$v.customer_status}</span>{/if}

								</td>


								<td class="td-actions text-right ">

									<a href = "{base_url(log_user_type())}/jobs/order-details/{$v.enc_id}" class="btn btn-sm btn-link btn-info" data-placement="top" title ="View Order Details" target="_blank"><i class="material-icons" aria-hidden="true" target="_blank">local_see</i></a>
								</td>     
							</tr>
							{/foreach}

						</tbody>
					</table>
				</div>
				
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Project Job Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
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
				<h4 class="card-title">Inventory Details</h4>
			</div>
			<div class="card-body">
				{if $inventory}
				<div class="table-responsive">
					<table class="table" id="material_issue">
						<thead class="bg-light text-warning normal">
							<tr>
								<th class="text-center">#</th>
								<th>Voucher number</th>
								<th>Voucher Date</th>
								<th>Allocated Quantity</th>
								<th>Issued Quantity</th>
								<th>Difference</th>
								<th>Created On</th>
							</tr>
						</thead>
						<tbody>
							{$i=1}
							{foreach from=$inventory item=in}			
							<tr>
								<td >{$i++}</td>
								<td>{$in.voucher_number}</td>
								<td>{$in.voucher_date}</td>
								<td>{$in.allocated_qty}</td>
								<td>{$in.total_issued_qty}</td>
								<td>{$in.diff}</td>
								<td>{$in.date_added}</td> 
							</tr>					
							{/foreach}
						</tbody>
					</table>
				</div>
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Inventory Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
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
				<h4 class="card-title">Delivery Details</h4>
			</div>
			<div class="card-body">
				<!-- {if $delivery} -->
				<div class="table-responsive">
					<table class="table" id="example">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Delivery Code</th>
 								<th>Driver</th>
								<th>Vehicle</th>
								<th>Status</th>
								<th>Created On</th> 
							</tr>
						</thead> 
					
					</table>

				</div>
				
				<!-- {else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Delviery Details Found</i>
						</h4>
					</p>
				</div>
				{/if} -->
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Package Added</h4>
			</div>
			<div class="card-body">
				<!-- {if $packages} -->
				<div class="table-responsive">
					<table class="table" id="package_list">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
 								<th>Package Code</th>
								<th>Package Name</th>
								<th>Area Master</th>
								<th>Item</th>
								<th>Status</th>
								<th>Created On</th>
								<th class="text-center">View</th>
							</tr>
						</thead> 
						
					</table>

				</div>
				
				<!-- {else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Delviery Details Found</i>
						</h4>
					</p>
				</div>
				{/if} -->
			</div>
		</div>
	</div>
</div> 


{/if}
{/block}

{block name="footer"}
<script src="{assets_url('js/ui-notifications.js')}"></script>


<script src="{assets_url('js/tables/datatable/datatables.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.autoFill.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.colReorder.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.fixedColumns.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.select.min.js')}"></script>
<script src="{assets_url('js/scripts/tables/datatables-extensions/datatable-autofill.min.js')}"></script>

<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>

<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script> 


<script src="{assets_url('js/jquery-confirm.min.js') }"></script>
<script> 
	$('.project_ajax').select2({


		placeholder: 'Select a project',
		ajax: {
			url:'{base_url()}admin/autocomplete/project_ajax',

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

	// $(document).ready(function(){

	// 	var order = $('#example').DataTable({

	// 		'processing': true,
	// 		'serverSide': true,
	// 		"autoWidth": false,
	// 		'serverMethod': 'post', 
	// 		"pagingType": "full_numbers",
	// 		"pageLength": 10,
	// 		"sortable": true,

	// 		"aaSorting": [],
	// 		"order": [],
	// 		"aoColumnDefs": [
	// 		{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6 ] },
	// 		],
	// 		"columnDefs": [{
	// 			"targets": 'no-sort',
	// 			"orderable": false,
	// 			"order": [],
	// 		}],

	// 		'ajax': {
	// 			'url':'{base_url()}admin/jobs/get_job_ajax',
	// 			"type": "POST",
	// 			"data" : {
	// 				'project_id' : '{$post_arr['project_id']}',


	// 			}

	// 		},
	// 		'columns': [
	// 		{ data: 'index' },
	// 		{ data: 'order_id',mRender: function(data,type,row){
	// 			if(row.order_id){
	// 				var action = data;
	// 				if(row.name)
	// 				{
	// 					action += '<span class="clearfix"></span>' +row.name;
	// 				}
	// 				return action;
	// 			}
	// 		}},
	// 		{ data: 'amount' },
	// 		{ data: 'estimated_working_hrs'},
	// 		{ data: 'actual_workin_hrs'},
	// 		{ data: 'date'},
	// 		{ data: 'requested_date'},
	// 		{ data: 'customer_name'},
	// 		{ data: 'project_name'},
	// 		{ data: 'admin_status',
	// 		mRender: function(data, type, row) { 

	// 			if(row.admin_status)
	// 			{


	// 				if (row.admin_status =='pending')
	// 				{
	// 					var admin_status='Admin:<span class="badge badge-pill badge-warning">Pending</span>'
	// 				}
	// 				else if (row.admin_status =='reject')
	// 				{
	// 					var admin_status='Admin:<span class="badge badge-pill badge-danger">Rejected</span>' 
	// 				}
	// 				else if(row.admin_status =='confirm')
	// 				{
	// 					var admin_status='Admin:<span class="badge badge-pill badge-success">Approved</span>' 
	// 				}
	// 				var action = admin_status;

	// 			}
	// 			if(row.customer_status)
	// 			{

	// 				if (row.customer_status =='pending')
	// 				{
	// 					action +='<br> Cust:<span class="badge badge-pill badge-warning">Pending</span>'
	// 				}
	// 				else if (row.customer_status =='rejected')
	// 				{

	// 					action +='<br> Cust:<span class="badge badge-pill badge-danger">Rejected</span>'
	// 				}
	// 				else if(row.customer_status =='approved')
	// 				{
	// 					action +='<br> Cust:<span class="badge badge-pill badge-success">Approved</span>' 
	// 				}
	// 				else
	// 				{
	// 					action +='<br> Cust:<span class="badge badge-pill badge-success">customer_status</span>'
	// 				}
	// 			}
	// 			return action;
	// 		}},

	// 		{ data: 'view',
	// 		mRender: function(data, type, row) {

	// 			var link = '<a href = "'+ '{base_url(log_user_type())}/jobs/order-details/' + row.enc_id +'" class="" data-placement="top" title ="View Order Details" target="_blank"><i class="material-icons text-info" aria-hidden="true">local_see</i></a>';


	// 			link += '<a data-id="'+row.enc_id+'" class="edit_job_order " id="edit" title="Edit"><i class="material-icons text-rose" aria-hidden="true">edit</i></a>';

	// 			return link;
	// 		}},
	// 		],


	// 		success: function(response) { 
	// 		} 


	// 	});  
	// }); 


	$(document).on("click", "body #example tbody .edit_job_order" , function() {

		var id = $(this).attr('data-id');            

		if(id) {  
			$.confirm({
				title: 'Confirm!',
				content: 'Are You Sure to edit?? ',
				buttons: {
					confirm: function () {

						document.location.href = '{base_url()}' + "admin/jobs/edit-job/"+id; 
					},
					cancel: function () {
						$.alert('Canceled!');
					},

				}
			});

		}



	} );

	

	
</script>


<script type="text/javascript">
	
	$(document).ready(function(){

		var order = $('#example').DataTable({

			'processing': true,
			'serverSide': true,
			"autoWidth": false,
			'serverMethod': 'post', 
			"pagingType": "full_numbers",
			"pageLength": 10,
			"sortable": true,

			"aaSorting": [],
			"order": [],
			"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4,5 ] },
			],
			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
				"order": [],
			}],

			'ajax': {
				'url':'{base_url()}admin/project/get_delivery_project_dashboard',
				"type": "POST",
				"data" : {
					'project_id' : '{$post_arr['project_id']}',
					
				}

			},
			'columns': [
			{ data: 'index' },
			{ data: 'code' },
			
			{ data: 'driver_name'},
			{ data: 'vehicle'},
			{ data: 'status',
			mRender: function(data) {  
				if (data == "confirm") {
					return "Confirmed"
				}
				else if(data == "reject") {
					return "Rejected" 

				}else if(data == "delivered") {
					return "Delivered" 

				}else if(data == "send_to_delivery") {
					return "Send to deliverey" 

				}else if(data == "deleted") {
					return "Deleted" 

				}else if(data == "partially_delivered") {
					return "Partially delivered" 

				}
				else
				{
					return "Pending"
				}

			}},

			{ data: 'date_created', mRender: function(data, type, row) {
				var link = 'Created: '+ data;
				link += '<span class="clearfix"></span>';			
				
				return link;
			}},

		
			],

			success: function(response) { 
			} 


		});  
	}); 

	
</script>

<script>
	$(document).ready(function(){
		var order = $('#package_list').DataTable({

		'processing': true,
		'serverSide': true,
		"autoWidth": false,
		'serverMethod': 'post', 
		"pagingType": "full_numbers",
		"pageLength": 10,
		"sortable": true,

		"aaSorting": [],
		"order": [],
		"aoColumnDefs": [
		{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6,7 ] },  
		],


		"columnDefs": [{
			"targets": 'no-sort',
			"orderable": false,
			"order": [],
		}],

		'ajax': {
			'url':'{base_url()}admin/project/get_package_list_dashboard',
			'type': "POST", 
			'data' : {
				'project_id' : '{$post_arr['project_id']}',
				
			}

		},

		'columns': [

		{ data: 'index'},
		
		{ data: 'code',
		mRender: function(data, type, row) {
			var link = '<p class="text-info">'+row.code;

			return link;
		}},

		{ data: 'name'},
		{ data: 'area_master'},
		{ data: 'item'},
		{ data: 'status', mRender: function(data, type, row) {
			return data.charAt(0).toUpperCase() + data.slice(1); ;
		}},

		{ data: 'date_created'},
		{ data: 'id', mRender: function(data, type, row) {

		
			var link ='<a rel="tooltip" title="View" href="{base_url()}admin/packages/package_details/'+row.enc_id+'" ><i class="material-icons text-info">local_see</i></a>';
			link+=row.print_view;

			return link;
		}}, 
		],
		success: function(response) { 
		} 
	}); 
}); 
</script>

{/block}