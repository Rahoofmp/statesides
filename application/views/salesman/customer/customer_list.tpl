{extends file="layout/base.tpl"}

{block header} 
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/extensions/fixedColumns.dataTables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/select.dataTables.min.css')}">

{/block}


{block body}
<style type="text/css">
	.my-group .form-control
	{
		width:50%;
	} 
	table.dataTable thead > tr > th.sorting
	{
		text-align: left !important;
	}
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
									<select type="text" id="customer_username" class="form-control customer_ajax" name="customer_username"></select>
								</div>
							</div> 
							<div class="col-sm-4">
								<div class="form-group">
									<label for="first_name">{lang('first_name')}</label>
									<input type="text" id="name" class="form-control" name="name" autocomplete="off" value="{$search_arr['name']}">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="email">{lang('email')}</label>
									<input type="text" id="email" class="form-control" name="email" autocomplete="off" value="{$search_arr['email']}">
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label for="mobile">{lang('mobile')}</label>
									<input type="text" id="email" class="form-control" name="mobile" autocomplete="off" value="{$search_arr['mobile']}">
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
					<table class="table" id="customer_list">
						<thead class="bg-light text-warning">
							<tr>
								<th>#</th> 
								<th>{lang('username')}</th>
								<th>{lang('firstname')}</th>
								<th>Details</th>
								<th>Salesman</th>

								<th>Location</th>
								<th>{lang('registered_on')}</th>  

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
<script src="{assets_url('vendors/js/pagination/jquery.twbsPagination.min.js')}"></script>  
<script src="{assets_url('js/scripts/pagination/pagination.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>


<script type="text/javascript">

	$(document).ready(function(){ 

		$('.salesman_ajax').select2({

			placeholder: 'Select a Salesman',
			ajax: {
				url:'{base_url()}{log_user_type()}/autocomplete/salesman_ajax',

				type: 'post',
				dataType: 'json',
				delay:250,
				data: function (params) {

					var searchString = $( "select#customer_id option:checked" ).val() ;

					var query = {
						customer_id: searchString,
						type: 'public'
					}
					return query;
				},
				processResults: function(data) {
					return {
						results: data
					};
				}
			},

		});
		$('.customer_ajax').select2({

			placeholder: 'Select a customer',
			ajax: {
				url:'{base_url()}salesman/autocomplete/customer_ajax',

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

		var order = $('#customer_list').DataTable({

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
				'url':'{base_url()}salesman/customer/get_customer_list_ajax',
				"type": "POST", 
				"data" : {
					'customer_username' : '{$search_arr['customer_username']}',
					'name' : '{$search_arr['name']}',
					'email' : '{$search_arr['email']}',
					'mobile' : '{$search_arr['mobile']}',
				}

			},

			'columns': [


			{ data: 'index'},
			{ data: 'customer_username',
			mRender: function(data, type, row) {

				var html = data;
				if(row.user_type == 'lead'){
					html += '<br><span class="badge badge-info">Lead</span>';
				}else{
					html += '<br><span class="badge badge-success">Customer</span>';
				}

				return html;
			} },
			{ data: 'name',
			mRender: function(data, type, row) {

				var html = data;
				if(row.organization_type == 'Individual'){
					html += '<br><span class="badge badge-danger">Individual</span>';
				}else if(row.organization_type == 'organization'){
					html += '<br><span class="badge badge-warning">Organization</span>';
				}

				return html;
			}},
			{ data: 'email',
			mRender: function(data, type, row) {
				var link = row.email +'<span class="clearfix"></span>'+row.mobile;

				return link;
			}},
			{ data: 'salesman_name'}, 
			{ data: 'map',
			mRender: function(data, type, row) {
				var link = '<a href ="' + row.map +'"   target="_blank">Show location</a>';

				return link;
			}}, 
			{ data: 'date'},
			{ data: 'customer_username',
			mRender: function(data, type, row) {
				var link = '<a href = "add-customer/' + row.enc_customerid +'" class="btn-sm btn btn-info btn-link" data-placement="top" title ="Edit" target="_blank"><i class="material-icons" aria-hidden="true">person</i></a>';

				return link;
			}}, 
			],
			success: function(response) { 
			} 
		});  

	});  
</script>
{/block}