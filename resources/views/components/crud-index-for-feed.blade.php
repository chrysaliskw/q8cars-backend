@props([
'title' => '',
'cardTitle' => '',
'createUrl' => '',
'createButtonText' => '',
'selectUrl' => '',
'page' => '',
])
@php
    $cardTitle = $cardTitle != '' ? $cardTitle :  $title;
@endphp

<x-card-for-feed title="{{ $cardTitle }}" createUrl="{{ $createUrl }}" createButtonText="{{ $createButtonText }}"
    selectUrl="{{ $selectUrl }}" page="{{ $page }}" >
    <div class="row" style="width:100%">
        <div class="col-md-12 col-sm-12 col-12">
            {{ $slot }}
        </div>
    </div>
</x-card-for-feed>
