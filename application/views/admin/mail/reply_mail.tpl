{extends file="layout/base.tpl"}

{block name="body"}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title ">  {lang('text_mail_reply')}</h4></div>
                <div class="card-body">

                    <div class="box-solid">
                        <div class="box-body">
                            <div class="col-md-12 col-sm-12" >
                                {form_open('','name="reply" id="reply" class="smart-wizard form-horizontal" method="post"')} <div class="form-group">
                                <input type="text" class="form-control" id="user_id1" name="user_id1" readonly value="{$reply_user}"/>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject" id="subject" value=" Rep:{$reply_msg}" />{form_error('subject')}
                            </div>
                            <div class="form-group">
                                <textarea class="textarea" name='message' id='message' value="{set_value('message')}" placeholder="{lang('text_message_to_send')}" style="width: 100%" rows="10"></textarea>{form_error('message')}
                            </div>

                            <div class="box-footer clearfix">

                                <button class="btn btn-green" type="submit" id="send" value="{lang('text_send_message')}" name="send" tabindex="2">
                                    {lang('text_send_message')}</button>
                                </div>
                                {form_close()}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {/block}
