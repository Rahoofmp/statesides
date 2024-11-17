{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}"> 
{/block}

{block body}

<div style="display:none;">
	<span id="base_url">{base_url()}</span>
	<span id="text_are_you_sure">Are you sure ? </span>
	<span id="text_you_will_not_recover">You will not recover</span>
	<span id="text_yes_delete_it">Yes , delete it</span>
	<span id="text_no_cancel_please">No , cancel please</span>
	<span id="text_deleted">Deleted</span>
	<span id="text_news_deleted">News deleted</span>
	<span id="text_course_activated">Activated</span>
	<span id="text_cancelled">Cancelled</span>
	<span id="text_news_safe">Market safe</span>
	<span id="text_you_want_edit_news">You want edit market ?</span>
	<span id="text_yes_edit_it">Yes , edit it</span>
	<span id="text_yes_desctivate_it">Yes , deactivate it</span>
	<span id="text_yes_activate_it">Yes , activate it</span>
	<span id="text_yes_edit_it">Yes , edit it</span>
</div>
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header card-header-primary">
				<h4 class="card-title">Add Market</h4>
			</div>
			<div class="card-body">
				<div id="accordion" role="tablist">
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingOne">
							<h5 class="mb-0">
								<a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="collapsed">
									Collapsible Group Item #1
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
							<div class="card-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingTwo">
							<h5 class="mb-0">
								<a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
									Collapsible Group Item #2
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo" data-parent="#accordion">
							<div class="card-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
							</div>
						</div>
					</div>
					<div class="card-collapse">
						<div class="card-header" role="tab" id="headingThree">
							<h5 class="mb-0">
								<a class="collapsed" data-toggle="collapse" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
									Collapsible Group Item #3
									<i class="material-icons">keyboard_arrow_down</i>
								</a>
							</h5>
						</div>
						<div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree" data-parent="#accordion">
							<div class="card-body">
								Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
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
			<div class="card-header card-header-primary">
				<h4 class="card-title">Add Market</h4>
			</div>
			<div class="card-body">
				{form_open('','id="file_form" name="file_form" class="form-add-market" enctype="multipart/form-data"')}

				<div class="form-group">
					<label class="bmd-label-floating">
						Pair
					</label>
					<input type="text" class="form-control" id="pair" name="pair" {if $edit_id} value="{$edit_details['pair']}" {else} value="{set_value('pair')}" {/if} required="true">
					{form_error("pair")}
				</div>

				<div class="form-group">
					<label class="bmd-label-floating">
						Percentual
					</label>
					<input type="text" class="form-control" id="percentual" name="percentual" {if $edit_id} value="{$edit_details['percentual']}" {else} value="{set_value('percentual')}" {/if} required="true">
					{form_error("percentual")}
				</div>

				<div class="form-group">
					<label class="bmd-label-floating">
						Bot
					</label>
					<input type="text" class="form-control" id="bot" name="bot" {if $edit_id} value="{$edit_details['bot']}" {else} value="{set_value('bot')}" {/if} required="true">
					{form_error("bot")}
				</div>


				<div class="form-group">
					<label class="bmd-label-floating">
						Date
					</label>

					<input type="text" id="date" class="form-control datepicker" name="date" value="{$edit_details['date']}" >
					{form_error("date")}
				</div>



				<div>
					<label class="bmd-label-floating">
						Exchange
					</label>
					<div class="fileupload fileupload-new" data-provides="fileupload">
						<div class="fileupload-preview fileupload-exists thumbnail col-sm-12"></div>
						<div class="user-edit-image-buttons">
							<span class="btn btn-azure btn-file btn btn-info btn-sm tooltips edit_hsn"><span class="fileupload-new"><i class="fa fa-picture"></i> Select Image</span><span class="fileupload-exists"><i class="fa fa-picture"></i> {lang('change')}</span>
							<input type="file" id="userfile" name="userfile"><i class="material-icons">attach_file</i>
						</span>
						<a href="#" class="btn fileupload-exists btn-red" data-dismiss="fileupload">
							<i class="fa fa-times"></i> Remove
						</a>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="bmd-label-floating">
					Sort Order
				</label>
				<input type="text" class="form-control" id="sort_order" name="sort_order" {if $edit_id} value="{$edit_details['sort_order']}" {else} value="{set_value('sort_order')}" {/if} number="true" required="true" >
				<span id="error_sort_order"></span>
			</div>


			{if $edit_id}

			<div class="form-group">
				<button class="btn btn-primary pull-right" type="submit" id="update_market" name="update_market" value="update_market">
					Update market <i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>

			{else}

			<div class="form-group">
				<button class="btn btn-primary pull-right" type="submit" id="add_market" name="add_market" value="add_market">
					Add Market <i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>

			{/if}

			{form_close()}

		</div>
	</div>
