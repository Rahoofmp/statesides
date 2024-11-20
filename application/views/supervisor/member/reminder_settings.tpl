{extends file="layout/base.tpl"}

{block body}    
<div class="row"> 


	<div class="col-sm-12">
		<div class="card">
			<div class="card-header card-header-tabs card-header-info">
				<div class="nav-tabs-navigation">
					<div class="nav-tabs-wrapper"> 
						<ul class="nav nav-tabs" data-tabs="tabs">

							<li class="nav-item">
								<a class="nav-link active" href="#change_password" data-toggle="tab">
									<i class="material-icons">code</i> Create New Reminder
									<div class="ripple-container"></div>
								</a>
							</li> 
						</ul>
					</div>
				</div>
			</div>
			<div class="card-body">
				<div class="tab-content">
					<div class="tab-pane active" id="change_password">
						{form_open("")} 
						<div class="row"> 
							<div class="col-md-6">
								<div class="form-group bmd-form-group">
									<label class="bmd-label-floating">Message</label>
									<input type="message" class="form-control" name="message">
									{form_error('message')}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group bmd-form-group">
									<label for="from_date"> Date</label>
									<input type="date" id="date" class="form-control datepicker" name="date" autocomplete="off">
									{form_error("date")} 
								</div>
							</div>
						</div>

						<div class="form-group bmd-form-group">
							<button type="submit" class="btn btn-rose pull-right" name="reminder" value="reminder">Set Reminder</button>
						</div>
						{form_close()}
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
				<h4 class="card-title">Recent Leads(Latest 10)</h4>
			</div>
			<div class="card-body">
				{if $current_reminders}
				<div class="table-responsive">
					<table class="table">
						<thead class="bg-light text-warning">
							<tr>
								<th class="text-center">#</th>
								<th>Message</th>
								<th>Date</th>
								
								
							</tr>
						</thead>
						<tbody>

							{foreach $current_reminders as $v} 

							<tr>
								<td >{counter}</td>
								<td>{$v.message}</td>
								<td>{$v.date}</td>
							
							</tr>
							{/foreach}
						</tbody>
					</table>
				</div>
				<div class="card-footer"> 
							<!-- <div class="stats">
								<i class="material-icons">local_offer</i><a href="{base_url()}{log_user_type()}/packages/list-leads" > {lang('text_view_more')}</a>
							</div> -->
						</div>
				{else}
				<div class="card-body">
					<p>
						<h4 class="text-center"> 
							<i class="fa fa-exclamation"> No Reminders  Found</i>
						</h4>
					</p>
				</div>
				{/if}
			</div>
		</div>
		
	</div>
	
</div>
{/block}
{block name="header"}

<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
{/block}

{block name="footer"}
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script></script> 
{/block}