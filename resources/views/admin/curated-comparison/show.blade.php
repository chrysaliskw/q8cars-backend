    @php

        $displaybrand3 = !empty($curatedComparison->brand_id_3) ? 'display: flex;' : 'display: none;';
        $displaycar3 = !empty($curatedComparison->car_id_3) ? 'display: flex;' : 'display: none;';
        $displayimage3 = !empty($curatedComparison->image_3) ? 'display: flex;' : 'display: none;';
        $displayimage2 = !empty($curatedComparison->image_2) ? 'display: flex;' : 'display: none;';

    @endphp
    {{-- @dd($displaybrand3, $displaycar3, $displayimage3); --}}
    <x-admin-layout title="Curated Comparison">
        <x-slot name="breadcrumb">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.curated-comparison.index') }}">Curated Comparison</a></li>
            <li class="active">View</li>
        </x-slot>

        <x-card title="">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="card card-border card-primary">
                        <div class="card-header">
                            <div class="m-b-30">
                                <a href="{{ route('admin.curated-comparison.edit', $curatedComparison) }}"
                                    class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i>
                                    Edit</a>
                                <a href="#" onclick="showDeleteConfirmation(event)"
                                    class="btn btn-danger btn-custom waves-effect waves-light">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                                <form id="delete-form"
                                    action="{{ route('admin.curated-comparison.destroy', $curatedComparison) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('delete')
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-horizontal">
                                {{-- car1 --}}
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Image 1</label>
                                    <div class="col-sm-8">
                                        <img src="{{ file_asset('files-curated_comparisons', $viewData['image_1']) }}"
                                            alt='image_1' class='img-thumbnail' width='100' height='150'>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Brand 1</label>
                                    <div class="col-sm-8">
                                        {{ $viewData['Brand 1'] }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Car 1</label>
                                    <div class="col-sm-8">
                                        {{ $viewData['Car 1'] }}
                                    </div>
                                </div>
                                {{-- car 2 --}}
                                <div class="form-group row" style="{{ $displayimage2 }}">
                                    <label class="col-sm-4 control-label">Image 2</label>
                                    <div class="col-sm-8">
                                        <img src="{{ file_asset('files-curated_comparisons', $viewData['image_2']) }}"
                                            alt='image_2' class='img-thumbnail' width='100' height='150'>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Brand 2</label>
                                    <div class="col-sm-8">
                                        {{ $viewData['Brand 2'] }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Car 2</label>
                                    <div class="col-sm-8">
                                        {{ $viewData['Car 2'] }}
                                    </div>
                                </div>


                                {{-- car 3 --}}
                                <div class="form-group row" style="{{ $displayimage3 }}">
                                    <label class="col-sm-4 control-label">Image 3</label>
                                    <div class="col-sm-8">
                                        <img src="{{ file_asset('files-curated_comparisons', $viewData['image_3']) }}"
                                            alt='image_3' class='img-thumbnail' width='100' height='150'>
                                    </div>
                                </div>
                                <div class="form-group row" style="{{ $displaybrand3 }}">
                                    <label class="col-sm-4 control-label">Brand 3</label>
                                    <div class="col-sm-8">
                                        {{ $viewData['Brand 3'] }}
                                    </div>
                                </div>
                                <div class="form-group row" style="{{ $displaycar3 }}">
                                    <label class="col-sm-4 control-label">Car 3</label>
                                    <div class="col-sm-8">
                                        {{ $viewData['Car 3'] }}
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Source</label>
                                    <div class="col-sm-8">
                                        {{ $viewData['Source'] }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Title</label>
                                    <div class="col-sm-8">
                                        {{ $viewData['title'] }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Content</label>
                                    <div class="col-sm-8">
                                        {!! $curatedComparison->html_content !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Published Date</label>
                                    <div class="col-sm-8">
                                        {{ date('d-m-Y', strtotime($curatedComparison->published_date)) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Status</label>
                                    <div class="col-sm-8">
                                        {!! $viewData['status'] !!}

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Created Date</label>
                                    <div class="col-sm-8">
                                        {{ date('d-m-Y h:i A', strtotime($curatedComparison->created_at)) }}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 control-label">Updated Date</label>
                                    <div class="col-sm-8">
                                        {{ date('d-m-Y h:i A', strtotime($curatedComparison->updated_at)) }}
                                    </div>
                                </div>
                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure that you want to delete this item?                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Cancel</button>
                                                <button type="button" class="btn btn-danger"
                                                    onclick="submitDeleteForm()">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>




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
