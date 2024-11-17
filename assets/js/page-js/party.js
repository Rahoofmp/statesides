

var rootPath = $("#rootPath").val();
var logType = $("#logType").val();

var CreatePartyValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var form = $('#create_party');

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
            name : {
                required : true
            },
            address : {
                required : true
            },
            phone : {
                digits: true,
                required : true
            },
            start_date : {
                required : true
            },
            end_date : {
                required : true
            },
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            name: { 
                required : error_in_the_fieldid.replace("fieldid", $("#name").data('lang'))
            },
            address: { 
                required : error_in_the_fieldid.replace("fieldid", $("#address").data('lang'))
            },
            phone: { 
                required : error_in_the_fieldid.replace("fieldid", $("#phone").data('lang'))
            },
            start_date: { 
                required : error_in_the_fieldid.replace("fieldid", $("#start_date").data('lang'))
            },
            end_date: { 
                required : error_in_the_fieldid.replace("fieldid", $("#end_date").data('lang'))
            }, 
            email: { 
                required : error_in_the_fieldid.replace("fieldid", $("#email").data('lang'))
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



var PartyProductValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var form = $('#party_product_form');

        var errorHandler = $('.errorHandler', form);
        form.validate({
            errorElement: "span", 
            errorClass: 'help-block',
            errorPlacement: function (error, element) { 
                if (element.hasClass("date-picker") || element.prev().hasClass('input-group-addon')) {
                    error.insertAfter($(element).closest('.input-group'));
                } else {
                    error.insertAfter(element);
                }
            },
            ignore: ':hidden',

            rules : {
                product_name : {
                    required : true
                },
                product_amount : {
                    required : true
                }

            },
            messages: {
                product_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#product_name").data('lang'))
                },
                product_amount: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#product_amount").data('lang'))
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

    var runMakeSwitch = function() {
        $("input[type='checkbox'].change-party-to-product-status").bootstrapSwitch(); 
    };


    var runChangePartyStatus = function() { 
        $("input[type='checkbox'].change-party-to-product-status").on('switchChange.bootstrapSwitch', function(event, state)
        {  
            var partyProductId = $(this).data('id'); 
            var state = $(this).prop("checked");
            var status = (state == true) ? 'yes' : 'inactive' ;

            var url = rootPath + logType  + "/party/change_party_product_status";  

            $.post(url, 
            {
                party_product_id: partyProductId,
                status: status
            },function(data){  
                window.location.reload();
            });
        });
    };

    return {
        init: function() {
            runFormValidator();
            runMakeSwitch();
            runChangePartyStatus();
        }
    };
}(); 

function inactive_party(id)
{


    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_inactivate_party").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_inactivate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            document.location.href = rootPath + logType + "/party/inactivate_party/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_rank_safe").html(), "error");
        }
    });
}


function edit_party(id)
{


    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_party").html(),
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
            document.location.href = rootPath + logType + "/party/create_party/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_package_safe").html(), "error");
        }
    });
}
 
function edit_party_product(id)
{


    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_party_product").html(),
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
            document.location.href = rootPath + logType + "/party/party_product_management/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_package_safe").html(), "error");
        }
    });
}
function edit_party_to_product(id)
{


    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_party_product").html(),
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
            document.location.href = rootPath + logType + "/party/party_management/edit/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_package_safe").html(), "error");
        }
    });
}

function inactive_product_to_party(id)
{


    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_inactivate_party_to_product").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_inactivate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            document.location.href = rootPath + logType + "/party/inactivate_party_to_product/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_rank_safe").html(), "error");
        }
    });
}

function inactive_party_to_user(id)
{


    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_inactivate_party_from_user").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_inactivate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            document.location.href = rootPath + logType + "/party/inactivate_party_to_user/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_rank_safe").html(), "error");
        }
    });
}

function edit_party_to_user(id)
{


    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_party").html(),
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
            document.location.href = rootPath + logType + "party/party_management/edit_party_to_user/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_package_safe").html(), "error");
        }
    });
}

