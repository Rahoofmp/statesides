{extends file="layout/base.tpl"}

{block header} 

<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" />  
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/DataTables/datatables.min.css" rel="stylesheet" />
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />

{/block}

{block body}

<div class="row">
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-header card-header-rose card-header-text">
				<div class="card-icon">
					<i class="material-icons">library_books</i>
				</div>
				<h4 class="card-title">Sales Quotation</h4>
			</div>

			<div class="card-body">
				<div id="accordion" role="tablist">
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingOne">
							<h5 class="mb-0">
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
									SALES INFO 
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
							{form_open_multipart('','name="release_payout"  id="payout_release" class="form-login"')}
							<div class="card-body">
								<div class="row">
									<div class="col-lg-2">
										<div class="input-group form-control-lg">

											<div class="col-sm-12">
												<div class="form-group">

													<input type="text" name="code" class="form-control" value="{$details['code']}" readonly="">
													{form_error("code")}
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-5">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">person_add</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">
													<label for="exampleInput1" class="bmd-label-floating">Subject</label>
													<input type="text" id="subject" class="form-control" name="subject" value="{$details['subject']}" required autocomplete="off">
													{form_error('subject')}
												</div> 
											</div>
										</div>
									</div>
									<div class="col-lg-5">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">date_range</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating">
														Quotation date
													</label>
													<input type="text" name="date" class="form-control datepicker" value="{$details['date']}">
													{form_error("date")}
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="input-group form-control-lg"> 
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating">
														Total Amount
													</label>
													<input type="text" name="total_vat_inclusive"
													id="total_vat_inclusive" class="form-control" value="{$details['total_vat_inclusive']}" disabled="">

												</div>
											</div>
										</div>
									</div> 
									<div class="col-lg-4">
										<div class="input-group form-control-lg"> 
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating">
														Discount By Amount
													</label>
													<input type="text" name="date" class="form-control" value="{$details['discount_by_amount']}" disabled="">

												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="input-group form-control-lg"> 
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating">
														Discount By Percentage
													</label>
													<input type="text" name="date" class="form-control" value="{$details['discount_by_percentage']}" disabled="">

												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">person</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating"> Customer Name </label>

													<select id="customer_name" name="customer_name" class="customer_ajax form-control" >
														{if $details['customer_id']}
														<option value="{$details['customer_id']}">{$details['customer_name']}</option>
														{/if} 

													</select> 
													{form_error("customer_name")} 
												</div>
											</div>
										</div>
									</div>



									<div class="col-lg-6 m-auto">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">person</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">
													<select id="salesperson" name="salesperson" class="form-control" >
														{if $details['salesperson']}
														<option value="{$details['salesperson']}" selected>{$details['salesman_name']}</option>
														{/if} 
													</select>
													{form_error("salesperson")}
												</div>
											</div>
										</div>
									</div>


								{* 	<div class="col-lg-6">
										<div class="input-group form-control-lg">

											<div class="col-sm-11">
												<div class="form-group">
													<div class="col-sm-10 checkbox">
														<div class="form-check form-check-inline">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="payment"  name="tc_type" {if $details['tc_type'] == 'payment'}checked{/if} > Payment terms and condition
																<span class="circle">  <span class="check"></span> </span>
															</label>
														</div> 
														<div class="form-check form-check-inline">
															<label class="form-check-label">
																<input class="form-check-input" type="radio" value="normal" name="tc_type" {if $details['tc_type'] == 'normal'}checked{/if} >  Normal terms and condition
																<span class="circle">  <span class="check"></span> </span>
															</label>
														</div> 
														{form_error("tc_type")}  
													</div>  
												</div>
											</div>
										</div>
									</div> *}
									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">gavel</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group"> 
													<select id="payment_terms_id" name="payment_terms_id" class="form-control">
														{if $details['payment_terms_id']}<option value="{$details['payment_terms_id']}">{$details['payment_name']} </option>{/if}
													</select> 
													{form_error('payment_terms_id')}

												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">gavel</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">

													<select id="normal_terms_id" name="normal_terms_id" class="form-control">
														{if $details['terms_conditions']}<option value="{$details['terms_conditions']}">{$details['normal_payment_name']} </option>{/if}
													</select> 
													{form_error('normal_terms_id')}

												</div>
											</div>
										</div>
									</div>

									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">schedule</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating"> Status </label>

													<select id="status" name="status" class="selectpicker" data-style="select-with-transition" title="Status" >

														<option value="draft" {if
															$details['status']=='draft'}selected{/if}>Draft
														</option>
														<option value="submitted_for_approval" {if
															$details['status']=='submitted_for_approval'}selected{/if}>Submitted for approval 
														</option>
														<option value="approved" {if
															$details['status']=='approved'}selected{/if}>Approved 
														</option>
														<option value="sent"{if
															$details['status']=='sent'}selected{/if} >Sent 
														</option>
														<option value="lost_or_win" {if
															$details['status']=='lost_or_win'}selected{/if} >Lost or win  
														</option>
													</select> 
													{form_error('status')}
												</div>
											</div>

										</div>
									</div>
									{* <div class="col-lg-12" >
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">gavel</i>
												</span>
											</div>
											<div class="col-sm-11">
												<div class="form-group">
													<label class="bmd-label-floating"> Normal Terms & Conditions  </label>
													<textarea type="text" class="form-control" id="terms_conditions" name="terms_conditions" >{$details['terms_conditions']}</textarea>
													{form_error('terms_conditions')}

												</div>
											</div>
										</div>
									</div>  *}
									<div class="col-lg-12">
										<input type="submit" name="update" id="update" class="btn btn-primary pull-right" value="Update">
									</div>

								</div>

								<div class="mt-4">
									<div class="col-md-12">
										{if $details['items']}
										<div class="table-responsive">
											<table class="table" style="table-layout: fixed; width: 100%;">
												<thead class="bg-light">
													<tr>
														<th width="50px;">#</th>
														<th>Code</th>
														<th width="250px;">Item Name</th>
														<th>Added Date</th>
														<th width="50px;">Qty</th>
														<th>Unit Price</th>
														<th width="50px;">Vat</th>
														<th>Total</th>
														<th>Item Images</th>
														<th width="50px;" class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>

													{foreach $details['items'] as $v} 

													<tr>
														<td >{counter}</td>
														<td>{$v.code}</td>
														<td>
															{$v.name} <br> <b>Spec:</b>{$v.spec}
														</td>
														<td>{$v.date_added}</td>  
														<td>{$v.quantity}</td> 
														<td>{$v.total_price/$v.quantity}</td>
														<td>{$v.vat_perc}%</td>
														<td>{$v.vat_inclusive}</td> 
														<td>
															{foreach $v['item_images'] as $i}
															<a href="{assets_url()}images/items/{$i.image}" target="_blank"> <img src="{assets_url()}images/items/{$i.image}" width="100px;" height="100px;"></a><br>
															{/foreach}
														</td>  
														<td class="td-actions text-center ">
															<a rel="tooltip" title="edit"  class="btn btn-success btn-sm edit-progress"  data-id="{$enc_id}" data-item_id="{$v.id}"><i class="material-icons">edit</i></a>

															<a rel="tooltip" title="Delete" href="javascript:remove_sales_item('{$v.enc_id}', '{$enc_id}')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>
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

							</div>
							{form_close()}
						</div>
					</div> 

				</div>
				<div class="card-collapse">
					<div class="card-header" role="tab" id="headingTwo">
						<h5 class="mb-0">
							<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								ADD NEW  ITEMS
								<i class="material-icons">keyboard_arrow_down</i>
							</a>
						</h5>
					</div>
					<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
						<div class="card-body">  
							{form_open('','name="item_add"  id="add-items-form" class="form-login"')}

							<div class="col-sm-12 product-items">
								<div class="row">
									<div class="col-sm-4">
										<div class="form-group">
											<select id="item_id" name="item_id" class=" form-control item_ajax" required="" style="width:100%"></select> 
											{form_error("item_id")}
										</div>
									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<input type="number" class="form-control" id="quantity" name="quantity" value="{set_value('quantity')}"  required="true" autocomplete="Off" placeholder="Quantity">
											{form_error("quantity")}
										</div>

									</div>
									<div class="col-sm-4">
										<div class="form-group">
											<input type="text" class="form-control" id="rate" name="rate" value="{set_value('rate')}"  required="true" autocomplete="Off" placeholder="Rate">
											{form_error("rate")}
										</div>
									</div>
									<div class="col-sm-10">
										<div class="form-group">
											<textarea class="form-control" required="true" required="true" id="note" name="note"></textarea> 
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<button type="button"  class="add btn btn-facebook" name="add"  value="Add"  />Add</button>
										</div>
									</div>


								</div>
							</div>
							{form_close()}


							<table id="example" class="display" >
								<thead class="bg-dark text-warning">
									<tr>
										<th width="50px;">No.</th>
										<th>Item Code</th>
										<th width="150px;">Item</th>
										<th>Unit Price</th>
										<th width="50px;">VAT</th>
										<th width="50px;">Qty</th>
										<th>Rate</th>
										<th>Total (Incl. VAT)</th>
										<th>IMAGE</th>

										<th width="50px;">Action</th> 
									</tr>
								</thead> 

								<tfoot>
									<tr class="font-weight-bold font-italic">
										<td>TOTAL</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td id="qty-sum">0</td>
										<td id="total">0</td>
										<td id="total_inclusive">0</td>
										<td></td>
										<td></td>

									</tr>
								</tfoot>
							</table>

							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Add Discount</button>



							<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Add Discount</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<form>
												<div class="form-group">
													<label for="recipient-name" class="col-form-label" id="by_amount_label">Discount BY Amount</label>
													<input type="text" class="form-control" id="by_amount" name="by_amount" placeholder="Discount BY Amount" onblur="getDiscountPercentage();" value="{$details['discount_by_amount']}">
												</div>
												<div class="form-group">
													<label for="message-text" class="col-form-label" id="by_percentage_label">Discount By Percentage</label>
													<input type="text" class="form-control" id="by_percentage" name="by_percentage" onblur="getDiscountAmount();" value="{$details['discount_by_percentage']}" placeholder="Discount By Percentage">
												</div>
												<div class="form-group" style="display: none;">
													<label for="message-text" class="col-form-label" id="by_percentage_label">Terms & Conditions</label>
													<textarea type="text" class="form-control" id="terms_conditions" name="terms_conditions" required="">{$details['terms_conditions']}</textarea>
												</div>
												<input type="hidden" name="enc_id" id="enc_id" value="{$enc_id}">
											</form>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
											<button type="button" class="btn btn-primary items_update">Add Items</button>
										</div>

									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 
