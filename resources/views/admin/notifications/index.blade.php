<x-admin-layout title="Notifications">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Notifications</li>
    </x-slot>
    <x-crud-index title="Notifications" createUrl="{{ route('admin.notifications.create') }}" createButtonText="Add Notification">
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
