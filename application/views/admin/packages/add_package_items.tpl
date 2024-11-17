{extends file="layout/base.tpl"}

{block header} 
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
<link href="{assets_url()}plugins/DataTables/datatables.min.css" rel="stylesheet" />


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
							<div class="mb-0">
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
									PACKAGE INFO
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</div>
						</div>
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
							<div class="card-body">
								<div class="tim-typo">
									<h6>
										<span class="tim-note">Project Name</span>
										{$project_details['project_name']}
									</h6>
								</div>
								<div class="tim-typo">
									<h5>
										<span class="tim-note">Package Name</span>
										{$project_details['name']}
									</h5>
								</div> 
							</div>
						</div>
					</div>
					<div class="card-collapse">
						<div id="headingTwo" class="card-header" role="tab">
							<div class="mb-0">
								<a data-toggle="collapse" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo" class="collapsed">
									ADD PACKAGE ITEMS
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

<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>

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

				saveUrl  = '{base_url()}'+'admin/packages/edit-package/{$enc_id}';
				saveCreateUrl  = '{base_url('admin/packages/add-package')}';

				if(data.success){

					(btnVal == 'save') ? (document.location.href = saveUrl) : document.location.href = saveCreateUrl;

				}else{
					(btnVal == 'save') ? (document.location.href = saveUrl) : document.location.href = saveCreateUrl;
				}
 			}); 

		});

		$("#qty").keypress(function (e) {
			if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
				return false;
			}
		});

	} );
 
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
