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
				<h4 class="card-title">Edit</h4>
			</div>

			<div class="card-body">
				<div id="accordion" role="tablist">
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingOne">
							<h5 class="mb-0">
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
									EDIT DETAILS
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
							{form_open_multipart('','name="release_payout"  id="payout_release" class="form-login"')}
							<div class="card-body">
								<div class="row">
									
									<div class="col-lg-12">
										<div class="input-group form-control-md">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="material-icons">grading</i>
												</span>
											</div>
											<div class="col-sm-11">
												<div class="form-group">
													<label class="bmd-label-floating">
														Minutes no
													</label>
													<input type="text" name="code" class="form-control" value="{$details['code']}" readonly="">
													{form_error("code")}
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
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
													<label class="bmd-label-floating">Meeting Attendee </label>

													<select id="user_name" class="form-control user_ajax" name="user_name[]" multiple>
														{foreach $details['user_name_string'] item=$item key=$user_id}
														<option value="{$user_id}"selected>{$item}</option>
														{/foreach}
													</select> 
													{form_error("user_name")} 
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12">
									<div class="input-group form-control-lg">
										

										<div class="col-sm-12">
											<div class="row">

												{foreach $details['images'] as $key=>$images }
												
												<div class="col-sm-2">

													<div class="col-sm-12">
														<div class="img-thumbnail">
															<embed src="{assets_url('images/meeting')}/{$images['image']}" width="100" height="100" />
														</div>
													</div>
													<div class="col-sm-12 text-center">
														<div class="row">

															<div class="col-sm-6">
																<input type="checkbox" name="images[]" value="{$images.id}"/>

																<input type="text" name="img_id" value="{$details.id}" hidden>
															</div>		
															<div class="col-sm-6">
																<a href="{assets_url('images/meeting')}/{$images['image']}"  data-placement="top"  title="Download file" target="_blank">
																	<i class="fa fa-download fa text-danger"></i>
																</a> 
															</div>

														</div>
													</div>

												</div>
												{/foreach}

											</div>


											{if $details['images'] }
											<button class="btn btn-rose" type="submit" id="delete_images" name="submit" value="delete_images">
												Delete Images <i class="fa fa-arrow-circle-right"></i>
											</button> 
											{/if}
										</div>
										<div class="col-lg-12">
											<div class="fileinput fileinput-new text-center" data-provides="fileinput">
												<div class="cloningDiv files">
													<span class="btn btn-round btn-info btn-file dropzone">
														<span class="fileinput-new">ATTACHMENT</span>
														<span class="fileinput-exists">{lang('change')}</span>
														<input type="file" id="upload_file" name="upload_file[]"   multiple=""  />

														{form_error('upload_file')}
													</span>

													<div class="form-group fileinput fileinput-new">
														<div class="image_preview" >
															<div class='fileinput-new thumbnail m-1'>
																<img class='p-2' src='{assets_url('images/meeting/no-image.png')}' width="100" height="100">
															</div>
														</div>
													</div> 
												</div>
											</div>

										</div>

									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12">
									<div class="input-group form-control-lg">
										<div class="input-group-prepend">
											<span class="input-group-text"><i class="material-icons">person</i>
											</span>
										</div>
										<div class="col-sm-10">
											<div class="form-group">
												<label class="bmd-label-floating"> Additional Note </label>

												<textarea name="ad_note" class="form-control ad_note">{$details['ad_note']}</textarea> 
												{form_error("ad_note")}
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12">
								<input type="submit" name="update" id="update" class="btn btn-primary pull-right" value="Update">
							</div>


							<div class="mt-4">
								<div class="col-md-12">
									{if $details['items']}
									<div class="table-responsive">
										<table class="table" style="table-layout: fixed; width: 100%;">
											<thead class="bg-light">
												<tr>
													<th width="50px;">#</th>
													<th>Sample Code</th>
													<th width="250px;">Name</th>
													<th>Added Date</th>
													<th>cost</th>
													<th>Price</th>
													<th>Item Images</th>
													<th width="50px;" class="text-center">Action</th>
												</tr>
											</thead>
											<tbody>

												{foreach $details['items'] as $v} 

												<tr>
													<td >{counter}</td>
													<td>{$v.code}</td>
													<td>{$v.name} <br> <b>Spec:</b>{$v.note}</td>
													<td>{$v.date_added}</td>  
													<td>{$v.cost}</td> 
													<td>{$v.price}</td>
													<td>

														<a href="{assets_url()}images/sample/{$v.item_images}" target="_blank"> <img src="{assets_url()}images/sample/{$v.item_images}" width="100px;" height="100px;"></a><br>

													</td>  


													<td class="td-actions text-center ">
														<a rel="tooltip" title="Delete" href="javascript:remove_meeting_sample('{$v.enc_id}', '{$enc_id}')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>
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
				<div class="card-collapse">
					<div class="card-header" role="tab" id="headingTwo">
						<h5 class="mb-0">
							<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
								ADD NEW SAMPLES
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
											<select id="item_id" name="item_id" class=" form-control item_ajax"  style="width:100%"></select> 
											{form_error("item_id")} 
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
										<th>Sample Code</th>
										<th width="150px;">Name</th>
										<th>Note</th>
										<th width="50px;">Cost</th>
										<th width="50px;">Price</th>
										<th>IMAGE</th>
										<th>Action</th> 
									</tr>
								</thead> 

								<tfoot>
									<tr class="font-weight-bold font-italic">
										<td>TOTAL</td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td id="total">0</td>
										<td></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
							<button type="button" class="btn btn-rose items_update">Add Samples</button>
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
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
<script src="{assets_url('js/ckeditor.js') }"></script>  
<script type="text/javascript">
	$(document).on("change", '.files_upl',function() { 
		preview_image($(this));
	});
	$(document).on("change", '#upload_file',function() { 
		var clone = $( ".cloningDiv" ).clone();
		var parent = $(this).parent().parent();
		parent.attr('class', 'cloningDiv-'+$('.files').length); 
		parent.addClass('files');
        // parent.closest('.files').find('.image_preview').find('img').attr('src', '{assets_url('images/items/no-image.png')}');
        // console.log(parent.find('input').val(''));
        // parent.find('input').val('')
        if(clone.insertBefore( ".files:first" )){
        	$(this).attr('id', 'upload_file'+$('.files').length)
        	$(this).attr('class', 'files_upl');
        	preview_image($(this))
        }
    });
	function preview_image(e) 
	{
		console.log(event.target.files[0])

		var total_file=$('.files').length;
		for(var i=0;i<total_file;i++)
		{
			$(e).closest('.files').find('.image_preview').html("<div class='fileinput-new thumbnail m-1'><img class='p-2' src='"+URL.createObjectURL(event.target.files[i])+"' width='100' height='100'></div>");
		}
	}
	function remove_meeting_sample(id, meeting_id)
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
				document.location.href = '{base_url()}' +"salesman/showroom/remove_meeting_sample/"+id+'/'+meeting_id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});

	}	
	$(document).ready(function() { 

		var ad_note = ClassicEditor	
		.create( document.querySelector( '.ad_note' ), {	
		} ).catch( err => {	
			console.error( err.stack );	
		} );
		
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
			url:"{base_url('salesman/showroom/get-items')}",
			data: { 
				id: $('#item_id option:selected').val(), 
				item_name: $('.item_ajax').find(":selected").text(),
				note: mySpec.getData(), 
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
				data.name,
				note,
				data.cost,
				data.price,
                    // data.inclusive_vat,
                    '<a href="{assets_url()}images/sample/'+data.item_images[0]['image']+'" target="_blank"><img src="{assets_url()}images/sample/'+data.item_images[0]['image']+'" width="100"; height="100" class="img-thumbnail" target="_blank"></a>',


                    '<button type="button" class="btn btn-danger btn-sm remove" data-dept="'+dept+'" data-price="'+data.total_price+'" data-inclusive="'+data.inclusive_vat+'" data-quantity="'+ $('#quantity').val()+'"><i class="material-icons">delete</i></button>'
                    ] ).draw( false ).node();
			counter++;


			qty_sum=parseFloat(qty_sum)+parseFloat($('#quantity').val());
			total=parseFloat(total)+parseFloat(data.price);

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
			url:"{base_url('salesman/showroom/add-meeting-sample')}",
			data: { id: '{$enc_id}',  data: JSON.stringify(form_data.toArray()), by_percentage: by_percentage, by_amount: by_amount, } ,
			dataType:'json',
		})
		.done(function( response ) { 

			url  = "{base_url('salesman/showroom/meeting_mint')}";
			if(response.success){
				(btnVal == 'save') ? (document.location.href = url) : document.location.href = "{base_url('salesman/showroom/edit-meeting/')}"+'{$enc_id}';

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
			url:"{base_url('salesman/showroom/get-item-details')}",

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
	$('.item_ajax').select2({

		placeholder: 'Select a Item',
		ajax: {
			url:'{base_url()}salesman/autocomplete/sample_with_name_ajax',
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




$('.item_ajax').select2({

	placeholder: 'Select a Item',
	ajax: {
		url:'{base_url()}salesman/autocomplete/item_with_name_ajax',
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
$('.user_ajax').select2({

	placeholder: 'Select a user',
	ajax: {
		url:'{base_url()}salesman/autocomplete/user_ajax',

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
$(document).on("click", "body #example tbody #delete_images" , function() {

	var id = $(this).attr('data-id');            

	if(id) {  
		$.confirm({
			title: 'Confirm!',
			content: 'Are You Sure to delete?? ',
			buttons: {
				confirm: function () {

					document.location.href = '{base_url(log_user_type())}' +"/showroom/edit-meeting/"+id; 
				},
				cancel: function () {
					$.alert('Canceled!');
				},

			}
		});

	}
} );

</script>


{/block}
