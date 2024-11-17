{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />

{/block}

{block body}
<style type="text/css">
    select.form-control:not([size]):not([multiple]) {
        height: calc(4.4375rem + 2px);
    }

</style>
{if $id}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Edit Payment Terms & Conditions</h4>
            </div>
            {form_open('','id="file_form" name="file_form" class="form-add-project ValidateForm" enctype="multipart/form-data"')}
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">person</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <label class="bmd-label-floating">
                                    Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name"required="" autocomplete="Off" value="{$details['name']}">
                                {form_error("name")}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">gavel</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <div class="col-sm-10 checkbox">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" value="payment"  name="tc_type" {if $details['tc_type'] == 'payment'}checked{/if} > Payment terms and condition
                                            <span class="circle">  <span class="check"></span> </span>
                                        </label>
                                    </div> 
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" value="normal" name="tc_type" {if $details['tc_type'] == 'normal'}checked{/if} >  Normal terms and condition
                                            <span class="circle">  <span class="check"></span> </span>
                                        </label>
                                    </div> 
                                    {form_error("tc_type")}  
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">gavel</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <label class="bmd-label-floating"> Payment Terms & Conditions  </label>
                                <textarea type="text" class="form-control" id="terms_conditions" name="terms_conditions" required="">{$details['terms_conditions']}</textarea>
                                {form_error('terms_conditions')}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right"> 
                <button class="btn btn-primary pull-right" type="submit" id="submit" name="update" value="submit">
                    Edit T&C <i class="fa fa-arrow-circle-right"></i>
                </button>
            </div>
            {form_close()}
        </div>
    </div>
</div>
{else}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Add Payment Terms & Conditions</h4>
            </div>
            {form_open('','id="file_form" name="file_form" class="form-add-project ValidateForm" enctype="multipart/form-data"')}
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">person</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <label class="bmd-label-floating">
                                    Name
                                </label>
                                <input type="text" class="form-control" id="name" name="name"required="" autocomplete="Off">
                                {form_error("name")}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">gavel</i> </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <div class="col-sm-10 checkbox">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" value="payment"  name="tc_type" checked> Payment terms and condition
                                            <span class="circle">  <span class="check"></span> </span>

                                        </label>
                                    </div> 
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="radio" value="normal" name="tc_type" >  Normal terms and condition
                                            <span class="circle">  <span class="check"></span> </span>
                                        </label>
                                    </div> 
                                    {form_error("tc_type")}  
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">gavel</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <label class="bmd-label-floating"> Payment Terms & Conditions  </label>
                                <textarea type="text" class="form-control" id="terms_conditions" name="terms_conditions" required=""> </textarea>

                                {form_error('terms_conditions')}

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-footer text-right">
                <div class="form-check mr-auto">
                </div>

                <button class="btn btn-primary pull-right" type="submit" id="submit" name="submit" value="submit">
                    Add Conditions <i class="fa fa-arrow-circle-right"></i>
                </button>

            </div>

            {form_close()}

        </div>
    </div>
    {if $details}
    <div class="col-md-12">
        <div class="card"> 
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">{lang('details')}</h4>
            </div> 
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="bg-light text-warning">
                            <tr>
                                <th>#</th>   
                                <th>Name</th>  
                                <th>T&C</th>  
                                <th>Created on</th>  
                                <th class="text-center">Action</th>   
                            </tr>
                        </thead>
                        <tbody> 
                            {foreach from=$details item=v}

                            <tr>
                                <td>{counter}</td>
                                <td>{$v.name}
                                    <span class="clearfix"></span>
                                    <span class="badge badge-info">{$v.tc_type}</span>
                                </td>  
                                <td>{$v.terms_conditions}</td>  
                                <td>{$v.date|date_format:"%d-%m-%Y"}</td>

                                <td class="td-actions text-center"> 

                                    <a href="{base_url('admin/sales/create-tc/edit/')}{$v.enc_id}" rel="tooltip" class="btn btn-info btn-link" title="Edit">
                                        <i class="material-icons">edit</i>
                                    </a> 

                                </td>  
                            </tr>
                            {/foreach}  
                        </tbody>
                    </table>
                </div>
            </div> 
        </div>
        <div class="d-flex justify-content-center">  
            <ul class="pagination start-links"></ul> 
        </div>
    </div>
    {/if}
</div>

{/if}


{/block}

{block footer} 
<script src="{assets_url('bootv4/js/plugins/jquery.validate.min.js')}"></script>

<script src="{assets_url('js/ui-notifications.js')}"></script>
<script src="{assets_url('plugins/sweetalert/lib/sweet-alert.min.js')}"></script>
<script src="{assets_url('js/confirm.js')}"></script>
<script src="{assets_url('js/page-js/settings.js')}"></script>
<script src="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.js')}"></script>

<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script>
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

<script>
    $(document).ready(function() { 
        md.initFormExtendedDatetimepickers();
        $('.vat').select2();

        $('.customer_ajax').select2({

            placeholder: 'Select a Customer',
            ajax: {
                url:'{base_url()}admin/autocomplete/customer_ajax',

                type: 'post',
                dataType: 'json',
                delay:250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
        });  
        $('.customer_ajax').on("select2:close", function(e) {
            var customer_id = $("select.customer_ajax option:checked" ).val();

            $.ajax({
                url:'{base_url()}admin/autocomplete/salesman_by_customer_ajax',
                cache: true,
                data: { 
                    customer_id: customer_id,
                    type: 'ajax'
                },
                type: 'post',
                dataType: 'json',
                success: function(html){
                    $('#salesperson').find('option').remove().end();

                    if( html.length != 0){
                        daySelect = document.getElementById('salesperson');
                        daySelect.options[daySelect.options.length] = new Option(html.user_name, html.user_id); 
                    }
                }
            }); 
        });



    } );

</script>
<script src="{assets_url('js/ckeditor.js') }"></script>  

<script>
    ClassicEditor
    .create( document.querySelector( '#terms_conditions' ), {
    } )
    .then( editor => {
        window.editor = editor;
    } )
    .catch( err => {
        console.error( err.stack );
    } );
    $(document).on('change', '.dimension_checkbox', function() {
       if($(this).prop("checked") == true){
        $(this).parent().next().find('.form-control').removeClass('hide')
    }else{
        $(this).parent().next().find('.form-control').addClass('hide')
    } 
});
</script>

{/block}
