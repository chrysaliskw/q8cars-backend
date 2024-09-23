<x-admin-layout title="Drafted News">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Drafted News</li>
    </x-slot>
    {{-- <div class="row">
            <div class="col-sm-12">
                <!-- <h4 class="pull-left page-title">News</h4> -->
                <ol class="breadcrumb pull-right">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="active">News</li>
                </ol>
            </div>
        </div>--}}
    <x-crud-index title="" createUrl="{{ route('admin.news.draft.create') }}" createButtonText="Add News">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>
    <x-slot name="scripts">

        {!! $grid->scripts() !!}

        <script type="application/javascript">
            
            function  publishDraftNews(identifier)
            {
                swal.fire({
                    title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    showCancelButton: true
                }).then(function(result) {
                    if (result.value) 
                    {
                        const url = "{{ route('admin.news.draft.publish') }}";
                        window.location.href = url + '?id=' + $(identifier).data().id;
                    }
                });
            }
       </script>

    </x-slot>
</x-admin-layout>
