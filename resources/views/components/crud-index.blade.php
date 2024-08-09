@props([
'title' => '',
'cardTitle' => '',
'createUrl' => '',
'createButtonText' => '',
'deleteUrl' => '',
'deleteButtonText' => '',
])
@php
    $cardTitle = $cardTitle != '' ? $cardTitle :  $title;
@endphp

<x-card title="{{ $cardTitle }}" createUrl="{{ $createUrl }}" createButtonText="{{ $createButtonText }}"  deleteUrl="{{ $deleteUrl }}" deleteButtonText="{{ $deleteButtonText }}">
    
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            {{ $slot }}
        </div>
    </div>
</x-card>
