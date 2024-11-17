{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" />  
*}<link href="{assets_url()}plugins/DataTables/datatables.min.css" rel="stylesheet" />
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">

{/block}

{block body}

<div class="row">
<div class="col-md-12">
<div class="card"> 
	<div class="card-header card-header-rose card-header-text">
		<div class="card-icon">
			<i class="material-icons">library_books</i>
		</div>
		<h4 class="card-title">Package Details</h4>
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
														<label class="bmd-label-floating">Job order ID
														</label>
														<input type="number" class="form-control" id="order_id" name="order_id" {if $id} value="{$jobs['order_id']}" {else} value="{set_value('order_id')}" {/if} required="true" autocomplete="Off" readonly="">{form_error("order_id")}
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
									<button class="btn btn-primary pull-right" type="submit" id="update_dept_job" name="update_dept_job" value="update_dept_job">
										Update <i class="fa fa-arrow-circle-right"></i>
									</button>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" > 

							<div class="table-responsive">
								<table class="table table-hover" id="sample-table-1">
									<thead class="bg-light text-warning">
										<tr>
											<th>Department</th>
											<th>Short Desc</th>
											<th>Order Desc</th> 
											<th>Estimated <br>Working Hours</th>  
											<th>Work Status</th>   
											<th>Action</th>
										</tr>
									</thead> 
									<tbody>
										{foreach $jobs['department'] as $d}
										<tr>
											<td>{counter}. {$d.department_name}</td>
											<td>{$d.short_description}</td>
											<td>{$d.order_description}</td>
											<td>{$d.estimated_working_hrs} Hrs</td>
											<td>{if $d.work_status=='finished'}{$class="badge-success"}
												{elseif $d.work_status=='pending'}
												{$class="badge-warning"}
												{/if}
												<span class="badge badge-pill {$class}">
												{$d.work_status}
						
											</span>
												
											
											<!-- {if $jobs['admin_status']=='pending'}{$class="badge-warning"}{elseif $jobs['admin_status']=='rejected'}{$class="badge-alert"}{else}{$class="badge-success"}{/if}
												Admin: <span class="badge badge-pill {$class}">
													{$jobs['admin_status']}
												</span>

												<span class="clearfix"></span>
												{if $jobs['customer_status']=='pending'}{$c_class="badge-warning"}{elseif $jobs['customer_status']=='rejected'}{$c_class="badge-alert"}{else}{$c_class="badge-success"}{/if}
												Customer: <span class="badge badge-pill {$c_class}">{$jobs['customer_status']}</span>
 -->

											</td>
											<td><a rel="tooltip" title="Delete" href="javascript:delete_department_job('{$enc_id}','{$d.enc_id}')" class="btn btn-link btn-danger btn-sm"><i class="material-icons">delete</i></a>
											<a rel="tooltip" title="Edit" href="javascript:edit_department_job('{$enc_id}','{$d.enc_id}')" class="btn btn-link btn-dribbble btn-sm"><i class="material-icons">edit</i></i></a></td>

										</tr>
										{/foreach}
									</tbody>
								</table>

							</div> 
						</div>
					</div>
				</div>
				{form_close()} 
				<div class="card-collapse">
					<div class="card-header" role="tab" id="headingTwo">
						<h5 class="mb-0">
							<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								ADD DEPARTMENT JOBS
								<i class="material-icons">keyboard_arrow_down</i>
							</a>
						</h5>
					</div>
					<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
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
							<button type="button"  name="items_update" class="btn btn-primary items_update" value="save_exit" > Save & Exit</button>

						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
</div>
</div>



{/block}

{block footer} 
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>

<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>

<script type="text/javascript">

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
	var btnVal = $(this).val();

	var form_data = table
	.rows()
	.data();

	$.ajax({
		type:'POST',
		url:"{base_url('admin/jobs/save-departments')}",
		data: { id: '{$enc_id}', data: JSON.stringify(form_data.toArray()) } ,
		dataType:'json',
	})
	.done(function( data ) { 

		url  = '{base_url()}'+'admin/jobs/edit-job/{$enc_id}';
		if(data.success){

			(btnVal == 'save') ? (document.location.href = url) : document.location.href = '{base_url('admin/jobs/job-list')}';

		}else{
			(btnVal == 'save') ? (document.location.href = url) : document.location.href = '{base_url('admin/jobs/job-list')}';
		}
	}); 

});

$("#qty").keypress(function (e) {
	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
		return false;
	}
});

} );
$('.project_ajax').select2({

placeholder: 'Select a project',
ajax: {
	url:'{base_url(log_user_type())}/jobs/project_ajax',
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
});


function remove_package_item(id, package_id)
{
swal({
	title:'{lang('text_are_you_sure')}',
	text:"You Will not recover",
	type:"warning",
	showCancelButton:true,
	confirmButtonColor:"#DD6B55", 
	confirmButtonText: '{lang('text_yes_delete_it')}',
	cancelButtonText: '{lang('text_no_cancel_please')}',
	closeOnConfirm: false,
	closeOnCancel: false
},
function (isConfirm) {
	if (isConfirm) {
		document.location.href = '{base_url()}' +"admin/packages/remove-package-item/"+id+'/'+package_id; 
	} else {
		swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
	}
});

}
function edit_department_job(id,dept_id)
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
					document.location.href = '{base_url()}' + "admin/jobs/edit_dept_job/"+id+"/"+dept_id; 
				} else {
					swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
				}
			});
		}		
function delete_department_job(id,dept_id)
{

swal({
	title:'{lang('text_are_you_sure')}',
	text:"You Will not recover",
	type:"warning",
	showCancelButton:true,
	confirmButtonColor:"#DD6B55", 
	confirmButtonText: '{lang('text_yes_delete_it')}',
	cancelButtonText: '{lang('text_no_cancel_please')}',
	closeOnConfirm: false,
	closeOnCancel: false
},
function (isConfirm) {
	if (isConfirm) {
		document.location.href = '{base_url()}' +"admin/jobs/delete-department-job/"+id+"/"+dept_id; 
	} else {
		swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
	}
});

}
$(document).ready(function() {
setFormValidation('.ValidateForm'); 
});
</script>


{/block}
