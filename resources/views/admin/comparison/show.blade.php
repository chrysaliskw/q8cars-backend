@php
    $showHidden = $viewData['Page'] == 'Detailed Page' ? true : false;
@endphp
<x-admin-layout title="Compare Cars">
    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.comparison.index') }}">Compare Cars</a></li>
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
                                onclick="showDeleteConfirmation(event)"
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
                    <div class="form-group row">
                                    <label class="col-sm-4 control-label">Status </label>
                                    <div class="col-sm-8">
                                        {{ $viewData['status']}}

                                    </div>
                                </div>
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
               <div class= 'modal fade' id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class= 'modal-dialog' role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Delete</h5>
                                <button type="button" class="close" data-dismiss='modal' aria-label="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Are you sure that you want to delete this item?                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Cancel </button>
                                <button type="button" class="btn btn-primary"
                                onclick="submitDeleteForm()">Delete</button>
                            </div>
                        </div>
                    </div>
            </div>

    </x-card>
    <x-slot name="scripts">
    <script>
        function showDeleteConfirmation(event) {
            event.preventDefault(); // Prevent default link behavior
            $('#deleteConfirmationModal').modal('show'); // Show Bootstrap modal
        }

        function submitDeleteForm() {
            document.querySelector('form#delete-form').submit(); // Submit the form
        }


    </script>
</x-slot></x-admin-layout>
