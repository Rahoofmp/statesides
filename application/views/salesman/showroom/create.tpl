{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />

{/block}

{block body}
<style type="text/css">
    select.form-control:not([size]):not([multiple]) {
        height: calc(4.4375rem + 2px);
    }

</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Add</h4>
            </div>
            {form_open('','id="file_form" name="file_form" class="form-add-project ValidateForm" enctype="multipart/form-data"')}
            <div class="card-body">
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">grading</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <label class="bmd-label-floating">
                                    Minutes no
                                </label>
                                <input type="text" name="code" class="form-control" value="{$code}" readonly="">
                                {form_error("code")}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">person</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <label class="bmd-label-floating"> Customer Name </label>

                                <select id="customer_name" name="customer_name" class="customer_ajax form-control">
                                 {if $id || $smarty.post.customer_name}
                                 <option value="{$post_arr['customer_name']}" selected>{$post_arr['customer_name']}</option>
                                 {/if}
                             </select> 
                             {form_error("customer_name")} 
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-lg-12">
                <div class="input-group form-control-lg">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="material-icons">person</i>
                        </span>
                    </div>
                    <div class="col-sm-11">
                        <div class="form-group">
                           <label class="bmd-label-floating"> Meeting Attendee </label>
                           <select type="text" id="user_name" class="form-control user_ajax" name="user_name[]" multiple>
                               {if $id || $smarty.post.user_name}
                               {for $i=1; $i<{$post_arr['count']}; $i++}
                               <option value="{$post_arr['user_name'][{$i}]}" selected>{$post_arr['user_name'][{$i}]}</option>
                               {/for}
                               {/if}
                           </select>
                           {form_error("user_name")} 
                       </div>
                   </div>
               </div>
           </div>
       </div>
       <div class="card-footer text-right">
        <div class="form-check mr-auto">
        </div>
        <button class="btn btn-primary pull-right" type="submit" id="submit" name="submit" value="submit">
            Add<i class="fa fa-arrow-circle-right"></i>
        </button>
    </div>
</div>
{form_close()}
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

<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script>
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>

<script>
 $(document).on("change", '.files_upl',function() { 
    preview_image($(this));
});
 $(document).on("change", '#upload_file',function() { 
    var clone = $( ".cloningDiv" ).clone();
    var parent = $(this).parent().parent();
    parent.attr('class', 'cloningDiv-'+$('.files').length); 
    parent.addClass('files');
        // parent.closest('.files').find('.image_preview').find('img').attr('src', '{assets_url('images/items/no-image.png')}');
        // console.log(parent.find('input').val(''));
        // parent.find('input').val('')
        if(clone.insertBefore( ".files:first" )){
            $(this).attr('id', 'upload_file'+$('.files').length)
            $(this).attr('class', 'files_upl');
            preview_image($(this))
        }
    });
 function preview_image(e) 
 {
    console.log(event.target.files[0])

    var total_file=$('.files').length;
    for(var i=0;i<total_file;i++)
    {
        $(e).closest('.files').find('.image_preview').html("<div class='fileinput-new thumbnail m-1'><img class='p-2' src='"+URL.createObjectURL(event.target.files[i])+"' width='100' height='100'></div>");
    }
}
$(document).ready(function() { 

    md.initFormExtendedDatetimepickers();

    $('.user_ajax').select2({

        placeholder: 'Select a Designer',
        ajax: {
            url:'{base_url()}salesman/autocomplete/user_ajax',

            type: 'post',
            dataType: 'json',
            delay:250,
            processResults: function(data) {
                return {
                    results: data
                };
            }
        },

    });  $('.sales_ajax').select2({

        placeholder: 'Select a Salesman',
        ajax: {
            url:'{base_url()}salesman/autocomplete/sales_ajax',

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
        url:'{base_url()}salesman/autocomplete/salesman_by_customer_ajax',
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
{/block}
