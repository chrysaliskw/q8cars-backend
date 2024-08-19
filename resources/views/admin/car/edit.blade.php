<x-admin-layout title="Cars">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.car.index') }}">Brands</a></li>
        <li class="active">Update</li>
    </x-slot>

    <x-crud-update title="Car">
        <x-form method="PUT" 
            action="{{ route('admin.car.update', $car) }}" 
            class="form" enctype="multipart/form-data">
            <div class="row">

                <div class="col-md-4">
                    <x-form-select field="brand_id" field-name="Brand" id="brand_id">
                    </x-form-select>
                    <input type="hidden" id="brand_id_text" name="brand_id_text" />
                    <span class="error" role="alert" id="brand_id_error" ></span>
                </div>
                <div class="col-md-3">
                    <x-form-input type="text" field="model_name" field-name="Model Name" value="{{ $car->model_name ?? old('model_name')}}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" class="control-label">Image</label><br>
                        <img src="{{ file_asset('files-car', $car->image) }}" 
                            alt="image-img" class="img-thumbnail" width="100" height="150">
                        <input id="image" type="file" name="image" class="form-control">
                        <span class="error" role="alert">
                            @error('image')
                                {{ $message }}</br>
                            @enderror
                        </span>
                        <span class="text-muted">
                            {{'Max size : 2MB'}} 
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select">
                        @foreach (config('params.car.status') as $value => $label)
                            <option {{ old('status', $car->status) == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                
            </div> 

               <div class="row">
              
               

            </div>

            <div class="col-md-10">
                <x-form-submit>Save</x-form-submit>
            </div>
        </x-form>
    </x-crud-update>
    
    <x-slot name="scripts">
        <script type="text/javascript">
            $('#brand_id').select2({
                
                placeholder: "Search brand",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.brand.select') }}",
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
            $('#brand_id').on('select2:select', function (e) {
                const data = e.params.data;
                $("#brand_id_text").val(data.text);
            });
            if('{!! $currentBrand !!}') {
                const currentBrand = JSON.parse('{!! $currentBrand !!}');
                const brandOption = new Option(currentBrand.text, currentBrand.id, true, true);
                $('#brand_id').append(brandOption).trigger('change');
            }
        </script>

       
    </x-slot>
    
</x-admin-layout>