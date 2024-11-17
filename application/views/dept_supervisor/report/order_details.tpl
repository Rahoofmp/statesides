{extends file="layout/base.tpl"}


{block body}

<div class="row">  
	<div class="col-sm-12 hidden-print text-center no-print"> 
		<div class="card">  
			<div class="card-body"> 
				<a class="btn btn-github" href="javascript:close_window();">
					<span class="btn-label">
						<i class="material-icons">keyboard_return</i>
					</span>
					Close
				</a>

				<button class="btn btn-info" onclick="javascript:window.print();">
					<span class="btn-label">
						<i class="material-icons">print</i>
					</span>
					Print
				</button> 
			</div>

		</div> 
	</div>  
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="invoice">
					<div class="row">
						<div class="col-sm-12">
							<div class="media">
								<div class="media-left">
									<img class="media-object img-60 " src="{assets_url()}/images/logo/{$site_details['logo']}" >
									<span class="clearfix"></span>
									
								</div>
								<div class="media-body ml-4">
									<h5 class="media-heading mt-2 mb-2">{$site_details['name']}</h5>
									<p>{$site_details['email']}<br><span>{$site_details['phone']}</span></p>
								</div>
							</div> 
						</div>
						
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-7">
							<div class="row">
								<div class="col-9">
									<h6>Job Details</h6>
									<div class="media">

										<div class="media-body m-l-20">

											<small>Job Name: {$job_details['name']}</small>
											<span class="clearfix"></span>
											<small>Order id: {$job_details['order_id']}</small>
											<span class="clearfix"></span>

											<small>Order Date: {$job_details['date']}</small>
											<span class="clearfix"></span>  
											<small>Requested Date: {$job_details['requested_date']}</small>
											<span class="clearfix"></span>
											<small>Total Cost : {cur_format($job_details['department_cost'])}</small>
											<span class="clearfix"></span>
											<small>Total Working hours: {$job_details['estimated_working_hrs']} Hrs</small>
											<span class="clearfix"></span>   
											<small>Status: 
												{if $job_details['customer_status'] == 'reject'}Rejected
												{elseif $job_details['customer_status'] == 'pending'}Pending
												{elseif $job_details['customer_status'] == 'confirm'}Approved
												{ucfirst($job_details['customer_status'])}
												{/if}
											</small>

											<span class="clearfix"></span>
										</div>
									</div>
								</div>
							</div>
						</div> 
						<div class="col-sm-5">
							<div class="pull-right text-right" id="project">
								<h6>Customer Details</h6>
								<small>Name: {$job_details['customer_name']}</small>  
								<span class="clearfix"></span> 
								<small>Email: {$job_details['customer_email']}</small>  
								<span class="clearfix"></span> 
								<small>Mobile: {$job_details['customer_mobile']}</small>  

								<hr>
								<h6>Project Details</h6>
								<small>Name: {$job_details['project_name']}</small>  
								<span class="clearfix"></span>
								<small>E-mail: {$job_details['project_email']} </small>   
								<span class="clearfix"></span> 
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive invoice-table mt-4" id="table">	
					<table class="table">
						<thead class="bg-light">

							<tr class="font-italic">
								<th> Sl. No: </th> 
								<th> Department  Name </th>
								<th> Description</th> 
								<th> Dept. Cost</th> 
								<th> Estimated Working Time </th> 
								<th> Status </th> 
							</tr>
						</thead>
						<tbody>   
							{foreach from=$job_details['job_orders'] item=v key=key  }
							<tr>
								<td>{counter}</td>  
								<td>{$v.department_name}</td> 
								<td>{$v.order_description}</td>  
								<td>{cur_format($v.department_cost)}</td>  
								<td>{$v.estimated_working_hrs} Hrs</td> 
								<td>{ucfirst($v.work_status)}</td> 
							</tr>
							{/foreach}	 
						</tbody>
					</table>
				</div> 
			</div>
		</div>
	</div>
</div> 

{/block}

{block footer}  
<script>	
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
	function close_window() { 
			window.close(); 
	}	
</script>
{/block}