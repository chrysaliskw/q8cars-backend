@props([
'title' => '',
'cardTitle' => '',
'restoreUrl' => '',
'id',
'data' => [],
])
@php
    $cardTitle = $cardTitle != '' ? $cardTitle :  $title;
@endphp

<x-card title="{{ $cardTitle }}">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="card card-border card-primary">
                <div class="card-header">
                    <div class="m-b-30">
                    
                       <a href="#"
                           class="btn btn-danger btn-custom waves-effect waves-light"
                           onclick="restoreData(this)" data-id="{{ $id }}"><i class="fa fa-undo"></i>
                            Restore</a>
                        
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-horizontal">
                    
                        @foreach($data as $key => $value)
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">{{ $key }}</label>
                                <div class="col-sm-8">
                                    {{ $value }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <script type="application/javascript">
            
            function restoreData(identifier)
            {
                swal.fire({
                    title: '<p class="font-weight-normal" style="font-size: 20px;">Are you sure?</p>',
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    showCancelButton: true
                }).then(function(result) {
                    if (result.value) 
                    {
                        const url = "{{ $restoreUrl }}";
                        window.location.href = url + '?id=' + $(identifier).data().id;
                    }
                });
            }

        </script>

</x-card>
