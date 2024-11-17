{extends file="layout/base.tpl"}

{block header} 
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
<link href="{assets_url()}plugins/DataTables/datatables.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
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
				<h4 class="card-title">Delivery Details</h4>
			</div>

			<div class="card-body">
				{form_open('','')}
				<div class="row">
					<div class="col-sm-6">
						<div class="card-body">
							<div class="tim-typo">
								<h6>
									<span class="tim-note">Delivery Code</span>
									{$details['code']}
								</h6>
							</div>
							<div class="tim-typo">
								<h6>
									<span class="tim-note">Project Name</span>
									<select id="name" name="project_id" class="project_ajax form-control" >
										{if $details['project_id']}
										<option value="{$details['project_id']}">{$details['project_name']}</option>
										{/if} 
									</select>
								</h6>
							</div>
							<div class="tim-typo">
								<h6>
									<span class="tim-note">Driver </span>
									<select name="driver_id" id="driver_id" class="driver_ajax form-control" >
										{if $details['driver_id']}
										<option value="{$details['driver_id']}">{$details['driver_name']}</option>
										{/if} 
									</select>
								</h6>
							</div>
							<div class="tim-typo">
								<h6>
									<span class="tim-note">Vehicle No.</span>
									<input type="text"  class="form-control" id="vehicle" name="vehicle" value="{$details['vehicle']}">
								</h6>
							</div> 
						</div>
					</div>
					<div class="col-sm-6">
						<div class="card-body">  
							<div class="tim-typo">
								<h6>
									<span class="tim-note">Customer name</span>
									{$details['customer_name']}
								</h6>
							</div>
							<div class="tim-typo">
								<h6>
									<span class="tim-note">Mobile</span>
									{$details['mobile']}
								</h6>
							</div>
							<div class="tim-typo">
								<h6>
									<span class="tim-note">Email </span>
									{$details['email']}
								</h6>
							</div> 
							<div class="tim-typo">
								<h6>
									<span class="tim-note">Created on</span>
									{$details['date_created']}
								</h6>
							</div> 
							<div class="tim-typo">
								<h6>
									<span class="tim-note">status</span>
									{ucfirst($details['status'])|replace:'_':' '}
								</h6>
							</div>
						</div>
					</div>
				</div>
				{form_close()}
				<div id="accordion" role="tablist">
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingOne">
							<div class="mb-0">
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
									DELIVERY PACKAGES
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</div>
						</div>
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">

							<div class="m-4">

								{if $details['packages']}
								<div id="packageAccordion" role="tablist" >

									{foreach $details['packages'] as $packages} 

									<div class="card-collapse">
										<div class="card-header" role="tab" id="packageHeading{$packages.id}"> 

											<h5 class="mb-0">


												<a data-toggle="collapse" href="#packageCollapse{$packages.id}" aria-expanded="false" aria-controls="packageCollapse{$packages.id}" class="collapsed">
													{$packages.package_name}
													<i class="material-icons">keyboard_arrow_down</i>
												</a>
											</h5> 
										</div>
										<div id="packageCollapse{$packages.id}" class="collapse" role="tabpanel" aria-labelledby="packageHeading{$packages.id}" data-parent="#packageAccordion" style="">
											<div class="card-body">
												<div class="col-sm-12">
													
													<a href="javascript:remove_package('{$packages.enc_id}', '{$enc_id}')"  class="btn btn-danger btn-link" > <i class="fa fa-trash"></i> Remove This Package</a>

													{if $packages['items']}
													<div class="table-responsive">
														<table class="table">
															<thead class="bg-light">
																<tr>
																	<th class="text-center">#</th>
																	<th>Sl.No</th>
																	<th>Item Name</th>
																	<th>Quantity</th>
																	<th>Added Date</th>
																</tr>
															</thead>
															<tbody>


																{foreach $packages['items'] as $v} 

																<tr>
																	<td >{counter}</td>
																	<td>{$v.serial_no}</td>
																	<td>{$v.name}</td>
																	<td>{$v.qty}</td> 
																	<td>{$v.date_addedd}</td>      

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
									</div> 
									{/foreach} 
								</div>
								{else}
								<div class="card-body">
									<h4 class="text-center"> 
										<i class="fa fa-exclamation"> No Pacakges added</i>
									</h4>
								</div>
								{/if} 

							</div>

						</div>
					</div>
					<div class="card-collapse">
						<div id="headingTwo" class="card-header" role="tab">
							<div class="mb-0">
								<a data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="collapsed">
									ADD DELIVERY PACKAGES
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</div>
						</div>
						<div id="collapseTwo" class="collapse show" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
							<div class="card-body">  
								{form_open('','name="item_add"  id="add-items-form" class="form-login"')}

								<div class="col-sm-12 product-items">
									<div class="row"> 
										<div class="col-sm-4">
											<div class="form-group">
												{* <input type="text" class="form-control" id="item_name" placeholder="Item Name / Code" required  onClick="autoComplete(this, 'admin', 'autocomplete/package-items-filter')" autocomplete="Off" >   *}
												<input type="text"  class="form-control" id="package_name" name="package_name"  placeholder="Package Name / Code"   autocomplete="Off">

											</div>
										</div>

										<div class="col-sm-5"> 
											<div class="togglebutton mt-3">
												<label>
													<input type="checkbox" name="by_package_name" id="by_package_name" value="on" {set_checkbox('by_package_name', 'on')}>
													<span class="toggle"></span>
													Search based on Package Name 
												</label>
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
								<button type="button"  name="items_update" class="btn btn-primary items_update" value="save_exit" > Save & Create New</button>

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
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>

