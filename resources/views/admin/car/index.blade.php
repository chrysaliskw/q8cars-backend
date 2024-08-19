<x-admin-layout title="Cars">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Cars</li>
    </x-slot>


    <x-crud-index title="" createUrl="{{ route('admin.car.create') }}" createButtonText="Add Car">
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
