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

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Add Sales Quotation</h4>
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
                                    Quotation no
                                </label>
                                <input type="text" name="code" class="form-control" value="{$code}" readonly="">
                                {form_error("code")}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="row"> 
                        <div class="col-lg-6">
                            <div class="input-group form-control-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">date_range</i>
                                    </span>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">
                                            Quotation date
                                        </label>
                                        <input type="text" name="date" class="form-control datepicker" value="{set_value('date')}">
                                        {form_error("date")}
                                    </div>
                                </div>
                            </div>
                        </div> 
                        <div class="col-lg-6">
                            <div class="input-group form-control-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">person_add</i>
                                    </span>
                                </div>
                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Subject</label>
                                        <input type="text" id="subject" class="form-control " name="subject" value="{set_value('subject')}" required autocomplete="off">
                                        {form_error('subject')}
                                    </div> 
                                </div>
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
                                <label class="bmd-label-floating"> Sales Person </label>
                                <select id="salesperson" name="salesperson" class="salesman_ajax form-control">
                                    {if $post_arr['salesperson']}
                                    <option value="{$post_arr['salesperson']}">{$post_arr['salesman_name']}</option>
                                    {/if} 
                                </select>
                                {form_error("salesperson")}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 mt-4">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">schedule</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <label class="bmd-label-floating"> Status </label>
                                <select id="status" name="status" class="selectpicker" data-style="select-with-transition" style="width:100%" title="Status">

                                    <option value="draft">Draft</option>
                                    <option value="submitted_for_approval">submitted for approval </option>
                                    <option value="approved">Approved </option>
                                    <option value="sent">sent </option>
                                    <option value="lost_or_win">lost or win  </option>

                                </select> 
                                {form_error('status')}
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
                                <select id="payment_terms_id" name="payment_terms_id" class="form-control">
                                </select> 
                                {form_error('payment_terms_id')}

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
                                {* <label class="bmd-label-floating"> Normal Terms & Conditions  </label> *}
                                <select id="normal_terms_id" name="normal_terms_id" class="form-control">
                                </select> 
                                {form_error('normal_terms_id')}

                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <div class="card-footer text-right">
                <div class="form-check mr-auto">
                </div>

                <button class="btn btn-primary pull-right" type="submit" id="submit" name="submit" value="submit">
                    Add Sales <i class="fa fa-arrow-circle-right"></i>
                </button>

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
    $(document).ready(function() { 

        md.initFormExtendedDatetimepickers();
        // $('.vat').select2();
        // var payment_terms_id = $('#payment_terms_id'); 
        // payment_terms_id.select2({
        //     dropdownAutoWidth: !0,
        //     width: "100%"
        // });

        // openTerms($("input[name='tc_type']:checked").val())     
        // $('input[name="tc_type"]').change(function(){
        //     openTerms($(this).val())
        // });

        // function openTerms(tc_type){ 

        // }
        $('#payment_terms_id').select2({

            placeholder: 'Select Payment T&C',
            ajax: {
                url:'{base_url()}admin/autocomplete/terms_conditions_ajax',

                type: 'post',
                data: { 
                    tc_type: 'payment',
                    type: 'ajax'
                },
                dataType: 'json',
                delay:250,
                processResults: function(data) {
                    return {
                        results: data
                    };
                }
            },
        }); 
        $('#normal_terms_id').select2({

            placeholder: 'Select Normal T&C',
            ajax: {
                url:'{base_url()}admin/autocomplete/terms_conditions_ajax',

                type: 'post',
                data: { 
                    tc_type: 'normal',
                    type: 'ajax'
                },
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

        // $(window).keydown(function(event){
        //     if(event.keyCode == 13) {
        //         event.preventDefault();
        //         addRow(); 
        //         return false;
        //     }
        // });

        // var table = $('#example').DataTable({ searching: false, paging: false, info: false,responsive: true});
        // var counter = 1;

        // $('#example').on('click', '.remove', function() {

        //     var dept_id=$(this).attr('data-dept');
        //     var row = $(this).parents('tr');

        //     if ($(row).hasClass('child')) {
        //         table.row($(row).prev('tr')).remove().draw();
        //     } else {
        //         table
        //         .row($(this).parents('tr'))
        //         .remove()
        //         .draw();
        //     }
        //     $("#department_id option[value*='"+dept_id+"']").prop('disabled',false);
        // });


        // function addRow () {
        //     var validFrom = true;
        //     $(".product-items :input").each(function(){
        //         var input = $(this); 
        //         if(input.val() == ''){
        //             input.focus();
        //             validFrom = false;
        //             return false;
        //         }
        //     });

        //     if(validFrom == true){
        //         var dept=$('#department_id option:selected').val();
        //         if (dept=='') {
        //             validFrom = false;
        //             return false;

        //         }
        //         table.row.add( [
        //             counter,
        //             $('#department_id option:selected').text(),
        //             $('#short_description').val(),
        //             $('#order_description').val(),
        //             $('#estimated_working_hrs').val(),

        //             '<button type="button" class="btn btn-danger btn-sm remove" data-dept="'+dept+'"><i class="material-icons">delete</i></button>'
        //             ] ).draw( false ).node();
        //         counter++;
        //         $('.product-items input').val(''); 
        //         $('.product-items select').val(''); 
        //         $('.product-items textarea').val(''); 
        //         $('.product-items #department_id').focus();
        //         $("#department_id option[value*='"+dept+"']").prop('disabled',true);
        //     }

        // } ;



        // $("#qty").keypress(function (e) {
        //     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //         return false;
        //     }
        // });

    } );

</script>
{/block}
