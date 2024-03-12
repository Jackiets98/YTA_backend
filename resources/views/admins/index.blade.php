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
<body>

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
                                    <h4 class="mb-sm-0 font-size-18">Admins</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Admins</li>
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

                                    <h4 class="card-title">Admin Management Table</h4>
                                    <p class="card-title-desc">Welcome to the Admin Management Table! From this interface, you have the ability to create, edit, update, or disable user profiles seamlessly.
                                    </p>

                                    <!-- Button trigger modal with data-show-modal attribute -->
                                    <button type="button" class="btn btn-success float-end" data-toggle="modal"
                                            data-target="#exampleModalCenter"
                                            @if ($errors->any())
                                                data-show-modal="true"
                                            @endif>
                                        Create
                                    </button>

                                    <!-- Pop Up User Input -->
                                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">New Admin
                                                        Information</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ url('/admins/storeAdmin') }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>First Name <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}">
                                                            @error('first_name')
                                                                <div class="mt-2">
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Last Name <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}">
                                                            @error('last_name')
                                                                <div class="mt-2">
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Phone Number <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="phoneNo" value="{{ old('phoneNo') }}" placeholder="Eg: 0123456789" maxlength="11">
                                                            @error('phoneNo')
                                                                <div class="mt-2">
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Email <span style="color: red;">*</span></label>
                                                            <input type="" class="form-control" name="email" value="{{ old('email') }}">
                                                            @error('email')
                                                                <div class="mt-2">
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Password <span style="color: red;">*</span></label>
                                                            <input type="password" class="form-control" name="password">
                                                            @error('password')
                                                                <div class="mt-2">
                                                                    <div class="alert alert-danger">{{ $message }}</div>
                                                                </div>
                                                            @enderror
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Confirm Password <span style="color: red;">*</span></label>
                                                            <input type="password" class="form-control" name="password_confirmation">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Status</label>
                                                            <select class="form-control form-select" name="status">
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

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Create</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End Of Pop Up User Input -->

                                    <table id="datatable-buttons-2" 
                                        class="table table-bordered dt-responsive nowrap w-100"> <!--datatable-buttons-2 is just a dummy id -->
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>Phone Number</th>
                                                <th>Status</th>
                                                <!-- <th>Created At</th>
                                                <th>Updated At</th> -->
                                                <th>Actions</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($admins as $admin)
                                            <tr>
                                                <td>{{ $admin->first_name }}</td>
                                                <td>{{ $admin->last_name }}</td>
                                                <td>{{ $admin->email }}</td>
                                                <td>{{ $admin->phone_no }}</td>
                                                <td>
                                                    @if ($admin->status == 1)
                                                        <span class="badge badge-pill badge-success">Active</span>
                                                    @elseif ($admin->status == 0)
                                                        <span class="badge badge-pill badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <!-- <td>{{ $admin->created_at }}</td>
                                                <td>{{ $admin->updated_at }}</td> -->
                                                <td>
                                                <a href="{{ url('/admins/'.$admin->id) }}"class="btn btn-sm btn-info mr-1"><i class="fa fa-address-card"> View Details</i></a>
                                                <a href="/admins/{{ $admin->id }}/edit" class="btn btn-sm btn-warning mr-1">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @if ($admin->status == 1)
                                                    <a href="{{ route('admin.status.update', ['admin' => $admin->id, 'status' => 0]) }}" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-ban"></i>
                                                    </a>
                                                @elseif ($admin->status == 0)
                                                    <a href="{{ route('admin.status.update', ['admin' => $admin->id, 'status' => 1]) }}" class="btn btn-sm btn-success">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                @endif
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
        $(document).ready(function() {
            // Check if data-show-modal attribute exists
            if ($('[data-show-modal="true"]').length) {
                $('#exampleModalCenter').modal('show');
            }
        });
    </script>

    <script>
    $('document').ready(function() {
        $('#datatable-buttons-2').DataTable();
        });
    </script>
    
    </body>

</html>