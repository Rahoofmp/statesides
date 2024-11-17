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
																<input type="text"  class="form-control"name="order_id" id="order_id" value="{$order_id}"  readonly="true">
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
																<select id="project_id" name="project_id" class=" form-control project_ajax" >

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

					{* <div class="card-collapse">
						<div class="card-header" role="tab" id="headingTwo">
							<h5 class="mb-0">
								<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">

									ADD DEPARTMENT JOBS

									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
							<div class="row">
								<div class="col-md-12" >
									<div class="card">
										<div class="col-md-12" >
											<div class="card">
												<div class="card-body">
													<div class="col-sm-12" >
														<h5>DEPARTMENT JOB 1</h5>
													</div>
													<div class="row">
														<div class="col-lg-6">
															<div class="input-group form-control-lg">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="material-icons">local_fire_department</i>
																	</span>
																</div>
																<div class="col-sm-10">
																	<div class="form-group">
																		<select id="department_id1" name="department_id1" class=" form-control department_select">
																			<option value="" disabled="" selected="">Select Department</option>
																			{foreach $departments as $d}
																			<option value="{$d.id}">{$d.name}</option>
																			{/foreach}


																		</select> 
																		{form_error("department_id1")}
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="input-group form-control-lg">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="material-icons">description</i>
																	</span>
																</div>
																<div class="col-sm-10">
																	<div class="form-group">
																		<label class="bmd-label-floating">
																			Job order short description
																		</label>
																		<input type="text" class="form-control" id="short_description1" name="short_description1" {if $id} value="{$jobs['short_description']}" {else} value="{set_value('short_description')}" {/if} required="true" autocomplete="Off">
																		{form_error("short_description1")}
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-12">
															<div class="input-group form-control-lg">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="material-icons">wb_iridescent</i>
																	</span>
																</div>
																<div class="col-sm-11">
																	<div class="form-group">
																		<label class="bmd-label-floating">
																			Job order description
																		</label>
																		<textarea class="form-control" name="order_description1" id="order_description1"></textarea>

																		{form_error("order_description1")}
																	</div>
																</div>
															</div>
														</div>
														<div class="col-lg-6">
															<div class="input-group form-control-lg">
																<div class="input-group-prepend">
																	<span class="input-group-text"><i class="material-icons">schedule</i>
																	</span>
																</div>
																<div class="col-sm-10">
																	<div class="form-group">
																		<label class="bmd-label-floating">
																			Estimated Working times (in Min)
																		</label>
																		<input type="text" class="form-control" id="estimated_working_hrs1" name="estimated_working_hrs1" {if $id} value="{$jobs['estimated_working_hrs']}" {else} value="{set_value('estimated_working_hrs')}" {/if} required="true" autocomplete="Off">
																		{form_error("estimated_working_hrs1")}
																	</div>
																</div>
															</div>
														</div>
			<!-- <div class="col-lg-6">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">grading</i>
						</span>
					</div>
					<div class="col-sm-10">
						<div class="form-group">
							<label class="bmd-label-floating">
								actual working hours
							</label>
							<input type="text" class="form-control" id="actual_working_hrs1" name="actual_working_hrs1" {if $id} value="{$jobs['actual_working_hrs']}" {else} value="{set_value('actual_working_hrs')}" {/if} required="true" autocomplete="Off">
							{form_error("actual_working_hrs1")}
						</div>
					</div>
				</div>
			</div> -->

		</div>
	</div>
</div>
</div>
<hr>
<div id="more_dept"></div>
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
</div> *}
<div class="card-collapse">
	<div class="card-header" role="tab" id="headingTwo">
		<h5 class="mb-0">
			<a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">

				ADD DEPARTMENT JOBS

				<i class="material-icons">keyboard_arrow_down</i>
			</a>
		</h5>
	</div>
	<div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
		<div class="card-body">  
			{form_open('','name="item_add"  id="add-items-form" class="form-login"')}

			<div class="col-sm-12 product-items">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<select id="department_id" name="department_id" class=" form-control" required="">
								<option value='' disabled="" selected>Select Department</option>
								{foreach $departments as $d}
								<option value="{$d.id}"  {if in_array($d.id, $dept_ids)} disabled=""{/if}>{$d.name}</option>
								{/foreach}


							</select> 
							{form_error("department_id")}
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<input type="text" class="form-control" id="short_description" name="short_description" value="{set_value('short_description')}"  required="true" autocomplete="Off" placeholder="Short Description">
							{form_error("short_description")}
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<textarea class="form-control" name="order_description" id="order_description" placeholder="Description" rows="1"></textarea>

							{form_error("order_description")} 
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group">
							<input type="number" class="form-control" id="estimated_working_hrs" name="estimated_working_hrs"  required="true" autocomplete="Off" placeholder="Estimated Working Hour" >
							{form_error("estimated_working_hrs")}
						</div>
					</div>
				</div>
			</div>

			{form_close()}


			<table id="example" class="display" style="width:100%">
				<thead>
					<tr>
						<th>No.</th>
						<th>Department</th>
						<th>Short Description</th>
						<th>Description</th> 
						<th>Estimated Working Hrs</th> 
						<th>Action</th> 
					</tr>
				</thead> 
			</table>

			<button type="button"  name="items_update" class="btn btn-info items_update" value="save" > Save </button>
			<!-- <button type="button"  name="items_update" class="btn btn-primary items_update" value="save_exit" > Save & Exit</button> -->

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
	$(document).ready(function() {

		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				addRow(); 
				return false;
			}
		});

		var table = $('#example').DataTable({ searching: false, paging: false, info: false,responsive: true});
		var counter = 1;

		$('#example').on('click', '.remove', function() {

			var dept_id=$(this).attr('data-dept');
			var row = $(this).parents('tr');

			if ($(row).hasClass('child')) {
				table.row($(row).prev('tr')).remove().draw();
			} else {
				table
				.row($(this).parents('tr'))
				.remove()
				.draw();
			}
			$("#department_id option[value*='"+dept_id+"']").prop('disabled',false);
		});


		function addRow () {
			var validFrom = true;
			$(".product-items :input").each(function(){
				var input = $(this); 
				if(input.val() == ''){
					input.focus();
					validFrom = false;
					return false;
				}
			});

			if(validFrom == true){
				var dept=$('#department_id option:selected').val();
				if (dept=='') {
					validFrom = false;
					return false;

				}
				table.row.add( [
					counter,
					$('#department_id option:selected').text(),
					$('#short_description').val(),
					$('#order_description').val(),
					$('#estimated_working_hrs').val(),

					'<button type="button" class="btn btn-danger btn-sm remove" data-dept="'+dept+'"><i class="material-icons">delete</i></button>'
					] ).draw( false ).node();
				counter++;
				$('.product-items input').val(''); 
				$('.product-items select').val(''); 
				$('.product-items textarea').val(''); 
				$('.product-items #department_id').focus();
				$("#department_id option[value*='"+dept+"']").prop('disabled',true);
			}

		} ;

		$('.items_update').on('click', function(){


			var name= $("#name").val();
			var order_id= $("#order_id").val();
			var customer_id= $("#customer_id").val();
			var requested_date= $("#requested_date").val();
			var project_id= $("#project_id").val();

			var btnVal = $(this).val();

			var form_data = table
			.rows()
			.data();

			$.ajax({
				type:'POST',
				url:"{base_url('admin/jobs/save-departments-jobs')}",
				data: { id: '{$enc_id}',  data: JSON.stringify(form_data.toArray()), name: name, order_id: order_id, customer_id: customer_id, requested_date: requested_date, project_id: project_id, } ,
				dataType:'json',
			})
			.done(function( response ) { 

				url  = "{base_url('admin/jobs/create-job')}";
				if(response.success){
					(btnVal == 'save') ? (document.location.href = url) : document.location.href = "{base_url('admin/jobs/create-job')}";

				}else{
					alert("failed");
					(btnVal == 'save') ? (document.location.href = url) : document.location.href = "{base_url('admin/jobs/create-job')}";
				}
			}); 

		});

		$("#qty").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});

	} );

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

		$('#project_id').removeAttr('disabled');
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
				url:'{base_url()}admin/jobs/project_ajax',
				data: function (params) {

					var customer_id = $( "select#customer_id option:checked" ).val() ;
					var searchString = $( "select#project_id option:checked" ).val() ;

					var query = {
						customer_id: customer_id,
						project_id: searchString,
						type: 'public'
					}
					return query;
				},
				type:'post',
				dataType: 'json',
				delay: 250,
				processResults: function (data) { 
					return {
						results: data
					};
				},
				cache: true
			}
		});				


	});

	var counter=1;


	var selected_dept=[];

	$("#add_more_dept").on('click', function(e) {
		var selected = $('#department_id'+counter).val();
		selected_dept.push(selected);
		counter=counter+1;
		var options='';
		var append_dept='';
		$.ajax({
			url:'{base_url(log_user_type())}/autocomplete/department_ajax',
			data: { selected_dept:selected_dept },
			type: 'post',
			dataType: 'json',
			success:function(response){
				$.each(response, function(key, value){
					var idx = $.inArray(value.id, selected_dept);
					if (idx == -1) {
						options += '<option value="'+value.id+'">'+value.name+'</option>';
					} else {
						options += '<option value="'+value.id+'" disabled>'+value.name+'</option>';
					}
				// options += '<option value="'+value.id+'">'+value.name+'</option>';
			});  

				append_dept +='<div class="col-md-12" ><div class="card"><div class="card-body"><div class="col-sm-12"><h5>DEPARTMENT JOB '+counter+'</h5></div><div class="row"><div class="col-lg-6"><div class="input-group form-control-lg"><div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">local_fire_department</i></span></div><div class="col-sm-10"><div class="form-group"><select id="department_id'+counter+'" name="department_id'+counter+'" class=" form-control department_select">'+options+ '</select></div></div></div></div><div class="col-lg-6"><div class="input-group form-control-lg"><div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">description</i></span></div><div class="col-sm-10"><div class="form-group"><label class="bmd-label-floating">Job order short description	</label><input type="text" class="form-control" id="short_description" name="short_description'+counter+'" required="true" autocomplete="Off"></div></div></div></div><div class="col-lg-12"><div class="input-group form-control-lg"><div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">wb_iridescent</i></span></div><div class="col-sm-11"><div class="form-group"><label class="bmd-label-floating">Job order description</label><textarea class="form-control" name="order_description'+counter+'" id="order_description'+counter+'"></textarea></div></div></div></div><div class="col-lg-6"><div class="input-group form-control-lg"><div class="input-group-prepend"><span class="input-group-text"><i class="material-icons">schedule</i></span></div><div class="col-sm-10"><div class="form-group"><label class="bmd-label-floating">Estimated Working times (in Min)</label><input type="text" class="form-control" id="estimated_working_hrs'+counter+'" name="estimated_working_hrs'+counter+'"required="true" autocomplete="Off"></div></div></div></div></div></div></div></div></div></div><hr>';
				$("#more_dept").append(append_dept);
				$("#dept_counter").val(counter);
			}
		});
	})
	$(document).ready(function() {
		setFormValidation('.ValidateForm'); 
	});
</script>

{/block}
