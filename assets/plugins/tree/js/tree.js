
function generateTree(user_id, user_type, tree_type,entry_no)
{
    var rootPath = $("#rootPath").val();
    $loadingDiv = $(".loading-div").parent();;

    $.ajax({
        type: "POST",
        url: rootPath+user_type+'/network/view_network',
        data: {
            user_id: user_id, tree: tree_type,entry_no: entry_no
        },
        beforeSend: function(){            
            $(".loading-div").show().ajaxStart(function() {
                $(this).show();
            })
        },

        success: function(data) {
            $('#tree').html(data);
        },
        complete: function(){
            $(".loading-div").hide();
        } 
    });
} 

function register(user_name)
{
    var rootPath = $("#rootPath").val();
    location.href = rootPath + 'signup/'+ user_name ;
}