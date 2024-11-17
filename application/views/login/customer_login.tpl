{extends file="login/layout.tpl"}

{block name="body"} 
<style type="text/css">
    .card.bg-rose, .card .card-header-rose .card-icon, .card .card-header-rose .card-text, .card .card-header-rose:not(.card-header-icon):not(.card-header-text), .card.card-rotate.bg-rose .back, .card.card-rotate.bg-rose .front {
    background: linear-gradient(60deg,#13181f,#4b4f53);
}
</style>
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-8 ml-auto mr-auto">
        {form_open('', 'class="form form-horizontal form-simple"')}
        <div class="card card-login card-hidden">
            <div class="card-header card-header-rose text-center">
                <h4 class="card-title">Customer Login</h4>
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
                    <p class="text-right"><a href="{base_url('customer-forgot')}"> Forgot password</a></p>
                    {form_error('password')} 
                </span>
            </div>
            <div class="card-footer justify-content-center"> 
                <button type="submit" class="btn btn-black" name="login" value="login" style="background-color: #474a4e;border-color: #484c50;"><i class="fa fa-unlock"></i> {lang('button_login')}</button>
            </div>
   
        </div>
        {form_close()}
    </div>
</div>

{/block}