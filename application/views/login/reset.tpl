{extends file="login/layout.tpl"}

{block name="body"} 

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
        {form_open('', 'class="form form-horizontal form-simple"')}
        <div class="card card-login card-hidden">
            <div class="card-header card-header-rose text-center">
                <h4 class="card-title">{lang('button_reset')}</h4>
            </div>
            <div class="card-body ">
                <span class="bmd-form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">lock</i>
                            </span>
                        </div>
                        <input type="hidden" id="keyword_encode" name="keyword_encode" value="{$keyword_encode}" required >
                        <input type="password" class="form-control form-control-lg required" id="new_password" name="new_password" placeholder="{lang('new_password')}" required autocomplete="off">
                    </div>
                    {form_error('new_password')}
                </span>
                <span class="bmd-form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">lock</i>
                            </span>
                        </div>
                        <input type="password" class="form-control form-control-lg" id="confirm_password" name="confirm_password" placeholder="{lang('confirm_password')}" required >
                    </div>
                    {form_error('confirm_password')}
                </span>
            </div>
            <div class="card-footer justify-content-center"> 
                <button type="submit" class="btn btn-rose btn-link btn-lg" name="reset_password" value="reset_password"><i class="fa fa-unlock"></i> {lang('button_reset')}</button> 
            </div>
        </div>
        {form_close()}
    </div>
</div>

{/block}