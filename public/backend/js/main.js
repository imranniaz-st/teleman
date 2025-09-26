"use strict"

$(document).on("change", ".uploadProfileInput", function () {
  var triggerInput = this;
  var currentImg = $(this).closest(".pic-holder").find(".pic").attr("src");
  var holder = $(this).closest(".pic-holder");
  var wrapper = $(this).closest(".profile-pic-wrapper");
  $(wrapper).find('[role="alert"]').remove();
  var files = !!this.files ? this.files : [];
  if (!files.length || !window.FileReader) {
    return;
  }
  if (/^image/.test(files[0].type)) {
    // only image file
    var reader = new FileReader(); // instance of the FileReader
    reader.readAsDataURL(files[0]); // read the local file

    reader.onloadend = function () {
      $(holder).addClass("uploadInProgress");
      $(holder).find(".pic").attr("src", this.result);
      $(holder).append(
        '<div class="upload-loader"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div></div>'
      );

      // Dummy timeout; call API or AJAX below
      setTimeout(() => {
        $(holder).removeClass("uploadInProgress");
        $(holder).find(".upload-loader").remove();
        // If upload successful
        if (Math.random() < 0.9) {
          $(wrapper).append(
            '<div class="snackbar show" role="alert"><i class="fa fa-check-circle text-success"></i> Image uploaded successfully</div>'
          );

          // Clear input after upload
          $(triggerInput).val("");

          setTimeout(() => {
            $(wrapper).find('[role="alert"]').remove();
          }, 3000);
        } else {
          $(holder).find(".pic").attr("src", currentImg);
          $(wrapper).append(
            '<div class="snackbar show" role="alert"><i class="fa fa-times-circle text-danger"></i> There is an error while uploading! Please try again later.</div>'
          );

          // Clear input after upload
          $(triggerInput).val("");
          setTimeout(() => {
            $(wrapper).find('[role="alert"]').remove();
          }, 3000);
        }
      }, 1500);
    };
  } else {
    $(wrapper).append(
      '<div class="alert alert-danger d-inline-block p-2 small" role="alert">Please choose the valid image.</div>'
    );
    setTimeout(() => {
      $(wrapper).find('role="alert"').remove();
    }, 3000);
  }
});

/**
 * SUBMIT FORM
 */

function submitForm() {
  $('.validate-form').submit();
}

/**
 * DARK MODE
 */

(function() {
    var dark_mode = localStorage.getItem('dark-mode');

    if (dark_mode == 'on') {
        document.getElementById('body').classList.add('dark-mode');
    }else{
        document.getElementById('body').classList.remove('dark-mode');
    }
})();

/**
 * CHECK SUBDOMAIN AJAX
 */

function checkSubdomain() {
  var subdomain = $('#subdomain').val();
  var base_url = $('#base_url').val();
  var domain = subdomain + '.' + base_url;
  var url = $('#check_domain_url').val();

  //Build your expression
  var regex = new RegExp("^[a-zA-Z]+[a-zA-Z0-9\\-]*$");
  //Test your current value
  var check_subdomain = regex.test(subdomain);

  if (check_subdomain == true) {

    $.ajax({
      url: url,
      type: 'GET',
      dataType: 'json',
      data: {
        domain: domain
      },
      success: function (data) {
        if (data.status == 'success') {
          $('#subdomain').removeClass('is-invalid');
          $('#subdomain').addClass('is-valid');
          $('.invalid-subdomain').addClass('d-none');
          $('#subdomain').next().find('.invalid-feedback').remove();
        } else {
          $('#subdomain').removeClass('is-valid');
          $('#subdomain').addClass('is-invalid');
          $('.invalid-subdomain').addClass('d-none');
          $('#subdomain').next().find('.valid-feedback').remove();
        }
      }
    });

  } else {
    $('#subdomain').removeClass('is-valid');
    $('#subdomain').addClass('is-invalid');
    $('.invalid-subdomain').removeClass('d-none');
    $('.invalid-subdomain').addClass('is-invalid');
    $('.invalid-feedback').addClass('d-none');
  }

}

function ChangeMode() {

  var $body = $('body');

  if ($body.hasClass('dark-mode')) {
    localStorage.setItem('dark-mode', 'off');
  } else {
    localStorage.setItem('dark-mode', 'on');
  }

  var dark_mode = localStorage.getItem('dark-mode');
}

function Loader() {
  $('.loading').removeClass('d-none');
}

// saas frontend fix
let drpparent = document.getElementById('drpParent');
let adddrpdown = document.getElementById('dropdown_menu_cc');

if (drpparent) {
  drpparent.addEventListener("click", function () {
    adddrpdown.classList.toggle('show');
  });
}

let admin_dropdown_parent = document.getElementById('admin_dropdown_parent');
let admin_dropdown_child = document.getElementById('admin_dropdown_child');

if (admin_dropdown_parent) {

  admin_dropdown_parent.addEventListener("click", function () {
    admin_dropdown_child.classList.toggle('d-block');
  });
}

