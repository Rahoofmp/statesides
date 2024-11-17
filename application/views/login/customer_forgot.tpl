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
                <h4 class="card-title">{lang('button_forgot')}</h4>
            </div>
            <div class="card-body ">
                <span class="bmd-form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="material-icons">email</i>
                            </span>
                        </div>
                        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="{lang('email')}" required autocomplete="off">
                    </div>
                    {form_error('email')}
                </span>
            </div>
            <div class="card-footer justify-content-center"> 
                <button type="submit" class="btn btn-rose btn-link btn-lg" name="forgot" value="forgot"><i class="fa fa-unlock"></i> {lang('button_forgot')}</button> 
            </div>
        </div>
        {form_close()}
    </div>
</div>

{/block}