var dialer = {
  view: 'dialpad',
}
$(function () {
//   toggleCallTimer(true);
  var speakerDevices = document.getElementById('speaker-devices');
  var ringtoneDevices = document.getElementById('ringtone-devices');
  var outputVolumeBar = document.getElementById('output-volume');
  var inputVolumeBar = document.getElementById('input-volume');
  var volumeIndicators = document.getElementById('volume-indicators');
  var capability_token = document.getElementById('capability_token').value;
  var my_number = document.getElementById('my_number').value;

  var device;
 
  log('Requesting Capability Token...');
  $.getJSON(`${capability_token}`, function (data) {
     getQueueList(my_number);
    console.log(data);
    log('Capability Token: ' + data.token);
      console.log('Token: ' + data.token);

      // Setup Twilio.Device
      device = new Twilio.Device(data.token, {
        // Set Opus as our preferred codec. Opus generally performs better, requiring less bandwidth and
        // providing better audio quality in restrained network conditions. Opus will be default in 2.0.
        codecPreferences: ["opus", "pcmu"],
        // Use fake DTMF tones client-side. Real tones are still sent to the other end of the call,
        // but the client-side DTMF tones are fake. This prevents the local mic capturing the DTMF tone
        // a second time and sending the tone twice. This will be default in 2.0.
        fakeLocalDTMF: true,
        // Use `enableRingingState` to enable the device to emit the `ringing`
        // state. The TwiML backend also needs to have the attribute
        // `answerOnBridge` also set to true in the `Dial` verb. This option
        // changes the behavior of the SDK to consider a call `ringing` starting
        // from the connection to the TwiML backend to when the recipient of
        // the `Dial` verb answers.
        enableRingingState: true
      });

      device.on("ready", function(device) {
        log("Twilio.Device Ready!");
        document.getElementById("call-controls").style.display = "none";
        $("#dialer-message").html('Active');
      });

      device.on("error", function(error) {
        log("Twilio.Device Error: " + error.message);
      });

      device.on("connect", function(conn) {
        log("Successfully established call!");
        volumeIndicators.style.display = "block";
        dialer.incall = true;
        showCallButtons();
        $("button").on('click', function(){
          
        });
        bindVolumeIndicators(conn);
      });

      device.on("disconnect", function(conn) {
        log("Call ended.");
        localStorage.setItem('incoming_call', false);
        document.getElementById("dialerpad").classList.add('d-none');
        volumeIndicators.style.display = "none";
        dialer.direction = '';
        dialer.view = 'dialer';
        dialer.number = '';
        dialer.incall = false;
        toggleCallTimer();
        switchView();
      });

      device.on("incoming", function(conn) {
          console.log("Incoming connection from " + conn.parameters.From);
          localStorage.setItem('incoming_call', true);
          document.getElementById("dialerpad").classList.remove('d-none');
          dialer.direction = 'incoming';
          dialer.view = 'calling';
          dialer.number = conn.parameters.From;
          dialer.conn = conn;
          switchView();
          
          $("#display-number").html(dialer.number);

          /* The above code is a code snippet written in JavaScript. It seems to be a function call or
          method call to a function named `find_contact` with a parameter of `'+880153314902'`.
          However, without seeing the implementation of the `find_contact` function, it is not
          possible to determine what the code is doing exactly. The ` */
          find_contact(dialer.number);

          sessionStorage.setItem('caller_uuid_session', uuidv4()); // set the session uuid
          var get_caller_uuid_session = sessionStorage.getItem('caller_uuid_session'); // get the session uuid

          storeCallData(get_caller_uuid_session, // session
                        my_number, // my number
                       dialer.number, // caller number
                       null, // pick_up_time
                       null, // hang_up_time
                       null, // record_file 
                       'missed'); // status

      });

      setClientNameUI(data.identity);

      device.audio.on("deviceChange", updateAllDevices.bind(device));

      // Show audio selection UI if it is supported by the browser.
      if (device.audio.isOutputSelectionSupported) {
        document.getElementById("output-selection").style.display = "block";
      }
    })
    .catch(function (err) {
      console.log(err);
      log("Could not get a token from server!");
    });

  // Bind button to make call
  document.getElementById("button-call").onclick = function() {
    // get the phone number to connect the call to
    if (dialer.direction === 'incoming') {
      dialer.conn.accept();
      dialer.incall = true;

      var get_caller_uuid_session = sessionStorage.getItem('caller_uuid_session'); // get the session uuid

      storeCallData(get_caller_uuid_session, // session
                    my_number, // my number
                    dialer.number, // caller number
                    pickupAndhangupTime(), // pick_up_time or incoming call time
                    null, // hang_up_time
                    null, // record_file 
                    'picked'); // status
                    
    agentAvailableStatus(0); // make agent unavailable
                    
    toggleCallTimer(true);

      return;
    }
    var params = {
      To: document.getElementById("phone-number").value
    };
    if(!params.To){return;}
      console.log("Calling " + params.To + "...");

    if (device) {
      var outgoingConnection = device.connect(params);
       outgoingConnection.on("ringing", function() {
        log("Ringing...");
        dialer.direction = 'calling';
        dialer.view = 'calling';
        dialer.number = params.To;
        toggleCallTimer(true);
        switchView();
      });
    }
  };

  // Bind button to hangup call
 document.getElementById("button-hangup").onclick = function() {
    log("Hanging up...");
    localStorage.setItem('incoming_call', false);
    document.getElementById("dialerpad").classList.add('d-none');

    var get_caller_uuid_session = sessionStorage.getItem('caller_uuid_session'); // get the session uuid

    storeCallData(get_caller_uuid_session, // session
                  my_number, // my number
                  dialer.number, // caller number
                  null, // pick_up_time or incoming call time
                  pickupAndhangupTime(), // hang_up_time
                  null, // record_file 
                  'hanged'); // status
                  
    agentAvailableStatus(1); // make agent available
    
    device.activeConnection().reject();
    dialer.direction = '';
    dialer.view = 'dialer';
    dialer.number = '';
    dialer.incall = false;
    toggleCallTimer();
    switchView();
    if (device) {
      device.disconnectAll();
    }

  };

  document.getElementById("get-devices").onclick = function() {
    navigator.mediaDevices
      .getUserMedia({ audio: true })
      .then(updateAllDevices.bind(device));
  };

  speakerDevices.addEventListener("change", function() {
    var selectedDevices = [].slice
      .call(speakerDevices.children)
      .filter(function(node) {
        return node.selected;
      })
      .map(function(node) {
        return node.getAttribute("data-id");
      });

    device.audio.speakerDevices.set(selectedDevices);
  });

  ringtoneDevices.addEventListener("change", function() {
    var selectedDevices = [].slice
      .call(ringtoneDevices.children)
      .filter(function(node) {
        return node.selected;
      })
      .map(function(node) {
        return node.getAttribute("data-id");
      });

    device.audio.ringtoneDevices.set(selectedDevices);
  });

  function bindVolumeIndicators(connection) {
    connection.on("volume", function(inputVolume, outputVolume) {
      var inputColor = "red";
      if (inputVolume < 0.5) {
        inputColor = "green";
      } else if (inputVolume < 0.75) {
        inputColor = "yellow";
      }

      inputVolumeBar.style.width = Math.floor(inputVolume * 300) + "px";
      inputVolumeBar.style.background = inputColor;

      var outputColor = "red";
      if (outputVolume < 0.5) {
        outputColor = "green";
      } else if (outputVolume < 0.75) {
        outputColor = "yellow";
      }

      outputVolumeBar.style.width = Math.floor(outputVolume * 300) + "px";
      outputVolumeBar.style.background = outputColor;
    });
  }

  function updateAllDevices() {
    updateDevices(speakerDevices, device.audio.speakerDevices.get());
    updateDevices(ringtoneDevices, device.audio.ringtoneDevices.get());
  }

  // Update the available ringtone and speaker devices
  function updateDevices(selectEl, selectedDevices) {
    selectEl.innerHTML = "";

    device.audio.availableOutputDevices.forEach(function(device, id) {
      var isActive = selectedDevices.size === 0 && id === "default";
      selectedDevices.forEach(function(device) {
        if (device.deviceId === id) {
          isActive = true;
        }
      });

      var option = document.createElement("option");
      option.label = device.label;
      option.setAttribute("data-id", id);
      if (isActive) {
        option.setAttribute("selected", "selected");
      }
      selectEl.appendChild(option);
    });
  }

  // Activity log
  function log(message) {
    var logDiv = document.getElementById("log");
    logDiv.innerHTML += "<p>&gt;&nbsp;" + message + "</p>";
    logDiv.scrollTop = logDiv.scrollHeight;
  }

  // Set the client name in the UI
  function setClientNameUI(clientName) {
    var div = document.getElementById("client-name");
    div.innerHTML = "Your client name: <strong>" + clientName + "</strong>";
  }

  function switchView(){
    console.log(dialer);

      if(dialer.number){
        $("#display-number").html(dialer.number);
        $("#call-direction").html(dialer.direction);
      }
      showCallButtons();
  }
  function showCallButtons(){
    var btn = '';
    if(dialer.direction){
      if(dialer.direction == 'calling' || dialer.incall){
        btn = 'reject';
      }
    }else{
      btn = 'accept';
    }
    if(btn){
      $(".call-btn").hide();
      $(".call-"+btn).show();
    }else{
      $(".call-btn").show();
    }
  }
   function toggleCallTimer(start){
    console.log('timer started');
    dialer.time = 0;
    var diaplayTime = '';
    if(start){
      var currentTime;
      var h,m,s;
      dialer.timer = setInterval(() => {
        dialer.time++;
        $("#dialer-timer").html(getTimeSpent(dialer.time));
      }, 1000);
      $("#call-direction").html('Connected');
    }else{
      $("#dialer-timer").html('');
      $("#call-direction").html('');
      clearInterval(dialer.timer);
    }
    console.log(dialer.timer);
  }
  function addPrefixZero(num){
    if(!num){
      num = 0;
    }
    return num < 10 ? '0'+num : num;
  }
  function getTimeSpent(s){
    // var s = Math.floor(ms / 1000);
    var unit = 60;
    var m = Math.floor(s / unit);
    if(m){
      s = s%unit;
    }
    var h = Math.floor(m / unit);
    if(h){
      m = m%unit;
    }
    var dTime = addPrefixZero(h)+':'+addPrefixZero(m)+':'+addPrefixZero(s);
    return dTime;
    console.log('dTime', dTime);
  }
});

