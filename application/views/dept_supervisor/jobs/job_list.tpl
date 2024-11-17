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
									<label for="issueinput3">Ordered From</label>
									<input type="text"  class="form-control datepicker" name="order_from" value="{$post_arr['order_from']}">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">Ordered To</label>
									<input type="text"  class="form-control datepicker" name="order_to" value="{$post_arr['order_to']}">
								</div>
							</div>
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
									<label for="issueinput3">Delivery Date </label>
									<input type="text"  class="form-control datepicker" name="delivery_date" value="{$post_arr['delivery_date']}">
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
				{if $job_list}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								
								<th>Order Name</th>
								<th>Date</th>
								<th>Customer</th>
								<th>Estm. Time</th>
								<th>Spent Time</th>
								<th>Project</th>
								<th>Status</th>

								
								
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>

							{foreach $job_list as $v} 

							<tr>
								<td >{counter}</td>
								
								<td>{$v.job_name}<br>({$v.order_id})</td>
								<td>Ordered: {$v.date|date_format: "%Y-%m-%d"}
									<span class="clearfix"></span>
									<hr>
								Delivery: {$v.requested_date|date_format: "%Y-%m-%d"}</td>
								
								<td>{$v.customer_username}</td>
								<td>{$v.estimated_working_hrs} Hrs</td>
								<td>{if $v.actual_working_hrs > $v.estimated_working_hrs }<span class="badge badge-pill badge-danger">{$v.actual_working_hrs} Hrs</span>{elseif $v.actual_working_hrs <= $v.estimated_working_hrs}<span class="badge badge-pill badge-success">{$v.actual_working_hrs} Hrs</span>{/if}</td>

								<td>{$v.project_name}</td>
								<td>
									Admin: {if $v.admin_status == 'pending'}<span class="badge badge-pill badge-warning">Pedning</span>{elseif $v.admin_status == 'reject'}<span class="badge badge-pill badge-danger">Rejected</span>{elseif $v.admin_status == 'confirm'}<span class="badge badge-pill badge-success">Approved</span>{/if}

									<span class="clearfix"></span>

									Cust: {if $v.customer_status == 'pending'}<span class="badge badge-pill badge-warning">Pedning</span>{elseif $v.customer_status == 'reject'}<span class="badge badge-pill badge-danger">Rejected</span>{elseif $v.customer_status == 'confirm'}<span class="badge badge-pill badge-success">Approved</span>{/if}
								</td>
								
								


								<td class="td-actions text-right ">

									<a rel="tooltip" title="Edit" href="javascript:edit_job_order('{$v.enc_id}')" class="btn btn-dribbble btn-link"><i class="material-icons">edit</i></i></a><br>

									<a rel="tooltip" title="View" href="{base_url(log_user_type())}/report/order-details/{$v.enc_id}" class="btn btn-info btn-link" target="_blank"><i class="material-icons">visibility</i></i></a>

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
							<i class="fa fa-exclamation"> No Job Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
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
				document.location.href = '{base_url(log_user_type())}' + "/jobs/edit-job/"+id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});
	}

	
	$('.customer_ajax').select2({
		placeholder: 'Select Customer',
		ajax: {
			url:'{base_url(log_user_type())}/autocomplete/customer_ajax',

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

</script>
{/block}
