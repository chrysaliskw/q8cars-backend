@php
    $showHidden = $viewData['Page'] == 'Detailed Page' ? true : false;
@endphp
<x-admin-layout title="Car Comparison">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.comparison.index') }}">Car Comparison</a></li>
        <li class="active">View</li>
    </x-slot>

    <x-card title="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">
                    <div class="card-header">
                        <div class="m-b-30">
                            <a href="{{ route('admin.comparison.edit', $id) }}"
                                class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                            <a href="#"
                                onclick="(function(){if(confirm('Are you sure?')){$('form#delete-form').submit()}})()"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                            <form id="delete-form" action="{{ route('admin.comparison.destroy', $id) }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <table class="table table-bordered table-striped table-hover">
                                <thead style="color: #bc1d1d; font-weight: bold;">
                                    <td colspan="3" style="padding: 15px; font-size: 16px;">
                                        <strong style="color: #bc1d1d; font-weight: bold;">Page </strong>: <span
                                            style="padding-left: 10px; color: #000;">{!! $viewData['Page'] !!}</span><br>
                                    </td>
                                    <tr>
                                        
                                        <th style=" @if(!$showHidden) display: none @endif" class="main-car-tabel">MAIN CAR</th>
                                        <th>CAR 1</th>
                                        <th>CAR 2</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="padding: 15px; @if(!$showHidden) display: none @endif; text-align: center; vertical-align: middle;" class="main-car-tabel">
                                            <img src="{{ file_asset('files-car', $viewData['Car Image']) }}"
                                                alt="noimage.webp" style="margin: 10px; width: 215px; height: 136px;"><br>
                                        </td>
                                        <td style="padding: 15px; text-align: center; vertical-align: middle;">
                                            <img src="{{ file_asset('files-car', $viewData['Car 1 Image']) }}"
                                                alt="noimage.webp" style="margin: 10px; width: 215px; height: 136px;"><br>
                                        </td>
                                        <td style="padding: 15px; text-align: center; vertical-align: middle;">
                                            <img src="{{ file_asset('files-car', $viewData['Car 2 Image']) }}"
                                                alt="noimage.webp" style="margin: 10px; width: 215px; height: 136px;"><br>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td style="padding: 15px; @if(!$showHidden) display: none @endif" class="main-car-tabel">
                                            <strong style="color: #bc1d1d; font-weight: bold;">Main Brand</strong>:
                                            <span style="padding-left: 10px;">{!! $viewData['Main Brand'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Main Car</strong>:
                                            <span style="padding-left: 10px;">{!! $viewData['Car Model'] !!}</span><br>
                                        </td>
                                        <td style="padding: 15px;">
                                            <strong style="color: #bc1d1d; font-weight: bold;">Brand 1</strong>:
                                            <span style="padding-left: 10px;">{!! $viewData['Brand 1'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Car 1 Model</strong>:
                                            <span style="padding-left: 10px;">{!! $viewData['Car 1 Model'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Car version 1</strong>:
                                            <span style="padding-left: 10px;">{!! $viewData['Car version 1'] !!}</span><br>
                                        </td>
                                        <td style="padding: 15px;">
                                            <strong style="color: #bc1d1d; font-weight: bold;">Brand 2</strong>:
                                            <span style="padding-left: 10px;">{!! $viewData['Brand 2'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Car 2 Model</strong>:
                                            <span style="padding-left: 10px;">{!! $viewData['Car 2 Model'] !!}</span><br>
                                            <strong style="color: #bc1d1d; font-weight: bold;">Car version 2</strong>:
                                            <span style="padding-left: 10px;">{!! $viewData['Car version 2'] !!}</span><br>
                                        </td>
                                    </tr>
                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>

    </x-card>
    <x-slot name="scripts">
    <script>

        // $(document).ready(function() {
        //     if ("{!! $viewData['Page'] !!}" == 'Detailed Page') {
        //         $('.main-car-tabel').show();
        //     } else {
        //         $('.main-car-tabel').hide();
        //     }
        // });
    </script>
</x-slot></x-admin-layout>
