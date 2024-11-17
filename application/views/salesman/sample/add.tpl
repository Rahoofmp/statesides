{extends file="layout/base.tpl"}
{block header}
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/jquery-ui.min.css')}" rel="stylesheet" />
<link href="{assets_url('plugins/autocomplete/style.css')}" rel="stylesheet" /> 
{/block}
{block body}

<div class="row">
    <div class="col-sm-12">
        {form_open_multipart("",'class="form form-horizontal FormValidation"' )} 
        <div class="card ">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">list</i>
                </div>
                <h4 class="card-title">{$title}</h4>
            </div>
            <div class="card-body  mt-3">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="input-group form-control-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">code</i>
                                </span>
                            </div>
                            <div class="col-sm-11">
                                <div class="form-group">

                                    <input type="text" name="code" class="form-control"  {if $id} value="{$edit_item['code']}"{else} value="{$code}" {/if} readonly="">
                                    {form_error("code")}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12"> 
                        <div class="input-group form-control-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">dns</i></span>
                            </div>
                            <div class="col-sm-11">
                                <div class="form-group">

                                    <input type="text" name="name" class="form-control" placeholder="Name" autocomplete="Off" {if $id} value="{$edit_item['name']}" {else}  value="{set_value('name')}" {/if}>
                                    {form_error('name')}
                                </div>
                            </div>
                        </div>
                    </div>
                   <!--  <div class="col-lg-12"> 
                        <div class="input-group form-control-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">note</i></span>
                            </div>
                            <div class="col-sm-11">
                                <div class="form-group">
                                    <textarea class="form-control" id="note" name="note">{$edit_item['note']}</textarea> 
                                    {form_error('note')}
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="input-group form-control-lg">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="material-icons">chrome_reader_mode</i>
                                </span>
                            </div>
                            <div class="form-group col-sm-10">
                                <select id="main_category" name="main_category" class="category_ajax form-control">
                                    {if $id || $smarty.post.main_category}
                                    <option value="{$edit_item['category']}">{$edit_item['category_name']}</option>
                                    {/if}
                                </select>
                                {form_error('main_category')}
                            </div>
                        </div>
                    </div> 
                    <div class="col-lg-12"> 
                        <div class="row"> 
                            <div class="col-lg-6">
                                <div class="input-group form-control-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">attach_money</i>
                                        </span>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">

                                            <input type="text" name="cost" class="form-control" placeholder="Cost" autocomplete="Off" {if $id} value="{$edit_item['cost']}" {else}  value="{set_value('cost')}" {/if}>
                                            {form_error('cost')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group form-control-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">attach_money</i>
                                        </span>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">

                                            <input type="text" name="price" class="form-control" placeholder="Selling Price" autocomplete="Off"  {if $id} value="{$edit_item['price']}" {else} value="{set_value('price')}"{/if}>
                                            {form_error('price')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"> 
                        <div class="row"> 
                            <div class="col-lg-6">
                                <div class="input-group form-control-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">star</i>
                                        </span>

                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">

                                            <input type="text" name="brand" class="form-control" placeholder="Brand" autocomplete="Off"  {if $id} value="{$edit_item['brand']}" {else} value="{set_value('brand')}" {/if}>
                                            {form_error('brand')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group form-control-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">circle</i>
                                        </span>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">

                                            <input type="text" name="origin" class="form-control" placeholder="Origin" autocomplete="Off"  {if $id} value="{$edit_item['origin']}" {else} value="{set_value('origin')}" {/if}>
                                            {form_error('origin')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"> 
                        <div class="row"> 
                            <div class="col-lg-6">
                                <div class="input-group form-control-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">business</i>
                                        </span>

                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">

                                            <input type="text" name="paint_code" class="form-control" placeholder="Paint Code" autocomplete="Off"  {if $id} value="{$edit_item['paint_code']}" {else} value="{set_value('paint_code')}" {/if}>
                                            {form_error('paint_code')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group form-control-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">rectangle</i>
                                        </span>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">

                                            <input type="text" name="size" class="form-control" placeholder="Size" autocomplete="Off"  {if $id} value="{$edit_item['size']}" {else} value="{set_value('size')}" {/if}>
                                            {form_error('size')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12"> 
                        <div class="row"> 
                            <div class="col-lg-6">
                                <div class="input-group form-control-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">list</i>
                                        </span>

                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">

                                            <input type="text" name="type" class="form-control" placeholder="Type Of Material" autocomplete="Off"  {if $id} value="{$edit_item['type']}" {else} value="{set_value('type')}" {/if}>
                                            {form_error('type')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-group form-control-lg">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="material-icons">grade</i>
                                        </span>
                                    </div>
                                    <div class="col-sm-10">
                                        <div class="form-group">

                                            <input type="text" name="grade" class="form-control" placeholder="Grade" autocomplete="Off"  {if $id} value="{$edit_item['grade']}" {else} value="{set_value('grade')}" {/if}>
                                            {form_error('grade')}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                  {if $edit_item['item_images']}
                    <div class="row" class="container">
                        {foreach $edit_item['item_images'] as $key=>$images }
                        <!-- {print_r($edit_item.id) } -->
                        <img class='p-2' src='{assets_url('images/sample/')}{$images->image}' width="100" height="100" >
                       {*  {if $key!=0} *}
                        <input type="checkbox" name="images[]" value="{$images->id}"/>
                        {* {/if} *}
                        <input type="text" name="img_id" value="{$edit_item.id}" hidden>

                        {/foreach}
                        <button class="btn btn-rose" type="submit" name="delete" value="delete_images">
                            Delete Images <i class="fa fa-arrow-circle-right"></i>
                        </button> 
                    </div>
                    {/if}
                    <div class="col-lg-12">
                        <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                            <div class="cloningDiv files">
                                <span class="btn btn-round btn-info btn-file dropzone">
                                    <span class="fileinput-new">Select Images</span>
                                    <span class="fileinput-exists">{lang('change')}</span>
                                    <input type="file" id="upload_file" name="upload_file[]"   multiple=""  />
                                    {form_error('upload_file')}
                                </span>

                                <div class="form-group fileinput fileinput-new">
                                    <div class="image_preview" >
                                        <div class='fileinput-new thumbnail m-1'>
                                            <img class='p-2' src='{assets_url('images/sample/no-image.png')}' width="100" height="100">
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-12"> 
                    </div> 
                </div>
                
                <div class="card-footer text-right">
                    <div class="form-check mr-auto"></div>
                    {if $id}
                    <button class="btn btn-rose" type="submit" id="update_item" name="submit" value="update_item">
                        Update <i class="fa fa-arrow-circle-right"></i>
                    </button>
                    {else}
                    <button class="btn btn-rose" type="submit" id="add_item" name="submit" value="add_item">
                        Create <i class="fa fa-arrow-circle-right"></i>
                    </button>  
                    {/if} 

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
{* <script src="{assets_url('js/dropzone.js')}"></script> *}
<script src="{assets_url('js/ckeditor.js') }"></script>  

<script type="text/javascript"> 

    var mySpec; 
    var editor = ClassicEditor  
    .create( document.querySelector( '#note' ), {   
    } ) 
    .then( editor => {  
        window.editor = editor; 
        mySpec = editor;    
    } ) 
    .catch( err => {    
        console.error( err.stack ); 
    } );

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


    $(document).on("click", "body #example tbody #delete_image" , function() {

        var id = $(this).attr('data-id');            

        if(id) {  
            $.confirm({
                title: 'Confirm!',
                content: 'Are You Sure to delete?? ',
                buttons: {
                    confirm: function () {

                        document.location.href = '{base_url(log_user_type())}' +"/delivery/delete-category/"+id; 
                    },
                    cancel: function () {
                        $.alert('Canceled!');
                    },

                }
            });

        }
    } );


    $(document).on("change", '.files_upl',function() { 
        preview_image($(this));
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

    $(document).ready(function(){ 

        var subCategorySelect=$('#sub_category');

        $('.sub_category_ajax').select2({

            placeholder: 'Select a Sub-Category',
            ajax: {
                url:'{base_url()}admin/item/sub_category_ajax',
                data: function (params) {

                    var main_category = $( "select#main_category option:checked" ).val() ;

                    var query = {
                        main_category: main_category,
                        type: 'public'
                    }
                    return query;
                },
                type:'post',
                dataType: 'json',
                delay: 250,
                processResults: function (data) { 
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });             



        $('.category_ajax').select2({

            placeholder: 'Select a Category',
            ajax: {
                url:'{base_url()}salesman/autocomplete/category_ajax',

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
