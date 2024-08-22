<x-admin-layout title="Trashed Brands">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Trashed Brands</li>
    </x-slot>
    <x-crud-index title="Trashed Brands">
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
                        const url = "{{ route('admin.trash-brand.edit','__id__') }}".replace('__id__',$(identifier).data().id);
                        window.location.href = url;
                    }
                });
            }
        </script>
    </x-slot>
</x-admin-layout>
