<div class="card">
    <div class="card-header">
        <!-- Card header content -->
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SI No</th>
                                <th>Category</th>
                                <th>Specification Title</th>
                                <th>Value Type</th>
                                <th>Value(Text)</th>
                                <th>Value(Boolean)</th>
                                <th>Units</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="specification-rows">
                            <!-- The first row -->
                            <tr id="row_0">
                                <td>1</td>
                                <td>
                                    <x-form-select field="section_0" id="section_0">
                                    <option value="">Choose Section</option>
                                        @foreach(config('params.car.specification-section') as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </x-form-select>
                                </td>
                                <td>
                                    <x-form-input type="text" field="attribute_0" id="attribute_0" class="validate"></x-form-input>
                                </td>
                                <td>
                                    <x-form-select field="input_type_0" id="input_type_0" onchange="toggleFields(0)">   
                                    <option value="1">Text</option>
                                        <option value="2">Boolean</option>
                                    </x-form-select>
                                </td>
                                <td>
                                    <x-form-input type="text" field="text_value_0" id="text_value_0" class="validate"></x-form-input>
                                </td>
                                <td>
                                    <x-form-select field="bool_value_0" id="bool_value_0" disabled> 
                                    <option value="1" >Yes</option>
                                        <option value="2">No</option>
                                    </x-form-select>
                                </td>
                                <td>
                                    <x-form-input type="text" field="units_0" id="units_0" class="validate"></x-form-input>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-md btn-danger mt-4" title="Clear"
                                        id="delete_btn_0" data-id="0" onclick="clearRow(this)">
                                        <span class="ion-trash-a" data-attribute></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" id="row_count" name="row_count" value="1">
                <div class="text-right mt-3">
                    <button type="button" class="btn btn-md btn-primary" onclick="addRow()">Add Specifications</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let rowCount = 1;
    function addRow() {
        const tableBody = document.getElementById('specification-rows');
        
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
                <x-form-input type="text" field="units_${rowCount}" id="units_${rowCount}" class="validate" ></x-form-input>
            </td>
            <td>
                <button type="button" class="btn btn-md btn-danger mt-4" title="Clear"
                    id="delete_btn_${rowCount}" data-id="${rowCount}" onclick="clearRow(this)">
                    <span class="ion-trash-a" data-attribute></span>
                </button>
            </td>
        `;
        
        tableBody.appendChild(newRow); 
        rowCount++;
        document.getElementById('row_count').value = rowCount;
    }
</script>