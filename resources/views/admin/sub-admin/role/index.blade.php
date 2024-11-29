<x-admin-layout title="Roles">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Roles</li>
    </x-slot>
    <x-crud-index title="Roles" createUrl="{{ route('admin.sub-admin.role.create') }}" createButtonText="Add Role">
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
