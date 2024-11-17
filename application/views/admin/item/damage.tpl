{extends file="layout/base.tpl"}
{block header} 
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" />  
<link href="{assets_url()}plugins/DataTables/datatables.min.css" rel="stylesheet" />
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
{/block}
{block body}

<div class="row">
	<div class="col-sm-12">
		{form_open_multipart("",'class="form form-horizontal FormValidation" ' )} 
		<div class="card ">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">inventory_2</i>
				</div>
				<h4 class="card-title">Create</h4>
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
								<input type="text" class="form-control" id="voucher_number" name="voucher_number" required value="{$voucher_number}" readonly=""> 
								{form_error('voucher_number')}
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
								<input type="text" class="form-control datepicker" id="voucher_date" name="voucher_date" required value="{set_value('voucher_date')}">					
								{form_error('voucher_date')}

							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">square</i>
							</span>
						</div>
						<div class="col-sm-9">
							<div class="form-group">
								<label for="voucher_type" class="bmd-label-floating">Voucher Type</label>
								<select class="selectpicker"data-size="7" data-style="select-with-transition"  name="voucher_type" id="voucher_type">
									<option value="Damage">Damage</option>
									<option value="Loss">Loss</option>
								</select>							
								{form_error('voucher_type')}

							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">person</i>
							</span>
						</div>
						<div class="col-sm-9">
							<div class="form-group">
								<label for="voucher_entered_by" class="bmd-label-floating">Voucher Entered By </label>
								<select id="voucher_entered_by" name="voucher_entered_by" class="voucher_entered_by form-control" >
									{if $smarty.post.voucher_entered_by}
									<option value="{$smarty.post.voucher_entered_by}">{$voucher_entered_name}</option>
									{/if}
								</select>					

								{form_error('voucher_entered_by')}

							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">rectangle</i>
							</span>
						</div>
						<div class="col-sm-9">
							<div class="form-group">
								<label for="damaged_lost_by" class="bmd-label-floating">Damaged/Lost By</label>

								<select id="damaged_lost_by" name="damaged_lost_by" class="damaged_lost_by form-control" >
									{if $smarty.post.damaged_lost_by}
									<option value="{$smarty.post.damaged_lost_by}">{$damager_name}</option>
									{/if}
								</select>					

								{form_error('damaged_lost_by')}

							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-12">
					<div class="input-group form-control-lg">
						<div class="input-group-prepend">
							<span class="input-group-text"><i class="material-icons">circle</i>
							</span>
						</div>
						<div class="col-sm-9">
							<div class="form-group">
								<label for="reported_by" class="bmd-label-floating">Reported By</label>
								
								<select id="reported_by" name="reported_by" class="reported_by form-control" >
									{if $smarty.post.reported_by}
									<option value="{$smarty.post.reported_by}">{$reported_name}</option>
									{/if}
								</select>					

							</div>
						</div>
					</div>
				</div>



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
												<input type="text" class="form-control" id="qty"  name="qty" placeholder="Damaging Quantity" required> 
											</div>
										</div>
										{* <div class="col-sm-3">
											<div class="form-group">
												<select id="damage_lose" name="damage_lose" class="form-control">
													<option value="damage">Damage</option>
													<option value="loss">Loss</option>
												</select> 
											</div>
										</div>  *}
										<div class="col-sm-3">
											<div class="form-group">
												<input type="text" class="form-control" id="remarks"  name="remarks" placeholder="Remarks" required> 
											</div>
										</div> 
									</div>
								</div>
								{* <div class="col-sm-12 product-items">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<select id="material_receipt_ajax" name="job_orderid" class="material_receipt_ajax form-control">
												</select> 
											</div>
										</div>

										<div class="col-sm-6">
											<div class="form-group">
												<input type="text" class="form-control" id="qty"  name="qty" placeholder="Issuing Quantity" required> 
											</div>
										</div>
									</div>
								</div> *}
								{form_close()}


								<table id="example" class="display" style="width:100%">
									<thead class="bg-dark text-warning">
										<tr> 
											<th>No.</th>
											
											<th>Job Order</th>
											<th>Material Name</th>
											<th>Remarks</th>
											{* <th>Damage Loss</th> *}
											<!-- <th>Item Name</th>  -->
											<th>Allocated Qty</th> 
											<!-- <th>Unit</th>  -->
											<th>Damaged Qty</th> 
											<th>Difference</th> 
											<th>Damaging Qty </th> 
											<th>Action</th> 
										</tr>
									</thead> 
								</table>

								<button type="button"  name="items_update" class="btn btn-info items_update" value="save" > Save </button>
								<button type="button"  name="items_update" class="btn btn-primary items_update" value="save_exit" > Save & Create New</button>

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



<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});

	$(document).ready(function(){ 

		$('.voucher_entered_by').select2({

			placeholder: 'Select a Entered By',
			ajax: {
				url:'{base_url()}admin/autocomplete/return_employee_ajax',

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

		
		$('.damaged_lost_by').select2({

			placeholder: 'Select Damaged/Losted Employee',
			ajax: {
				url:'{base_url()}admin/autocomplete/return_employee_ajax',

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
		$('.reported_by').select2({

			placeholder: 'Select Return  Employee',
			ajax: {
				url:'{base_url()}admin/autocomplete/return_employee_ajax',

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
				url:'{base_url()}{log_user_type()}/autocomplete/item_with_name_ajax',
				data: function (params) {
					var items_arr=['consumables','tools'];

					// var job_orderid = $( "select#job_orderid_ajax option:checked" ).val() ;
					var query = {
						// job_orderid: job_orderid,
						q: params.term,
						with_name: true,
						items_arr:items_arr

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
					url:"{base_url(log_user_type())}/item/check-consumable-issue-items",
					data: { 
						material_id: $('.material_receipt_ajax').find(":selected").val(),
						item_id: $('.item_ajax').find(":selected").val(),
						job_orderid: $('.job_orderid_ajax').find(":selected").val(),
						project_id: $('.project_ajax').find(":selected").val(),
						// item_name: $('.item_ajax').find(":selected").text(),
						item_qty: $('#qty').val(),
						item_remarks: $('#remarks').val(),
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
							$('#remarks').val(),
							// $('#damage_lose').val(),
							data.total_issued_qty,
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
				url:"{base_url('admin/item/save_damage_items')}",
				data: { 
					voucher_date: $('#voucher_date').val(),
					voucher_number: $('#voucher_number').val(),
					damaged_lost_by: $('#damaged_lost_by').val(),
					voucher_entered_by: $('#voucher_entered_by').val(),
					voucher_type: $('#voucher_type').val(),
					reported_by: $('#reported_by').val(),

					data: JSON.stringify(form_data.toArray()) } ,
					dataType:'json',
				})
			.done(function( response ) {
				url  = '{base_url()}'+'admin/item/damage-list';
				if(response.success){
					document.location.href = url

				}else{
					$.notify(response.msg,  { type: 'warning' });

				}
			}); 

		});

	});  
</script>



{/block}
