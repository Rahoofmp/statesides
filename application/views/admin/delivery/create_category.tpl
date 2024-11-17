{extends file="layout/base.tpl"}
{block header}
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
{/block}
{block body}

<div class="row">
    <div class="col-sm-12">
        {form_open_multipart("",'class="form form-horizontal FormValidation" ' )} 
        <div class="card ">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">list</i>
                </div>
                <h4 class="card-title">{$title}</h4>
            </div>
            <div class="card-body  mt-3">
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">code</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">

                                <input type="text" name="code" class="form-control" placeholder="Item Code" autocomplete="Off"{if $id} value="{$edit_category['code']}" {else} value="{$code}" {/if} readonly="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">category</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">

                                <input type="text" name="category_name" class="form-control" placeholder="Category Name" autocomplete="Off" {if $id} value="{$edit_category['category_name']}" {else} value="{set_value('category_name')}" {/if}>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12" id="edit_category_div" {if $edit_category['main_category']==0} style="display: none;" {/if}>
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">chrome_reader_mode</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">

                                <select id="main_category1" name="edit_category_id"  >
                                    {foreach $main_categories as $c}
                                    <option value="{$c.id}" {if $c.id==$edit_category['main_category']} selected="" {/if}>{$c.text}</option>
                                    {/foreach}

                                </select>
                                {form_error('edit_category_id')}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12"> 
                    <div class="togglebutton mt-3">
                        <label>
                            <input type="checkbox" name="set_main_category" id="set_main_category" value="on" {set_checkbox('set_main_category', 'on')} onchange="toggleCategory();"{if $id} {if $edit_category['main_category']==0}checked{/if} {/if}>
                            <span class="toggle"></span>
                            Set As Main Category 
                        </label>
                    </div>  

                </div>
                <div class="col-lg-12" id="main_category_div"{if $id} style="display: none;" {/if}>
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">chrome_reader_mode</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">

                                <select id="main_category" name="category_id" class="category_ajax form-control" style="width: 100%">
                                </select>
                                {form_error('category_id')}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12" id="order_div" {if $edit_category['sort_order']==0}  style="display: none;" {/if}>
                    <div class="input-group form-control-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="material-icons">chrome_reader_mode</i>
                            </span>
                        </div>
                        <div class="col-sm-11">
                            <div class="form-group">
                                <input type="text" name="sort_order" class="form-control" placeholder="Sort Order" autocomplete="Off" {if $id} value="{$edit_category['sort_order']}" {else} value="{set_value('sort_order')}" {/if}>
                                {form_error('sort_order')}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="card-footer text-right">
            <div class="form-check mr-auto"></div>
            {if $id}
            <button class="btn btn-rose" type="submit" id="add_project" name="submit" value="update_category">
                Update <i class="fa fa-arrow-circle-right"></i>
            </button>  
            {else}

            <button class="btn btn-rose" type="submit" id="add_project" name="submit" value="add_category">
                Create <i class="fa fa-arrow-circle-right"></i>
            </button>   
            {/if}

        </div>
        {form_close()}
    </div>
</div> 
{/block}
{block footer}
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url('plugins/autocomplete/filter.js')}"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script>
<script type="text/javascript">

    function toggleCategory() 
    {
        var e = $('#set_main_category')

        if($(e).prop('checked')) {
            if('{$id}'){ 
                $('#edit_category_div').hide();
                $('#edit_main_category').removeAttr('required');
            }

            $('#main_category_div').hide();
            $('#main_category').removeAttr('required');
            $('#order_div').show();

        } else {
            if('{$id}'){ 
                $('#main_category_div').hide();
                $('#main_category').removeAttr('required');
                $('#edit_category_div').show();
                $('#edit_main_category').attr('required','true');
            }
            else{ 
                $('#main_category_div').show();
                $('#main_category').attr('required','true');
            }
            $('#order_div').hide();

        }
    }

    $(document).ready(function(){ 

        toggleCategory();

        $('.category_ajax').select2({

            placeholder: 'Category Under ',
            ajax: {
                url:'{base_url()}admin/autocomplete/category_ajax',

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


</script>
{/block}
