{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}"> 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
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
    <span id="text_cancelled">{lang('text_cancelled')}</span>
    <span id="text_news_safe">{lang('text_news_safe')}</span>
    <span id="text_you_want_edit_news">{lang('text_you_want_edit_news')}</span>
    <span id="text_yes_edit_it">{lang('text_yes_edit_it')}</span>
    <span id="error_the_fieldid_field_is_required">{lang('error_the_fieldid_field_is_required')}</span>
    <span id="error_atleast_number">{lang('error_atleast_number')}</span>
    <span id="error_enter_greater_number">{lang('error_enter_greater_number')}</span>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">{lang('text_details')}</h4></div>
                <div class="card-body">
                    {form_open('','id="file_form" name="file_form" class="form-login" enctype="multipart/form-data"')}
                    <div class="form-group">
                        <label class="bmd-label-floating">
                            {lang('training_file_title')}
                        </label>
                        <input type="text" class="form-control" id="doc_title" name="doc_title" value="{set_value('doc_title')}" required="true">
                        {form_error("doc_title")}
                    </div>
                    <div class="form-group">
                        <label class="bmd-label-floating">
                            {lang('description')}
                        </label>
                        <textarea class="form-control" id="doc_desc" name="doc_desc" required="true">{set_value('doc_desc')}</textarea>
                        {form_error("doc_desc")}
                    </div>

                    <div>
                        <label class="bmd-label-floating">
                            {lang('text_efile')}
                        </label>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-preview fileupload-exists thumbnail col-sm-12"></div>
                            <div class="user-edit-image-buttons">
                                <span class="btn btn-azure btn-file"><span class="fileupload-new"><i class="fa fa-picture"></i> {lang('select_training_tool')}</span><span class="fileupload-exists"><i class="fa fa-picture"></i> {lang('change')}</span>
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
                        {lang('text_sort_order')}
                    </label>
                    <input type="text" class="form-control" id="sort_order" name="sort_order" value="{set_value('sort_order')}" number="true" required="true" >

                </div>

                <div class="form-group">
                    <button class="btn btn-primary pull-right" type="submit" id="efile_upload" name="efile_upload" value="efile_upload">
                        {lang('text_uplaod')} <i class="fa fa-arrow-circle-right"></i>
                    </button>
                </div>
                {form_close()}

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title ">  {lang('text_available_efile')}</h4>
            </div>
            <div class="card-body">
                {if count($efile_details) > 0}

                <table class="table table-hover" id="sample-table-1">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>{lang('text_efile_title')}</th>
                            <th class="hidden-xs">{lang('text_efile_description')}</th>
                            <th>{lang('text_efile')}</th>
                            <th>{lang('text_added_date')}</th>
                            <th class="hidden-xs">{lang('text_sort_order')}
                            </th>
                            <th>{lang('text_actions')}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $efile_details as $v} 
                        {$i=$i+1}
                        <tr>
                            <td class="center">{$i}</td>
                            <td class="hidden-xs">{$v.doc_title}</td>
                            <td>{$v.doc_desc}</td>
                            <td class="hidden-xs">
                                <a href="{assets_url()}uploads/Training_tools/{$v.doc_file_name}" class="btn btn-xs btn-green tooltips" data-placement="top" data-original-title="{lang('text_view')}"  target="blank" ><i class="material-icons">share</i></a>
                            </td>
                            <td>{$v.uploaded_date}</td>
                            <td class="hidden-xs">{$v.sort_order}</td>
                            <td class="center">
                                <div class="visible-md visible-lg hidden-sm hidden-xs">
                                    <a  href="javascript:inactivate_efile({$v.id})" class="btn btn-xs btn-red tooltips" data-placement="top" data-original-title="{lang('text_deactivate')}"><i class="material-icons">close</i></a>

                                </div>
                            </td>
                        </tr>
                        {/foreach}

                    </tbody>
                </table>
                {else}
                <div class="card-body">
                    <p>
                        <h4 class="text-center"> 
                            <i class="fa fa-exclamation"> {lang('text_no_transfer_details_found')}</i>
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

{/block}
