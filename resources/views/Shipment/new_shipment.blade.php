<!DOCTYPE html>
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
                        <h4 class="mb-sm-0 font-size-18">Shipment</h4>
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
                            <h4 class="card-title">Let's Create A New Shipment!</h4>
                            <form action="{{url('/addNewShipment')}}" method="POST">
                                @csrf

                                <!-- Display validation errors here -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Remaining input fields in another card -->
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Shipment Details</h4>
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label for="address">Shipment Code <span style="color: red;">*</span></label>
                                                <input type="text" class="form-control" name="shipment_code" value="{{ old('shipment_code') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="address">Assigned Driver <span style="color: red;">*</span></label>
                                                <select name="driver" class="form-control" required>
                                                    <option selected disabled>-Please Select Driver-</option>
                                                    @foreach ($drivers as $driver)
                                                        @if ($driver->status == 1)
                                                            <option value="{{$driver->id}}" {{ old('driver') == $driver->id ? 'selected' : '' }}>{{$driver->name}}</option>
                                                        @endif 
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="deliver_to">Deliver To <span style="color: red;">*</span></label>
                                                <select name="customer" class="form-control">
                                                    <option selected disabled>-Please Select Customer-</option>
                                                    @foreach ($customers as $customer)
                                                        @if ($customer->status == 1)
                                                            <option value="{{$customer->id}}" {{ old('customer') == $customer->id ? 'selected' : '' }}>{{$customer->last_name}}</option>
                                                        @endif  
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="truck_plate">Truck Plate No. <span style="color: red;">*</span></label>
                                                <select class="form-control" name="truck_plate" required>
                                                    <option selected disabled>-Please Select A Vehicle-</option>
                                                    @foreach ($vehicles as $vehicle)
                                                        <option value="{{$vehicle->id}}" {{ old('truck_plate') == $vehicle->id ? 'selected' : '' }}>{{$vehicle->plate_no}}</option> 
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="delivery_status">Delivery Status <span style="color: red;">*</span></label>
                                                <select name="delivery_status" class="form-control" required>
                                                    <option selected disabled>-Please Select One-</option>
                                                    <option value="0" {{ old('delivery_status') == '0' ? 'selected' : '' }}>To Be Delivered</option>
                                                    <option value="1" {{ old('delivery_status') == '1' ? 'selected' : '' }}>Delivering</option>
                                                    <option value="2" {{ old('delivery_status') == '2' ? 'selected' : '' }}>Delivered</option>
                                                    <option value="3" {{ old('delivery_status') == '3' ? 'selected' : '' }}>On Hold</option>
                                                    <option value="4" {{ old('delivery_status') == '4' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="remarks">Remarks</label>
                                            <textarea class="form-control" name="remarks" rows="2">{{ old('remarks') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card for Item Code, Total Item, and Item Description -->
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Add Items</h4>
                                        <div class="item-container">
                                            <!-- Item entry fields will be dynamically added here -->
                                            @if(old('item_code'))
                                                @foreach(old('item_code') as $key => $value)
                                                    <div class="form-row item-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="address">Item Code <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="item_code[]" value="{{ old('item_code.' . $key) }}" required>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="address">Total Items <span style="color: red;">*</span></label>
                                                            <input type="number" class="form-control" name="amount[]" value="{{ old('amount.' . $key) }}" required>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="address">Item Color <span style="color: red;">*</span></label>
                                                            <input type="color" class="form-control" name="item_color[]" value="{{ old('item_color.' . $key) }}" required>
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <label for="address">Item Description <span style="color: red;">*</span></label>
                                                            <input type="text" class="form-control" name="item_desc[]" value="{{ old('item_desc.' . $key) }}" required>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="form-row item-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="address">Item Code <span style="color: red;">*</span></label>
                                                        <input type="text" class="form-control" name="item_code[]" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="address">Total Item <span style="color: red;">*</span></label>
                                                        <input type="number" class="form-control" name="amount[]" required>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="address">Item Color <span style="color: red;">*</span></label>
                                                        <input type="color" class="form-control" name="item_color[]" required>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="address">Item Description <span style="color: red;">*</span></label>
                                                        <input type="text" class="form-control" name="item_desc[]" required>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <!-- Add More button to dynamically add more item entry fields -->
                                        <div class="form-row">
                                            <div class="form-group mr-2">
                                                <button type="button" class="btn btn-info" onclick="addMoreItems()">Add More</button>
                                            </div>
                                            <div class="form-group">
                                                <button type="button" class="btn btn-danger remove-btn" onclick="removeLastItem()" style="display: none;">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Submit button -->
                                <button type="submit" class="btn btn-outline-success" style="float:right">Create Shipment</button>
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
    function addMoreItems() {
        // Clone the last item entry row and append it to the container
        var itemContainer = document.querySelector('.item-container');
        var lastItemRow = document.querySelector('.item-row:last-child');
        var clonedItemRow = lastItemRow.cloneNode(true);

        // Reset the values of the cloned item row
        clonedItemRow.querySelectorAll('input').forEach(function (input) {
            input.value = ''; // Reset the value of each input field
        });

        // Insert a divider before appending the cloned item row
        var divider = document.createElement('hr');
        itemContainer.appendChild(divider);
        var spacing = document.createElement('br');
        itemContainer.appendChild(spacing);

        // Append the cloned item row
        itemContainer.appendChild(clonedItemRow);

        // Display the remove button
        document.querySelector('.remove-btn').style.display = 'block';
    }

    function removeLastItem() {
        // Remove the last item entry row
        var itemContainer = document.querySelector('.item-container');
        var lastItemRow = document.querySelector('.item-row:last-child');
        itemContainer.removeChild(lastItemRow);

        // Remove the divider and spacing
        itemContainer.removeChild(itemContainer.lastChild); // Remove the divider
        itemContainer.removeChild(itemContainer.lastChild); // Remove the spacing

        // Hide the remove button if there is only one item section left
        var itemRows = document.querySelectorAll('.item-row');
        if (itemRows.length <= 1) {
            document.querySelector('.remove-btn').style.display = 'none';
        }
    }
</script>
</body>

</html>
