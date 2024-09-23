<x-admin-layout title="Online News">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.news.post.index') }}">Online News</a></li>
        <li class="active">Create</li>
    </x-slot>
    {{-- <div class="row">
            <div class="col-sm-12">
                <!-- <h4 class="pull-left page-title">Online News</h4> -->
                <ol class="breadcrumb pull-right">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.news.post.index') }}">Online News</a></li>
                    <li class="active">Create</li>
                </ol>
            </div>
        </div>--}}
    <x-crud-create title="Online News">
        <x-form method="POST" action="{{ route('admin.news.post.store') }}" class="form" enctype="multipart/form-data">
            <div class="row">
                <!-- <div class="col-md-4">
                    <x-form-select field="country_id" field-name="Country" id="country_id">
                    </x-form-select>
                    <input type="hidden" id="country_text" name="country_text" />
                </div> -->
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category_id" class="control-label">Category</label>
                        <select name="category_id" class="form-control" id="category_id" >
                        </select>
                        <span class="error" role="alert">
                            @error('category_id')
                                {{ $message }}</br>
                            @enderror
                        </span>
                    </div>
                </div>
                <input type="hidden" id="category_text" name="category_text" />
                <div class="col-md-4">
                    <x-form-input type="file" field="image" field-name="Image" value="{{ old('image') }}"></x-form-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-form-input type="text" field="title" field-name="Title" value="{{ old('title') }}"></x-form-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <x-form-textarea field="content" field-name="Content" field-value="{{ old('content') }}"></x-form-textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="expiry_date" class="control-label">Expiry Date*</label>
                        <div class="input-group">
                            <input type="text" name="expiry_date" id="expiry_date" class="form-control"
                                value="{{ old('expiry_date') }}">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div>
                        </div>
                        @error('expiry_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
           {{--     <div class="col-md-4">
                    <x-form-input type="text" field="sort_order" field-name="Sort Order" value="{{ old('sort_order') }}"></x-form-input>
                </div>--}}
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach(config('params.news.posts.status') as $value => $label)
                            <option {{ old("status") == $value ? "Selected" : "" }} value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-create>

    

    <x-slot name="scripts">

        <script type="application/javascript">

            $("#expiry_date").datepicker({
                format: 'dd-mm-yyyy',
                startDate: 'today',
         
            });

            $('#country_id').select2({
                
                placeholder: "Search country",
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

            $('#category_id').select2({
                
                placeholder: "Search category",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.news.category.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            country_id: $("#country_id").val()
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });

            $('#country_id').on('select2:select', function (e) {
                const data = e.params.data;
                $("#country_text").val(data.text);
            });

            if('{!! old("country_id") !!}' && '{!! old("country_text") !!}') {
                const countryOption = new Option('{{ old("country_text") }}', '{{ old("country_id") }}', true, true);
                $('#country_id').append(countryOption).trigger('change');
                $("#country_text").val('{{ old("country_text") }}');
            }

        </script>
    </x-slot>
</x-admin-layout>