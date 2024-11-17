Ticket = {
    changeTicketStatus: function() {
        $('.change-status').on('click', function(event){ 

            swal({
                title: "Wait.., Be patient we are changing the status..",
                timer: 15000,
                type: 'warning',
                showConfirmButton: false
            }).catch(swal.noop);

            var ticketIds = [];
            var changeStatusUrl = $('rootPath').val() + $('logType').val() + '/support/changeTicketStatus';
            $(".ticketIds:checked").each(function() {
                ticketIds.push($(this).val());
            }); 


            var ticketIds = [];
            $(".ticketIds:checked").each(function() {
                ticketIds.push($(this).val());
            });
            var changeStatusUrl = $('#rootPath').val() + $('#logType').val() + '/support/changeTicketStatus';
            var data = {ticket_ids : ticketIds, status: $(this).data('val')}
            $.post(changeStatusUrl, data, function(response){
                response = JSON.parse(response);
                if(response.error){                    
                    $.notify({
                        icon: "notifications",
                        message: response.message
                    }, {
                        type: type['danger'],
                        timer: 3000,
                        placement: {
                            from: 'top',
                            align: 'right'
                        }
                    });
                }else{
                    window.location.href = $('#rootPath').val() + $('#logType').val() + '/support/my_tickets';
                }
            });
        }); 
    }
}