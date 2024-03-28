    <!-- General JS Scripts -->
    <script src="{{ url('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ url('assets/modules/popper.js') }}"></script>
    <script src="{{ url('assets/modules/tooltip.js') }}"></script>
    <script src="{{ url('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ url('assets/modules/moment.min.js') }}"></script>
    <script src="{{ url('assets/js/stisla.js') }}"></script>

    <!-- Addon Javascript -->
    <script src="{{ url('assets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    @yield('addon-script')

    <!-- Template JS File -->
    <script src="{{ url('assets/js/scripts.js') }}"></script>
    <script src="{{ url('assets/js/custom.js') }}"></script>

    <script src="{{ url('js/toastr.js') }}"></script>

    <footer class="main-footer print:hidden hidden-print">
        <div class="footer-left">
            Copyright &copy; 2024 <div class="bullet"></div> Design By Unknown
        </div>
        <div class="footer-right">
            Dibuat dengan <i class="fas fa-heart text-danger"></i> oleh unknown
        </div>
    </footer>