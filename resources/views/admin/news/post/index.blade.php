<x-admin-layout title="News">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">News</li>
    </x-slot>
    {{-- <div class="row">
            <div class="col-sm-12">
                <!-- <h4 class="pull-left page-title">News</h4> -->
                <ol class="breadcrumb pull-right">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="active">News</li>
                </ol>
            </div>
        </div> --}}
    <x-crud-index title="" createUrl="{{ route('admin.news.create') }}" createButtonText="Add News">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>
    <div id="newsToggleModel" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">BANNER</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="newsToggleForm" method="POST" action=" ">
                        @csrf <!-- CSRF token for security -->
                        <input type="hidden" id="itemId" name="id" value="">
                        {{-- <input type="hidden" name="banner" id="banner" value="1"> --}}
    
                        <div class="form-group">
                            <label for="banner">Are you sure to change the banner?</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="OK" class="btn btn-primary" form="newsToggleForm">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>



    <x-slot name="scripts">

        {!! $grid->scripts() !!}

    </x-slot>

    <script>
        function toggleNews(button) {

            const itemId = $(button).data('id');
            $('#itemId').val(itemId);
            const currentStatus = $(button).data('status');
            $('#status').val(currentStatus);

            $('#newsToggleModel').modal('show');
        }



        function toggleNews(button) {
            const itemId = $(button).data('id');
            const carId = $(button).data('car-id');
            const showInDetailPage = $(button).data('show_in_detail_page');
            const isTrending = $(button).data('is_trending');
            const status = $(button).data('status');

            // Set the values in the form inputs
            $('#itemId').val(itemId);
            // $('#carId').val(carId);
            // $('#showInDetailPage').val(showInDetailPage);
            // $('#is_trending').val(isTrending);
            // $('#status').val(status);

            
            const actionUrl = "{{ route('admin.news.banner', ':id') }}".replace(':id', itemId);


            $('#newsToggleForm').attr('action', actionUrl);

            // Show the modal
            $('#newsToggleModel').modal('show');

        }
    </script>




</x-admin-layout>
