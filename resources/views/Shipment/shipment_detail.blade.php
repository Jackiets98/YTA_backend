<!doctype html>
<html lang="en">

@include('layout.header')

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
                                    <h4 class="mb-sm-0 font-size-18">Shipment's Details</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                                            <li class="breadcrumb-item"><a href="/shipments">Shipments</a></li>
                                            <li class="breadcrumb-item active">{{$shipment_code}}</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="row">
                                    <!-- Total News uploaded By User -->
                                    <div class="col-lg-6">
                                        <div class="card blog-stats-wid">
                                            <a href="#uploadedNews">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Shipment Code</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 0, 0);">{{$shipment_code}}</span>
                                                        </h5>
                                                    </div>

                                                    <div class="avatar-sm ms-auto">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                            <i class='bx bx-news bx-rotate-90 bx-tada'
                                                                style='color:#131313'></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Total Top Up By User -->
                                    <div class="col-lg-6">
                                        <div class="card blog-stats-wid">
                                            <a href="#totalTopUp">
                                            <div class="card-body">

                                                <div class="d-flex flex-wrap">
                                                    <div class="me-3">
                                                        <p class="text-muted mb-2">Total Order(s)</p>
                                                        <h5 class="mb-0"><span
                                                                style="color: rgb(0, 110, 253);">{{$totalOrders}}</span>
                                                        </h5>
                                                    </div>

                                                    <div class="avatar-sm ms-auto">
                                                        <div
                                                            class="avatar-title bg-light rounded-circle text-primary font-size-20">
                                                            <i class='bx bxs-bank bx-rotate-90 bx-tada'
                                                                style='color:#356eec'></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- end row -->
                            </div>
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
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        @foreach($shipmentDetail as $shipmentDetail)
                                        <div class="image-preview" id="imagePreview">
                                        </div>
                                        <p class="text-muted mb-2">Orders</p>
                      
                                        @php
                                            $itemCodes = explode('==', $shipmentDetail->item_code);
                                            $itemDescriptions = explode('==', $shipmentDetail->item_description);
                                            $amounts = explode('==', $shipmentDetail->amount);
                                            $itemColors = explode('==', $shipmentDetail->item_color)
                                        @endphp

                                        @foreach ($itemCodes as $index => $itemCode)
                                            <div class="item-data">
                                                <h3 class="card-title">{{ $itemCode }}</h3>
                                                <h4 class="card-title">{{ $itemDescriptions[$index] }} (Qty: {{ $amounts[$index] }})</h4>
                                            </div>
                                            @if ($index < count($itemCodes) - 1)
                                                <hr> <!-- horizontal line as a divider -->
                                            @endif
                                        @endforeach
                                        <hr>
                                        <br>
                                        <p class="card-title-desc">{{$shipmentDetail->remarks}}</p>
                                       
                                        <div class="row">
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Delivery Person: </span>{{ $shipmentDetail->name }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Truck Plate No: </span>{{ $shipmentDetail->plate_no }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p>
                                                    <span style="font-weight: bold;">Status: </span>
                                                    @if ($shipmentDetail->delivery_status == '0')
                                                        <span class="text-primary">To Be Delivered</span>
                                                    @elseif ($shipmentDetail->delivery_status == '1')
                                                        <span class="text-warning">Delivering</span>
                                                    @elseif ($shipmentDetail->delivery_status == '2')
                                                    <span class="text-success">Delivered</span>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Departed Time: </span>{{ $shipmentDetail->departed_time }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Arrival Time: </span>{{ $shipmentDetail->delivered_time }}</p>
                                            </div>
                                            <div class="col-6">
                                                <p><span style="font-weight: bold;">Customer: </span>{{ $shipmentDetail->last_name }}</p>
                                            </div>
                                            <br>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <a href="/shipments"><button class="btn btn-secondary mr-1">Back</button></a>
                                                <button type="button" class="btn btn-warning mr-1"
                                                data-toggle="modal" data-target="#exampleModalCenter">
                                                <i class="fa fa-edit"></i>
                                                </button>
                                                <a href="/get-gps-data/{{$shipmentDetail->imei}}"><button class="btn btn-primary mr-1">Get GPS Location</button></a>
                                                @if ($shipmentDetail->delivery_status != '2')
                                                <a href="/get-location-data/{{$shipmentDetail->imei}}"><button class="btn btn-primary">Set GeoFence</button></a>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                    </div> <!-- end col -->
                </div>


                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Update Current Shipment's Information
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                @foreach ($form as $shipment)
                                <form method="POST" action="{{ url('/shipmentUpdate/'.$shipment->id) }}">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-12">
                                                <label>Shipment Code <span style="color: red;">*</span></label>
                                                <input value="{{ $shipment->shipment_code }}" class="form-control" name="shipment_code" required>
                                            </div>
                                        </div>  
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Driver <span style="color: red;">*</span></label>
                                                <select class="form-control" name="driver" required>
                                                    @foreach ($drivers as $item)
                                                    <option value="{{ $item->id }}"  @if($item->id === $shipment->driver) selected @endif>{{ $item->name }}</option>                             
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Deliver To <span style="color: red;">*</span></label>
                                                <select class="form-control" name="customer" required>
                                                    @foreach ($customers as $item)
                                                    <option value="{{ $item->id }}"  @if($item->id === $shipment->customer) selected @endif>{{ $item->last_name }}</option>                             
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Truck Plate No. <span style="color: red;">*</span></label>
                                                <select class="form-control" name="truck_plate" required>
                                                    @foreach($allVehicle as $vehicles)
                                                    <option value="{{ $vehicles->id }}"  @if($vehicles->id === $shipment->truck_plate_no) selected @endif>{{ $vehicles->plate_no }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Status <span style="color: red;">*</span></label>
                                                <select class="form-control" name="status" required>
                                                    <option value="0"  @if($shipment->delivery_status == '0') selected @endif>To Be Delivered</option>       
                                                    <option value="1"  @if($shipment->delivery_status == '1') selected @endif>Delivering</option> 
                                                    <option value="2"  @if($shipment->delivery_status == '2') selected @endif>Delivered</option> 
                                                    <option value="3"  @if($shipment->delivery_status == '3') selected @endif>On Hold</option>   
                                                    <option value="4"  @if($shipment->delivery_status == '4') selected @endif>Cancelled</option>                     
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            @if ($shipment->remarks == NULL)
                                                <textarea class="form-control" name="remarks" rows="4">No Remarks Yet.</textarea>  
                                            @else
                                            <textarea class="form-control" name="remarks" rows="4">{{$shipment->remarks}}</textarea>
                                            @endif
                                        </div>
                                        
                                        <br>
                                        @php
                                            $numSets = max(count($itemCodes), count($amounts), count($itemDescriptions));
                                        @endphp

                                        <div id="itemSetsContainer">
                                            @for ($i = 0; $i < $numSets; $i++)
                                                <div class="form-row item-set">
                                                    <div class="form-group col-md-3">
                                                        <label>Item Code {{ $i + 1 }} <span style="color: red;">*</span></label>
                                                        <input value="{{ $itemCodes[$i] ?? '' }}" class="form-control" name="item_code[]" type="text" required>
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <label>Total Items {{ $i + 1 }} <span style="color: red;">*</span></label>
                                                        <input value="{{ $amounts[$i] ?? '' }}" class="form-control" name="amount[]" required>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Item Description {{ $i + 1 }} <span style="color: red;">*</span></label>
                                                        <input value="{{ $itemDescriptions[$i] ?? '' }}" class="form-control" name="item_description[]" required>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label>Item Color {{ $i + 1 }} <span style="color: red;">*</span></label>
                                                        <input value="{{ $itemColors[$i] ?? '' }}" class="form-control" name="item_color[]" type="color" required>
                                                    </div>
                                                </div>
                                                <hr>
                                            @endfor
                                        </div>
                                    <button type="button" class="btn btn-success" id="addMoreBtn">Add More</button>
                                    <button type="button" class="btn btn-danger" id="removeBtn">Remove</button>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit"
                                            class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                                @endforeach
                            </div>
                        </div>
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
                    document.getElementById("addMoreBtn").addEventListener("click", function () {
                        // Clone the last set of inputs and increment the labels
                        const lastItemSet = document.querySelector(".item-set:last-of-type");
                        const newItemSet = lastItemSet.cloneNode(true);

                        // Increment labels
                        const labels = newItemSet.querySelectorAll("label");
                        labels.forEach(label => {
                            const labelTextArray = label.innerText.split(" ");
                            const labelText = labelTextArray[0] + " " + labelTextArray[1];
                            const labelNumber = parseInt(labelTextArray[2]) + 1; // Assuming the number is at index 2
                            label.innerHTML = labelText + " " + labelNumber + ' <span style="color: red;">*</span>';
                        });

                        // Clear input values in the new set
                        const inputs = newItemSet.querySelectorAll("input");
                        inputs.forEach(input => {
                            input.value = "";
                        });

                        // Append the new set
                        document.getElementById("itemSetsContainer").appendChild(newItemSet);
                    });

                    document.getElementById("removeBtn").addEventListener("click", function () {
                        const itemSets = document.querySelectorAll(".item-set");
                        if (itemSets.length > 1) {
                            const lastItemSet = itemSets[itemSets.length - 1];
                            lastItemSet.parentNode.removeChild(lastItemSet);
                        }
                    });
                });
            </script>

    </body>

</html>