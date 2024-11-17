var ChangeAdminPasswordValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#pass_admin_change');

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
                current_pass : {
                    required : true
                },
                new_pass : {
                    required : true
                },
                con_pass : {
                    required : true
                }
            },
            messages: {

               current_pass: { 
                required : error_in_the_fieldid.replace("fieldid", $("#current_pass").data('lang')) 
            },
            new_pass: { 
                required : error_in_the_fieldid.replace("fieldid", $("#new_pass").data('lang'))
            },  
            con_pass: { 
                required : error_in_the_fieldid.replace("fieldid", $("#con_pass").data('lang'))
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


var ChangeUserPasswordValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#pass_user_change');

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
                user_name : {
                    required : true
                },
                new_usr_pass : {
                    required : true
                },
                current_pass : {
                    required : true 
                },
                con_pass : {
                    required : true 
                },
                new_pass : { 
                    required : true 
                },
                con_usr_pass : {
                    required : true
                }
            },
            messages: {
                user_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#user_name").data('lang')) 
                }, 
                new_usr_pass: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#new_usr_pass").data('lang')) 
                }, 
                current_pass: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#current_pass").data('lang')) 
                },
                new_pass: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#new_pass").data('lang'))
                },  
                con_pass: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#con_pass").data('lang'))
                },
                con_usr_pass: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#con_usr_pass").data('lang'))
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

var UserSecurityPinValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#send_pin');

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
                user_name_for_mail : {
                    required : true
                }
            },
            messages: {

             user_name_for_mail: { 
                required : error_in_the_fieldid.replace("fieldid", $("#user_name_for_mail").data('lang'))
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


var AdminSecurityPinValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#pin_admin_change');

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
                current_pin : {
                    required : true
                },
                new_pin : {
                    required : true
                },
                con_pin : {
                    required : true
                }
            },
            messages: {

                current_pin: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#current_pin").data('lang'))
                },
                new_pin: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#new_pin").data('lang'))
                },
                con_pin: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#con_pin").data('lang'))
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

var UserSecurityPinValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#pin_user_change');

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
                user_name : {
                    required : true
                },
                new_user_pin : {
                    required : true
                },
                con_user_pin : {
                    required : true
                }
            },
            messages: {

                user_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#user_name").data('lang'))
                },
                new_user_pin: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#new_user_pin").data('lang'))
                },
                con_user_pin: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#con_user_pin").data('lang'))
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

var ChangeUserNameValidation = function() {
    "use strict";
    var runFormValidator = function() {
        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#user_name_change');

        var errorHandler = $('.errorHandler', form);
        form.validate({
            errorElement: "span", 
            errorClass: 'help-block',
            errorPlacement: function (error, element) {

                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") { 
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.hasClass("date-picker")) {
                    error.insertAfter($(element).closest('.input-group'));
                } else if (element.attr("name") == "card_expiry_mm" || element.attr("name") == "card_expiry_yyyy") {
                    error.appendTo($(element).closest('.form-group').children('div'));
                } else {
                    error.insertAfter(element);
                }
            },
            ignore: ':hidden',

            rules : {
                user_name : {
                    required : true
                },
                new_user_name : {
                    required : true
                }
            },
            messages: {

                user_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#user_name").data('lang'))
                },  
                new_user_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#new_user_name").data('lang'))
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



var UserPinValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#pass_user_change');

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
                current_pin : {
                    required : true
                },
                new_pin : {
                    required : true
                },
                con_pin : {
                    required : true
                }
            },
            messages: {
                current_pin: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#current_pin").data('lang'))
                }, new_pin: { 
                     required : error_in_the_fieldid.replace("fieldid", $("#new_pin").data('lang'))
                }, con_pin: { 
                     required : error_in_the_fieldid.replace("fieldid", $("#con_pin").data('lang')) 
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



var ProfileValidation = function() { 
    "use strict";
    var runFormValidator = function() {
        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#user_wise');

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
                user_name : {
                    required : true
                }
            },
            messages: {
                user_name: { 
                    required : error_in_the_fieldid.replace("fieldid", $("#user_name").data('lang')) 
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


var EditProfileValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var error_missmatch_with_fieldid = $("#error_missmatch_with_fieldid").html();
        var error_select_year_month_day = $("#error_select_year_month_day").html();
        var form = $('#edit_profile');

        $.validator.addMethod("FullDate", function () {
            if ($("#day").val() != "" && $("#month").val() != "" && $("#year").val() != "") {
                var dateOgj = [$("#day").val(), $("#month").val(), $("#year").val()];
                var d = new Date(dateOgj[2] + '/' + dateOgj[1] + '/' + dateOgj[0]);
                return !!(d && (d.getMonth() + 1) == dateOgj[1] && d.getDate() == Number(dateOgj[0]));
            } else {
                return false;
            }
        }, error_select_year_month_day);
        form.validate({
            errorElement: "span", 
            errorClass: 'help-block',
            errorPlacement: function (error, element) {

                if (element.attr("type") == "radio" || element.attr("type") == "checkbox") {
                    error.insertAfter($(element).closest('.form-group').children('div').children().last());
                } else if (element.attr("name") == "day" || element.attr("name") == "month" || element.attr("year") == "yyyy") {
                    error.insertAfter($(element).closest('.form-group').children('div'));
                } else {
                    error.insertAfter(element);
                }
            },
            ignore: ':hidden',
            rules: {
                first_name: {
                    required: true,
                    minlength: 2,
                },
                email: {
                    required: true,
                    email: true
                },
                mobile: {
                    required: true,
                    minlength: 6,
                },
                year:"FullDate"
            },
            messages: {
                first_name: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#first_name").data('lang')) 
                },
                email: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                   required : error_in_the_fieldid.replace("fieldid", $("#email").data('lang')) 
                },
                mobile: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#mobile").data('lang')) 
                }
            },
            groups: {
                DateofBirth: "day month year",
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




