<script src="{{ asset('backend/vendor/jquery/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/js/demo/datatables-demo.js') }}"></script>
<script src="{{ asset('backend/js/jscolor.js') }}"></script>
<script src="{{ asset('backend/js/jquery.timepicker.js') }}"></script>
<script src="{{ asset('backend/js/jquery-ui.js') }}"></script>
<script src="{{ asset('backend/js/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('backend/js/select2.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap4-toggle.min.js') }}"></script>
<script src="{{ asset('backend/js/sweetalert2.min.js') }}"></script>
{{-- @if (app()->getLocale() == 'ar') --}}
<script>
    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#dataTable-ar')) {
            $('#dataTable-ar').DataTable().clear().destroy();
        }

        var table = $('#dataTable-ar').DataTable({
            language: {
                url: '/assets/datatables/i18n/' + "{{ app()->getLocale() }}" + '.json'
            },
            scrollX: true,
            scrollCollapse: true,
            paging: true,
            autoWidth: false
        });

        // Now this works because "table" is defined
        table.on('draw', function() {
            $('input[data-toggle="toggle"]').bootstrapToggle();
        });
    });
</script>
{{-- @else
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#dataTable-ar')) {
                $('#dataTable-ar').DataTable().clear().destroy();
            }

            var table = $('#dataTable-ar').DataTable();

            // Now this works because "table" is defined
            table.on('draw', function() {
                $('input[data-toggle="toggle"]').bootstrapToggle();
            });
        });
    </script>
@endif --}}
