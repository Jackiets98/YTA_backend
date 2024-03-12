<!doctype html>
<html lang="en">

@include('layout.header')

<style>

    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none; 
    margin: 0; 
    }

    /* DataTable buttons */
    div.dataTables_wrapper div.dt-buttons {
        margin-left: -15px;
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
                                    <h4 class="mb-sm-0 font-size-18">Customers</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Customers</li>
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

                                    <h4 class="card-title">Customer Management Table</h4>
                                    <p class="card-title-desc">Welcome to the Customer Management Table! From this interface, you have the ability to create, edit, update, or disable user profiles seamlessly.
                                    </p>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-success float-end" data-toggle="modal"
                                            data-target="#exampleModalCenter"
                                            @if ($errors->any())
                                                data-show-modal="true"
                                            @endif
                                    >
                                        Create
                                    </button>

                                    <!-- Pop Up User Input -->
                                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">New Customer
                                                        Information</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ url('/customers') }}"
                                                    method="POST" id="customerForm">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                            <label>First Name <span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required>
                                                                @error('first_name')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                            <label>Last Name <span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required>
                                                                @error('last_name')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Phone Number <span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="phoneNo" value="{{ old('phoneNo') }}" placeholder="Eg: 0123456789" maxlength="11" required>
                                                                @error('phoneNo')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Email <span style="color: red;">*</span></label>
                                                                <input type="" class="form-control" name="email" value="{{ old('email') }}" required>
                                                                @error('email')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Password <span style="color: red;">*</span></label>
                                                                <input type="password" class="form-control" name="password" required>
                                                                @error('password')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Confirm Password <span style="color: red;">*</span></label>
                                                                <input type="password" class="form-control" name="password_confirmation" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">                                                                                         
                                                            <div class="form-group col-md-12">
                                                                <label>Address <span style="color: red;">*</span></label>
                                                                <input type="address" class="form-control" name="address" required>
                                                                @error('address')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-5">
                                                                <label>State  <span style="color: red;">*</span></label>
                                                                    <select name="state" class="form-control" required>
                                                                        <option selected>-Please Select One-</option>
                                                                        <option>Johor</option>
                                                                        <option>Kedah</option>
                                                                        <option>Kelantan</option>
                                                                        <option>Melaka</option>
                                                                        <option>Negeri Sembilan</option>
                                                                        <option>Pahang</option>
                                                                        <option>Perak</option>
                                                                        <option>Perlis</option>
                                                                        <option>Pulau Pinang</option>
                                                                        <option>Sabah</option>
                                                                        <option>Sarawak</option>
                                                                        <option>Selangor</option>
                                                                        <option>Terengganu</option>
                                                                        <option>Wilayah Persekutuan Kuala Lumpur</option>
                                                                        <option>Wilayah Persekutuan Labuan</option>
                                                                        <option>Wilayah Persekutuan Putrajaya</option>
                                                                    </select>
                                                                </div>
                                                            <div class="form-group col-md-4">
                                                                <label>City <span style="color: red;">*</span></label>
                                                                <input type="city" class="form-control" name="city" required>
                                                                @error('city')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label>Zip <span style="color: red;">*</span></label>
                                                                <input type="postcode" class="form-control" name="postcode" required>
                                                                @error('postcode')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                                <label>Status <span style="color: red;">*</span></label>
                                                                <select class="form-control form-select" name="status" required>
                                                                    <option value="1" selected>Active</option>
                                                                    <option value="0">Inactive</option>
                                                                </select>
                                                                @error('status')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Insert</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Of Pop Up User Input -->

                                    <table id="datatable-buttons"
                                        class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>App Activity</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="customer-table-body">
                                        <!-- Initially empty, will be populated dynamically -->
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

    @include('layout.scripts')
    <script>
        $(document).ready(function() {
            // Check if data-show-modal attribute exists
            if ($('[data-show-modal="true"]').length) {
                $('#exampleModalCenter').modal('show');
            }
        });
    </script>

<script>
    // Function to fetch data from the backend API and update status badges
    function fetchDataAndUpdate() {
        $.get('/customerList')
            .done(function(data) {
                var customerTableBody = $('#customer-table-body');
                customerTableBody.empty(); // Clear existing rows
                
                data.forEach(function(customer) {
                    var badgeClass = customer.app_online_status === '1' ? 'success' : 'danger';
                    var statusText = customer.app_online_status === '1' ? 'ONLINE' : 'OFFLINE';
                    var statusBadge = '<span class="badge badge-pill badge-' + badgeClass + '">' + statusText + '</span>';

                    var statusCell = '<td>' + statusBadge + '</td>';
                    var statusLabel = customer.status == '1' ? '<span class="badge badge-pill badge-success">Active</span>' : '<span class="badge badge-pill badge-danger">Inactive</span>';
                    var detailsLink = '<a href="{{ url('/customers/') }}/' + customer.id + '" class="btn btn-sm btn-info"><i class="fa fa-address-card"> View Details</i></a>';
                    var row = '<tr><td>' + customer.first_name + '</td><td>' + customer.last_name + '</td><td>' + customer.email + '</td><td>' + customer.phone_no + '</td>' + statusCell + '<td>' + statusLabel + '</td><td>' + detailsLink + '</td></tr>';
                    
                    customerTableBody.append(row);
                });
            })
            .fail(function() {
                console.error('Failed to fetch data from the API.');
            });
    }

    // Call the fetchDataAndUpdate function initially and every 10 seconds
    $(document).ready(function() {
        fetchDataAndUpdate(); // Fetch data initially
        setInterval(fetchDataAndUpdate, 5000); // Fetch data every 10 seconds
    });
</script>
    </body>

</html>