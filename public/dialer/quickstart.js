"use strict"

$(function () {
  var speakerDevices = document.getElementById('speaker-devices'); 
  var ringtoneDevices = document.getElementById('ringtone-devices');
  var outputVolumeBar = document.getElementById('output-volume');
  var inputVolumeBar = document.getElementById('input-volume');
  var volumeIndicators = document.getElementById('volume-indicators');
  var check_phone_number = document.getElementById('phone-number');
  var capability_token = document.getElementById('capability_token').value; 
  
  var dialer_call_duration = document.getElementById('dialer_call_duration').value;
  var dialer_country_code_exists_in_package = document.getElementById('dialer_country_code_exists_in_package').value;
  
  // log messages
  var log_connecting = document.getElementById('log_connecting').value; 
  var verifying_identification = document.getElementById('verifying_identification').value; 
  var identity = document.getElementById('identity').value; 
  var device_is_ready_to_make_calls = document.getElementById('device_is_ready_to_make_calls').value; 
  var device_error = document.getElementById('device_error').value; 
  var successfully_established_call = document.getElementById('successfully_established_call').value; 
  var call_ended_at = document.getElementById('call_ended_at').value; 
  var something_went_wrong = document.getElementById('something_went_wrong').value; 
  var no_call_to_hangup = document.getElementById('no_call_to_hangup').value; 
  var verification_failed = document.getElementById('verification_failed').value; 
  var please_enter_a_valid_phone_number = document.getElementById('please_enter_a_valid_phone_number').value; 

  log(log_connecting);
  $.getJSON(`${capability_token}`)
  //Paste URL HERE
    .done(function (data) {
      log(verifying_identification);

      // Setup Twilio.Device
      Twilio.Device.setup(data.token);

      log(identity + ': ' + data.identity);

      Twilio.Device.ready(function (device) {
        log(device_is_ready_to_make_calls);
        document.getElementById('call-controls').style.display = 'block';
      });

      Twilio.Device.error(function (error) {
        log(device_error + ': ' + error.message);
      });

      Twilio.Device.connect(function (conn) {
        log(successfully_established_call);
        document.getElementById('button-call').style.display = 'none';
        document.getElementById('button-hangup').style.display = 'inline';
        volumeIndicators.style.display = 'block';
        bindVolumeIndicators(conn);
      });

      Twilio.Device.disconnect(function (conn) {
        log(call_ended);
        document.getElementById('button-call').style.display = 'inline';
        document.getElementById('button-hangup').style.display = 'none';
        document.getElementById('call-name').innerHTML = call_ended;
        volumeIndicators.style.display = 'none';

        /**
         * Call end Time
         */

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

    // check if session storage has dialer_session_uuid
    if (sessionStorage.getItem('dialer_session_uuid') != null) {

      // ajax request to store the contact lead
      $.ajax({
          url: dialer_call_duration,
          type: "POST",
          data: {
              dialer_session_uuid: sessionStorage.getItem('dialer_session_uuid'),
              end_at: 'now',
          },
          success: function (data) {
            // get current time
            var current_time = new Date();
            var time = current_time.getHours() + ":" + current_time.getMinutes() + ":" + current_time.getSeconds();
            // log the call start time
              toastr.info(call_ended_at + ' ' + time);
              log(call_ended_at + ' ' + time);
              sessionStorage.removeItem('dialer_session_uuid');
          },
          error: function (error) {
              toastr.remove();
              toastr.error(something_went_wrong);
              log(something_went_wrong);
          }
      });

      
    }else{
      toastr.warning(no_call_to_hangup);
      log(no_call_to_hangup);
      return;
    }

    /**
    * Call end Time::ENDS
    */

      });

      setClientNameUI(data.identity);

      Twilio.Device.audio.on('deviceChange', updateAllDevices);

      // Show audio selection UI if it is supported by the browser.
      if (Twilio.Device.audio.isSelectionSupported) {
        document.getElementById('output-selection').style.display = 'block';
      }
    })
    .fail(function () {
      log(verification_failed);
    });

  // Bind button to make call
  document.getElementById('button-call').onclick = function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Check the phone number is not empty
     */
    if (check_phone_number.value === '') {
      toastr.remove();
      toastr.error(please_enter_a_valid_phone_number);
      log(please_enter_a_valid_phone_number);
      return;
    }

    /**
     * EXTRA CHARGE
     */
    $.ajax({
      url: dialer_country_code_exists_in_package,
      type: "POST",
      data: {
        phone: document.getElementById('phone-number').value,
      },
      success: function(data){

        if (data.status === 'error') { // if the call was successful
            toastr.error(data.message);
            log('warning: ' + data.message);

            // if unsuppoted number hang up the call
            if (data.message === 'Unsupported number.') {
              Twilio.Device.disconnectAll();
              toastr.info('Hanging up...');
              log(document.getElementById('phone-number').value + ' is not a supported number');
              log('Hanging up...');
            }

        }// if the call was successful
      }
    });

    /**
     * EXTRA CHARGE::ENDS
     */

    // get the phone number to connect the call to
    document.getElementById('call-name').innerHTML = 'Calling';
    document.getElementById('call-number').innerHTML = document.getElementById('phone-number').value;
    var params = {
      To: document.getElementById('phone-number').value
    };

    // check if session storage has dialer_session_uuid
    if (sessionStorage.getItem('dialer_session_uuid') === null) { // if not, create a new one
      sessionStorage.setItem('dialer_session_uuid', uuidv4()); // set the session uuid

      // ajax request to store the contact lead
      $.ajax({
          url: dialer_call_duration,
          type: "POST",
          data: {
              dialer_session_uuid: sessionStorage.getItem('dialer_session_uuid'),
              phone: document.getElementById('phone-number').value,
              start_at: 'now',
          },
          success: function (data) { 
            // get current time
            
            if (data.status === 'success') { // if the call was successful
              var current_time = new Date();
              var time = current_time.getHours() + ":" + current_time.getMinutes() + ":" + current_time.getSeconds();
              // log the call start time
              toastr.info('Calling ' + params.To);
              toastr.info('Call started at ' + time);
              log('Calling ' + params.To);
              log('Call started at ' + time);

              storeCallData(sessionStorage.getItem('dialer_session_uuid'), // session
                            params.From, // my number
                            params.To, // caller number
                            pickupAndhangupTime, // pick_up_time
                            null, // hang_up_time
                            null, // record_file 
                            'dialed'); // status

              Twilio.Device.connect(params);

            }else{ // if the call was not successful
              sessionStorage.removeItem('dialer_session_uuid', uuidv4());
              toastr.error(data.message);
              log('Error: ' + data.message);
              return false;
            } // end of if data.status
          }, // end of success
          error: function (error) { // if the call was not successful
              sessionStorage.removeItem('dialer_session_uuid', uuidv4());
              toastr.error(error.message);
              log(something_went_wrong);
              return false;
          } // end of error
      }); // end of ajax request

    }else{ // if session storage has dialer_session_uuid
      toastr.warning('You are already in a call');
      log('warning: You are already in a call!');
      return false;
    } // end of if session storage has dialer_session_uuid

  };

  // Bind button to hangup call
  document.getElementById('button-hangup').onclick = function () {
    document.getElementById('call-name').innerHTML = 'Call Ended';
    document.getElementById('call-number').innerHTML = document.getElementById('phone-number').value;

    $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

    // check if session storage has dialer_session_uuid
    if (sessionStorage.getItem('dialer_session_uuid') != null) {

      // ajax request to store the contact lead
      $.ajax({
          url: dialer_call_duration,
          type: "POST",
          data: {
              dialer_session_uuid: sessionStorage.getItem('dialer_session_uuid'),
              end_at: 'now',
          },
          success: function (data) {
            console.log(data);
            // get current time
            var current_time = new Date();
            var time = current_time.getHours() + ":" + current_time.getMinutes() + ":" + current_time.getSeconds();
            // log the call start time
              toastr.info('Call ended at ' + time);
              log('Call ended at ' + time);

              storeCallData(sessionStorage.getItem('dialer_session_uuid'), // session
                            null, // my number
                            null, // caller number
                            null, // pick_up_time
                            pickupAndhangupTime(), // hang_up_time
                            null, // record_file 
                            'picked'); // status

              sessionStorage.removeItem('dialer_session_uuid');
          },
          error: function (error) {
              toastr.remove();
              toastr.error(something_went_wrong);
              log(something_went_wrong);
          }
      });

      
    }else{
      toastr.warning(no_call_to_hangup);
      log('warning: No call to hangup!');
      return;
    }

    toastr.info('Hanging up...');
    log('Hanging up...');
    Twilio.Device.disconnectAll();
  };

  document.getElementById('get-devices').onclick = function() {
    navigator.mediaDevices.getUserMedia({ audio: true })
      .then(updateAllDevices);
  };

  speakerDevices.addEventListener('change', function() {
    var selectedDevices = [].slice.call(speakerDevices.children)
      .filter(function(node) { return node.selected; })
      .map(function(node) { return node.getAttribute('data-id'); });
    
    Twilio.Device.audio.speakerDevices.set(selectedDevices);
  });

  ringtoneDevices.addEventListener('change', function() {
    var selectedDevices = [].slice.call(ringtoneDevices.children)
      .filter(function(node) { return node.selected; })
      .map(function(node) { return node.getAttribute('data-id'); });
    
    Twilio.Device.audio.ringtoneDevices.set(selectedDevices);
  });

  function bindVolumeIndicators(connection) {
    connection.volume(function(inputVolume, outputVolume) {
      var inputColor = 'red';
      if (inputVolume < .50) {
        inputColor = 'green';
      } else if (inputVolume < .75) {
        inputColor = 'yellow';
      }

      inputVolumeBar.style.width = Math.floor(inputVolume * 300) + 'px';
      inputVolumeBar.style.background = inputColor;

      var outputColor = 'red';
      if (outputVolume < .50) {
        outputColor = 'green';
      } else if (outputVolume < .75) {
        outputColor = 'yellow';
      }

      outputVolumeBar.style.width = Math.floor(outputVolume * 300) + 'px';
      outputVolumeBar.style.background = outputColor;
    });
  }

  function updateAllDevices() {
    updateDevices(speakerDevices, Twilio.Device.audio.speakerDevices.get());
    updateDevices(ringtoneDevices, Twilio.Device.audio.ringtoneDevices.get());
  }
});

