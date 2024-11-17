{extends file="layout/base.tpl"}

{block header} 

<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" />  
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
				<h4 class="card-title">Package Details</h4>
			</div>

			<div class="card-body">
				<div id="accordion" role="tablist">
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingOne">
							<h5 class="mb-0">
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
									PACKAGE INFO 
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
							{form_open_multipart('','name="release_payout"  id="payout_release" class="form-login"')}
							<div class="card-body">

								<div class="row">
									<div class="col-sm-4">
										<div class="col-sm-12">

											<label class="col-form-label">Project Name</label>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<select id="project_id" name="project_id" class="project_ajax form-control" >
													{if $details['project_id']}
													<option value="{$details['project_id']}">{$details['project_name']}</option>
													{/if} 
												</select> 
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="col-sm-12">

											<label class="col-form-label">Package Name</label>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<input type="text" class="form-control" id="package" name="package" required placeholder="Package" value="{$details['name']}">
											</div>
										</div>

									</div>
									<div class="col-sm-4">
										<div class="col-sm-12">

											<label class="col-form-label">Location</label>
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<input type="text" class="form-control" id="package_location" name="package_location" required placeholder="Location" value="{$details['package_location']}">
											</div>
										</div>

									</div>

									{* {print_r($details->area_master)} *}

									<div class="col-sm-4">
										<div class="col-sm-12">

											<label class="col-form-label">Area Master</label>
											
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<select id="projectinput5" name="area_master" class="type_ajax form-control" >
													{if $details['type_id']}

													<option value="{$details['type_id']}">{$details['area_master']}</option>
													{/if}

												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-4">
										<div class="col-sm-12">

											<label class="col-form-label">Item</label>
											
										</div>
										<div class="col-sm-12">
											<div class="form-group">
												<select id="item_id" name="item" class="item_ajax form-control" >
													{if $details['item_id']}

													<option value="{$details['item_id']}">{$details['item']}</option>
													{/if}

												</select>
											</div>
										</div>
									</div>
									
									<div class="col-md-4">

										<div class="fileinput fileinput-new text-center" data-provides="fileinput">
											<div class="fileinput-new thumbnail img-circle">
												<img src="{assets_url('images/package_pic/')}{$details['image']}"  alt="package-image" class="p-2">
											</div>
											<div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
											<div>
												<span class="btn btn-round btn-info btn-file">
													<span class="fileinput-new">{lang('change_photo')}</span>
													<span class="fileinput-exists">{lang('change')}</span>
													<input type="file" name="userfile" />
												</span>
												<br />
												<a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
											</div>
										</div>
									</div>

									<div class="col-sm-2">
										<div class="form-group col-sm-12">
											<input type="submit"  name="info_update" class="btn btn-primary" value="update" >
										</div>

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
														<th>Added Date</th>
														<th class="text-center">Action</th>
													</tr>
												</thead>
												<tbody>

													{foreach $details['items'] as $v} 

													<tr>
														<td >{counter}</td>
														<td>{$v.serial_no}</td>
														<td>{$v.name}</td>
														<td>{$v.qty}</td> 
														<td>{$v.date_addedd}</td>      
														<td class="td-actions text-center ">

															<a rel="tooltip" title="Delete" href="javascript:remove_package_item('{$v.enc_id}', '{$enc_id}')" class="btn btn-danger btn-sm"><i class="material-icons">delete</i></a>


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
									ADD NEW PACKAGE ITEMS
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
												<input type="text" class="form-control" id="serial_no" placeholder="Code" required> 
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<input type="text" class="form-control" id="name" placeholder="Item Name" required  onClick="autoComplete(this, 'admin', 'autocomplete/package-items-filter')" autocomplete="Off" >  
											</div>
										</div>
										<div class="col-sm-4">
											<div class="form-group">
												<input type="text" class="form-control" id="qty" placeholder="Quantity" required> 
											</div>
										</div>
									</div>
								</div>
								{form_close()}


								<table id="example" class="display" style="width:100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>Code</th>
											<th>Name</th>
											<th>Qty</th> 
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

<script type="text/javascript">

	$(document).ready(function() {

		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				addRow(); 
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
				table.row.add( [
					counter,
					$('#serial_no').val(),
					$('#name').val(),
					$('#qty').val(),
					'<button type="button" class="btn btn-danger btn-sm remove"><i class="material-icons">delete</i></button>'
					] ).draw( false ).node();
				counter++;
				$('.product-items input').val(''); 
				$('.product-items #serial_no').focus();
			}
		} ;

		$('.items_update').on('click', function(){
			var btnVal = $(this).val();

			var form_data = table
			.rows()
			.data();

			$.ajax({
				type:'POST',
				url:"{base_url('admin/packages/save-items')}",
				data: { id: '{$enc_id}', data: JSON.stringify(form_data.toArray()) } ,
				dataType:'json',
			})
			.done(function( data ) { 

				url  = '{base_url()}'+'admin/packages/edit-package/{$enc_id}';
				if(data.success){

					(btnVal == 'save') ? (document.location.href = url) : document.location.href = '{base_url('admin/packages/package-list')}';

				}else{
					(btnVal == 'save') ? (document.location.href = url) : document.location.href = '{base_url('admin/packages/package-list')}';
				}
			}); 

		});

		$("#qty").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});

	} );
	$('.project_ajax').select2({

		placeholder: 'Select a project',
		ajax: {
			url:'{base_url(log_user_type())}/autocomplete/project_ajax',

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

	$('.type_ajax').select2({

		placeholder: 'Select a Type/Area master',
		ajax: {
			url:'{base_url()}admin/autocomplete/type_ajax',

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
	

	function remove_package_item(id, package_id)
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
				document.location.href = '{base_url()}' +"admin/packages/remove-package-item/"+id+'/'+package_id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});

	}	
</script>


{/block}
