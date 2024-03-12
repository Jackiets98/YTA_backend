<!doctype html>
<html lang="en">

@include('layout.header')

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
                                    <h4 class="mb-sm-0 font-size-18">{{$name}}'s Details</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="/driverlists">Drivers</a></li>
                                            <li class="breadcrumb-item active">{{$name}}</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <!-- Total News uploaded By User -->
                                    <div class="col-lg-6">
                                        <div class="card blog-stats-wid">
                                            <a href="#none">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Total Shipment Delivered</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 0, 0);">{{$totalShipmentDelivered}}</span>
                                                        </h5>
                                                    </div>

                                                    <div class="avatar-sm ms-auto">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                            <i class='bx bx-package bx-rotate-90 bx-tada'
                                                                style='color:#131313'></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Total Top Up By User -->
                                    <div class="col-lg-6">
                                        <div class="card blog-stats-wid">
                                            <a href="#none">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Total Rating By Customers</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 110, 253);">{{$formattedRating}}</span>
                                                        </h5>
                                                    </div>

                                                    <div class="avatar-sm ms-auto">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                            <i class='bx bxs-star bx-rotate-90 bx-tada'
                                                                style='color:#356eec'></i>
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
                            <div class="col-3">
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
                                        @foreach($driverDetail as $driverDetail)
                                        <div class="image-preview" id="imagePreview">
                                            @if ($driverDetail->user_image != null)
                                            <img src="{{asset('drivers/'.$driverDetail->user_image)}}" alt="Image Preview"
                                                class="image-preview__image"  style="width:10%">
                                            @else
                                            <img src="{{asset('drivers/unknown_pic.webp')}}" alt="Image Preview"
                                                class="image-preview__image">
                                            @endif
    
                                        </div>
                                        <br>
                                        <h4 class="card-title">{{ $driverDetail->name }} {{ $driverDetail->surname }}</h4>
                                        @if($driverDetail->address != null && $driverDetail->postcode != null && $driverDetail->city != null &&
                                        $driverDetail->state != null)
                                        <p class="card-title-desc">{{ $driverDetail->address }}, {{ $driverDetail->postcode }},
                                            {{ $driverDetail->city }}, {{ $driverDetail->state }}</p>
                                        @else
                                        <p class="card-title-desc">Address Is Not Updated Yet</p>
                                        @endif
                                        <br>
                                        
                                        <div class="row">
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">IC No: </span>{{ $driverDetail->identity_card }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">License No: </span>{{ $driverDetail->license }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Phone Number: </span>{{ $driverDetail->phone_num }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p>
                                                    <span style="font-weight: bold;">Status: </span>
                                                    @if ($driverDetail->status == 1)
                                                        <span class="text-success">Active</span>
                                                    @elseif ($driverDetail->status == 0)
                                                        <span class="text-danger">Disabled</span>
                                                    @endif
                                                    @if ($driverDetail->status == 1)
                                            <a href="{{ route('driver.status.update',['id'=>$driverDetail->id, 'status_code'=>0]) }}"
                                                class="btn btn-sm btn-danger" style="margin-left: 20px"><i class="fa fa-ban"></i></a>
                                            @else
                                            <a href="{{ route('driver.status.update',['id'=>$driverDetail->id, 'status_code'=>1]) }}"
                                                class="btn btn-sm btn-success" style="margin-left: 20px"><i class="fa fa-check"></i></a>
                                            @endif
                                                </p>
                                            </div>
                                            <br>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <a href="/driverlists"><button class="btn btn-secondary mr-1">Back</button></a>
                                                <button type="button" class="btn btn-warning"
                                                data-toggle="modal" data-target="#exampleModalCenter">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            
                                            </div>
                                        </div>
                                        

    
                                        {{-- <table id="datatable-buttons"
                                            class="table table-bordered dt-responsive nowrap w-100">
                                            <thead>
                                                <tr>
                                                    <th>IC No.</th>
                                                    <th>License No.</th>
                                                    <th>Phone Number</th>
    
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
    
    
                                            <tbody>
                                                
                                                <tr>
                                                    <td>{{ $driverDetail->identity_card }}</td>
                                                    <td>{{ $driverDetail->license }}</td>
                                                    <td>{{ $driverDetail->phone_num }}</td>
    
                                                    @if ($driverDetail->status == 1)
                                                    <td class="status_role"><a href="javascript:void(0)"
                                                            class="badge badge-pill badge-success">Active</a>
                                                    </td>
                                                    @endif
                                                    @if ($driverDetail->status == 0)
                                                    <td class="status_role"><a href="javascript:void(0)"
                                                            class="badge badge-pill badge-danger">Disabled</a>
                                                    </td>
                                                    @endif
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-warning"
                                                            data-toggle="modal" data-target="#exampleModalCenter">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
    
                                                        @if ($driverDetail->status == 1)
                                                        <a href="{{ route('driver.status.update',['id'=>$driverDetail->id, 'status_code'=>0]) }}"
                                                            class="btn btn-sm btn-danger"><i class="fa fa-ban"></i></a>
                                                        @else
                                                        <a href="{{ route('driver.status.update',['id'=>$driverDetail->id, 'status_code'=>1]) }}"
                                                            class="btn btn-sm btn-success"><i class="fa fa-check"></i></a>
                                                        @endif
    
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table> --}}
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
                                    <h5 class="modal-title" id="exampleModalLongTitle">Update Current Driver's Information
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @foreach ($form as $user)
                                <form method="POST" action="{{ url('/driverUpdate/'.$user->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Name <span style="color: red;">*</span></label>
                                                <input value="{{ $user->name }}" class="form-control"
                                                    name="name" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Surname <span style="color: red;">*</span></label>
                                                <input value="{{ $user->surname }}" class="form-control"
                                                    name="surname" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>IC No. <span style="color: red;">*</span></label>
                                                <input value="{{ $user->identity_card }}" class="form-control" name="ic_no" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>License No. <span style="color: red;">*</span></label>
                                            <input value="{{ $user->license }}" class="form-control" name="license" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Email <span style="color: red;">*</span></label>
                                                <input value="{{ $user->email }}" class="form-control" name="email" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Phone Number <span style="color: red;">*</span></label>
                                            <input value="{{ $user->phone_num }}" class="form-control" name="phone" placeholder="Eg: 0123456789" maxlength="11" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Address <span style="color: red;">*</span></label>
                                            <input value="{{ $user->address }}" class="form-control" name="address" required>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>City <span style="color: red;">*</span></label>
                                                <input value="{{ $user->city }}" class="form-control" name="city" required>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label>State <span style="color: red;">*</span></label>
                                                <select name="state" class="form-control" required>
                                                    <option {{ $user->state == 'Johor' ? 'selected' : '' }}>Johor</option>
                                                    <option {{ $user->state == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                                    <option {{ $user->state == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                                    <option {{ $user->state == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                                    <option {{ $user->state == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                                    <option {{ $user->state == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                                    <option {{ $user->state == 'Perak' ? 'selected' : '' }}>Perak</option>
                                                    <option {{ $user->state == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                                    <option {{ $user->state == 'Pulau Pinang' ? 'selected' : '' }}>Pulau Pinang</option>
                                                    <option {{ $user->state == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                                    <option {{ $user->state == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                                    <option {{ $user->state == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                                    <option {{ $user->state == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                                    <option {{ $user->state == 'Wilayah Persekutuan Kuala Lumpur' ? 'selected' : '' }}>Wilayah Persekutuan Kuala Lumpur</option>
                                                    <option {{ $user->state == 'Wilayah Persekutuan Labuan' ? 'selected' : '' }}>Wilayah Persekutuan Labuan</option>
                                                    <option {{ $user->state == 'Wilayah Persekutuan Putrajaya' ? 'selected' : '' }}>Wilayah Persekutuan Putrajaya</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <label>Zip <span style="color: red;">*</span></label>
                                                <input value="{{ $user->postcode }}" class="form-control" name="postcode" required>
                                            </div>
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
    </body>

</html>