{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}"> 
{/block}

{block body}

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				{if $id}<h4 class="card-title">Edit Supplier</h4>{else}<h4 class="card-title">Add Supplier</h4>{/if}
			</div>
			<div class="card-body">
				{form_open('','id="file_form" name="file_form" class="form-add-customer ValidateForm" enctype="multipart/form-data"')}
				<div class="col-lg-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">grading</i>
							</span>
						</div>
						<div class="col-sm-11">
							<div class="form-group">
								<label class="bmd-label-floating">
									UserName
								</label>
								<input type="text" class="form-control" id="user_name" name="user_name" value="{set_value('username', $supplier['user_name'])}" required="true" autocomplete="Off">
								{form_error("user_name")}
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
								<input type="text" class="form-control" id="name" name="name"{if $id} value="{$supplier['name']}" {else}value="{set_value('name')}"  {/if}required="true" autocomplete="Off">
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
								<label class="bmd-label-floating">  Email </label>

								<input type="email" id="email" class="form-control " name="email"{if $id} value="{$supplier['email']}"{else}value="{set_value('email')}"{/if} required="true" autocomplete="Off">
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
								<label class="bmd-label-floating">  Address </label>
								<textarea name="address" id="address" class="form-control " >{$supplier['address']}</textarea>
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
								<label class="bmd-label-floating">  Mobile </label>
								<input type="text" class="form-control" id="mobile" name="mobile"{if $id} value="{$supplier['mobile']}" {else}value="{set_value('mobile')}" {/if}required="true" number="true" autocomplete="Off">
								{form_error("mobile")}
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
								<label class="bmd-label-floating"> Supplier Contact Person </label>
								<input type="text" class="form-control" id="contact_person" name="contact_person"{if $id} value="{$supplier['contact_person']}" {else}value="{set_value('contact_person')}" {/if}required="true" number="true" autocomplete="Off">
								{form_error("contact_person")}
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="card-footer text-right">
				<div class="form-check mr-auto">
				</div>
				{if $id}
				<button class="btn btn-primary pull-right" type="submit" id="update_customer" name="update_supplier" value="update_supplier">
					Update  <i class="fa fa-arrow-circle-right"></i>
				</button>
				{else}	
				<button class="btn btn-primary pull-right" type="submit" id="add_supplier" name="add_supplier" value="add_supplier">
					Add Supplier <i class="fa fa-arrow-circle-right"></i>
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
{/block}
