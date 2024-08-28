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
                <x-form-input type="text" field="model_name" field-name="Model Name*" value="{{ $car->model_name }}">
                </x-form-input>
            </div>
          
            <div class="col-md-4">
                <x-form-input type="text" field="sort_order" field-name="Sort Order*" value="{{ $car->sort_order }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-select field="is_just_launched" field-name="Is Just Launched ?" >
                    <option value="1" <?php if(1 == $car->is_just_launched){ echo "selected";}?>>Yes</option>
                    <option  value="2" <?php if(2 == $car->is_just_launched){ echo "selected";}?>>No</option>
                </x-form-select>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="just_launch_sort_order" field-name="Just Lauch Sort Order" value="{{ $car->just_launch_sort_order }}">
                </x-form-input>
            </div>
            
            <div class="col-md-4">
                <x-form-select field="status" field-name="Status*" defaultPrompt="Select status">
                    @foreach (config('params.car.status') as $value => $label)
                        <option {{ $car->status == $value ? 'Selected' : '' }} value="{{ $value }}">
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
                <x-form-input type="text" field="ex_showroom_price" field-name="Ex-Showroom Price*" value="{{  $car->ex_showroom_price }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="on_road_price" field-name="On Road Price*" value="{{  $car->on_road_price }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="finance_available" field-name="Finance Available*" value="{{  $car->finance_available }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="insurance" field-name="Insurance*" value="{{  $car->insurance }}">
                </x-form-input>
            </div>
            <div class="col-md-4">
                <x-form-input type="text" field="service_charge" field-name="Service Cost(Avg of 5 Years)*" value="{{  $car->service_charge }}">
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
                        @php
                            $allProfeessionCheck = '';
                            if ($pcount == $selectedProfessionCount) {
                                $allProfeessionCheck = 'checked';
                            }
                        @endphp
                    <div class="form-check form-check-inline col-md-2">
                            <input class="form-check-input" type="checkbox" name="all_profession" id="all_profession"
                                value="-1" onclick="selectAllProfession()" {{ $allProfeessionCheck }}>
                            &nbsp;<label class="form-check-label" for="all_profession" style="color: black;">
                                All 
                            </label>
                    </div>
                 
                    @foreach (config('params.professions') as $key => $value)
                        @php
                            $attrPCheck = in_array($key, $currentProfessions) ? 'checked' : '';
                        @endphp
                    <div class="form-check form-check-inline col-md-2">
                                <input class="form-check-input" type="checkbox" name="professions[]"
                                    id="professions{{ $key }}" value="{{ $key }}" {{ $attrPCheck }}>
                                &nbsp;<label class="form-check-label" for="professions{{ $key }}"
                                    style="color: black;">
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

@include('admin.car.edit-section.edit_key_features')




















