var rootPath = $("#rootPath").val();
function delete_currency(id,type='admin')
{   
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_delete_event").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {

            document.location.href = rootPath + type + "/currency/deleteCurrency/" + id ;
        } else {
            swal($("#text_cancelled").html(),$("#text_email_safe").html(), "error");
        }
    });
}

function edit_currency(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_currency").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_edit_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            document.location.href = rootPath + "admin/currency/add_edit/edit/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });
}


var CurrencyValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#currency_management_form');

        var errorHandler = $('.errorHandler', form);
        form.validate({
            errorElement: "span", 
            errorClass: 'help-block',
            errorPlacement: function (error, element) {

                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { 
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
                    error.appendTo($(element).closest('.form-group').children('div'));
                } else if (element.hasClass("date-picker")) {
                    error.insertAfter($(element).closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            ignore: ':hidden',

            rules : {
                name : {
                    required : true
                },
                symbol : {
                    minlength : 1,
                    required : true
                },
                value : {
                    required : true
                }
            },
            messages: {
                name: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#name").closest('.form-group').find(".control-label").html()),
                },
                symbol: { 
                    minlength : error_atleast_number.replace("symbolsymbol", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#symbol").closest('.form-group').find(".control-label").html()),
                },
                value: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#value").closest('.form-group').find(".control-label").html()),
                }
            },
            highlight: function (element) {
                $(element).closest('.help-block').removeClass('valid');

                $(element).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');
            },
            unhighlight: function (element) { 
                $(element).closest('.form-group').removeClass('has-error');

            },
            success: function (label, element) {
                label.addClass('help-block valid');
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
            }
        });
    };

    return {
        init: function() {
            runFormValidator();
        }
    };
}(); 
