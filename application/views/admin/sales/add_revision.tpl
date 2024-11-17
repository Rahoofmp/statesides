{extends file="layout/base.tpl"}

{block header} 

<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" />  
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
*}<link href="{assets_url()}plugins/DataTables/datatables.min.css" rel="stylesheet" />
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
									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">grading</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating">
														Quotation no
													</label>
													<input type="text" name="code" class="form-control" {if $insert_id} value="{$details['code']}
													"{else} value="{$code}" {/if} readonly="">
													{form_error("code")}
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
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
									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">money</i>
												</span>
											</div>
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
									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">money</i>
												</span>
											</div>
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



									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">person</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating"> Sales Person </label>
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

									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">attach_money</i>
												</span>
											</div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="bmd-label-floating"> VAT </label>

													<select id="vat" name="vat" class="selectpicker" data-style="select-with-transition" title="VAT" >


														{foreach $vat as $v}
														<option value="{$v.id}" {if $details['vat']==$v.id}selected{/if}>{$v.value} %</option>
														{/foreach}

													</select> 
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
									<div class="col-lg-6">
										<div class="input-group form-control-lg">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">note</i></span>
											</div>
											<input type="hidden" name="total_item_amount"
											id="total_item_amount" value="{$total_item_amount}">
											
											<div class="col-sm-10">
												<div class="form-group">

													<div class="col-sm-10">
														<div class="form-group">
															<textarea name="t_and_c" id="t_and_c" class="form-control" placeholder="Terms And Conditions">{$details['terms_conditions']}</textarea>
															{form_error('status')}
														</div>
													</div>
													{form_error('status')}
												</div>
											</div>

										</div>
										{if !$insert_id}
										<input type="submit" name="add" id="add" class="btn btn-primary" value="Add">

										{/if}
									</div>

								</div>

								<div class="mt-4">
									<div class="col-md-12">
										{if $details['items']}
										<div class="table-responsive">
											<table class="table">
												<thead class="bg-light">
													<tr>
														<th class="text-center">#</th>
														<th>Code</th>
														<th>Item Name</th>
														<th>Quantity</th>
														<th>Type</th>
														<th>Added Date</th>
														<th>Item Images</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>

													{foreach $details['items'] as $v} 

													<tr>
														<td >{counter}</td>
														<td>{$v.code}</td>
														<td>{$v.name}</td>
														<td>{$v.quantity}</td> 
														<td>{$v.type}</td> 
														<td>{$v.date_added}</td>  

														<td>{foreach $v['item_images'] as $i}<a href="{assets_url()}images/items/{$i.image}" target="_blank">
															<img src="{assets_url()}images/items/{$i.image}" width="100px;" height="100px;"></a><br>
														{/foreach}</td>   

														<td class="td-actions text-center ">

															<a rel="tooltip" title="Delete"
															{if $insert_id} href="javascript:remove_sales_item('{$v.enc_id}', '{$enc_id}')" class="btn btn-danger btn-sm" {/if}><i class="material-icons">delete</i></a>


														</td>     
													</tr>
													{/foreach}
													<tfoot>
														<tr>
															<td>Total</td>
															<td>{$total_item_amount}</td>
														</tr>
													</tfoot>

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
										<div class="col-sm-6">
											<div class="form-group">
												<select id="item_id" name="item_id" class=" form-control" required="">
													<option value='' disabled="" selected>Select Item</option>

													{foreach $items as $i}
													<option value="{$i.id}" {if in_array($i.id, $item_ids)} disabled=""{/if}>{$i.name}</option>
													{/foreach}


												</select> 
												{form_error("item_id")}
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<input type="number" class="form-control" id="quantity" name="quantity" value="{set_value('quantity')}"  required="true" autocomplete="Off" placeholder="Quantity">
												{form_error("quantity")}
											</div>

										</div>


									</div>
								</div>
								{form_close()}


								<table id="example" class="display" style="width:100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>Item Code</th>
											<th>Item</th>
											<th>Quantity</th>
											<th>Unit Price</th>
											<th>Rate</th>
											<th>VAT</th>
											<th>IMAGE</th>

											<th>Action</th> 
										</tr>
									</thead> 


									<tfoot>
										<tr class="font-weight-bold font-italic">
											<td>TOTAL</td>
											<td></td>
											<td></td>
											<td id="qty-sum">0</td>
											<td></td>
											<td id="total">0</td>

										</tr>
										<tr>
											<td>Prev Added TOTAL</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
											<td>{$total_item_amount}</td>

										</tr>
									</tfoot>
								</table>

								<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo" {if !$insert_id} disabled=""{/if}>Add Discount</button>



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
														<input type="text" class="form-control" id="by_amount" name="by_amount" onblur="getDiscountPercentage();" value="{$details['discount_by_amount']}">
													</div>
													<div class="form-group">
														<label for="message-text" class="col-form-label" id="by_percentage_label">Discount By Percentage</label>
														<input type="text" class="form-control" id="by_percentage" name="by_percentage" onblur="getDiscountAmount();" value="{$details['discount_by_percentage']}">
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



			{/block}

			{block footer} 
			<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

			<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
			<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>

			<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>
			<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
			<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
			<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
			<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
			<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script>
			<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
			<script src="{assets_url('js/ui-notifications.js')}"></script>
			<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>

			<script>
				$(document).ready(function() { 
					md.initFormExtendedDatetimepickers();
				});
			</script>

			<script type="text/javascript">
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
							document.location.href = '{base_url()}' +"admin/sales/remove-sales-item/"+id+'/'+sales_id+'/add-revision'; 
						} else {
							swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
						}
					});

				}	
				$(document).ready(function() { 
					md.initFormExtendedDatetimepickers();
				});
				$(document).ready(function() {

					$(window).keydown(function(event){
						if(event.keyCode == 13) {
							event.preventDefault();
							getItemDetails(); 
							return false;
						}
					});

					var table = $('#example').DataTable({ searching: false, paging: false, info: false,responsive: true});
					var counter = 1;
					var qty_sum = 0;
					var total = 0;


					function getItemDetails () {

						$.ajax({
							type:'POST',
							url:"{base_url('admin/sales/get-items')}",
							data: { id: $('#item_id option:selected').val(), quantity: $('#quantity').val()} ,
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


					function addRow (data) {


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
							var dept=$('#item_id option:selected').val();
							if (dept=='') {
								validFrom = false;
								return false;

							}
							table.row.add( [
								counter,
								data.code,
								$('#item_id option:selected').text(),
								$('#quantity').val(),
								data.price,
								data.total_price,
								data.vat_name,
								'<a href="{assets_url()}images/items/'+data.item_images[0]['image']+'" target="_blank"><img src="{assets_url()}images/items/'+data.item_images[0]['image']+'" width="100"; height="100" class="img-thumbnail" target="_blank"></a>',



								'<button type="button" class="btn btn-danger btn-sm remove" data-dept="'+dept+'" data-price="'+data.total_price+'" data-quantity="'+ $('#quantity').val()+'"><i class="material-icons">delete</i></button>'
								] ).draw( false ).node();
							counter++;
							qty_sum=parseFloat(qty_sum)+parseFloat($('#quantity').val());
							total=parseFloat(total)+parseFloat(data.total_price);
							$('.product-items input').val(''); 
							$('.product-items select').val(''); 
							$('.product-items textarea').val(''); 
							$('.product-items #item_id').focus();
							$('#qty-sum').html(qty_sum);
							$('#total').html(total);
							$("#item_id option[value*='"+dept+"']").prop('disabled',true);
						}

					} ;
					$('#example').on('click', '.remove', function() {
						var total_qty=$('#qty-sum').html();
						var total_price=$('#total').text();
						var quantity=$(this).attr('data-quantity');
						var price=$(this).attr('data-price');
						var dept_id=$(this).attr('data-dept');
						var row = $(this).parents('tr');

						if ($(row).hasClass('child')) {
							table.row($(row).prev('tr')).remove().draw();
						} else {
							table
							.row($(this).parents('tr'))
							.remove()
							.draw();
						}
						$("#item_id option[value*='"+dept_id+"']").prop('disabled',false);
						var qty=parseFloat(total_qty)-parseFloat(quantity);
						var sum=parseFloat(total_price)-parseFloat(price);
						$('#qty-sum').html(qty);
						$('#total').html(sum);

					});


					$('.items_update').on('click', function(){


						var enc_id= $("#enc_id").val();

						var by_percentage= $("#by_percentage").val();
						var by_amount= $("#by_amount").val();
						var terms_conditions= $("#terms_conditions").html();


						var btnVal = $(this).val();

						var form_data = table
						.rows()
						.data();

						$.ajax({
							type:'POST',
							url:"{base_url('admin/sales/add-sales-items')}",
							data: { id: '{$enc_id}',  data: JSON.stringify(form_data.toArray()), by_percentage: by_percentage, by_amount: by_amount,terms_conditions: terms_conditions, } ,
							dataType:'json',
						})
						.done(function( response ) { 

							url  = "{base_url('admin/sales/sales-quotation')}";
							if(response.success){
								(btnVal == 'save') ? (document.location.href = url) : document.location.href = "{base_url('admin/sales/add-revision/')}"+enc_id;

							}else{
								alert("failed");
								(btnVal == 'save') ? (document.location.href = url) : document.location.href = "{base_url('admin/sales/add-revision/')}"+enc_id;
							}
						}); 

					});

					$("#qty").keypress(function (e) {
						if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
							return false;
						}
					});

				} );

function getDiscountPercentage() {
	var total_price=$('#total').html();
	var total_item_amount=$('#total_item_amount').val();


	var sum=parseFloat(total_price)+parseFloat(total_item_amount);

	var by_amount=$('#by_amount').val();


	var by_percentage=by_amount/sum *100;

	$('#by_percentage').val(by_percentage);
	$('#by_amount_label').html('');
	$('#by_percentage_label').text('');

}
function getDiscountAmount() {
	var total_price=$('#total').html();
	var total_item_amount=$('#total_item_amount').val();

	var sum=parseFloat(total_price)+parseFloat(total_item_amount);


	var by_percentage=$('#by_percentage').val();


	var amount=sum * by_percentage/100;

	$('#by_amount').val(amount);

	$('#by_amount_label').html('');
	$('#by_percentage_label').text('');

}


$('.customer_ajax').select2({

	placeholder: 'Select a Customer',
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

</script>


{/block}
