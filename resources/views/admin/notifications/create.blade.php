<x-admin-layout title="Notifications">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
        <li class="active">Create</li>
    </x-slot>
    <x-crud-create title="Notifications">
        <x-form method="POST" action="{{ route('admin.notifications.store') }}" class="form"
            enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <x-form-textarea field="title" field-name="Title"
                        field-value="{{ old('title') }}"></x-form-textarea>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="9" name="description">{{ old('description') }}</textarea>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="file" field="image" field-name="Image" value="{{ old('image') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{ 'Max size : 2MB, Formats : PNG,JPG,JPEG' }}
                        <br>
                        <br>
                    </span>
                </div>
                <div class="col-md-4">
                    <x-form-input type="file" field="logo" field-name="Logo" value="{{ old('logo') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{ 'Max size : 2MB, Formats : PNG,JPG,JPEG' }}
                        <br>
                        <br>
                    </span>
                </div>
                <div class="col-md-4">
                    {{-- <x-form-textarea field="business_name" field-name="Business name" field-value="{{ old('business_name') }}"></x-form-textarea> --}}
                    <x-form-input type="text" field="business_name" field-name="Business name"
                        value="{{ old('business_name') }}">
                    </x-form-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date" class="control-label">Start Date</label>
                        <div class="input-group">
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date', \Carbon\Carbon::today()->format('Y-m-d')) }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('start_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date" class="control-label">End Date</label>
                        <div class="input-group">
                            <input type="text" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date') }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('end_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div> --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date" class="control-label">End Date</label>
                        <div class="input-group">
                            <input type="text" name="end_date_display" id="end_date_display" class="form-control"
                                value="{{ old('end_date') }}">
                            <input type="hidden" name="end_date" id="end_date" value="{{ old('end_date') }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('end_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>




                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (App\Models\Notification::STATUSES as $value => $label)
                            <option {{ old('status') == $value ? 'selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>

            <br>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <x-slot name="scripts">

        <script>
            jQuery(document).ready(function() {
                const today = new Date();

                $('#start_date').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    startDate: today
                }).datepicker('setDate', today);

                $('#start_date').on('changeDate', function(e) {
                    const selectedDate = e.date;
                    const startDateWithTime = new Date(selectedDate);
                    startDateWithTime.setHours(23, 59, 0); // Set time to 00:00

                    $('#end_date').datepicker('setStartDate', startDateWithTime);
                    $('#end_date').val('');
                });

                $('#end_date_display').datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    startDate: today
                }).on('changeDate', function(e) {
                    // Get the selected date
                    const selectedDate = e.date;

                    // Set the default time to 23:59
                    const endDateWithTime = new Date(selectedDate);
                    endDateWithTime.setHours(23, 59, 0); // Set time to 23:59

                    // Format the date and time as "YYYY-MM-DD 23:59"
                    const formattedDate = endDateWithTime.toISOString().slice(0, 10); // Get date part
                    const formattedTime = '23:59'; // Set time part

                    // Update the hidden input field
                    $('#end_date').val(`${formattedDate} ${formattedTime}`);
                });
            });
        </script>
    </x-slot>
</x-admin-layout>
