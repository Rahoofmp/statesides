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
	.dataTables_length select {
		width: 80px;
		padding: 5px;
		font-size: 14px;
	}


</style>


<div class="row "> 
	<div class="col-sm-12 hidden-print"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					{form_open('','')}
					<div class="form-body">
						<div class="row">
							<!-- <div class="col-md-3">
								<div class="form-group">
									<select id="packager" name="packager_id" class="packager_ajax form-control" >
										{if $post_arr['packager_id']}
										<option value="{$post_arr['packager_id']}">{$post_arr['packager_name']}</option>
										{/if} 
									</select> 
								</div> 
							</div> -->


							<div class="col-md-3">
								<div class="form-group">
									<select id="source" name="source_id" class="source_ajax form-control" >

										{if $search_arr['source_id']}
										<option value="{$search_arr['source_id']}">{$search_arr['source_user']}</option>
										{/if} 
									</select>  
								</div> 
							</div> 
							
							
							<div class="col-md-2" style="max-width: 237px;">
								<div class="form-group">
									<select class="selectpicker" data-size="7" data-style="select-with-transition" title="ALL" name="enquiry" id="enquiry" >
										<option value='all'>--ALL--</option>
										<option value="customer" {if $search_arr['enquiry']=='customer'} selected {/if}>Customer</option>
										<option value="lead" {if $search_arr['enquiry']=='lead'} selected {/if}>Lead</option>
									</select>
								</div>
							</div>


							<!-- <div class="col-md-3">
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
							</div> -->
							<div class="col-md-4"> 
								<button type="submit" class="btn btn-primary" name="submit" value="filter">
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
		<!-- <div class="card"> 
			<div class="card-content collapse show">
				<div class="card-body">
					{form_open('','class="form" ')}
					<div class="form-body">
						<div class="row">
							<div class="col-sm-4">

								<div class="form-group">
							

						<select id="type" name="type" class="selectpicker" data-style="select-with-transition" title="Type" >

						<option value="customer" }>Customer</option>
						<option value="lead" }>Lead</option>
							

						</select> 
								
							</div> 
							
							

						</div>
					</div>
						<div class="row mt-2"> 
							<div class="col-sm-3"> 
								<button type="submit" class="btn btn-primary col-sm-6" name="submit" value="filter">
									<i class="fa fa-filter"></i> Filter
								</button>
							</div>
							<div class="col-sm-3"> 
								<button type="submit" class="btn btn-warning col-sm-6  pull-right" name="submit" value="reset">
									<i class="fa fa-refresh"></i> Reset
								</button>  
							</div>
						</div>
					
					{form_close()}
				</div>
			</div>
		</div>
	</div> -->
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
								<th>{lang('fullname')}</th>
								<th>Source Name</th>
								
								<th>Mobile</th>
								<th>Email</th>

								<th>Emigration</th>
								<th>Enquiry</th>  
								<th>Created date</th>  

								<th class="text-center">{lang('action')}</th>   
							</tr>
						</thead> 
					</table>
				</div>
			</div> 
		</div>
		<div class="d-flex justify-content-center">  
			<!-- <ul class="pagination start-links"></ul>  -->
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


		$('.source_ajax').select2({

			placeholder: 'Select a Source',
			ajax: {
				url:'{base_url()}admin/autocomplete/source_ajax',

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
			"lengthMenu": [
			 [10, 25, 50, 100, 150, 200, 250, 300, 350, 400], 
			 [10, 25, 50, 100, 150, 200, 250, 300, 350, 400]  
			 ],


			 "sortable": true,

			 "aaSorting": [],
			 "order": [],
			 "aoColumnDefs": [
			 { "bSortable": false, "aTargets": [0, 1, 2, 3, 4, 5, 6, 7, 8] },
			 ],

			 "columnDefs": [{
			 	"targets": 'no-sort',
			 	"orderable": false,
			 	"order": [],
			 }],

			'ajax': {
				'url':'{base_url()}admin/customer/get_customer_list_ajax',
				"type": "POST", 
				"data" : {
					'customer_username' : '{$search_arr['customer_username']}',
					'name' : '{$search_arr['name']}',
					'email' : '{$search_arr['email']}',
					'mobile' : '{$search_arr['mobile']}',
					'enquiry' : '{$search_arr['enquiry']}',
					'source_id' : '{$search_arr['source_id']}',
				}

			},

			'columns': [


			{ data: 'index'},
			{ data: 'fullname'},
			{ data: 'source_user_name'},
			{ data: 'mobile'},
			{ data: 'email'},
			{ data: 'immigration_status'},
			{ data: 'enquiry_status'},

			{ data: 'created_date'},
			{ data: 'customer_username',
			mRender: function(data, type, row) {
				var link = '<a href = "add-customer/' + row.enc_customerid +'" class="btn-sm btn btn-info btn-link" data-placement="top" title ="Edit" target="_blank"><i class="material-icons" aria-hidden="true">person</i></a>';

				return link;
			}}, 
			],


			 dom: '<"top"lBf>rt<"bottom"ip>',
			 buttons: [
			 {
			 	extend: 'excelHtml5',
			 	title: 'Exported Data',
			 	className: 'btn btn-success',
			 	exportOptions: {
			 		columns: ':visible'
			 	}
			 },

			 {
			 	extend: 'print',
			 	title: 'Exported Data',
			 	className: 'btn btn-primary',
			 	exportOptions: {
			 		columns: ':visible'
			 	}
			 }
			 ],

			success: function(response) { 
			} 
		});  

	});  
</script>
{/block}