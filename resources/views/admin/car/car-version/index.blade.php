@if($carVersions) 
<div class="col-md-12 col-sm-12 col-12">
    <div class="card card-border card-primary">
        <div class="card-header">
            <div class="m-b-30">
                <h5>Car Versions</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <th>SI.No</th>
                        <th>Version Name </th>
                        <th>Engine Capacity </th>
                        <th>Transmission Type </th>
                        <th>Fuel Type </th>
                        <th>Mileage </th>
                        <!-- <th>Ex-Showroom Price </th>
                        <th>On Road Price </th> -->
                        <th>Status </th>
                        <th>Action </th>
                    </thead>
                    <tbody>
                        @php 
                            $key = 1;
                        @endphp
                        @foreach ($carVersions as $carVersion)
                        <tr>
                            <td>{{ $key}}</td>
                            <td>{{ $carVersion->varient_name }}</td>
                            <td>{{ $carVersion->engine_capacity }}  cc</td>
                            <td>{{ config('params.car.transmission_type')[$carVersion->transmission_type] }}</td>
                            <td>{{ config('params.car.fuel_type')[$carVersion->fuel_type] }}</td>
                            <td>{{ $carVersion->mileage }} KM/L</td>
                          {{-- <td>KWD {{ $carVersion->ex_showroom_price }}</td>
                            <td>KWD {{ $carVersion->on_road_price }}</td>---}} 
                            <td>{{ config('params.car.status')[$carVersion->status] }}</td>
                            <td>
                                <a class="btn btn-success btn-icon waves-effect waves-light m-b-5 mr-1"  
                                    title="View" target="_blank" 
                                    href="{{ route('admin.car-version.show', $carVersion) }}">
                                    <span class="ion-eye"></span>
                                </a>
                                <a class="btn btn-info btn-icon waves-effect waves-light m-b-5 mr-1"  
                                    title="Update" target="_blank" 
                                    href="{{ route('admin.car-version.edit', $carVersion) }}">
                                    <span class="ion-edit"></span>
                                </a>
                                <a class="btn btn-danger btn-icon waves-effect waves-light m-b-5 mr-1"  
                                    onclick="(function(){if(confirm('Are you sure?')){$('form#delete-version-form').submit()}})()"
                                    title="Delete" >
                                    <span class="ion-trash-a"></span>
                                </a>
                                <form id="delete-version-form"
                                    action="{{ route('admin.car-version.destroy', $carVersion) }}"
                                    method="POST" style="display: none;">
                                    @csrf
                                    @method('delete')
                                </form>
                            </td>
                        </tr>
                        @php 
                            $key++;
                        @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif

  

