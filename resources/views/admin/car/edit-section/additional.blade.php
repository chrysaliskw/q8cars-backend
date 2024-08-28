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
                          
                            @for($i = 0; $i < count($additionals); $i++)
                                    
                                    <tr>
                                        <td>{{$i+1}}</td>
                                        <td>
                                            <x-form-select field="section_{{$i}}" id="section_{{$i}}" >
                                                @foreach(config('params.car.specification-section') as $value => $label)
                                                    <option value="{{ $value }}"  
                                                        <?php if($value == $additionals[$i]->section){ echo "selected";}?>>
                                                    {{ $label }}</option>
                                                @endforeach
                                            </x-form-select>
                                        </td>
                                        <td>
                                            <x-form-input type="text" field="attribute_{{$i}}" id="attribute_{{$i}}" class="validate" value="{{$additionals[$i]->specification}}"> </x-form-input>
                                        </td>
                                        <td>
                                            <x-form-select field="input_type_{{$i}}" id="input_type_{{$i}}">
                                                <option value="1" <?php if(1 == $additionals[$i]->input_type){ echo "selected";}?>>Text</option>
                                                <option value="2" <?php if(2 == $additionals[$i]->input_type){ echo "selected";}?>>Boolean</option>
                                            </x-form-select>
                                        </td>
                                       
                                        <td>
                                            <x-form-input type="text" field="text_value_{{$i}}" id="text_value_{{$i}}" class="validate" value="{{$additionals[$i]->value}}"></x-form-input>
                                        </td>
                                        <td>
                                            <x-form-select field="bool_value_{{$i}}" id="bool_value_{{$i}}">
                                                <option value="1" <?php if(1 == $additionals[$i]->value){ echo "selected";}?>>Yes</option>
                                                <option value="2" <?php if(1 == $additionals[$i]->value){ echo "selected";}?>>No</option>
                                            </x-form-select>
                                        </td>
                                        <td>
                                            <x-form-input type="text" field="units_{{$i}}" id="units_{{$i}}" class="validate" value="{{$additionals[$i]->units}}"></x-form-input>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-md btn-danger mt-4" title="Clear"
                                                id="delete_btn_{{$i}}" data-id="{{$i}}" onclick="clearRow(this)">
                                                <span class="ion-trash-a" data-attribute></span>
                                            </button>  
                                        </td>         
                                        <input type="hidden" name="attribute_id_{{$i}}" id="attribute_id_{{$i}}" value="{{ $additionals[$i]->id }}" />
                                    </tr>
                               
                                
                                @endfor
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>