{extends file="layout/base.tpl"}

{block name="body"}  


<div class="row">

	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Reminder</h5>
					<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button> -->
				</div>
				<div class="modal-body">
					<p>{$today_reminder['message']}</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Leads</p>
				<h4 class="card-title">{$project_count}</h4>
			</div>
			

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="#" > View Details</a>
				</div>
			</div>
			
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Customers</p>
				<h4 class="card-title">{$project_count}</h4>
			</div>
			

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="#" > View Details</a>
				</div>
			</div>
			
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Staffs</p>
				<h4 class="card-title">{$project_count}</h4>
			</div>
			

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="#" > View Details</a>
				</div>
			</div>
			
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Customers</p>
				<h4 class="card-title">{$project_count}</h4>
			</div>
			

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="#" > View Details</a>
				</div>
			</div>
			
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Customers</p>
				<h4 class="card-title">{$project_count}</h4>
			</div>
			

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="#" > View Details</a>
				</div>
			</div>
			
		</div>
	</div>
	<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="card card-stats">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon">
					<i class="material-icons">format_align_right</i>
				</div>
				<p class="card-category">Total Customers</p>
				<h4 class="card-title">{$project_count}</h4>
			</div>
			

			<div class="card-footer">
				<div class="stats">
					<i class="material-icons">local_offer</i><a href="#" > View Details</a>
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
				<h4 class="card-title">Recent Leads</h4>
			</div>
			<div class="card-body">
				{if $details}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Name</th>
								<th>Enquiry Status</th>
								<th>Immigration Status</th>
								<th>Mobile</th>
								<th>Email</th>
								<th>Source</th>
								<th>Due Date</th>
								<!-- <th>Action</th> -->
								
							</tr>
						</thead>
						<tbody>

							{foreach $details as $v} 

							<tr>
								<td >{counter}</td>
								<td>{$v.firstname}</td>
								<td>{$v.enquiry_status}</td>
								<td>{$v.immigration_status}</td>
								<td>{$v.mobile}</td>
								<td>{$v.email}</td>
								<td>{$v.source_name}</td>
								<td>{$v.date}</td>
								
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
				<div class="card-footer"> 
					<div class="stats">
						<i class="material-icons">local_offer</i><a href="{base_url()}{log_user_type()}/customer/customer-list" > {lang('text_view_more')}</a>
					</div>
				</div>
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
			</div>
		</div>

	</div>
</div>

{/block}

{block name="footer"}


<script type="text/javascript">
	$(document).ready(function () {

		var todayReminder = '{$today_reminder}';
		var reminderId = '{$reminder_id}';


		if (todayReminder) {
			$('#exampleModal .modal-body p').text(todayReminder);
			$('#exampleModal').modal('show');
		}

		$('#exampleModal .btn-secondary').click(function() {



			$.ajax({
				url: '{base_url()}login/reminder_change',
				type: 'POST',
				data: {
					id: reminderId,        
					status: 'closed'        
				},
				success: function(response) {
					console.log('Status updated successfully:', response);

					$('#exampleModal').modal('hide');
				},
				error: function(xhr, status, error) {
					console.error('Error updating status:', error);
				}
			});
		});
	});
</script>


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