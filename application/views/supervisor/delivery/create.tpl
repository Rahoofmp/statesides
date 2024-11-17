{extends file="layout/base.tpl"}
{block header}
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
{/block}
{block body}

<div class="row">
    <div class="col-sm-12">
        {form_open("",'class="form form-horizontal FormValidation" ' )} 
        <div class="card ">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">airport_shuttle</i>
                </div>
                <h4 class="card-title">{$title}</h4>
            </div>
            <div class="card-body  mt-3">


                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">chrome_reader_mode</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">

                                <select id="project_id" name="project_id" class="project_ajax form-control" >
                                </select>
                                {form_error('project_id')}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">grading</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">

                                <select id="driverinput6" name="driver_id" class="driver_ajax form-control" >

                                </select>
                                {form_error('driver_id')}
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">airport_shuttle</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">

                                <input type="text" name="vehicle_number" class="form-control" placeholder="Vehicle Number" autocomplete="Off" value="{set_value('vehicle_number')}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer text-right">
                <div class="form-check mr-auto"></div>

                <button class="btn btn-rose" type="submit" id="add_project" name="submit" value="add_project">
                    Create <i class="fa fa-arrow-circle-right"></i>
                </button>   

            </div>
        </div>
        {form_close()}
    </div>
</div>  
</div>  
{/block}
{block footer}
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>
<script type="text/javascript">

   $(document).ready(function(){ 

    $('.driver_ajax').select2({

        placeholder: 'Select a Driver',
        ajax: {
            url:'{base_url(log_user_type())}/autocomplete/driver_ajax',

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
    $('.project_ajax').select2({

        placeholder: 'Select a project',
        ajax: {
            url:'{base_url(log_user_type())}/autocomplete/project_ajax',

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
</script>

{/block}
