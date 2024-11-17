{extends file="layout/base.tpl"}

{block header}  
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
{* <link href="{assets_url()}plugins/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css" rel="stylesheet" /> *}
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

									<select id="item_id" name="item_id" class="item_ajax form-control" >
										{if $post_arr['item_id']}
										<option value="{$post_arr['item_id']}">{$item_code}</option>
										{/if} 
									</select> 
									{form_error("item_id")}
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<select id="category_id" name="category_id" class="selectpicker" data-size="7" data-style="select-with-transition" >
										<option value="" disabled="" selected="">Select Category</option>
										<option value="" {if $post_arr['category_id']==''} selected="" {/if}>All </option>
										{foreach $categories as $c}

										<option value="{$c.id}" {if $c.id==$post_arr['category_id']} selected="" {/if}>{$c.category_name}</option>
										{/foreach}
										<!-- {if $post_arr['category_id']}
										<option value="{$post_arr['category_id']}">{$post_arr['category_name']}</option>
										{/if}  -->
									</select>
									{form_error("customer_name")}
								</div>
							</div>
							{* <div class="col-sm-3">
								<div class="form-group">
									<select id="customer_name" name="customer_name" class="customer_ajax form-control" >
										{if $post_arr['customer_name']}
										<option value="{$post_arr['customer_name']}">{$post_arr['cus_name']}</option>
										{/if} 
									</select>
									{form_error("customer_name")}
								</div>
							</div>
							
							<div class="col-sm-2">
								<div class="form-group">
									<select id="status" name="status" class="select2 form-control" > 
										<option value="draft" {set_select('status', 'draft')} >Draft</option>
										<option value="pending" {set_select('status', 'pending', true)} >Pending</option>
										<option value="inactive" {set_select('status', 'inactive')} >Inactive</option>


									</select>
									{form_error("mobile")}
								</div>
							</div>  *}
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
							{* <div class="row"> *}
								<div class="col-sm-3">
									<div class="form-group">
										<select id="stock" name="stock" class="selectpicker" data-size="7" data-style="select-with-transition" >
											<option value="2"  {if $post_arr['stock']=='2'} selected="" {/if}>All</option>

											<option value="1" {if $post_arr['stock']=='1'} selected="" {/if}>Ignore Zero Stock</option>
											<option value="3"  {if $post_arr['stock']=='3'} selected="" {/if}>Ignore Negantive Stock </option>

										</select>
										{form_error("customer_name")}
									</div>
								</div>

								<div class="col-sm-6"> 
									<button type="submit" class="btn btn-primary" name="search" value="search">
										<i class="fa fa-filter"></i> {lang('button_filter')}
									</button>
									<button type="submit" class="btn btn-warning mr-1" name="submit" value="reset">
										<i class="fa fa-refresh"></i>  {lang('button_reset')}
									</button> 
								</div>
							{* </div> *}
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

			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon hidden-print">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Available Projects</h4>

				<div class="d-flex align-items-end pull-right "> 

					<a href="#" onclick="javascript:window.print();"  class="btn btn-social-icon btn-block btn-sm mb-1 btn-github hidden-print"  ><i class="fa fa-print"></i></a>
				</div>

			</div> 
			<div class="card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="example">
						<thead class="bg-light text-warning">
							<tr>
								<th class="center">#</th>
								<th>Item Name</th>
								<th>Category</th>
								<th>cost</th>
								<th>VAT</th>
								<th>selling price</th>
								<th>Total Qty</th>
								{* <th>Item images</th> *}
								<th>Item type</th>
								<th>Date</th>
								
							</tr>
						</thead>
						
					</table>
				</div>
				
			</div>
		</div>
	</div>
</div>
{/if}
{/block}

{block footer} 


<script src="{assets_url('js/notify/notify-script.js')}"></script>
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
		$('.item_ajax').select2({

			placeholder: 'Select an Item Code',
			ajax: {
				url:'{base_url()}admin/autocomplete/item_ajax',

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

        // $('.category_ajax').select2({

        //     placeholder: 'Select Category',
        //     ajax: {
        //         url:'{base_url()}admin/autocomplete/category_ajax',

        //         type: 'post',
        //         dataType: 'json',
        //         delay:250,
        //         processResults: function(data) {
        //             return {
        //                 results: data
        //             };
        //         }
        //     },

        // });
		});  

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
				{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6, 7,8 ] },
				],

			"columnDefs": [{
				"targets": 'no-sort',
				"orderable": false,
				"order": [],
			}],

			'ajax': {
				'url':'{base_url()}admin/report/get_item_ajax',
				"type": "POST",
				"data" : {
					'start_date' : '{$post_arr['start_date']}',
					'end_date' : '{$post_arr['end_date']}',
					'category_id' : '{$post_arr['category_id']}',
					'item_id' : '{$post_arr['item_id']}',
					'stock_qty' : '{$post_arr['stock']}',

					
				}

			},
			'columns': [
				{ data: 'index' },
				{ data: 'name'},
				{ data: 'category_name'},
				{ data: 'cost'},
				{ data: 'value', mRender: function(data, type, row) {  
					return data+' %';


				} },
				{ data: 'price'},
				{ data: 'stock'},
			// { data: 'date'},


				{ data: 'type',
				mRender: function(data, type, row) {  
					if (data == "raw_materials") {
						return '<span class="badge badge-pill badge-warning">Raw Materials</span>'
					}
					else if(data == "finished_item") {
						return '<span class="badge badge-pill badge-danger">Finished Item </span>' 

					}

				} },

				{ data: 'date'},


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
</script>


<script type="text/javascript">

	
	$('.select2').select2();

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

					$.notify("Updated Successfully", "success");

				}
			})
		});
		
	});

</script>
{/block}
