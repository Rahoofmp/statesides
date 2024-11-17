{extends file="layout/base.tpl"}
{block header}  
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<style type="text/css">
@media print {
	.printdiv { display: block !important; }
}
.btn-actions .btn {
	margin: 0;
	padding: 5px;
}
.timeline > li {
	margin-bottom: 0px;
}
.timeline > li > .timeline-badge { 
	font-size: 11px; 
	font-weight: bold;
	padding-top: 7px !important; 
	line-height: 17px;
} 
</style> 
{/block}
{block body} 

{if $job_order_id}
{if $department_job_detail['actual_working_hrs']>$department_job_detail['estimated_working_hrs']}
<div class="alert alert-info alert-with-icon" data-notify="container">
	
	<i class="material-icons" data-notify="icon">notifications</i>
	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
		<i class="material-icons">close</i>
	</button>
	<span data-notify="message">The Spent Time is greater than Estimated Working Time By {$department_job_detail['actual_working_hrs'] - $department_job_detail['estimated_working_hrs']} Hrs</span>
	
</div>
{/if}
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
									<select id="job_order_name" name="job_order_name" class="orderid_ajax form-control" >
										{if $job_order_name}
										<option value="{$job_order_name}">{$job_order_name}</option>
										{/if} 
									</select> 
								</div>
								{form_error('job_order_name')}
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
<div class="row">
	
	<div class="col-md-6">
		<div class="row">
			{if $department_job_detail['work_status']!='finished'}

			<div class="col-md-12">
				{form_open('','id="fromDayProgress"')}
				<div class="card">
					<div class="card-header card-header-rose card-header-icon">
						<div class="card-icon">
							<span class="material-icons">date_range</span>
						</div>
						<h4 class="card-title">Day Progress</h4>
					</div>
					<div class="card-body ">
						<div class="form-group">
							<label for="exampleEmail" class="bmd-label-floating"> Date *</label>                   
							<input type="text" readonly class="form-control" value="{$smarty.now|date_format:"%Y-%m-%d"}">
						</div>
						<div class="form-group">
							<label for="today_status" class="bmd-label-floating"> Work Description *</label>
							<textarea class="form-control" required name="today_status" required></textarea>
							{form_error('today_status')}
						</div>
						<div class="form-group">
							<label for="employees_worked" class="bmd-label-floating"> Employees Worked *</label>
							<textarea class="form-control" required name="employees_worked" required></textarea>
							{form_error('employees_worked')}
						</div>
						<div class="form-group">
							<label for="worked_time" class="bmd-label-floating"> Worked Time *</label>
							<input class="form-control" type="text" name="worked_time" number="true" required="true" aria-required="true" id="worked_time" > 
							{form_error('worked_time')}
						</div> 
					</div>
					<input type="hidden" class="form-control" required name="job_order_name" value="{$job_order_name}">
					{form_error('job_order_name')}
					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary" name="today_progress" value="today_progress">Add</button>
					</div>
				</div>
				{form_close()}
			</div>
			{/if}
			<div class="col-md-12">
				<div class="card">
					<div class="card-header card-header-rose card-header-icon">

						<h4 class="card-title">JOB Details</h4>
					</div>
					<div class="card-body ">
						<div class="table-responsive">
							<table class="table table-hover"> 
								<tbody> 
									<tr>
										<td >Order ID: </td>
										<td>{$job_details['order_id']}</td> 
									</tr>
									<tr>
										<td >Name: </td>
										<td>{$job_details['name']}</td> 
									</tr>
									<tr>
										<td>Created Date: </td>
										<td>{$job_details['date']|date_format:"%Y-%m-%d"}</td> 
									</tr>
									<tr>
										<td>Delivery Scheduled Date: </td>
										<td>{$job_details['requested_date']|date_format:"%Y-%m-%d"}</td> 
									</tr>
									<tr>
										<td>Customer: </td>
										<td>{$job_details['customer_username']}</td> 
									</tr>
									<tr>
										<td>Project: </td>
										<td>{$job_details['project_name']}</td> 
									</tr> 
									<tr>
										<td>Estimate Time: </td>
										<td> <h6>{$department_job_detail['estimated_working_hrs']}  Hrs</h6></td> 
									</tr>
									<tr>
										<td>Spent Time: </td>
										<td><h6>{$department_job_detail['actual_working_hrs']} Hrs</h6></td> 
									</tr> 
									<tr>
										<td>Work Status: </td>
										<td><h6>{$department_job_detail['work_status']} </h6></td> 
									</tr> 
								</tbody>
							</table>
						</div>
					</div> 
				</div>
			</div>
			{if $department_job_detail['work_status']!='finished'}
			<div class="col-md-12">
				{form_open('','id="fromDayProgress"')}
				<div class="card">
					<div class="card-header card-header-info card-header-icon">
						<div class="card-icon">
							<span class="material-icons">update</span>
						</div>
						<h4 class="card-title">Change Department Job status</h4>
					</div>
					<div class="card-body ">
						<input type="hidden" name="job_order_id" value="{$job_order_id}">
						<div class="form-group">
							<select class="selectpicker col-12"  data-style="select-with-transition" title="Status" id="status" name="status" required="" >

								<option value="finished">Finished</option>

							</select>
						</div>  
					</div>
					{form_error('department_id')}
					<div class="card-footer text-right">
						<button type="submit" class="btn btn-primary" name="change_status" value="today_progress">Update</button>
					</div>
				</div>
				{form_close()}

			</div>
			{/if}
		</div>
	</div>
	<div class="col-md-6">
		<h4> Day By Day Progress </h4>
		<hr>
		{if $job_day_progress}
		<ul class="timeline timeline-simple">
			{foreach from=$job_day_progress item=item key=key}
			{$index = $key%4}
			<li class="timeline-inverted">
				<div class="timeline-badge {$progress_color[$index]}">
					{$item['date_added']|date_format: "%I:%M"}
					<span class="clearfix"></span>
					{$item['date_added']|date_format: "%p"}
				</div>  
				<div class="timeline-panel">
					<div class="timeline-heading">
						<span class="badge badge-pill badge-{$progress_color[$index]}">{$item['date_added']|date_format}</span>
					</div>
					<div class="timeline-body text-muted">
						<p ><span class="font-weight-normal">Worked:</span> {$item['employees_worked']}</p>
						<p>{$item['today_status']}</p>
					</div>
					<hr>
					<div class="row"> 
						<h6 class="col-sm-9"> 
							<span class="material-icons" style="font-size: 12px;"> schedule </span> <i>Total Worked </i>: {$item['worked_in_min']} Hrs
						</h6>
						<div class="btn-actions text-right col-sm-3"> 
							<button type="button" rel="tooltip" class="btn btn-info edit-progress btn-link" data-original-title="Edit" title="Edit" data-job_order_id="{$item['job_order_id']}" data-id="{$item['id']}">
								<i class="material-icons">edit</i>
								<div class="ripple-container"></div>
							</button>

							{if $smarty.now|date_format:"%Y-%m-%d" == $item.date_added|date_format:"%Y-%m-%d"}
							<a rel="tooltip" title="Delete" href="javascript:delete_event('/{$item.enc_id}/{$item.enc_job_id}')" class="btn btn-danger btn-link"><i class="material-icons">delete</i></a>{/if}

						</div>
					</div>
				</div>
			</li>
			{/foreach}  
		</ul> 
		
		{if $key == 5}
		<a href="{base_url()}{log_user_type()}/jobs/day-progress-list/{$job_order_id}" target="_blank"><i class="material-icons">local_offer</i> {lang('text_view_more')}</a>
		{/if}

		{else}
		<div class="alert alert-warning alert-with-icon" data-notify="container">
			<i class="material-icons" data-notify="icon">notifications</i>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<i class="material-icons">close</i>
			</button>
			<span data-notify="icon" class="now-ui-icons ui-1_bell-53"></span>
			<span data-notify="message">Day progress not added yet</span>
		</div>
		{/if}
	</div>
