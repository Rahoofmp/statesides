{extends file="layout/base.tpl"}

{block body}   
<div class="row">
	<div class="col-md-4">
		
		<div class="card profile-card-with-stats box-shadow-2">
			<div class="text-center">
				<div class="card-body">
					<img src="{assets_url('images/profile_pic/')}{$user_details['user_photo']}" class="rounded-circle  height-150" alt="Profile image"style="max-width: 100%;">
				</div>
				<div class="card-body">
					<h4 class="card-title">{$user_name}</h4>
				</div>
				<div class="text-center mb-2">

					<a href="{$user_details['facebook']}" class="btn btn-social-icon btn-sm mr-1 btn-facebook"><span class="fa fa-facebook" target="_blank"></span></a>

					<a href="{$user_details['twitter']}" class="btn btn-social-icon btn-sm mr-1 btn-twitter" target="_blank"><span class="fa fa-twitter"></span></a>

					<a href="{$user_details['instagram']}" class="btn btn-social-icon btn-sm mr-1 btn-instagram"><span class="fa fa-instagram" target="_blank"></span></a>

				</div> 
			</div> 
		</div> 
	</div>
	<div class="col-md-8">
		<div class="card">
			<div class="card-header card-header-icon card-header-rose">
				<div class="card-icon">
					<i class="material-icons">perm_identity</i>
				</div>
				<h4 class="card-title">{lang('edit_profile')} -
					<small class="category">{lang('complete_your_profile')}</small>
				</h4>
			</div>
			<div class="card-body">
				{form_open_multipart("{current_url()}?user_name={$user_name}",'id="trans_form" name="trans_form" class="form-login"' )} 
				<div class="row"> 
					<div class="col-md-6">
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('username')}</label>
							<input type="text" class="form-control" disabled value="{$user_name}">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('email')}</label>
							<input type="email" class="form-control" name="email" value="{$user_details['email']}"> 
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('firstname')}</label>

							{form_input('first_name', $user_details['first_name'], "class='form-control'")} 
							{form_error('firstname' )}
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('mobile')}</label>
							{form_input('mobile', $user_details['mobile'], "class='form-control'")} 
							{form_error('mobile' )}
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						{* <div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('address')}</label>

							{form_input('address', $user_details['address'], "class='form-control'")} 
							{form_error('address')}
						</div> *}
					</div>
				</div>
				<div class="row">
					{* <div class="col-md-6">
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('city')}</label>
							{form_input('city', $user_details['city'], "class='form-control' ")} 
							{form_error('city')}
						</div>
					</div> *}
					{* <div class="col-md-6">
						<div class="form-group bmd-form-group">

							{form_dropdown('country', $countries, $user_details['country'], "class='selectpicker col-md-12' data-style='btn btn-primary btn-round' title='{lang("country")}'")} 
							{form_error("country")} 
						</div>
					</div>  *}
				</div>
				
				<div class="row">
					<div class="col-md-4">
						<h4 class="title">Avatar</h4>
						<div class="fileinput fileinput-new text-center" data-provides="fileinput">
							<div class="fileinput-new thumbnail img-circle">
								<img src="{assets_url('images/profile_pic/')}{$user_details['user_photo']}" alt="{$user_name}">
							</div>
							<div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
							<div>
								<span class="btn btn-round btn-info btn-file">
									<span class="fileinput-new">{lang('update_avatar')}</span>
									<span class="fileinput-exists">{lang('change')}</span>
									<input type="file" name="userfile" />
								</span>
								<br />
								<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group bmd-form-group">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-facebook"></i>
									</span>
								</div>
								<input type="text" class="form-control" placeholder="{lang('facebook')}" name="facebook" value="{$user_details['facebook']}">
							</div>
						</div> 
						<div class="form-group bmd-form-group">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-twitter"></i>
									</span>
								</div>
								<input type="text" class="form-control" placeholder="{lang('twitter')}" name="twitter" value="{$user_details['twitter']}">
							</div> 
						</div> 
						<div class="form-group bmd-form-group">
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text">
										<i class="fa fa-instagram"></i>
									</span>
								</div>
								<input type="text" class="form-control" placeholder="{lang('instagram')}" name="instagram" value="{$user_details['instagram']}">
							</div>
						</div>
					</div>
				</div>
				
				<button type="submit" class="btn btn-rose pull-right" name="profile_update" value="profile_update">{lang('update_profile')}</button>
				<div class="clearfix"></div>
				{form_close()}
			</div>
		</div> 
	</div>
</div>  
{/block}

{block name="header"}

<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
{/block}

{block name="footer"}
<script src="{assets_url('bootv4/js/plugins/jasny-bootstrap.min.js')}"></script>
<script src="{assets_url('js/page-js/ewallet.js')}"></script>
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script>
<script type="text/javascript">
	
	$(function() {
		md.initFormExtendedDatetimepickers(); 	
	});
</script> 

{/block}