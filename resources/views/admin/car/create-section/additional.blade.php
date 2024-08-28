<div class="card">
        <div class="card-header">
           
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
                                </tr>
                            </thead>
                            <tbody>
                            @php 
                                $i = 1;
                            @endphp
                            @for($i = 1; $i <=30; $i++)
                               
                                    <tr>
                                        <td>{{$i}}</td>
                                       
                                        <td>
                                            <x-form-select field="section_{{$i}}" id="section_{{$i}}" >
                                                @foreach(config('params.car.specification-section') as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </x-form-select>
                                        </td>
                                        <td>
                                            <x-form-input type="text" field="attribute_{{$i}}" id="attribute_{{$i}}" class="validate"> </x-form-input>
                                        </td>
                                        <td>
                                            <x-form-select field="input_type_{{$i}}" id="input_type_{{$i}}">
                                                <option value="1">Text</option>
                                                <option value="2">Boolean</option>
                                            </x-form-select>
                                        </td>
                                       
                                        <td>
                                            <x-form-input type="text" field="text_value_{{$i}}" id="text_value_{{$i}}" class="validate"></x-form-input>
                                        </td>
                                        <td>
                                            <x-form-select field="bool_value_{{$i}}" id="bool_value_{{$i}}" defaultPrompt="Select">
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </x-form-select>
                                        </td>
                                        <td>
                                            <x-form-input type="text" field="units_{{$i}}" id="units_{{$i}}" class="validate"></x-form-input>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-md btn-danger mt-4" title="Clear"
                                                id="delete_btn_{{$i}}" data-id="{{$i}}" onclick="clearRow(this)">
                                                <span class="ion-trash-a" data-attribute></span>
                                            </button>  
                                        </td>         
                                        
                                    </tr>
                               
                                
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>