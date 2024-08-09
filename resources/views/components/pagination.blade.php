@props([
    'data' => '',
])
<div class="row">
    <div class="col-sm-12 col-md-7 float-right">
        {{ $data->links() }}
    </div>
    <div class="col-sm-12 col-md-5 text-right p-2">
            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} out of {{ $data->total() }} results
    </div>
</div>