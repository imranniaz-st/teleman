<script src="{{ asset('frontend/'. active_theme() .'/assets/js/app.js') }}"></script>
<script src="{{ asset('frontend/'. active_theme() .'/assets/js/core.js') }}"></script>
<script src="{{ asset('js/toastr.js') }}"></script>
<script src="{{ asset('js/google-translate.js') }}"></script>
<script src="{{ asset('frontend/'. active_theme() .'/assets/js/custom.js') }}"></script>

<x:notify-messages />
@notifyJs

<script>
    "use strict"
// @php
//     echo custom_css_script('frontend_js');
// @endphp

document.addEventListener('htmx:afterOnLoad', function() {
    document.querySelector('.pageloader').classList.add('d-none');
    document.querySelector('.infraloader').classList.remove('is-active');
});

</script>