let toggle_trigger_nikka = document.getElementById('toggle_trigger_nikka');
let sidebar_nk_aside = document.getElementById('sidebar_nk_aside');

if (toggle_trigger_nikka) {

  toggle_trigger_nikka.addEventListener("click", function () {
    sidebar_nk_aside.classList.toggle('content-active');
    toggle_trigger_nikka.classList.toggle('active');
  });
}



function PaymentFormCheckBox(value)
{

  if (value == 0) {
    value = 1;
    $('#payment-off').val(value);
    $('.PaymentForm').removeClass('d-none');
  } else {
    value = 0;
    $('#payment-off').val(value);
    $('.PaymentForm').addClass('d-none');
  }

}


/**
 * DEMO
 */

function demoAdmin(){
  const email = 'admin@mail.com';
  const password = '12345678';

  $('#email').val(email);
  $('#password').val(password);
  $('#login_form').submit();
}

function demoCustomer(){
  const email = 'customer@mail.com';
  const password = '12345678';

  $('#email').val(email);
  $('#password').val(password);
  $('#login_form').submit();
}

/**
 * Floating button
 */

$(document).ready(function(){
        $('.floatingButton').on('click',
            function(e){
                e.preventDefault();
                $(this).toggleClass('open');
                if($(this).children('.fa').hasClass('fa-plus'))
                {
                    $(this).children('.fa').removeClass('fa-plus');
                    $(this).children('.fa').addClass('fa-close');
                } 
                else if ($(this).children('.fa').hasClass('fa-close')) 
                {
                    $(this).children('.fa').removeClass('fa-close');
                    $(this).children('.fa').addClass('fa-plus');
                }
                $('.floatingMenu').stop().slideToggle();
            }
        );
        $(this).on('click', function(e) {
          
            var container = $(".floatingButton");
            // if the target of the click isn't the container nor a descendant of the container
            if (!container.is(e.target) && $('.floatingButtonWrap').has(e.target).length === 0) 
            {
                if(container.hasClass('open'))
                {
                    container.removeClass('open');
                }
                if (container.children('.fa').hasClass('fa-close')) 
                {
                    container.children('.fa').removeClass('fa-close');
                    container.children('.fa').addClass('fa-plus');
                }
                $('.floatingMenu').hide();
            }
          
            // if the target of the click isn't the container and a descendant of the menu
            if(!container.is(e.target) && ($('.floatingMenu').has(e.target).length > 0)) 
            {
                $('.floatingButton').removeClass('open');
                $('.floatingMenu').stop().slideToggle();
            } 
        });
    });

/**
 * ToggleMobileSideBar
 */
function ToggleMobileSideBar()
{
  $(".nk-sidebar-mobile").toggleClass("nk-sidebar-active");
}

/* Showing the modal on page load. */
$(document).ready(function(){
      $("#whatsNewModal").modal('show');
});

/**
 * It copies the value of the key to the value of the translation.
 */
function copy() {
    $("#translation-table > tbody  > tr").each(function (index, tr) {
        $(tr).find(".value").val($(tr).find(".key").text());
    });
}

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

/* The code `$(".audio").mb_miniPlayer({...});` is initializing a mini audio player plugin on all
elements with the class "audio". The plugin is configured with the following options: */
$(".audio").mb_miniPlayer({
    width:50,
    inLine:true,
    id3:true,
    addShadow:false,
    pauseOnWindowBlur: false,
    downloadPage:null
});

function createIncomingCallWidget() {
    // Create the widget container
    var widget = $('<div>', { id: 'incomingCallWidget', class: 'incoming-call-widget' }).hide();

    // Add an icon
    var callIcon = $('<div>', { class: 'call-icon' }).html('📞'); // Using an emoji as an example
    widget.append(callIcon);

    // Create the caller info section with updated text
    var callerInfo = $('<div>', { class: 'caller-info' }).text('You have a new incoming call...');
    widget.append(callerInfo);

    // Append the widget to the body
    $('body').append(widget);
}

createIncomingCallWidget();

function styleIncomingCallWidget() {
    $('#incomingCallWidget').css({
        'position': 'fixed',
        'bottom': '10px',
        'right': '10px',
        'z-index': '1000',
        'background-color': 'white',
        'padding': '10px',
        'border-radius': '5px',
        'box-shadow': '0 2px 5px rgba(0,0,0,0.3)',
        'display': 'none',
        'align-items': 'center'
    });

    $('.call-icon, .caller-info').css({
        'display': 'inline-block',
        'vertical-align': 'middle'
    });

    $('.call-icon').css({
        'margin-right': '10px',
        'animation': 'shake 0.5s infinite'
    });
}

// Add keyframes for shake animation
$('<style>')
    .prop('type', 'text/css')
    .html('@keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }')
    .appendTo('head');

styleIncomingCallWidget();

$(window).on('storage', function(event) {
    if (event.originalEvent.key === 'incoming_call') {
        if (event.originalEvent.newValue === 'true') {
            $('#incomingCallWidget').show();
        } else if (event.originalEvent.newValue === 'false') {
            $('#incomingCallWidget').hide();
        }
    }
});
