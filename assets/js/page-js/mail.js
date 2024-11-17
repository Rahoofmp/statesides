
var rootPath = $('#rootPath').val();
var logType = $('#logType').val();


var Inbox = function() {
	"use strict";

	var runMakeSwitch = function() {
		$("input[type='checkbox'].make-switch").bootstrapSwitch(); 
	};
	var runSwitchChange = function() { 
		$("input[type='checkbox'].make-switch").on('switchChange.bootstrapSwitch', function(event, state)
		{ 
			var div = $('#user_names').parent();
			(state == true) ? div.addClass('hide') : div.removeClass('hide') ;
			(state == true) ? $( "#status" ).prop( "checked", false ) :   $( "#status" ).prop( "checked", true )  ;

		}); 
	};
	var runSendMAil = function() { 
		$("#send").on('click', function(event)
		{ 
			var status = ($('#user_names').parent().hasClass('hide')) ? 'send_all' : 'multiple';
			var message = $('#editor').text();

			var Url = rootPath + logType + "/mail/compose_mail";  

			$.post(Url,{
				subject: $('#subject').val(),
				user_names: $('#user_names').val(),
				message : $('#message').text(),
				status: status
			},function(data){ 

				toastr.options = {
					"closeButton": true,
					"positionClass": "toast-top-full-width"
				}

				if(data.status == true){ 
					window.location.reload();
				}else if(data.status == false){ 

					$.each(data.error_msg, function(key, val){
						var element = '#'+key;
						var helpBlock = '<span class="help-block">'+ val +'</span>';
						if($(element).hasClass('editor-wrapper'))
						{
							$(element).parent().addClass('has-error');
							$(helpBlock).insertAfter($(element));

						}else
						{
							$(element).closest('.form-group').addClass('has-error');
							$(helpBlock).appendTo($(element).closest('.form-group'));
						} 

					});

					toastr['warning']('', 'title');
				}
 
            });
		}); 
	};

	return {
		init: function() {
			runMakeSwitch();
			runSwitchChange();
			runSendMAil();
		}
	};
}();

var ComposeMailValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#compose');

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
                user_id : {
                    required : true
                },
                subject : {
                    required : true
                },
                message : {
                    required : true
                }
            },
            messages: {
              user_id: { 
                required : error_in_the_fieldid.replace("fieldid", $("#user_id").data('lang')) 
            },  
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


var ReplyMailValidation = function() {
    "use strict";
    var runFormValidator = function() {

        var error_in_the_fieldid = $("#error_the_fieldid_field_is_required").html();
        var error_atleast_number = $("#error_atleast_number").html();
        var error_enter_greater_number = $("#error_enter_greater_number").html();
        var form = $('#reply');
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


$('.messages-item').on('click', function(){
	var activeList = this;
	var mesgId = $(activeList).data('val');
	var readMessage = rootPath +'/'+ logType +"/mail/read_message";
	$.post(readMessage, {
		message_id: mesgId
	}, function (response) {
		$('#messages-content').remove();
		$('.messages-content').html(response);
		$('.messages-list').find(".active").removeClass('active');
		$(activeList).addClass('active');
	});

});

$('body').on('click', '.mail-to-trash', function () {
	var mesgId = $('.messages-list').find(".active").data('val');
	var deleteMessage = rootPath +'/'+ logType +"/mail/deleteMessage";
	$.post(deleteMessage, {
		message_id: mesgId
	}, function (response) {
		var response = $.parseJSON(response);
		toastr[response.status](response.message, "Notifications");
		var activeMessage = $('.messages-list').find(".active");
		$(activeMessage).remove();
		var nextMessage = $(activeMessage).next();
		var nextMessageId = $(nextMessage).data('val');
		nextMessageId = (nextMessageId != 'undefined') ? nextMessageId : $('.messages-list .messages-item').first().data('val');
		if(nextMessageId){	
			var readMessage = rootPath +'/'+ logType +"/mail/read_message";
			$.post(readMessage, {
				message_id: nextMessageId
			}, function (data) {
				$(nextMessage).addClass('active');
				$('#messages-content').remove();
				$('.messages-content').html(data);
			});
		}else{
			$('.messages-list .messages-item').first().addClass('active');
		}
	});
});

function change_status(val)
{
	if (val == "all")
	{
		$('#user_div').hide();
	}
	else {
		$('#user_div').show();
	}
}

function deleteMessage(id, row, type, path_root ,status)
{
	var confirm_msg = $("#confirm_msg").html();
	if (confirm(confirm_msg))
	{
		document.location.href = path_root + "/mail/deleteMessage/" + id + "/" + type + "/" + status;
	}
}