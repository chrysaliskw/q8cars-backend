@props([
    'title' => '', 
    'selectUrl' => '',
    'page' => '',
    'createUrl' => '',
    'createButtonText' => ''
])

@php
    $createButtonText = $createButtonText != '' ? $createButtonText : "Add";
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title float-left">{{ $title }}</h3>
     
       
        <div class="dt-buttons btn-group float-right">
            <div class="dt-buttons btn-group select-all-class" style="margin-right: 10px;margin-top:7px" id="selectBox">
            
           

                <div class="form-check news-publish-chk">
                    <input type="checkbox" onclick="selectAll(this)" data-page="{{ $page }}" class="form-check-input" id="checkAll"
                    name="checkAll" title='Select All'>
                    
                    <label class="form-check-label" for="exampleCheck1">Select All</label>
                </div>
         
            @if($createUrl)
                <a href="{{ $createUrl }}" class="btn btn-primary btn-rounded waves-effect waves-light publish-btn-class" style="border-radius: 15px;"> 
                   
                    {{ $createButtonText }} 
                    <i class="mdi mdi-plus-circle-outline"></i>
                </a>        
            @endif
        </div>
       
    </div>
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
