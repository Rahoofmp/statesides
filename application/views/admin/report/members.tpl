{extends file="layout/base.tpl"}

{block header }  
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/extensions/fixedColumns.dataTables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/select.dataTables.min.css')}">  
<style type="text/css">

@media print {
	.hidden-print { display: none; }
}
</style> 
{/block}


{block body } 
{form_open('','')}
<div class="row "> 
	<div class="col-sm-12 hidden-print"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					
					<div class="form-body">
						<div class="row">
							
							<div class="col-sm-4">
								<div class="form-group">
									<label for="from_date">{lang('from_date')}</label>
									<input type="text" id="from_date" class="form-control datepicker" name="from_date" value="{$post_arr['from_date']}" autocomplete="off">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="end_date">{lang('end_date')}</label>
									<input type="text" id="end_date" class="form-control datepicker" name="end_date" value="{$post_arr['end_date']}" autocomplete="off">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<select class="selectpicker" data-size="7" data-style="select-with-transition" id="user_type" name="user_type">
										<option value="All" {set_select( 'user_type', 'All')}>All</option>
										<option value="admin" {set_select( 'user_type', 'admin')}>Admin</option>
										<option value="packager" {set_select( 'user_type', 'packager')}>Packager</option>
										<option value="store_keeper" {set_select( 'user_type', 'store_keeper')}>Storekeeper</option>
										<option value="supervisor" {set_select( 'user_type', 'supervisor')}>Supervisor</option>
										<option value="dept_supervisor" {set_select( 'user_type', 'dept_supervisor')}>Dept. Supervisor</option>
										<option value="driver" {set_select( 'user_type', 'driver')}>Driver</option>
									</select> 
								</div>
							</div>
							<div class="col-sm-12"> 
								<button type="submit" class="btn btn-primary" name="submit" value="search">
									<i class="fa fa-filter"></i> {lang('button_filter')}
								</button>
								<button type="submit" class="btn btn-warning mr-1" name="submit" value="reset">
									<i class="fa fa-refresh"></i>  {lang('button_reset')}
								</button> 
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div> 
	</div> 
</div> 


<div class="row "> 
	<div class="col-sm-12"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body">  
					<div class="d-flex align-items-end pull-right "> 

						<a href="#" onclick="javascript:window.print();"  class="btn btn-social-icon btn-block btn-sm mb-1 btn-github hidden-print"  ><i class="fa fa-print"></i></a>
					</div>


					<div class="table-responsive">

						<table class="table table-striped table-hover" id="example">
							<thead >
								<tr>
									<th>#</th>
									<th>{lang('user_name')}</th>
									<th>{lang('first_name')}</th> 
									<th>Email</th> 
									<th>Mobile</th>  
									<th>{lang('registerd_on')}</th>  
									<th>{lang('user_type')}</th> 
								</tr>
							</thead>
							
						</table>
					</div>
				</div>
			</div>
		</div> 
	</div> 
</div>


{form_close()} 
{/block}


{block footer }   
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"/> 
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 

<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 

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

			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
				"order": [],
			}],

			'ajax': {
				'url':'{base_url()}admin/report/get_member_ajax',
				"type": "POST",
				"data" : {
					'from_date' : '{$post_arr['from_date']}',
					'end_date' : '{$post_arr['end_date']}',
					'user_type' : '{$post_arr['user_type']}',
				}

			},
			'columns': [
			{ data: 'index' },
			{ data: 'user_name' ,mRender: function(data, type, row)
			{
				return '<a href="'+ row.base_url +'admin/member/profile?user_name=' + row.user_name + '"> '+ data + '</a>';
			}
		},
		{ data: 'first_name'},
		{ data: 'email'},
		{ data: 'mobile'},
		{ data: 'joining_date'},
		
		{ data: 'user_type',
		mRender: function(data, type, row) {  
			if (data == "driver") {
				return '<span class="badge badge-primary">Driver</span>'
			}
			else if(data == "admin") {
				return '<span class="badge badge-warning">Admin</span>' 

			}else if(data == "supervisor") {
				return '<span class="badge badge-danger">Supervisor</span>' 

			}else if(data == "store_keeper") {
				return '<span class="badge badge-success">Store Keeper</span>' 

			}else if(data == "packager") {
				return '<span class="badge badge-info">Packager</span>' 

			}else if(data == "dept_supervisor") {
				return '<span class="badge badge-secondary">Dept.Supervisor</span>' 

			}

		} },
		 
		],
		success: function(response) { 
		} 
	});  
	}); 


</script>
{* <script type="text/javascript">

	$('#exportTable').DataTable( {
		"lengthMenu": [[10, 25, 50, 100], [25, 50,100, 200]] ,
	} );

</script> *}
<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});
</script>
{/block}