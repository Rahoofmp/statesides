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
				{if $id}<h4 class="card-title">Edit Customer</h4>{else}<h4 class="card-title">Add Customer</h4>{/if}
			</div>
			<div class="card-body">
				{form_open('','id="file_form" name="file_form" class="form-add-customer ValidateForm" enctype="multipart/form-data"')}
				<div class="col-lg-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">grading</i>
							</span>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="bmd-label-floating">
									UserName
								</label>
								<input type="text" class="form-control" id="customer_username" name="customer_username" {if $id} value="{$customer['customer_username']}" {else} value="{set_value('customer_username')}" {/if} required="true" autocomplete="Off">
								{form_error("customer_username")}
							</div>
						</div>
						<div class="col-md-5">
							<div class="form-group">
								<label class="bmd-label-floating">
									<select id="salesman_id" name="salesman_id" class="salesman_ajax form-control" >
									</label>
									{if $customer['salesman_id']}
									<option value="{$customer['salesman_id']}">{$customer['salesman_name']}</option>
									{/if} 
								</select> 
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
									Name
								</label>
								<input type="text" class="form-control" id="name" name="name"{if $id} value="{$customer['name']}" {else}value="{set_value('name')}"  {/if}required="true" autocomplete="Off">
								{form_error("name")}
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

								<input type="email" id="email" class="form-control " name="email"{if $id} value="{$customer['email']}"{else}value="{set_value('email')}"{/if} required="true" autocomplete="Off">
								{form_error("email")} 
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">home</i>
							</span>
						</div>
						<div class="col-sm-11">
							<div class="form-group">
								<label class="bmd-label-floating"> Address </label>
								<textarea name="address" id="address" class="form-control " >{if $customer['address']}"{else}{set_value('address')}{/if}</textarea>
								{form_error("address")} 
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
								<label class="bmd-label-floating"> Contact Number </label>
								<input type="text" class="form-control" id="mobile" name="mobile"{if $id} value="{$customer['mobile']}" {else}value="{set_value('mobile')}" {/if}required="true" number="true" autocomplete="Off">
								{form_error("mobile")}
							</div>
						</div>
					</div>
				</div>

				<div class="row col-lg-12"> 
					<div class="col-lg-6">  
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">password</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label for="exampleInput1" class="bmd-label-floating">Password</label>
									<input type="password" id="password" class="form-control" name="psw" value="" autocomplete="off" {if !$id} required{/if}>
									{form_error('psw')}
								</div>
							</div>
						</div>
					</div> 
					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">password</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label for="exampleInput1" class="bmd-label-floating">Confirm Password</label>

									<input type="password" id="cpassword" class="form-control" name="cpsw" value="" autocomplete="off" {if !$id} required{/if}>
									{form_error('cpassword')}
								</div>
							</div>
						</div>
					</div>
				</div>
				
				
				<div class="row col-lg-12"> 

					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">place</i>
								</span>
							</div>
							<div class="col-sm-7">
								<div class="form-group">
									<label class="bmd-label-floating"> Location: ( lat, long )</label>

									<input type="text" class="form-control"  name="location"
									{if $id}value="{$customer['location']}" {/if} id="location" autocomplete="Off" >
									{form_error("location")} 
								</div>
							</div>
							<label class="col-sm-3 label-on-right">
								<a href="https://www.google.com/maps/@25.20388676754449,55.26983662798098,16z" class="btn btn-info" target="_blank">View Map</a>
							</label>
						</div>
					</div> 

					<div class="col-lg-3">
						<div class="form-group">                     
							<label class="col-sm-12 label-checkbox">Customer Type</label>
							<div class="col-sm-10 checkbox-radios">
								<div class="form-check form-check-inline">

									{assign var="customer_checked" value='checked'}
									{assign var="lead_checked" value=''}

									{if $id} 
									{if $customer['user_type'] == 'customer' } 
									{$customer_checked = 'checked' }
									{else if $customer['user_type'] == 'lead'}
									{$lead_checked = 'checked' }
									{/if}
									{/if}

									<label class="form-check-label">
										<input class="form-check-input" type="radio" value="customer" {$customer_checked} name="user_type"> Customer
										<span class="circle">  <span class="check"></span> </span>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input class="form-check-input" type="radio" value="lead" {$lead_checked} name="user_type"> Lead
										<span class="circle">  <span class="check"></span> </span>

									</label>
								</div> 
								{form_error("user_type")}  
							</div>  
						</div>  
					</div>  
					<div class="col-lg-3">
						<div class="form-group">                     
							<label class="col-sm-12 label-checkbox">Organization Type</label>
							<div class="col-sm-10 checkbox-radios">
								<div class="form-check form-check-inline">

									{assign var="Individual_checked" value='checked'}
									{assign var="organization_checked" value=''}

									{if $id} 
									{if $customer['organization_type'] == 'Individual' } 
									{$organization_checked = 'checked' }
									{else if $customer['organization_type'] == 'organization'}
									{$Individual_checked = 'checked' }
									{/if}
									{/if}

									<label class="form-check-label">
										<input class="form-check-input" type="radio" value="Individual" {$Individual_checked} name="organization_type"> Individual 
										<span class="circle">  <span class="check"></span> </span>
									</label>
								</div>
								<div class="form-check form-check-inline">
									<label class="form-check-label">
										<input class="form-check-input" type="radio" value="organization" {$organization_checked} name="organization_type"> organization
										<span class="circle">  <span class="check"></span> </span>

									</label>
								</div> 
								<!-- {form_error("organization_type")}   -->
							</div>  
						</div>  
					</div>  

				</div>  
			</div>

			<div class="card-footer text-right">
				<div class="form-check mr-auto">
				</div>
				{if $id}
				<button class="btn btn-primary pull-right" type="submit" id="update_customer" name="update_customer" value="update_customer">
					Update customer <i class="fa fa-arrow-circle-right"></i>
				</button>
				{else}	
				<button class="btn btn-primary pull-right" type="submit" id="add_customer" name="add_customer" value="add_customer">
					Add customer <i class="fa fa-arrow-circle-right"></i>
				</button>
				{/if}			
			</div>

			{* <div class="form-group">
				<button class="btn btn-primary pull-right" type="submit" id="add_customer" name="add_customer" value="add_customer">
					Add customer <i class="fa fa-arrow-circle-right"></i>
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
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>


<script
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCtmmOPB_Ngkkmq8u_bZcpkp-w_1gvpslk&callback=initMap&libraries=&v=weekly"
async
></script>

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
	});
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
</script> 
{/block}
