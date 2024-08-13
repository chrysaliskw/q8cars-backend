<x-admin-layout title="Users">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Users</li>
    </x-slot>
    <x-crud-index title="" createUrl="{{ route('admin.user.create') }}" createButtonText="Add User" >
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
