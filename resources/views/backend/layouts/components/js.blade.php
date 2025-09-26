<script src="{{ asset('backend/assets/js/bundle.js?ver=2.4.0') }}"></script>
<script src="{{ asset('backend/assets/js/scripts.js?ver=2.4.0') }}"></script>
<script src="{{ asset('js/favloader.js') }}"></script>
<script src="{{ asset('skeleton/jquery.scheletrone.js') }}"></script>
<script src="{{ asset('js/google-translate.js') }}"></script>
<script src="{{ asset('csv_viewer/js/handsontable.full.js') }}"></script>
<script src="{{ asset('csv_viewer/js/papaparse@5.js') }}"></script>
{{-- toastr --}}
<script src="{{ asset('js/toastr.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert2.js') }}"></script>
<script src="{{ asset('mini_audio_player/js/jquery.mb.miniAudioPlayer.js') }}"></script>
<script src="{{ asset('backend/js/main.js') }}"></script>
@yield('js')

<x:notify-messages />
@notifyJs