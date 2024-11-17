{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="{assets_url()}plugins/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" />

{/block}

{block body}
<div class="row "> 
	<div class="col-sm-12 hidden-print"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					{form_open('','')}
					<div class="form-body">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">

									<select id="name" name="project_id" class="project_ajax form-control" >
										{if $post_arr['project_id']}
										<option value="{$post_arr['project_id']}">{$post_arr['project_name']}</option>
										{/if} 
									</select>
									{form_error("name")}
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<select id="customer_name" name="customer_name" class="customer_ajax form-control" >
										{if $post_arr['customer_name']}
										<option value="{$post_arr['customer_name']}">{$post_arr['cus_name']}</option>
										{/if} 
									</select>
									{form_error("customer_name")}
								</div>
							</div>
							
							<div class="col-sm-3">
								<div class="form-group">
									<label for="issueinput3">{lang('mobile')}</label>
									<input type="text"  class="form-control" name="mobile" value="{$post_arr['mobile']}" autocomplete="Off"   >
									{form_error("mobile")}
								</div>
							</div>
							
							<div class="col-sm-3">
								<div class="form-group">
									<label for="issueinput3">{lang('email')}</label>
									<input type="email"  class="form-control" name="email" value="{$post_arr['email']}">
									{form_error("email")}
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="issueinput3">{lang('from_date')}</label>
									<input type="text"  class="form-control datepicker" name="start_date" value="{$post_arr['start_date']}">
									{form_error("date")}
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="issueinput3">{lang('to_date')}</label>
									<input type="text"  class="form-control datepicker" name="end_date" value="{$post_arr['end_date']}">
									{form_error("date")}
								</div>
							</div>
							
							
							{* <div class="col-sm-12">  *}
								<button type="submit" class="btn btn-primary" name="search" value="search">
									<i class="fa fa-filter"></i> {lang('button_filter')}
								</button>
								<button type="submit" class="btn btn-warning mr-1" name="submit" value="reset">
									<i class="fa fa-refresh"></i>  {lang('button_reset')}
								</button> 
							{* </div> *}
						</div>
					</div>
					{form_close()}
				</div>
			</div>
		</div> 
	</div> 
</div> 

<div class="row">
	<div class="col-md-12">
		<div class="card">

			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Available Projects</h4>
			</div> 
			<div class="card-body">
				{if count($project) > 0}
				<div class="table-responsive">
					<table class="table table-hover" id="sample-table-1">
						<thead class="bg-light">
							<tr>
								<th class="center">#</th>
								<th>Name</th>
								<th>Cus. Name</th>
								<th>Mobile</th>
								<th>Status</th>
								<th>Location</th>
								<th>Email</th>
								<th>Date</th>
								<th>Action</th>
								
							</tr>
						</thead>
						<tbody>
							{form_open('','')}
							{foreach $project as $v} 
							<tr>
								<td >{counter}</td>
								<td>{$v.project_name}</td>
								<td>{$v.customer_username}</td>
								<td>{$v.customer_mobile}</td>
								<td> 
									
									<div class="togglebutton">
										<label  data-off="Off" data-on="On">
											<input type="checkbox"  id="status{$v.id}" name="status[]" {if $v.status =='pending'} checked {/if} data-id="{$v.id}" class="status">
											<span class="toggle"></span>
										</label>
									</div>
									
								</td>
								<td><a href="{$v.map}" target="_blank">Show in Map</a></td>
								<td>{$v.email}</td>	
								<td>{$v.date}</td>	
								<td class="td-actions"> 

									<a rel="tooltip" title="Edit" href="javascript:edit_event('{$v.enc_id}')" class="btn btn-success btn-link"><i class="material-icons">edit</i></i></a>
								</td>	
							</tr>
							<input name="id" type="hidden" id="id" value="{$v.id}"/>
							{/foreach}
							{form_close()}

						</tbody>
					</table>
				</div>
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Project Details Found</i>
						</h4>
					</p>
				</div>
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

<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('js/notify/bootstrap-notify.min.js')}"></script>
<script src="{assets_url('js/notify/notify-script.js')}"></script>
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url()}plugins/bootstrap-switch/dist/js/bootstrap-switch.min.js"></script>

<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});
</script>


<script type="text/javascript">

	
	$('.project_ajax').select2({

		placeholder: 'Select a project',
		ajax: {
			url:'{base_url()}admin/autocomplete/project_ajax',

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

	$(document).ready(function(){ 

		$('.customer_ajax').select2({

			placeholder: 'Select a customer',
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
<script type="text/javascript">
	function edit_event(id)
	{
		swal({
			title: 'Are you sure?',
			text: 'do you want to edit ',
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: 'edit',
			cancelButtonText: 'Cancel Please',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = "{base_url()}{log_user_type()}/project/add_project/"+id; 
			} else {
				swal('{lang("text_cancelled")}','{lang("text_cancelled")}', "error");
			}
		});
	}

	$(document).ready(function () {
		$('.status').on('change', function () {
			// var id=$("#status" ).val();
			var id = $(this).attr("data-id");

			if($(this).prop("checked") == true){
				var status='pending';
			}
			else if($(this).prop("checked") == false){
				var status='delivered';
			}
			$.ajax({
				type: "POST",
				url: "{base_url()}admin/project/changeStatus",
				data: { status: status , id : id },
				success: function () {
					if(status =='delivered')
						$.notify("Successfully Inactivated", "success");
					else
						$.notify("Changed to pending", "success");
				}
		})
		});
		
	});

</script>
{/block}