</div> 
{/if}
<div class="modal fade" id="empModal" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Day Progress</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">	
			</div> 
		</div>
	</div>
</div>
{/block}
{block footer} 
<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> <script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>
{* 
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script>  *}
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>
<script> 
	$('.edit-progress').click(function(){
		var ID = $(this).data('id');
		var jobOrderId = $(this).data('job_order_id');
		$.ajax({
			url: '{base_url(log_user_type())}/jobs/edit-day-progress',
			type: 'post',
			data: { job_order_id: jobOrderId, progress_id: ID },
			success: function(response){  
				$('.modal-body').html(response.html);
				$('#empModal').modal('show'); 
			}
		});
		$('.modal-body').html('response');
		$('#empModal').modal('show'); 
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
	function setFormValidation(id) {
		$(id).validate({
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-danger');
				$(element).closest('.form-check').removeClass('has-success').addClass('has-danger');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-danger').addClass('has-success');
				$(element).closest('.form-check').removeClass('has-danger').addClass('has-success');
			},
			errorPlacement: function(error, element) {
				$(element).closest('.form-group').append(error);
			},
		});
	}
	$(document).ready(function() { 
		setFormValidation('#fromDayProgress');
	});
</script>
<script type="text/javascript">
	function delete_event(id)
	{
		swal({
			title: 'Are you sure?',
			text: 'do you want to delete ',
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: 'Delete',
			cancelButtonText: 'Cancel Please',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = '{base_url()}' +"dept_supervisor/jobs/delete_day_progres/delete/"+id;  
			} else {
				swal('{lang("text_cancelled")}','{lang("text_cancelled")}', "error");
			}
		});
	}
	$(document).ready(function () {
		$('.status').on('change', function () {
			var id = $(this).attr("data-id");
			if($(this).prop("checked") == true){
				var status='pending';
			}
			else if($(this).prop("checked") == false){
				var status='delivered';
			}
			$.ajax({
				type: "POST",
				url: "{base_url()}admin/project/changeStatus",
				data: { status: status , id : id },
				success: function () {
					$.notify("Updated Successfully", "success");
				}
			})
		});

	});
</script>
{/block}
