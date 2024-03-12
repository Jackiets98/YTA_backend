<!doctype html>
<html lang="en">

@include('layout.header')

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>

    div.dataTables_wrapper div.dataTables_filter{
        margin-right:7px;
    }


    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    }

</style>
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Vehicle's Details</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="/vehicles">Vehicles</a></li>
                                            <li class="breadcrumb-item active">{{$vin_no}}</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <!-- Vin No -->
                                    <div class="col-lg-3">
                                        <div class="card blog-stats-wid">
                                            <a href="#uploadedNews">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Vin No</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 0, 0);">{{$vin_no}}</span>
                                                        </h5>
                                                    </div>

                                                    <div class="avatar-sm ms-auto">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                            <i class='bx bx-news bx-rotate-90 bx-tada'
                                                                style='color:#131313'></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Plate No -->
                                    <div class="col-lg-3">
                                        <div class="card blog-stats-wid">
                                            <a href="#totalTopUp">
                                            <div class="card-body">
                    
                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Plate No</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 110, 253);">{{$plate_no}}</span>
                                                        </h5>
                                                    </div>

                                                    <div class="avatar-sm ms-auto">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                            <i class='bx bxs-bank bx-rotate-90 bx-tada'
                                                                style='color:#356eec'></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Today's Mileage -->
                                    <div class="col-lg-3">
                                        <div class="card blog-stats-wid">
                                            <a href="">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Today's Mileage</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 0, 0);">{{$distance}} KM</span>
                                                        </h5>
                                                    </div>

                                                    <div class="avatar-sm ms-auto">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                            <i class="fa-regular fa-road"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Total Mileage -->
                                    <div class="col-lg-3">
                                        <div class="card blog-stats-wid">
                                            <a href="">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Total Mileage</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 0, 0);">{{$totalMileage}} KM</span>
                                                        </h5>
                                                    </div>

                                                    <div class="avatar-sm ms-auto">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                            <i class="fa-regular fa-road"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div>
                            <div class="col-12">
                                @if (session()->has('success'))
                                <div class="alert alert-success" id="alert">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    {{ session()->get('success') }}
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="engine-status">
                                            <strong>Current Engine Status:</strong>
                                            @if ($vehicleDetail->isEmpty())
                                                <span class="text-muted">Unknown</span>
                                            @else
                                                @foreach($vehicleDetail as $vehicle)
                                                    @if ($vehicle->last_param == 1)
                                                        <span class="text-success">Turned On</span>
                                                    @elseif ($vehicle->last_param == 2)
                                                        <span class="text-danger">Cut-Off</span>
                                                    @else
                                                        <span class="text-muted">Unknown</span>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        @foreach($vehicleDetail as $vehicleDetail)
                                        <div class="image-preview" id="imagePreview">
                                        </div>
                                        <br>
                                        <h4 class="card-title">{{ $vehicleDetail->imei }}</h4>
                                        <p class="card-title-desc">{{$vehicleDetail->remarks}}</p>
                                        <br>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Vehicle Owner: </span>{{ $vehicleDetail->vehicle_owner }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Contact Number: </span>{{ $vehicleDetail->phone_no }}</p>
                                            </div>
                                            <div class="col-6">                              
                                                <p><span style="font-weight: bold;">Vehicle Color: </span><div style="display: inline-block; width: 30px; height: 30px; border-radius: 50%; background-color: {{ $vehicleDetail->vehicle_color }};"></div></p>
                                            </div>
                                            <div class="col-6">
                                                <p>
                                                    <span style="font-weight: bold;">Status: </span>
                                                    @if ($vehicleDetail->status == '0')
                                                        <span class="text-danger">Deactivated</span>
                                                    @elseif ($vehicleDetail->status == '1')
                                                        <span class="text-success">Active</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Device Name: </span>{{ $vehicleDetail->device_name }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Sim No: </span>{{ $vehicleDetail->sim_no }}</p>
                                            </div>
                                            <br>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <a href="/vehicles"><button class="btn btn-secondary mr-1">Back</button></a>
                                                <button type="button" class="btn btn-warning mr-1"
                                                data-toggle="modal" data-target="#exampleModalCenter">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <a href="/get-vehicle-data/{{$vehicleDetail->imei}}"><button class="btn btn-primary mr-1">Get GPS Location</button></a>
                                            <a href="/get-vehicle-dashcam/{{$id}}"><button class="btn btn-info mr-1">Dashcam</button></a>
                                            <a href="{{ route('updateEngineStatus', ['imei' => $vehicle->imei]) }}">
                                                <button class="btn
                                                    @if ($vehicle->last_param == 1)
                                                        btn-danger
                                                    @elseif ($vehicle->last_param == 2)
                                                        btn-success
                                                    @else
                                                        btn-primary
                                                    @endif
                                                mr-1 float-end"
                                                onclick="return confirmAction();">
                                                    @if ($vehicle->last_param == 1)
                                                        Cut Off Engine
                                                    @elseif ($vehicle->last_param == 2)
                                                        Turn On Engine
                                                    @else
                                                        Null
                                                    @endif
                                                </button>
                                            </a>

                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                    </div> <!-- end col -->
                </div>


                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Update Current Vehicle's Information
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @foreach ($form as $vehicle)
                                <form method="POST" action="{{ url('/vehicleUpdate/'.$vehicle->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="address">VIN No <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="vin_no" value="{{$vehicle->vin_no}}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Plate No <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="plate_no" value="{{$vehicle->plate_no}}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Contract No <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="contract_no" value="{{$vehicle->contract_no}}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="address">Vehicle Owner <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="vehicle_owner" value="{{$vehicle->vehicle_owner}}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Contact Person <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="contact_person" value="{{$vehicle->contact_person}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Phone No <span style="color: red;">*</span></label>
                                            <input type="number" class="form-control" name="phone_no" value="{{$vehicle->phone_no}}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="address">Vehicle Brand</label>
                                            <input type="text" class="form-control" name="vehicle_brand" value="{{$vehicle->vehicle_brand}}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Vehicle Color</label>
                                            <input type="color" class="form-control" name="vehicle_color" value="{{$vehicle->vehicle_color}}" style="height: 38px">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Current Mileage</label>
                                            <input type="text" class="form-control" name="vehicle_mileage" value="{{$vehicle->vehicle_mileage}}" style="height: 38px">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="address">Device Name <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="device_name" value="{{$vehicle->device_name}}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">IMEI <span style="color: red;">*</span></label>
                                            <input type="number" class="form-control" name="imei" value="{{$vehicle->imei}}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">SIM Card No <span style="color: red;">*</span></label>
                                            <input type="number" class="form-control" name="sim_no" value="{{$vehicle->sim_no}}" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select class="form-control" name="status">
                                            <option value="0"  @if($vehicle->status == '0') selected @endif>Deactivated</option>       
                                            <option value="1"  @if($vehicle->status == '1') selected @endif>Active</option>               
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        @if ($vehicle->remarks == NULL)
                                        <textarea class="form-control" name="remarks" rows="4">No Remarks Yet.</textarea>  
                                        @else
                                        <textarea class="form-control" name="remarks" rows="4">{{$vehicle->remarks}}</textarea>
                                        @endif
                                    </div>                                         
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit"
                                            class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

            @include('layout.footer')
            </div>
            <!-- end main content-->

        @include('layout.scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            function confirmAction() {
                if (confirm("Are you sure you want to change the Engine Status?")) {
                    return true; // User confirmed, continue with the action
                } else {
                    return false; // User canceled, prevent the action
                }
            }
        </script>


    </body>

</html>