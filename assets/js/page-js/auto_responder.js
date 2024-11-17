
var rootPath = $("#rootPath").val();


var TextInviteValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var form = $('#text_invite_form');

        var errorHandler = $('.errorHandler', form);
        form.validate({
            errorElement: "span", 
            errorClass: 'help-block',
            errorPlacement: function (error, element) {

                if (element.hasClass("date-picker")) {
                    error.insertAfter($(element).closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            ignore: ':hidden',

            rules : {
                subject : {
                    required : true
                },
                message : {
                    required : true
                }

            },
            messages: {
                subject: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#subject").data('lang'))
                },
                message: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#message").data('lang'))
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

function edit_autoresponder(id)
{
   
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_autoresponder").html(),
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
            document.location.href = rootPath + "admin/auto_responder/auto_promotion/edit/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_package_safe").html(), "error");
        }
    });
}

function chngeAutoType(type){

    if(type == "manual"){
       $(".flip").show();
    }
    else{

       $(".flip").hide();
    }
}