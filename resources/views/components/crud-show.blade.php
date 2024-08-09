@props([
'title' => '',
'cardTitle' => '',
'deleteUrl' => '',
'updateUrl' => '',
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
                        <a href="{{ $updateUrl }}"
                           class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                        <a href="#"
                           onclick="(function(){if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                           class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                            Delete</a>
                        <form id="delete-form"
                              action="{{ $deleteUrl }}"
                              method="POST" style="display: none;">
                            @csrf
                            @method('delete')
                        </form>
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
    </div>
</x-card>
