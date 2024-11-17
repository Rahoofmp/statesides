{extends file="layout/base.tpl"}
{block header} 
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet"/>
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet"/>  
<link href="{assets_url()}plugins/DataTables/datatables.min.css" rel="stylesheet"/>
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/jquery-confirm.min.css') }">

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

	<div class="row">
		<div class="col-sm-12">
			{form_open_multipart("",'class="form form-horizontal FormValidation" ' )} 
			<div class="card ">
				<div class="card-header card-header-rose card-header-icon">
					<div class="card-icon">
						<i class="material-icons">inventory_2</i>
					</div>
					<h4 class="card-title">Edit</h4>
				</div>
				<div class="card-body  mt-3">
					<div class="col-sm-12">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">grading</i>
								</span>
							</div>
							<div class="col-sm-9">
								<div class="form-group">
									<label for="voucher_number" class="bmd-label-floating">Voucher number </label>
									<input type="text" class="form-control" id="voucher_number" name="voucher_number" required value="{$material_data['voucher_number']}" readonly=""> 

									{form_error('voucher_number')}
									<input type="hidden" name="issue_id" id="issue_id" value="{$material_data['id']}">
								</div>
							</div>
						</div>
					</div>

					<div class="col-sm-12">
						<div class="input-group form-control-lg">
							<div class="input-group-prepend">
								<span class="input-group-text"><i class="material-icons">date_range</i>
								</span>
							</div>
							<div class="col-sm-9">
								<div class="form-group">
									<label for="voucher_date" class="bmd-label-floating">Voucher Date </label>
									<input type="text" class="form-control datepicker" id="voucher_date" name="voucher_date" required value="{$material_data['voucher_date']}">					
									{form_error('voucher_date')}

								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">

									<input type="submit" class="form-control btn btn-info" id="voucher_update" name="voucher_update" value="Update">					

								</div>
							</div>
						</div>
					</div>
					<div class="mt-4">
						<div class="col-md-12">
							{if $material_data['items']}
							<div class="table-responsive">
								<table class="table">
									<thead class="bg-light">
										<tr> 
											<th>No.</th>
											
											<th>Job Order</th>
											<th>Material Name</th>
											<th>Item Name</th> 
											<th>Allocated Qty</th> 
											<!-- <th>Unit</th>  -->
											<th>Issued Qty</th> 
											<th>Difference</th> 
											
											<th>Action</th>
										</tr>
									</thead>
									<tbody>

										{foreach $material_data['items'] as $v} 

										<tr>
											<td >{counter}</td>
											<td>{$v.job_order}</td>
											<td>{$v.name}</td>
											<td>{$v.item_name}</td>
											<td>{$v.total_qty}</td> 
											<td>{$v.issued_qty}</td> 
											<td>{$v.diff}</td>      
											<td class="td-actions text-center ">

												<a rel="tooltip" title="Delete" href="javascript:remove_material_receipt('{$v.enc_id}', '{$material_data['enc_id']}')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>


											</td>     
										</tr>
										{/foreach}

									</tbody>
								</table>
							</div>
							{else}
							<div class="card-body">
								<p>
									<h4 class="text-center"> 
										<i class="fa fa-exclamation"> No Items Found</i>
									</h4>
								</p>
							</div>
							{/if}
						</div>
					</div> 
					{form_close()}
					<div class="col-sm-12">

						<div class="card-collapse">
							<div id="headingTwo" class="card-header" role="tab">
								<div class="mb-0">
									<a data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="collapsed">
										ADD ITEMS
										<i class="material-icons">keyboard_arrow_down</i>
									</a>
								</div>
							</div>
							<div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
								<div class="card-body">  
									{form_open('','name="item_add"  id="add-items-form" class="form-login"')}

									<div class="col-sm-12 product-items">
										<div class="row">
											<div class="col-sm-3">
												<div class="form-group">
													<select id="project_ajax" name="project_id" class="project_ajax form-control">
													</select> 
												</div>
											</div>
											<div class="col-sm-3">

												<div class="form-group">
													<select name="job_orderid" class="job_orderid_ajax form-control" id="job_orderid_ajax" >
													</select> 
												</div>
											</div>
											<div class="col-sm-3">

												<div class="form-group">
													<select name="item_ajax" class="item_ajax form-control" id="item_ajax" >
													</select> 
												</div>
											</div> 
											<div class="col-sm-3">
												<div class="form-group">
													<input type="text" class="form-control" id="qty"  name="qty" placeholder="Issuing Quantity" required> 
												</div>
											</div>
										</div>
									</div>


									<table id="example" class="display" style="width:100%">
										<thead>
											<tr> 
												<th>No.</th>

												<th>Job Order</th>
												<th>Material Name</th>
												<!-- <th>Item Name</th>  -->
												<th>Allocated Qty</th> 
												<!-- <th>Unit</th>  -->
												<th>Issued Qty</th> 
												<th>Difference</th> 
												<th>Issuing Qty </th> 
												<th>Action</th> 
											</tr>
										</thead> 
									</table>


									<button type="button"  name="items_update" class="btn btn-info items_update" value="save" > Save </button>
									<button type="button"  name="items_update" class="btn btn-primary items_update" value="save_exit" > Save & Exit</button>

								</div>
							</div>
						</div>

					</div>

					<hr>
					{* 	<div class="card-footer text-right">
						<div class="form-check mr-auto"></div>
						{if $id}
						<button class="btn btn-rose" type="submit" id="add_project" name="update" value="add_project">
							Update <i class="fa fa-arrow-circle-right"></i>
						</button>
						{else}
						<button class="btn btn-rose" type="submit" id="add_project" name="submit" value="add_project">
							Create <i class="fa fa-arrow-circle-right"></i>
						</button>   
						{/if}
					</div> *}
				</div>
			</div>
			{form_close()}

		</div>  
	</div>  
	{/block}
	{block footer}

	<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

	<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>
	<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

	<script src="{assets_url('js/notify/bootstrap-notify.min.js')}"></script>
	<script src="{assets_url('js/notify/notify-script.js')}"></script>

	<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
	<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
	<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
	<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

	<script src="{assets_url('js/notify/bootstrap-notify.min.js')}"></script>
	<script src="{assets_url('js/notify/notify-script.js')}"></script>
	<script src="{assets_url('js/jquery-confirm.min.js') }"></script>

	<script>
		$(document).ready(function() { 
			md.initFormExtendedDatetimepickers();
		});

		$(document).ready(function(){ 
		$('.project_ajax').select2({

			placeholder: 'Select a project',
			ajax: {
				url:'{base_url(log_user_type())}/autocomplete/project_ajax',
				// data: function (params) {

				// 	var customer_id = $( "select#customer_id option:checked" ).val() ;
				// 	var searchString = $( "select#project_id option:checked" ).val() ;

				// 	var query = {
				// 		customer_id: customer_id,
				// 		project_id: searchString,
				// 		type: 'public'
				// 	}
				// 	return query;
				// },

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
		$('.project_ajax').on('select2:select', function (e) {
			$('.job_orderid_ajax').select2('open');
		});
		var project_ajax = $('#project_ajax');
		project_ajax.change( function(){  

			$('#job_orderid_ajax').val(null).trigger('change');
		});

		$('.job_orderid_ajax').select2({
			placeholder: 'Select Job Order Id',
			ajax: {
				url:'{base_url(log_user_type())}/autocomplete/job_orderid_with_name_ajax',
				data: function (params) {
					var project_id = $( "select#project_ajax option:checked" ).val() ;
					var query = {
						project_id:project_id,
						q: params.term,
						with_name: true
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

		$('.job_orderid_ajax').on('select2:select', function (e) {
			$('.item_ajax').select2('open');
		});
		var job_orderid_ajax = $('#job_orderid_ajax');
		job_orderid_ajax.change( function(){  

			$('#item_ajax').val(null).trigger('change');
		});


		$('.item_ajax').select2({

			placeholder: 'Select a Item',
			ajax: {
				url:'{base_url()}{log_user_type()}/material/item_with_name_ajax',
				data: function (params) {
					var job_orderid = $( "select#job_orderid_ajax option:checked" ).val() ;
					var query = {
						job_orderid: job_orderid,
						q: params.term,
						with_code: true,
						with_name: true
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

			$('.supplier_id').select2({

				placeholder: 'Select a supplier',
				ajax: {
					url:'{base_url()}admin/autocomplete/supplier_ajax',

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

			$('.material_receipt_ajax').select2({

				placeholder: 'Select a bill number',
				ajax: {
					url:'{base_url(log_user_type())}/autocomplete/material_receipt_ajax',

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


		// $('.job_orderid_ajax').on('select2:select', function (e) {
		// 	$('.item_ajax').select2('open');
		// });

		// $('.item_ajax').select2({

		// 	placeholder: 'Select a Material Items',
		// 	ajax: {
		// 		url:'{base_url()}admin/autocomplete/item_ajax',

		// 		type: 'post',
		// 		dataType: 'json',
		// 		delay:250,
		// 		processResults: function(data) {
		// 			return {
		// 				results: data
		// 			};
		// 		}
		// 	},

		// });
		// $('.item_ajax').on('select2:select', function (e) {
		// 	$('#qty').focus();
		// });

		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				addRow(); 
				return false;
			}
		});

		$("#qty").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});


		var table = $('#example').DataTable({ searching: false, paging: false, info: false,responsive: true});
		var counter = 1;

		$('#example').on('click', '.remove', function() {
			var row = $(this).parents('tr');

			if ($(row).hasClass('child')) {
				table.row($(row).prev('tr')).remove().draw();
			} else {
				table
				.row($(this).parents('tr'))
				.remove()
				.draw();
			}
		});


		function addRow () {
			var validFrom = true;
			$(".product-items :input").each(function(){
				var input = $(this); 
				if(input.val() == ''){
					input.focus();
					validFrom = false;
					return false;
				}
			});



			if(validFrom == true){
				var form_data = table
				.rows()
				.data();

				$.ajax({
					type:'POST',
					url:"{base_url(log_user_type())}/material/check-material-items",
					data: { 
						material_id: $('.material_receipt_ajax').find(":selected").val(),
						item_id: $('.item_ajax').find(":selected").val(),
						job_orderid: $('.job_orderid_ajax').find(":selected").val(),
						project_id: $('.project_ajax').find(":selected").val(),
						item_qty: $('#qty').val(),
						// item_name: $('.item_ajax').find(":selected").text(),
						form_data: JSON.stringify(form_data.toArray()),

					}
				})
				.done(function( response ) { 

					if(response.status && response.data){
						const data = response.data;
						console.log(data)
						table.row.add( [
							counter,
							

							$('#job_orderid_ajax').text(),
							// data.name,
							$('#item_ajax').text(),
							data.qty,
							data.issued_qty,
							data.difference,
							$('#qty').val(),
							'<button type="button" class="btn btn-danger btn-sm remove"><i class="material-icons">delete</i></button>'
							] ).draw( false ).node();
						counter++;
						$('.product-items input').val('');
						$('.item_ajax').val(null).trigger('change').select2('open');
						// $('.item_ajax').val(null).trigger('change');

					}else{
						// alert(response.msg);
						$.notify(response.msg,  { type: 'warning' });
						// $.notify(response.msg, 'danger');


					}
				}); 

			}
		} ;

		$('.items_update').on('click', function(){
			var btnVal = $(this).val();

			var form_data = table
			.rows()
			.data();

			$.ajax({
				type:'POST',
				url:"{base_url('admin/material/update-issue-items')}",
				data: { 
					issue_id: $('#issue_id').val(),
					voucher_date: $('#voucher_date').val(),
					voucher_number: $('#voucher_number').val(),

					data: JSON.stringify(form_data.toArray()) } ,
					dataType:'json',
				})
			.done(function( response ) {
				url  = '{base_url()}'+'admin/material/issue-list';
				if(response.success){
					document.location.href = url

				}else{
					$.notify(response.msg,  { type: 'warning' });

				}
			}); 

		});



	}); 
function remove_material_receipt(material_item_id, issue_id)
{

	$.confirm({
		title: 'Confirm!',
		content: 'Are You Sure to delete item?? ',
		buttons: {
			confirm: function () {
				document.location.href = '{base_url()}' + "admin/material/remove-material-reciept/"+material_item_id+'/'+issue_id;
			},
			cancel: function () {
				$.alert('Canceled!');
			},

		}
	});
}	 

</script>



{/block}
