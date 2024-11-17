{extends file="login/layout.tpl"}

{block name="body"} 

<div class="row">
    <div class="col-lg-6 col-md-6 col-sm-8 ml-auto mr-auto">
        <div class="card card-pricing card-raised">
            <div class="card-body">
                <h2 class="card-category">{lang('success')}</h2>
                <div class="card-icon icon-success">
                    <i class="material-icons">done_all</i>
                </div>
                <h3 class="card-title">{$username}</h3>
                <h4>Welcome to {$site_details['name']} </h4>
                <p class="card-description">
                    {lang("text_para_2")}

             </p>
             <div class="col-lg-10 col-md-8 mr-auto ml-auto"> 
                <table class="table table-bordered table-hover">
                    <tr>
                        <td> {lang('username')} </td>
                        <td> {$preview_details['username']} </td>
                    </tr>
                    <tr>
                        <td> {lang('firstname')} </td>
                        <td> {$preview_details['first_name']} </td>
                    </tr>
                    <tr>
                        <td> {lang('secondname')} </td>
                        <td> {$preview_details['second_name']} </td> 
                    </tr>
                    <tr>
                        <td> {lang('email')} </td>
                        <td> {$preview_details['email']} </td> 
                    </tr>
                </table>
                <a href="{base_url('login')}" class="btn btn-rose btn-round">{lang('login')}</a>
            </div>
        </div>
    </div>
</div>
</div>
{/block}