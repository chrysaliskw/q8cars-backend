<x-admin-layout title="Colors">

    <x-slot name="breadcrumb">
        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li><a href="{{ route('admin.color.index') }}">Colors</a></li>
        <li class="active">View</li>
    </x-slot>
    
   <x-card title="">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <div class="card card-border card-primary">

                 <div class="card-header">
                        <div class="m-b-30">
                            <a href="{{ route('admin.color.edit', $color) }}"
                                class="btn btn-primary waves-effect waves-light"><i class="fa fa-pencil"></i> Edit</a>
                                <a href="#" onclick="showDeleteConfirmation(event)"
                                class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i>
                                Delete</a>
                                <form id="delete-form"
                                action="{{ route('admin.color.destroy', $color) }}"
                                method="POST" style="display: none;">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-horizontal">

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Name</label>
                                <div class="col-sm-8">
                                    {{ $color->name }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Color Code</label>
                                <div class="col-sm-8">
                                    {{ $color->code }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Brand</label>
                                <div class="col-sm-8">
                                    {{ $color->brand->name }}
                                </div>
                            </div>
                          

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Status</label>
                                <div class="col-sm-8">
                                    {{ config('params.brand_color.status')[$color->status] }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Created At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($color->created_at) }}
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 control-label">Updated At</label>
                                <div class="col-sm-8">
                                    {{ dateTimeFormat($color->updated_at) }}
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
                                        Are you sure that you want to delete this item?
                                     </div>
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
    </x-slot>
</x-admin-layout>

