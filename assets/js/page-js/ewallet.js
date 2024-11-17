
$('body').on('click', '.mfk_overlay,.pop_cross', function () {
	$('.mfk_overlay').fadeOut().find('.convert_popup,.withdraw_popup').removeClass('active');
});
$('body').on('click', '#convert_to_purchase', function () {
	$('.mfk_overlay').fadeIn().find('.convert_popup').addClass('active');
	var win_height = $(window).outerHeight() / 2;
	var elem_height = $('.convert_popup').outerHeight() / 2;
	var targ_offset = win_height - elem_height;
	$('.convert_popup').offset({
		top: targ_offset
	});
});
$('body').on('click', '#with_draw', function () {
	$('.mfk_overlay').fadeIn().find('.withdraw_popup').addClass('active');
	var win_height = $(window).outerHeight() / 2;
	var elem_height = $('.withdraw_popup').outerHeight() / 2;
	var targ_offset = win_height - elem_height;
	$('.withdraw_popup').offset({
		top: targ_offset
	});
});
$('body').keypress(function (e) {
	if (e.which == 27) {
		$('.mfk_overlay').fadeOut().find('.withdraw_popup,.convert_popup').removeClass('active');
	}
});

$('body').on('click', '.convert_popup,.withdraw_popup', function (e) {
	e.stopPropagation();
});
$('body').on('click', '.mfktoggle', function (e) {
	plus_img = '';
	minus_img = '';

	$('.bonus_drop').each(function () {
		$(this).slideUp().prev().find('.mfktoggle img').attr('src', plus_img);
	});

	if ($(this).hasClass('minus')) {
		$(this).removeClass('minus').addClass('plus').find('img').attr('src', plus_img);
		$(this).closest('.bonus_title').next().slideUp();
	} else {
		$(this).removeClass('plus').addClass('minus').find('img').attr('src', minus_img);
		$(this).closest('.bonus_title').next().slideDown();
	}
});

function changeCommission(commisionTypeId)
{ 
	$('.selected_val').val(commisionTypeId);
	$("#my_wallet").submit();
};
var TransferValidation = function() {
    "use strict";
    var runFormValidator = function() {

        
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
     
    return {
        init: function() { 
            runFormValidator();
        }
    };
}(); 