<div class="modal fade" id="empModal" role="dialog">
	<div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Edit Items</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body empEditBody">	

			</div> 
		</div>
	</div>
</div>

{/block}

{block footer} 
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>

<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  

<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
<script src="{assets_url('js/ckeditor.js') }"></script>  
<script type="text/javascript">

{* </script>
<script type="text/javascript">
 *}
// var payment_terms_id = $('#payment_terms_id'); 
// payment_terms_id.select2({
// 	dropdownAutoWidth: !0,
// 	width: "100%"
// });

// openTerms($("input[name='tc_type']:checked").val())     
// $('input[name="tc_type"]').change(function(){
// 	$('.terms_conditions_ajax').val(null).trigger('change');

// 	openTerms($(this).val());
// });

// function openTerms(tc_type){ 
// 	$('.terms_conditions_ajax').select2({

// 		placeholder: 'Select Terms And Conditions',
// 		ajax: {
// 			url:'{base_url()}admin/autocomplete/terms_conditions_ajax',

// 			type: 'post',
// 			data: { 
// 				tc_type: tc_type,
// 				type: 'ajax'
// 			},
// 			dataType: 'json',
// 			delay:250,
// 			processResults: function(data) {
// 				return {
// 					results: data
// 				};
// 			}
// 		},
// 	}); 
// }
	$('#payment_terms_id').select2({

		placeholder: 'Select Payment T&C',
		ajax: {
			url:'{base_url()}admin/autocomplete/terms_conditions_ajax',

			type: 'post',
			data: { 
				tc_type: 'payment',
				type: 'ajax'
			},
			dataType: 'json',
			delay:250,
			processResults: function(data) {
				return {
					results: data
				};
			}
		},
	}); 
	$('#normal_terms_id').select2({

		placeholder: 'Select Normal T&C',
		ajax: {
			url:'{base_url()}admin/autocomplete/terms_conditions_ajax',

			type: 'post',
			data: { 
				tc_type: 'normal',
				type: 'ajax'
			},
			dataType: 'json',
			delay:250,
			processResults: function(data) {
				return {
					results: data
				};
			}
		},
	});
	function remove_sales_item(id, sales_id)
	{
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
				document.location.href = '{base_url()}' +"admin/sales/remove-sales-item/"+id+'/'+sales_id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});

	}	
	$(document).ready(function() { 

		var mySpec;	
		var editor = ClassicEditor	
		.create( document.querySelector( '#note' ), {	
		} )	
		.then( editor => {	
			window.editor = editor;	
			mySpec = editor;	
		} )	
		.catch( err => {	
			console.error( err.stack );	
		} );

		md.initFormExtendedDatetimepickers(); 

	// $(window).keydown(function(event){
	// 	if(event.keyCode == 13) {
	// 		event.preventDefault();
	// 		getItemDetails(); 
	// 		return false;
	// 	}
	// });

		var table = $('#example').DataTable({ searching: false, paging: false, info: false,responsive: true});
		var counter = 1;
		var qty_sum = 0;
		var total = 0;


		function getItemDetails () {
			var form_data = table
			.rows()
			.data();

			$.ajax({
				type:'POST',
				url:"{base_url('admin/sales/get-items')}",
				data: { 
					id: $('#item_id option:selected').val(), 
					item_name: $('.item_ajax').find(":selected").text(),
					quantity: $('#quantity').val(),
					rate: $('#rate').val(),
					form_data: JSON.stringify(form_data.toArray()),
				} ,
				dataType:'json',
				success: function(data) {

					if(data.success){
						addRow(data.item_info); 
					}else{
						alert(data.msg)
					}
				}
			})  
		}
		$(".add").on('click', function(event){	
			getItemDetails();	
		});

		function addRow (data) {
			var qty_sum=$('#qty-sum').html();
			var total_inclusive=$('#total_inclusive').html();
			var total_price=$('#total').text();
			var note= mySpec.getData();

			var validFrom = true;
			var  formInputs = ["item_id", "quantity"];
			$(".product-items :input").each(function(){
				var input = $(this); 
				if(formInputs.includes($(this).attr('id'))){ 
					if(input.val() == ''){
						input.focus();
						validFrom = false;
						return false;
					}
				}
			});

			if(validFrom == true){
				var dept=$('#item_id option:selected').val();
				if (dept=='') {
					validFrom = false;
					return false;
				}

				table.row.add( [
					counter,
					data.code,
					data.name +"<br><strong> Spec: </strong>" +note,
					$('#rate').val(),
					data.value+'%',
					$('#quantity').val(),
					data.total_price,
					data.inclusive_vat,
					'<a href="{assets_url()}images/items/'+data.item_images[0]['image']+'" target="_blank"><img src="{assets_url()}images/items/'+data.item_images[0]['image']+'" width="100"; height="100" class="img-thumbnail" target="_blank"></a>',


					'<button type="button" class="btn btn-danger btn-sm remove" data-dept="'+dept+'" data-price="'+data.total_price+'" data-inclusive="'+data.inclusive_vat+'" data-quantity="'+ $('#quantity').val()+'"><i class="material-icons">delete</i></button>'
					] ).draw( false ).node();
				counter++;


				qty_sum=parseFloat(qty_sum)+parseFloat($('#quantity').val());
				total=parseFloat(total)+parseFloat(data.total_price);

				total_inclusive=parseFloat(total_inclusive)+parseFloat(data.inclusive_vat);

				$('.product-items input').val(''); 
				$('.product-items select').val(''); 
				$('.product-items textarea').val(''); 
				$('.product-items #item_id').focus();
				mySpec.setData( '' );

				$('#qty-sum').html(qty_sum);
				$('#total').html(total);
				$('#total_inclusive').html(total_inclusive);
				$("#item_id option[value*='"+dept+"']").prop('disabled',true);
				$('.item_ajax').val(null).trigger('change').select2('open');

			}

		} ;
		$('#example').on('click', '.remove', function() {
			var total_qty=$('#qty-sum').html();
			var total_price=$('#total').text();
			var total_inclusive=$('#total_inclusive').text();
			var quantity=$(this).attr('data-quantity');
			var price=$(this).attr('data-price');
			var inclusive=$(this).attr('data-inclusive');
			var row = $(this).parents('tr');

			if ($(row).hasClass('child')) {
				table.row($(row).prev('tr')).remove().draw();
			} else {
				table
				.row($(this).parents('tr'))
				.remove()
				.draw();
			} 
			var qty=parseFloat(total_qty)-parseFloat(quantity);
			var sum=(parseFloat(total_price)-parseFloat(price)).toFixed(2);
			var inclusive_sum=(parseFloat(total_inclusive)-parseFloat(inclusive)).toFixed(2);
			$('#qty-sum').html(qty);
			$('#total').html(sum); 
			$('#total_inclusive').html(inclusive_sum);


		});


		$('.items_update').on('click', function(){


			var enc_id= $("#enc_id").val();

			var by_percentage= $("#by_percentage").val();
			var by_amount= $("#by_amount").val(); 

			var btnVal = $(this).val();

			var form_data = table
			.rows()
			.data();

			$.ajax({
				type:'POST',
				url:"{base_url('admin/sales/add-sales-items')}",
				data: { id: '{$enc_id}',  data: JSON.stringify(form_data.toArray()), by_percentage: by_percentage, by_amount: by_amount, } ,
				dataType:'json',
			})
			.done(function( response ) { 

				url  = "{base_url('admin/sales/sales-quotation')}";
				if(response.success){
					(btnVal == 'save') ? (document.location.href = url) : document.location.href = "{base_url('admin/sales/edit-sales/')}"+enc_id;

				}else{
					alert(response.msg);

				}
			}); 

		});

		$("#qty").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});

		$('.item_ajax').on("select2:close", function(e) {
			var item_id = $("select.item_ajax option:checked" ).val();

			$.ajax({
				url:"{base_url('admin/sales/get-item-details')}",

				cache: true,
				data: { 
					id: $('#item_id option:selected').val(), 
					type: 'ajax'
				},
				type: 'post',
				dataType: 'json',
				success: function(html){
					if( html.success){
						mySpec.setData(html.item_info.note ); 
						$('#rate').val(html.item_info.price);
					}
				}
			}); 
		});
	// $('.item_ajax').on("select2:close", function (e) { 
	// 	var getItemSpecURL = '{base_url('admin/item/get_item_note')}';
	// 	$.post( getItemSpecURL, { 'item_id': $('#item_id option:selected').val() },function( data ) {
	// 		mySpec.setData(data ); 
	// 	});

	// });
	});

