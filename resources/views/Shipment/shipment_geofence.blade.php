<!doctype html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">

@include('layout.header')
<style>
#map-container {
    position: relative;
    height: 100vh;
    width: 100%;
}

#map {
    height: 100%;
    width: 80%; /* Adjust the width to suit your layout */
    float: left;
}

#sidebar {
    height: 100%;
    width: 20%; /* Adjust the width to suit your layout */
    float: left;
    overflow: auto;
}

#sidebar-header {
    background-color: #f2f2f2;
    padding: 10px;
    text-align: center;
}

#toggleSidebarButton {
    display: block;
    width: 100%;
    padding: 10px;
    cursor: pointer;
    border: none;
    background-color: #ddd;
}

#fenceList {
    padding: 10px;
}

/* Existing CSS */
.info-window {
    background-color: #fff;
    border-radius: 5px;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.info-window label {
    display: block;
    margin-bottom: 8px;
}

.info-window input[type="text"] {
    width: calc(100% - 20px);
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 6px;
}

.info-window input[type="radio"] {
    margin: 0px 0; /* Changed from margin-right to margin */
}

.info-window button {
    margin-top: 8px;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
}

.info-window button#cancelButton {
    background-color: #f44336;
}


    .fence-name-div {
        height: 40px;
        display: flex;
        align-items: center;
    }

    .fence-name-div:hover {
        background-color: #f0f0f0;
    }

    .fence-name-div input[type="checkbox"],
    .fence-name-div span {
        margin: 5px 5px; /* Adjust the margin as needed */
    }

/* Add more styling as needed */
</style>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places,drawing"></script>
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="margin-left: 20px; margin-right: 20px">
                    <div id="map-container">
                        <div id="map" height="600px"></div>
                        <div id="sidebar">
                            <div id="sidebar-header">
                                <!-- You can have a header or title for the sidebar if needed -->
                                <h3>Fence List</h3>
                            </div>
                            <div id="fenceList">
                                <form id="fenceForm" action="/save-selected-fences" method="POST">
                                    @csrf <!-- Add CSRF token for Laravel -->
                                    
                                    <label class="d-block">
                                        <input type="checkbox" id="selectAll"> Select All
                                    </label>
                                    
                                    @foreach($fenceName as $index => $name)
                                        @if(isset($fenceId[$index]))
                                            @php
                                                $id = htmlspecialchars($fenceId[$index]);
                                            @endphp
                                        @else
                                            @php
                                                $id = ''; // or handle this case as needed
                                            @endphp
                                        @endif
                                        <label class="fence-name-div" style="display: flex; justify-content: space-between; height: 40px;">
                                            <span>
                                                <input type="checkbox" name="selectedFences[]" value="{{ $name }}">
                                                {{ $name }}
                                            </span>
                                            <div style="display: flex; align-items: center;">
                                                <a href="#" style="text-decoration: none;" onclick="deleteGeofence('{{ $imei }}', '{{ $id }}')">
                                                    <div>
                                                        <i class='bx bxs-trash delete-icon text-danger' data-fence-name="{{ $name }}" data-fence-id="{{ $id }}"></i>
                                                    </div>
                                                </a>
                                            </div>
                                        </label>
                                    @endforeach
                                </form>
                            </div>
                        </div>
                    </div>
                        <div id="loading-indicator" style="display: none; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <div style="background-color: rgba(0, 0, 0, 0.7); color: white; padding: 15px; border-radius: 10px; text-align: center;">
                                <i class="fa fa-spinner fa-spin" style="font-size: 24px;"></i>
                                <p style="margin-top: 10px; font-size: 18px;">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <!-- Buttons for adding geofences -->
                    <button id="addCircularGeofence" class="btn btn-primary">Add Circular Geofence</button>
                </div>
            </div>
            @include('layout.footer')
        </div>
        <!-- end main content-->
        @include('layout.scripts')
        <script>
    function deleteGeofence(imei, fenceId) {
        if (confirm("Are you sure you want to delete this geofence?")) {
            window.location.href = `/delete-geofence-data/${imei}/${fenceId}`;
        }
    }
