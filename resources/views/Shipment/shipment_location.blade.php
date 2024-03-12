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

    .circle {
    display: inline-block;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-left: 5px; /* Add some spacing between text and circle */
    }

    .green {
    background-color: green;
    }

    .red {
    background-color: red;
    }

    /* Center-align the tab buttons horizontally */
    .tab {
        text-align: center;
        background-color: #333; /* Background color for the tab area */
        padding: 10px; /* Add some padding for space */
        margin: 0px 20px;
    }

    /* Style the tab buttons */
    .tablinks {
        background-color: #555; /* Button background color */
        color: white; /* Text color */
        border: none; /* Remove borders */
        padding: 10px 20px; /* Adjust padding as needed */
        cursor: pointer;
        margin: 5px; /* Add some margin to separate buttons */
        transition: background-color 0.3s;
        border-radius: 5px; /* Add rounded corners */
    }

    /* Hover effect for tab buttons */
    .tablinks:hover {
        background-color: #777; /* Button background color on hover */
    }

    /* Active tab button style */
    .tablinks.active {
        background-color: #4CAF50; /* Active button background color */
    }

    /* Style for tab content */
    .tabcontent {
        display: none;
    }

    /* Style for map containers */
    #map1, #map2 {
        width: 100%;
        height: 100%;
    }

    .time-filter {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin: 10px;
    }

    .time-filter label {
        font-weight: bold;
        margin-right: 10px;
    }

    .time-filter input {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 10px;
    }

    .time-filter button {
        background-color: #4CAF50;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        font-weight: bold;
    }

    .time-filter button:hover {
        background-color: #45a049;
    }

    .disabled-button {
        background-color: #D3D3D3 !important; /* Change the background color to a muted color */
        color: #666 !important; /* Change the text color to a darker muted color */
        pointer-events: none; /* Disable pointer events (clicks) */
        cursor: not-allowed; /* Change the cursor to 'not-allowed' */
    }

