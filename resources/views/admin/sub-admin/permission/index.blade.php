<x-admin-layout title="Permissions">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Permissions</li>
    </x-slot>
    <x-crud-index title="Permissions" createUrl="{{ route('admin.sub-admin.permission.create') }}" createButtonText="Add Permission" >
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
