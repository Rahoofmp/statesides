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
				{if $id}<h4 class="card-title">Update Department</h4>{else}<h4 class="card-title">Add Department</h4>{/if}
			</div>
			<div class="card-body">
				{form_open('','id="file_form" name="file_form" class="form-add-project ValidateForm" enctype="multipart/form-data"')}
				<div class="col-lg-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">person</i>
							</span>
						</div>
						<div class="col-sm-11">
							<div class="form-group">
								<label class="bmd-label-floating">
									Department ID
								</label>
								<input type="text" class="form-control" id="department_id" name="department_id" autocomplete="Off" required="" {if $id} value="{$department_details['dep_id']}" {else} value="{set_value('department_id')}" {/if}>
								{form_error("department_id")}
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
									Department Name
								</label>
								<input type="text" class="form-control" id="department_name" name="department_name" autocomplete="Off" required="" {if $id} value="{$department_details['name']}" {else} value="{set_value('department_name')}" {/if}>
								{form_error("department_name")}
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">schedule</i>
							</span>
						</div>
						<div class="col-sm-11">
							<div class="form-group">
								<label class="bmd-label-floating">
									Department Cost per Hour		
								</label>
								<input type="text" class="form-control" id="cost_per_hour" name="cost_per_hour" autocomplete="Off" required="" {if $id} value="{$department_details['cost_per_hour']}" {else} value="{set_value('cost_per_hour')}" {/if}>
								{form_error("cost_per_hour")}
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">list</i>
							</span>
						</div>
						<div class="col-sm-11">
							<div class="form-group">
								
								<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Status" id="status" name="status" required="">
									{if $id}
									{if $department_details['status']==1}
									<option value="{$department_details['status']}" selected>Active </option>
									<option value="0">InActive </option>
									{else}
									<option value="{$department_details['status']}" selected="">InActive </option>
									<option value="1" >Active</option> 
									{/if}
									{else}
									<option value="1">Active</option>
									<option value="0">InActive</option>
									{/if}
								</select>
								{form_error("status")}
							</div>
						</div>
					</div>
				</div>
			<!-- 	<div class="col-lg-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">list</i>
							</span>
						</div>
						<div class="col-sm-11">
							<div class="form-group">
								<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="Status" id="status" name="status" required="" >
									<option value="draft" {if $project['status'] == 'draft'} selected="" {/if}>Draft</option>
									<option value="pending" {if $project['status'] == 'pending'} selected="" {/if}>Active</option>
									<option value="inactive" {if $project['status'] == 'inactive'} selected="" {/if}>Inactive</option>
								</select> 
								{form_error('status')}
								
							</div>
						</div>
					</div>
				</div> -->
			</div>

			<div class="card-footer text-right">
				<div class="form-check mr-auto">
				</div>
				{if $id}
				<button class="btn btn-primary pull-right" type="submit" id="update_department" name="update_department" value="Update Departmentt">
					Update <i class="fa fa-arrow-circle-right"></i>
				</button>
				{else}
				<button class="btn btn-primary pull-right" type="submit" id="add_department" name="add_department" value="Add Departmentt">
					Insert <i class="fa fa-arrow-circle-right"></i>
				</button>
				{/if}
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

<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script>
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
</script>  *}
{/block}
