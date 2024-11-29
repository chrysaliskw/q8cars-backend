<x-admin-layout title="Permissions">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.sub-admin.permission.index') }}">Permissions</a></li>
        <li class="active">View</li>
    </x-slot>
    <x-crud-show-without-edit
            title="Permission"
            
            :deleteUrl="route('admin.sub-admin.permission.destroy', $permission)"
            :data="$viewData"
            >
    </x-crud-show-without-edit>
</x-admin-layout>

