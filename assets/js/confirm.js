var rootPath = $("#rootPath").val();

function trim(a)
{

    return a.replace(/^\s+|\s+$/, '');
}

function inactivate_news(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/settings/delete_news";

            $.post(check_user_available, {news_id: id}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_news_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/settings/news_management";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}

function inactivate_roi(id)
{

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/settings/delete_date";

            $.post(check_user_available, {date_id: id}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_date_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/settings/no_roi_dates";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}
function inactivate_efile(id)
{

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/settings/delete_efiles";

            $.post(check_user_available, {efile_id: id}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_news_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/settings/training";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}

function inactivate_market(id)
{

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/market/delete_market";

            $.post(check_user_available, {market_id: id}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_news_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/market/add_market";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}


function activate_market(id)
{

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_activate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/market/activate_market";

            $.post(check_user_available, {market_id: id}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_course_activated").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/market/add_market";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}




function edit_market(id)
{

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
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
            document.location.href = rootPath+'admin/market/add_market/'+id+'/'+'edit';
        } else {
            swal($("#text_cancelled").html(),$("#text_cancelled").html(), "error");
        }
    });


}

function edit_news(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_news").html(),
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
            document.location.href = rootPath + "admin/settings/news_management/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}

function activate_package(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_activate").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_activate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/settings/update_package";

            $.post(check_user_available, {package_id: id , status: "yes"}, function(data)
            {
                swal({
                    title: $("#text_activated").html(), 
                    text: $("#text_package_activated").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/settings/package_settings";
                });
            });            
        } else {
            swal($("#text_cancelled").html(),$("#text_package_safe").html(), "error");
        }
    });
}

function inactivate_package(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_deactivate").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_deactivate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/settings/update_package";

            $.post(check_user_available, {package_id: id , status: "no"}, function(data)
            {
                swal({
                    title: $("#text_deactivated").html(), 
                    text: $("#text_package_deactivated").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/settings/package_settings";
                });
            });



        } else {
            swal($("#text_cancelled").html(),$("#text_package_safe").html(), "error");
        }
    });
}

function edit_package(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_package").html(),
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
            document.location.href = rootPath + "admin/settings/package_settings/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_package_safe").html(), "error");
        }
    });
}

function activate_rank(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_activate_rank").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_activate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/settings/update_rank";

            $.post(check_user_available, {rank_id: id , status: "yes"}, function(data)
            {


                swal({
                    title: $("#text_activated").html(), 
                    text: $("#text_rank_activated").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/settings/rank_settings";
                });
            });            
        } else {
            swal($("#text_cancelled").html(),$("#text_rank_safe").html(), "error");
        }
    });
}

function inactivate_rank(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_deactivate_rank").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_deactivate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/settings/update_rank";

            $.post(check_user_available, {rank_id: id , status: "no"}, function(data)
            {


                swal({
                    title: $("#text_deactivated").html(), 
                    text: $("#text_rank_deactivated").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/settings/rank_settings";
                });
            });
        } else {
            swal($("#text_cancelled").html(),$("#text_rank_safe").html(), "error");
        }
    });
}

function edit_rank(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_rank").html(),
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
            document.location.href = rootPath + "admin/settings/rank_settings/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_rank_safe").html(), "error");
        }
    });
}

function edit_event_management(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_event").html(),
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
            document.location.href = rootPath + "admin/events/event_management/edit/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });
}

function delete_event_management(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_delete_event").html(),
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
            document.location.href = rootPath + "admin/events/event_management/delete/"+id ; 
        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });
}


function deleteMessage(id,log_type='',red_path)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_delete_event").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {

            document.location.href = rootPath + log_type + "/mail/deleteMessage/" + id +"/"+ red_path;
        } else {
            swal($("#text_cancelled").html(),$("#text_email_safe").html(), "error");
        }
    });
}



