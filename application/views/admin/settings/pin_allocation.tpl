{extends file="layout/base.tpl"}

{block header }  
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" />   
<link href="{assets_url('plugins/select2/select2.css')}" rel="stylesheet" />   
<style type="text/css">
	
	@media print {
		.hidden-print { display: none; }
	}
</style> 
{/block}

{block footer }   
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 

<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('plugins/select2/select2.min.js')}"></script> 

<script src="{assets_url('plugins/tableExport/tableExport.js')}"></script> 
<script src="{assets_url('plugins/tableExport/jquery.base64.js')}"></script> 
<script src="{assets_url('plugins/tableExport/html2canvas.js')}"></script> 
<script src="{assets_url('plugins/tableExport/jquery.base64.js')}"></script> 
<script src="{assets_url('plugins/tableExport/jspdf/libs/sprintf.js')}"></script> 
<script src="{assets_url('plugins/tableExport/jspdf/jspdf.js')}"></script> 
<script src="{assets_url('plugins/tableExport/jspdf/libs/base64.js')}"></script> 
<script src="{assets_url('js/table-export.js')}"></script> 



<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
		TableExport.init(); 
	});
</script>
{/block}

{block body } 
<div class="row "> 
	<div class="col-sm-12 hidden-print"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body"> 
					{form_open('','')}
					<div class="form-body">
						<div class="row">
					
							<div class="col-sm-4">
								<div class="form-group">
									<label for="issueinput3">{lang('username')}</label>
									<input type="text"  class="form-control" name="user_name"  onClick="autoComplete(this, 'admin', 'autocomplete/user_filter')"  autocomplete="Off" required   >
									{form_error("user_name")}
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="issueinput3">Count</label>
									<input type="text"  class="form-control" name="count"   value="1" autocomplete="Off"   >
									{form_error("count")}
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group"> 


									<select class="form-control" name="bv_select" id="bv_select" required>
										<option value="">Select a Package</option>
										{foreach $packages as $v}
										<option value="{$v.pack_id}">{$v.name}</option>
										{/foreach}
									</select>
									{form_error("bv_select")}

								</div> 
							</div>
						</div>

						<div class="col-sm-12"> 
							<button type="submit" class="btn btn-primary" name="submit" value="submit">
								<i class="fa fa-filter"></i> submit
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
{* <div class="row "> 
	<div class="col-sm-12"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body">  

					<div class="table-responsive">
						<table class="table color-table primary-table" id="exportTable">
							<thead >
								<tr>
									<th>#</th>
									<th>{lang('username')}</th>
									<th>{lang('sponsor_name')}</th> 
									<th>{lang('full_name')}</th> 
									<th>{lang('status')}</th>  
									<th>{lang('email_id')}</th>  
									<th>{lang('joining_date')}</th> 
								</tr>
							</thead>
							<tbody>
								{foreach from=$members item=v}
								<tr>
									<td>{counter}</td> 
									<td>{$v.user_name}</td> 
									<td>{$v.sponsor_name}</td> 
									<td>{$v.full_name}</td> 
									<td>{$v.status}</td> 
									<td>{$v.email}</td> 
									<td>{$v.joining_date}</td> 
								</tr> 
								{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div> 
	</div> 
</div>  *}
{/block}