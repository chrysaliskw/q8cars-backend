<x-admin-layout title="Sub Admins">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.sub-admin.admin.index') }}">Sub Admins</a></li>
        <li class="active">Update</li>
    </x-slot>
    <x-crud-update title="SubAdmin" >
        <x-form method="PUT" action="{{ route('admin.sub-admin.admin.update', $admin->id) }}" class="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-3">
                    <x-form-input type="text" field="name" field-name="Name" value=" {{ $admin->name}}">
                    </x-form-input>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="country_id" class="control-label">Select Country</label>
                        <select name="country_id" class="form-control" id="country_id">
                        </select>
                        <span class="error" role="alert">
                            @error('country_id')
                                {{ $message }}</br>
                            @enderror
                        </span>
                    </div>
                </div>
                <input type="hidden" id="country_text" name="country_text" value="<?php echo $_GET['country_text'] ?? ''; ?>" />
                <input type="hidden" id="country_selected_id" name="country_selected_id" value="<?php echo $_GET['country_selected_id'] ?? ''; ?>" />

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="area_id" class="control-label">Select Area</label>
                        <select name="area_id" class="form-control" id="area_id">
                        </select>
                        <span class="error" role="alert">
                            @error('area_id')
                                {{ $message }}</br>
                            @enderror
                        </span>
                    </div>
                </div>
                <input type="hidden" id="area_text" name="area_text" value="<?php echo $_GET['area_text'] ?? ''; ?>" />
                <input type="hidden" id="area_selected_id" name="area_selected_id" value="<?php echo $_GET['area_selected_id'] ?? ''; ?>" />

                <div class="col-md-3">
                    <x-form-input type="email" field="email" field-name="Email" value=" {{ $admin->email}}"></x-form-input>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="file_name" class="control-label">Image</label><br>
                        @if (isset($admin->picture))
                        <img src="{{ file_asset('files-admin',$admin->picture) }}" alt="profile-img"
                            class="img-thumbnail" width="100" height="150">
                        @endif
                        <!-- <img src="{{ file_asset('files-admin', $admin->picture) }}"
                            alt="profile-img" class="img-thumbnail" width="100" height="150"> -->
                        <input id="file_name" type="file" name="file_name" class="form-control">
                        <span class="error" role="alert">
                            @error('file_name')
                                {{ $message }}<br>
                            @enderror
                        </span>
                    </div>
                </div>

                <div class="col-md-3">
                    <label  for="password-text" style="margin-right:100px">Password</label>
                    <div class="input-group" >
                        <input type="text" id="password" name="password" value="" class="form-control" >
                        <div class="input-group-prepend">
                            <span class="input-group-text"><a style="height:1px;margin-top:-20px;cursor:pointer"class="input-group-addon btn-crs" value="{{ $admin->pasword }}" onClick="randomPassword(10);">Generate</a></span>
                        </div>
                    </div>
                    <span class="error" role="alert">
                            @error('password')
                                {{ $message }}<br>
                            @enderror
                    </span>
                </div>
                <div class="col-md-3">
                    <x-form-select field="role" field-name="Role" defaultPrompt="Select role">
                        @foreach($roles as $role)
                            @php
                                $id = $role->id;
                            @endphp
                            <option  {{ $roleId == $id ? "Selected" : "" }} value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </x-form-select>
                </div>

                <div class="col-md-3">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach(config('params.admin.status') as $value => $label)
                            <option {{ $admin->status == $value ? "Selected" : "" }} value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>


            </div>


            <br>

            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-update>

    <x-slot name="scripts">
        <script type="application/javascript">
            function randomPassword(length) {
                var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890";
                var pass = "";
                for (var x = 0; x < length; x++) {
                    var i = Math.floor(Math.random() * chars.length);
                    pass += chars.charAt(i);
                }
                document.getElementById("password").value = pass;
            }

            if ('{!! $currentCountry !!}') {
                    const currentCountry = JSON.parse('{!! $currentCountry !!}');
                    const countryOption = new Option(currentCountry.name, currentCountry.id, true, true);
                   $('#country_id').append(countryOption);
                }

                $('#country_id').on('select2:select', function (e) {
                   $('#area_id').val("").trigger('change');
                });

            $('#country_id').select2({
                placeholder: "Search Country",
                minimumInputLength: 1,
                ajax: {
                        url: "{{ route('admin.country.select') }}",
                        dataType: 'json',
                        data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                        }
                }
            });


            $('#area_id').select2({
                placeholder: "Search Area",
                minimumInputLength: 1,
                ajax: {
                        url: "{{ route('admin.area.select') }}",
                        dataType: 'json',
                        data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                        }
                }
            });


            if ('{!! $currentArea !!}') {
                    const currentArea = JSON.parse('{!! $currentArea !!}');
                    const areaOption = new Option(currentArea.name, currentArea.id, true, true);
                    $('#area_id').append(areaOption).trigger('change');
                }
        </script>
    </x-slot>

</x-admin-layout>
