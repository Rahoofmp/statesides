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
							
							
							<div class="col-md-2" style="max-width: 237px;">
								<div class="form-group">
									<select class="selectpicker" data-size="7" data-style="select-with-transition" title="ALL" name="type" id="type" >
										<option value='all'>--ALL--</option>
										<option value="follow" {if $search_arr['type']=='follow'} selected {/if}>Follow-up</option>
										<option value="inactive" {if $search_arr['type']=='inactive'} selected {/if}>Inactive</option>
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
								<th>{lang('Name')}</th>
								
								<th>Type</th>
								<th>Message/Reason</th>
								<th>Added by</th>  
								<th>date</th>  

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
				'url':'{base_url()}supervisor/member/get_messages_list_ajax',
				"type": "POST", 
				"data" : {
					'customer_username' : '{$search_arr['customer_username']}',
					// 'name' : '{$search_arr['name']}',
					// 'email' : '{$search_arr['email']}',
					// 'mobile' : '{$search_arr['mobile']}',
					'type' : '{$search_arr['type']}',
				}

			},

			'columns': [


			{ data: 'index'},
			{ data: 'first_name'},
			{ data: 'type'},
			{ data: 'message'},
			
			{ data: 'added_by'},

			{ data: 'date'},
			{ data: 'customer_username',
			mRender: function(data, type, row) {

				if (row.type === 'inactive') {
					var link = '<a href="inactive-leads/' + row.enc_customerid + '/' + row.enc_reminder_id+'" class="btn-sm btn btn-info btn-link" data-placement="top" title="Edit" target="_blank"><i class="material-icons" aria-hidden="true">person</i></a>';
					return link;
				} else {
                    return ''; // Return empty if not 'inactive'
                }
                // var link = '<a href = "add-customer/' + row.enc_customerid +'" class="btn-sm btn btn-info btn-link" data-placement="top" title ="Edit" target="_blank"><i class="material-icons" aria-hidden="true">person</i></a>';

                return link;
            }}, 
            ],
            success: function(response) { 
            } 
        });  

	});  
</script>
{/block}