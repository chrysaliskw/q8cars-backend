<x-admin-layout title="Cars">
<style>
    .filter{
        min-width: 7.5em;
        max-width: 7.5em;
    }
</style>
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Cars</li>
    </x-slot>


    <x-crud-index title="" createUrl="{{ route('admin.car.create') }}" createButtonText="Add Car">
        <div class="mb-3 text-right">
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#bulkUploadModal">
                <i class="fa fa-upload"></i> Bulk Upload
            </button>
        </div>
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>

    <!-- Bulk Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" role="dialog" aria-labelledby="bulkUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('admin.car.bulk_upload.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkUploadModalLabel">Bulk Upload Cars</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Upload Excel File</label>
                        <input type="file" name="file" class="form-control" required>
                        <small class="text-muted">Accepted formats: .xlsx, .xls, .csv</small>
                    </div>
                    {{-- <a href="{{ asset('template/car-bulk-upload-template.xlsx') }}" class="btn btn-link">
                        <i class="fa fa-download"></i> Download Sample Template
                    </a> --}}
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>


    <x-slot name="scripts">

        {!! $grid->scripts() !!}

        <script type="application/javascript">

        </script>

    </x-slot>

</x-admin-layout>
