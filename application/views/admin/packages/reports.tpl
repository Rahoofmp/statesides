{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/extensions/fixedColumns.dataTables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/select.dataTables.min.css')}">  


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
									<select id="packager" name="packager_id" class="packager_ajax form-control" >
										{if $post_arr['packager_id']}
										<option value="{$post_arr['packager_id']}">{$post_arr['packager_name']}</option>
										{/if} 
									</select> 
								</div> 
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

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Available Packages</h4>
			</div>
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="example">
						 
					<thead class="bg-light text-warning">
						<tr>
							<th class="text-center">#</th>
							<th class="img" >Image</th>
							<th>Code</th>
							<th>Name</th>
							<th>Project Name</th>
							<th>Status</th>
							<th>Created On</th>
							<th class="td-actions text-right ">Action</th>

						</tr>
					</thead> 
				</table>
			</div>

		</div>
	</div>
</div>
</div> 
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

	$(document).ready(function(){

		var order = $('#example').DataTable({

			'processing': true,
			'serverSide': true,
			"autoWidth": false,
			'serverMethod': 'post', 
			"pagingType": "full_numbers",
			"pageLength": 10,
			"sortable": true,

			"aaSorting": [],
			"order": [],
			"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6, 7 ] },
			],

			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
				"order": [],
			}],

			'ajax': {
				'url':'{base_url()}admin/packages/get_package_ajax',
				"type": "POST",
				"data" : {
					'project_id' : '{$post_arr['project_id']}',
					'package_id' : '{$post_arr['package_id']}',
					'packager_id' : '{$post_arr['packager_id']}',
					'start_date' : '{$post_arr['start_date']}',
					'end_date' : '{$post_arr['end_date']}',
					'status' : '{$post_arr['status']}',
				}

			},
			'columns': [
			{ data: 'index' },
			{ data: 'image',mRender: function(data,type,row){
				return '<img src="'+row.assets_url+'images/package_pic/'+row.image+'" class="text-center img-thumbnail" alt="Profile image" >'
			}},
			{ data: 'code',mRender: function(data,type,row){
				return '<a href="'+row.base_url+'admin/packages/package_details/'+row.enc_id+'">'+data+'</a>';
			}},
			{ data: 'name',mRender: function(data,type,row){
				return '<a href="'+row.base_url+'admin/packages/package_details/'+row.enc_id+'">'+data+'</a>';
			}},

			{ data: 'project_name'},
			{ data: 'status',
			mRender: function(data) {  
				if (data == "confirm") {
					return "Confirmed"
				}
				else if(data == "reject") {
					return "Rejected" 

				}else if(data == "delivered") {
					return "Delivered" 

				}else if(data == "send_to_delivery") {
					return "Send to deliverey" 

				}else if(data == "deleted") {
					return "Deleted" 

				}else if(data == "partially_delivered") {
					return "Partially delivered" 

				}
				else
				{
					return "Pending"
				}

			}},
			{ data: 'date_created'},
			{ data: 'qr_code',
			mRender: function(data,type,row) {
				var link = '<a href="'+row.base_url+'admin/packages/package_details/'+row.enc_id+'" rel="tooltip" title="View"><i class="material-icons text-rose">local_see</i></a> ';
				link+='<a data-id="'+row.enc_id+'" title="QRcode"onClick="printQrCode(this)" class=""><i class="material-icons text-gray">grid_view</i></a>';
				link+=row.print_view;
				return link;
			}},



			],
			success: function(response) { 
			} 
		});  
	}); 


</script>
<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});

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
	$('.packager_ajax').select2({

		placeholder: 'Select packager',
		ajax: {
			url:'{base_url()}admin/autocomplete/packager_ajax',

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
