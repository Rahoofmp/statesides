var FormWizard = function () {
    "use strict";
    var wizardContent = $('#wizard');
    var wizardForm = $('#form');
    var numberOfSteps = $('.swMain > ul > li').length;
    var initWizard = function () {
        wizardContent.smartWizard({
            selected: 0,
            keyNavigation: false,
            onLeaveStep: leaveAStepCallback,
            onShowStep: onShowStep,
        });
        var numberOfSteps = 0;
        animateBar();
        initValidator();
    };
    var animateBar = function (val) {
        if ((typeof val == 'undefined') || val == "") {
            val = 1;
        };

        var valueNow = Math.floor(100 / numberOfSteps * val);
        $('.step-bar').css('width', valueNow + '%');
    };
    var validateCheckRadio = function (val) {
        $("input[type='radio'], input[type='checkbox']").on('ifChecked', function(event) {
            $(this).parent().closest(".has-error").removeClass("has-error").addClass("has-success").find(".help-block").remove().end().find('.symbol').addClass('ok');
        });
    };    
    var initValidator = function () {
        $.validator.addMethod("FullDate", function () {
            if ($("#day").val() != "" && $("#month").val() != "" && $("#year").val() != "") {
                var dateOgj = [$("#day").val(), $("#month").val(), $("#year").val()];
                var d = new Date(dateOgj[2] + '/' + dateOgj[1] + '/' + dateOgj[0]);
                return !!(d && (d.getMonth() + 1) == dateOgj[1] && d.getDate() == Number(dateOgj[0]));
            } else {
                return false;
            }
        }, error_select_year_month_day);
        $.validator.setDefaults({
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
                sponsor_username: {
                    required: true
                },
                position: {
                    required: true
                },
                package_id: {
                    required: true
                },
                user_name_entry: {
                    minlength: 4,
                    required: true
                },
                password: {
                    minlength: 6,
                    required: true
                },
                password_again: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                },
                first_name: {
                    required: true,
                    minlength: 2,
                },
                email: {
                    required: true,
                    email: true
                },
                phone: {
                    required: true,
                    minlength: 6,
                },
                user_name_wallet: {
                    required: true,
                },
                tran_pass_ewallet: {
                    required: true,
                    minlength: 6,
                },
                year:"FullDate"
            },
            messages: {
                sponsor_username: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#user_name_entry").data('lang')),
                },
                package_id: { 
                    required :error_in_the_fieldid.replace("fieldid", $("#package_id").data('lang')),
                },
                user_name_entry: "Please specify your username,or user name length is too low",
                password: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#password").data('lang')),
                },
                password_again: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#password_again").data('lang')),
                    equalTo : error_missmatch_with_fieldid.replace("fieldid", "{0}"),
                },
                first_name: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#first_name").data('lang')),
                },
                email: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#email").data('lang')),
                },
                phone: { 
                    minlength : error_atleast_number.replace("number_required", "{0}"),
                    required : error_in_the_fieldid.replace("fieldid", $("#phone").data('lang')),
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
var displayConfirm = function () {
    $('.display-value', form).each(function () {
        var input = $('[name="' + $(this).attr("data-display") + '"]', form);
        if (input.attr("type") == "text" || input.attr("type") == "email" || input.is("textarea")) {
            $(this).html(input.val());
        } else if (input.is("select")) {
            $(this).html(input.find('option:selected').text());
        } else if (input.is(":radio") || input.is(":checkbox")) {

            $(this).html(input.filter(":checked").closest('label').text());
        } else if ($(this).attr("data-display") == 'card_expiry') {
            $(this).html($('[name="card_expiry_mm"]', form).val() + '/' + $('[name="card_expiry_yyyy"]', form).val());
        } else if ($(this).attr("data-display") == 'dob') {
            $(this).html($('[name="year"]', form).val() + '-' + $('[name="month"]', form).val() + '-' + $('[name="day"]', form).val());
        }
    });
};
var onShowStep = function (obj, context) {
        // if(context.toStep == numberOfSteps){
        // 3rd step is the preview page
        if(context.toStep == 3){ 
            $('.anchor').children("li:nth-child(" + context.toStep + ")").children("a").removeClass('wait');
            displayConfirm();
        }
        $(".next-step").unbind("click").click(function (e) {
            e.preventDefault();
            wizardContent.smartWizard("goForward");
        });
        $(".back-step").unbind("click").click(function (e) {
            e.preventDefault();
            wizardContent.smartWizard("goBackward");
        });
        $(".finish-step").unbind("click").click(function (e) {
            e.preventDefault();
            onFinish(obj, context);
        });
    };
    var leaveAStepCallback = function (obj, context) {
        return validateSteps(context.fromStep, context.toStep);
    };
    var onFinish = function (obj, context) {
        
        if (validateAllSteps()) {
            $('.anchor').children("li").last().children("a").removeClass('wait').removeClass('selected').addClass('done').children('.stepNumber').addClass('animated tada');
            wizardForm.submit();
        }
    };
    var validateSteps = function (stepnumber, nextstep) {
        var isStepValid = false;


        if (numberOfSteps >= nextstep && nextstep > stepnumber) {

            if (wizardForm.valid()) { 
                wizardForm.validate().focusInvalid();
                for (var i=stepnumber; i<=nextstep; i++){
                    $('.anchor').children("li:nth-child(" + i + ")").not("li:nth-child(" + nextstep + ")").children("a").removeClass('wait').addClass('done').children('.stepNumber').addClass('animated tada');
                }
                animateBar(nextstep);
                isStepValid = true;
                return true;
            };
        } else if (nextstep < stepnumber) {
            for (i=nextstep; i<=stepnumber; i++){
                $('.anchor').children("li:nth-child(" + i + ")").children("a").addClass('wait').children('.stepNumber').removeClass('animated tada');
            }

            animateBar(nextstep);
            return true;
        } 
    };
    var validateAllSteps = function () {
        var isStepValid = true;
        return isStepValid;
    };
    //function to initiate bootstrap-datepicker
    var runDatePicker = function() {
        $('.date-picker').datepicker({
            autoclose: true
        });
    };
    return {
        init: function () {
            initWizard();
            validateCheckRadio();
            runDatePicker();
        }
    };
}();