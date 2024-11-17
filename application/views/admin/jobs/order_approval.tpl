{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
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
							<div class="col-sm-4">
								<div class="form-group">

									<select id="name" name="project_id" class="project_ajax form-control" >
										{if $post_arr['project_id']}
										<option value="{$post_arr['project_id']}">{$post_arr['project_name']}</option>
										{/if} 
									</select>
									
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<select id="customer_name" name="customer_name" class="customer_ajax form-control" >
										{if $post_arr['customer_name']}
										<option value="{$post_arr['customer_name']}">{$post_arr['cus_name']}</option>
										{/if} 
									</select>
									
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<select id="order_id" name="order_id" class="orderid_ajax form-control" >
										{if $post_arr['order_id']}
										<option value="{$post_arr['order_id']}">{$post_arr['order_id']}</option>
										{/if} 
									</select> 
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-3">
								<div class="form-group">
									<label for="text">Job Name</label>
									<input type="text"  class="form-control " name="job_name" value="{$post_arr['job_name']}">
								</div>
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
							{* <div class="col-sm-3">
								<div class="form-group"> 
									<select class="selectpicker col-12"  data-style="select-with-transition" title="Status" id="status" name="status"  >
										<option value="pending" {if $post_arr['status'] == 'pending'}selected{/if} >Pending</option>
										<option value="confirm"{if $post_arr['status'] == 'confirm'}selected{/if}> Confirmed</option>
										<option value="reject"{if $post_arr['status'] == 'reject'}selected{/if}>Rejected</option>

									</select>
								</div>
							</div> *}
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
				{form_open('','')}
				{if $job_list}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Order ID</th>
								<th>Order Name</th>
								<th>Order Date</th>
								<th>Estm. Cost</th>
								<th>Delivery Date</th>
								<th>Project</th>
								<th>Status</th>
								<th>View</th>
								
							</tr>
						</thead>
						<tbody>

							{foreach $job_list as $v} 

							<tr>
								<td ><input type="checkbox" name="order_id[]" value="{$v.id}">  {counter}</td>
								<td>{$v.order_id}</td>
								<td>{$v.name}</td>
								<td>{$v.date}</td>
								<td>{cur_format($v.department_cost)}</td>
								<td>{$v.requested_date}</td>
								<td>{$v.project_name}</td>
								<td>
									Admin: {if $v.admin_status == 'pending'}<span class="badge badge-pill badge-warning">Pedning</span>{elseif $v.admin_status == 'reject'}<span class="badge badge-pill badge-danger">Rejected</span>{elseif $v.admin_status == 'confirm'}<span class="badge badge-pill badge-success">Approved</span>{/if}

									<span class="clearfix"></span>

									Cust: {if $v.customer_status == 'pending'}<span class="badge badge-pill badge-warning">Pedning</span>{elseif $v.customer_status == 'reject'}<span class="badge badge-pill badge-danger">Rejected</span>{elseif $v.customer_status == 'confirm'}<span class="badge badge-pill badge-success">Approved</span>{/if}
								</td>
								<td>
									<a href = "{base_url(log_user_type())}/jobs/order-details/{$v.enc_id}" class="btn btn-sm btn-link btn-info" data-placement="top" title ="View Order Details" target="_blank"><i class="material-icons" aria-hidden="true" target="_blank">local_see</i></a>
								</td>
							</tr>
							{/foreach}

						</tbody>
					</table>
				</div>
				<div class="card-footer">
					<div class="row">
						<div class="col-md-12">
							<select class="form-control select2" name="admin_status" id="admin_status" style="width: 250px"> 
								<option value="confirm" selected>Approve</option>
								<option value="reject">Reject</option>
							</select>
							<button class="btn btn-primary" type="submit" name="change_status" value="change"> Submit</button>
							{form_error('order_id[]')}
							{form_error('customer_status')}
						</div> 
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
				{form_open()}
			</div>
		</div>
	</div>
</div> 
{/block}

{block footer} 

<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url()}plugins/bootstrap-selectpicker.js"></script>



<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});

	$('.select2').select2();
	$('.orderid_ajax').select2({
		placeholder: 'Select Job Order Id',
		ajax: {
			url:'{base_url(log_user_type())}/autocomplete/job_orderid_ajax',

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
	$(document).ready(function(){ 

		$('.customer_ajax').select2({

			placeholder: 'Select a customer',
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

	});  

</script>
{/block}
