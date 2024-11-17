{extends file="layout/base.tpl"}

{block header} 
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/datatables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/jquery-confirm.min.css') }">

<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/extensions/fixedColumns.dataTables.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('css/tables/datatable/select.dataTables.min.css')}">
{/block}


{block body}
<style type="text/css">
	.my-group .form-control{
		width:50%;
	} 
	table.dataTable thead > tr > th.sorting{
		text-align: left !important;
	}
</style>

<div class="row">
	<div class="col-md-12">
		<div class="card"> 
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Details</h4>
			</div> 
			<div class="card-body">
				<div class="table-responsive">
					<table class="table" id="example">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Code</th>
								<th>Main Category</th>
								<th>Sub-Category</th>
								<th>Sort Order</th>
								<th>Created On</th>
								<th class="text-center">Action</th>

							</tr>
						</thead>
						<tbody>
							{foreach $categories as $c}
							<tr>
								<td>{counter}</td>
								<td>{$c.code}</td>
								
								<td>{if $c.main_category==0}{$c.category_name}{else}{$c.main_category_name}
								{/if}</td>


								<td>{$c.sub_category_name}</td>
								<td>{if $c.main_category==0}{$c.sort_order}{/if}</td>
								<td>{$c.date}</td>
								<td><a data-id="{$c.enc_id}" class="edit_category" id="edit" title="Edit"><i class="material-icons text-rose" aria-hidden="true">edit</i></a>
									<a data-id="{$c.enc_id}" class="delete_category" id="delete" title="Delete"><i class="material-icons text-danger" aria-hidden="true">delete</i></a></td>
								</tr>
								{/foreach}
							</tbody>
						</table>
					</div>
				</div> 
			</div>
			<div class="d-flex justify-content-center">  
				<ul class="pagination start-links"></ul> 
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
	<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
	<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
	<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
	<script src="{assets_url('js/scripts/tables/datatables-extensions/datatable-autofill.min.js')}"></script>
	<script src="{assets_url('js/jquery-confirm.min.js') }"></script>

	<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() { 
			md.initFormExtendedDatetimepickers();
		});
	</script>
	<script type="text/javascript">
		
		$(document).on("click", "body #example tbody .edit_category" , function() {

			var id = $(this).attr('data-id');            

			if(id) {  
				$.confirm({
					title: 'Confirm!',
					content: 'Are You Sure to edit?? ',
					buttons: {
						confirm: function () {

							document.location.href = '{base_url()}' + "admin/delivery/create-category/"+id; 

						},
						cancel: function () {
							$.alert('Canceled!');
						},

					}
				});

			}



		} );



		$(document).on("click", "body #example tbody .delete_category" , function() {

			var id = $(this).attr('data-id');            

			if(id) {  
				$.confirm({
					title: 'Confirm!',
					content: 'Are You Sure to delete?? ',
					buttons: {
						confirm: function () {

							document.location.href = '{base_url(log_user_type())}' +"/delivery/delete-category/"+id; 
						},
						cancel: function () {
							$.alert('Canceled!');
						},

					}
				});

            	//end
            }



        } );





    </script>
    {/block}