<!doctype html>
<html lang="en">

@include('layout.header')

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

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
                                    <h4 class="mb-sm-0 font-size-18">Vehicle's Details</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="/vehicles">Vehicles</a></li>
                                            <li class="breadcrumb-item"><a href="/vehicle_detail/{{$id}}">{{$vin_no}}</a></li>
                                            <li class="breadcrumb-item active">Dashcam</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                                <div class="card">
                                    <div class="card-body">
                                    
                                
                                    </div>
                                </div>
                    <!-- end col -->
                </div>

                <!-- End Page-content -->

            @include('layout.footer')
            </div>
            <!-- end main content-->

        @include('layout.scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <script>
            function confirmAction() {
                if (confirm("Are you sure you want to change the Engine Status?")) {
                    return true; // User confirmed, continue with the action
                } else {
                    return false; // User canceled, prevent the action
                }
            }
        </script>


    </body>

</html>