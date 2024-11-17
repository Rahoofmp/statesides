{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />

{/block}

{block body}
<div class="row">
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-header card-header-rose card-header-text">
				<div class="card-icon">
					<i class="material-icons">library_books</i>
				</div>
				<h4 class="card-title">Create Job Order</h4>
			</div>
			<div class="card-body">
				<div id="accordion" role="tablist">
					{form_open('','id="file_form" name="file_form" class="form-add-project ValidateForm" enctype="multipart/form-data"')}
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingOne">
							<h5 class="mb-0">
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
									JOB ORDER
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
							<div class="row">
								<div class="col-md-12">
									<div class="card">
										<div class="card-body">
											<div class="row">
												<div class="col-lg-6">
													<div class="input-group form-control-lg">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="material-icons">grading</i>
															</span>
														</div>
														<div class="col-sm-10">
															<div class="form-group">
																<label class="bmd-label-floating">
																	Job order ID
																</label>
																<input type="number" class="form-control" id="order_id" name="order_id" {if $id} value="{$jobs['order_id']}" {else} value="{set_value('order_id')}" {/if} required="true" autocomplete="Off">
																{form_error("order_id")}
															</div>
														</div>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="input-group form-control-lg">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="material-icons">grading</i>
															</span>
														</div>
														<div class="col-sm-10">
															<div class="form-group">
																<label class="bmd-label-floating">
																	Job order name
																</label>
																<input type="text" class="form-control" id="name" name="name" {if $id} value="{$jobs['name']}" {else} value="{set_value('name')}" {/if} required="true" autocomplete="Off">
																{form_error("name")}
															</div>
														</div>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="input-group form-control-lg">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="material-icons">person</i>
															</span>
														</div>
														<div class="col-sm-10">
															<div class="form-group">
																<label class="bmd-label-floating">
																	Customer
																</label>
																<select id="customer_id" name="customer_id" class="customer_ajax form-control" >
																	{if $id}
																	{if $jobs['customer_id']}
																	<option value="{$jobs['customer_id']}">{$jobs['customer_name']}</option>
																	{/if}
																	{/if}
																</select> 
																{form_error("customer_name")}
															</div>
														</div>
													</div>
												</div>

												<div class="col-lg-6">
													<div class="input-group form-control-lg">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="material-icons">date_range</i>
															</span>
														</div>
														<div class="col-sm-10">
															<div class="form-group">
																<label class="bmd-label-floating"> Delivery requested date </label>

																<input type="text" id="requested_date" class="form-control datepicker" name="requested_date" {if $id} value="{$jobs['requested_date']}"{else}value="{set_value('requested_date')}"{/if} required="true" autocomplete="Off">
																{form_error("requested_date")} 
															</div>
														</div>
													</div>
												</div>
											</div>

											<div class="row">

												<div class="col-lg-6">
													<div class="input-group form-control-lg">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="material-icons">person</i>
															</span>
														</div>
														<div class="col-sm-10">
															<div class="form-group">
																<label class="bmd-label-floating">
																	Project
																</label>
																<select id="project_id" name="project_id" class=" form-control project_ajax">

																	{if $id}
																	{if $jobs['project_id']}
																	<option value="{$jobs['project_id']}">{$jobs['project_name']}</option>
																	{/if}
																	{/if}
																</select> 
																{form_error("project_id")}
															</div>
														</div>
													</div>
												</div>
											</div>
											{if $id}
											<input type="submit" name="update_dept_job" class="btn btn-primary pull-right" value="Update">
											<button class="btn btn-primary pull-right" type="submit" id="update_dept_job" name="update_dept_job" value="update_dept_job">
												Update <i class="fa fa-arrow-circle-right"></i>
											</button>
											{/if}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingTwo">
							<h5 class="mb-0">
								<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									{if $id}
									EDIT DEPARTMENT JOBS
									{else}
									ADD DEPARTMENT JOBS
									{/if}
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
							<div class="row">
								<div class="col-md-12" > 
										<div class="col-md-12 clone-div" >
											<div class="card">
												<div class="card-body">
													<div class="col-sm-12" >
														<h5>DEPARTMENT JOB <span class="dept-job">1</span></h5>
													</div>
													<div class="row">
														<div class="col-lg-6">
															<div class="input-group form-control-lg">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="material-icons">grading</i>
																	</span>
																</div>
																<div class="col-sm-10">
																	<div class="form-group">
																		<select id="department_id_1" name="department_id[]" class=" form-control">
																			<option value="" disabled="" selected="">Select Department</option>
																			{foreach $departments as $d}
																			<option value="{$d.id}">{$d.name}</option>
																			{/foreach}


																		</select> 
																		{* {form_error("department_id1")} *}
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="input-group form-control-lg">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="material-icons">grading</i>
																	</span>
																</div>
																<div class="col-sm-10">
																	<div class="form-group">
																		<label class="bmd-label-floating">
																			job order short description
																		</label>
																		<input type="text" class="form-control" id="short_description_1" name="short_description[]" required="true" autocomplete="Off">
																		{* {form_error("short_description1")} *}
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-12">
															<div class="input-group form-control-lg">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="material-icons">grading</i>
																	</span>
																</div>
																<div class="col-sm-11">
																	<div class="form-group">
																		<label class="bmd-label-floating">
																			job order description
																		</label>
																		<textarea class="form-control" name="order_description[]" id="order_description_1"></textarea>

																		{* {form_error("order_description1")} *}
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="input-group form-control-lg">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="material-icons">grading</i>
																	</span>
																</div>
																<div class="col-sm-10">
																	<div class="form-group">
																		<label class="bmd-label-floating">
																			estimated working hours
																		</label>
																		<input type="text" class="form-control estimatedWorkingHrs" id="estimatedWorkingHrs_1" name="estimated_working_hrs[]" required="true" autocomplete="Off">
																		{* {form_error("estimated_working_hrs1")} *}
																	</div>
																</div>
															</div>
														</div>

													</div>
												</div>
											</div>
										</div>

										<hr>
										<div class="more_dept"></div>
										{if !$id}
										<div class="card-footer text-center" id="" >
											<div class="form-check mr-auto">
											</div>
											<span class="btn btn-info col-lg-6" type="submit" id="add_more_dept" style="text-align:center;">
												Add More Departments <i class="fa fa-plus-circle"></i>
											</span>
										</div>
										{/if}
										<input type="hidden" name="dept_counter" id="dept_counter" value="1">
										<div class="card-footer text-right">
											<div class="form-check mr-auto">
											</div>
											{if $id}
											<button class="btn btn-primary pull-right" type="submit" id="add_dept" name="add_dept" value="add_dept">
												Add <i class="fa fa-arrow-circle-right"></i>
											</button>
											{else}	
											<button class="btn btn-primary pull-right" type="submit" id="add_project" name="add_job" value="add_project">
												Add Jobs <i class="fa fa-arrow-circle-right"></i>
											</button>
											{/if}			
										</div>
										{if $id}
										<table id="example" class="display" style="width:100%">
											<thead>
												<tr>
													<th>No.</th>
													<th>Department</th>
													<th>Short Description</th>
													<th>Order Description</th> 
													<th>Estimated Working Hours</th> 
													<th>Actual Working Hours</th> 
													<th>Difference</th> 
													<th>Admin Status</th> 
													<th>Customer Status</th> 
													<th>Work Status</th> 
													<th>Action</th> 
												</tr>
											</thead> 
											<tbody>
												{foreach $jobs['department'] as $d}
												<tr>
													<td>{counter}</td>
													<td>{$d.department_name}</td>
													<td>{$d.short_description}</td>
													<td>{$d.order_description}</td>
													<td>{$d.estimated_working_hrs}</td>
													<td>{$d.actual_working_hrs}</td>
													<td>{$d.time_difference}</td>
													<td>{$d.admin_status}</td>
													<td>{$d.customer_status}</td>
													<td>{$d.work_status}</td>
													<td></td>
												</tr>
												{/foreach}
											</tbody>
										</table>
										{/if} 
								</div>
							</div>
						</div>
					</div>
					{form_close()}
				</div>
			</div>
		</div>
	</div>
</div>


{/block}

{block footer} 
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
<script src="{assets_url('js/confirm.js')}"></script>
<script src="{assets_url('js/page-js/settings.js')}"></script>
<script src="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}"></script>
<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script>
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});