</script>

        <script>
            let drawingManager;
            let isDrawing = false;
            let circle;
            let undoCircle;
            let infoWindow;
            let infoWindowContent;
            let polygonInfoWindow;
            let polygonInfoWindowContent;
            let polygonPath = [];
            let polygon;
            let addCircularGeofenceButton = document.getElementById('addCircularGeofence');
            let addPolygonGeofenceButton = document.getElementById('addPolygonGeofence');
            let type;
            var settingParameter;

            document.addEventListener('DOMContentLoaded', function () {
                var initialGPSData = {
                    lat: {{ $lat }},
                    lng: {{ $lng }},
                };


                // Define an object to store the drawn circular geofences
                const drawnGeofences = {};

                // Function to draw a circular geofence based on setting information
                function drawCircularGeofence(fenceName, setting) {
                    if (setting) {
                        const [lng, lat, radius] = setting.split(' ').map(parseFloat);
                        const center = new google.maps.LatLng(lat, lng);

                        const circle = new google.maps.Circle({
                            strokeColor: '#314594',
                            strokeOpacity: 0.8,
                            strokeWeight: 2,
                            fillColor: '#314594',
                            fillOpacity: 0.35,
                            map: map, // Ensure 'map' represents your Google Map instance
                            center: center,
                            radius: radius,
                        });

                        // Store the drawn geofence in the object
                        drawnGeofences[fenceName] = circle;
                    }
                }

                // Function to remove a drawn geofence
                function removeGeofence(fenceName) {
                    if (drawnGeofences[fenceName]) {
                        drawnGeofences[fenceName].setMap(null);
                        delete drawnGeofences[fenceName];
                    }
                }

                // Trigger drawing and removal of geofences when checkboxes are changed
                const checkboxes = document.querySelectorAll('input[name="selectedFences[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function () {
                        const fenceName = this.value;
                        const index = <?php echo json_encode($fenceName); ?>.indexOf(fenceName);
                        const setting = <?php echo json_encode($fenceCoordinates); ?>[index];

                        if (this.checked) {
                            drawCircularGeofence(fenceName, setting);
                        } else {
                            // Remove the geofence if unchecked
                            removeGeofence(fenceName);
                        }
                    });
                });

                // Initialize Google Map
                const mapElement = document.getElementById('map');
                const mapOptions = {
                    center: { lat: initialGPSData.lat, lng: initialGPSData.lng },
                    zoom: 15
                };
                const map = new google.maps.Map(mapElement, mapOptions);

                // Add a marker for the current location
                const marker = new google.maps.Marker({
                    position: { lat: initialGPSData.lat, lng: initialGPSData.lng },
                    map: map,
                    title: 'Current Location'
                });

                // Function to handle adding a circular geofence
                addCircularGeofenceButton.addEventListener('click', function () {
                    if (!isDrawing) {
                        type = 2;
                        enableDrawingCircularGeofence();
                    }
                });

                function enableDrawingCircularGeofence() {
                    if (drawingManager) {
                        drawingManager.setMap(null);
                    }
                    drawingManager = new google.maps.drawing.DrawingManager({
                        drawingMode: google.maps.drawing.OverlayType.CIRCLE,
                        drawingControl: false,
                    });

                    drawingManager.setMap(map);

                    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {
                        if (event.type === google.maps.drawing.OverlayType.CIRCLE) {
                            // The user has completed drawing a circle geofence
                            undoCircle = circle; // Store the last drawn circle to undo
                            circle = event.overlay;
                            // Access circle's radius and center properties
                            const radius = circle.getRadius();
                            const center = circle.getCenter();
                            const centerLat = center.lat();
                            const centerLng = center.lng();
                            // Combine values to form the 'setting' parameter
                            settingParameter = `${parseFloat(centerLng).toFixed(6)} ${parseFloat(centerLat).toFixed(6)} ${Math.round(radius)}`;
                            console.log('Setting Parameter:', settingParameter);


                            // Create a marker for the center
                            infoWindowContent = getGeofenceInfoHtml(radius, center, "circle");
                            infoWindow = new google.maps.InfoWindow({
                                content: infoWindowContent,
                                position: center,
                            });

                            // Add a listener to the infoWindow's closeclick event to perform cancel action
                            google.maps.event.addListener(infoWindow, 'closeclick', cancelGeofence);

                            infoWindow.open(map);
                            addCircularGeofenceButton.disabled = true; // Disable circular geofence button
                            addPolygonGeofenceButton.disabled = true; // Disable polygon geofence button
                            drawingManager.setMap(null);
                            isDrawing = false;
                        }
                    });

                    isDrawing = true;
                }

                const selectAllCheckbox = document.getElementById('selectAll');

                // Event listener for the "Select All" checkbox
                selectAllCheckbox.addEventListener('change', function () {
                    const allFenceCheckboxes = document.querySelectorAll('input[name="selectedFences[]"]');

                    allFenceCheckboxes.forEach(checkbox => {
                        // Uncheck all currently checked checkboxes
                        checkbox.checked = false;
                        
                        // Trigger change event manually for each checkbox
                        const event = new Event('change');
                        checkbox.dispatchEvent(event);
                    });

                    // Check all checkboxes if "Select All" is checked
                    if (selectAllCheckbox.checked) {
                        allFenceCheckboxes.forEach(checkbox => {
                            checkbox.checked = true;

                            // Trigger change event manually for each checkbox
                            const event = new Event('change');
                            checkbox.dispatchEvent(event);
                        });
                    }
                });


                // Function to handle adding a polygon geofence
                addPolygonGeofenceButton.addEventListener('click', function () {
                    if (!isDrawing) {
                        type = 1;
                        enableDrawingPolygonGeofence();
                    }
                });

                function enableDrawingPolygonGeofence() {
                    if (drawingManager) {
                        drawingManager.setMap(null);
                    }
                    drawingManager = new google.maps.drawing.DrawingManager({
                        drawingMode: google.maps.drawing.OverlayType.POLYGON,
                        drawingControl: false,
                    });

                    drawingManager.setMap(map);

                    google.maps.event.addListener(drawingManager, 'overlaycomplete', function (event) {
                        if (event.type === google.maps.drawing.OverlayType.POLYGON) {
                            // The user has completed drawing a polygon geofence
                            polygon = event.overlay;
                            polygonPath = polygon.getPath();

                            // Create a listener for double-click to finish drawing
                            google.maps.event.addListener(map, 'dblclick', finishDrawingPolygon);

                            // Update infoWindow for polygon drawing
                            polygonInfoWindowContent = getGeofenceInfoHtml(null, null, "polygon");
                            polygonInfoWindow = new google.maps.InfoWindow({
                                content: polygonInfoWindowContent,
                                position: polygonPath.getAt(0),
                            });

                            // Add a listener to the infoWindow's closeclick event to perform cancel action
                            google.maps.event.addListener(polygonInfoWindow, 'closeclick', cancelPolygonGeofence);

                            polygonInfoWindow.open(map);
                            addCircularGeofenceButton.disabled = true; // Disable circular geofence button
                            addPolygonGeofenceButton.disabled = true; // Disable polygon geofence button
                            drawingManager.setMap(null);
                            isDrawing = false;
                        }
                    });
                }

                function finishDrawingPolygon(event) {
                    // The user has double-clicked to finish drawing the polygon
                    google.maps.event.clearListeners(map, 'dblclick');
                    // Close the polygon infoWindow
                    polygonInfoWindow.close();
                    // Disable drawing and remove the drawing manager
                    drawingManager.setMap(null);
                    isDrawing = false;
                    addCircularGeofenceButton.disabled = false; // Enable circular geofence button
                    addPolygonGeofenceButton.disabled = false; // Enable polygon geofence button
                }

                function getGeofenceInfoHtml(radius, center, type) {
                    if (type === "circle") {
                        return `
                            <div class="info-window">
                                <p>Current Geofence: ${Math.round(radius)} meters</p>
                                <input type="text" id="geofenceName" placeholder="Enter Geofence Name">
                                <br>
                                <div class="radio-options">
                                    <label>
                                        <input type="radio" id="inFenceAlert" name="alertType" value="1" checked> In Fence Alert
                                    </label>
                                    <label>
                                        <input type="radio" id="outFenceAlert" name="alertType" value="2"> Out Fence Alert
                                    </label>
                                    <label>
                                        <input type="radio" id="inOutFenceAlert" name="alertType" value="3"> In/Out Fence Alert
                                    </label>
                                </div>
                                <input type="hidden" id="triggerOnce" value="0">
                                <input type="checkbox" id="triggerOnceCheckbox" checked> Trigger Only Once
                                <br>
                                <div class="infoWindowButtons">
                                    <button id="okButton" onclick="saveGeofence()">OK</button>
                                    <button id="cancelButton" onclick="cancelPolygonGeofence()">Cancel</button>
                                </div>
                            </div>
                        `;
                    } else if (type === "polygon") {
                        return `
                            <div>
                                <input type="text" id="geofenceName" placeholder="Enter Geofence Name">
                                <br>
                                <div class="radio-options">
                                    <label>
                                        <input type="radio" id="inFenceAlert" name="alertType" value="1" checked> In Fence Alert
                                    </label>
                                    <label>
                                        <input type="radio" id="outFenceAlert" name="alertType" value="2"> Out Fence Alert
                                    </label>
                                    <label>
                                        <input type="radio" id="inOutFenceAlert" name="alertType" value="3"> In/Out Fence Alert
                                    </label>
                                </div>
                                <input type="checkbox" id="triggerOnceCheckbox" checked> Trigger Only Once
                                <br>
                                <button id="okButton" onclick="saveGeofence()">OK</button>
                                <button id="cancelButton" onclick="cancelPolygonGeofence()">Cancel</button>
                            </div>
                        `;
                    }
                }
            });

            const checkbox = document.getElementById('triggerOnceCheckbox');

            checkbox.addEventListener('change', function() {
                const value = this.checked ? 1 : 0;
                console.log('Value:', value);
                // You can use the 'value' variable as needed, for instance, send it to a server, update a display, etc.
            });

            // Function to close circular geofence info window
            function closeCircularGeofenceWindow() {
                if (infoWindow) {
                    infoWindow.close();
                }
                // Remove the current circle
                if (circle) {
                    circle.setMap(null);
                }
                // Restore the last drawn circle
                if (undoCircle) {
                    undoCircle.setMap(map);
                }
                addCircularGeofenceButton.disabled = false; // Enable circular geofence button
                addPolygonGeofenceButton.disabled = false; // Enable polygon geofence button
            }

            // Function to close polygon geofence info window
            function closePolygonGeofenceWindow() {
                if (polygonInfoWindow) {
                    polygonInfoWindow.close();
                }
                // Remove the current polygon
                if (polygon) {
                    polygon.setMap(null);
                }
                drawingManager.setMap(null);
                isDrawing = false;
                addCircularGeofenceButton.disabled = false; // Enable circular geofence button
                addPolygonGeofenceButton.disabled = false; // Enable polygon geofence button
            }


            function saveGeofence() {
                const geofenceName = document.getElementById('geofenceName').value;
                if (geofenceName.trim() === '') {
                    alert('Fence name cannot be empty!');
                    return; // Prevent further execution if the name is empty
                }
                const alertType = document.querySelector('input[name="alertType"]:checked').value;
                const triggerOnce = triggerOnceCheckbox.checked ? 1 : 0;

                
                const data = {
                    fenceName: geofenceName,
                    triggerType: alertType,
                    oneTime: triggerOnce,
                    type: type, // Previously set as a global variable
                    setting: settingParameter,
                    mapType: 1, // New parameter as discussed
                    imei: {{ $imei }}
                    // Add more parameters if needed
                };

                console.log(data);

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/save-geofence-data/{{ $imei }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(data),
                })
                .then(response => {
                    // If the response is successful, close the info windows and reload the page after 1.8 seconds
                    
                    closeCircularGeofenceWindow();
                    closePolygonGeofenceWindow();
                    setTimeout(() => {
                        location.reload(); // Reload the page after 1.8 seconds
                    }, 1800); // 1800 milliseconds = 1.8 seconds
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }

            function cancelGeofence() {
                // Close the circular geofence infoWindow
                if (infoWindow) {
                    infoWindow.close();
                }
                // Remove the current circle
                circle.setMap(null);
                // Restore the last drawn circle
                if (undoCircle) {
                    undoCircle.setMap(map);
                }
                addCircularGeofenceButton.disabled = false; // Enable circular geofence button
                addPolygonGeofenceButton.disabled = false; // Enable polygon geofence button
            }

            function cancelPolygonGeofence() {
                // Close the polygon infoWindow
                if (polygonInfoWindow) {
                    polygonInfoWindow.close();
                }
                // Remove the current polygon
                polygon.setMap(null);
                // Disable drawing and remove the drawing manager
                drawingManager.setMap(null);
                isDrawing = false;
                addCircularGeofenceButton.disabled = false; // Enable circular geofence button
                addPolygonGeofenceButton.disabled = false; // Enable polygon geofence button
            }

        </script>
    </body>
</html>
