<!doctype html>
<html lang="en">

@include('layout.header')

<style>
    div.dataTables_wrapper div.dataTables_filter {
        margin-right: 7px;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* DataTable buttons */
    div.dataTables_wrapper div.dt-buttons {
        margin-bottom: -50px;
    }

    .hidden-column {
        display: none;
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
                        <h4 class="mb-sm-0 font-size-18">{{ $customer->first_name }} {{ $customer->last_name }}'s Details</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="/customers">Customers</a></li>
                                <li class="breadcrumb-item active">{{ $customer->first_name }} {{ $customer->last_name }}</li>
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
                                                <p class="text-muted mb-2">Account Created Date</p>
                                                <h5 class="mb-0"><span style="color: rgb(0, 0, 0);">{{ \Carbon\Carbon::parse($customer->created_at)->format('Y-m-d') }}</span></h5>
                                            </div>
                                            <div class="avatar-sm ms-auto">
                                                <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                    <i class='bx bx-calendar bx-rotate-90 bx-tada' style='color:#131313'></i>
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
                                                <p class="text-muted mb-2">Total Orders Made</p>
                                                <h5 class="mb-0"><span style="color: rgb(0, 110, 253);">{{$totalCustomerShipments}}</span></h5>
                                            </div>
                                            <div class="avatar-sm ms-auto">
                                                <div class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                    <i class='bx bxs-package bx-rotate-90 bx-tada' style='color:#356eec'></i>
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

                        <div class="image-preview" id="imagePreview">
                            @if ($customer->user_image != null)
                            <img src="{{ asset('images/' . $customer->user_image) }}" alt="Image Preview"
                            class="image-preview__image" style="width:10%">
                            @else
                            <img src="{{ asset('drivers/unknown_pic.webp') }}" alt="Image Preview"
                            class="image-preview__image">
                            @endif
                        </div>
                        <br>
                        <h4 class="card-title" style="font-weight: bold; font-size: large">{{ $customer->first_name }} {{ $customer->last_name }}</h4>
                        <div class="row">
                            <div class="col-6">
                                <p><span style="font-weight: bold;">Email: </span>{{ $customer->email }}</p>
                            </div>
                            <div class="col-6">
                                <p><span style="font-weight: bold;">Phone Number: </span>{{ $customer->phone_no }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <p><span style="font-weight: bold;">Address: </span>{{ $customer->address }}</p>
                            </div>
                            <div class="col-6">
                                <p><span style="font-weight: bold;">State: </span>{{ $customer->state }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <p><span style="font-weight: bold;">City: </span>{{ $customer->city }}</p>
                            </div>
                            <div class="col-6">
                                <p><span style="font-weight: bold;">Zip: </span>{{ $customer->postcode }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <p>
                                    <span style="font-weight: bold;">Status: </span>
                                    @if ($customer->status == 1)
                                        <span class="text-success">Active</span>
                                    @elseif ($customer->status == 0)
                                        <span class="text-danger">Disabled</span>
                                    @endif
                                    @if ($customer->status == 1)
                                    <a href="{{ route('customer.status.update',['id'=>$customer->id, 'status_code'=>0]) }}"
                                        class="btn btn-sm btn-danger" style="margin-left: 20px"><i class="fa fa-ban"></i></a>
                                    @else
                                    <a href="{{ route('customer.status.update',['id'=>$customer->id, 'status_code'=>1]) }}"
                                        class="btn btn-sm btn-success" style="margin-left: 20px"><i class="fa fa-check"></i></a>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <a href="/customers"><button class="btn btn-secondary mt-4 mr-1">Back</button></a>
                                <a href="/customers/{{ $customer->id }}/edit"><button class="btn btn-warning mt-4 mr-1"><i class="fa fa-edit"></i></button></a>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Shipment Information -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Shipment Details for Customer: {{ $customer->first_name }} {{ $customer->last_name }}</h4>
                            <div class="table-responsive">
                            <table id="datatable-buttons"
                                        class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <!-- Hidden for sorting reasons -->
                                                <th class="hidden-column">Created At</th>
                                                <th>Item Code</th>
                                                <th>Item Description</th>
                                                <th>Amount</th>
                                                <th>Delivery Status</th>
                                                <th>Driver</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($customerShipments as $shipment)
                                            <tr>
                                                <!-- Hidden for sorting reasons -->
                                                <td class="hidden-column">{{ $shipment->created_at }}</td>
                                                <td>{{ $shipment->item_code }}</td>
                                                <td>{{ $shipment->item_description }}</td>
                                                <td>{{ $shipment->amount }}</td>
                                                @if($shipment->delivery_status == '0')
                                                <td>To Be Delivered</td>
                                                @elseif($shipment->delivery_status == '1')
                                                <td>Delivering</td>
                                                @elseif($shipment->delivery_status == '2')
                                                <td>Delivered</td>
                                                @elseif($shipment->delivery_status == '3')
                                                <td>On Hold</td>
                                                @endif
                                                <td>{{ $shipment->driver_surname }}</td>
                                                <td>
                                                    <a href="{{ route('checkShipment', ['id' => $shipment->id]) }}"class="btn btn-sm btn-info"><i class="fa fa-info-circle"> View Details</i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.footer')
        </div>
        <!-- end main content-->

        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Include DataTables library -->
        <script src="https://cdn.datatables.net/1.11.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.7/js/dataTables.bootstrap4.min.js"></script>

        <!-- JavaScript to dismiss the DataTable error alert and initialize DataTable -->
        <script>
            $(document).ready(function() {
                var dataTableAlert = window.alert; // Store the original alert function

                // Override the alert function to automatically dismiss the alert
                window.alert = function(message) {
                    if (message && message.startsWith("DataTables warning:")) {
                        console.log(message); // Log the DataTable warning message
                        return; // Don't show the alert
                    } else {
                        // For other alert messages, use the original alert function
                        dataTableAlert(message);
                    }
                };

                // Initialize DataTable with default sorting order and keep buttons
                if (!$.fn.dataTable.isDataTable('#datatable-buttons')) {
                    $('#datatable-buttons').DataTable({
                        "order": [[0, "desc"]], // Sort by the first column (Created At) in descending order
                        "dom": 'Bfrtip', // Specify which buttons to show
                        "buttons": [
                            'copy',
                            'excel',
                            'pdf',
                            'colvis'
                        ]
                    });
                }
            });
        </script>
        @include('layout.scripts')
    </body>
</html>
