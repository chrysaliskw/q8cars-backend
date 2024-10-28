<div class="card card-border card-primary">
    <div class="card-header">
        <div class="m-b-30">
            <h5></h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <x-form-select field="brand_id" field-name="Brand*" id="brand_id">
                </x-form-select>
                <input type="hidden" id="brand_id_text" name="brand_id_text" />
                <span class="error" role="alert" id="brand_id_error" ></span>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="model_name" field-name="Model Name*" value="{{ old('model_name') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="body_type_id" field-name="Body Type*" id="body_type_id">
                </x-form-select>
                <input type="hidden" id="body_type_id_text" name="body_type_id_text" />
                <span class="error" role="alert" id="body_type_id_error" ></span>
            </div>

            <div class="col-md-4">
                <x-form-input type="text" field="sort_order" field-name="Sort Order*" value="{{ old('sort_order') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="is_upcoming" field-name="Is Upcoming ?" defaultPrompt="Select" id="is_upcoming" onchange="toggleUpcoming(this)">
                    <option value="1">Yes</option>
                    <option selected value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4 justLaunch" >
                <x-form-select field="is_just_launched" field-name="Is Just Launched ?" defaultPrompt="Select" id="is_just_launched" onchange="toggleJustLaunch(this)">
                    <option value="1">Yes</option>
                    <option selected value="2">No</option>
                </x-form-select>
            </div>
            <div class="col-md-4 justLaunch">
                <x-form-input type="text" id="just_launch_sort_order" field="just_launch_sort_order" field-name="Just Launch Sort Order" value="{{ old('just_launch_sort_order') }}" disabled>
                </x-form-input>
            </div>

            <div class="col-md-4">
                <x-form-select field="status" field-name="Status*" defaultPrompt="Select status">
                    @foreach (config('params.car.status') as $value => $label)
                        <option {{ old('status') == $value ? 'Selected' : '' }} value="{{ $value }}">
                            {{ $label }}</option>
                    @endforeach
                </x-form-select>
            </div>
        </div>
    </div>
</div>
<div class="card card-border card-primary">
    <div class="card-header">
        <div class="m-b-30">
            <h5>Pricing Details (KWD)</h5>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <x-form-input type="text" field="ex_showroom_price" field-name="Ex-Showroom Price*" value="{{ old('ex_showroom_price') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="on_road_price" field-name="On Road Price*" value="{{ old('on_road_price') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="finance_available" field-name="Finance Available*" value="{{ old('finance_available') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="insurance" field-name="Insurance*" value="{{ old('insurance') }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="service_charge" field-name="Service Cost(Avg of 5 Years)*" value="{{ old('service_charge') }}">
                </x-form-input>
            </div>
        </div>
    </div>
</div>

<div class="card card-border card-primary">
    <div class="card-header">
        <div class="m-b-30">
        Professions
        </div>
    </div>
    <div class="card-body">
        <div class="col-md-12">
            <div class="row">
                <div class="form-check form-check-inline col-md-2">
                    <input class="form-check-input" type="checkbox" name="all_profession" id="all_profession"
                        value="-1" onclick="selectAllProfession()" {{ old('all_profession') == '-1' ? 'checked' : '' }}>
                    &nbsp;<label class="form-check-label" for="all_profession" style="color: black;">
                        All
                    </label>
                </div>
                @foreach (config('params.professions') as $key => $value)
                <div class="form-check form-check-inline col-md-2">
                    <input class="form-check-input" type="checkbox" name="professions[]"
                        id="professions{{ $key }}" value="{{ $key }}" onclick="updateAllProfession()"
                        {{ in_array($key, old('professions', [])) ? 'checked' : '' }}>
                    &nbsp;<label class="form-check-label" for="professions{{ $key }}" style="color: black;">
                        {{ $value }}
                    </label>
                </div>
                @endforeach
            </div>
            <span class="error" role="alert">
                @error('professions')
                    {{ $message }}</br>
                @enderror
            </span>
        </div>
    </div>


</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function toggleJustLaunch(that){

    console.log(that.value);
    if ((that).value == 1) { // If "Yes" is selected
        $('#just_launch_sort_order').prop('disabled', false); // Enable the input field
    } else { // If "No" is selected
        $('#just_launch_sort_order').prop('disabled', true); // Disable the input field
    }
};
function toggleUpcoming(that){

    console.log(that.value);
    if ((that).value == 1) { 
        $('.justLaunch').css('display', 'none'); 
    } else {
        $('.justLaunch').css('display', 'flex');
    }
};

</script>
@include('admin.car.create-section.create_key_features')




















