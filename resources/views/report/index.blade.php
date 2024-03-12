<!doctype html>
<html lang="en">

@include('layout.header')

<style>
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
                                    <h4 class="mb-sm-0 font-size-18">Reports</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Report</li>
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

                                    <h4 class="card-title">Report Management Table</h4>
                                    <p class="card-title-desc">Welcome to the Report Management Table! From this interface, you have the ability to create, edit, update, or disable report details seamlessly.
                                    </p>

                                    <table id="datatable-buttons"
                                        class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Description</th>
                                                <th>Media</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($reports as $report)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>{{ $report->text }}</td>
                                                <td>
                                                    @if($report->media)
                                                        @foreach(json_decode($report->media) as $mediaPath)
                                                            <img src="{{ asset($mediaPath) }}" alt="Media" style="width: 60px; height: 60px; margin-right: 5px;">
                                                        @endforeach
                                                    @else
                                                        No Media
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('/vehicle_detail/'.$report->id) }}"class="btn btn-sm btn-info"><i class="fa fa-address-card"> View Details</i></a>
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
    </body>

</html>