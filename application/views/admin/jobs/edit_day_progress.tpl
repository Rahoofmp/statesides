
{form_open('admin/jobs/update_day_progress','id="fromDayProgress"')}
<div class="form-group">
	<label for="exampleEmail" class="bmd-label-floating"> Date *</label>                   
	<input type="text" readonly class="form-control" value="{$day_progress.date_added|date_format:"%Y-%m-%d"}">

</div>
<div class="form-group">
	<label for="today_status" class="bmd-label-floating">Work Description *</label>
	<textarea class="form-control" required name="today_status" required>{$day_progress.today_status}</textarea>
	{form_error('today_status')}
</div>
<div class="form-group">
	<label for="employees_worked" class="bmd-label-floating">Employees Worked *</label>
	<textarea class="form-control" required name="employees_worked" required>{$day_progress.employees_worked}</textarea>
	{form_error('employees_worked')}
</div>
<div class="form-group">
	<label for="worked_time" class="bmd-label-floating"> Worked Time *</label>
	<input class="form-control" type="text" name="worked_time" number="true" required="true" aria-required="true" id="worked_time" value="{$day_progress.worked_in_min}"> 
	{form_error('worked_time')}
</div>
<div class="form-check mr-auto">
	<div class="togglebutton">
		<label>
			In minutes 
			<input type="checkbox" name="time_span" value="hr">
			<span class="toggle"></span>
			In Hours
		</label>
	</div>
</div>
<input type="hidden" class="form-control" required name="job_order_id" value="{$job_order_id}">
<input type="hidden" class="form-control" required name="progress_id" value="{$progress_id}">
<input type="hidden" class="form-control" required name="department_id" value="{$department_id}">{$department_id}
{form_error('job_order_id')} 
{form_error('progress_id')} 
{form_error('department_id')} 
<div class="card-footer text-right">
	<button type="button" class="btn btn-default"  data-dismiss="modal">Close</button>


	<button type="submit" class="btn btn-primary" name="today_progress" value="today_progress">Update</button>
</div>
{form_close()}