</style>
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">


                <div class="row">
                    <div class="col-12">
                        <div class="tab">
                            <button class="tablinks active" onclick="openTab(event, 'Map1')" id="defaultOpen">Live Location Tracking</button>
                            <button class="tablinks" onclick="openTab(event, 'Map2')">Playback Trace</button>
                        </div>
                        <div class="card"  style="margin-left: 20px; margin-right: 20px">

                        <div id="Map1" class="tabcontent">
                            <div id="map" style="height: 600px;"></div>
                        </div>

                        <div id="Map2" class="tabcontent">
                            <div id="map2" style="height: 600px;">
                        </div>
                            <div class="time-filter">
                                <label for="start-time">Start Time:</label>
                                <input type="datetime-local" id="start-time">
                                <label for="end-time">End Time:</label>
                                <input type="datetime-local" id="end-time">
                                <button id="submit_button" onclick="applyTimeFilter()">Start Tracking</button>
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


                <div class="modal fade" id="noDataModal" tabindex="-1" role="dialog" aria-labelledby="noDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="noDataModalLabel">No Data Found</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeNoDataModal()">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                Sorry, no tracking data was found for the specified time range.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeNoDataModal()">Close</button>
                            </div>
                        </div>
                    </div>
                </div>


            @include('layout.footer')
            </div>
            <!-- end main content-->

            @include('layout.scripts')

        <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap"></script>

        <script>
            function openTab(evt, tabName) {
                var i, tabcontent, tablinks;

                // Hide all tabcontent elements
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }

                // Remove the "active" class from all tablinks
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }

                // Show the specific tabcontent and add an "active" class to the tablink
                document.getElementById(tabName).style.display = "block";
                evt.currentTarget.className += " active";

                // Initialize the map for the selected tab
                if (tabName === 'Map1') {
                    initMap();
                } else if (tabName === 'Map2') {
                    initMap2();
                }
            }

            // Automatically open the default tab
            document.getElementById("defaultOpen").click();

            var lorryMarker; // Define a variable for the lorry marker
            var map;
            var map2;
            var currentDirection = 0; // Initial direction in degrees
            var infoWindow; // Define a variable for the info window
            var markers = []; // Array to store markers
            var currentIndex = 0; // Index of the current marker being displayed
            var previousMarker = null; // Reference to the previous marker
            var markerInterval = 500; // Interval between displaying markers in milliseconds (adjust as needed)
            var routeCoordinates = []; // Array to store coordinates for the route line
            var routeLine = null; // Reference to the route line
            // Get the loading indicator element
            const loadingIndicator = document.getElementById("loading-indicator");
            const filterButton = document.getElementById("submit_button");
            const tabButton = document.getElementById("defaultOpen");

            function initMap() {
                // Set up the map
                map = new google.maps.Map(document.getElementById('map'), {
                    center: { lat: initialGPSData.lat, lng: initialGPSData.lng },
                    zoom: 13
                });

            // Set the initial direction
            var initialDirection = initialGPSData.course;

            // Map the initial direction to the nearest available direction
            var availableDirections = [0, 11.25, 22.5, 33.75, 45, 56.25, 67.5, 78.75, 90, 101.25, 112.25, 123.75, 135, 146.25, 157.5, 168.75, 180, 191.25, 202.5, 213.75, 225, 236.25, 247.5, 258.75, 270, 281.25, 292.5, 303.75, 315, 326.25, 337.5, 348.75];
            var nearestDirection = availableDirections[0];

            for (const dir of availableDirections) {
                if (Math.abs(initialDirection - dir) < Math.abs(initialDirection - nearestDirection)) {
                    nearestDirection = dir;
                }
            }

           // Function to update the lorry's direction
            function updateLorryDirection(newDirection) {
                // Define an array of available directions and their corresponding image file names
                const availableDirections = [
                    0, 11.25, 22.5, 33.75, 45, 56.25, 67.5, 78.75, 90, 101.25, 112.25, 123.75,
                    135, 146.25, 157.5, 168.75, 180, 191.25, 202.5, 213.75, 225, 236.25, 247.5, 258.75,
                    270, 281.25, 292.5, 303.75, 315, 326.25, 337.5, 348.75
                ];
                const imageFiles = [
                    'carIcon_0.png', 'carIcon_11.25.png', 'carIcon_22.5.png', 'carIcon_33.75.png',
                    'carIcon_45.png', 'carIcon_56.25.png', 'carIcon_67.5.png', 'carIcon_78.75.png',
                    'carIcon_90.png', 'carIcon_101.25.png', 'carIcon_112.25.png', 'carIcon_123.75.png',
                    'carIcon_135.png', 'carIcon_146.25.png', 'carIcon_157.5.png', 'carIcon_168.75.png',
                    'carIcon_180.png', 'carIcon_191.25.png', 'carIcon_202.5.png', 'carIcon_213.75.png',
                    'carIcon_225.png', 'carIcon_236.25.png', 'carIcon_247.5.png', 'carIcon_258.75.png',
                    'carIcon_270.png', 'carIcon_281.25.png', 'carIcon_292.5.png', 'carIcon_303.75.png',
                    'carIcon_315.png', 'carIcon_326.25.png', 'carIcon_337.5.png', 'carIcon_348.75.png'
                ];

                // Find the nearest available direction
                let nearestDirection = availableDirections[0];
                for (const dir of availableDirections) {
                    if (Math.abs(newDirection - dir) < Math.abs(newDirection - nearestDirection)) {
                        nearestDirection = dir;
                    }
                }

                // Find the corresponding image file for the nearest direction
                const index = availableDirections.indexOf(nearestDirection);
                const imageFileName = imageFiles[index];

                // Calculate the image URL based on the new direction
                const imageUrl = '/carsIcon/' + imageFileName;

                // Update the lorry icon with the new image and rotation
                lorryMarker.setIcon({
                    url: imageUrl,
                    scaledSize: new google.maps.Size(60, 60), 
                    anchor: new google.maps.Point(20, 20),
                    rotation: newDirection
                });

                // Update the current direction
                currentDirection = newDirection;

                // Log the new direction and image URL
                console.log('New Direction:', newDirection);
                console.log('Image URL:', imageUrl);
            }

            // Create the icon element
            const centerIcon = document.createElement('img');
            centerIcon.src = '/carsIcon/locator.png'; // Replace with the path to your icon image
            centerIcon.alt = 'Center on Marker';

            // Set the image size (width and height)
            centerIcon.style.width = '40px'; // Adjust the width as needed
            centerIcon.style.height = '40px'; // Adjust the height as needed

            // Add a click event listener to the icon
            centerIcon.addEventListener('click', function() {
                map.setCenter(lorryMarker.getPosition());
                map.setZoom(13); // Adjust the zoom level as needed
            });

            // Create a div element to hold the icon
            const centerIconDiv = document.createElement('div');
            centerIconDiv.appendChild(centerIcon);

            // Set the CSS for the div
            centerIconDiv.style.padding = '10px'; // Add space at the bottom
            centerIconDiv.style.backgroundColor = 'white';
            centerIconDiv.style.border = '1px solid rgba(0, 0, 0, 0.2)';
            centerIconDiv.style.borderRadius = '5px';
            centerIconDiv.style.cursor = 'pointer';
            centerIconDiv.style.margin = '10px';
            

            // Position the control at the bottom-center of the map
            map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerIconDiv);


            // Create a div element to hold the icon
            const lorryDetailsDiv = document.createElement('div');

            // Set the CSS for the div
            lorryDetailsDiv.style.padding = '10px 25px';
            lorryDetailsDiv.style.backgroundColor = 'white';
            lorryDetailsDiv.style.border = '1px solid rgba(0, 0, 0, 0.2)';
            lorryDetailsDiv.style.borderRadius = '5px';
            lorryDetailsDiv.style.cursor = 'pointer';
            lorryDetailsDiv.style.margin = '10px 0px 30px -65px';
            

            // Position the control at the bottom-center of the map
            map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(lorryDetailsDiv);

            // Create the content you want to display
            const content = `
                <p><strong style="font-size: 16px">${initialGPSData.plateNo}</strong></p>
                <p>IMEI: ${initialGPSData.imei}</p>
                <p>Latitude: ${initialGPSData.lat}</p>
                <p>Longitude: ${initialGPSData.lng}</p>
                <p>Speed: ${initialGPSData.speed} km/h</p>
                <p>Direction: ${initialGPSData.course}</p>
                <p>Battery Level: ${initialGPSData.battery} V</p>
                <p>Status: <span style="color: ${initialGPSData.status === '静止' ? 'blue' : 'green'}">${initialGPSData.status}</span></p>
            `;

            // Set the content inside the lorryDetailsDiv
            lorryDetailsDiv.innerHTML = content;

            

             // Function to update the lorry's direction
             function updateLorryRealTimeDirection(newDirection) {
                // Define an array of available directions and their corresponding image file names
                const availableDirections = [
                    0, 11.25, 22.5, 33.75, 45, 56.25, 67.5, 78.75, 90, 101.25, 112.25, 123.75,
                    135, 146.25, 157.5, 168.75, 180, 191.25, 202.5, 213.75, 225, 236.25, 247.5, 258.75,
                    270, 281.25, 292.5, 303.75, 315, 326.25, 337.5, 348.75
                ];
                const imageFiles = [
                    'carIcon_0.png', 'carIcon_11.25.png', 'carIcon_22.5.png', 'carIcon_33.75.png',
                    'carIcon_45.png', 'carIcon_56.25.png', 'carIcon_67.5.png', 'carIcon_78.75.png',
                    'carIcon_90.png', 'carIcon_101.25.png', 'carIcon_112.25.png', 'carIcon_123.75.png',
                    'carIcon_135.png', 'carIcon_146.25.png', 'carIcon_157.5.png', 'carIcon_168.75.png',
                    'carIcon_180.png', 'carIcon_191.25.png', 'carIcon_202.5.png', 'carIcon_213.75.png',
                    'carIcon_225.png', 'carIcon_236.25.png', 'carIcon_247.5.png', 'carIcon_258.75.png',
                    'carIcon_270.png', 'carIcon_281.25.png', 'carIcon_292.5.png', 'carIcon_303.75.png',
                    'carIcon_315.png', 'carIcon_326.25.png', 'carIcon_337.5.png', 'carIcon_348.75.png'
                ];

                // Find the nearest available direction
                let nearestDirection = availableDirections[0];
                for (const dir of availableDirections) {
                    if (Math.abs(newDirection - dir) < Math.abs(newDirection - nearestDirection)) {
                        nearestDirection = dir;
                    }
                }

                // Find the corresponding image file for the nearest direction
                const index = availableDirections.indexOf(nearestDirection);
                const imageFileName = imageFiles[index];

                // Calculate the image URL based on the new direction
                const imageUrl = '/carsIcon/' + imageFileName;

                // Update the lorry icon with the new image and rotation
                lorryMarker.setIcon({
                    url: imageUrl,
                    scaledSize: new google.maps.Size(60, 60), 
                    anchor: new google.maps.Point(20, 20),
                    rotation: newDirection
                });

                // Update the current direction
                currentDirection = newDirection;

                // Log the new direction and image URL
                console.log('New Direction:', newDirection);
                console.log('Image URL:', imageUrl);
            }

            function updateLorryLiveDirection() {
                $.ajax({
                    url: '/get-gps-data-json/{{ $imei }}', // Replace with the actual route
                    type: 'GET',
                    dataType: 'json', // Expect JSON response
                    success: function(data) {
                        var newDirection = data.course; // Access the live direction data
                        var newLat = data.lat; // Access the latitude
                        var newLng = data.lng; // Access the longitude
                        var speed = data.speed;
                        var battery = data.battery;
                        var status = data.status;
                        var engineStatus = data.engine;
                        var imei = data.imei;
                        var plateNo = data.plateNo;

                         // Create an HTML element to display the engine status with a colored circle
                        var engineStatusNewHTML = `
                        <div class="engine-status">
                        Engine: 
                        <span class="circle ${engineStatus === 'ON' ? 'green' : 'red'}"></span>
                        </div>`;

                        // Create the content with the updated data
                        const content = `
                            <p><strong style="font-size: 16px">${data.plateNo}</strong></p>
                            <p>IMEI: ${data.imei}</p>
                            <p>Latitude: ${data.lat}</p>
                            <p>Longitude: ${data.lng}</p>
                            <p>Speed: ${data.speed} km/h</p>
                            <p>Direction: ${data.course}</p>
                            <p>Battery Level: ${data.battery} V</p>
                            <p>Status: <span style="color: ${data.status === '静止' ? 'blue' : 'green'}">${data.status}</span></p>
                        `;

                        // Set the updated content inside lorryDetailsDiv
                        lorryDetailsDiv.innerHTML = content;

                        // Append engineStatusHtml to the lorryDetailsDiv
                        lorryDetailsDiv.innerHTML += engineStatusNewHTML;


                        // Update the lorry marker's position
                        lorryMarker.setPosition(new google.maps.LatLng(newLat, newLng));

                        updateLorryRealTimeDirection(newDirection);



                     // Update the info window content with the latest information
                    var infoContent = 'Latitude: ' + newLat + '<br>Longitude: ' + newLng + '<br>Speed: ' + speed + ' km/h'  + '<br>Direction: ' + newDirection + '<br>Battery Level: ' + battery + ' V' + '<br>Status: <span style="color: ' + (status === '静止' ? 'blue' : 'green') + '">' + status + '</span>' + engineStatusNewHTML;
                    infoWindow.setContent(infoContent);

                        // Do something with newLat and newLng if needed
                        console.log('New Latitude:', newLat);
                        console.log('New Longitude:', newLng);
                        console.log('Course:', newDirection);
                    },
                    error: function(error) {
                        console.error('Error fetching direction:', error);
                    }
                });
            }

                // Update the lorry's live direction periodically (every 30 seconds)
                setInterval(function() {
                    updateLorryLiveDirection();
                }, 30000); 

                // Create an HTML element to display the engine status with a colored circle
                var engineStatusHtml = `
                <div class="engine-status">
                Engine: 
                <span class="circle ${initialGPSData.engine === 'ON' ? 'green' : 'red'}"></span>
                </div>`;

                // Append engineStatusHtml to the lorryDetailsDiv
                lorryDetailsDiv.innerHTML += engineStatusHtml;

                // Initialize the info window
                infoWindow = new google.maps.InfoWindow({
                    content: 'Latitude: ' + initialGPSData.lat + '<br>Longitude: ' + initialGPSData.lng + '<br>Speed: ' + initialGPSData.speed + ' km/h'  + '<br>Direction: ' + initialGPSData.course + '<br>Battery Level: ' + initialGPSData.battery + ' V' + '<br>Status: <span style="color: ' + (initialGPSData.status === '静止' ? 'blue' : 'green') + '">' + initialGPSData.status + '</span>' + engineStatusHtml, // Replace with your details
                    maxWidth: 200, // Adjust the maximum width of the info window
                });



                // Initial lorry marker setup with the initial direction
                lorryMarker = new google.maps.Marker({
                    position: { lat: initialGPSData.lat, lng: initialGPSData.lng },
                    map: map,
                    icon: {
                        url: '/carsIcon/carIcon_' + nearestDirection + '.png', // Initial direction
                        scaledSize: new google.maps.Size(60, 60), 
                        anchor: new google.maps.Point(20, 20),
                        rotation: nearestDirection
                    }
                });

                // Add a listener for when the mouse hovers over the marker
                google.maps.event.addListener(lorryMarker, 'mouseover', function() {
                    // Display the info window when hovering
                    infoWindow.open(map, lorryMarker);
                });

                // Add a listener for when the mouse leaves the marker
                google.maps.event.addListener(lorryMarker, 'mouseout', function() {
                    // Close the info window when the mouse moves away
                    infoWindow.close();
                });

                // Run the direction update code only once on startup
                updateLorryDirection(nearestDirection);
            }

            // Define the initialGPSData object with actual data
            var initialGPSData = {
                lat: {{ $lat }},
                lng: {{ $lng }},
                course: {{ $course }},
                speed: {{ $speed }},
                battery: {{ $battery }},
                status: "{{ $status }}",
                engine: "{{ $engine == 1 ? 'ON' : 'OFF' }}",
                imei: {{$imei}},
                plateNo: "{{$plateNo}}"
            };

            // Call the initMap function when the page loads
            google.maps.event.addDomListener(window, 'load', initMap);

            // Function to initialize Map 2
            function initMap2() {
                map2 = new google.maps.Map(document.getElementById('map2'), {
                    center: { lat: 34.0522, lng: -118.2437 }, // Example coordinates
                    zoom: 12
                });
            }

            function applyTimeFilter() {

                // Disable the button by adding the CSS class
                filterButton.classList.add("disabled-button");
                tabButton.classList.add("disabled-button");

                // Show the loading indicator
                loadingIndicator.style.display = "block";

                // Get the values from the input fields
                const startTimeInput = document.getElementById("start-time");
                const endTimeInput = document.getElementById("end-time");

                // Get the selected start and end times as strings
                const startTimeString = startTimeInput.value;
                const endTimeString = endTimeInput.value;

                clearMapData();

                // Convert the time strings to Unix timestamps
                const startTimeUnix = new Date(startTimeString).getTime() / 1000;
                const endTimeUnix = new Date(endTimeString).getTime() / 1000;


                console.log(startTimeUnix);
                console.log(endTimeUnix);

                // Make an AJAX request to get tracking data
                $.ajax({
                    url: '/getTrackingData/{{ $imei }}/' + startTimeUnix + '/' + endTimeUnix,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        // Check if there is no data
                        if (response.data.length === 0) {
                            
                            // Hide the loading indicator
                            loadingIndicator.style.display = "none";

                            // Display an alert indicating that no data was found
                            $('#noDataModal').modal('show');

                        // Update the map with the tracking data
                        updateMapWithTrackingData(response.data);
                            return;
                        }

                        // Update the map with the tracking data
                        updateMapWithTrackingData(response.data);

                        // Hide the loading indicator
                        loadingIndicator.style.display = "none";

                        console.log(response);
                    },
                    error: function (error) {
                        console.error('Error fetching tracking data:', error);

                        // Hide the loading indicator in case of an error
                        loadingIndicator.style.display = "none";
                    }
                });
            }

            // Close the Bootstrap modal with ID "noDataModal"
            function closeNoDataModal() {
                $('#noDataModal').modal('hide');
            }

            function updateMapWithTrackingData(trackingData) {


                // Sort the tracking data by the Unix time in ascending order
                trackingData.sort(function (a, b) {
                    return a.gpsTime - b.gpsTime;
                });

                // Function to add a marker to the map
                function addMarker() {
                    if (currentIndex < trackingData.length) {
                        var dataPoint = trackingData[currentIndex];
                        var lat = parseFloat(dataPoint.lat);
                        var lng = parseFloat(dataPoint.lng);
                        var direction = parseFloat(dataPoint.course);

                        // Calculate the nearest direction for the car icon
                        var nearestDirection = calculateNearestDirection(direction);

                        // Create a new marker for this data point with a rotated car icon
                        var marker = new google.maps.Marker({
                            position: new google.maps.LatLng(lat, lng),
                            map: map2, // Replace 'map2' with your map object
                            icon: {
                                url: '/carsIcon/carIcon_' + nearestDirection + '.png',
                                scaledSize: new google.maps.Size(60, 60),
                                anchor: new google.maps.Point(30, 30), // Adjust the anchor point
                                rotation: direction, // Rotate the icon based on the direction
                            },
                        });

                        markers.push(marker); // Add the marker to the array

                        // Remove the previous marker if it exists
                        if (previousMarker) {
                            previousMarker.setMap(null);
                        }

                        previousMarker = marker; // Update the reference to the previous marker
                        currentIndex++; // Move to the next data point

                        // Update the map's center to the position of the latest marker
                        map2.setCenter(marker.getPosition());

                        // Update the route coordinates
                        routeCoordinates.push(marker.getPosition());

                        // Update the route line
                        if (routeLine) {
                            routeLine.setPath(routeCoordinates);
                        } else {
                            // Create the route line if it doesn't exist
                            routeLine = new google.maps.Polyline({
                                path: routeCoordinates,
                                geodesic: true,
                                strokeColor: '#0000FF',
                                strokeOpacity: 1.0,
                                strokeWeight: 2,
                            });
                            routeLine.setMap(map2);
                        }

                        // Set a timer to add the next marker
                        setTimeout(addMarker, markerInterval);
                    }else {
                        // All markers have been added, enable the filter button
                        filterButton.classList.remove("disabled-button");
                        tabButton.classList.remove("disabled-button");
                    }
                }

                // Start adding markers
                addMarker();

                // Function to calculate the nearest available direction
                function calculateNearestDirection(newDirection) {
                    const availableDirections = [
                        0, 11.25, 22.5, 33.75, 45, 56.25, 67.5, 78.75, 90, 101.25, 112.25, 123.75,
                        135, 146.25, 157.5, 168.75, 180, 191.25, 202.5, 213.75, 225, 236.25, 247.5, 258.75,
                        270, 281.25, 292.5, 303.75, 315, 326.25, 337.5, 348.75
                    ];
                    let nearestDirection = availableDirections[0];
                    for (const dir of availableDirections) {
                        if (Math.abs(newDirection - dir) < Math.abs(newDirection - nearestDirection)) {
                            nearestDirection = dir;
                        }
                    }
                    return nearestDirection;
                }
            }

            function clearMapData() {
                // Clear existing markers
                markers.forEach(function (marker) {
                    marker.setMap(null);
                });
                markers = [];

                // Clear the route line
                if (routeLine) {
                    routeLine.setMap(null);
                }
                routeCoordinates = [];

                // Reinitialize currentIndex and previousMarker
                currentIndex = 0;
                previousMarker = null;    
                routeLine = null; // Reference to the route line
            }

        </script>

        <script>
            // Function to set the maximum value for the "End Time" input
            function setMaxEndTime() {
                const now = new Date();
                const year = now.getFullYear();
                const month = (now.getMonth() + 1).toString().padStart(2, "0");
                const day = now.getDate().toString().padStart(2, "0");
                const hours = now.getHours().toString().padStart(2, "0");
                const minutes = now.getMinutes().toString().padStart(2, "0");
                const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                
                // Set the maximum attribute of the "End Time" input to the current date and time
                document.getElementById("start-time").setAttribute("max", currentDateTime);
                document.getElementById("end-time").setAttribute("max", currentDateTime);
            }

            // Call the function to set the maximum "End Time" value when the page loads
            setMaxEndTime();
        </script>
    </body>

</html>