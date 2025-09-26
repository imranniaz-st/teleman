"use strict"
/**
 * @param phone_number 
 * @param campaign_id 
 * @returns 
 */
function SendSMS(phone_number, campaign_id) // phone_number is contact id
{
    var message = $('#'+ phone_number +'').val();
    var url = $('#twilio_send_sms').val();

    if (message == '') {
        toastr.error('Please write something');
        return false;
    }

    // ajaxSetup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // ajax setup request start

    $.ajax({
        type: 'GET',
        url: url,
        data: {
            phone_number: phone_number,
            campaign_id: campaign_id,
            message: message
        },
        success: function(data) {
            console.log(data);
            if (data.status == 'success') {
                $('#slc-' + phone_number).removeClass('btn-secondary');
                $('#slc-' + phone_number).addClass('btn-success');
                toastr.success(data.message);
            }else{
                toastr.error(data.message);
            }
        }
    });

    // ajax setup request end
}
