var TransferValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#trans_form');

        var errorHandler = $('.errorHandler', form);
        form.validate({
            errorElement: "span", 
            errorClass: 'help-block',
            errorPlacement: function (error, element) {

                if (element.attr("type") == "radio" || element.attr("type") == "checkbox" ) { 
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.hasClass("touchspin")) {
                    error.appendTo($(element).closest('.form-group'));
                } else if (element.hasClass("date-picker")) {
                    error.insertAfter($(element).closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            ignore: ':hidden',

            rules : {
                from_name : {
                    required : true
                },
                to_name : {
                    required : true
                },
                amount : {
                    digits: true,
                    required : true
                },
                security_pass : {
                    required : true
                },
                transaction_note : {
                    required : true
                }
            },
            messages: {
                from_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#from_name").data('lang'))
                },
                to_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#to_name").data('lang'))
                },
                amount: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#amount").data('lang'))
                },
                security_pass: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#security_pass").data('lang'))
                },
                transaction_note: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#transaction_note").data('lang'))
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
    /*var runTouchSpin = function() {
        $("input[name='amount']").TouchSpin({
            verticalbuttons: true
        });
    };*/

    return {
        init: function() {
            runTouchSpin();
            runFormValidator();
        }
    };
}(); 
