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
								<div class="col-md-3">
									<div class="form-group">
										<select id="sales_id" name="sales_id" class="sales_code_ajax form-control" >
											{if $post_arr['sales_id']}
											<option value="{$post_arr['sales_id']}">{$post_arr['sales_code']}</option>
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


										<select class="selectpicker" data-size="7" data-style="select-with-transition" title="--ALL--" name="status" id="status" > 
											<option value="draft" {set_select('status', 'draft')}>Draft</option>
											<option value="submitted_for_approval" {set_select('status', 'submitted_for_approval')}>submitted for approval </option>
											<option value="approved" {set_select('status', 'approved')}>Approved </option>
											<option value="sent" {set_select('status', 'sent')}>sent </option>
											<option value="lost_or_win" {set_select('status', 'lost_or_win')}>lost or win  </option>
											<option value="deleted" {set_select('status', 'deleted')}>Deleted </option>
											<option value='all'  {set_select('status', 'all')}>--ALL--</option>

										</select> 

									</div>
								</div>
								<div class="col-md-2" style="max-width: 237px;">
									<div class="form-group">


										<select class="selectpicker" data-size="7" data-style="select-with-transition" title="--ALL--" name="type" id="type" > 
											<option value="revision" {set_select('type', 'revision')}>Revision</option>
											
											<option value="quotation" {set_select('status', 'quotation')}>Quotation </option>
											

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
					<h4 class="card-title">Available Quotations</h4>
				</div>
				<div class="card-body"> 
					<div class="table-responsive">
						<table class="table" id="sales_list">
							<thead class="bg-light text-warning">
								<tr>
									<th class="text-center">#</th>
									<!-- <th >Image</th> -->
									<th>Sales Code</th>
									<th>Date</th>
									<th>Customer Name</th>
									<th>Status</th> 
									<th>Discount </th>
									<th>Amount</th>
									<th>Approve</th>

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

		$('.sales_code_ajax').select2({

			placeholder: 'Select a Sales Code',
			ajax: {
				url:'{base_url()}admin/autocomplete/sales_code_ajax',

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



		$('body').on('click', '.edit-sales', function(){
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
					document.location.href = '{base_url()}' + "admin/sales/edit-sales/"+id; 
				} else {
					swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
				}
			});
		})

		$('body').on('click', '.create-revision', function(){
			var id = $(this).data('id');
			swal({
				title:'{lang('text_are_you_sure')}',
				text: "You will not recall",
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
					document.location.href = '{base_url()}' + "{log_user_type()}/sales/create-revision/"+id; 
				} else {
					swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
				}
			});
		})

		$('body').on('click', '.delete-sales', function(){
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
					document.location.href = '{base_url()}' +"admin/sales/sales-quotation/delete/"+id; 
				} else {
					swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
				}
			});

		}) 

		$('body').on('click', '.approve-sales', function(){
			var id = $(this).data('id');
			swal({
				title:'{lang('text_are_you_sure')}',
				text: "You will not recover",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: '{lang('text_yes_Approve_it')}',
				cancelButtonText: '{lang('text_no_cancel_please')}',
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function (isConfirm) {
				if (isConfirm) {
					document.location.href = '{base_url()}' + "admin/sales/approve_sales/"+id; 
				} else {
					swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
				}
			});
		})
		// $('body').on('click', '.approve-sales', function(){
		// 	var id = $(this).data('id');
		// 	swal({ 
		// 	$.confirm({
		// 		title: 'Confirm!',
		// 		content: 'Are You Sure to edit?? ',
		// 		buttons: {
		// 			confirm: function () {

		// 				document.location.href = '{base_url()}' + "admin/delivery/create-category/"+id; 

		// 			},
		// 			cancel: function () {
		// 				$.alert('Canceled!');
		// 			},

		// 		}
		// 	});

		// }



	// } );


	var order = $('#sales_list').DataTable({

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
		{ "bSortable": false, "aTargets": [ 0, 1, 2, 3, 4, 5, 6 ] },  
		],


		"columnDefs": [{
			"targets": 'no-sort',
			"orderable": false,
			"order": [],
		}],

		'ajax': {
			'url':'{base_url()}admin/sales/get_sales_list_ajax',
			'type': "POST", 
			'data' : {
				'type' : '{$post_arr['type']}',
				'sales_id' : '{$post_arr['sales_id']}',
				'start_date' : '{$post_arr['start_date']}',
				'end_date' : '{$post_arr['end_date']}',
				'status' : '{$post_arr['status']}',
			}

		},

		'columns': [

		{ data: 'index'},

		{ data: 'code', mRender: function(data, type, row) {

			var  html = '<p class="text-info">'+row.code+'</p>';
			html +="<p>"+ row.subject +" </p>";
			html +="<small>Total Items: <i>"+ row.total_items +"</i> </small>";
			html +="<br><small>Total Qty: <i>"+ row.total_qty +"</i> </small>";
			return html;
		}},

		{ data: 'date'},
		{ data: 'customer_name',mRender: function(data, type, row) {
			var  html = data ;
			html +="<br><small>Handled: <i>"+ row.salesman_name +"</i> </small>";
			return html;
		}},

		{ data: 'sales_status', mRender: function(data, type, row) {
			var html = data.replace(/_/g, " ").toUpperCase(); 
			if(row.type == 'revision')
				html += '<i class="clearfix"></i><span class="badge badge-info">'+ row.note +'</span>'

			return html; 
		}}, 
		{ data: 'discount_by_amount', mRender: function(data, type, row) {
			return  data+ ' (' +row.discount_by_percentage+ '%)';
		}},
		{ data: 'total_vat_inclusive'},
		{ data: 'approve', mRender: function(data, type, row){
			var  link= ''
			if(row.sales_status == 'deleted'){
				link +="<a rel='tooltip' ><i class='material-icons-failed'>deleted</i></a>";
			}
			else if(row.status != 'deleted' && row.status != 'approved'){

				link +="<a rel='tooltip' title='Approve' data-id='"+row.enc_id+"' class=' approve-sales'><i class='material-icons text-success'>done</i></i></a>";
			}
			else{
				link +='Approved'
			}
			link+=row.print_view;

			return link;
		}}, 

		{ data: 'id', mRender: function(data, type, row) {

			var  link= ''
			if(row.status != 'deleted'){

				link +="<a rel='tooltip' title='Edit' data-id='"+row.enc_id+"' class=' edit-sales'><i class='material-icons text-success'>edit</i></i></a>";
				link +="<a rel='tooltip' title='Delete' data-id='"+row.enc_id+"' class='delete-sales'><i class='material-icons text-danger'>delete</i></a>";
				link +="<a rel='tooltip' title='Create Revision' data-id='"+row.enc_id+"' class='create-revision'><i class='material-icons text-info'>receipt</i></a>";
				link +="<a href='{base_url()}admin/sales/print_details/"+row.enc_id+"'  rel='tooltip' title='Print Out' data-id='"+row.enc_id+"'class='print_out'><i class='material-icons fa fa-print'></i></a>";

			} 
			link+=row.print_view;

			return link;
		}}, 
		],
		success: function(response) { 
		} 
	});   

</script>

{/block}