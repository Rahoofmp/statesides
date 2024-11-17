
function trim(a)
{
    return a.replace(/^\s+|\s+$/, '');
}



$(document).ready(function()
{
    var rootPath = $("#rootPath").val();
    
    $("#sponsor_username").blur(function()
    {        
        var sponsorName = $('#sponsor_username').val(); 

        var checkUserAvailable = rootPath + "register/checkUsernameAvailability";
        var msg = $("#validation_message2").html();

        $("#message_box").removeClass();
        $("#message_box").addClass('messagebox');
        $("#message_box").html(msg).show().fadeTo(2200, 1);

        $.post(checkUserAvailable, {username: $('#sponsor_username').val()}, function(data)
        {

            if (trim(data) == 'no') 
            {
                document.getElementById('reg_page').style.display = 'none';
                $("#message_box").fadeTo(2200, 0.1, function(){
                    var msg = $("#validation_message1").html();
                    var error = "<span class='help-block' style='color: red;''><i class='fa fa-times fa-lg'></i> "+ msg +"</span>";
                    $(this).html(error).show().fadeTo(100, 1);
                    $(this).closest('.form-group').removeClass('has-success').addClass('has-error').find('.symbol').removeClass('ok').addClass('required');

                });

            } else {
                document.getElementById('reg_page').style.display = 'block';
                $("#message_box").fadeTo(2200, 0.1, function()
                {
                    msg = $("#validation_message3").html(); 
                    $(this).html('<span class="help-block"> <i class="symbol ok"  style="color: green;">'+ msg +' </i></span>').show().fadeTo(100, 1);
                    $(this).closest('.form-group').removeClass('has-error').addClass('has-success').find('.symbol').removeClass('required').addClass('ok');
                });

            }
        });

    }); 

});


