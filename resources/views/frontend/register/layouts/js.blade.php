	<!-- COMMON SCRIPTS -->
	<script src="{{ asset('subscription/js/jquery-3.6.0.js') }}"></script>
    <script src="{{ asset('subscription/js/common_scripts.js') }}"></script>
	<script src="{{ asset('subscription/js/velocity.js') }}"></script>
	<script src="{{ asset('subscription/js/common_functions.js') }}"></script>
	<script src="{{ asset('subscription/js/file-validator.js') }}"></script>

	<script src="{{ asset('js/favloader.js') }}"></script>
	<!-- Wizard script-->
	<script src="{{ asset('subscription/js/func_1.js') }}"></script>
    @yield('js')

    <x:notify-messages />
    @notifyJs