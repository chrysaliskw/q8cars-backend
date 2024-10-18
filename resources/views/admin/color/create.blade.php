<x-admin-layout title="Colors">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.color.index') }}">Colors</a></li>
        <li class="active">Create</li>
    </x-slot>

   <x-crud-create title="Brands">
        <x-form method="POST" action="{{ route('admin.color.store') }}" class="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-4">
                    <x-form-input type="text" field="name" field-name="Name" value="{{ old('name') }}" id="nameInput">
                    </x-form-input>
                    <span id="nameWarning" style="color: red; display: none;">Maximum length is 30 characters.</span>
                </div>
                <div class="col-md-4">
                    <label for=""> Choose color <span id="selectedColor"></span></label>
                    <input type="color" class="form-control" id="colorPicker" name="color_code" value="">
                </div>
                <div class="col-md-4">
                    <x-form-multi-select field="brand" field-name="Brand" defaultPrompt="Select Brand" id="userSelect">
                        @foreach($brandArr as $key => $value)
                            <option {{ old("brand") == $key ? "selected" : "" }} value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </x-form-multi-select>
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.brand_color.status') as $value => $label)
                            <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>
            <div class="col-md-10">
                <x-form-submit>Save</x-form-submit>
            </div>
        </x-form>
    </x-crud-create>
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
