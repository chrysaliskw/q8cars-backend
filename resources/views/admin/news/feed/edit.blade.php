<x-admin-layout title="Online News">
   <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.news.post.index') }}">Online News</a></li>
        <li class="active">Update</li>
    </x-slot>
    {{--<div class="row">
            <div class="col-sm-12">
                <ol class="breadcrumb pull-right">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.news.feed.index') }}">Online News</a></li>
                    <li class="active">Update</li>
                </ol>
            </div>
        </div>--}}
        @php 
            $count = $count;
        @endphp
    <x-crud-update title="Online News">
        <x-form method="PUT" action="{{ route('admin.news.feed.update', $feed) }}" class="form" enctype="multipart/form-data">
            <div class="row">
                <input type="hidden" name="count" id="count" value="{{$count}}" />
                <div class="col-md-12">
                    <label class="control-label" for="permissions">Publishable Countries</label> 
                    <div class="row"> 
                            @php
                                $allCheck = '';
                                if($count == $selectedCount) {
                                    $allCheck = 'checked'; 
                                }
                            @endphp
                        <div class="form-check form-check-inline col-md-4">
                            <input class="form-check-input" type="checkbox" name="all_countries" 
                                id="all_countries" value="-1" onclick="selectAllCountries()" {{ $allCheck }}>
                                &nbsp;<label class="form-check-label" for="all_countries" style="color: black;">
                                    All Countries
                            </label> 
                        </div>
                        @php 
                            $i = 1;
                        @endphp
                        @foreach($countries as $country)
                            @php
                                $attrCheck = in_array($country->id, $currentCountries) ? 'checked' : '';
                            @endphp
                            <div class="form-check form-check-inline col-md-4">
                                <input class="form-check-input" type="checkbox" name="country_id[]" 
                                        id="country_id_{{ $i }}" value="{{ $country->id }}" {{ $attrCheck }}>
                                        &nbsp;<label class="form-check-label" for="country_id_{{ $i }}" style="color: black;">
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
               
            </div>
          <br>
            <x-form-submit>Save</x-form-submit>
        </x-form>
    </x-crud-update>
    
    <x-slot name="scripts">
        
        <script type="application/javascript">
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