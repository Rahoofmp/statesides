{extends file="layout/base.tpl"}

{block header} 
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/sweetalert/lib/sweet-alert.css')}">
<link rel="stylesheet" type="text/css" href="{assets_url('plugins/bootstrap-fileupload/bootstrap-fileupload.min.css')}">
<link href="{assets_url()}plugins/select2/css/select2.min.css" rel="stylesheet" />

{/block}

{block body}
<div class="row">
    <div class="col-md-12">
        <div class="card"> 
            <div class="card-header card-header-rose card-header-text">
                <div class="card-icon">
                    <i class="material-icons">library_books</i>
                </div>
                <h4 class="card-title">Add Items</h4>
            </div>
            <div class="card-body">
                <div id="accordion" role="tablist">

                    <div class="card-collapse">
                        <div class="card-header" role="tab" id="headingTwo">
                            <h5 class="mb-0">
                                <a class="collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">

                                    ADD ITEMS

                                    <i class="material-icons">keyboard_arrow_down</i>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">  
                                {form_open('','')}


                                <div class="col-sm-12 product-items">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <select id="item_id" name="item_id" class=" form-control item_ajax" required="" style="width:100%"></select> 
                                                {form_error("item_id")}
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="number" class="form-control" id="quantity" name="quantity" value="{set_value('quantity')}"  required="true" autocomplete="Off" placeholder="Quantity">
                                                {form_error("quantity")}
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="rate" name="rate" value="{set_value('rate')}"  required="true" autocomplete="Off" placeholder="Rate">
                                                {form_error("rate")}
                                            </div>
                                        </div>

                                        <div class="col-sm-7">
                                            <div class="form-group">
                                                <textarea class="form-control ckeditor" required="true" required="true" id="note" name="note"> </textarea> 
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <button type="button"  class="add btn btn-primary" name="add"  value="Add"  />Add</button>
                                            </div>
                                        </div>
{*  <div class="col-sm-4">
<div class="form-group">
<textarea class="form-control" required="true" required="true" id="note" name="note"></textarea> 
</div>
</div> *}

</div>
</div>

{form_close()}

<div class="table-responsive">  

    <table id="example" class="display"  style="table-layout: fixed; width: 100%;">
        <thead class="bg-dark text-warning">
            <tr>
                <th width="50px;">No.</th>
                <th>Item Code</th>
                <th width="150px;">Item</th>
                <th>Rate</th>
                <th width="50px;">VAT</th>
                <th width="50px;">Quantity</th>
                <th>Total</th>
                <th>Total (Incl. VAT)</th>
                <th>IMAGE</th>

                <th>Action</th> 
            </tr>
        </thead> 

        <tfoot>
            <tr class="font-weight-bold font-italic">
                <td>TOTAL</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td id="qty-sum">0</td>
                <td id="total">0</td>
                <td id="total_inclusive">0</td>
                <td></td>
                <td></td>

            </tr>
        </tfoot>
    </table>
</div>

<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Add Discount</button>



<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">

                        <input type="text" class="form-control" id="by_amount" name="by_amount" placeholder="Discount BY Amount " onblur="getDiscountPercentage();" value="">
                    </div>
                    <div class="form-group"> 
                        <input type="text" class="form-control" id="by_percentage" name="by_percentage" placeholder="Discount By Percentage" onblur="getDiscountAmount();" value="">
                    </div>
{* <div class="form-group">
<textarea type="text" class="form-control" id="terms_conditions" placeholder="Terms & Conditions" name="terms_conditions" required=""></textarea>
</div>*}
<input type="hidden" name="enc_id" id="enc_id" value="{$enc_id}">
</form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary items_update">Add Items</button>
</div>
</div>
</div>
</div>

</div>

</div>

</div>


