{extends file="layout/base.tpl"}

{block body}    
<div class="row"> 
	<div class="col-sm-5">
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					<div class="table-responsive">
						<table class="table mb-0 table-xs">
							<tbody>
								<tr>
									<td>{lang('username')}</td>
									<td>{$user_name}</td> 
								</tr>
								<tr>
									<td>{lang('firstname')}</td>
									<td>{$user_details['first_name']}</td> 
								</tr>
								<tr>
									<td>{lang('mobile')}</td>
									<td>{$user_details['mobile']}</td> 
								</tr> 
								<tr>
									<td>{lang('email')}</td>
									<td>{$user_details['email']}</td> 
								</tr>  
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-7">
		<div class="card">
			<div class="card-header card-header-tabs card-header-info">
				<div class="nav-tabs-navigation">
					<div class="nav-tabs-wrapper"> 
						<ul class="nav nav-tabs" data-tabs="tabs">

							<li class="nav-item">
								<a class="nav-link active" href="#change_password" data-toggle="tab">
									<i class="material-icons">code</i> {lang('change_password')}
									<div class="ripple-container"></div>
								</a>
							</li> 
						</ul>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="tab-content">
					<div class="tab-pane active" id="change_password">
						{form_open("{current_url()}?user_name={$user_name}",'id="trans_form" name="trans_form" class="form-login"' )} 
						<div class="row"> 
							<div class="col-md-6">
								<div class="form-group bmd-form-group">
									<label class="bmd-label-floating">{lang('new_password')}</label>
									<input type="password" class="form-control" name="new_password">
									{form_error('new_password')}
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group bmd-form-group">
									<label class="bmd-label-floating">{lang('confirm_password')}</label>
									<input type="password" class="form-control" name="confirm_password">
									{form_error('confirm_password')} 
								</div>
							</div>
						</div>

						<div class="form-group bmd-form-group">
							<button type="submit" class="btn btn-rose pull-right" name="credential_update" value="password">{lang('button_update')}</button>
						</div>
						{form_close()}
					</div> 
				</div>
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
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script></script> 
{/block}