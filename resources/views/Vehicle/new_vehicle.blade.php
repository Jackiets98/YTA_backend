<!doctype html>
<html lang="en">

@include('layout.header')

<style>
    div.dataTables_wrapper div.dataTables_filter {
        margin-top: -17px;
        margin-right: 8px;
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
                                    <h4 class="mb-sm-0 font-size-18">Vehicle</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Vehicles</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="col-3">
                            @if (session()->has('success'))
                            <div class="alert alert-success" id="alert">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                {{ session()->get('success') }}
                            </div>
                            @endif
                        </div>

                        <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title">Let's Add A New Vehicle!</h4>
                                    <br>
                                    <form action="{{url('/addNewVehicle')}}" method="POST">
                                        @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="address">VIN No <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="vin_no" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Plate No <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="plate_no" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Contract No <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="contract_no" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="address">Vehicle Owner <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="vehicle_owner" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Contact Person <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="contact_person">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Phone No <span style="color: red;">*</span></label>
                                            <input type="number" class="form-control" name="phone_no" required>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="address">Vehicle Brand</label>
                                            <input type="text" class="form-control" name="vehicle_brand">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Vehicle Color</label>
                                            <input type="color" class="form-control" name="vehicle_color" style="height: 38px">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">Current Vehicle Mileage</label>
                                            <input type="text" class="form-control" name="vehicle_mileage" style="height: 38px">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="address">Device Name <span style="color: red;">*</span></label>
                                            <input type="text" class="form-control" name="device_name" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">IMEI <span style="color: red;">*</span></label>
                                            <input type="number" class="form-control" name="imei" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="address">SIM Card No <span style="color: red;">*</span></label>
                                            <input type="number" class="form-control" name="sim_no" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea class="form-control" name="remarks" rows="2"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-outline-success" style="float:right">Create Vehicle</button>
                                </form>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> 
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

    @include('layout.footer')
            </div>
            <!-- end main content-->

    @include('layout.scripts')
    </body>

</html>