<!doctype html>
<html lang="en">

@include('layout.header')

<style>
    div.dataTables_wrapper div.dataTables_filter {
        margin-top: -17px;
        margin-right: 8px;
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
                                    <h4 class="mb-sm-0 font-size-18">Report</h4>

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
                        <div class="col-12">
                            @if (session()->has('success'))
                            <div class="alert alert-success" id="alert">
                                <button type="button" class="close" data-dismiss="alert">x</button>
                                {{ session()->get('success') }}
                            </div>
                            @endif
                            @if (session()->has('error'))
                                <div class="alert alert-danger" id="alert">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    {{ session()->get('error') }}
                                </div>
                            @endif
                        </div>

                        <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Let's Add A New Report!</h4>
                                    <br>
                                    <form action="{{ url('/addNewReport') }}" method="POST" enctype="multipart/form-data">
                                        @csrf

                                        <div class="form-group">
                                            <label for="reportDescription">Report Description:</label>
                                            <textarea class="form-control" id="reportDescription" name="reportDescription" rows="4" placeholder="Enter report description">{{ old('reportDescription') }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="media">Select Media (Multiple) <span class="text-danger">(Format: jpeg, jpg, png, mp4)</span>:</label>
                                            <input type="file" class="form-control" id="media" name="media[]" multiple>
                                        </div>

                                        <div id="imagePreview" class="mt-3 d-flex flex-wrap"></div>

                                        <button type="submit" class="btn btn-outline-success" style="float:right">Create Report</button>
                                    </form>

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
        document.getElementById('media').addEventListener('change', function (e) {
            displayImagePreview(e.target.files);
        });
    
        function displayImagePreview(files) {
            var previewContainer = document.getElementById('imagePreview');
            previewContainer.innerHTML = '';
    
            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();
    
                reader.onload = function (e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail', 'mr-2', 'mb-2');
                    img.style.width = '100px';
                    img.style.height = '100px';
                    previewContainer.appendChild(img);
                };
    
                reader.readAsDataURL(file);
            }
        }
    </script>
    In this modific

    </body>

</html>