</div>
</div>
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
<script src="{assets_url()}plugins/DataTables/datatables.min.js"></script>
<script src="{assets_url('plugins/autocomplete/jquery-ui.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/moment.min.js')}"></script>  
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script> 
<script src="{assets_url('plugins/DataTables/media/js/jquery.dataTables.min.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-selectpicker.js')}"></script> 
<script src="{assets_url('bootv4/js/plugins/bootstrap-datetimepicker.min.js')}"></script>
<script src="{assets_url()}plugins/select2/js/select2.min.js"></script>
<script src="{assets_url('js/ckeditor.js') }"></script>  
<script>
    $(document).ready(function() { 
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


        md.initFormExtendedDatetimepickers();


        var table = $('#example').DataTable({ searching: false, paging: false, info: false,responsive: true});
        var counter = 1;
        var qty_sum = 0;
        var total = 0;
        var total_inclusive = 0;


        function getItemDetails () {
            var form_data = table
            .rows()
            .data();

            $.ajax({
                type:'POST',
                url:"{base_url('salesman/sales/get-items')}",
                data: { 
                    id: $('#item_id option:selected').val(), 
                    rate: $('#rate').val(), 
                    item_name: $('.item_ajax').find(":selected").text(),
                    quantity: $('#quantity').val(),
                    form_data: JSON.stringify(form_data.toArray()),
                } ,
                dataType:'json',
                success: function(data) {

                    if(data.success){
                        addRow(data.item_info); 
                    }else{
                        alert(data.msg)
                    }
                }
            })  
        }
        $(".add").on('click', function(event){
            getItemDetails();

        });

        function addRow (data) { 
            var validFrom = true;
            var  formInputs = ["item_id", "quantity"];
            $(".product-items :input").each(function(){
                var input = $(this); 
                if(formInputs.includes($(this).attr('id'))){ 
                    if(input.val() == ''){

                        input.focus();
                        validFrom = false;
                        return false;
                    }
                }
            });

            if(validFrom == true){
                var dept=$('#item_id option:selected').val();
                var note= mySpec.getData();
                mySpec.setData( '' );
                if (dept=='') {
                    validFrom = false;
                    return false;

                }
                var rowNode = table.row.add( [
                    counter,
                    data.code,
                    data.name +"<br><strong> Spec: </strong>" +note,
                    $('#rate').val(),
                    data.value+'%',
                    $('#quantity').val(),
                    data.total_price,
                    data.inclusive_vat,
                    '<a href="{assets_url()}images/items/'+data.item_images[0]['image']+'" target="_blank"><img src="{assets_url()}images/items/'+data.item_images[0]['image']+'" width="100"; height="100" class="img-thumbnail" target="_blank"></a>',


                    '<button type="button" class="btn btn-danger btn-sm remove" data-dept="'+dept+'" data-price="'+data.total_price+'" data-inclusive="'+data.inclusive_vat+'" data-quantity="'+ $('#quantity').val()+'"><i class="material-icons">delete</i></button>'
                    ] ).draw( false ).node();

                $( rowNode ).find('td').eq(5).addClass('td-qty');
                $( rowNode ).find('td').eq(6).addClass('td-rate');
                $( rowNode ).find('td').eq(7).addClass('td-total');

                $('.product-items input').val(''); 
                $('.product-items select').val(''); 
                $('.product-items textarea').val(''); 
                $('.product-items #item_id').focus();

                counter++;


                var qty_sum = 0;
                table.cells('.td-qty').data().each(function(value, index) {
                    qty_sum += parseFloat(value)
                });


                var total = 0;
                table.cells('.td-rate').data().each(function(value, index) {
                    total += parseFloat(value)
                });


                var total_inclusive = 0;
                table.cells('.td-total').data().each(function(value, index) {
                    total_inclusive += parseFloat(value)
                });

                $('#qty-sum').html(qty_sum);
                $('#total').html(total);
                $('#total_inclusive').html(total_inclusive);

                $("#item_id option[value*='"+dept+"']").prop('disabled',true);
                $('.item_ajax').val(null).trigger('change').select2('open');
            }
            else{ 
                alert('Invalid fields')
            }

        } ;
        $('#example').on('click', '.remove', function() {

            var total_qty = 0;
            table.cells('.td-qty').data().each(function(value, index) {
                total_qty += parseFloat(value)
            });


            var total_price = 0;
            table.cells('.td-rate').data().each(function(value, index) {
                total_price += parseFloat(value)
            });


            var total_inclusive = 0;
            table.cells('.td-total').data().each(function(value, index) {
                total_inclusive += parseFloat(value)
            });

            var quantity = $(this).attr('data-quantity');
            var price = $(this).attr('data-price');
            var inclusive = $(this).attr('data-inclusive'); 
            var row = $(this).parents('tr');

            if ($(row).hasClass('child')) {
                table.row($(row).prev('tr')).remove().draw();
            } else {
                table
                .row($(this).parents('tr'))
                .remove()
                .draw();
            }

            var qty=parseFloat(total_qty)-parseFloat(quantity);
            var sum=parseFloat(total_price)-parseFloat(price);
            var inclusive_sum=parseFloat(total_inclusive)-parseFloat(inclusive);
            $('#qty-sum').html(qty);
            $('#total').html(sum);
            $('#total_inclusive').html(inclusive_sum);

        });


        $('.items_update').on('click', function(){


            var enc_id= $("#enc_id").val();

            var by_percentage= $("#by_percentage").val();
            var by_amount= $("#by_amount").val();
// var terms_conditions= $("#terms_conditions").text();


var btnVal = $(this).val();
var form_data = table
.rows()
.data();

$.ajax({
    type:'POST',
    url:"{base_url('salesman/sales/add-sales-items')}",
    data: { id: '{$enc_id}',  data: JSON.stringify(form_data.toArray()), by_percentage: by_percentage, by_amount: by_amount, } ,
    dataType:'json',

})
.done(function( response ) { 
    url  = "{base_url('salesman/sales/sales-quotation')}";
    if(response.success){

        (btnVal == 'save') ? (document.location.href = url) : document.location.href = "{base_url('salesman/sales/edit-sales/')}"+'{$enc_id}';

    }else{
        alert("failed");
        (btnVal == 'save') ? (document.location.href = url) : document.location.href = "{base_url('salesman/sales/sales-quotation')}";
    }
}); 

});

        $("#qty").keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                return false;
            }
        });

        $('.item_ajax').on("select2:close", function(e) {
            var item_id = $("select.item_ajax option:checked" ).val();

            $.ajax({
                url:"{base_url('salesman/sales/get-item-details')}",

                cache: true,
                data: { 
                    id: $('#item_id option:selected').val(), 
                    type: 'ajax'
                },
                type: 'post',
                dataType: 'json',
                success: function(html){
                    if( html.success){
                        mySpec.setData(html.item_info.note ); 
                        $('#rate').val(html.item_info.price);
                    }
                }
            }); 
        }); 
    } );

