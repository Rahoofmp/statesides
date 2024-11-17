var AddDeductValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number_items").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#trans_form');

        var errorHandler = $('.errorHandler', form);
        form.validate({
            errorElement: "span", 
            errorClass: 'help-block',
            errorPlacement: function (error, element) {

                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { 
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
                user_name : {
                    required : true
                },
                
                amount : {
                    digits: true,
                    required : true
                },
                tran_concept : {
                    required : true
                }
            },
            messages: {
                user_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#user_name").data('lang'))
                },
                amount: {
                    required : error_in_the_fieldid.replace("fieldid", $("#amount").data('lang'))
                },
                tran_concept: {
                    required : error_in_the_fieldid.replace("fieldid", $("#tran_concept").data('lang'))
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

var PayoutValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number_items = $("#error_atleast_number_items").html();
        var form = $('#payout_release');

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
            rules: {
                'releases[]': {
                    minlength: 1,
                    required: true
                }
            },
            messages: {
                'releases[]': {  
                    minlength : jQuery.format(error_atleast_number_items.replace("number_required", "{0}")),
                    required : error_in_the_fieldid.replace("fieldid", $("#release0").data('lang'))
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

var UIModals = function() {
    "use strict";  
    var initModals = function() {
        $.fn.modalmanager.defaults.resize = true;
        $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' + '<div class="progress progress-striped active">' + '<div class="progress-bar" style="width: 100%;"></div>' + '</div>' + '</div>';
        var $modal = $('#ajax-modal');
        $('.ajax .demo').on('click', function() {
            // create the backdrop and wait for next modal to be triggered
            $('body').modalmanager('loading');
            setTimeout(function() {
                var url = $("#rootPath").val()+ "admin/payments/show_bank_details";
                var user_id = $(this).data("user_id");
                var data = {user_id:user_id, type:"bank"};
                $modal.load(url, data, function() {
                    $modal.modal();
                });
            }, 1000);
        });
    }; 
    return {
        init : function() {
            initModals(); 
        }
    };
}(); 


