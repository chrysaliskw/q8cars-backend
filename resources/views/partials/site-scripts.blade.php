<script>
    var resizefunc = [];
</script>
<script src="{{ url('moltran-asset/js/jquery.min.js') }}"></script>
<script src="{{ url('moltran-asset/js/bootstrap.bundle.min.js') }}"></script>
<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>.
<!-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>    
<script src="//cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
<script src="{{ url('moltran-asset/js/detect.js') }}"></script>
<script src="{{ url('moltran-asset/js/fastclick.js') }}"></script>
<script src="{{ url('moltran-asset/js/jquery.slimscroll.js') }}"></script>
<script src="{{ url('moltran-asset/js/jquery.blockUI.js') }}"></script>
<script src="{{ url('moltran-asset/js/waves.js') }}"></script>
<script src="{{ url('moltran-asset/js/wow.min.js') }}"></script>
<script src="{{ url('moltran-asset/js/jquery.imagemaps.js') }}"></script>
<script src="{{ url('moltran-asset/js/jquery.nicescroll.js') }}"></script>
<script src="{{ url('moltran-asset/js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ url('moltran-asset/plugins/notifyjs/dist/notify.min.js') }}"></script>
<script src="{{ url('moltran-asset/plugins/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ url('moltran-asset/plugins/notifications/notify-metro.js') }}"></script>
<script src="{{ url('moltran-asset/plugins/notifications/notifications.js') }}"></script>
<script src="{{ url('moltran-asset/plugins/switchery/switchery.min.js') }}"></script>
<script src="{{ url('moltran-asset/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ url('moltran-asset/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ url('moltran-asset/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ url('moltran-asset/pages/jquery.sweet-alert.init.js') }}"></script>
<script src="{{ url('moltran-asset/js/jquery.app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

<script>
    $.fn.dataTable.ext.errMode = 'none';
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    });
</script>
