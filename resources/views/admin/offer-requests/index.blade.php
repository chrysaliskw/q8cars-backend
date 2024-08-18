<x-admin-layout title="Offer Requests">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Offer Requests</li>
    </x-slot>
    <x-crud-index title="Offer Requests" createUrl="">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>
    <x-slot name="scripts">
        {!! $grid->scripts() !!}  
    </x-slot>
</x-admin-layout>
