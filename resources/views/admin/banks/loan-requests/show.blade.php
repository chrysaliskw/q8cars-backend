<x-admin-layout title="Loan Requests">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.loan-requests.index') }}">Loan Requests</a></li>
        <li class="active">View</li>
    </x-slot>
    @php
        $statuses = config('params.banks.status');
    @endphp
    <x-card title="Loan Requests">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">
                    <div class="card-header">
                        <div class="m-b-30">
                            @if($loan_request->status != \App\Models\BankSuggestionRequest::STATUS_ACCEPTED)
                        <button
                            onclick="openUpdateStatusModal(this)"
                            data-id="{{ $loan_request->id }}"
                            data-status="{{ $loan_request->status }}"
                            class="btn btn-primary waves-effect waves-light">
                            <i class="fa fa-pencil"></i> Edit
                        </button>
                        @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-horizontal">
                            @foreach($viewData as $key => $value)
                            @if($key !== 'id')
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">{{ $key }}</label>
                                    <div class="col-sm-8">
                                        {!! $value !!}
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Update Status Modal -->
    <div id="updateStatusModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateStatusForm">
                        <input type="hidden" id="itemId" name="id">
                        <div class="form-group">
                            <label for="status">Select Status</label>
                            <select id="status" name="status" class="form-control">
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitStatusUpdate()">Update</button>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            function openUpdateStatusModal(button) {
                let id = $(button).data('id');
                let currentStatus = $(button).data('status');

                document.getElementById('itemId').value = id;
                const statuses = @json($statuses);
                const statusSelect = document.getElementById('status');
                statusSelect.innerHTML = '';

                Object.entries(statuses).forEach(([key, value]) => {
                    const option = document.createElement('option');
                    option.value = key;
                    option.text = value;
                    if (key == currentStatus) { // Set default selection
                        option.selected = true;
                    }
                    statusSelect.add(option);
                });

                $('#updateStatusModal').modal('show');
            }

            function submitStatusUpdate()
            {
                const form = document.getElementById('updateStatusForm');
                const formData = new FormData(form);
                fetch('{{ route('admin.loan-requests.update') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        swal.fire({
                            title: 'Success',
                            text: data.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#updateStatusModal').modal('hide');
                            location.reload(); // Reload the page to reflect changes
                        });
                    } else {
                        swal.fire({
                            title: 'Error',
                            text: data.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    swal.fire({
                        title: 'Error',
                        text: 'An error occurred while updating the status.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            }
        </script>
    </x-slot>
</x-admin-layout>
