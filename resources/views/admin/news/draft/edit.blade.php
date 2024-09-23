<x-admin-layout title="Drafted News">
    <link href="{{ url('moltran-asset/plugins/summernote/summernote-bs4.css') }}" rel="stylesheet">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.news.draft.index') }}">Drafted News</a></li>
        <li class="active">Update</li>
    </x-slot>
    {{-- <div class="row">
            <div class="col-sm-12">
                <!-- <h4 class="pull-left page-title">News</h4> -->
                <ol class="breadcrumb pull-right">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.news.post.index') }}">News</a></li>
                    <li class="active">Update</li>
                </ol>
            </div>
        </div> --}}
    @php
        $count = $count;
    @endphp
    <x-crud-update title="News">
        <x-form method="PUT" action="{{ route('admin.news.draft.update', $newsDraft) }}" class="form"
            enctype="multipart/form-data">
            <div class="row">
                <!-- <div class="col-md-4">
                    <x-form-select field="country_id" field-name="Country" id="country_id">
                    </x-form-select>
                    <input type="hidden" id="country_text" name="country_text" />
                </div> -->
                <input type="hidden" name="count" id="count" value="{{ $count }}" />
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category_id" class="control-label">Category</label>
                        <select name="category_id" class="form-control" id="category_id">
                        </select>
                        <span class="error" role="alert">
                            @error('category_id')
                                {{ $message }}</br>
                            @enderror
                        </span>
                    </div>
                </div>
                <input type="hidden" id="category_text" name="category_text" />

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="image" class="control-label">Image</label><br>
                        <img src="{{ file_asset('news-files-post', $newsDraft->image) }}" alt="news-img"
                            class="img-thumbnail" width="100" height="150">
                        <input id="image" type="file" name="image" class="form-control">
                        <span class="error" role="alert">
                            @error('image')
                                {{ $message }}</br>
                            @enderror
                        </span>
                    </div>
                </div>
                <div class="col-md-4">
                    <x-form-select field="language" field-name="Language" defaultPrompt="Select language">
                        @foreach (config('params.news.posts.language') as $value => $label)
                            <option {{$newsDraft->language == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
            </div>
            <div class="row">
            <div class="col-md-4">
                    <x-form-input type="text" field="photo_caption" field-name="Photo Caption" 
                        value="{{$newsDraft->photo_caption?? old('photo_caption') }}">
                    </x-form-input>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                            <label for="author_image" class="control-label">Author Image (maximum limit : 2MB)</label><br>
                            <img src="{{ file_asset('news-files-post',$newsDraft->author_image) }}" alt="author-img"
                                class="img-thumbnail" width="100" height="150">
                            <input id="author_image" type="file" name="author_image" class="form-control">
                            <span class="error" role="alert">
                                @error('author_image')
                                    {{ $message }}</br>
                                @enderror
                            </span>
                        </div>
                    </div>
                <div class="col-md-4">
                    <x-form-input type="text" field="author_name" field-name="Author Name" 
                        value="{{ $newsDraft->author_name ?? old('author_name')}}">
                    </x-form-input>
                </div>
             </div>
            <div class="row">
                <div class="col-md-12">
                    <x-form-input type="text" field="title" field-name="Title" value="{{ $newsDraft->title }}">
                    </x-form-input>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
               
                        
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <label for="content">Content</label>
                                    <div class="card-body">
                                        <textarea class="summernote form-control" rows="9" name="content">{{$newsDraft->html_content}}</textarea>
                                    </div>
                                    <span class="error" role="alert">
                
                                        @error('content')
                                            {{ $message }}</br>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                        </div> 
                  
                </div>
            </div>
          
            <div class="row">
                <div class="col-md-12">
                    <label class="control-label" for="permissions">Publishable Countries</label>
                    <div class="row">
                        @php
                            $allCheck = '';
                            if ($count == $selectedCount) {
                                $allCheck = 'checked';
                            }
                        @endphp
                        <div class="form-check form-check-inline col-md-4">
                            <input class="form-check-input" type="checkbox" name="all_countries" id="all_countries"
                                value="-1" onclick="selectAllCountries()" {{ $allCheck }}>
                            &nbsp;<label class="form-check-label" for="all_countries" style="color: black;">
                                All Countries
                            </label>
                        </div>
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($countries as $country)
                            @php
                                $attrCheck = in_array($country->id, $currentCountries) ? 'checked' : '';
                            @endphp
                            <div class="form-check form-check-inline col-md-4">
                                <input class="form-check-input" type="checkbox" name="country_id[]"
                                    id="country_id_{{ $i }}" value="{{ $country->id }}"
                                    {{ $attrCheck }}>
                                &nbsp;<label class="form-check-label" for="country_id_{{ $i }}"
                                    style="color: black;">
                                    {{ $country->name }}
                                </label>
                            </div>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    </div>
                    <span class="error" role="alert">
                        @error('country_id')
                            {{ $message }}</br>
                        @enderror
                    </span>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="scheduled_date" class="control-label">Scheduled Date*</label>
                        <div class="input-group">
                            <input type="datetime-local" name="scheduled_date" id="scheduled_date" class="form-control"
                                value="{{ $newsDraft->scheduled_date }}">
                            <!-- <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div> -->
                        </div>
                        @error('scheduled_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="expiry_date" class="control-label">Expiry Date*</label>
                        <div class="input-group">
                            <input type="datetime-local" name="expiry_date" id="expiry_date" class="form-control"
                                value="{{$newsDraft->expiry_date}}">
                            <!-- <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event"></i></span>
                            </div> -->
                        </div>
                        @error('expiry_date')
                            <span class="error" role="alert">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                {{--    <div class="col-md-4">
                    <x-form-input type="text" field="sort_order" field-name="Sort Order" value="{{ $newsDraft->sort_order }}"></x-form-input>
                </div> --}}
                <div class="col-md-3">
                    <x-form-select field="status" field-name="Status" defaultPrompt="Select status">
                        @foreach (config('params.news.draft.status') as $value => $label)
                            <option {{ $newsDraft->status == $value ? 'Selected' : '' }} value="{{ $value }}">
                                {{ $label }}</option>
                        @endforeach
                    </x-form-select>
                </div>
                <div class="col-md-3"
                    style="display: flex;
                    align-items: center;
                padding-left: 2em;">
                        <input class="form-check-input" type="checkbox" name="notification" value="1">Allow Notification?
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
                    ['para', ['ul', 'ol']],
                    // ['height', ['height']]
                    ['insert', [ 'picture']],
                ]
            });

        });
    </script>

        <script type="application/javascript">
             let scheduled_date= document.getElementById('scheduled_date');
            let expiry_date=document.getElementById('expiry_date');

            scheduled_date.addEventListener('change', function() {

                let selectedDateTime = new Date(scheduled_date.value);
                
            });
            expiry_date.addEventListener('change', function() {

                let selectedDateTime = new Date(expiry_date.value);

            });


            // $("#expiry_date").datepicker({
            //     format: 'dd-mm-yyyy',
            //     startDate: 'today',
                
            // });

            // $('#country_id').select2({
            //     allowClear: true,
            //     placeholder: "Search country",
            //     minimumInputLength: 1,
            //     ajax: {
            //         url: "{{ route('admin.country.select') }}",
            //         dataType:'json',
            //         data: function (params) {
            //             var query = {
            //                 search: params.term,
            //                 page: params.page || 1,
            //             }

            //             // Query parameters will be ?search=[term]&page=[page]
            //             return query;
            //         }
            //     }
            // });

          

            $('#category_id').select2({
                
                placeholder: "Search category",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.news.category.select') }}",
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1,
                           // country_id: $("#country_id").val()
                        }

                        // Query parameters will be ?search=[term]&page=[page]
                        return query;
                    }
                }
            });
           
            const currentCategory = JSON.parse('{!! $currentCategory !!}');
            const categoryOption = new Option(currentCategory.text, currentCategory.id, true, true);
            $('#category_id').append(categoryOption).trigger('change');

               //  selectAllCountries();
            function selectAllCountries()
            {
                if ($("#all_countries").prop("checked")) {
                    var count = document.getElementById("count").value;
                    for(var i = 1; i <= count; i++) {
                        $("#country_id_"+i).attr("disabled", true)
                        $("#country_id_"+i).prop("checked", true);
                    }
                }
                else {
                    var count = document.getElementById("count").value;
                    for(var i = 1; i <= count; i++) {
                        $("#country_id_"+i).attr("disabled", false);
                        $("#country_id_"+i).prop("checked", false);
                    }
                }
            }

          
        </script>
    </x-slot>
</x-admin-layout>
