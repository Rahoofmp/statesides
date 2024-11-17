{extends file="layout/base.tpl"}

{block header} 
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/jquery-confirm.min.css') }">

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
							<div class="col-md-3">
								<div class="form-group">

									<select id="item_id" name="item_id" class=" form-control item_ajax"  style="width:100%">
										{if $post_arr['item_id']}
										<option value="{$post_arr['item_id']}">{$post_arr['code']}</option>
										{/if} 
									</select> 
								</div>
							</div>
							

							<div class="col-md-3">
								<div class="form-group">
									<select id="category_id" name="category_id" class="category_ajax form-control">
										{if $post_arr['category_id']}
										<option value="{$post_arr['category_id']}">{$post_arr['category_name']}</option>
										{/if}
									</select> 
								</div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<select class="selectpicker" data-size="7" data-style="select-with-transition" name="type" id="type" >
										<option value="raw_materials" {set_select('type', 'raw_materials')}>Raw Materials</option>
										<option value="finished_item"  {set_select('type', 'finished_item')}>Finished item</option>
										<option value="all" {set_select('type', 'all', TRUE)}>--ALL--</option>
									</select> 
								</div>
							</div>  
							<div class="col-md-2" style="max-width: 237px;">
								<div class="form-group">
									<select class="selectpicker" data-size="7" data-style="select-with-transition" name="status" id="status" >
										<option value='1' {set_select('status', '1', TRUE)}>Active</option>
										<option value='0' {set_select('status', '0')}>Deleted</option> 
										<option value='all'  {set_select('status', 'all')}>--ALL--</option>
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



<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Details</h4>
			</div>
			<div class="card-body"> 
				<div class="table-responsive">
					<table class="table" id="item_list">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Item Code</th> 
								{* <th>Category</th>  *}
								<th>Cost</th>
								<th>VAT</th>
								<th>Selling price</th>
								<th>Quantity</th>
								<th>Unit</th>
								<th>Image</th>
								<th>Type</th>
								<th class="text-center">Action</th>
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
<script src="{assets_url('js/tables/datatable/datatables.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.autoFill.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.colReorder.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.fixedColumns.min.js')}"></script>
<script src="{assets_url('js/tables/datatable/dataTables.select.min.js')}"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('js/scripts/tables/datatables-extensions/datatable-autofill.min.js')}"></script>
<script src="{assets_url('js/jquery-confirm.min.js') }"></script>

<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	}); 

	$(document).on("click", "body #item_list tbody .edit_item" , function() {

		var id = $(this).attr('data-id');            

		if(id) {  
			$.confirm({
				title: 'Confirm!',
				content: 'Are You Sure to edit?? ',
				buttons: {
					confirm: function () {

						document.location.href = '{base_url()}' + "admin/item/create-items/"+id; 

					},
					cancel: function () {
						$.alert('Canceled!');
					},

				}
			});

		}
	} );

	$(document).on("click", "body #item_list tbody .delete_item" , function() {

		var id = $(this).attr('data-id');            

		if(id) {  
			$.confirm({
				title: 'Confirm!',
				content: 'Are You Sure to delete?? ',
				buttons: {
					confirm: function () {

						document.location.href = '{base_url(log_user_type())}' +"/item/list-items/delete/"+id; 
					},
					cancel: function () {
						$.alert('Canceled!');
					},

				}
			});

            	//end
            }



        } );

	$('.item_ajax').select2({

		placeholder: 'Select a Item',
		ajax: {
			url:'{base_url()}{log_user_type()}/autocomplete/item_with_name_ajax',
			data: function (params) {

				var query = {
					q: params.term,
					with_name: true,
					type: 'finished_item'
				}
				return query;
			},
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

	$('.category_ajax').select2({

		placeholder: 'Select a Category',
		ajax: {
			url:'{base_url()}{log_user_type()}/autocomplete/category_ajax',

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


	var order = $('#item_list').DataTable({

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
		{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4] },  
		],


		"columnDefs": [{
			"targets": 'no-sort',
			"orderable": false,
			"order": [],
		}],

		'ajax': {
			'url':'{base_url(log_user_type())}/item/get_item_list_ajax',
			'type': "POST", 
			'data' : { 
				'images' : '{$post_arr['images']}',
				'item_id' : '{$post_arr['item_id']}',
				'category_id' : '{$post_arr['category_id']}',
				'status' : '{$post_arr['status']}',
				'type' : '{$post_arr['type']}',
			}

		},

		'columns': [

		{ data: 'index'},

		{ data: 'code', mRender: function(data, type, row) {

			var  html = '<p class="text-info">'+row.code+'</p>';
			html +="<small><i>"+ row.name +"</i> </small><br>"; 
			html +="<small>Category: <i>"+ row.category_name +"</i> </small>"; 
			return html;
		}},

		// { data: 'category_name'},

		{ data: 'cost'},
		{ data: 'vat_value'},
		{ data: 'price'},
		{ data: 'total_quantity'},
		{ data: 'unit'},
		{ data: 'images', mRender: function(data, type, row) {
			if( data[0]){
				console.log(data[0]['image'])
				var link = '<div class="flag" style="width: 100%;"><img src="{assets_url('images/items/')}'+data[0]['image']+'" class="img-thumbnail" alt="Profile image"style="max-width: 70px;"></div>';
				return link;
			}
		}},
		{ data: 'type', mRender: function(data, type, row) {
			var html = data.replace(/_/g, " ").toUpperCase(); 

			return html; 
		}}, 
		{ data: 'id', mRender: function(data, type, row) {

			var  link= ''
			if(row.status != '0'){

				// <a data-id="Z1I0Njk4TVBtQ1pkZTNPMUZOSk1oZz09" class="edit_item" id="edit" title="Edit"><i class="material-icons text-rose" aria-hidden="true">edit</i></a>
				// <a data-id="Z1I0Njk4TVBtQ1pkZTNPMUZOSk1oZz09" class="delete_item" id="delete" title="Delete"><i class="material-icons text-danger" aria-hidden="true">delete</i></a></td>

				link +="<a rel='tooltip' title='Edit' data-id='"+row.enc_id+"' class='edit_item'><i class='material-icons text-success'>edit</i></i></a>";
				link +="<a rel='tooltip' title='Delete' data-id='"+row.enc_id+"' class='delete_item'><i class='material-icons text-danger'>delete</i></a>";
			}else{	
				link +='Deleted';
			}

			return link;
		}}, 
		],
		success: function(response) { 
		} 
	}); 

</script>
{/block}