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
                                    <h4 class="mb-sm-0 font-size-18">Drivers</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Drivers</li>
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

                                    <h4 class="card-title">Driver Management Table</h4>
                                    <p class="card-title-desc">Welcome to the Driver Management Table! From this interface, you have the ability to create, edit, update, or disable user profiles seamlessly.
                                    </p>

                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-success float-end" data-toggle="modal"
                                        data-target="#exampleModalCenter">
                                        Create
                                    </button>

                                    <!-- Pop Up User Input -->
                                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">New Driver
                                                        Information</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="addDriversForm" action="{{ url('/addDrivers') }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Name <span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="name" required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Surname <span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="surname" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="address">IC No. <span style="color: red;">*</span></label>
                                                                <input type="number" class="form-control" name="ic_no" required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="address">License No <span style="color: red;">*</span>.</label>
                                                                <input type="number" class="form-control" name="license" required>
                                                            </div>
                                                        </div>  
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="address">Email <span style="color: red;">*</span></label>
                                                                <input type="email" class="form-control" name="email" required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                            <label for="address">Phone No. <span style="color: red;">*</span></label>
                                                            <input type="number" class="form-control" name="phone" placeholder="Eg: 0123456789" maxlength="11" required>
                                                        </div>
                                                        </div>                                                                                         
                                                        <div class="form-group">
                                                            <label for="address">Address <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="address" required>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-4">
                                                                <label>City <span style="color: red;">*</span></label>
                                                                <input type="text" class="form-control" name="city" required>
                                                            </div>
                                                            <div class="form-group col-md-5">
                                                                <label>State <span style="color: red;">*</span></label>
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
                                                            <div class="form-group col-md-3">
                                                                <label>Zip <span style="color: red;">*</span></label>
                                                                <input type="number" class="form-control" name="postcode" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Password <span style="color: red;">*</span></label>
                                                                <input type="password" class="form-control" name="password">
                                                                @error('password')
                                                                    <div class="mt-2">
                                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                                    </div>
                                                                @enderror
                                                            </div>

                                                            <div class="form-group col-md-6">
                                                                <label>Confirm Password <span style="color: red;">*</span></label>
                                                                <input type="password" class="form-control" name="password_confirmation">
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
                                                <th>Name</th>
                                                <th>Surname</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($drivers as $drivers)
                                            <tr>
                                                <td>{{ $drivers->name }}</td>
                                                <td>{{ $drivers->surname }}</td>
                                                <td>{{ $drivers->email }}</td>
                                                <td>{{ $drivers->phone_num }}</td>
                                                <td>
                                                    @if ($drivers->status == 1)
                                                        <span class="badge badge-pill badge-success">Active</span>
                                                    @elseif ($drivers->status == 0)
                                                        <span class="badge badge-pill badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('/driver_detail/'.$drivers->id) }}"class="btn btn-sm btn-info"><i class="fa fa-address-card"> View Details</i></a>
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

    @include('layout.scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var form = document.getElementById("addDriversForm");
            var password = form.elements["password"];
            var confirmPassword = form.elements["password_confirmation"];

            function validatePassword() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity("Passwords don't match");
                } else {
                    confirmPassword.setCustomValidity('');
                }
            }

            password.addEventListener("input", validatePassword);
            confirmPassword.addEventListener("input", validatePassword);

            form.addEventListener("submit", function (event) {
                if (password.value !== confirmPassword.value) {
                    event.preventDefault(); // Prevent form submission
                    // Optionally, you can display an alert or error message here.
                }
            });
        });
    </script>
    </body>

</html>