{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}"> 
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
{/block}

{block body}

<div class="row">


	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel1">Inactive Lead</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> 
				</div>

				{form_open()}
				<div class="modal-body">
					<div class="form-group">
						<input type="hidden" class="" id="enc_id" name="enc_id"  value="{$customer['enc_customerid']}" >
					</div>

					<div class="form-group">
						<label class="bmd-label-floating">
							Reason
						</label>
						<input type="text" class="form-control" id="reason" name="reason"  required="true" required autocomplete="Off">
						{form_error("reason")}
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="inactive" id="inactive" value="inactive" class="btn btn-secondary" >Inactivate</button>
					
				</div>
				{form_close()}
			</div>
		</div>
	</div>

	<div class="modal fade" id="followUp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel1">Daily Fllow-up Message</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> 
				</div>

				{form_open()}
				<div class="modal-body">
					<div class="form-group">
						<input type="hidden" class="" id="enc_id" name="enc_id"  value="{$customer['enc_customerid']}" >
					</div>

					<div class="form-group">
						<label class="bmd-label-floating">
							Message
						</label>
						<input type="text" required class="form-control" id="message" name="message"  required="true" autocomplete="Off">
						{form_error("message")}
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" name="follow_up" id="follow_up" value="follow_up" class="btn btn-secondary" >Add Message</button>
					
				</div>
				{form_close()}
			</div>
		</div>
	</div>


	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				{if $id}<h4 class="card-title">Edit Customer</h4>{else}<h4 class="card-title">Add Customer</h4>{/if}
			</div>
			<div class="card-body">
				{form_open_multipart('','id="file_form" name="file_form" class="form-add-project ValidateForm needs-validation" enctype="multipart/form-data"')}
				<div class="row">

					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<label for="from_date"> Enquiry Status</label>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Enquiry Status" id="enquiry_status" name="enquiry_status" required="" >
										<option value="lead" {if $customer['enquiry_status'] == 'lead'} selected="" {/if}>Lead</option>
										<option value="customer" {if $customer['enquiry_status'] == 'customer'} selected="" {/if}>Customer</option>
										
									</select> 
									{form_error('gender')}
								</div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="input-group form-control-lg">
								<div class="input-group-prepend ">
									<span class="input-group-text"><i class="material-icons">grading</i>
									</span>
								</div>



							</div>
						</div>
					</div>

					<div class="col-lg-3">
						<div class="input-group-prepend">
							<label for="from_date"></label>
						</div>
						<button class="btn btn-primary pull-right" 
						data-toggle="modal" 
						data-target="#followUp"
						type="button" 
						data-enc-id="{$customer['enc_id']}" 
						>
						follow-up 
						<i class="fa fa-arrow-circle-up"
						></i>
					</button>
				</div>

				<div class="col-lg-3">
					<div class="input-group-prepend">
						<label for="from_date"></label>
					</div>
					<button class="btn btn-primary pull-right" 
					type="button" 
					data-enc-id="{$customer['enc_id']}" 
					data-toggle="modal" 
					data-target="#exampleModal">
					<i class="fa fa-trash"></i>
				</button>
			</div>


			<div class="col-lg-12">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">person</i>
						</span>
					</div>

					<div class="col-sm-11">
						<div class="form-group">
							<label class="bmd-label-floating">
								Source User
							</label>


							<input type="text" class="form-control" id="source_user" name="source_user" disabled=""  value="{$customer['source_user']}"  required="true" autocomplete="Off">
							{form_error("source_user")}
						</div>
					</div>

				</div>
			</div>


			<div class="col-lg-12">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">person</i>
						</span>
					</div>

					<div class="col-sm-11">
						<div class="form-group">
							<label class="bmd-label-floating">
								First Name
							</label>
							<input type="text" class="form-control" id="first_name" name="first_name"  value="{$customer['firstname']}"  required="true" autocomplete="Off">
							{form_error("first_name")}
						</div>
					</div>

				</div>
			</div>

			<div class="col-lg-12">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">person</i>
						</span>
					</div>

					<div class="col-sm-11">
						<div class="form-group">
							<label class="bmd-label-floating">
								Last Name
							</label>
							<input type="text" class="form-control" id="last_name" name="last_name" value="{$customer['lastname']}" required="true" autocomplete="Off">
							{form_error("last_name")}
						</div>
					</div>

				</div>
			</div>
			<div class="col-lg-12">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">call</i>
						</span>
					</div>
					<div class="col-sm-11">
						<div class="form-group">
							<label class="bmd-label-floating"> Mobile </label>
							<input type="text" class="form-control" id="mobile" name="mobile"  value="{$customer['mobile']}" required="true" number="true" autocomplete="Off">
							{form_error("mobile")}
						</div>
					</div>
				</div>
			</div> 

			<div class="col-lg-12">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">email</i>
						</span>
					</div>
					<div class="col-sm-11">
						<div class="form-group">
							<label class="bmd-label-floating"> Email </label>

							<input type="email" id="email" class="form-control " name="email"  value="{$customer['email']}" required="true" autocomplete="Off">
							{form_error("email")} 
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-12">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">person</i>
						</span>
					</div>

					<div class="col-sm-11">
						<div class="form-group">
							<label class="bmd-label-floating">
								Age
							</label>
							<input type="text" class="form-control" id="last_name" name="age"  value="{$customer['age']}"  autocomplete="Off">
							{form_error("last_name")}
						</div>
					</div>

				</div>
			</div>



		</div>

		<div class="row">

			<div class="col-lg-6">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<label for="from_date"> Gender</label>
					</div>
					<div class="col-sm-10">
						<div class="form-group">
							<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Gender" id="gender" name="gender"  >
								<option value="male" {if $customer['gender'] == 'male'} selected="" {/if}>Male</option>
								<option value="femaile" {if $customer['gender'] == 'femaile'} selected="" {/if}>Femaile</option>
								<option value="others" {if $customer['status'] == 'others'} selected="" {/if}>Others</option>
							</select> 
							{form_error('gender')}
						</div>
					</div>
				</div>
			</div>


			<div class="col-lg-6">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">job</i>
						</span>
					</div>
					<div class="col-sm-10">
						<div class="form-group">
							<label class="bmd-label-floating"> Current Job </label>

							<input type="text" id="current_job" class="form-control " name="current_job"  value="{$customer['current_job']}"   autocomplete="Off">
							<input type="hidden" id="advance_amount" class="form-control " name="advance_amount"  value="{$customer['advance']}"  autocomplete="Off">
							<input type="hidden" id="total_amount" class="form-control " name="total_amount"  value="{$customer['total_amount']}"   autocomplete="Off">

							<input type="hidden" id="total_amount" class="form-control " name="salesman_id"  value="{$customer['salesman_id']}"   autocomplete="Off">
							{form_error("current_job")} 
						</div>
					</div>
				</div>
			</div> 

		</div>

		<div class="row">
			<div class="col-lg-6">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="material-icons">date_range</i>
						</span>
					</div>
					<div class="col-sm-10">
						<div class="form-group">
							<label for="from_date"> Date</label>
							<input type="text" id="date" class="form-control datepicker" name="date" value="{$customer['date']}" autocomplete="off">
							{form_error("date")} 
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-6">
				<div class="input-group form-control-lg">
					<div class="input-group-prepend">
						<label for="from_date"> Emmigration</label>

					</span>
				</div>
				<div class="col-sm-10">
					<div class="form-group">
						<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Emmigration" id="emmigration" name="emmigration" 
						>
						<option value="none" {if $customer['immigration_status'] == 'none'} selected="" {/if}>None</option>
						<option value="pending" {if $customer['immigration_status'] == 'pending'} selected="" {/if}>Pending</option>

						<option value="approved" {if $customer['immigration_status'] == 'approved'} selected="" {/if}>Approved</option>

						<option value="rejected" {if $customer['immigration_status'] == 'rejected'} selected="" {/if}>Rejected</option>

					</select> 
					{form_error('gender')}
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">




