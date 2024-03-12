<!doctype html>
<html lang="en">

@include('layout.header')

<body>
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0 font-size-18">Settings Page</h4>
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
                <div class="card">
                    <div class="card-body">
                        <div class="d-sm-flex flex-wrap">
                            <h4 class="card-title mb-4">Set Alerts</h4>
                            <div class="ms-auto">
                                <ul class="nav nav-pills" id="category-pills">
                                    <!-- <li class="nav-item">
                                        <a class="nav-link active" href="#geo-content" data-category="geo">Geo-Fence</a>
                                    </li> -->
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#speed-content" data-category="speed">Speed</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#battery-content" data-category="battery">Battery</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <form action="{{ route('settings.save', ['id' => $setting->id]) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <!-- Geo-Fence Category -->
                            <!-- <div id="geo-content" class="category-content">
                                <div class="form-group row mt-4">
                                    <label for="geoDistance" class="col-md-5 col-form-label text-md-right">Geo-Fence Coverage Distance(km)</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" name="geo_distance" value="{{ $setting->geo_distance }}">
                                        @error('geoDistance')
                                        <div class="mt-2">
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        </div>
                                        @enderror
                                    </div>
                                    <div id="map" style="height: 600px;"></div>
                                </div>
                            </div> -->

                            <!-- Speed Category -->
                            <div id="speed-content" class="category-content">
                                <div class="form-group row mt-4">
                                    <label for="speedLimit" class="col-md-5 col-form-label text-md-right">Speed Limit(km/h)</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" name="speed_limit" value="{{ $setting->speed_limit }}">
                                        @error('speedLimit')
                                        <div class="mt-2">
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Battery Category -->
                            <div id="battery-content" class="category-content" style="display: none;">
                                <div class="form-group row mt-4">
                                    <label for="battery" class="col-md-5 col-form-label text-md-right">Battery Percentage(%)</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control" name="battery" value="{{ $setting->battery }}">
                                        @error('battery')
                                        <div class="mt-2">
                                            <div class="alert alert-danger">{{ $message }}</div>
                                        </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input to store the active category -->
                            <input type="hidden" name="category" value="geo">

                            <!-- Save Button -->
                            <div class="row mt-4">
                                <div class="col-md-6 offset-md-5">
                                    <button type="submit" class="btn btn-primary" id="saveButton">Save All</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('layout.footer')
    </div>

    <!-- end main content -->
    @include('layout.scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryLinks = document.querySelectorAll('#category-pills .nav-link');
            const categoryContents = document.querySelectorAll('.category-content');
            const saveButton = document.getElementById('saveButton');
            const settingsForm = document.getElementById('settingsForm');

            categoryLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();

                    // Remove 'active' class from all links
                    categoryLinks.forEach(link => {
                        link.classList.remove('active');
                    });

                    // Add 'active' class to the clicked link
                    this.classList.add('active');

                    // Hide all category contents
                    categoryContents.forEach(content => {
                        content.style.display = 'none';
                    });

                    // Show the corresponding category content
                    const category = this.getAttribute('data-category');
                    const categoryContent = document.getElementById(`${category}-content`);
                    if (categoryContent) {
                        categoryContent.style.display = 'block';
                    }
                });
            });

            saveButton.addEventListener('click', function () {
                // Set the active category as a hidden input value
                const activeCategory = document.querySelector('#category-pills .nav-link.active').getAttribute('data-category');
                document.querySelector('input[name="category"]').value = activeCategory;

                // Submit the form
                settingsForm.submit();
            });
        });
    </script>
</body>

</html>
