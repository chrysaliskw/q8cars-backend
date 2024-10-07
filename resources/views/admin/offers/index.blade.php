<x-admin-layout title="Offers">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Offers</li>
    </x-slot>
    @php
        $statuses = config('params.offers.status');
    @endphp
    <x-crud-index title="Offers" createUrl="{{ route('admin.offers.create') }}" createButtonText="Add Offer">
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
