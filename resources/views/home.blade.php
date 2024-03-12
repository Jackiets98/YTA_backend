<!doctype html>
<html lang="en">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@include('layout.header')
<style>
        /* Basic styling for the bar chart container */
        .bar-chart {
            display: table;
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Add some margin to separate from the top */
        }

        /* Styling for each row (months) */
        .bar-row {
            display: table-row;
        }

        /* Styling for each cell (bar and label) */
        .bar-cell {
            display: table-cell;
            text-align: center;
            vertical-align: bottom;
            padding: 5px;
            position: relative;
        }

        .bar {
            width: 20px;
            background-color: #3498db;
            transition: height 0.5s;
            margin: auto;
        }

        .label {
            margin-top: 5px;
            text-align: center;
        }

        /* Styling for the y-axis labels */
        .y-axis-cell {
            display: table-cell;
            text-align: right;
            vertical-align: top;
            padding-right: 10px;
        }

        .y-axis-label {
            margin-bottom: 25px;
            font-size: 12px;
        }

        #post-customer .dropdown-menu.show {
            top:0!important
        }

        #post-customer .dropdown-menu-end[style] {
            left:auto!important;
            right:100%!important
        }

        #post-driver .dropdown-menu.show {
            top:0!important
        }

        #post-driver .dropdown-menu-end[style] {
            left:auto!important;
            right:100%!important
        }

        .table {
            margin-bottom: 0rem;
        }

        .hidden-column {
            display: none;
        }

        h4.card-title {
            margin-bottom: 0px;
        }
    
    /* DataTable buttons */
    div.dataTables_wrapper div.dt-buttons {
        margin-bottom: -50px;
    }
    </style>
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                    @if (session()->has('info'))
                                <div class="alert alert-info" id="alert">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    {{ session()->get('info') }}{{$userName}}
                                </div>
                            @endif

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        <div class="col-12">
                            @if (session()->has('success'))
                                <div class="alert alert-success" id="alert">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-xl-4 col-lg-6">
                                <div class="card">
                                    <div class="card-header bg-transparent border-bottom">
                                        <div class="d-flex flex-wrap align-items-start">
                                            <div class="me-2">
                                                <h5 class="card-title mt-1 mb-0">Relevant Users</h5>
                                            </div>
                                            <ul class="nav nav-tabs nav-tabs-custom card-header-tabs ms-auto" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="dashboard-blog.html#post-customer" role="tab">
                                                        Customer
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="dashboard-blog.html#post-driver" role="tab">
                                                        Driver
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div data-simplebar style="max-height: 295px;">
                                            <!-- Tab panes -->
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="post-customer" role="tabpanel">
                                                    <ul class="list-group list-group-flush">
                                                    @foreach ($customers as $customer)
                                                        <li class="list-group-item py-3">
                                                            <div class="d-flex">
                                                                <div class="me-3">
                                                                    @if ($customer->user_image != null)
                                                                        <img src="{{ asset('images/' . $customer->user_image) }}" alt="" class="avatar-md h-auto d-block rounded">
                                                                    @else
                                                                        <img src="{{ asset('drivers/unknown_pic.webp') }}" alt="" class="avatar-md h-auto d-block rounded">
                                                                    @endif
                                                                </div>

                                                                <div class="align-self-center overflow-hidden me-auto">
                                                                    <div>
                                                                        <h5 class="font-size-14 text-truncate">
                                                                            <a href="javascript: void(0);" class="text-dark">
                                                                                {{ $customer->first_name }} {{ $customer->last_name }}
                                                                            </a>
                                                                        </h5>
                                                                        <p class="text-muted mb-0">{{ $customer->total_shipments }} orders</p>
                                                                    </div>
                                                                </div>

                                                                <div class="dropdown ms-2">
                                                                    <a class="text-muted font-size-14" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                                    </a>

                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a class="dropdown-item" href="{{ asset('customers/' . $customer->id) }}">View Profile</a>
                                                                        <!-- <a class="dropdown-item" href="#">Another action</a>
                                                                        <a class="dropdown-item" href="#">Something else here</a> -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                    </ul>
                                                    <div class="text-center">
                                                        <a href="{{ asset('customers/') }}" class="link-text">
                                                            View More Customers <i class="mdi mdi-arrow-right ms-1"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- end tab pane -->

                                                <div class="tab-pane" id="post-driver" role="tabpanel">
                                                    
                                                    <ul class="list-group list-group-flush">

                                                    @foreach ($drivers as $driver)
                                                        <li class="list-group-item py-3">
                                                            <div class="d-flex">
                                                                <div class="me-3">
                                                                    @if ($driver->user_image != null)
                                                                        <img src="{{ asset('images/' . $driver->user_image) }}" alt="" class="avatar-md h-auto d-block rounded">
                                                                    @else
                                                                        <img src="{{ asset('drivers/unknown_pic.webp') }}" alt="" class="avatar-md h-auto d-block rounded">
                                                                    @endif
                                                                </div>

                                                                <div class="align-self-center overflow-hidden me-auto">
                                                                    <div>
                                                                        <h5 class="font-size-14 text-truncate">
                                                                            <a href="javascript: void(0);" class="text-dark">
                                                                                {{ $driver->name }} {{ $driver->surname }}
                                                                            </a>
                                                                        </h5>
                                                                        <p class="text-muted mb-0">{{ $driver->total_shipments }} shipments completed</p>
                                                                    </div>
                                                                </div>

                                                                <div class="dropdown ms-2">
                                                                    <a class="text-muted font-size-14" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                                    </a>

                                                                    <div class="dropdown-menu dropdown-menu-end">
                                                                        <a class="dropdown-item" href="{{ asset('driver_detail/' . $driver->id) }}">View Profile</a>
                                                                        <!-- <a class="dropdown-item" href="#">Another action</a>
                                                                        <a class="dropdown-item" href="#">Something else here</a> -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                    </ul>
                                                    <div class="text-center">
                                                        <a href="{{ asset('driverlists/') }}" class="link-text">
                                                            View More Drivers <i class="mdi mdi-arrow-right ms-1"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <!-- end tab pane -->
                                            </div>
                                            <!-- end tab content -->
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Vehicle Status</h4>
                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle table-nowrap">
                                                <tbody>
                                                    @foreach ($vehicles as $vehicle)
                                                        <tr>
                                                            <td style="width: 30%">
                                                                <h5 class="mb-0 fs-6 fs-md-5">{{ $vehicle->plate_no }}</h5>
                                                            </td>
                                                            <td style="width: 25%">
                                                                @if ($vehicle->last_param == 1)
                                                                    <p class="mb-0 text-success fs-6 fs-md-5">Turned On</p>
                                                                @elseif ($vehicle->last_param == 2)
                                                                    <p class="mb-0 text-danger fs-6 fs-md-5">Cut Off</p>
                                                                @else
                                                                    <p class="mb-0 fs-6 fs-md-5">Unknown Status</p>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="text-center mt-4">
                                                                    <a href="{{ asset('vehicle_detail/' . $vehicle->id) }}" style="margin-top: -50px;" href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm fs-6 fs-md-5">
                                                                        View More <i class="mdi mdi-arrow-right ms-1"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="text-center">
                                            <a href="{{ asset('vehicles/') }}" class="link-text">
                                                View More Vehicles <i class="mdi mdi-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">Pending Orders</p>
                                                        <h4 class="mb-0">{{ $totalPendingShipments }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center">
                                                        <!-- <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                                            <span class="avatar-title"> -->
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-time font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">On Going Orders</p>
                                                        <h4 class="mb-0">{{ $totalDeliveringShipments }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center ">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-archive-in font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card mini-stats-wid">
                                            <div class="card-body">
                                                <div class="d-flex">
                                                    <div class="flex-grow-1">
                                                        <p class="text-muted fw-medium">Completed Orders</p>
                                                        <h4 class="mb-0">{{ $totalDeliveredShipments }}</h4>
                                                    </div>

                                                    <div class="flex-shrink-0 align-self-center">
                                                        <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                <i class="bx bx-user-check font-size-24"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->

                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-sm-flex flex-wrap">
                                            <h4 class="card-title mb-4">Total Orders by Year</h4>
                                            <div class="ms-auto">
                                                <!-- Dropdown for selecting the year -->
                                                <label for="selectYear">Select Year:</label>
                                                <select id="selectYear" onchange="updateChart()">
                                                    @foreach ($uniqueYears as $year)
                                                        <option value="{{ $year }}" {{ $year == 'Select Year' ? 'disabled' : '' }}>
                                                            {{ $year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button class="btn btn-dark" onclick="printChart()">Print Chart</button>
                                    <!-- Bar chart canvas -->
                                    <canvas id="myChart" width="400" height="200"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->

                        <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title">Notification Table</h4>

                                    <table id="datatable-buttons"
                                        class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <!-- Hidden for sorting reasons -->
                                                <th class="hidden-column">Created At</th>
                                                <th>Alert Type</th>
                                                <th>Message</th>
                                                <th>Time Received</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($notifications as $notification)
                                            <tr>
                                                <!-- Hidden for sorting reasons -->
                                                <td class="hidden-column">{{ $notification->created_at }}</td>
                                                <td>
                                                    @if($notification->eventId == 1)
                                                        Delivery Status Alert
                                                    @elseif($notification->eventId == 2)
                                                        Geofence Alert
                                                    @elseif($notification->eventId == 3)
                                                        Speed Alert
                                                    @else
                                                        Unknown Alert
                                                    @endif
                                                </td>
                                                <td>{{ $notification->message }}</td>
                                                <td>{{ $notification->created_at }}</td>
                                                <td>
                                                    <a href="{{ $notification->redirectPath }}"class="btn btn-sm btn-info mr-1"><i class="fa fa-info"> View Details</i></a>
                                                    <!-- <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteConfirmationModal" onclick="setDeleteFormAction('{{ url('/delete-noti/'.$notification->id) }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </button> -->
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end col -->
                    </div> 
                    </div>
                    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this notification?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <form id="deleteNotificationForm" method="POST" action="{{ url('/delete-noti/') }}">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                        <!-- <div class="row">
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Social Source</h4>
                                        <div class="text-center">
                                            <div class="avatar-sm mx-auto mb-4">
                                                <span class="avatar-title rounded-circle bg-primary bg-soft font-size-24">
                                                        <i class="mdi mdi-facebook text-primary"></i>
                                                    </span>
                                            </div>
                                            <p class="font-16 text-muted mb-2"></p>
                                            <h5><a href="javascript: void(0);" class="text-dark">Facebook - <span class="text-muted font-16">125 sales</span> </a></h5>
                                            <p class="text-muted">Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus tincidunt.</p>
                                            <a href="javascript: void(0);" class="text-primary font-16">Learn more <i class="mdi mdi-chevron-right"></i></a>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-4">
                                                <div class="social-source text-center mt-3">
                                                    <div class="avatar-xs mx-auto mb-3">
                                                        <span class="avatar-title rounded-circle bg-primary font-size-16">
                                                                <i class="mdi mdi-facebook text-white"></i>
                                                            </span>
                                                    </div>
                                                    <h5 class="font-size-15">Facebook</h5>
                                                    <p class="text-muted mb-0">125 sales</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="social-source text-center mt-3">
                                                    <div class="avatar-xs mx-auto mb-3">
                                                        <span class="avatar-title rounded-circle bg-info font-size-16">
                                                                <i class="mdi mdi-twitter text-white"></i>
                                                            </span>
                                                    </div>
                                                    <h5 class="font-size-15">Twitter</h5>
                                                    <p class="text-muted mb-0">112 sales</p>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="social-source text-center mt-3">
                                                    <div class="avatar-xs mx-auto mb-3">
                                                        <span class="avatar-title rounded-circle bg-pink font-size-16">
                                                                <i class="mdi mdi-instagram text-white"></i>
                                                            </span>
                                                    </div>
                                                    <h5 class="font-size-15">Instagram</h5>
                                                    <p class="text-muted mb-0">104 sales</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-5">Activity</h4>
                                        <ul class="verti-timeline list-unstyled">
                                            <li class="event-list">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bx-right-arrow-circle font-size-18"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">22 Nov <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            Responded to need “Volunteer Activities
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="event-list">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bx-right-arrow-circle font-size-18"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">17 Nov <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            Everyone realizes why a new common language would be desirable... <a href="javascript: void(0);">Read more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="event-list active">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bxs-right-arrow-circle font-size-18 bx-fade-right"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">15 Nov <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            Joined the group “Boardsmanship Forum”
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="event-list">
                                                <div class="event-timeline-dot">
                                                    <i class="bx bx-right-arrow-circle font-size-18"></i>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <h5 class="font-size-14">12 Nov <i class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i></h5>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div>
                                                            Responded to need “In-Kind Opportunity”
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                        <div class="text-center mt-4"><a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View More <i class="mdi mdi-arrow-right ms-1"></i></a></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Top Cities Selling Product</h4>

                                        <div class="text-center">
                                            <div class="mb-4">
                                                <i class="bx bx-map-pin text-primary display-4"></i>
                                            </div>
                                            <h3>1,456</h3>
                                            <p>San Francisco</p>
                                        </div>

                                        <div class="table-responsive mt-4">
                                            <table class="table align-middle table-nowrap">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 30%">
                                                            <p class="mb-0">San Francisco</p>
                                                        </td>
                                                        <td style="width: 25%">
                                                            <h5 class="mb-0">1,456</h5></td>
                                                        <td>
                                                            <div class="progress bg-transparent progress-sm">
                                                                <div class="progress-bar bg-primary rounded" role="progressbar" style="width: 94%" aria-valuenow="94" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <p class="mb-0">Los Angeles</p>
                                                        </td>
                                                        <td>
                                                            <h5 class="mb-0">1,123</h5>
                                                        </td>
                                                        <td>
                                                            <div class="progress bg-transparent progress-sm">
                                                                <div class="progress-bar bg-success rounded" role="progressbar" style="width: 82%" aria-valuenow="82" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <p class="mb-0">San Diego</p>
                                                        </td>
                                                        <td>
                                                            <h5 class="mb-0">1,026</h5>
                                                        </td>
                                                        <td>
                                                            <div class="progress bg-transparent progress-sm">
                                                                <div class="progress-bar bg-warning rounded" role="progressbar" style="width: 70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- end row -->

                        <!-- <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Latest Transaction</h4>
                                        <div class="table-responsive">
                                            <table class="table align-middle table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 20px;">
                                                            <div class="form-check font-size-16 align-middle">
                                                                <input class="form-check-input" type="checkbox" id="transactionCheck01">
                                                                <label class="form-check-label" for="transactionCheck01"></label>
                                                            </div>
                                                        </th>
                                                        <th class="align-middle">Order ID</th>
                                                        <th class="align-middle">Billing Name</th>
                                                        <th class="align-middle">Date</th>
                                                        <th class="align-middle">Total</th>
                                                        <th class="align-middle">Payment Status</th>
                                                        <th class="align-middle">Payment Method</th>
                                                        <th class="align-middle">View Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check font-size-16">
                                                                <input class="form-check-input" type="checkbox" id="transactionCheck02">
                                                                <label class="form-check-label" for="transactionCheck02"></label>
                                                            </div>
                                                        </td>
                                                        <td><a href="javascript: void(0);" class="text-body fw-bold">#SK2540</a> </td>
                                                        <td>Neal Matthews</td>
                                                        <td>
                                                            07 Oct, 2019
                                                        </td>
                                                        <td>
                                                            $400
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-pill badge-soft-success font-size-11">Paid</span>
                                                        </td>
                                                        <td>
                                                            <i class="fab fa-cc-mastercard me-1"></i> Mastercard
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".transaction-detailModal">
                                                                View Details
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div class="form-check font-size-16">
                                                                <input class="form-check-input" type="checkbox" id="transactionCheck03">
                                                                <label class="form-check-label" for="transactionCheck03"></label>
                                                            </div>
                                                        </td>
                                                        <td><a href="javascript: void(0);" class="text-body fw-bold">#SK2541</a> </td>
                                                        <td>Jamal Burnett</td>
                                                        <td>
                                                            07 Oct, 2019
                                                        </td>
                                                        <td>
                                                            $380
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-pill badge-soft-danger font-size-11">Chargeback</span>
                                                        </td>
                                                        <td>
                                                            <i class="fab fa-cc-visa me-1"></i> Visa
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".transaction-detailModal">
                                                                View Details
                                                            </button>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>
                                                            <div class="form-check font-size-16">
                                                                <input class="form-check-input" type="checkbox" id="transactionCheck04">
                                                                <label class="form-check-label" for="transactionCheck04"></label>
                                                            </div>
                                                        </td>
                                                        <td><a href="javascript: void(0);" class="text-body fw-bold">#SK2542</a> </td>
                                                        <td>Juan Mitchell</td>
                                                        <td>
                                                            06 Oct, 2019
                                                        </td>
                                                        <td>
                                                            $384
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-pill badge-soft-success font-size-11">Paid</span>
                                                        </td>
                                                        <td>
                                                            <i class="fab fa-cc-paypal me-1"></i> Paypal
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".transaction-detailModal">
                                                                View Details
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check font-size-16">
                                                                <input class="form-check-input" type="checkbox" id="transactionCheck05">
                                                                <label class="form-check-label" for="transactionCheck05"></label>
                                                            </div>
                                                        </td>
                                                        <td><a href="javascript: void(0);" class="text-body fw-bold">#SK2543</a> </td>
                                                        <td>Barry Dick</td>
                                                        <td>
                                                            05 Oct, 2019
                                                        </td>
                                                        <td>
                                                            $412
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-pill badge-soft-success font-size-11">Paid</span>
                                                        </td>
                                                        <td>
                                                            <i class="fab fa-cc-mastercard me-1"></i> Mastercard
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".transaction-detailModal">
                                                                View Details
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check font-size-16">
                                                                <input class="form-check-input" type="checkbox" id="transactionCheck06">
                                                                <label class="form-check-label" for="transactionCheck06"></label>
                                                            </div>
                                                        </td>
                                                        <td><a href="javascript: void(0);" class="text-body fw-bold">#SK2544</a> </td>
                                                        <td>Ronald Taylor</td>
                                                        <td>
                                                            04 Oct, 2019
                                                        </td>
                                                        <td>
                                                            $404
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-pill badge-soft-warning font-size-11">Refund</span>
                                                        </td>
                                                        <td>
                                                            <i class="fab fa-cc-visa me-1"></i> Visa
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".transaction-detailModal">
                                                                View Details
                                                            </button>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="form-check font-size-16">
                                                                <input class="form-check-input" type="checkbox" id="transactionCheck07">
                                                                <label class="form-check-label" for="transactionCheck07"></label>
                                                            </div>
                                                        </td>
                                                        <td><a href="javascript: void(0);" class="text-body fw-bold">#SK2545</a> </td>
                                                        <td>Jacob Hunter</td>
                                                        <td>
                                                            04 Oct, 2019
                                                        </td>
                                                        <td>
                                                            $392
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-pill badge-soft-success font-size-11">Paid</span>
                                                        </td>
                                                        <td>
                                                            <i class="fab fa-cc-paypal me-1"></i> Paypal
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-primary btn-sm btn-rounded waves-effect waves-light" data-bs-toggle="modal" data-bs-target=".transaction-detailModal">
                                                                View Details
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <!-- end row -->
                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <!-- Transaction Modal -->
                <div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" aria-labelledby="transaction-detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="transaction-detailModalLabel">Order Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-2">Product id: <span class="text-primary">#SK2540</span></p>
                                <p class="mb-4">Billing Name: <span class="text-primary">Neal Matthews</span></p>

                                <div class="table-responsive">
                                    <table class="table align-middle table-nowrap">
                                        <thead>
                                            <tr>
                                                <th scope="col">Product</th>
                                                <th scope="col">Product Name</th>
                                                <th scope="col">Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <th scope="row">
                                                    <div>
                                                        <img src="assets/images/product/img-7.png" alt="" class="avatar-sm">
                                                    </div>
                                                </th>
                                                <td>
                                                    <div>
                                                        <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                                                        <p class="text-muted mb-0">$ 225 x 1</p>
                                                    </div>
                                                </td>
                                                <td>$ 255</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    <div>
                                                        <img src="assets/images/product/img-4.png" alt="" class="avatar-sm">
                                                    </div>
                                                </th>
                                                <td>
                                                    <div>
                                                        <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                                                        <p class="text-muted mb-0">$ 145 x 1</p>
                                                    </div>
                                                </td>
                                                <td>$ 145</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <h6 class="m-0 text-right">Sub Total:</h6>
                                                </td>
                                                <td>
                                                    $ 400
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <h6 class="m-0 text-right">Shipping:</h6>
                                                </td>
                                                <td>
                                                    Free
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <h6 class="m-0 text-right">Total:</h6>
                                                </td>
                                                <td>
                                                    $ 400
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end modal -->

                <!-- subscribeModal -->
                <!-- <div class="modal fade" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header border-bottom-0">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center mb-4">
                                    <div class="avatar-md mx-auto mb-4">
                                        <div class="avatar-title bg-light rounded-circle text-primary h1">
                                            <i class="mdi mdi-email-open"></i>
                                        </div>
                                    </div>

                                    <div class="row justify-content-center">
                                        <div class="col-xl-10">
                                            <h4 class="text-primary">Subscribe !</h4>
                                            <p class="text-muted font-size-14 mb-4">Subscribe our newletter and get notification to stay update.</p>

                                            <div class="input-group bg-light rounded">
                                                <input type="email" class="form-control bg-transparent border-0" placeholder="Enter Email address" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                
                                                <button class="btn btn-primary" type="button" id="button-addon2">
                                                    <i class="bx bxs-paper-plane"></i>
                                                </button>
                                                
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
                <!-- end modal -->
                @include('layout.footer')
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center px-3 py-4">
            
                    <h5 class="m-0 me-2">Settings</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
                <h6 class="text-center mb-0">Choose Layouts</h6>

                <div class="p-4">
                    <div class="mb-2">
                        <img src="assets/images/layouts/layout-1.jpg" class="img-thumbnail" alt="layout images">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked>
                        <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                    </div>
    
                    <div class="mb-2">
                        <img src="assets/images/layouts/layout-2.jpg" class="img-thumbnail" alt="layout images">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch">
                        <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                    </div>
    
                    <div class="mb-2">
                        <img src="assets/images/layouts/layout-3.jpg" class="img-thumbnail" alt="layout images">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="rtl-mode-switch">
                        <label class="form-check-label" for="rtl-mode-switch">RTL Mode</label>
                    </div>

                    <div class="mb-2">
                        <img src="assets/images/layouts/layout-4.jpg" class="img-thumbnail" alt="layout images">
                    </div>
                    <div class="form-check form-switch mb-5">
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-rtl-mode-switch">
                        <label class="form-check-label" for="dark-rtl-mode-switch">Dark RTL Mode</label>
                    </div>

            
                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script>
            // Get the canvas element
            var ctx = document.getElementById('myChart').getContext('2d');

            // Initial data for the chart
            var initialData = {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Orders',
                    backgroundColor: 'rgba(49, 69, 148, 0.5)',
                    borderColor: 'rgba(49, 69, 148, 1)',
                    borderWidth: 1,
                    data: {!! json_encode($data) !!}
                }]
            };

            // Define the chart configuration
            var options = {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            };

            // Create the initial bar chart
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: initialData,
                options: options
            });

            // Function to update the chart based on the selected year
            function updateChart() {
                var selectedYear = document.getElementById('selectYear').value;

                // Make an AJAX request to fetch data for the selected year
                fetch('/getChartData?year=' + selectedYear)
                    .then(response => response.json())
                    .then(data => {
                        myChart.data.labels = data.labels;
                        myChart.data.datasets[0].data = data.data;
                        myChart.update();
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function printChart() {
        // Convert the chart to a base64-encoded image
        var imageData = myChart.toBase64Image();

        // Create a new window to display the image
        var printWindow = window.open('');
        printWindow.document.write('<img src="' + imageData + '" />');

        // Call the print function after the image is loaded
        printWindow.document.getElementsByTagName('img')[0].onload = function () {
            printWindow.print();
        };
    }
        </script>
        <script>
    function setDeleteFormAction(action) {
        document.getElementById('deleteNotificationForm').action = action;
    }
</script>
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
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>

        <!-- apexcharts -->
        <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

        <!-- dashboard init -->
        <script src="assets/js/pages/dashboard.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>
        @include('layout.scripts')
    </body>

</html>