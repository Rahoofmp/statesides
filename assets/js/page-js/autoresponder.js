
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




function delete_autoresponder(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_delete_this_autoresponder").html(),
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
            document.location.href = rootPath + "admin/auto_responder/auto_promotion/delete/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_autoresponder_safe").html(), "error");
        }
    });
}



