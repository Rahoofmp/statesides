{extends file="layout/base.tpl"}

{block name="header"} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('support/css/popup.css')}"> 
{/block}

{block name="body"}
<div style="display:none;">
	<span id="base_url">{base_url()}</span>
	<span id="text_are_you_sure">{lang('text_are_you_sure')}</span>
	<span id="text_you_will_not_recover">{lang('text_you_will_not_recover')}</span>
	<span id="text_yes_delete_it">{lang('text_yes_delete_it')}</span>
	<span id="text_no_cancel_please">{lang('text_no_cancel_please')}</span>
	<span id="text_deleted">{lang('text_deleted')}</span>
	<span id="text_news_deleted">{lang('text_news_deleted')}</span>
	<span id="text_cancelled">{lang('text_cancelled')}</span>
	<span id="text_news_safe">{lang('text_news_safe')}</span>
	<span id="text_you_want_edit_news">{lang('text_you_want_edit_news')}</span>
	<span id="text_yes_edit_it">{lang('text_yes_edit_it')}</span>
	<span id="error_the_fieldid_field_is_required">{lang('error_the_fieldid_field_is_required')}</span>
	<span id="error_atleast_number">{lang('error_atleast_number')}</span>
	<span id="error_enter_greater_number">{lang('error_enter_greater_number')}</span>
</div>

{include file="{log_user_type()}/mail/header.tpl"  name=""}

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				<h4 class="card-title ">  {lang('text_inbox')}</h4></div>
				<div class="card-body">

					<div class="box-solid">
						<div class="box-body">
							<div class="col-md-12 col-sm-12">
								<div class="table-responsive">
									<table class="table table-mailbox table-hover"> 

										<thead>
											{if $messages}
											<tr>
												<th>{lang('text_user_name')}</th>
												<th>{lang('text_subject')}</th>
												<th>{lang('text_message')}</th>
												<th>{lang('text_date')}</th>
												<th>{lang('text_action')}</th>
											</tr>
										</thead>
										<tbody>

											{foreach from=$messages item=v}
											{$id = $v.id}    

											<tr>
												<td class="name">{if $v.type == 'team'}ALL{else}{$v.user_name}{/if}
												</td>
												<td class="subject">{$v.subject}
												</td>
												<td class="message">
													<form method="post" action="/actions.php/" data-js-validate="true" data-js-highlight-state-msg="true" data-js-show-valid-msg="true">

														<button class="btn btn-primary payBtn update-email-status" type="submit" name="confirm" id="{$v.id}">View Message</button>
													</form>

													{* {$v.message} *}
												</td>
												<td class="time">{$v.date}
												</td>
												<td class="center"> 
													<div class="visible-md visible-lg hidden-sm hidden-xs buttons-widget">

														<a class="btn btn-xs btn-red tooltips" data-placement="top" title="Reply" data-srcid="{$id}" href="{base_url()}{log_user_type()}/mail/reply_mail/{$id}" ><i class="material-icons">reply</i></a>
													</div>
													<div class="visible-md visible-lg hidden-sm hidden-xs buttons-widget">

														<a class="btn btn-xs btn-red tooltips" data-placement="top" title="Delete message" data-srcid="{$id}" href="javascript:deleteMessage({$id},'{log_user_type()}','inbox')" ><i class="material-icons">close</i></a>
													</div>
												</td>   
											</tr> 

											<div class="modal fade" id="myModal{$v.id}" role="dialog">
												<div class="modal-dialog">
													<!-- Modal content-->
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal">&times;</button>
															<h4 class="modal-title">{$v.subject}</h4>
														</div>
														<div class="modal-body">
															{$v.message}

														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														</div>
													</div>
												</div>
											</div>





											{/foreach}
											{else}
											<div class="card-body">
												<p>
													<h4 class="text-center"> 
														<i class="fa fa-exclamation"> {lang('text_no_transfer_details_found')}</i>
													</h4>
												</p>
											</div>

											{/if}
										</tbody>
									</table>
								</div>
							</div>
							<div class="box-footer clearfix">
								<div class="pull-right">
									<small> {$result_per_page}</small>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{/block}

	{block name="footer"} 
	
	<script src="{assets_url('js/ui-notifications.js')}"></script>
	<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
	<script src="{assets_url('js/confirm.js')}"></script>
	<script src="{assets_url('support/js/pop_up.js')}"></script>
	<script type="text/javascript">

		$('document').ready(function(){

			$('.payBtn').on('click',function(e){
				e.preventDefault();
				var id = $(this).attr('id');
				$('#myModal'+id).modal('toggle');

			});

		});





		$(document).on('click', ".update-email-status" , function(event)
		{

			var userid = $(this).attr('id');
			var Url = $("#rootPath").val() + "/"+  $("#logType").val() + "/mail/readEmail";  
			$.post(Url,{
				user_id: userid
			},function(data){ 
                  
                        
                    });

		});



	</script>

	{/block} 



