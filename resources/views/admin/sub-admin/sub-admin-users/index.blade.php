<x-admin-layout title="Sub Admins">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Sub Admins</li>
    </x-slot>
    <x-crud-index title="Sub Admins" createUrl="{{ route('admin.sub-admin.admin.create') }}" createButtonText="Add Sub Admin">
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
