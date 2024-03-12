<!doctype html>
<html lang="en">



        @include("layout.header")

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18">Admin Management</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="/admins">Admin Listing</a></li>
                                        <li class="breadcrumb-item active">Edit Admin</li>
                                    </ol>
                                </div>
                            </div>
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
                                    <form action="/admins/{{ $admin->id }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <div class="form-group d-flex justify-content-center">
                                            <div class="col-md-6">
                                                <label>First Name <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="first_name" value="{{ $admin->first_name }}" required>
                                                @error('first_name')
                                                    <div class="mt-2">
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-center">
                                            <div class="col-md-6">
                                                <label>Last Name <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="last_name" value="{{ $admin->last_name }}" required>
                                                @error('last_name')
                                                    <div class="mt-2">
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-center">
                                            <div class="col-md-6">
                                                <label>Email <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="email" value="{{ $admin->email }}" required>
                                                @error('email')
                                                    <div class="mt-2">
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-center">
                                            <div class="col-md-6">
                                                <label>Phone Number <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="phoneNo" value="{{ $admin->phone_no }}" placeholder="Eg: 0123456789" maxlength="11" required>
                                                @error('phoneNo')
                                                    <div class="mt-2">
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-center">
                                            <div class="col-md-6">
                                                <label>New Password <span class="text-secondary">(optional)</label>
                                                <input type="password" class="form-control" name="password">
                                                @error('password')
                                                    <div class="mt-2">
                                                        <div class="alert alert-danger">{{ $message }}</div>
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-center">
                                            <div class="col-md-6">
                                                <label>Confirm New Password</label>
                                                <input type="password" class="form-control" name="password_confirmation">
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-center">
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-primary mt-2">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Start -->
            @include("layout.footer")
             <!-- Footer End -->
        </div>
    </div>
    <!-- END layout-wrapper -->

    @include('layout.scripts')
    <!-- JAVASCRIPT -->
    <script src="{{ URL::asset('libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ URL::asset('libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('libs/node-waves/waves.min.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ URL::asset('libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- crypto dash init js -->
    <script src="{{ URL::asset('js/pages/saas-dashboard.init.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ URL::asset('libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Buttons examples -->
    <script src="{{ URL::asset('libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ URL::asset('libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ URL::asset('js/pages/datatables.init.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('js/app.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>


    <script type="text/javascript">
        $("document").ready(function() {
            setTimeout(function() {
                $("div.alert").remove();
            }, 5000);
        });
    </script>
</body>

</html>
