{extends file="login/layout.tpl"}

{block name="body"} 

<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
        {form_open('', 'class="form form-horizontal form-simple"')}
        <div class="card card-login card-hidden">
            <div class="card-header card-header-rose text-center">
                <h4 class="card-title">{lang('button_login')}</h4>
            </div>
            <div class="card-body ">
                <span class="bmd-form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">account_box</i>
                            </span>
                        </div>
                        <input type="text" class="form-control form-control-lg required" id="user-name" name="user_name" placeholder="{lang('username')}" required autocomplete="off">
                    </div>
                    {form_error('user_name')}
                </span>
                <span class="bmd-form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">lock</i>
                            </span>
                        </div>
                        <input type="password" class="form-control form-control-lg" id="password" name="password" placeholder="{lang('password')}" required >
                    </div>
                    <p class="text-right"><a href="{base_url('forgot')}"> Forgot password</a></p>
                    {form_error('password')} 
                </span>
            </div>
            <div class="card-footer justify-content-center"> 
                <button type="submit" class="btn btn-rose" name="login" value="login"><i class="fa fa-unlock"></i> {lang('button_login')}</button>
            </div>
   
        </div>
        {form_close()}
    </div>
</div>

{/block}