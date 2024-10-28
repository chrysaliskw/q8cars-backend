<x-admin-layout title="Notifications">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.notifications.index') }}">Notifications</a></li>
        <li class="active">Edit</li>
    </x-slot>
    <x-crud-create title="Notifications">
        <x-form method="POST" action="{{ route('admin.notifications.update', $notification) }}" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-12">
                    <x-form-textarea field="title" field-name="Title" field-value="{{ old('title', $notification->title) }}"></x-form-textarea>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="9" name="description">{{ old('description', $notification->description) }}</textarea>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="file" field="image" field-name="Image" value="{{ old('image') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB, Formats : PNG,JPG,JPEG'}}
                        <br>
                    </span>
                    @if ($notification->image)
                        <img src="{{ $notification->image ? url(file_asset('files-notifications', $notification->image)) : '' }}"
                        alt="image" class="img-thumbnail" width="100" height="150">
                    @endif
                </div>
                <div class="col-md-4">
                    <x-form-input type="file" field="logo" field-name="Logo" value="{{ old('logo') }}">
                    </x-form-input>
                    <span class="text-muted">
                        {{'Max size : 2MB, Formats : PNG,JPG,JPEG'}}
                        <br>
                    </span>
                    @if ($notification->logo)
                        <img src="{{ $notification->logo ? url(file_asset('files-notifications', $notification->logo)) : '' }}"
                        alt="logo" class="img-thumbnail" width="100" height="150">
                    @endif
                </div>
                <div class="col-md-4">
                    {{-- <x-form-textarea field="business_name" field-name="Business name" field-value="{{ old('business_name', $notification->business_name) }}"></x-form-textarea> --}}
                    <x-form-input type="text" field="business_name" field-name="Business name" value="{{ old('business_name', $notification->business_name) }}">
                    </x-form-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date" class="control-label">Start Date</label>
                        <div class="input-group">
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="{{ old('start_date', $notification->start_date) }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('start_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date" class="control-label">End Date</label>
                        <div class="input-group">
                            <input type="text" name="end_date" id="end_date" class="form-control"
                                value="{{ old('end_date', $notification->end_date) }}">
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
                            <option {{ old('status', $notification->status) == $value ? 'selected' : '' }} value="{{ $value }}">
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
            }).datepicker('setDate', $('#start_date').val() || today);

            $('#start_date').on('show.bs.datepicker', function() {
                $(this).datepicker('setStartDate', today);
            });

            $('#start_date').on('changeDate', function(e) {
                $('#end_date').datepicker('setStartDate', e.date);
                $('#end_date').val('');
            });

            $('#end_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                startDate: today
            });
        });

    </script>
    </x-slot>
</x-admin-layout>
