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
			<div class="card-header card-header-primary">
				{if $id}<h4 class="card-title">Edit Project</h4>{else}<h4 class="card-title">Add Project</h4>{/if}
			</div>
			<div class="card-body">
				{form_open('','id="file_form" name="file_form" class="form-add-project ValidateForm" enctype="multipart/form-data"')}
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
										Project Name
									</label>
									<input type="text" class="form-control" id="project_name" name="project_name" {if $id} value="{$project['project_name']}" {else} value="{set_value('project_name')}" {/if} required="true" autocomplete="Off">
									{form_error("project_name")}
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
										Customer Name
									</label>
									<select id="customer_name" name="customer_name" class="customer_ajax form-control" >
										{if $id}
										{if $project['customer_name']}
										<option value="{$project['customer_name']}">{$project['customer_username']}</option>
										{/if}
										{/if}
									</select> 
									{form_error("customer_name")}
								</div>
							</div>
						</div>
					</div>
					{* <div class="col-lg-12">
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
					</div> *}

					<div class="col-lg-12">
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
				</div>

				<div class="row">
					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">money</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label class="bmd-label-floating"> Estimated Cost </label>

									<input type="text" id="estimated_cost" class="form-control " name="estimated_cost" {if $id} value="{$project['estimated_cost']}"{else}value="{set_value('estimated_cost')}"{/if} required="true" autocomplete="Off">
									{form_error("estimated_cost")} 
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">attach_money</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label class="bmd-label-floating"> Estimated Value </label>

									<input type="text" id="estimated_value" class="form-control " name="estimated_value"{if $id} value="{$project['estimated_value']}"{else}value="{set_value('estimated_value')}"{/if} required="true" autocomplete="Off">
									{form_error("estimated_value")} 
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">schedule</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label class="bmd-label-floating"> Estimated Duration </label>

									<input type="text" id="estimated_duration" class="form-control " name="estimated_duration"{if $id} value="{$project['estimated_duration']} Hrs" {else}value="{set_value('estimated_duration')} Hrs"{/if} required="true" autocomplete="Off">
									{form_error("estimated_duration")} 
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
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Status" id="status" name="status" required="" >
										<option value="draft" {if $project['status'] == 'draft'} selected="" {/if}>Draft</option>
										<option value="pending" {if $project['status'] == 'pending'} selected="" {/if}>Pending</option>
										<option value="inactive" {if $project['status'] == 'inactive'} selected="" {/if}>Inactive</option>
									</select> 
									{form_error('status')}
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
									<label for="from_date">Start Date</label>
									<input type="text" id="start_date" class="form-control datepicker" name="start_date" {if $id} value="{$project['start_date']}" {else} value="{set_value('start_date')}" {/if} autocomplete="off">
									{form_error("start_date")} 
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
									<label for="from_date">End Date</label>
									<input type="text" id="end_date" class="form-control datepicker" name="end_date" {if $id} value="{$project['end_date']}" {else} value="{set_value('end_date')}" {/if} autocomplete="off">
									{form_error("end_date")} 
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row"> 
					<div class="col-lg-12">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">place</i>
								</span>
							</div>
							<div class="col-sm-7">
								<div class="form-group">
									<label class="bmd-label-floating"> Location: ( lat, long )</label>

									<input type="text" class="form-control"  name="location"
									{if $id}value="{$project['location']}" {/if} id="location" autocomplete="Off" >
									{form_error("location")} 
								</div>
							</div>
							<label class="col-sm-3 label-on-right">
								<a href="https://www.google.com/maps/@25.20388676754449,55.26983662798098,16z" class="btn btn-info" target="_blank">View Map</a>
							</label>
						</div>
					</div> 
				</div>
			</div>
			<div class="card-footer text-right">
				<div class="form-check mr-auto">
				</div>
				{if $id}
				<button class="btn btn-primary pull-right" type="submit" id="update_project" name="update_project" value="update_project">
					Update Project <i class="fa fa-arrow-circle-right"></i>
				</button>
				{else}	
				<button class="btn btn-primary pull-right" type="submit" id="add_project" name="add_project" value="add_project">
					Add Project <i class="fa fa-arrow-circle-right"></i>
				</button>
				{/if}			
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

<script>
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

		$('.customer_ajax').select2({

			placeholder: 'Select a Customer',
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