/**
 * The function sends a POST request to a specified URL with call data as parameters.
 * @param identity_id - The ID of the user or identity associated with the call.
 * @param my_number - The phone number of the person who received the call.
 * @param caller_number - The phone number of the person who made the call.
 * @param pick_up_time - The time when the call was answered or picked up.
 * @param hang_up_time - The time when the call ended or was hung up.
 * @param [record_file=null] - The record_file parameter is an optional parameter that can be used to
 * store the audio recording of the call. If there is no recording, it can be set to null.
 * @param status - The status parameter is used to indicate the outcome or result of the call. It could
 * be "answered", "missed", "voicemail", "busy", or any other relevant status.
 */
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
 * The function generates a random UUID version 4 using the crypto.getRandomValues method in
 * JavaScript.
 * @returns A version 4 UUID (Universally Unique Identifier) is being returned as a string.
 */
function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
      (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

/**
 * The function returns the current time in a formatted string.
 * @returns The function `pickupAndhangupTime()` returns a string representing the current date and
 * time in the format "YYYY-MM-DD HH:mm:ss".
 */
function pickupAndhangupTime() {
  const now = new Date();
  const formattedTime = now.toISOString().slice(0, 19).replace('T', ' ');
  return formattedTime;
}

/**
 * The function uses AJAX to retrieve contact information based on a caller's phone number and displays
 * it on the webpage.
 * @param caller_number - The phone number of the caller that we want to search for in the database.
 */
function find_contact(caller_number) {
  var url = document.getElementById('find_contact_url').value;

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $.ajax({
      url: url,
      type: 'GET',
      data: {
          caller_number: caller_number,
      },
      dataType: 'json',
      success: function(data) {
        console.log(data);

        if (data.contact_info) {
          $('#no_caller_data_found').addClass('d-none');
          $('#caller_information').removeClass('d-none');

          $('#full_name').text(data.contact_info.name);
          $('#contact_country').text(data.contact_info.country);
          $('#contact_gender').text(data.contact_info.gender);
          $('#contact_dob').text(data.contact_info.dob);
          $('#contact_profession').text(data.contact_info.profession);
        }else{
          $('#no_caller_data_found').removeClass('d-none');
          $('#caller_information').addClass('d-none');
        }

      },
      error: function(xhr, status, error) {
          console.log(xhr.responseText);
      }
  });
}

$(document).ready(function() {
  /* The above code is a jQuery function that listens for a keyup event on an input field with the ID
  "search_input". When a key is pressed, it gets the value of the input field, converts it to
  lowercase, and then filters the rows of a table with the class "search-table" based on whether
  their text content contains the input value. The rows that match the search criteria are
  displayed, while the others are hidden. */
  $('#search_input').on('keyup', function() {
    var value = $(this).val().toLowerCase();
    $('.search-table tbody tr').filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });

});

// JavaScript code to enable copy functionality for the copy-cat class
document.addEventListener("DOMContentLoaded", () => {
  const copyCatElements = document.querySelectorAll(".copy-cat");

  copyCatElements.forEach((element) => {
    element.addEventListener("click", () => {
      copyTextToClipboard(element.textContent);
    });
  });
});

function copyTextToClipboard(text) {
  const tempTextArea = document.createElement("textarea");
  tempTextArea.value = text;
  document.body.appendChild(tempTextArea);
  tempTextArea.select();
  document.execCommand("copy");
  document.body.removeChild(tempTextArea);

  // You can add some feedback to the user here if you want
  console.log("Copied to clipboard!");
}

function formatDateToCustomFormat(dateString) {
  var date = new Date(dateString);
  var year = date.getFullYear();
  var month = String(date.getMonth() + 1).padStart(2, '0'); // Adding 1 to month as it is zero-indexed
  var day = String(date.getDate()).padStart(2, '0');
  var hour = String(date.getHours()).padStart(2, '0');
  var minute = String(date.getMinutes()).padStart(2, '0');
  var second = String(date.getSeconds()).padStart(2, '0');

  return `${year}-${month}-${day} ${hour}:${minute}:${second}`;
}

function waitingCountdown(targetTime) {
  var countdownInterval = setInterval(function() {
    var currentTime = new Date().getTime();
    var timeDifference = currentTime - targetTime;

    // Calculate the remaining days, hours, minutes, and seconds
    var days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
    var hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

    // Display the countdown
    $("#countdown").text(hours + "h " + minutes + "m " + seconds + "s");
  }, 1000); // Update every second (1000 milliseconds)
}

function getTimeDifference(targetDate) {
  // Convert the targetDate string to a Date object in UTC
  var targetTime = new Date(targetDate);

  // Get the current time as a Date object in UTC
  var currentTime = new Date();

  // Calculate the time difference in milliseconds
  var timeDifference = currentTime.getTime() - targetTime.getTime();

  // Convert the time difference to hours, minutes, and seconds
  var seconds = Math.floor(Math.abs(timeDifference) / 1000) % 60;
  var minutes = Math.floor(Math.abs(timeDifference) / (1000 * 60)) % 60;
  var hours = Math.floor(Math.abs(timeDifference) / (1000 * 60 * 60));

  return hours + "h " + minutes + "m " + seconds + "s";
}

/**
 * The function `agentAvailableStatus` sends an AJAX request to update the agent's availability status
 * and logs the response data or any error messages to the console.
 * @param status - The "status" parameter is the current availability status of the agent. It is used
 * to update the agent's availability status on the server.
 */
function agentAvailableStatus(status) {

  var url = document.getElementById('agent_status_update_url').value;

  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $.ajax({
        url: url,
        type: 'GET',
        data: {
            status: status
        },
        success: function(data) {
            console.log(data);
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });

}

function updateQueueList(data) {
  var userCardsHTML = '';

  // Loop through the data array and create the user card HTML for each entry
  data.forEach(function (queue, index) {
    userCardsHTML += '<li>' +
      '<div class="user-card">' +
      '<div class="user-avatar bg-dim-primary d-none d-sm-flex">' +
      '<span>' + (index + 1) + '</span>' +
      '</div>' +
      '<div class="user-info">' +
      '<span class="lead-text ff-mono">' + queue.caller_number + '</span>' +
      '<span class="sub-text ff-mono">' + getTimeDifference(formatDateToCustomFormat(queue.created_at)) + '</span>' +
      '</div>' +
      '</div>' +
      '</li>';
  });

  // Append the generated HTML to the 'getQueue' element
  $('#getQueue').html(userCardsHTML);
}

/**
 * The function `getQueueList` makes an AJAX GET request to a specified URL with a given `my_number`
 * parameter and logs the response data to the console.
 * @param my_number - The `my_number` parameter is a number that represents a specific queue. It is
 * used as a parameter in the AJAX request to retrieve the queue list from the specified URL.
 */
function getQueueList(my_number) {
      var url = document.getElementById('get_queue_list_url').value;

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url: url,
        type: 'GET',
        data: {
          my_number: my_number
        },
        success: function (data) {
          updateQueueList(data);
          $('#countQueueWaiting').text(data.length);
        },
        error: function (xhr, status, error) {
          console.log(xhr.responseText);
        }
      });
    }
    
    // Call getQueueList every 2 seconds and pass 'my_number'
    setInterval(function () {
      var number = document.getElementById('my_number').value;
      getQueueList(number);
    }, 1000); // 2000 milliseconds = 2 seconds


  // MINI AUDIO PLAYER
$(".audio").mb_miniPlayer({
    width:50,
    inLine:true,
    id3:true,
    addShadow:false,
    pauseOnWindowBlur: false,
    downloadPage:null
}); 