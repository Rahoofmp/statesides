
{extends file="layout/base.tpl"}

{block header}   
<style type="text/css">

@media print {
	.printdiv { display: block !important; }
}
.btn-actions .btn {
	margin: 0;
	padding: 5px;
}
.timeline > li > .timeline-badge { 
    font-size: 11px; 
    font-weight: bold;
    padding-top: 7px !important; 
    line-height: 17px;
} 
</style>
{/block}

{block body} 

{form_open('','id="fromDayProgress"')}
<div class="col-md-12">
	<ul class="timeline">

		{foreach from=$job_day_progress item=item key=key}
		{$index = $key%4}
		{$index_mod = $key%2}
		<li {if $index_mod == '0'}class="timeline-inverted"{/if}>
			<div class="timeline-badge {$progress_color[$index]}"> 
				{$item['date_added']|date_format: "%I:%M"}
				<span class="clearfix"></span>
				{$item['date_added']|date_format: "%p"}
			</div>  
			<div class="timeline-panel">
				<div class="timeline-heading">
					<span class="badge badge-pill badge-{$progress_color[$index]}">{$item['date_added']|date_format}</span>
				</div>
				<div class="timeline-body">
					<p>{$item['today_status']}</p>
				</div>
				<div class="timeline-body">
					<p>{$item['employees_worked']}</p>
				</div>
				<hr>
				<div class="row"> 
					<h6 class="col-sm-9"> 
						<span class="material-icons" style="font-size: 12px;"> schedule </span> <i>Total Worked </i>: {$item['worked_in_min']} Hrs
					</h6>

					<div class="btn-actions text-right col-sm-3"> 
						<button type="button" rel="tooltip" class="btn btn-info edit-progress btn-link" data-original-title="Edit" title="Edit" data-job_order_id="{$item['job_order_id']}"data-department_id="{$item['department_id']}" data-id="{$item['id']}">
							<i class="material-icons">edit</i>
							<div class="ripple-container"></div>
						</button>
						{if $smarty.now|date_format:"%Y-%m-%d" == $item.date_added}
						<a rel="tooltip" title="Delete" href="javascript:delete_event('/{$item.enc_id}/{$item.enc_job_id}/{$item.enc_department_id}')" class="btn btn-danger btn-link"><i class="material-icons">delete</i></a>{/if}
					</div>
				</div>
			</div>
		</li>


		{/foreach}  
	</ul>
</div>  

{form_close()}
<div class="modal fade" id="empModal" role="dialog">
	<div class="modal-dialog"> 
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Day Progress</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">	

			</div> 
		</div>
	</div>
</div>
{/block}

{block footer}  
<script> 
	$('.edit-progress').click(function(){
		var ID = $(this).data('id');
		var jobOrderId = $(this).data('job_order_id');
		var department_id = $(this).data('department_id');
		$.ajax({
			url: '{base_url(log_user_type())}/jobs/edit-day-progress',
			type: 'post',
			data: { job_order_id: jobOrderId, progress_id: ID,department_id:department_id },
			success: function(response){  
				$('.modal-body').html(response.html);
				$('#empModal').modal('show'); 
			}
		});

		$('.modal-body').html('response');

		$('#empModal').modal('show'); 
	});

     
	function delete_event(id)
	{
		swal({
			title: 'Are you sure?',
			text: 'do you want to delete ',
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#DD6B55",
			confirmButtonText: 'Delete',
			cancelButtonText: 'Cancel Please',
			closeOnConfirm: false,
			closeOnCancel: false
		},
		function (isConfirm) {
			if (isConfirm) {
				document.location.href = '{base_url(log_user_type())}' +"/jobs/delete_day_progres/delete/"+id;  
			} else {
				swal('{lang("text_cancelled")}','{lang("text_cancelled")}', "error");
			}
		});
	}
 

</script>

{/block}