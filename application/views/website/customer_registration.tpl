{extends file="website/website_base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<style type="text/css">
	
</style>

{/block}

{block body}

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				<h4 class="card-title">Registration Form</h4>
			</div>
			<div class="card-body">
				{form_open_multipart('','id="file_form" name="file_form" class="form-add-project ValidateForm needs-validation" enctype="multipart/form-data"')}
				<div class="row">
					
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
									<input type="text" class="form-control" id="source_user" name="source_user" {if $user_name} value="{$user_name}" {else} value="Marketing"{/if} autocomplete="Off">
									{form_error("source_user")}
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
										Country
									</label>
									<select id="country" name="country" class="country_ajax form-control" >
										{if $id}
										{if $project['country']}
										<option value="{$project['country']}">{$project['country_name']}</option>
										{/if}
										{/if}
									</select> 
									{form_error("country")}
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							

							<div class="col-sm-11">
								<div class="form-group">
									<label class="bmd-label-floating">
										First Name
									</label>
									<input type="text" class="form-control" id="first_name" name="first_name" required autocomplete="Off">
									{form_error("first_name")}
								</div>
							</div>

						</div>
					</div>

					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							

							<div class="col-sm-11">
								<div class="form-group">
									<label class="bmd-label-floating">
										Last Name
									</label>
									<input type="text" class="form-control" id="last_name" name="last_name"  autocomplete="Off">
									{form_error("last_name")}
								</div>
							</div>

						</div>
					</div>
					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">call</i>
								</span>
							</div>
							<div class="col-sm-11">
								<div class="form-group">
									<label class="bmd-label-floating"> Mobile </label>
									<input type="text" class="form-control" id="mobile" name="mobile"{if $id} value="{$project['mobile']}" {else}value="{set_value('mobile')}" {/if}required="true" number="true" autocomplete="Off">
									{form_error("mobile")}
								</div>
							</div>
						</div>
					</div> 

					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">email</i>
								</span>
							</div>
							<div class="col-sm-11">
								<div class="form-group">
									<label class="bmd-label-floating"> Email </label>

									<input type="email" id="email" class="form-control " name="email"{if $id} value="{$project['email']}"{else}value="{set_value('email')}"{/if} required="true" autocomplete="Off">
									{form_error("email")} 
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							

							<div class="col-sm-11">
								<div class="form-group">
									<label class="bmd-label-floating">
										Age
									</label>
									<input type="text" class="form-control" id="last_name" name="age" {if $id} value="{$project['last_name']}" {else} value="{set_value('last_name')}" {/if} autocomplete="Off">
									{form_error("last_name")}
								</div>
							</div>

						</div>
					</div>
					


					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">list</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Gender" id="gender" name="gender" >
										<option value="male" {if $project['gender'] == 'male'} selected="" {/if}>Male</option>
										<option value="femaile" {if $project['gender'] == 'femaile'} selected="" {/if}>Female</option>
										<option value="others" {if $project['status'] == 'others'} selected="" {/if}>Others</option>
									</select> 
									{form_error('gender')}
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							
							<div class="col-sm-10">
								<div class="form-group">
									<label class="bmd-label-floating"> Current Job </label>

									<input type="text" id="current_job" class="form-control " name="current_job" {if $id} value="{$project['current_job']}"{else}value="{set_value('current_job')}"{/if}  autocomplete="Off">
									{form_error("current_job")} 
								</div>
							</div>
						</div>
					</div> 

<!-- 					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">date_range</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label for="from_date"> Date</label>
									<input type="text" id="date" class="form-control datepicker" name="date" {if $id} value="{$project['date']}" {else} value="{set_value('date')}" {/if} autocomplete="off">
									{form_error("date")} 
								</div>
							</div>
						</div>
					</div> -->

					<!-- <div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">list</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Emmigration" id="emmigration" name="emmigration">
										<option value="none" {if $project['immigration_status'] == 'none'} selected="" {/if}>None</option>
										<option value="applied" {if $project['immigration_status'] == 'applied'} selected="" {/if}>Applied</option>

										<option value="approved" {if $project['immigration_status'] == 'approved'} selected="" {/if}>Approved</option>

										<option value="rejected" {if $project['immigration_status'] == 'rejected'} selected="" {/if}>Rejected</option>
										
									</select> 
									{form_error('gender')}
								</div>
							</div>
						</div>
					</div> -->
				</div>
				<div class="row">



				</div>
			</div>

			<div class="row">
				<div class=" col-md-3 px-5">

					<!-- <div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail img-circle">
							<img src="{assets_url('images/profile_pic/no_file')}" alt="{$user_name}">
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
					</div> -->
				</div>


				<div class="col-md-3">

					<!-- <div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail img-circle">
							<img src="{assets_url('images/profile_pic/no_file')}" alt="{$user_name}">
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
				</div> -->

				<div class="col-md-3">

					<!-- <div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail img-circle">
							<img src="{assets_url('images/profile_pic/no_file')}" alt="{$user_name}">
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
				</div> -->

				<div class="col-md-3">

					<!-- <div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail img-circle">
							<img src="{assets_url('images/profile_pic/no_file')}" alt="{$user_name}">
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
				</div> -->

				<div class="col-md-3 px-5">

					<!-- <div class="fileinput fileinput-new text-center" data-provides="fileinput">
						<div class="fileinput-new thumbnail img-circle">
							<img src="{assets_url('images/profile_pic/no_file')}" alt="{$user_name}">
						</div>
						<div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
						<div>
							<span class="btn btn-round btn-info btn-file">
								<span class="fileinput-new">DOB Cirtificate</span>
								<span class="fileinput-exists">{lang('change')}</span>
								<input type="file" name="dob_certificate" />
							</span>
							<br />
							<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
						</div>
					</div> -->
				</div>


			</div>








			<div class="card-footer text-right">
				<div class="form-check mr-auto">
				</div>

				<button class="btn btn-primary pull-right" type="submit" id="add_project" name="add_project" value="add_project">
					Register <i class="fa fa-arrow-circle-right"></i>
				</button>

			</div>

			{* <div class="form-group">
				<button class="btn btn-primary pull-right" type="submit" id="add_project" name="add_project" value="add_project">
					Add Project <i class="fa fa-arrow-circle-right"></i>
				</button>
			</div> *}

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


	(function () {
		'use strict'

		var forms = document.querySelectorAll('.needs-validation')
		Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}

				form.classList.add('was-validated')
			}, false)
		})
	})()

	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});