</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				<h4 class="card-title ">  Available Market </h4>
			</div>
			<div class="card-body">
				{if count($market_details) > 0}
				<div class="table-responsive">
					<table class="table table-hover" id="sample-table-1">
						<thead>
							<tr>
								<th class="center">#</th>
								<th>Date</th>
								<th>Pair</th>
								<th>Percentual</th>
								<th>Bot</th>
								<th>Exchange</th>
								<th>Sort Order
								</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tbody>
							{foreach $market_details as $v} 
							{$i=$i+1}
							<tr>
								<td >{$i}</td>
								<td>{$v.date}</td>
								<td>{$v.pair}</td>
								<td>{$v.percentual}</td>
								<td>{$v.bot}</td>
								<td class="hidden-xs">

									<div class="fileupload-new thumbnail"><img src="{assets_url()}uploads/Market/{$v.exchange}" alt="" style="width:50px;height:40px;">
									</div>
									
									<a href="{assets_url()}uploads/Market/{$v.exchange}" class="btn btn-info btn-sm tooltips edit_hsn" data-placement="top" data-original-title="{lang('text_view')}"  target="blank" ><i class="material-icons">pageview</i></a>
								</td>
								
								<td class="hidden-xs">{$v.sort_order}</td>
								
								<td class="center">
									
									{if $v.status == 'yes'}
									<div class="visible-md visible-lg hidden-sm hidden-xs">
										<a  href="javascript:inactivate_market({$v.id})" class="btn btn-info btn-sm tooltips edit_hsn" data-placement="top" data-original-title="{lang('deactivate_it')}"><i class="material-icons">close</i></a>
									</div>	
									{else}
									<div class="visible-md visible-lg hidden-sm hidden-xs">
										<a  href="javascript:activate_market({$v.id})" class="btn btn-info btn-sm tooltips edit_hsn" data-placement="top" data-original-title="{lang('activate_it')}"><i class="material-icons">done</i></a>
									</div>	
									{/if}
									<div class="visible-md visible-lg hidden-sm hidden-xs">
										<a  href="javascript:edit_market({$v.id})" class="btn btn-info btn-sm tooltips edit_hsn" data-placement="top" data-original-title="{lang('text_deactivate')}"><i class="material-icons">edit</i></a>
									</div>	

									
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
							<i class="fa fa-exclamation"> No Market Details Found</i>
						</h4>
					</p>
				</div>
				{/if}
			</div>
		</div>
	</div>
</div>
{/block}

{block footer} 
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
<script src="{assets_url('js/confirm.js')}"></script>
<script src="{assets_url('js/page-js/settings.js')}"></script>
<script src="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}"></script>

<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 

<script>
	$(document).ready(function() { 
		md.initFormExtendedDatetimepickers();
	});
</script>


<script type="text/javascript">

	$("#sort_order").keypress(function(e)
	{
		var msg20 = "{lang('digits_only')}";
		if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57))
		{
			$("#error_sort_order").html(msg20).show().fadeOut(2200, 0);
			return false;
		}
	});
</script>

{/block}
