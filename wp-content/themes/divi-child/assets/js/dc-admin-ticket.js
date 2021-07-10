let sync_console = 'div#ticket-console';
function sync_log( str, color = '#fff' ) {
    return str !== '' ? '<div style="color:' + color + '">' + str + '</div>' : '';
}
function scroll($) {
    $(sync_console).scrollTop($(sync_console)[0].scrollHeight);
}
async function notify($) {
    let token = ticket.token;
    let ticket_id = ticket.post_id;
    let template = ticket.template;

    // start notifications.
    let xmlRequest = $.ajax({
        method: "POST",
        url: ticket.url,
        data: { 
            action: "dc_notify_users", 
            ticket_id: ticket_id, 
            template: template,
            token: token
        }
    });

    await xmlRequest.done( function( response ) {
        console.log( response );
        let res = JSON.parse(response);
        if ( res.status === '1' ) {
            $( sync_console ).append( sync_log( 'Users notified, Email sent.', '#43afb9' ) );
        } else if ( res.status === '-1' ) {
            $( sync_console ).append( sync_log( 'Alert, Invalid security nonce.' ,'#f56565' ) );
        } else if ( res.status === '-2' ) {
            $( sync_console ).append( sync_log( 'Required fields are missing.', '#f56565' ) );
        } else if ( res.status === '-3' ) {
            $( sync_console ).append( sync_log( 'Ticket has not been assigned to any users.', '#f56565' ) );
        } else {
            $( sync_console ).append( sync_log( 'Can not process.', '#f56565' ) );
        }    
        scroll($);
    });

    await xmlRequest.fail(function( jqXHR, textStatus ) {
        $( sync_console ).append( sync_log( "Request failed: " + textStatus ) );
    });
}
async function notify_users($) {
    if (ticket.template === "") {
        $( sync_console ).append( sync_log( 'Coudln\'t find registered email template.' ) );
        return;
    }

    await notify($);
}

jQuery( document ).ready(function($) {
    $(document).on("click","#notify-ticket-users",function() {
        $(this).prop( "disabled", true );

        $(sync_console).show();

        $(sync_console).html( sync_log( 'Initializing, Please wait...' ) );

        window.addEventListener('beforeunload', function (e) {
            e.preventDefault();            
            return 'Email is not sent to all users yet, Are you sure you want to navigate?';
        });

        notify_users($);

        $(this).prop( "disabled", false );
    });
});