<x-admin-layout title="FAQ">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet"> 
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.faq.index') }}">FAQ</a></li>
        <li class="active">Update</li>
    </x-slot>
    <x-crud-update title="FAQ">
        <x-form method="PUT" action="{{ route('admin.faq.update', $faq) }}" class="form">
            <div class="row">
                <div class="col-md-4">
                    <x-form-select field="brand_id" field-name="Brand" id="brand_id">
                    </x-form-select>
                    <input type="hidden" id="brand_id_text" name="brand_id_text" />
                </div>
                <div class="col-md-4">
                    <x-form-select field="car_id" field-name="Car Model" id="car_id">
                    </x-form-select>
                    <input type="hidden" id="car_id_text" name="car_id_text" />
                </div>
                <div class="col-md-4">
                    <x-form-select field="car_version_id" field-name="Car Version" id="car_version_id">
                    </x-form-select>
                    <input type="hidden" id="car_version_id_text" name="car_version_id_text" />
                </div>
            </div>
              
            <div class="row">   
                <div class="col-md-12">
                    <x-form-textarea field="question" field-name="Question" field-value="{{ $faq->question }}"></x-form-textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <label for="answer">Answer</label>
                                    <div class="card-body">
                                        <textarea class="summernote form-control" rows="9" name="answer">{{ $faq->answer}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div> 
                </div>
            </div>
            <div class="row">  
                <div class="col-md-4">
                    <x-form-input field="sort_order" field-name="Sort Order" value="{{ $faq->sort_order }}"></x-form-input>
                </div>
                <div class="col-md-4">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.faq.status') as $value => $label)
                            <option {{ $faq->question_status == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-update>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <x-slot name="scripts">
   
        <script src="{{ url('moltran-asset/plugins/summernote/summernote-bs4.js') }}"></script>
        <script>
            jQuery(document).ready(function(){

                $('.summernote').summernote({
                    height: 200,                 // set editor height

                    minHeight: null,             // set minimum height of editor
                    maxHeight: null,             // set maximum height of editor

                    focus: true,                 // set focus to editable area after initializing summernote
                    toolbar: [
                        // [groupName, [list of button]]
                        ['style', ['bold', 'italic', 'underline']],
                        // ['style', ['bold', 'italic', 'underline', 'clear']],
                        // ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        // ['color', ['color']],
                        // ['para', ['ul', 'ol', 'paragraph']],
                        ['para', ['ul', 'ol']],
                        // ['height', ['height']]
                        ['insert', [ 'picture']],
                    ],
                });

            });
        </script>
        <script type="application/javascript">

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
                // Reset car_id when brand_id changes
                $('#car_id').val(null).trigger('change');
                $('#car_id_text').val(null).trigger('change');
                $('#car_version_id').val(null).trigger('change'); // Optionally reset car_version_id as well
                $('#car_version_id_text').val(null).trigger('change');
            });

            if('{!! $currentBrand !!}') {
                const currentBrand = JSON.parse('{!! $currentBrand !!}');
                const brandOption = new Option(currentBrand.text, currentBrand.id, true, true);
                $('#brand_id').append(brandOption).trigger('change');
            }


            $('#car_id').select2({
                
                placeholder: "Search car",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.car.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            brand_id: $('#brand_id').val(),
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });

            $('#car_id').on('select2:select', function (e) {
                const data = e.params.data;
                $("#car_id_text").val(data.text);
                // Reset car_version_id when brand_id changes
                $('#car_version_id').val(null).trigger('change');
                $('#car_version_id_text').val(null).trigger('change');
                
            });

            if('{!! $currentCar !!}') {
                const currentCar = JSON.parse('{!! $currentCar !!}');
                const cOption = new Option(currentCar.text, currentCar.id, true, true);
                $('#car_id').append(cOption).trigger('change');
            }

            $('#car_version_id').select2({
                
                placeholder: "Search Car version",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.car-version.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                            car_id: $('#car_id').val(),
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
            $('#car_version_id').on('select2:select', function (e) {
                const data = e.params.data;
                $("#car_version_id_text").val(data.text);
            
                
            });

            if('{!! $currentVersion !!}') {
                const currentVersion = JSON.parse('{!! $currentVersion !!}');
                const vOption = new Option(currentVersion.text, currentVersion.id, true, true);
                $('#car_version_id').append(vOption).trigger('change');
            }

        </script>
    </x-slot>
</x-admin-layout>