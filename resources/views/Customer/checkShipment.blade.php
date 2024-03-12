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
                                    <h4 class="mb-sm-0 font-size-18">Customer's Shipment Details</h4>

                                    <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                        <li class="breadcrumb-item"><a href="/customers">Customers</a></li>
                                        <li class="breadcrumb-item"><a href="{{ url()->previous() }}">{{ $customer->first_name }} {{ $customer->last_name }}</a></li>
                                        <li class="breadcrumb-item active">{{$item_code}}</li>
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
                                            <a href="#uploadedNews">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Item Code</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 0, 0);">{{$item_code}}</span>
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
                                    <!-- Total Top Up By User -->
                                    <div class="col-lg-6">
                                        <div class="card blog-stats-wid">
                                            <a href="#totalTopUp">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Total Item</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 110, 253);">{{$amount}}</span>
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
                                        @foreach($shipmentDetail as $shipmentDetail)
                                        <div class="image-preview" id="imagePreview">
                                        </div>
                                        <br>
                                        <h4 class="card-title">{{ $shipmentDetail->item_description }}</h4>
                                        <p class="card-title-desc">{{$shipmentDetail->remarks}}</p>
                                        <br>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Delivery Person: </span>{{ $shipmentDetail->name }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Truck Plate No: </span>{{ $shipmentDetail->plate_no }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p>
                                                    <span style="font-weight: bold;">Status: </span>
                                                    @if ($shipmentDetail->delivery_status == '0')
                                                        <span class="text-primary">To Be Delivered</span>
                                                    @elseif ($shipmentDetail->delivery_status == '1')
                                                        <span class="text-warning">Delivering</span>
                                                    @elseif ($shipmentDetail->delivery_status == '2')
                                                    <span class="text-success">Delivered</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Departed Time: </span>{{ $shipmentDetail->departed_time }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Arrival Time: </span>{{ $shipmentDetail->delivered_time }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Customer: </span>{{ $shipmentDetail->last_name }}</p>
                                            </div>
                                            <br>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <a href="{{ url()->previous() }}"><button class="btn btn-secondary mr-1">Back</button></a>
                                                <a href="/get-gps-data/{{$shipmentDetail->imei}}"><button class="btn btn-primary mr-1">Get GPS Location</button></a>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                    </div> <!-- end col -->
                </div>

            @include('layout.footer')
            </div>
            <!-- end main content-->

            @include('layout.scripts')

    </body>

</html>