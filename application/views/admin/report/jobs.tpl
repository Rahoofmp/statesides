{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
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
									<select id="customer_id" name="customer_id" class="customer_ajax form-control" >
										{if $post_arr['customer_id']}
										<option value="{$post_arr['customer_id']}">{$post_arr['customer_name']}</option>
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
							
							<div class="col-md-3">
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
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">Delivery Date</label>
									<input type="text"  class="form-control datepicker" name="delivery_date" value="{$post_arr['delivery_date']}">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Admin Status" id="admin_status" name="admin_status" >

										<option value="pending" {if $post_arr['admin_status'] == 'pending'}selected{/if} >Pending</option>
										<option value="confirm"{if $post_arr['admin_status'] == 'confirm'}selected{/if}> Confirmed</option>
										<option value="reject"{if $post_arr['admin_status'] == 'reject'}selected{/if}>Rejected</option>

									</select>
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Customer Status" id="customer_status" name="customer_status" >

										<option value="pending" {if $post_arr['customer_status'] == 'pending'}selected{/if} >Pending</option>
										<option value="confirm"{if $post_arr['customer_status'] == 'confirm'}selected{/if}> Confirmed</option>
										<option value="reject"{if $post_arr['customer_status'] == 'reject'}selected{/if}>Rejected</option>

									</select>
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

{if $smarty.post.search}
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon hidden-print">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Job Orders</h4>
				<div class="d-flex align-items-end pull-right "> 

					<a href="#" onclick="javascript:window.print();"  class="btn btn-social-icon btn-block btn-sm mb-1 btn-github hidden-print"  ><i class="fa fa-print"></i></a>
				</div>

			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="example">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Order ID</th> 
								<th>Total Cost</th>
								<th>Date</th>
								<th>Times</th>
								<th>Customer</th>
								<th>Project</th>
								<th>Status</th> 
							</tr>
						</thead>

					</table>
				</div>
				
			</div>
		</div>
	</div>
</div> 
{/if}
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
<script src="{assets_url('js/tables/datatable/dataTables.fixedColumns.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.select.min.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('js/scripts/tables/datatables-extensions/datatable-autofill.min.js')}"></script>

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
			{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6, 7 ] },
			],

			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
				"order": [],
			}],

			'ajax': {
				'url':'{base_url()}admin/report/get_jobs_ajax',
				"type": "POST",
				"data" : {
					'customer_id' : '{$post_arr['customer_id']}',
					'order_id' : '{$post_arr['order_id']}',
					'start_date' : '{$post_arr['start_date']}',
					'end_date' : '{$post_arr['end_date']}',
					'admin_status' : '{$post_arr['admin_status']}',
					'customer_status' : '{$post_arr['customer_status']}',
					'delivery_date' : '{$post_arr['delivery_date']}',
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
			{ data: 'department_cost'},
			{ data: 'date',
			mRender: function(data,type,row){
				if(row.date){
					var date = "Ordered : " +row.date;
					var action = date;
					if(row.requested_date){
						action += '<span class="clearfix"></span><hr> Delivery :' +row.requested_date;
					}
				}
				return action;

			}},
			{ data: 'estimated_working_hrs',
			mRender: function(data,type,row){
				if(row.estimated_working_hrs){
					var estimated_working_hrs = "Ordered : " +row.estimated_working_hrs+ " Hrs";
					var action = estimated_working_hrs;
					if(row.actual_workin_hrs){
						action += '<span class="clearfix"></span><hr> Worked :' +row.actual_workin_hrs+ "Hrs";
					}
				}
				return action;

			}},
			
			{ data: 'customer_username'},
			{ data: 'project_name'},
			
			{ data: 'admin_status',
			mRender: function(data, type, row) { 
				if(row.admin_status){
					if(row.admin_status == 'pending')
					{
						var admin_status = 'Admin: <span class="badge badge-pill badge-warning">Pedning</span>';
					}
					if(row.admin_status == 'reject')
					{
						var admin_status = 'Admin: <span class="badge badge-pill badge-danger">Rejected</span>';
					}
					if(row.admin_status == 'confirm')
					{
						var admin_status = 'Admin: <span class="badge badge-pill badge-success">Approved</span>';
					}
					var action = admin_status;
					if(row.customer_status)
					{
						if(row.customer_status == 'pending'){
							action += '<br> Cust: <span class="badge badge-pill badge-warning">Pedning</span>'
						}
						if(row.customer_status == 'reject'){
							action += '<br> Cust:<span class="badge badge-pill badge-danger">Rejected</span>'
						}
						if(row.customer_status == 'confirm'){
							action += '<br> Cust:<span class="badge badge-pill badge-success">Approved</span>'
						}
					}
					return action;
				} 
				

			} },
			
			
			],
			success: function(response) { 
			} 
		});  
	}); 


</script>

<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});
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
				document.location.href = '{base_url()}' + "admin/jobs/edit-job/"+id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});
	}

	
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
	

</script>
{/block}
