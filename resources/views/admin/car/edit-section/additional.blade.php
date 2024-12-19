{{-- <div class="card">
        <div class="card-header">

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table other-specs">
                            <thead>
                                <tr>
                                    <th>SI No</th>
                                    <th>Category</th>
                                    <th>Specification Title</th>
                                    <th>Value Type</th>
                                    <th>Value(Text)</th>
                                    <th>Value(Boolean)</th>
                                    <th>Units</th>
                                    <th>Key Feature</th>
                                <th>Key Spec</th>
                                <th>Upload Icon</th>
                                </tr>
                            </thead>
                            <tbody id="specification-rows">


                            @for($i = 0; $i < $carVarient->carAdditionalSpecifications->count(); $i++)

                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>
                                            <x-form-select field="section_{{$i}}" id="section_{{$i}}" >
                                                @foreach(config('params.car.specification-section') as $value => $label)
                                                    <option value="{{ $value }}"
                                                        <?php if($value == $additionals[$i]->category_id){ echo "selected";}?>>
                                                    {{ $label }}</option>
                                                @endforeach
                                            </x-form-select>
                                        </td>
                                        <td>
                                            <x-form-input type="text" field="attribute_{{$i}}" id="attribute_{{$i}}" class="validate" value="{{$additionals[$i]->specification}}"> </x-form-input>
                                        </td>
                                        <td>
                                            <x-form-select field="input_type_{{$i}}" id="input_type_{{$i}}" onchange="toggleFields({{$i}})" style="width: 500px;">
                                                <option value="1" <?php if(1 == $additionals[$i]->input_type){ echo "selected";}?>>Text</option>
                                                <option value="2" <?php if(2 == $additionals[$i]->input_type){ echo "selected";}?>>Boolean</option>
                                            </x-form-select>
                                        </td>

                                        <td>
                                            <x-form-input type="text" field="text_value_{{$i}}" id="text_value_{{$i}}" class="validate" value="{{(1 == $additionals[$i]->input_type) ?$additionals[$i]->value : ''}}"></x-form-input>
                                        </td>
                                        <td>
                                            <x-form-select field="bool_value_{{$i}}" id="bool_value_{{$i}}">
                                                <option value="1" <?php if(1 == $additionals[$i]->value){ echo "selected";}?>>Yes</option>
                                                <option value="2" <?php if(2 == $additionals[$i]->value){ echo "selected";}?>>No</option>
                                            </x-form-select>
                                        </td>
                                        <td>
                                            <x-form-input type="text" field="units_{{$i}}" id="units_{{$i}}" class="validate" value="{{$additionals[$i]->unit}}"></x-form-input>
                                        </td>
                                        <td>
                                        <div class="form-check form-check-inline col-md-2">
                                        <input class="form-check-input" type="checkbox" name="key_feature_{{$i}}"
                                        id="key_feature_{{ $i }}" value="{{ $additionals[$i]->is_key_feature }}" {{ $additionals[$i]->is_key_feature == 1 ? 'checked' : '' }} onclick="toggleKeySpec({{$i}})">
                                        &nbsp;<label class="form-check-label" for="key_feature_{{ $i }}"
                                        style="color: black;">
                                        </label></div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-inline col-md-2">
                                                    <input class="form-check-input" type="checkbox" name="key_spec_{{$i}}"
                                                        id="key_spec_{{ $i }}" value="{{ $additionals[$i]->is_key_spec }}" {{ $additionals[$i]->is_key_spec  == 1 ? 'checked' : '' }} onclick="toggleKeySpec({{$i}})>
                                                    &nbsp;<label class="form-check-label" for="key_spec__{{ $i }}"
                                                        style="color: black;">

                                                    </label>
                                        </div>
                                    </td>
                                    <td>
                                    <div class="form-group">
                    @php
                        $imageName = $additionals[$i]->key_icon;
                    @endphp
                    @if ($imageName)
                    <div class="col-mod-4">
                        <img src="{{ file_asset('files-car', $imageName) }}"
                             alt="car-image" id="image-preview_{{ $i }}" class="img-thumbnail" width="50" height="50">
                    </div>
                    @endif
                </div>
                                        <input type="file" id="icon_{{$i}}" name="icon_{{$i}}" class="d-none" onchange="previewIcon({{$i}})">
                                    </td>
                                        <td>
                                            <button type="button" class="btn btn-md btn-danger" title="Clear"
                                                id="delete_btn_{{$i}}" data-id="{{$i}}" onclick="clearRow(this)">
                                                <span class="ion-trash-a" data-attribute></span>
                                            </button>
                                        </td>
                                        <input type="hidden" name="attribute_id_{{$i}}" id="attribute_id_{{$i}}" value="{{ $additionals[$i]->id ?? '' }}" />
                                    </tr>


                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="row_count" name="row_count" value="{{$carVarient->carAdditionalSpecifications->count() }}">
                    <div class="text-right mt-3">
                        <button type="button" class="btn btn-md btn-primary" onclick="addRow()">Add Specifications</button>
                    </div>
                </div>
            </div>
        </div>
</div>

    <script>
   let rowCount = document.getElementById('row_count').value;
   console.log(rowCount);
    function addRow() {
        const tableBody = document.getElementById('specification-rows');
        //let rowCount = document.getElementById('row_count').value;
        const newRow = document.createElement('tr');
        newRow.id = `row_${rowCount}`;

        newRow.innerHTML = `
            <td>${rowCount + 1}</td>
            <td>
                <x-form-select field="section_${rowCount}" id="section_${rowCount}">
                <option value="">Choose Section</option>
                    @foreach(config('params.car.specification-section') as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-form-select>
            </td>
            <td>
                <x-form-input type="text" field="attribute_${rowCount}" id="attribute_${rowCount}" class="validate"></x-form-input>
            </td>
            <td>
                <x-form-select field="input_type_${rowCount}" id="input_type_${rowCount}" onchange="toggleFields(${rowCount})" style="width:80px;>
                <option value="1">Text</option>
                    <option value="2">Boolean</option>
                </x-form-select>
            </td>
            <td>
                <x-form-input type="text" field="text_value_${rowCount}" id="text_value_${rowCount}" class="validate"></x-form-input>
            </td>
            <td>
                <x-form-select field="bool_value_${rowCount}" id="bool_value_${rowCount}" disabled>
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </td>
             <td>
                <x-form-input type="text" field="units_${rowCount}" id="units_${rowCount}" class="validate" ></x-form-input>
            </td>
              <td>
                 <x-form-checkbox field="key_feature_${rowCount}" id="key_feature_${rowCount}" value="0" fieldName="" onclick="toggleKeySpec(${rowCount})" />

            </td>
            <td>
               <x-form-checkbox field="key_spec_${rowCount}" id="key_spec_${rowCount}" value="0" fieldName="" onclick="toggleKeySpec(${rowCount})" />

            <td>
                <input type="file" id="icon_${rowCount}" name="icon_${rowCount}" class="d-none" onchange="previewIcon(${rowCount})">
            </td>
            <td>
                <button type="button" class="btn btn-md btn-danger" title="Clear"
                    id="delete_btn_${rowCount}" data-id="${rowCount}" onclick="clearRow(this)">
                    <span class="ion-trash-a" data-attribute></span>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        rowCount++;
        document.getElementById('row_count').value = rowCount;
    }
    function toggleKeySpec(row) {
        const keyFeatureCheckbox = document.getElementById(`key_feature_${row}`);
        const keySpecCheckbox = document.getElementById(`key_spec_${row}`);
        const iconUpload = document.getElementById(`icon_${row}`);

        if (keyFeatureCheckbox.checked) {
            keySpecCheckbox.checked = false;
            keyFeatureCheckbox.value = 1;
            keySpecCheckbox.value = 0;
            iconUpload.classList.remove('d-none');
        } else if (keySpecCheckbox.checked) {
            keyFeatureCheckbox.checked = false;
            keySpecCheckbox.value = 1;
            keyFeatureCheckbox.value = 0;
            iconUpload.classList.remove('d-none');
        } else {
            iconUpload.classList.add('d-none');
            keyFeatureCheckbox.value = 0;
            keySpecCheckbox.value = 0;
        }
    }
</script> --}}

<div class="card">
    <div class="card-header">

    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table other-specs">
                        <thead>
                            <tr>
                                <th>SI No</th>
                                <th>Category</th>
                                <th>Specification Title</th>
                                <th>Value Type</th>
                                <th>Value(Text)</th>
                                <th>Value(Boolean)</th>
                                <th>Units</th>
                                <th>Key Feature</th>
                                <th>Key Spec</th>
                                <th>Upload Icon</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="specification-rows">
                            @for($i = 0; $i < $carVarient->carAdditionalSpecifications->count(); $i++)
                                <tr id="row_{{$i}}">
                                    <td>{{$i+1}}</td>
                                    <td>
                                        <x-form-select field="section_{{$i}}" id="section_{{$i}}">
                                            @foreach(config('params.car.specification-section') as $value => $label)
                                                <option value="{{ $value }}" <?php if($value == $additionals[$i]->category_id){ echo "selected";}?>>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </x-form-select>
                                    </td>
                                    <td>
                                        <x-form-input type="text" field="attribute_{{$i}}" id="attribute_{{$i}}" class="validate" value="{{$additionals[$i]->specification}}"></x-form-input>
                                    </td>
                                    <td>
                                        <x-form-select field="input_type_{{$i}}" id="input_type_{{$i}}" onchange="toggleFields({{$i}})" style="width: 80px;" >
                                            <option value="1" <?php if(1 == $additionals[$i]->input_type){ echo "selected";}?>>Text</option>
                                            <option value="2" <?php if(2 == $additionals[$i]->input_type){ echo "selected";}?>>Boolean</option>
                                        </x-form-select>
                                    </td>
                                    <td>
                                        <x-form-input type="text" field="text_value_{{$i}}" id="text_value_{{$i}}" class="validate" value="{{(1 == $additionals[$i]->input_type) ?$additionals[$i]->value : ''}}"></x-form-input>
                                    </td>
                                    <td>
                                        <x-form-select field="bool_value_{{$i}}" id="bool_value_{{$i}}">
                                            <option value="1" <?php if(1 == $additionals[$i]->value){ echo "selected";}?>>Yes</option>
                                            <option value="2" <?php if(2 == $additionals[$i]->value){ echo "selected";}?>>No</option>
                                        </x-form-select>
                                    </td>
                                    <td>
                                        <x-form-input type="text" field="units_{{$i}}" id="units_{{$i}}" class="validate" value="{{$additionals[$i]->unit}}"></x-form-input>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-inline col-md-2">
                                            <input class="form-check-input" type="checkbox" name="key_feature_{{$i}}"
                                            id="key_feature_{{ $i }}" value="{{ $additionals[$i]->is_key_feature }}" {{ $additionals[$i]->is_key_feature == 1 ? 'checked' : '' }} onclick="toggleKeySpec({{$i}})">
                                            <label class="form-check-label" for="key_feature_{{ $i }}" style="color: black;"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-inline col-md-2">
                                            <input class="form-check-input" type="checkbox" name="key_spec_{{$i}}"
                                            id="key_spec_{{ $i }}" value="{{ $additionals[$i]->is_key_spec }}" {{ $additionals[$i]->is_key_spec  == 1 ? 'checked' : '' }} onclick="toggleKeySpec({{$i}})">
                                            <label class="form-check-label" for="key_spec__{{ $i }}" style="color: black;"></label>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($additionals[$i]->key_icon)
                                            <div class="col-mod-4 mt-2">
                                                <img src="{{ file_asset('files-car', $additionals[$i]->key_icon) }}"
                                                     alt="car-image" id="image-preview_{{ $i }}" class="img-thumbnail" width="50" height="50">
                                            </div>
                                        @endif
                                        <input type="file" id="icon_{{$i}}" name="icon_{{$i}}" onchange="previewIcon({{$i}})" class="form-control">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-md btn-danger" title="Clear"
                                            id="delete_btn_{{$i}}" data-id="{{$i}}" onclick="clearRow(this)">
                                            <span class="ion-trash-a"></span>
                                        </button>
                                    </td>
                                    <input type="hidden" name="attribute_id_{{$i}}" id="attribute_id_{{$i}}" value="{{ $additionals[$i]->id ?? '' }}" />
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="row_count" name="row_count" value="{{$carVarient->carAdditionalSpecifications->count() }}">
                <div class="text-right mt-3">
                    <button type="button" class="btn btn-md btn-primary" onclick="addRow()">Add Specifications</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let rowCount = document.getElementById('row_count').value;

    function addRow() {
        const tableBody = document.getElementById('specification-rows');
        const newRow = document.createElement('tr');
        newRow.id = `row_${rowCount}`;

        newRow.innerHTML = `
            <td>${parseInt(rowCount) + 1}</td>
            <td>
                <x-form-select field="section_${rowCount}" id="section_${rowCount}">
                    <option value="">Choose Section</option>
                    @foreach(config('params.car.specification-section') as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </x-form-select>
            </td>
            <td>
                <x-form-input type="text" field="attribute_${rowCount}" id="attribute_${rowCount}" class="validate"></x-form-input>
            </td>
            <td>
                <x-form-select field="input_type_${rowCount}" id="input_type_${rowCount}" onchange="toggleFields(${rowCount})">
                    <option value="1">Text</option>
                    <option value="2">Boolean</option>
                </x-form-select>
            </td>
            <td>
                <x-form-input type="text" field="text_value_${rowCount}" id="text_value_${rowCount}" class="validate"></x-form-input>
            </td>
            <td>
                <x-form-select field="bool_value_${rowCount}" id="bool_value_${rowCount}" disabled>
                    <option value="1">Yes</option>
                    <option value="2">No</option>
                </x-form-select>
            </td>
            <td>
                <x-form-input type="text" field="units_${rowCount}" id="units_${rowCount}" class="validate"></x-form-input>
            </td>
            <td>
                <div class="form-check form-check-inline col-md-2">
                    <input class="form-check-input" type="checkbox" name="key_feature_${rowCount}"
                        id="key_feature_${rowCount}" value="0"
                        onclick="toggleKeySpec( ${rowCount} )">
                    <label class="form-check-label" for="key_feature_${rowCount}" style="color: black;">
                    </label>
                </div>
            </td>
            <td>
                <div class="form-check form-check-inline col-md-2">
                    <input class="form-check-input" type="checkbox" name="key_spec_${rowCount}"
                        id="key_spec_${rowCount}" value="0"
                        onclick="toggleKeySpec( ${rowCount} )">
                    <label class="form-check-label" for="key_spec_${rowCount}" style="color: black;">
                    </label>
                </div>
            </td>
            <td>
                <input type="file" id="icon_${rowCount}" name="icon_${rowCount}" class="d-none" onchange="previewIcon(${rowCount})">
            </td>
            <td>
                <button type="button" class="btn btn-md btn-danger" title="Clear"
                    id="delete_btn_${rowCount}" data-id="${rowCount}" onclick="clearRow(this)">
                    <span class="ion-trash-a"></span>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        rowCount++;
        document.getElementById('row_count').value = rowCount;
    }

    function toggleKeySpec(row) {
        const keyFeatureCheckbox = document.getElementById(`key_feature_${row}`);
        const keySpecCheckbox = document.getElementById(`key_spec_${row}`);
        const iconUpload = document.getElementById(`icon_${row}`);

        if (keyFeatureCheckbox.checked) {
            keySpecCheckbox.checked = false;
            keyFeatureCheckbox.value = 1;
            keySpecCheckbox.value = 0;
            iconUpload.classList.remove('d-none');
        } else if (keySpecCheckbox.checked) {
            keyFeatureCheckbox.checked = false;
            keySpecCheckbox.value = 1;
            keyFeatureCheckbox.value = 0;
            iconUpload.classList.remove('d-none');
        } else {
            iconUpload.classList.add('d-none');
            keyFeatureCheckbox.value = 0;
            keySpecCheckbox.value = 0;
        }
    }
</script>
