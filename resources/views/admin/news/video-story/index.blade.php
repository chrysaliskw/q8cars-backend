<x-admin-layout title="Video Stories">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="active">Video Stories</li>
    </x-slot>
   
    <x-crud-index title="" createUrl="{{ route('admin.news.video.create') }}" createButtonText="Add Video Story">
        <div class="row">
            <div class="col-lg-12">
                {!! $grid->render() !!}
            </div>
        </div>
    </x-crud-index>
    <x-slot name="scripts">

        {!! $grid->scripts() !!}
        <script type="application/javascript">
            function unsetBreaking(identifier)
            {
              swal.fire({
                  title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                  confirmButtonText: 'Yes',
                  cancelButtonText: 'No',
                  showCancelButton: true,
               
              }).then(function(result) {
                  if (result.dismiss) 
                  {
                  return;
                }else{
                    
                      const url = "{{ route('admin.news.video.breaking') }}";
                      window.location.href = url + '?id=' + $(identifier).data().id;
                }
              });
           }

           function setBreaking(identifier){
               
            swal.fire({
                  title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                  confirmButtonText: 'Yes', 
                  cancelButtonText: 'No',
                  showCancelButton: true,
               
              }).then(function(result) {
                  if (result.value) { 
                      const url = "{{ route('admin.news.video.breaking') }}";
                      window.location.href = url + '?id=' + $(identifier).data().id;
                }
              });
           }

           </script>

    </x-slot>
</x-admin-layout>
