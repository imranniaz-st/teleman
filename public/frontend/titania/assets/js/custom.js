"use strict"

function SubmitBraintree(){
    $('#braintree_form').submit();
}

function SubmitSSL(){
    $('#ssl_form').submit();
}

function SubmitFLUTTERWAVE(){
    $('#flutterwave_form').submit();
}

function SubmitPAYSTACK(){
    $('#paystack_form').submit();
}

function SubmitINSTAMOJO(){
    $('#instamojo_form').submit();
}

function SubmitStripe(){
    window.location.href = $('#stripe_form').val();
}

function SubmitRAZORPAY(){
    window.location.href = $('#razorpay_form').val();
}

function SubmitSQUAD() {

  var squad_success_url = document.getElementById("squad_success_url").value;
  var squad_cancel_url = document.getElementById("squad_cancel_url").value;
  var squad_merchant_currency = document.getElementById("squad_merchant_currency").value;

  const squadInstance = new squad({

    onClose: () => window.location = squad_cancel_url,
    onLoad: () => console.log('Widget Loaded.'),
    onSuccess: () => window.location = squad_success_url,
    key: document.getElementById("sandbox_pk").value,
    //Change key (test_pk_sample-public-key-1) to the key on your Squad Dashboard
    email: document.getElementById("email-address").value,
    amount: document.getElementById("amount").value * 100,
    //Enter amount in Naira or Dollar (Base value Kobo/cent already multiplied by 100)
    currency_code: squad_merchant_currency
  });
  squadInstance.setup();
  squadInstance.open();
}

function Loader() {
  $('.loading').removeClass('d-none');
}

function StoreNewsletter() {

  var url = $('#newsletter_url').val();
  var name = $('#newsletter_name').val();
  var phone = $('#phone').val();
  var email = $('#newsletter_email').val();
  var phone_number = phone;

  if (name == '') {
    $('#newsletter_name').focus();
    toastr.error('Name is required');
    return false;
  }

  if (phone == '') {
    $('#phone').focus();
    toastr.error('Phone is required');
    return false;
  }

  if (email == '') {
    $('#newsletter_email').focus();
    toastr.error('Email is required');
    return false;
  }

  var data = {
    email: email,
    name: name,
    phone: phone_number,
    _token: $('meta[name="csrf-token"]').attr('content')
  };

  $.ajax({
    url: url,
    type: 'POST',
    data: data,
    success: function (response) {
      if (response == 'success') {
        $('#newsletter_name').val('');
        $('#phone').val('');
        $('#newsletter_email').val('');
        toastr.success('You have successfully subscribed to our newsletter!');
      } else if (response == 'exist') {
        toastr.error('You have already subscribed to our newsletter!');
      } else {
        toastr.error('Something went wrong');
      }
    }
  });
}

function validateCheckbox() {
      // Get the checkbox element by its id
      var checkbox = document.getElementById("termsCheckbox");

      // Check if the checkbox is checked
      if (checkbox.checked) {
          // If the checkbox is checked, proceed with your desired action (e.g., redirect to the next page)
          // For example: window.location.href = "next_page.html";
          console.log("Checkbox is checked. Proceeding...");
      } else {
          // If the checkbox is not checked, show an error message or take appropriate action
          alert("Please agree to the Terms and Conditions to continue.");
          // You can show an error message to the user or take any other action as needed.
      }
  }