function getDiscountPercentage() {
	var prev_total_inclusive=$('#total_vat_inclusive').val();
	var total_inclusive=$('#total_inclusive').html();
	var total_price=(parseFloat(prev_total_inclusive)+parseFloat(total_inclusive)).toFixed(2);
	var by_amount=$('#by_amount').val();
	if(total_price > 0){
		var by_percentage = (parseFloat(by_amount)/parseFloat(total_price) *100).toFixed(2);

	}else{
		var by_percentage = 0
	}

	$('#by_percentage').val(by_percentage);
	$('#by_amount_label').html('');
	$('#by_percentage_label').text('');
}

function getDiscountAmount() {
	var prev_total_inclusive=$('#total_vat_inclusive').val();
	var total_inclusive=$('#total_inclusive').html();
	var total_price=(parseFloat(prev_total_inclusive)+parseFloat(total_inclusive)).toFixed(2);

	var by_percentage=$('#by_percentage').val();
	var amount=parseFloat(total_price) * parseFloat(by_percentage)/100;

	$('#by_amount').val(amount);
	$('#by_amount_label').html('');
	$('#by_percentage_label').text('');

}


$('.customer_ajax').select2({

	placeholder: 'Select a Customer',
	ajax: {
		url:'{base_url()}{log_user_type()}/autocomplete/customer_ajax',

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


$('.customer_ajax').on("select2:close", function(e) {
	var customer_id = $("select.customer_ajax option:checked" ).val();

	$.ajax({
		url:'{base_url()}admin/autocomplete/salesman_by_customer_ajax',
		cache: true,
		data: { 
			customer_id: customer_id,
			type: 'ajax'
		},
		type: 'post',
		dataType: 'json',
		success: function(html){
			$('#salesperson').find('option').remove().end();
			if( html.user_id){
				daySelect = document.getElementById('salesperson');
				daySelect.options[daySelect.options.length] = new Option(html.user_name, html.user_id); 
			}
		}
	}); 
});

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

$('.item_ajax').on('select2:select', function (e) {
	$('#quantity').focus();
});


$('.edit-progress').click(function(){
	var ID = $(this).data('id');
	var ItemId=$(this).data('item_id');
	$.ajax({
		url: '{base_url(log_user_type())}/sales/edit-items',
		type: 'post',
		data: { id:ID,item_id:ItemId},
		success: function(response){  
			$('.empEditBody').html(response.html);

			$('#empModal').modal('show'); 
		}
	});


});


</script>


{/block}
