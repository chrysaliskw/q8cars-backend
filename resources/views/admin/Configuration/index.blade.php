<x-admin-layout title="Configurations">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.brand.index') }}">Configurations</a></li>
        <li class="active">Update</li>
    </x-slot>

    <x-crud-update title="Configurations">
        <x-form method="POST" action="{{ route('admin.configurations.save') }}" class="form" enctype="multipart/form-data">
            <div class="row">
                {!! $html !!}
            </div>
            <x-form-submit>Save</x-form-submit>
            </div>
        </x-form>
    </x-crud-update>

    <x-slot name="scripts">

    </x-slot>

</x-admin-layout>
