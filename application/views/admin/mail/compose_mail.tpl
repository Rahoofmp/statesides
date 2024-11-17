{extends file="layout/base.tpl"}

{include file="{log_user_type()}/mail/header.tpl"  name=""}

{block name="header"} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/autocomplete/style.css')}">
{/block}


{block name="body"}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title ">  {lang('text_compose_mail')}</h4>
            </div>
            <div class="card-body">

                <div class="box-solid">
                    <div class="box-body">
                        <div class="col-md-12 col-sm-12" >
                            {form_open('','role="form"  class="smart-wizard form-horizontal" method="post" name="compose" id="compose"')}

                            <div class="form-group mb-5">
                                <select tabindex="1" class="form-control" id="status" name="status" onchange="change_status(this.value)">
                                    <option value="single" {if $mail_status=="single"} selected {/if}>{lang('text_single_user')}</option>
                                    <option value="all"  {if $mail_status=="all"} selected {/if}>{lang('text_all_users')}</option>

                                </select>
                            </div>

                            <div class="form-group" id="user_div" style="display: block;">
                                <input tabindex="2" type='text' class='form-control' name='user_id' id='user_id' placeholder='{lang('text_single_user')}' onClick="autoComplete(this, 'admin', 'autocomplete/user_filter')" autocomplete="Off" autocomplete="Off" data-lang="{lang('text_single_user')}"/>{form_error('user_id')}
                            </div>

                            <div class="form-group">
                                <input tabindex="3" type="text" class="form-control" name="subject" id="subject" placeholder="{lang('text_subject')}"  value="{set_value('subject')}" data-lang="{lang('text_subject')}" autocomplete="Off" maxlength="40" />{form_error('subject')}
                            </div>
                            <div class="form-group">                                                
                                <textarea tabindex="4"  data-lang="{lang('text_message')}" class="form-control textarea" name='message' id='message' placeholder="{lang('text_message')}" value="{set_value('message')}" style="width: 100%" rows="10"></textarea>{form_error('message')}
                            </div>

                            <div class="box-footer clearfix">
                                <button class="btn btn-green" type="submit" name="send"  id="send" value="{lang('text_send_message')}" tabindex="">{lang('text_send_message')}</button>
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


{block name="footer"} 
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('js/confirm.js')}"></script>
{/block} 

