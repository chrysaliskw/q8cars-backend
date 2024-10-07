<x-admin-layout title="Car Comparison">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Car Comparison</li>
    </x-slot>
    <x-crud-index title="" createUrl="{{ route('admin.comparison.create') }}" createButtonText="Add Compare Car">
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