<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

<script type="text/javascript">

	$(document).ready(function() {

		$(window).keydown(function(event){
			if(event.keyCode == 13) {
				event.preventDefault();
				getItemDetails()
				return false;
			}
		});

		var table = $('#example').DataTable({ searching: false, paging: false, info: false,responsive: true});
		var counter = 1;

		$('#package_name').click(function() {
			if($("#by_package_name").prop('checked')){
				autoCompletePackage(this, 'admin', 'autocomplete/project_package_filter', '{$details.project_id}')
				
			}
		});

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


		function getItemDetails () {
			$.ajax({
				type:'POST',
				url:"{base_url('admin/delivery/getDeliveryPackages')}",
				data: { id: '{$enc_id}',  project_id: '{$details.project_id}', package:  $('#package_name').val(), by_package_name:  $("#by_package_name").prop('checked'), data: JSON.stringify( table.rows().data().toArray()) } ,
				dataType:'json',
				success: function(data) {

					if(data.success){
						addRow(data.package_info); 
					}else{
						alert(data.msg)
					}
				}
			})  
		}


		function addRow (data) {
			var validFrom = true;
			$(".product-items #package_name").each(function(){
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
					data.code,
					data.name,
					1,
					'<button type="button" class="btn btn-danger btn-sm remove"><i class="material-icons">delete</i></button>'
					] ).draw( false ).node();
				counter++;
				$('.product-items #package_name').val('').focus();  
			}
		} ;

		$('.items_update').on('click', function(){
			var btnVal = $(this).val();

			var form_data = table
			.rows()
			.data();

			$.ajax({
				type:'POST',
				url:"{base_url('admin/delivery/save-package-items')}",
				data: { id: '{$enc_id}', project_id: $('#name').val(), data: JSON.stringify(form_data.toArray()),vehicle: $('#vehicle').val(),driver: $('#driver_id').val(),delivery_id: '{$details.id}' } ,
				dataType:'json',
			})
			.done(function( data ) { 

				saveUrl  = '{base_url()}'+'admin/delivery/add-delivery-items/{$enc_id}';
				saveCreateUrl  = '{base_url('admin/delivery/create')}';

				if(data.success){

					(btnVal == 'save') ? (document.location.href = saveUrl) : document.location.href = saveCreateUrl;

				}else{
					(btnVal == 'save') ? (document.location.href = saveUrl) : document.location.href = saveCreateUrl;
				}
			}); 

		});


	} );

	function remove_package(id, package_id)
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
				document.location.href = '{base_url()}' +"admin/delivery/remove-package/"+id+'/'+package_id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});

	}


	
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


    $('.driver_ajax').select2({

        placeholder: 'Select a Driver',
        ajax: {
            url:'{base_url()}admin/autocomplete/driver_ajax',

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
	$('#status').select2();
</script>	



{/block}
