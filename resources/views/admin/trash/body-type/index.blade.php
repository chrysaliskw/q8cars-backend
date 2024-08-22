<x-admin-layout title="Trashed Body Types">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Trashed Body Types</li>
    </x-slot>
    <x-crud-index title="Trashed Body Types">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>
    <x-slot name="scripts">

        {!! $grid->scripts() !!}
        <script type="application/javascript">

            function restoreUser(identifier)
            {
                console.log('svas');
                swal.fire({
                    title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    showCancelButton: true
                }).then(function(result) {
                    if (result.value)
                    {
                        const url = "{{ route('admin.trash-body-type.edit','__id__') }}".replace('__id__',$(identifier).data().id);
                        window.location.href = url;
                    }
                });
            }
        </script>
    </x-slot>
</x-admin-layout>
