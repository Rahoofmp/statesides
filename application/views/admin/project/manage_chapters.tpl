{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}"> 
{/block}

{block body}

<div style="display:none;">
	<span id="base_url">{base_url()}</span>
	<span id="text_are_you_sure">{lang('text_are_you_sure')}</span>
	<span id="text_you_will_not_recover">{lang('text_you_will_not_recover')}</span>
	<span id="text_yes_delete_it">{lang('text_yes_delete_it')}</span>
	<span id="text_no_cancel_please">{lang('text_no_cancel_please')}</span>
	<span id="text_deleted">{lang('text_deleted')}</span>
	<span id="text_news_deleted">{lang('text_news_deleted')}</span>
	<span id="text_course_activated">{lang('text_course_activated')}</span>
	<span id="text_cancelled">{lang('text_cancelled')}</span>
	<span id="text_news_safe">{lang('text_news_safe')}</span>
	<span id="text_you_want_edit_news">{lang('text_you_want_edit_news')}</span>
	<span id="text_yes_edit_it">{lang('text_yes_edit_it')}</span>
	<span id="text_yes_desctivate_it">{lang('text_yes_desctivate_it')}</span>
	<span id="text_yes_activate_it">{lang('text_yes_activate_it')}</span>
	<span id="text_yes_edit_it">{lang('text_yes_edit_it')}</span>
	<span id="chapter_deactivated">{lang('chapter_deactivated')}</span>
</div>


{form_open('','id="file_form" name="file_form" class="form-add-chapters" enctype="multipart/form-data"')}
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				<h4 class="card-title">{lang('manage_chapters')}</h4>
			</div>
			<div class="card-body"> 

				<div class="card-body">
					<select class="custom-select form-control required" id="course_id" name="course_id">

						<option value="">{lang('select_course')}</option>
						{foreach $active_courses as $v}
						<option value="{$v.id}" {set_select('course_id',  $v.id )} >{$v.cover_heading}</option>
						{/foreach}

					</select>
				</div>

				<div class="card-body" style="display: none">
					<select class="custom-select form-control required" id="language" name="language">
						<option value="english" {set_select('language',  'english' )} > {lang('english')} </option>
						<option value="arabic" {set_select('language',  'arabic' )} > {lang('arabic')} </option>

					</select>
				</div>

				<div class="card-body">
				</div>

				<div class="card-body">
					<button class="btn btn-primary pull-right" type="submit" id="show_chapters" name="submit" value="show_chapters">
						{lang('manage_chapters')} <i class="fa fa-arrow-circle-right"></i>
					</button>
				</div>
			</div>
		</div>
	</div>  
</div>  
{* {if $smarty.post.submit == 'show_chapters'} *}
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				<h4 class="card-title ">  {lang('manage_chapters')} </h4>
			</div>
			{if $course_name != "NA"}
			<div class="card">
				<div class="card-header card-header-primary">
					<h4 class="card-title ">  {lang('course_name')}   <b>{$course_name}</h4>
				</div>
			</div>
			{/if}



			<iframe id="myFrame" style="display:none" width="600" height="300"></iframe>



			<div class="card-body">
				{if count($chapter_details) > 0}
				<div class="table-responsive">
					<table class="table table-hover" id="sample-table-1">
						<thead>
							<tr>
								<th class="center">#</th>
								<th>{lang('heading')}</th>
								<th>{lang('document')}</th>
								<th>{lang('added_date')}</th>
								<th class="hidden-xs">{lang('text_sort_order')}
								</th>
								<th>{lang('actions')}</th>
							</tr>
						</thead>
						<tbody>
							{assign var="counter" value="0"}
							{foreach $chapter_details as $v} 
							{$i=$i+1}
							<tr>
								<td class="center">{$i}</td>
								<td class="hidden-xs">{$v.name}</td>
								<td class="hidden-xs">

									<div class="fileupload-new thumbnail"><img src="{assets_url()}images/course_chapters/{$v.file}" alt="" style="width:100px;height:100px;">
									</div>

									<a href="{assets_url()}images/course_chapters/{$v.file}" class="btn btn-info btn-sm tooltips edit_hsn" data-placement="top" data-original-title="{lang('text_view')}"  target="blank" ><i class="material-icons">pageview</i></a>
								</td>
								<td>{$v.date}</td>
								<td class="hidden-xs"><input type="number" class="form-control" id="sort_order" name="sort_order{$counter}" value="{$v.order_id}" number="true" required="true" > 

									<input type="hidden" class="form-control" id="table_id" name="table_id{$counter}" value="{$v.id}" >

								</td>
								<td class="center">
									
									{if $v.status == 1}
									<div class="visible-md visible-lg hidden-sm hidden-xs">
										<a  href="javascript:inactivate_chapter({$v.id})" class="btn btn-info btn-sm tooltips edit_hsn" data-placement="top" data-original-title="{lang('deactivate_it')}"><i class="material-icons">close</i></a>
									</div>	
									{else}
									<div class="visible-md visible-lg hidden-sm hidden-xs">
										<a  href="javascript:activate_chapter({$v.id})" class="btn btn-info btn-sm tooltips edit_hsn" data-placement="top" data-original-title="{lang('activate_it')}"><i class="material-icons">done</i></a>
									</div>	
									{/if}

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
							<i class="fa fa-exclamation"> {lang('no_chapters_found')}</i>
						</h4>
					</p>
				</div>
				{/if}

			</div>


			<div class="card-body">
				<button class="btn btn-primary pull-right" type="submit" id="update_order" name="update_order" value="update_order">
					{lang('update_sort_order')} <i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>


		</div>



	</div> 
</div> 
{* {/if} *}
{form_close()}
{/block}

{block footer} 
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
<script src="{assets_url('js/confirm.js')}"></script>
<script src="{assets_url('js/upload.js')}"></script>
<script src="{assets_url('js/page-js/settings.js')}"></script>
<script src="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}"></script>


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