</script>
{* <script
	src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCtmmOPB_Ngkkmq8u_bZcpkp-w_1gvpslk&callback=initMap&libraries=&v=weekly"
	async
	></script> 

	<script type="text/javascript">




		function initMap() {
			const myLatlng = { lat: 25.20388676754449, lng: 55.26983662798098 };
			const map = new google.maps.Map(document.getElementById("map"), {
				zoom: 10,
				center: myLatlng,
			});

			let infoWindow = new google.maps.InfoWindow({
				content: "Click the map to get Lat/Lng!",
				position: myLatlng,
			});
			infoWindow.open(map);

			map.addListener("click", (mapsMouseEvent) => {
				infoWindow.close();
				infoWindow = new google.maps.InfoWindow({
					position: mapsMouseEvent.latLng,
				});
				infoWindow.setContent(
					JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
					console.log(mapsMouseEvent),
					$('#location').val(mapsMouseEvent.latLng)
					);
				infoWindow.open(map);
			});
		}

		$("#sort_order").keypress(function(e)
		{
			var msg20 = "{lang('digits_only')}";
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
			{
				$("#error_sort_order").html(msg20).show().fadeOut(2200, 0);
				return false;
			}
		});  
		$(document).ready(function() {
			setFormValidation('.ValidateForm'); 
		});
	</script> *}
	<script type="text/javascript">

		$(document).ready(function(){ 

			$('.salesman_ajax').select2({

				placeholder: 'Select  Salesman',
				ajax: {
					url:'{base_url()}salesman/autocomplete/salesman_ajax',

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

			$('.country_ajax').select2({

				placeholder: 'Select  Country',
				ajax: {
					url:'{base_url()}salesman/autocomplete/countryNames_ajax',

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
