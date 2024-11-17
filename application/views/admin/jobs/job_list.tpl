{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/jquery-confirm.min.css') }">

<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/extensions/fixedColumns.dataTables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/select.dataTables.min.css')}">
<style type="text/css">

	@media print {
		.printdiv { display: block !important; }
	}
	.badge {
		padding: 2px 9px;
		text-transform: uppercase;
		font-size: 9px;
		color: #fff;
		display: inline-block;
		white-space: normal;
	}

</style>
{/block}

{block body} 

<div class="row "> 
	<div class="col-sm-12 hidden-print"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					{form_open('','')}
					<div class="form-body">
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<select id="name" name="project_id" class="project_ajax form-control" >
										{if $post_arr['project_id']}
										<option value="{$post_arr['project_id']}">{$post_arr['project_name']}</option>
										{/if} 
									</select>
								</div>

							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select id="customer_id" name="customer_id" class="customer_ajax form-control" >
										{if $post_arr['customer_id']}
										<option value="{$post_arr['customer_id']}">{$post_arr['cus_name']}</option>
										{/if} 
									</select> 
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select id="order_id" name="order_id" class="orderid_ajax form-control" >
										{if $post_arr['order_id']}
										<option value="{$post_arr['order_id']}">{$post_arr['order_id']}</option>
										{/if} 
									</select> 
								</div>
							</div>
							
							<!-- <div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">From Date</label>
									<input type="text"  class="form-control datepicker" name="start_date" value="{$post_arr['start_date']}">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">To Date</label>
									<input type="text"  class="form-control datepicker" name="end_date" value="{$post_arr['end_date']}">
								</div>
							</div> -->
							
							<div class="col-md-3">
								<div class="form-group">
									<label for="text">Job Name</label>
									<input type="text"  class="form-control " name="job_name" value="{$post_arr['job_name']}">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Status" id="status" name="admin_status" >

										<option value="pending" {if $post_arr['admin_status'] == 'pending'}selected{/if}>Pending </option>
										<option value="reject"{if $post_arr['admin_status'] == 'reject'}selected{/if}>Rejected </option>
										<option value="confirm"{if $post_arr['admin_status'] == 'confirm'}selected{/if}>Confirmed </option>

									</select>
								</div>
								{form_error('status')}
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">Ordered From</label>
									<input type="text"  class="form-control datepicker" name="start_date" value="{$post_arr['start_date']}">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">Ordered To </label>
									<input type="text"  class="form-control datepicker" name="end_date" value="{$post_arr['end_date']}">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">Delivery Date </label>
									<input type="text"  class="form-control datepicker" name="requested_date" value="{$post_arr['requested_date']}">
								</div>
							</div>

							<div class="col-md-4"> 
								<button type="submit" class="btn btn-primary" name="search" value="search">
									<i class="fa fa-filter"></i> {lang('button_filter')}
								</button>
								<button type="submit" class="btn btn-warning mr-1" name="submit" value="reset">
									<i class="fa fa-refresh"></i>  {lang('button_reset')}
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

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Job Orders</h4>
			</div>
			<div class="card-body">
				{* {if $job_list} *}
				<div class="table-responsive">
					<table class="table" id="example">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Order ID</th> 
								<th>Dept. Cost</th>
								<th>Estm. Time</th>
								<th>Spent Time</th>
								<th>Order Date</th>
								<th>Delivery Date</th>
								<th>Customer</th>
								<th>Project</th>
								<th>Status</th>
								
								<th class="text-center">Action</th>
							</tr>
						</thead>
						
						</table>
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
	<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>

	<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
	<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
	<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
	<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
	<script src="{assets_url('js/tables/datatable/datatables.min.js')}"></script>
	<script src="{assets_url('js/tables/datatable/dataTables.autoFill.min.js')}"></script>
	<script src="{assets_url('js/tables/datatable/dataTables.colReorder.min.js')}"></script>
	<script src="{assets_url('js/scripts/tables/datatables-extensions/datatable-autofill.min.js')}"></script>

	<script src="{assets_url('js/tables/datatable/dataTables.fixedColumns.min.js')}"></script>
	<script src="{assets_url('js/tables/datatable/dataTables.select.min.js')}"></script></script>
	<script src="{assets_url('js/jquery-confirm.min.js') }"></script>

	<script src="{assets_url('js/tables/datatable/dataTables.select.min.js')}"></script>

	<script>
		$(document).ready(function() { 
			md.initFormExtendedDatetimepickers();
		});

		
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

		$('.customer_ajax').select2({
			placeholder: 'Select Customer',
			ajax: {
				url:'{base_url()}admin/autocomplete/customer_ajax',

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
		$('.orderid_ajax').select2({
			placeholder: 'Select Job Order Id',
			ajax: {
				url:'{base_url()}admin/autocomplete/job_orderid_ajax',

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
				{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6 ] },
				],
				"columnDefs": [{
					"targets": 'no-sort',
					"orderable": false,
					"order": [],
				}],

				'ajax': {
					'url':'{base_url()}admin/jobs/get_job_ajax',
					"type": "POST",
					"data" : {
						'project_id' : '{$post_arr['project_id']}',
						'customer_id' : '{$post_arr['customer_id']}',
						'order_id' : '{$post_arr['order_id']}',
						'job_name' : '{$post_arr['job_name']}',
						'admin_status' : '{$post_arr['admin_status']}',
						'status' : '{$post_arr['status']}',
						'start_date' : '{$post_arr['start_date']}',
						'end_date' : '{$post_arr['end_date']}',
						'requested_date' : '{$post_arr['requested_date']}',
						
					}

				},
				'columns': [
				{ data: 'index' },
				{ data: 'order_id',mRender: function(data,type,row){
					if(row.order_id){
						var action = data;
						if(row.name)
						{
							action += '<span class="clearfix"></span>' +row.name;
						}
						return action;
					}
				}},
				{ data: 'amount' },
				{ data: 'estimated_working_hrs'},
				{ data: 'actual_workin_hrs'},
				{ data: 'date'},
				{ data: 'requested_date'},
				{ data: 'customer_name'},
				{ data: 'project_name'},
				{ data: 'admin_status',
				mRender: function(data, type, row) { 

					if(row.admin_status)
					{
						

						if (row.admin_status =='pending')
						{
							var admin_status='Admin:<span class="badge badge-pill badge-warning">Pending</span>'
						}
						else if (row.admin_status =='reject')
						{
							var admin_status='Admin:<span class="badge badge-pill badge-danger">Rejected</span>' 
						}
						else if(row.admin_status =='confirm')
						{
							var admin_status='Admin:<span class="badge badge-pill badge-success">Approved</span>' 
						}
						var action = admin_status;

					}
					if(row.customer_status)
					{

						if (row.customer_status =='pending')
						{
							action +='<br> Cust:<span class="badge badge-pill badge-warning">Pending</span>'
						}
						else if (row.customer_status =='rejected')
						{

							action +='<br> Cust:<span class="badge badge-pill badge-danger">Rejected</span>'
						}
						else if(row.customer_status =='approved')
						{
							action +='<br> Cust:<span class="badge badge-pill badge-success">Approved</span>' 
						}
						else
						{
							action +='<br> Cust:<span class="badge badge-pill badge-success">customer_status</span>'
						}
					}
					return action;
				}},

				{ data: 'view',
				mRender: function(data, type, row) {

					var link = '<a href = "'+ '{base_url(log_user_type())}/jobs/order-details/' + row.enc_id +'" class="" data-placement="top" title ="View Order Details" target="_blank"><i class="material-icons text-info" aria-hidden="true">local_see</i></a>';


					link += '<a data-id="'+row.enc_id+'" class="edit_job_order " id="edit" title="Edit"><i class="material-icons text-rose" aria-hidden="true">edit</i></a>';

					return link;
				}},
				],


				success: function(response) { 
				} 


			});  
		}); 


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
	{/block}
