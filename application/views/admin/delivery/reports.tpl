{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/extensions/fixedColumns.dataTables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/select.dataTables.min.css')}">  

<style type="text/css">

@media print {
	.printdiv { display: block !important; }
}

</style>
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
							<div class="col-md-3">
								<div class="form-group">
									<select id="supervisor_id" name="supervisor_id" class="supervisor_ajax form-control" >
										{if $post_arr['supervisor_id']}
										<option value="{$post_arr['supervisor_id']}">{$post_arr['supervisor_name']}</option>
										{/if} 
									</select> 
								</div>
								{* <div class="form-group">
									<select class="selectpicker" data-size="7" data-style="select-with-transition" title="User type" id="user_type" name="user_type">
										<option value="driver" {set_select( 'user_type', 'driver')}>Driver</option>
										<option value="supervisor" {set_select( 'user_type', 'supervisor')}>Supervisor</option>
										<option value="store_keeper" {set_select( 'user_type', 'store_keeper')}>Storekeeper</option>
									</select> 
								</div> *}
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select id="name" name="project_id" class="project_ajax form-control" >
										{if $post_arr['project_id']}
										<option value="{$post_arr['project_id']}">{$post_arr['project_name']}</option>
										{/if} 
									</select> 
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select id="package_id" name="package_id" class="package_ajax form-control"  >
										{if $post_arr['package_id']}
										<option value="{$post_arr['package_id']}">{$post_arr['package_name']}</option>
										{/if} 	
									</select> 
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select id="delivery_code" name="delivery_code" class="deliverycode_ajax form-control"  >
										{if $post_arr['delivery_code']}
										<option value="{$post_arr['delivery_code']}">{$post_arr['code']}</option>
										{/if} 	
									</select> 
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">From Date</label>
									<input type="text"  class="form-control datepicker" name="start_date" value="{$post_arr['start_date']}">
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="issueinput3">To Date</label>
									<input type="text"  class="form-control datepicker" name="end_date" value="{$post_arr['end_date']}">
								</div>
							</div>
							<div class="col-md-2" style="max-width: 237px;">
								<div class="form-group">
									<select class="selectpicker" data-size="7" data-style="select-with-transition" title="Pending" name="status" id="status" >
										<option value='pending' {set_select('status', 'pending')}>Pending</option>
										<option value='deleted' {set_select('status', 'deleted')}>Deleted</option>
										<option value='send_to_delivery' {set_select('status', 'send_to_delivery')}>Send to delivery</option>
										<option value='delivered' {set_select('status', 'delivered')}>Delivered</option>
										<option value='all'  {set_select('status', 'all', TRUE)}>--ALL--</option>
									</select>
								</div>
							</div>


							<div class="col-md-4"> 
								<button type="submit" class="btn btn-primary" name="search" value="search">
									<i class="fa fa-filter"></i> {lang('button_filter')}
								</button>
								<button type="submit" class="btn btn-warning mr-1" name="submit" value="reset">
									<i class="fa fa-refresh"></i>  {lang('button_reset')}
								</button> 
							</div>
						</div>
					</div>
					{form_close()}
				</div>
			</div>
		</div> 
	</div> 
</div> 
{if $smarty.post.search}

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Available Deliveries</h4>
			</div>
			<div class="card-body">
				{if $details}
				<div class="table-responsive">
					<table class="table" id="example">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Code</th>
								<th>Project Name</th>
								<th>Driver</th>
								<th>Vehicle</th>
								<th>Status</th>
								<th>Created On</th>
								<th class="td-actions text-right ">Action</th>
							</tr>
						</thead>
						<tbody>

							{foreach $details as $v} 

							<tr>
								<td >{counter}</td>
								<td><a href="{base_url(log_user_type())}/delivery/delivery-details/{$v.enc_id}">{$v.code}</a></td>
								<td>{$v.project_name}</td>
								<td>{$v.driver_name}</td>
								<td>{$v.vehicle}</td>
								<td>{ucfirst($v.status)|replace:'_':' '}</td>
								<td>{$v.date_created}</td> 
								<td class="td-actions text-right ">
									{if $v.packages}
									<a rel="tooltip" title="QRcode" onClick="printQrCode(this)" class="btn btn-default btn-link"><i class="material-icons">grid_view</i></a>
									{include file="admin/delivery/show_qrcode.tpl"}
									{/if}
									<a rel="tooltip" title="View" href="{base_url()}admin/delivery/delivery_details/{$v.enc_id}" class="btn btn-info btn-link"><i class="material-icons">local_see</i></a>
								</td>     

							</tr>
							{/foreach}

						</tbody>
					</table>
				</div>
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Delivery Note Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
			</div>
		</div>
	</div>
</div> 
{/if}
{/block}

{block footer} 

<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>

<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>


<script src="{assets_url('js/tables/datatable/datatables.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.autoFill.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.colReorder.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.fixedColumns.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.select.min.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('js/scripts/tables/datatables-extensions/datatable-autofill.min.js')}"></script>


<script type="text/javascript">

	$('#example').DataTable( {
		"lengthMenu": [[10, 25, 50, 100], [25, 50,100, 200]] ,
	} );

</script>

<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});

	$(document).ready(function(){ 
		$('.supervisor_ajax').select2({

			placeholder: 'Select a Supervisor',
			ajax: {
				url:'{base_url()}admin/autocomplete/supervisor_ajax',

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

		$('.project_ajax').select2({

			placeholder: 'Select a project',
			ajax: {
				url:'{base_url()}admin/autocomplete/project_report_ajax',

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


		$('.package_ajax').select2({

			placeholder: 'Select a package',
			ajax: {
				url:'{base_url()}admin/autocomplete/package_ajax',

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
		$('.deliverycode_ajax').select2({

			placeholder: 'Select delivery code',
			ajax: {
				url:'{base_url()}admin/autocomplete/deliverycode_ajax',

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
	function printQrCode(ele) {
		var windowUrl = 'about:blank'
		var uniqueName = new Date();
		var windowName = 'Print' + uniqueName.getTime();

		var myPrintContent = $(ele).parent().find('.printdiv')[0];
		var myPrintWindow = window.open(windowUrl, windowName, 'left=300,top=100,width=400,height=400');
		myPrintWindow.document.write(myPrintContent.innerHTML);
		myPrintWindow.document.close();
		myPrintWindow.focus();
		myPrintWindow.print();
		myPrintWindow.close();    
		return false;
	}


</script>
{/block}
