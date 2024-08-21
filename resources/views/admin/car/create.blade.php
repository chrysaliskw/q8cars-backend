<x-admin-layout title="Car">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.car.index') }}">Cars</a></li>
        <li class="active">Create</li>
    </x-slot>
    
    <div id="loading-spinner" style=font-size:10px>
        <!-- Loading spinner -->
    </div>
    
    <div id="edit-page" class="row" style="display: none;">
        <div class="col-xl-12">
            <div class="card ">
                <div class="card-header">
                    <div class="dt-buttons float-right">
                        <button type="button" onclick="onSubmit()" id="submit-btn" class="btn btn-primary buttons-copy buttons-html5 btn-md">
                            Save
                        </button>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs tabs" role="tablist" id="business-user-profile-tab">
                <li class="nav-item tab">
                    <a class="nav-link active" id="about-tab-2" data-toggle="tab" href="#about-2" role="tab" 
                        onclick="onTab('about')" aria-controls="about-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-home"></i></span>
                        <span class="d-none d-sm-block">Basic Info</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="contact-tab-2" data-toggle="tab" href="#contact-2" role="tab" 
                        onclick="onTab('contact')" aria-controls="contact-2" aria-selected="true">
                        <span class="d-block d-sm-none"><i class="fa fa-user"></i></span>
                        <span class="d-none d-sm-block">Summary</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="images-tab-2" data-toggle="tab" href="#images-2" role="tab" 
                        onclick="onTab('images')" aria-controls="images-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                        <span class="d-none d-sm-block">Images & Videos</span>
                    </a>
                </li>
                <li class="nav-item tab">
                    <a class="nav-link" id="colors-tab-2" data-toggle="tab" href="#colors-2" role="tab" 
                        onclick="onTab('colors')" aria-controls="colors-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-envelope-o"></i></span>
                        <span class="d-none d-sm-block">Colors</span>
                    </a>
                </li>

            
                <li class="nav-item tab">
                    <a class="nav-link" id="product_service-tab-2" data-toggle="tab" href="#product_service-2" role="tab" 
                        onclick="onTab('product')" aria-controls="product_service-2" aria-selected="false">
                        <span class="d-block d-sm-none"><i class="fa fa-cog"></i></span>
                        <span class="d-none d-sm-block">Base Varient Details</span>
                    </a>
                </li>

             

            </ul>

            <div class="tab-content">
                <form method="POST" id="business-user-form" 
                    action="{{ route('admin.car.store') }}" 
                    class="form" enctype="multipart/form-data">
                        @csrf

                    <div class="tab-pane show active" id="about-2" role="tabpanel" aria-labelledby="about-tab-2">
                        @include('admin.car.create_basic_info')
                    </div>
                    <div class="tab-pane" id="contact-2" role="tabpanel" aria-labelledby="contact-tab-2">
                        @include('admin.car.create_summary')
                    </div>
                    <div class="tab-pane" id="images-2" role="tabpanel" aria-labelledby="images-tab-2">
                        @include('admin.car.create_images')
                    </div>
                    <div class="tab-pane" id="colors-2" role="tabpanel" aria-labelledby="colors-tab-2">
                        @include('admin.car.create_colors')
                    </div>
                 
                    <div class="tab-pane" id="product_service-2" role="tabpanel" aria-labelledby="product_service-tab-2">
                        @include('admin.car.create_key_features')
                    </div>

                 

                </form>
            </div>
        </div>
    </div>
    
  
    <x-slot name="scripts">
        
        
        <script defer type="application/javascript">

            /**
             * Loading spinner
             */
            function onReady(callback) {
                var intervalID = window.setInterval(checkReady, 1000);

                function checkReady() {
                    if (document.getElementsByTagName('body')[0] !== undefined) {
                        window.clearInterval(intervalID);
                        callback.call(this);
                    }
                }
            }

            function show(id, value) {
                document.getElementById(id).style.display = value ? 'block' : 'none';
            }

            onReady(function() {
                show('edit-page', true);
                show('loading-spinner', false);
            });

         

            function onSubmit()
            {
                document.getElementById("business-user-form").submit();
            }

          
            function deleteImage(id)
            {
                $("#image_preview_" + id).remove();
                $("#image_old_" + id).val('');
            }


         
           
          

            

            

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

            $('#body_type_id').select2({
                
                placeholder: "Search body type",
                minimumInputLength: 1,
                ajax: {
                    url: "{{ route('admin.body-type.select') }}",
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
            });

         
           
            if('{!! old("brand_id") !!}' && '{!! old("brand_id_text") !!}') {
                const countryOption = new Option('{{ old("brand_id_text") }}', '{{ old("brand_id") }}', true, true);
                $('#brand_id').append(countryOption).trigger('change');
                $("#brand_id_text").val('{{ old("brand_id_text") }}');
            }
        
     

       
            
        </script>

    </x-slot>

</x-admin-layout>