</div>
</div>

<div class="row">
	<div class=" col-md-3 px-5 ">

		<div class="fileinput fileinput-new text-center" data-provides="fileinput">
			<div class="fileinput-new thumbnail img-circle">
				<!-- <img src="{assets_url('images/leads_data/')}{$customer['sslc_certificate']}" alt="{$user_name}"> -->

				{if {$customer['sslc_certificate']}}
				<a href="{assets_url('images/leads_data/')}{$customer['sslc_certificate']}" target="_blank">
					<i class="fa fa-file"></i> View Document
				</a>
				{/if}


			</div>
			<div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
			<div>
				<span class="btn btn-round btn-info btn-file">
					<span class="fileinput-new">SSLC-Cirtificate</span>
					<span class="fileinput-exists">{lang('change')}</span>
					<input type="file" name="ss_cirtifcate" />
				</span>
				<br />
				<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
			</div>
		</div>
	</div>


	<div class="col-md-3 ">

		<div class="fileinput fileinput-new text-center" data-provides="fileinput">
			<div class="fileinput-new thumbnail img-circle">
				<!-- <img src="{assets_url('images/leads_data/')}{$customer['police_certificate']}" alt="{$user_name}"> -->

				{if {$customer['police_certificate']}}
				<a href="{assets_url('images/leads_data/')}{$customer['police_certificate']}" target="_blank">
					<i class="fa fa-file"></i> View Document
				</a>
				{/if}


			</div>
			<div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
			<div>
				<span class="btn btn-round btn-info btn-file">
					<span class="fileinput-new">Police Clearence</span>
					<span class="fileinput-exists">{lang('change')}</span>
					<input type="file" name="police_clearence" />
				</span>
				<br />
				<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
			</div>
		</div>
	</div>

	<div class="col-md-3 ">

		<div class="fileinput fileinput-new text-center" data-provides="fileinput">
			<div class="fileinput-new thumbnail img-circle">
				<!-- <img src="{assets_url('images/leads_data/')}{$customer['job_cirtificate']}" alt="{$user_name}"> -->

				{if {$customer['job_cirtificate']}}
				<a href="{assets_url('images/leads_data/')}{$customer['job_cirtificate']}" target="_blank">
					<i class="fa fa-file"></i> View Document
				</a>
				{/if}


			</div>
			<div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
			<div>
				<span class="btn btn-round btn-info btn-file">
					<span class="fileinput-new">Job Cirtificate</span>
					<span class="fileinput-exists">{lang('change')}</span>
					<input type="file" name="job_cirtificate" />
				</span>
				<br />
				<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
			</div>
		</div>
	</div>

	<div class="col-md-3 ">

		<div class="fileinput fileinput-new text-center" data-provides="fileinput">
			<div class="fileinput-new thumbnail img-circle">
				<!-- <img src="{assets_url('images/leads_data/')}{$customer['passport_copy']}" alt="{$user_name}"> -->

				{if {$customer['passport_copy']}}
				<a href="{assets_url('images/leads_data/')}{$customer['passport_copy']}" target="_blank">
					<i class="fa fa-file"></i> View Document
				</a>
				{/if}

			</div>
			<div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
			<div>
				<span class="btn btn-round btn-info btn-file">
					<span class="fileinput-new">Passport Copy</span>
					<span class="fileinput-exists">{lang('change')}</span>
					<input type="file" name="passport_copy" />
				</span>
				<br />
				<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
			</div>
		</div>
	</div>

	<div class="col-md-3 px-5 ">

		<div class="fileinput fileinput-new text-center" data-provides="fileinput">
			<div class="fileinput-new thumbnail img-circle">
				<!-- <img src="{assets_url('images/leads_data/')}{$customer['dob_certificate']}" alt="{$user_name}"> -->

				{if {$customer['dob_certificate']}}
				<a href="{assets_url('images/leads_data/')}{$customer['dob_certificate']}" target="_blank">
					<i class="fa fa-file"></i> View Document
				</a>
				{/if}

				
			</div>
			<div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
			<div>
				<span class="btn btn-round btn-info btn-file">
					<span class="fileinput-new">Other Document File</span>
					<span class="fileinput-exists">{lang('change')}</span>
					<input type="file" name="dob_certificate" />
				</span>
				<br />
				<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
			</div>
		</div>
	</div>
</div>


<div class="card-footer text-right">
	<div class="form-check mr-auto">
	</div>

	<button class="btn btn-primary pull-right" type="submit" id="update_customer" name="update_customer" value="update_customer">
		Update Lead <i class="fa fa-arrow-circle-right"></i>
	</button>


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

<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script>
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

<script src="{assets_url('bootv4/js/plugins/jasny-bootstrap.min.js')}"></script>




<script>
	$(document).ready(function() {
		setFormValidation('.ValidateForm');  
		$('.salesman_ajax').select2({

			placeholder: 'Select a salesman',
			ajax: {
				url:'{base_url()}{log_user_type()}/autocomplete/salesman_ajax',

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

		$(document).ready(function() { 
			md.initFormExtendedDatetimepickers();
		});

		
	});




</script> 
{/block}
