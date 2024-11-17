{extends file="layout/base.tpl"}

{block name="body"}  
{if log_user_type()=="store_keeper"}




<div class="row">

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Projects</p>
				<h4 class="card-title">{$project_count}</h4>
			</div>
			{if log_user_type() =="store_keeper"}

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="#" > View Details</a>
				</div>
			</div>
			{/if}
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-icon card-header-icon">
				<div class="card-icon">
					<i class="material-icons">cloud_done</i>
				</div>
				<p class="card-category">Pending Deliveries</p>
				<h4 class="card-title">{$pending_delivery_count}</h4>
			</div>
			{if log_user_type() =="store_keeper"}

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}store_keeper/delivery/delivery-list" > View Details</a>
				</div>
			</div>
			{/if}
		</div>
	</div>


	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-success card-header-icon">
				<div class="card-icon">
					<i class="material-icons">message</i>
				</div>
				<p class="card-category">Send To Delivery</p>
				<h4 class="card-title">{$sendto_delivery_count}</h4>
			</div>
			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="{base_url()}store_keeper/delivery/delivery-list" > {lang('text_view_more')}</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12">
		<div class="card">

			<div class="card-header card-header-rose card-header-icon">
				<div class="card-icon">
					<i class="material-icons">assignment</i>
				</div>
				<h4 class="card-title">Recent Deliveries</h4>
			</div> 

			<div class="card-body table-responsive">
				{if $recent_deliveries} 


				<table class="table table-hover">
					<thead class="text-warning">						
						
						<th>Code</th>
						<th>Project Name</th>
						<th>Driver</th>
						<th>Status</th>
						<th>Vehicle</th>
						<th>Created On</th>					
						<th>Action</th>					
					</thead>
					<tbody> 
						{foreach $recent_deliveries as $p}
						<tr>
							
							<td>{$p.code}</td>
							<td>{$p.project_name}</td>
							<td>{$p.driver_name}</td>
							<td>{ucfirst($p.status)|replace:'_':' '}</td>
							<td>{$p.vehicle}</td>
							<td>{$p.date_created}</td> 

							<td class="td-actions text-right ">
								{if $p.packages}
								<a rel="tooltip" title="QRcode" onClick="printQrCode(this)" class="btn btn-default btn-link"><i class="material-icons">grid_view</i></a>

								{include file="{log_user_type()}/delivery/show_qrcode.tpl"}
								{/if}

								{if $p.status == 'pending'}
								<a rel="tooltip" title="Edit" href="javascript:edit_delivery_note('{$p.enc_id}')" class="btn btn-success btn-link"><i class="material-icons">edit</i></i></a>
								{/if} 

								<a rel="tooltip" title="View" href="{base_url(log_user_type())}/delivery/delivery_details/{$p.enc_id}" class="btn btn-info btn-link"><i class="material-icons">local_see</i></a>
							</td>   
							
						</tr>
						{/foreach}

					</tbody>
				</table>

				<div class="card-footer"> 
					<div class="stats">
						<i class="material-icons">local_offer</i><a href="{base_url()}store_keeper/delivery/delivery-list" > {lang('text_view_more')}</a>
					</div>
				</div>
				{else}
				<div class="alert alert-warning">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<i class="material-icons">close</i>
					</button>
					<span>
						<b> Warning - </b> No Details found
					</span>
				</div>
				{/if}
			</div>

		</div>
	</div>
</div>
{/if}
{/block}

{block name="footer"}
<script type="text/javascript">

	function edit_delivery_note(id)
	{
		swal({
			title:'{lang('text_are_you_sure')}',
			text: "You will not recover",
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: '{lang('text_yes_update_it')}',
			cancelButtonText: '{lang('text_no_cancel_please')}',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = '{base_url(log_user_type())}' + "/delivery/add-delivery-items/"+id; 
			} else {
				swal('{lang('text_cancelled')}','{lang('your_content_safe')}', "error");
			}
		});
	} 
	function printQrCode(ele) {
		var windowUrl = 'about:blank'
		var uniqueName = new Date();
		var windowName = 'Print' + uniqueName.getTime();

		var myPrintContent = $(ele).parent().find('.printdiv')[0];
		var myPrintWindow = window.open(windowUrl, windowName, 'left=300,top=100,width=400,height=400');
		myPrintWindow.document.write(myPrintContent.innerHTML);
		myPrintWindow.document.close();
		myPrintWindow.focus();
		myPrintWindow.print();
		myPrintWindow.close();    
		return false;
	}
</script>

{/block}