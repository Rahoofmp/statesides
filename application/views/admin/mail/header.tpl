

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header card-header-primary">
				<h4 class="card-title ">  Shotcuts</h4></div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-4">

							<a tabindex="2" href="{base_url()}{log_user_type()}/mail/compose-mail" class="btn btn-default btn-block {if current_uri()=="mail/compose-mail"}active{/if}" >
								<i class="fa fa-send"></i> 
								{lang('text_compose_mail')}
							</a>
						</div>
						<div class="col-md-4">

							<a tabindex="2" href="{base_url()}{log_user_type()}/mail/inbox" class="btn btn-default btn-block {if current_uri()=="mail/inbox" || current_uri()=="mail/reply-mail" || current_uri()=="mail/reply_mail"}active{/if}" >
								<i class="fa fa-envelope"></i> 
								{lang('text_inbox')}
								{if $unread_mail>0}({$unread_mail}){/if}
							</a>
						</div>
						<div class="col-md-4">
							<a tabindex="3" href="{base_url()}{log_user_type()}/mail/mail-sent" class="btn btn-default btn-block {if current_uri()=="mail/mail_sent"}active{/if}">
								<i class="fa fa-mail-forward"></i>  {lang('text_sent')}
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