$('.customer_ajax').select2({

    placeholder: 'Select a customer',
    ajax: {
        url:'{base_url()}salesman/customer/customer_ajax',

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


var counter=1;
var selected_dept=[];

$(document).ready(function() {
    setFormValidation('.ValidateForm'); 
});

function getDiscountPercentage() {
    var total_price = $('#total_inclusive').html();
    var by_amount = $('#by_amount').val();
    var by_percentage = (parseFloat(by_amount)/parseFloat(total_price) *100).toFixed(2);
    if(total_price > 0){
        var by_percentage = (parseFloat(by_amount)/parseFloat(total_price) *100).toFixed(2);

    }else{
        var by_percentage = 0
    }
    $('#by_percentage').val(by_percentage);  

}
function getDiscountAmount() {
    var total_price=$('#total_inclusive').html();
    var by_percentage=$('#by_percentage').val();
    var amount=total_price * by_percentage/100;
    $('#by_amount').val(amount);
}


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

$('.item_ajax').on('select2:select', function (e) {
    $('#quantity').focus();
});

$('.item_ajax').on("select2:close", function(e) {
    var item_id = $("select.item_ajax option:checked" ).val();

    $.ajax({
        url:"{base_url('salesman/sales/get-item-details')}",

        cache: true,
        data: { 
            id: $('#item_id option:selected').val(), 
            type: 'ajax'
        },
        type: 'post',
        dataType: 'json',
        success: function(html){


            if( html.length != 0){
                $('#rate').val(html.item_info.price);
            }
        }
    }); 
});
</script>

{/block}
