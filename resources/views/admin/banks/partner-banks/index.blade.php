<x-admin-layout title="Partner Banks">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Partner Banks</li>
    </x-slot>
    <x-crud-index title="Partner Banks" createUrl="{{ route('admin.partner-banks.create') }}" createButtonText="Add Bank">
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
