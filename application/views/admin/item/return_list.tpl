{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
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
	@media print {
		table tr td p,table tr td { 
			font-size:12px;
			padding: 0px;
			margin: 0px;
			margin-top:5px; 
		}

	}
	.neg-difference{
		color: red;
	}
	.pos-difference{
		color: green;
	}
	table.dataTable thead > tr > th.sorting{
		text-align: left !important;
	</style>
	<div class="row "> 
		<div class="col-sm-12 hidden-print"> 
			<div class="card"> 
				<div class="card-content">
					<div class="card-body"> 
						{form_open('','')}
						<div class="form-body">
							<div class="row">
						{* 		<div class="col-md-3">
									<div class="form-group">
										<select id="project_id" name="project_id" class="project_ajax form-control" >
											{if $post_arr['id']}
											<option value="{$post_arr['id']}">{$post_arr['project_name']}</option>
											{/if} 
										</select> 
									</div>
								</div>  *}
								<div class="col-md-3">
									<div class="form-group">
										<select id="issue_id" name="issue_id" class="consumable_return_ajax form-control" >
											{if $post_arr['issue_id']}
											<option value="{$post_arr['issue_id']}">{$post_arr['voucher_number']}</option>
											{/if} 
										</select> 
									</div>
								</div>


								<div class="col-md-3">
									<div class="form-group">
										<label for="issueinput3">Voucher Date</label>
										<input type="text"  class="form-control datepicker" name="voucher_date"
										id="voucher_date" value="{$post_arr['voucher_date']}">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="issueinput3">From Date (Created Date)</label>
										<input type="text"  class="form-control datepicker" name="start_date" value="{$post_arr['start_date']}">
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label for="issueinput3">To Date (Created Date)</label>
										<input type="text"  class="form-control datepicker" name="end_date" value="{$post_arr['end_date']}">
									</div>
								</div>
								<div class="col-md-2" style="max-width: 237px;">
									<div class="form-group">
										<select class="selectpicker" data-size="7" data-style="select-with-transition" title="Pending" name="status" id="status" >
											<option value='active' {set_select('status', 'active', TRUE)}>Active</option>
											<option value='deleted' {set_select('status', 'deleted')}>Deleted</option> 
											<option value='0'  {set_select('status', '0')}>--ALL--</option>
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
					<h4 class="card-title">Available Receipts</h4>
				</div>
				<div class="card-body"> 
					<div class="table-responsive">
						<table class="table" id="material_issue">
							<thead class="bg-light text-warning">
								<tr>
									<th class="text-center">#</th>
									<th>Voucher number</th>
									<th>Voucher Date</th>
									<th>Return By</th>
									<th>Received By</th>
									<th>Allocated Quantity</th>
									<th>Return Quantity</th>
									<th>Difference</th>
									
									<th>Created On</th>
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
	<script src="{assets_url('js/scripts/tables/datatables-extensions/datatable-autofill.min.js')}"></script>

	<script src="{assets_url('js/ui-notifications.js')}"></script>
	<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>

	<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
	<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
	<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
	<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>



	<script>
		$(document).ready(function() { 
			md.initFormExtendedDatetimepickers();
		});

		$('.consumable_return_ajax').select2({

			placeholder: 'Select a Voucher number',
			ajax: {
				url:'{base_url(log_user_type())}/autocomplete/consumable_return_ajax',

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



		$('body').on('click', '.edit-package', function(){
			var id = $(this).data('id');
			swal({
				title:'{lang('text_are_you_sure')}',
				text: "You will not recover",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: '{lang('text_yes_update_it')}',
				cancelButtonText: '{lang('text_no_cancel_please')}',
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function (isConfirm) {
				if (isConfirm) {
					document.location.href = '{base_url(log_user_type())}' + "/item/edit_return/"+id; 
				} else {
					swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
				}
			});
		})



		var order = $('#material_issue').DataTable({

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
				'url':'{base_url(log_user_type())}/item/get_material_return_ajax',
				'type': "POST", 
				'data' : {
					'voucher_number' : '{$post_arr['voucher_number']}',
					'voucher_date' : '{$post_arr['voucher_date']}',
					'start_date' : '{$post_arr['start_date']}',
					'end_date' : '{$post_arr['end_date']}',
					'status' : '{$post_arr['status']}',
					'project_id':"{$post_arr['project_id']}"
				}

			},

			'columns': [

				{ data: 'index'},
				{ data: 'voucher_number'},
				{ data: 'voucher_date'},
				{ data: 'return_name'},
				{ data: 'receiver_name'},
				{ data: 'allocated_qty'},
				// { data: 'total_issued_qty'},
				{ data: 'total_issued_qty'},
				{ data: 'diff'},
				{ data: 'date_added'},
				{ data: 'id', mRender: function(data, type, row) {

					var  link= ''
					if(row.status != 'deleted'){

						link +="<a rel='tooltip' title='Edit' data-id='"+row.enc_id+"' class=' edit-package'><i class='material-icons text-success'>edit</i></i></a>";
						link +="<a rel='tooltip' title='Delete' data-id='"+row.enc_id+"' class='delete-issue'><i class='material-icons text-danger'>delete</i></a>";
						link +="<a href='{base_url()}admin/item/return_history/"+row.enc_id+"'  rel='tooltip' title='Print Out' data-id='"+row.enc_id+"'class='print_out'><i class='material-icons fa fa-print'></i></a>";
					}else{	
						link +='Deleted';
					}

					return link;
				}}, 
				],
			success: function(response) { 
			} 
		});   


		$('body').on('click', '.delete-issue', function(){
			var id = $(this).data('id');
			swal({
				title:'{lang('text_are_you_sure')}',
				text:"You Will not recover",
				type:"warning",
				showCancelButton:true,
				confirmButtonColor:"#DD6B55", 
				confirmButtonText: '{lang('text_yes_delete_it')}',
				cancelButtonText: '{lang('text_no_cancel_please')}',
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function (isConfirm) {
				if (isConfirm) {
					document.location.href = '{base_url()}' +"admin/item/return-list/delete/"+id; 
				} else {
					swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
				}
			});

		})
		
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
	</script>

	{/block}