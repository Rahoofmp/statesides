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
									<input type="text"  class="form-control" name="user_name"  onClick="autoComplete(this, 'admin', 'autocomplete/user_filter')" autocomplete="Off"   >
									{form_error("user_name")}
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									
									<label for="from_date">{lang('from_date')}</label>
									<div class="col-10">
										<input class="form-control" type="date"  id="from_date" name="from_date" value="{$post_arr['from_date']}">
									</div>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">

									<label for="end_date">{lang('end_date')}</label>
									<div class="col-10">
										<input class="form-control" type="date" id="end_date" name="end_date" value="{$post_arr['end_date']}">
									</div>
								</div>
							</div>
							<div class="col-sm-12"> 
								<button type="submit" class="btn btn-primary" name="submit" value="search">
									<i class="fa fa-filter"></i> {lang('button_filter')}
								</button>
								<button type="submit" class="btn btn-warning mr-1" name="submit" value="reset">
									<i class="fa fa-refresh"></i>  {lang('button_reset')}
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
<div class="row "> 
	<div class="col-sm-12"> 
		<div class="card"> 
			<div class="card-content">
				<div class="card-body">  
					
					<div class="table-responsive">
						<table class="table color-table primary-table" id="exportTable">
							<thead >
								<tr>
									<th>#</th>
									<th>User Name</th>
									<th>User ePin</th> 
									<th>Package</th> 
									<th>Used By</th> 
									<th>Status</th>  
									<th>Created Date</th>  
									<th>Used Date</th> 
									<th>Action</th> 
								</tr>
							</thead>
							<tbody>
								{foreach from=$user_epin item=v}
								<tr>
									<td>{counter}</td> 
									<td>{$v.user_name}</td> 
									<td>{$v.random_string}</td> 
									<td>{$v.package_name}</td> 
									<td>{if $v.used_by_user}{$v.used_by_user}{else}NA{/if}</td> 
									<td>{if $v.status == 1}Active{else}Inactive{/if}</td> 
									<td>{$v.created_date}</td> 
									{if $v.status==0}
									<td>{$v.used_date}</td> 
									{else}

									<td>NA</td> 
									{/if}
									<td>
										<a href = "{base_url()}{log_user_type()}/settings/removePin/{$v.id}" class="btn btn-light" data-placement="top" title="Delete " data-original-title = "Delete " >
											<i class= "fa fa-trash-o fa fa-trash"></i></a>
										</td>
									</tr> 
									{/foreach}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div> 
		</div> 
	</div> 
	{/block}