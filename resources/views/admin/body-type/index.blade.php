<x-admin-layout title="Body Types">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Body Types</li>
    </x-slot>


    <x-crud-index title="" createUrl="{{ route('admin.body-type.create') }}" createButtonText="Add Body Types">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>

    <x-slot name="scripts">

        {!! $grid->scripts() !!}

        <script type="application/javascript">

        </script>
        
    </x-slot>
    
</x-admin-layout>
