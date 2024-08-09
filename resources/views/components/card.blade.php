@props([
    'title' => '', 
    'createUrl' => '',
    'createButtonText' => '',
    'deleteUrl' => '',
    'deleteButtonText' => ''
])

@php
    $createButtonText = $createButtonText != '' ? $createButtonText : "Add";
    $deleteeButtonText = $deleteButtonText != '' ? $deleteButtonText : "Delete";
    
@endphp
<div class="card">
    <div class="card-header">
        <div class="header-action d-flex align-items-center justify-content-end">
        <div class="dt-buttons btn-group">
            @if($deleteUrl)
                
                    <a href="#"
                    onclick= "event.preventDefault();(function(){if (ids.length<=0){
                            document.getElementById('error_text').style.display;
                            document.getElementById('error_text').style.color='#721c24';
                            document.getElementById('error_text').style.backgroundColor='#f8d7da';
                            document.getElementById('error_text').style.padding='15px';
                            document.getElementById('error_text').value= 'Please select at least one item to delete';}
                              else if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                        class="btn btn-danger btn-rounded waves-effect waves-light" style="border-top-right-radius: 30px;
                        border-bottom-right-radius: 30px;margin-right:15px;"><i class="fa fa-trash"></i>
                        Delete selected </a>
                            <form id="delete-form"
                                action="{{ $deleteUrl}}"
                                method="post" style="display: none;">
                                <input type="hidden" name="_hidden_field" id="_hidden_field" value="" >        
                                @csrf
                                {{-- @method('delete') --}}  
                                </form>     
            @endif
            </div>
            <div class="dt-buttons btn-group ">
                @if($createUrl)
                    <a href="{{ $createUrl }}" class="btn btn-primary btn-rounded waves-effect waves-light"> 
                        <i class="fa fa-plus pr-2"></i>
                        {{ $createButtonText }} 
                        <i class="mdi mdi-plus-circle-outline"></i>
                    </a>        
                @endif
            </div>
        </div>
            
    </div>
    <input type="text" name="" id="error_text" value=""  style=" border:hidden;">
    <div class="card-body">
    
        {{ $slot }}
    </div>
</div>
<script>
    var errorTextElement = document.getElementById('error_text');
    errorTextElement.readOnly = true;
 </script>
 