<x-admin-layout title="Offer Request Reports">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Offer Request Reports</li>
    </x-slot>
    
    <x-crud-create cardTitle="Create Offer Request Report">
        <form method="GET" class="form-horizontal" action="{{ route('admin.reports.offer-request.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="mobile" field-name="UserMobile" value="<?php echo isset($_GET['mobile']) ? $_GET['mobile'] : ''; ?>"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="model" field-name="Car Model" value="<?php echo isset($_GET['model']) ? $_GET['model'] : ''; ?>"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-select field="type" field-name="Type" defaultPrompt="Select ">
                        <option value="" selected>All</option>  
                        @foreach (config('params.offer_request.type') as $value => $label)
                            <option value="{{ $value }}"
                                {{ array_key_exists('type', $_GET) ? ($_GET['type'] == $value ? 'selected' : '') : '' }}>
                                {{ $label }}</option>
                        @endforeach                  
                    </x-form-select>
                </div>

            </div> 
            <div class="row"> 
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select ">
                        <option value="" selected>All</option>  
                        @foreach (config('params.offer_request.status') as $value => $label)
                            <option value="{{ $value }}"
                                {{ array_key_exists('status', $_GET) ? ($_GET['status'] == $value ? 'selected' : '') : '' }}>
                                {{ $label }}</option>
                        @endforeach                  
                    </x-form-select>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date" class="control-label">Start date</label>
                        <div class="input-group">
                            <input type="text" name="start_date" id="start_date" class="form-control"
                                value="<?php echo $_GET['start_date'] ?? ''; ?>">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('start_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                        @error('startDate')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                        <div class="form-group">
                            <label for="end_date" class="control-label">End date</label>
                            <div class="input-group">
                                <input type="text" name="end_date" id="end_date" class="form-control"
                                    value="<?php echo $_GET['end_date'] ?? ''; ?>">
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="md md-event"></i></span>
                                </div>
                            </div>
                            @error('end_date')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                            @error('endDate')
                                <span class="error" role="alert">{{ $message }}</span>
                            @enderror
                        </div>
                </div>
                <div class="col-md-9">
                    <div class="d-flex align-items-center" style="height:100%;padding:0 10px;">
                        <div class="action-item-button generate-btn"style="margin-right:10px;">
                            <x-form-submit>Generate Report</x-form-submit>
                        </div>
                        <div class="action-item-button"style="margin-right:10px;">
                            <button type="submit" class="btn btn-primary waves-effect waves-light"
                                onclick="event.preventDefault();document.getElementById('export-form').submit();">
                                <i class="fa fa-download"></i> Download
                            </button>
                        </div>
                        <div class="action-item-button">
                            <a href="{{route('admin.reports.offer-request.index')}}" class="btn btn-primary waves-effect waves-light">Reset</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </x-crud-create>

    <x-crud-index title="Offer Request Reports">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
        
        {{-- Hidden Form --}}
        <div class="col-lg-12" style="margin-top: 10px;">
            <div class="form-group row m-b-0">
                <div class="col-sm-10">
                    <form id="export-form" action="{{route('admin.reports.offer-request.export')}}" method="GET" style="display: none;">
                        <input type="hidden" name="startDate" value="<?php echo $_GET['start_date'] ?? ''; ?>" />
                        <input type="hidden" name="endDate" value="<?php echo $_GET['end_date'] ?? ''; ?>" />
                        <input type="hidden" name="type" value="<?php echo $_GET['type'] ?? ''; ?>" />
                        <input type="hidden" name="mobile" value="<?php echo $_GET['mobile'] ?? ''; ?>" />
                        <input type="hidden" name="status" value="<?php echo $_GET['status'] ?? ''; ?>" />
                        <input type="hidden" name="model" value="<?php echo $_GET['model'] ?? ''; ?>" />
                    </form>
                </div>
            </div>
        </div>
        {{-- Hidden Form End --}}
    </x-crud-index>
    <x-slot name="scripts">
        <script type="application/javascript">    
            $("#start_date").datepicker({
                format: 'dd-mm-yyyy',
                orientation: 'bottom',
            });

            $("#end_date").datepicker({
                format: 'dd-mm-yyyy',
                orientation: 'bottom',
            });           
        </script>
    </x-slot>
</x-admin-layout>