// Update the available ringtone and speaker devices
function updateDevices(selectEl, selectedDevices) {
  selectEl.innerHTML = '';
  Twilio.Device.audio.availableOutputDevices.forEach(function(device, id) {
    var isActive = (selectedDevices.size === 0 && id === 'default');
    selectedDevices.forEach(function(device) {
      if (device.deviceId === id) { isActive = true; }
    });

    var option = document.createElement('option');
    option.label = device.label;
    option.setAttribute('data-id', id);
    if (isActive) {
      option.setAttribute('selected', 'selected');
    }
    selectEl.appendChild(option);
  });
}

// Activity log
function log(message) {
  var logDiv = document.getElementById('log');
  logDiv.innerHTML += '<p>⇢ ' + message + '</p>';
  logDiv.scrollTop = logDiv.scrollHeight;
}

// Set the client name in the UI
function setClientNameUI(clientName) {
  var div = document.getElementById('client-name');
}


function MakeVoiceCall(sl_id = null, st_id = null, phone_number, phone, campaign, store_data = false, make_call, status = 'd') {

  var dialer_call_duration = document.getElementById('dialer_call_duration').value;
  var dialer_country_code_exists_in_package = document.getElementById('dialer_country_code_exists_in_package').value;

  if (make_call != null) { //  if the call was not successful
    
    document.getElementById('call-name').innerHTML = 'Calling';
    document.getElementById('phone-number').value = phone_number;
    document.getElementById('call-number').innerHTML = phone_number;
    var params = {
      To: document.getElementById('phone-number').value
    };

    // check if session storage has dialer_session_uuid
    if (sessionStorage.getItem('dialer_session_uuid') === null) { // if not, create a new one
      if (make_call == true) {
        sessionStorage.setItem('dialer_session_uuid', uuidv4()); // set the session uuid
      }

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      /**
     * EXTRA CHARGE
     */
      $.ajax({
        url: dialer_country_code_exists_in_package,
        type: "POST",
        data: {
          phone: document.getElementById('phone-number').value,
        },
        success: function(data){

          if (data.status === 'error') { // if the call was successful
              toastr.error(data.message);
              // Swal.fire(data.message);
              log('warning: ' + data.message);
          }// if the call was successful
        }
      });
    /**
     * EXTRA CHARGE::ENDS
     */

      // ajax request to store the contact lead
      $.ajax({
          url: dialer_call_duration,
          type: "POST",
          data: {
              dialer_session_uuid: sessionStorage.getItem('dialer_session_uuid'),
              phone: phone_number,
              start_at: 'now',
          },
          success: function (data) { 
            // get current time
            
            if (data.status === 'success') { // if the call was successful

              if (make_call == true) {
                var current_time = new Date();
                var time = current_time.getHours() + ":" + current_time.getMinutes() + ":" + current_time.getSeconds();
                // log the call start time
                toastr.info('Calling ' + params.To);
                toastr.info('Call started at ' + time);
                log('Calling ' + params.To);
                log('Call started at ' + time);
                Twilio.Device.connect(params);
              }

              if (store_data) { // if store_data is true, then store the data in the database
                var url = $('#dashboard_campaign_voice_lead').val();

                  $.ajaxSetup({
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      }
                  });
                  
                  // ajax request to store the contact lead
                  $.ajax({
                      url: url,
                      type: "POST",
                      data: { // store the data in the database
                          phone: phone,
                          number: phone_number,
                          campaign_id: campaign,
                          status: status
                      },
                      success: function (data) { // if the call was successful
                          toastr.info(data.success);
                          log(data.success);
                          // change the selected button class
                          if (make_call == true) {
                            $('#' + sl_id).removeClass('btn-secondary');
                            $('#' + sl_id).addClass('btn-success');
                          }else{
                            $('#' + st_id + status).removeClass('btn-secondary');
                            $('#' + st_id + status).addClass('btn-info');
                          }
                      },
                      error: function (data) {
                          toastr.error(something_went_wrong);
                          log(something_went_wrong);
                      }
                  });
              } // end of if store_data

            }else{ // if the call was not successful
              sessionStorage.removeItem('dialer_session_uuid', uuidv4());
              toastr.error(data.message);
              return false;
            } // end of if data.status
          }, // end of success
          error: function (error) { // if the call was not successful
              sessionStorage.removeItem('dialer_session_uuid', uuidv4());
              toastr.error(something_went_wrong);
              log(something_went_wrong);
              return false;
          } // end of error
      }); // end of ajax request

    }else{ // if session storage has dialer_session_uuid
      toastr.warning('You are already in a call');
      log('warning: You are already in a call');
      return false;
    } // end of if session storage has dialer_session_uuid
    
  }else{ // end of if make_call
    toastr.warning('Please make a call first!');
    log('warning: Please make a call first!');
    return false;
  } // end of if make_call

}

function storeCallData(get_caller_uuid_session,
                       my_number, 
                       caller_number, 
                       pick_up_time, 
                       hang_up_time, 
                       record_file = null, 
                       status) {
    var url = document.getElementById('create_call_history_url').value;

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            get_caller_uuid_session: get_caller_uuid_session,
            my_number: my_number,
            caller_number: caller_number,
            pick_up_time: pick_up_time,
            hang_up_time: hang_up_time,
            record_file: record_file,
            status: status
        },
        dataType: 'json',
        success: function(data) {
            console.log(data);
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}

/**
 * The function generates a version 4 UUID (Universally Unique Identifier) using random values.
 * @returns a version 4 UUID (Universally Unique Identifier).
 */
function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

/**
 * The function returns the current time in a formatted string.
 * @returns the current time in the format "YYYY-MM-DD HH:MM:SS".
 */
function pickupAndhangupTime() {
  const now = new Date();
  const formattedTime = now.toISOString().slice(0, 19).replace('T', ' ');
  return formattedTime;
}

function resetCallSession()
{
  sessionStorage.removeItem("dialer_session_uuid");
  toastr.info('All call session has been reset');
}