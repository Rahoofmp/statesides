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
																<input type="number" class="form-control" id="order_id" name="order_id" {if $id} value="{$jobs['order_id']}" {else} value="{set_value('order_id')}" {/if} required="true" autocomplete="Off" readonly="">
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

										</div>
										<div class="card-footer text-right">
											<button class="btn btn-primary pull-right" type="submit" id="update_job" name="update_job" value="update_job">
												Update <i class="fa fa-arrow-circle-right"></i>
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					{form_close()}
					{form_open('','id="file_form" name="file_form" class="form-add-project ValidateForm" enctype="multipart/form-data"')}
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingTwo">
							<h5 class="mb-0">
								<a class="collapsed" data-toggle="collapse" href="#collapseTwo" {if $tab=='2'} aria-expanded="true"{else} aria-expanded="false" {/if} aria-controls="collapseTwo">
									EDIT DEPARTMENT JOB 
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseTwo" {if $tab=='2'}class="collapse show"{else} class="collapse" {/if} role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
							<div class="row">
								<div class="col-md-12" >
									<div class="card">
										{foreach from=$jobs['department'] item=item }
										<div class="card-body">
											<div class="col-sm-12" >
												<h5>DEPARTMENT JOB</h5>
											</div>

											<div class="row"> 
												<div class="col-lg-12">
													<div class="input-group form-control-lg">
														<div class="input-group-prepend">
															<span class="input-group-text"><i class="material-icons">grading</i>
															</span>
														</div>
														<div class="col-sm-11">
															<div class="form-group">
																<label class="bmd-label-floating">
																	job order short description
																</label>
																<input type="text" class="form-control" id="short_description" name="short_description" value="{$item['short_description']}"  required="true" autocomplete="Off">
																{form_error("short_description")}
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
																<textarea class="form-control" name="order_description" id="order_description">{$item['order_description']}</textarea>
																{form_error("order_description")}
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
																<input type="text" class="form-control" id="estimated_working_hrs" name="estimated_working_hrs" value="{$item['estimated_working_hrs']}" required="true" autocomplete="Off"><input  class="form-control" id="estimated_workings" name="estimated_working" value="{$item['estimated_working_hrs']}" required="true" autocomplete="Off" type="hidden">
																{form_error("estimated_working_hrs")}
															</div>
														</div>
													</div>
												</div> 
											</div>
										</div>

										{/foreach}
										<input type="hidden" name="dept_counter" id="dept_counter" value="1">
										<div class="card-footer text-right"> 

											<button class="btn btn-primary pull-right" type="submit" id="update_dept_job" name="update_dept_job" value="update_dept_job">
												UPDATE <i class="fa fa-arrow-circle-right"></i>
											</button>

										</div>
										

									</div>
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

</script>

{/block}
