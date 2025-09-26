"use strict"

$('#answer-call').on('click', function() {
  if( $('input[type=tel]').val().length === 0 ) {
    return false;
  } else {
    $('#answer-call').hide();
    $('#end-call').show();
    
    var text = $('input[type=tel]');
    text.val(text.val() + ' ');
    text.focus();
  }
});

$('#end-call').on('click', function() {
  $('#end-call').hide();
  $('#answer-call').show();
  // Do business stuff here
  $('input[type=tel]').val('');
  $('input[type=tel]').focus();
});

$('#dialer').on('submit', function() {
  return false;
});

$('button').on('click', function() {
  var text = $('input[type=tel]');
  text.val(text.val() + this.value);
  text.focus();
});

$('#microphone').on('click', function() {
  $(this).find('i').toggleClass('fa-microphone fa-microphone-slash');
});

$('#volume').on('click', function() {
  $(this).find('i').toggleClass('fa-volume-up fa-volume-off');
});

$('input[type=reset]').on('click', function() {
  $('input[type=tel]').focus();
});

//this will need to reset when put into different states
window.raf = (function() {
  return window.requestAnimationFrame ||
    window.webkitRequestAnimationFrame ||
    window.mozRequestAnimationFrame ||
    function(callback) {
      window.setTimeout(callback, 1000 / 60);
    };
})();