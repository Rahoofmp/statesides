{extends file="layout/base.tpl"}

{block header} 
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/extensions/fixedColumns.dataTables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/select.dataTables.min.css')}">
{/block}


{block body}
<style type="text/css">
.my-group .form-control{
	width:50%;
} 
table.dataTable thead > tr > th.sorting{
	text-align: left !important;
</style>
<div class="row">
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-content collapse show">
				<div class="card-body">
					{form_open('','class="form" ')}
					<div class="form-body">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<label for="user_name">{lang('user_name')}</label>
									<input type="text" id="user_name" class="form-control" name="user_name" autocomplete="off" value="{$search_arr['user_name']}">
									
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="name">{lang('name')}</label>
									<input type="text" id="name" class="form-control" name="name" autocomplete="off" value="{$search_arr['name']}">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="email">{lang('email')}</label>
									<input type="text" id="email" class="form-control" name="email" autocomplete="off" value="{$search_arr['email']}">
								</div>
							</div>

						</div>
						<div class="row mt-2"> 
							<div class="col-sm-6"> 
								<button type="submit" class="btn btn-primary col-sm-6" name="submit" value="filter">
									<i class="fa fa-filter"></i> Filter
								</button>
							</div>
							<div class="col-sm-6"> 
								<button type="submit" class="btn btn-warning col-sm-6  pull-right" name="submit" value="reset">
									<i class="fa fa-refresh"></i> Reset
								</button>  
							</div>
						</div>
					</div>
					{form_close()}
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">{lang('details')}</h4>
			</div> 
			<div class="card-body">
				<div class="table-responsive">
					<table class="table" id="supplier_list">
						<thead class="bg-light text-warning">
							<tr>
								<th>#</th> 
								<th>{lang('username')}</th>
								<th>{lang('name')}</th>
								<th>{lang('email')}</th>
								<th>{lang('mobile')}</th>
								<th>{lang('address')}</th>
								<th>{lang('contact_person')}</th>
								<th>{lang('date')}</th>
								<th class="text-center">{lang('action')}</th>   
							</tr>
						</thead>
					</table>
				</div>
			</div> 
		</div>
		<div class="d-flex justify-content-center">  
			<ul class="pagination start-links"></ul> 
		</div>
	</div>
</div> 

{/block}

{block footer}
<script src="{assets_url('js/tables/datatable/datatables.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.autoFill.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.colReorder.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.fixedColumns.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.select.min.js')}"></script>
<script src="{assets_url('js/scripts/tables/datatables-extensions/datatable-autofill.min.js')}"></script>


<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>


<script type="text/javascript">

	$(document).ready(function(){ 

		
		var order = $('#supplier_list').DataTable({

			'processing': true,
			'serverSide': true,
			"autoWidth": false,
			'serverMethod': 'post', 
			"pagingType": "full_numbers",
			"pageLength": 10,
			"sortable": false,
			"rowReorder": false,

			"aaSorting": [],

			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
				"order": [],
			}],

			"aoColumnDefs": [
			{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6, 7 ] },  
			],

			'ajax': {
				'url':'{base_url()}admin/member/get_supplier_list_ajax',
				"type": "POST", 
				"data" : {
					'user_name' : '{$search_arr['user_name']}',
					'name' : '{$search_arr['name']}',
					'email' : '{$search_arr['email']}',
				}

			},

			'columns': [

			{ data: 'index'},
			{ data: 'user_name' },
			{ data: 'name'},
			{ data: 'email'},
			{ data: 'mobile'},
			{ data: 'address'},
			{ data: 'contact_person'},
			{ data: 'date'},
			{ data: 'user_name',
			mRender: function(data, type, row) {
				var link = '<a href = "add-supplier/' + row.enc_user_name +'" class="btn-sm btn btn-info btn-link" data-placement="top" title ="Edit" target="_blank"><i class="material-icons" aria-hidden="true">person</i></a>';

				return link;
			}}, 
			],
			success: function(response) { 
			} 
		});  
	}); 



</script>
{/block}