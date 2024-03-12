<!doctype html>
<html lang="en">

@include('layout.header')

<style>
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    }

    .hidden-column {
        display: none;
    }

    p.card-title-desc {
        margin-bottom: 5px;
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

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Shipments</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Shipments</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">

                                    <h4 class="card-title">Shipment Management Table</h4>
                                    <p class="card-title-desc">Welcome to the Shipment Management Table! From this interface, you have the ability to create, edit, or update shipment details seamlessly.
                                    </p>

                                    <table id="datatable-buttons"
                                        class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <!-- Hidden for sorting reasons -->
                                                <th class="hidden-column">Created At</th>
                                                <th>Shipment Code</th>
                                                <th>Total Order(s)</th>
                                                <th>Delivery Status</th>
                                                <th>Customer</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($shipments as $shipments)
                                            <tr>
                                                <!-- Hidden for sorting reasons -->
                                                <td class="hidden-column">{{ $shipments->created_at }}</td>
                                                <td>{{ $shipments->shipment_code }}</td>
                                                <td>{{ count(explode('==', $shipments->item_code)) }}</td>
                                                @if($shipments->delivery_status == '0')
                                                <td>To Be Delivered</td>
                                                @elseif($shipments->delivery_status == '1')
                                                <td>Delivering</td>
                                                @elseif($shipments->delivery_status == '2')
                                                <td>Delivered</td>
                                                @elseif($shipments->delivery_status == '3')
                                                <td>On Hold</td>
                                                @endif
                                                <td>{{ $shipments->last_name }}</td>
                                                <td>
                                                    <a href="{{ url('/shipment_detail/'.$shipments->id) }}"class="btn btn-sm btn-info"><i class="fa fa-address-card"> View Details</i></a>
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
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

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