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
                                        <li class="breadcrumb-item active">Admin Details</li>
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

                                    <div class="row">
                                        <div class="col-12">
                                            <p><span style="font-weight: bold;">Name: </span>{{ $admin->first_name . ' ' . $admin->last_name }}</p>
                                        </div>

                                        <div class="col-6">
                                            <p><span style="font-weight: bold;">Email: </span>{{ $admin->email }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <p><span style="font-weight: bold;">Phone Number: </span>{{ $admin->phone_no }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p>
                                                <span style="font-weight: bold;">Status: </span>
                                                @if ($admin->status == 1)
                                                    <span class="text-success">Active</span>
                                                @elseif ($admin->status == 0)
                                                    <span class="text-danger">Inactive</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <a href="/admins"><button class="btn btn-secondary mt-4 mr-1">Back</button></a>
                                            <a href="/admins/{{ $admin->id }}/edit"><button class="btn btn-warning mt-4"><i class="fa fa-edit"></i></button></a>
                                        </div>
                                    </div>
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