function activate_pre_user(id)
{
    var base_url = $("#base_url").html();

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_activate_preuser").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_activate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  base_url + "admin/privileged_user/update_pre_user";
            
            $.post(check_user_available, {preuser_id: id , status: "yes"}, function(data)
            {
               swal({
                title: $("#text_activated").html(), 
                text: $("#text_preuser_activated").html(), 
                type: "success"
            },function() {
               document.location.href = base_url + "admin/privileged_user/add_privileged_user";
           });
           });            
        } else {
            swal($("#text_cancelled").html(),$("#text_preuser_safe").html(), "error");
        }
    });
}

function inactivate_pre_user(id)
{
    var base_url = $("#base_url").html();

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_deactivate_preuser").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_deactivate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  base_url + "admin/privileged_user/update_pre_user";
            $.post(check_user_available, {preuser_id: id , status: "no"}, function(data)
            {
               swal({
                title: $("#text_deactivated").html(), 
                text: $("#text_preuser_deactivated").html(), 
                type: "success"
            },function() {
               document.location.href = base_url + "admin/privileged_user/add_privileged_user";
           });
           });
        } else {
            swal($("#text_cancelled").html(),$("#text_preuser_safe").html(), "error");
        }
    });
}

function edit_pre_user(id)
{
    var base_url = $("#base_url").html();

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_preuser").html(),
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
            document.location.href = base_url + "admin/privileged_user/add_privileged_user/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_preuser_safe").html(), "error");
        }
    });
}


function confirm_payment(id)
{
    var base_url = $("#base_url").html();

    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_want_edit_preuser").html(),
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
            document.location.href = base_url + "admin/settings/bitcoin_payments/"+id; 
        } else {
            swal($("#text_cancelled").html(),$("#text_preuser_safe").html(), "error");
        }
    });
}


//Bitcoin payments
function inactivate_payment(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/settings/delete_payment";

            $.post(check_user_available, {id: id}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_news_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/settings/bitcoin_payments";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}


//contact feeds
function delete_contact_feeds(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/report/update_contact_feeds";

            $.post(check_user_available, {id: id,status:"deleted"}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_news_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/report/contact_feedbacks";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}


function confirm_contact_feeds(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_update_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/report/update_contact_feeds";

            $.post(check_user_available, {id: id,status:"complete"}, function(data)
            {
                swal({
                    title: $("#text_updated").html(), 
                    text: $("#text_news_updated").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/report/contact_feedbacks";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}



//Register feeds
function delete_register_feeds(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/report/update_register_feeds";

            $.post(check_user_available, {id: id,status:"deleted"}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_news_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/report/register_feedbacks";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}


function confirm_register_feeds(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_update_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/report/update_register_feeds";

            $.post(check_user_available, {id: id,status:"complete"}, function(data)
            {
                swal({
                    title: $("#text_updated").html(), 
                    text: $("#text_news_updated").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/report/register_feedbacks";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}




//Fund transfer
function confirm_transfer(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_activate_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/ewallet/transfer_reject_confirm";

            $.post(check_user_available, {id: id,status:'confirm'}, function(data)
            {
                swal({
                    title: $("#text_activated").html(), 
                    text: $("#text_news_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/ewallet/transfer_requests";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}

function reject_transfer(id)
{
    swal({
        title: $("#text_are_you_sure").html(),
        text: $("#text_you_will_not_recover").html(),
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: $("#text_yes_delete_it").html(),
        cancelButtonText: $("#text_no_cancel_please").html(),
        closeOnConfirm: false,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var check_user_available =  rootPath + "admin/ewallet/transfer_reject_confirm";

            $.post(check_user_available, {id: id,status:'reject'}, function(data)
            {
                swal({
                    title: $("#text_deleted").html(), 
                    text: $("#text_news_deleted").html(), 
                    type: "success"
                },function() {
                    document.location.href = rootPath + "admin/ewallet/transfer_requests";
                });
            });

        } else {
            swal($("#text_cancelled").html(),$("#text_news_safe").html(), "error");
        }
    });

}



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