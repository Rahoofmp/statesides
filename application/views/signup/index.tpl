{extends file="layout/base.tpl"}

{block body}
<div class="row">
	<div class="col">
		<div class="card"> 

			<div class=" card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">person_add</i>
				</div>
				<h4 class="card-title">Register Users</h4>
			</div>
			{form_open("",'class="form form-horizontal" id="RegisterValidation" ' )}  
			<div class="card-body pt-0"> 

				<div class="row"> 
					<div class="col-lg-6"> 

						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">person</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style="select-with-transition" title="User type" id="user_type" name="user_type" required="" >
										<option value="admin">Admin</option>
										<option value="packager">Packager</option>
										<option value="store_keeper">Storekeeper</option>
										<option value="supervisor">Supervisor</option>
										<option value="dept_supervisor">Dept. Supervisor</option>
										<option value="driver">Driver</option>
										<option value="salesman">Salesman</option>
										<option value="designer">Designer</option>
										<option value="purchaser">Purchaser</option>
										<option value="workers">Workers</option>
									</select> 
									{form_error('user_type')}
								</div> 
							</div>
						</div>

					</div>
					<div class="col-lg-6 department" style="display: none;">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">build</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<select class="selectpicker col-12" data-size="7" data-style=" select-with-transition" title="Department" id="department" name="department">
										{foreach $department as $v}
										<option value="{$v.id}">{$v.name} - {$v.dep_id}</option>
										{/foreach}
									</select> 
									{form_error('department')}
								</div> 
							</div>
						</div>
					</div> 
				</div> 
				<div class="row"> 
					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">person_add</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label for="exampleInput1" class="bmd-label-floating">User Name </label>
									<input type="text" id="user_name" class="form-control " name="user_name" value="{set_value('user_name')}" autocomplete="off" required>
									{form_error('user_name')}
								</div> 
							</div>
						</div>
					</div> 
					<div class="col-lg-6">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">person_add</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label for="exampleInput1" class="bmd-label-floating">Name</label>
									<input type="text" id="name" class="form-control " name="name" value="{set_value('name')}" required autocomplete="off">
									{form_error('name')}
								</div> 
							</div>
						</div>
					</div> 

				</div> 
				<div class="row"> 
					<div class="col-lg-6"> 
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">email</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label for="exampleInput1" class="bmd-label-floating">Email</label>
									<input type="email" id="email" class="form-control " name="email" value="{set_value('email')}"  autocomplete="off" required>
									{form_error('email')}
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
							<div class="col-sm-10">
								<div class="form-group">
									<label for="exampleInput1" class="bmd-label-floating">Mobile</label>
									<input type="tel" id="mobile" class="form-control" name="mobile" value="{set_value('mobile')}" autocomplete="off" required>
									{form_error('mobile')}
								</div>
							</div>
						</div>
					</div> 
				</div> 
				<div class="row"> 
					<div class="col-lg-6">  
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">password</i>
								</span>
							</div>
							<div class="col-sm-10">
								<div class="form-group">
									<label for="exampleInput1" class="bmd-label-floating">Password</label>
									<input type="password" id="password" class="form-control" name="psw" value="" autocomplete="off" required>
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

									<input type="password" id="cpassword" class="form-control" name="cpsw" value="" autocomplete="off" required>
									{form_error('cpassword')}
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="col-lg-12">
					<div class="form-actions right"> 
						<button type="submit" class="btn btn-rose" name="register" value="website">
							<i class="fa fa-check-square-o"></i> Register
						</button>
					</div>
				</div>

			</div> 
			{form_close()}
		</div>
	</div>
</div>  
{/block}
{block footer}
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 

<script> 
	$(document).ready(function() {
		setFormValidation('#RegisterValidation'); 
		$("#user_type").on('change', function() {
			var selected=$(this).val();
			if (selected == 'dept_supervisor') {
				$(".department").show();
				$("#department").attr('required','true');
			}
			else {
				$(".department").hide();
				$("#department").removeAttr('required');
			}

		})
	});
</script>
{/block}