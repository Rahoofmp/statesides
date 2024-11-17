{extends file="layout/base.tpl"}
{block header}
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />

{/block}
{block name="body"}  
<div class="row "> 
	<div class="col-sm-12 hidden-print"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					{form_open('','')}
					<div class="form-body">
						<div class="row"> 
							<div class="col-md-8">
								<div class="form-group">
									<select id="item_id" name="item_id" class="item_ajax form-control" >
										{if $post_arr['item_id']}
										<option value="{$post_arr['item_id']}">{$item_details['code']}</option>
										{/if} 
									</select> 
								</div>
							</div>

							<div class="col-md-4"> 
								<button type="submit" class="btn btn-primary col-md-12" name="search" value="search">
									<i class="fa fa-filter"></i> {lang('button_filter')}
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
{if $post_arr['item_id']}

<div class="row">
	<div class="col-sm-6">
		<div class="card card-stats">
			<div class="card-header card-header-warning card-header-icon">
				<div class="card-icon">
					<i class="material-icons">people</i>
				</div>
				<p class="card-category">Total Orders</p> <h4 class="card-title">{$total_order}</h4>
				<!-- <p class="card-category">Total Est. Time</p>
				<h4 class="card-title">{$total_est_time} Hrs</h4><p class="card-category">Total Spent Time</p>
				<h4 class="card-title">{$total_spent_time} Hrs</h4> -->

			</div> 
			
		</div>
	</div>
	<div class="col-sm-6">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Quantity</p>
				<h4 class="card-title"> 

			<!-- 		{if $total_time_difference>0}
					<i class="material-icons">add</i>
					{elseif $total_time_difference<0} 
					<i class="material-icons">remove</i>
					{/if} -->
					<span class="font-weight-bold" style="font-size: 34px;"> {abs($item_details['total_quantity'])}</span>
				</h4>
			</div>
			{if log_user_type() =="admin"}

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}admin/item/list-items" > View Details</a>
				</div>
			</div>
			{/if}
		</div>
	</div>

</div>




<div class="row">
	<div class="col-md-6">

		<div class="row">
			<div class="col-md-12">
				<div class="card"> 
					<div class="card-body">
						<div id="accordion" role="tablist">
							<div class="card-collapse">
								<div class="card-header" role="tab" id="headingOne">
									<h5 class="mb-0">
										<a data-toggle="collapse" href="#collapseProjectDetails" aria-expanded="false" aria-controls="collapseProjectDetails" class="collapsed">
											Item Details
											<i class="material-icons">keyboard_arrow_down</i>
										</a>
									</h5>
								</div>
								<div id="collapseProjectDetails" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
									<div class="card-body">
										<div class="table-responsive"> 
											<table class="table table-hover">

												<thead>
												</thead>
												<tbody> 
													<tr>
														<td >Item Name: </td>
														<td>{$item_details['name']}</td> 
													</tr>
													<tr>
														<td >Status: </td>
														<td>{if $item_details['status']==1} Active {else} Inactive{/if}</td> 
													</tr>
													<tr>
														<td > Cost: </td>
														<td>{cur_format($item_details['cost'])}</td> 
													</tr>
													<tr>
														<td > Selling Price: </td>
														<td>{cur_format($item_details['price'])}</td> 
													</tr>
													<tr>
														<td>Main Category</td>
														<td>{$item_details['category_name']} </td> 
													</tr>
													<tr>
														<td>Sub Category</td>
														<td>{$item_details['sub_category_name']} </td> 
													</tr>
													<tr>
														<td>VAT: </td>
														<td>{$item_details['vat_name']}</td> 
													</tr>
													<tr>
														<td>Unit: </td>
														<td>{$item_details['total_quantity']}</td> 
													</tr>
													<tr>
														<td>Type: </td>
														<td>{ucfirst($item_details['type'])|replace:'_':' '}
														</td> 
													</tr>
													<tr>
														<td>Date: </td>
														<td>{$item_details['date']|date_format:"%Y - %m - %d"}</td> 
													</tr>


												</tbody>

											</table>
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
	<div class="col-md-6">

		<div class="row">
			<div class="col-md-12">
				<div class="card"> 

					<div class="card-body">
						<div id="accordion2" role="tablist">
							<div class="card-collapse">
								<div class="card-header" role="tab" id="headingTwo">
									<h5 class="mb-0">
										<a data-toggle="collapse" href="#collapseCustomerDetails" aria-expanded="false" aria-controls="collapseCustomerDetails" class="collapsed">
											Category Details
											<i class="material-icons">keyboard_arrow_down</i>
										</a>
									</h5>
								</div>
								<div id="collapseCustomerDetails" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion2" style="">
									<div class="card-body ">
										<div class="table-responsive">
											<table class="table table-hover">
												
												<thead>
												</thead>
												<tbody> 
													<tr>
														<td >Code: </td>
														<td>{$category_details['code']}</td> 
													</tr>
													<tr>
														<td >Category Name: </td>
														<td>{$category_details['category_name']}</td> 
													</tr>
													{if $item_details['sub_category']}
													<tr>
														<td >Sub Category Code: </td>
														<td>{$sub_category_details['code']}</td> 
													</tr>
													<tr>
														<td >Sub Category Name: </td>
														<td>{$sub_category_details['category_name']}</td> 
													</tr>
													{/if}
												</tbody>

											</table>
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
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Latest Orders</h4>
			</div>
			<div class="card-body">
				{if $order_details}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Sales Code</th> 
								<th>Customer Name</th>
								<th>Date</th>
								<th>Sales Person</th>
								<th>VAT</th>
								<th>Quantity</th>
								<th>Status</th>
								
								
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							{$i=1}
							{foreach $order_details as $v}

							<tr>
								<td >{$i++}</td>
								<td>{$v.code}</td>
								<td>{$v.customer_name}</td>
								<td>{$v.date|date_format:"%Y - %m - %d"}</td> 
								
								<td>
									{$v.salesperson} 
								</td>
								<td> {$v.vat_name} 
								</td>
								<td>
									{$v.quantity} 
								</td>
								<td>{$v.status}
								</td>


								<td class="td-actions text-right ">

									<a href = "{base_url(log_user_type())}/sales/edit-sales/{$v.enc_id}" class="btn btn-sm btn-link btn-info" data-placement="top" title ="View Order Details" target="_blank"><i class="material-icons" aria-hidden="true" target="_blank">local_see</i></a>
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
							<i class="fa fa-exclamation"> No Order Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
			</div>
		</div>
	</div>
</div>
</div


{/if}
{/block}

{block name="footer"}
<script src="{assets_url('js/ui-notifications.js')}"></script>

<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>



<script> 
	$('.item_ajax').select2({

		placeholder: 'Select an Item Code',
		ajax: {
			url:'{base_url()}admin/autocomplete/item_with_name_ajax',

			data: function (params) {

				var query = {
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


</script>

{/block}