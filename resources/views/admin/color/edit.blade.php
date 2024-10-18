<x-admin-layout title="Colors">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.color.index') }}">Brands</a></li>
        <li class="active">Update</li>
    </x-slot>

    <x-crud-update title="Brand">
        <x-form method="PUT"
            action="{{ route('admin.color.update', $color) }}"
            class="form" enctype="multipart/form-data">
            <div class="row">

            <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ old('name') ?? $color->name }}" id="nameInput">
                    </x-form-input>
                    <span id="nameWarning" style="color: red; display: none;">Maximum length is 30 characters.</span>
                </div>
                <div class="col-md-4">
                    <label for=""> Change color <span id="selectedColor"> -Selected:({{$color->code}})</span></label>
                    <input type="color" class="form-control" id="colorPicker" name="color_code" value="{{$color->code}}">
                </div>
                <div class="col-md-4">
                <x-form-input type="text" field="brand" field-name="Brand" value="{{ old('brand') ?? $color->brand->name }}" readonly>
                </x-form-input>
                </div>
                <input type="hidden" name="brand" value="{{$color->brand_id}}">
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.brand_color.status') as $value => $label)
                            <option {{ old('status',$color->status) == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>

            <div class="col-md-10">
                <x-form-submit>Save</x-form-submit>
            </div>
        </x-form>
    </x-crud-update>

    <x-slot name="scripts">
        <script>
            const nameInput = document.getElementById('nameInput');
            const nameWarning = document.getElementById('nameWarning');

            nameInput.addEventListener('input', function() {
                if (this.value.length > 30) {
                    this.value = this.value.slice(0, 30);
                    nameWarning.style.display = 'inline';
                } else {
                    nameWarning.style.display = 'none';
                }
            });

            const colorPicker = document.getElementById("colorPicker");
            const selectedColor = document.getElementById("selectedColor");
            colorPicker.addEventListener("input", function() {
                selectedColor.textContent = colorPicker.value;
            });
        </script>
    </x-slot>

</x-admin-layout>
