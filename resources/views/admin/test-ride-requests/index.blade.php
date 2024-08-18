<x-admin-layout title="Test Ride Requests">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Test Ride Requests</li>
    </x-slot>
    @php
        $statuses = config('params.test_drive.status');
        unset($statuses[5]);
    @endphp
    <x-crud-index title="Test Ride Requests" createUrl="">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>
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
                    <input type="hidden" id="itemId" name="id" value="">
                    <div class="form-group">
                        <label for="status">Select Status</label>
                        <select id="status" name="status" class="form-control">
                            @foreach($statuses as $key => $value)
                                <option value="{{ $key }}">
                                    {{ $value }}
                                </option>
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
    <x-slot name="scripts">
        {!! $grid->scripts() !!}  
        <script>
            function openUpdateStatusModal(identifier)
            {
                let id = $(identifier).data().id;
                let currentStatus = $(identifier).data().status; 
                document.getElementById('itemId').value = id;
                const statuses = @json($statuses); // Get statuses from PHP
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

                // Show the modal
                $('#updateStatusModal').modal('show');
            }

            function submitStatusUpdate()
            {
                const form = document.getElementById('updateStatusForm');
                const formData = new FormData(form);

                fetch('{{ route('admin.test-ride-requests.update') }}', {
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
