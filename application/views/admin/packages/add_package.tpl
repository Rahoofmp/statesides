{extends file="layout/base.tpl"}
{block header}
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />

{/block}
{block body}

<div class="row">
    <div class="col-sm-12">
        {form_open_multipart("",'class="form form-horizontal FormValidation" ' )} 
        <div class="card ">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">inventory_2</i>
                </div>
                <h4 class="card-title">Create</h4>
            </div>
            <div class="card-body  mt-3">

                <div class="row">
                    <div class="col-sm-9">
                        <div class="col-sm-12">

                            <div class="input-group form-control-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">chrome_reader_mode</i>
                                    </span>
                                </div>
                                <div class="form-group col-sm-9">
                                    <select id="projectinput6" name="name" class="project_ajax form-control" >
                                        {if $details->project_id}
                                        
                                        <option value="{$details->project_id}">{$details->project_name}</option>
                                        {/if}

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-group form-control-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">grading</i>
                                    </span>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Package Name </label>
                                        <input type="text" class="form-control" id="package" name="package" required value="{$details->name}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-group form-control-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">place</i>
                                    </span>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Location</label>
                                        <input type="text" class="form-control" id="package_location" name="package_location" >
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">

                            <div class="input-group form-control-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">person</i>
                                    </span>
                                </div>
                                <div class="form-group col-sm-9">
                                    <select id="projectinput5" name="area_master" class="type_ajax form-control" >
                                        {if $details->type_id}
                                        
                                        <option value="{$details->type_id}">{$details->areamaster_name}</option>
                                        {/if}

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">

                            <div class="input-group form-control-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="material-icons">person</i>
                                    </span>
                                </div>
                                <div class="form-group col-sm-9">
                                    <select id="item_id" name="item" class="item_ajax form-control" >
                                        {if $details->item_id}
                                        
                                        <option value="{$details->item_id}">{$details->item_name}</option>
                                        {/if}

                                    </select>
                                </div>
                            </div>
                        </div>
                        {* <div class="col-sm-12">
                            <div class="input-group form-control-lg">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">person</i>
                                    </span>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Type/ Area Master</label>
                                        <input type="checkbox" class="form-control" autocomplete="off" id="area_master" name="area_master" value="area_master">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="input-group form-control-lg" id="ar_name" style="display: none;">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="material-icons">spa</i>
                                    </span>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group">
                                        <label for="exampleInput1" class="bmd-label-floating">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" >
                                    </div>
                                </div>
                            </div>
                        </div> *}

                    </div>

                    <div class="col-sm-3">

                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="fileinput-new thumbnail img-circle">
                                <img src="{assets_url('images/package_pic/no-image.png')}" alt="package-image" class="p-2">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail img-circle" ></div>
                            <div>
                                <span class="btn btn-round btn-info btn-file">
                                    <span class="fileinput-new">{lang('upload_photo')}</span>
                                    <span class="fileinput-exists">{lang('change')}</span>
                                    <input type="file" name="userfile" />
                                </span>
                                <br />
                                <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> {lang('remove')}</a>
                            </div>
                        </div>
                    </div>
                </div> 
                <hr>
                <div class="card-footer text-right">
                    <div class="form-check mr-auto"></div>
                    {if $id}
                    <button class="btn btn-rose" type="submit" id="add_project" name="update" value="add_project">
                        Update <i class="fa fa-arrow-circle-right"></i>
                    </button>
                    {else}
                    <button class="btn btn-rose" type="submit" id="add_project" name="submit" value="add_project">
                        Create <i class="fa fa-arrow-circle-right"></i>
                    </button>   
                    {/if}
                </div>
            </div>
        </div>
        {form_close()}

    </div>  
</div>  
{/block}
{block footer}
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>


<script type="text/javascript">

   $(document).ready(function(){ 

    $('.project_ajax').select2({

        placeholder: 'Select a project',
        ajax: {
            url:'{base_url()}admin/autocomplete/project_ajax',

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

    $('.item_ajax').select2({

        placeholder: 'Select a Item',
        ajax: {
            url:'{base_url()}{log_user_type()}/autocomplete/item_with_name_ajax',
            data: function (params) {

                var query = {
                    q: params.term,
                    with_name: true,
                    type: 'finished_item'
                }
                return query;
            },
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

   $('.type_ajax').select2({

    placeholder: 'Select a Type/Area master',
    ajax: {
        url:'{base_url()}admin/autocomplete/type_ajax',

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

});



   $(document).ready(function(){
    $('#area_master').click(function(){
        if($(this).prop("checked") == true){
            $("#ar_name").show();
        }
        else if($(this).prop("checked") == false){
            $("#ar_name").hide();
        }
    }).change();
});


</script>


</script>
{/block}
