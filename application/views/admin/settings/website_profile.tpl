{extends file="layout/base.tpl"}

{block body}
<div class="row">
	<div class="col">
		<div class="card"> 
			<div class="card-header pb-0">
				<h4 class="card-title">{lang('website_profile_and_details')}</h4>
				<hr>
			</div>
			<div class="card-content pt-0"> 

				{form_open("",'class="form form-horizontal"' )} 
				<div class="card-body pt-0">    

					<div class="form-body"> 
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('website_name')}<span class="danger">*</span></label>
							<input type="text" id="website_name" class="form-control " name="website_name" value="{$site_info['name']}">
							{form_error('website_name')}
						</div> 

						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('address')}<span class="danger">*</span></label>
							<textarea id="address" rows="5" class="form-control square" name="address" >{$site_info['address']}</textarea>

							{form_error('address')}
						</div> 

						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('email')}<span class="danger">*</span></label>

							<input type="email" id="email" class="form-control " name="email" value="{$site_info['email']}">
							{form_error('email')}
						</div>
						<div class="form-group bmd-form-group">
							<label class="bmd-label-floating">{lang('phone')}<span class="danger">*</span></label>

							<input type="text" id="phone" class="form-control" name="phone" value="{$site_info['phone']}">
							{form_error('phone')}
						</div> 
					</div>

					<div class="form-actions right"> 
						<button type="submit" class="btn btn-primary" name="update" value="website">
							<i class="fa fa-check-square-o"></i> {lang('button_update')}
						</button>
					</div>

				</div>
				{form_close()}
			</div>
		</div>
	</div>  
</div> 
{/block}