</script>
<script type="text/javascript">

	$(document).ready(function(){ 

		$('.customer_ajax').select2({

			placeholder: 'Select a customer',
			ajax: {
				url:'{base_url()}admin/customer/customer_ajax',

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

	var customer_id = $('#customer_id');
	customer_id.change( function(){  
		$('#project_id').val(null).trigger('change');
	});

	function get_department(){
		var options='';
		$.ajax({
			url:'{base_url(log_user_type())}/autocomplete/department_ajax',
			type: 'post',
			dataType: 'json',
			success:function(response){

				$.each(response, function(key, value){
					options += '<option value="'+value.id+'">'+value.name+'</option>';
				});  
				
			}
		});

	}
	$(document).ready(function(){ 
		var projectSelect=$('#project_id');
		$('.project_ajax').select2({

			placeholder: 'Select a project',
			ajax: {
				url:'{base_url(log_user_type())}/autocomplete/customer_project_ajax',
				data: function (params) {

					var searchString = $( "select#customer_id option:checked" ).val() ;

					var query = {
						customer_id: searchString,
						type: 'public'
					}
					return query;
				},
				type: 'post',
				dataType: 'json',
				delay:250,
				processResults: function(data) {
					var options='';
					$.each(data, function(key, value){
						options += '<option value="'+value.id+'">'+value.project_name+'</option>';
					})  
					projectSelect.append(options).trigger('change');
				}
			},
			cache: true

		});


	}); 
	var counter=1;


	$("#add_more_dept").on('click', function(e) {

		  // Selecting last id 
		  var estimated_working_hrs_id = $('.clone-div .estimatedWorkingHrs').last().attr('id');
		  var split_id = estimated_working_hrs_id.split('_');
		  console.log(split_id)
		  var index = Number(split_id[1]) + 1;

		  var newel = $(".clone-div:last").clone(true);
		  $(newel).find('.estimatedWorkingHrs').attr("id","estimatedWorkingHrs_"+index);

		  $(newel).find('.dept-job').html(index); 

		  $(newel).insertAfter("div.clone-div:last");

		// counter=counter+1;
		// var options='';
		// var append_dept='';
		// $.ajax({
		// 	url:'{base_url()}admin/jobs/department_ajax',
		// 	type: 'post',
		// 	dataType: 'json',
		// 	success:function(response){

		// 		$.each(response, function(key, value){
		// 			options += '<option value="'+value.id+'">'+value.name+'</option>';
		// 		});  

		// 		append_dept +='<div class="col-md-12" ><div class="card"><div class="card-body"><div class="col-sm-12"><h5>DEPARTMENT JOB '+counter+'</h5></div><div class="row"><div class="col-lg-6"><div class="input-group form-control-lg"><div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">grading</i></span></div><div class="col-sm-10"><div class="form-group"><select id="department_id'+counter+'" name="department_id'+counter+'" class=" form-control">'+options+ '</select></div></div></div></div><div class="col-lg-6"><div class="input-group form-control-lg"><div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">grading</i></span></div><div class="col-sm-10"><div class="form-group"><label class="bmd-label-floating">job order short description	</label><input type="text" class="form-control" id="short_description" name="short_description'+counter+'" required="true" autocomplete="Off"></div></div></div></div><div class="col-lg-12"><div class="input-group form-control-lg"><div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">grading</i></span></div><div class="col-sm-11"><div class="form-group"><label class="bmd-label-floating">job order description</label><textarea class="form-control" name="order_description'+counter+'" id="order_description'+counter+'"></textarea></div></div></div></div><div class="col-lg-6"><div class="input-group form-control-lg"><div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">grading</i></span></div><div class="col-sm-10"><div class="form-group"><label class="bmd-label-floating">estimated working hours</label><input type="text" class="form-control" id="estimated_working_hrs'+counter+'" name="estimated_working_hrs'+counter+'"required="true" autocomplete="Off"></div></div></div></div></div></div></div></div></div></div><hr>';
		// 		$("#more_dept").append(append_dept);
		// 		$("#dept_counter").val(counter);
		// 	}
		// });
	})
</script>

{/block}
