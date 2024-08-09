@props([
'title' => '',
'cardTitle' => '',
])
@php
    $cardTitle = $cardTitle != '' ? $cardTitle :  "Update ".$title;
@endphp

<x-card title="{{ $cardTitle }}">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            {{ $slot }}
        </div>
    </div>
</x-card>
