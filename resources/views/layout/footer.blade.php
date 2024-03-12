<footer class="footer">
<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>document.write(new Date().getFullYear())</script> © Yes GPS Tracker.
            </div>
        <div class="col-sm-6">
        <div class="text-sm-end d-none d-sm-block">
            Design & Develop by Softwell Sdn.Bhd
        </div>
    </div>
</div>
</div>
</footer>

<script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
$(document).ready(function () {
    var pusher = new Pusher('bf1aebb70399463c5d8a', {
        cluster: 'ap1'
    });

    var notificationChannel = pusher.subscribe('pusher-notification');

    function saveAndFetchNotifications(message, eventId, redirectPath) {
        // Save the notification to the database
        saveNotificationToDatabase(message, eventId, redirectPath);

        // Fetch and display the updated list of notifications
        fetchAndDisplayNotifications();
    }

    // function saveNotificationToDatabase(message, eventId, redirectPath) {
    //     $.ajax({
    //         url: '/save-notification',
    //         method: 'POST',
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         },
    //         data: {
    //             message: message,
    //             eventId: eventId,
    //             redirectPath: redirectPath,
    //         },
    //         success: function (response) {
    //             console.log('Notification saved to database:', response);
    //         },
    //         error: function (error) {
    //             console.error('Error saving notification to database:', error);
    //         }
    //     });
    // }

    function fetchAndDisplayNotifications() {
    // Fetch notifications from the server
    $.ajax({
        url: '/get-notifications',
        method: 'GET',
        success: function (notifications) {
            // Filter notifications with viewedStatus equal to 1
            var unviewedNotifications = notifications.filter(function (notification) {
                return notification.viewedStatus === 1;
            });

            // Update the notification count and hide it if count is 0
            var notificationCount = unviewedNotifications.length;
            $("#notification-count").text(notificationCount);
            $("#notification-count").toggle(notificationCount > 0);

            // Update the notification list
            updateNotificationList(notifications);
        },
        error: function (error) {
            console.error('Error fetching notifications:', error);
        }
    });
}

$(document).on('click', '#page-header-notifications-dropdown', function () {
    markNotificationsAsViewed();
    
    // Hide the notification count
    $("#notification-count").hide();
});

    function markNotificationsAsViewed() {
        $.ajax({
            url: '/mark-notifications-as-viewed',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                console.log('Notifications marked as viewed:', response.message);
                // Optionally, you can update the UI or perform additional actions
            },
            error: function (error) {
                console.error('Error marking notifications as viewed:', error);
            }
        });
    }

    function updateNotificationList(notifications) {
    var notificationList = $("#notification-list");

    // Clear existing notifications
    notificationList.empty();

    // Append each notification to the list
    notifications.forEach(function (notification) {
        // Format the time difference
        var timeAgo = TimeDifference(notification.created_at);

        // Retrieve the eventId from data
        var eventId = notification.eventId;

        // Determine the notification style based on the eventId
        var icon;
        var alertTitle;
        switch (eventId) {
            case 1:
                icon = '<box-icon name="truck" type="solid" color="#ffffff"></box-icon>';
                alertTitle = 'Delivery Status Alert';
                break;
            case 2:
                icon = '<i class="bx bx-target-lock"></i>';
                alertTitle = 'Geofence Alert';
                break;
            case 3:
                icon = '<box-icon name="tachometer" color="#ffffff"></box-icon>';
                alertTitle = 'Overspeeding Alert';
                break;
            default:
                icon = ''; // Handle other cases or provide a default icon
        } 
        var notificationItem = '<a href="' + notification.redirectPath + '" class="text-reset notification-item">' +
        '<div class="d-flex">' +
        '<div class="avatar-xs me-3">' +
        '<span class="avatar-title rounded-circle font-size-16" style="width: 30px; height: 30px; background-color: #314594;">'+
        icon +
        '</span>' +
        '</div>' +
        '<div class="flex-grow-1">' +
        '<h6 class="mb-1">' + alertTitle + '</h6>' +
        '<div class="font-size-12 text-muted">' +
        '<p class="mb-1">' + notification.message + '</p>' +
        '<p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>' + timeAgo + '</span></p>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</a>';
        notificationList.append(notificationItem);
    });
}


    notificationChannel.bind('deliveryStatus', function (data) {
        toastr.info(data.message);
        saveAndFetchNotifications(data.message, 1, data.redirectPath);
    });

    notificationChannel.bind('geofenceAlert', function (data) {
        toastr.info(data.message);
        // saveAndFetchNotifications(data.message, 2, data.redirectPath);
    });

    notificationChannel.bind('speedAlert', function (data) {
        toastr.warning(data.message);
        // saveAndFetchNotifications(data.message, 3, data.redirectPath);
    });

    // Fetch and display notifications when the page loads
    fetchAndDisplayNotifications();
});

// Function to calculate time difference for notification
function TimeDifference(givenDateTime) {
    var currentDate = new Date();
    var givenDate = new Date(givenDateTime);
    var timeDifference = Math.abs(currentDate - givenDate) / 1000; // in seconds

    if (timeDifference < 60) {
        // Less than 1 minute
        return Math.floor(timeDifference) + "s ago";
    } else if (timeDifference < 3600) {
        // Less than 1 hour
        return Math.floor(timeDifference / 60) + "min ago";
    } else if (timeDifference < 86400) {
        // Less than 1 day
        return Math.floor(timeDifference / 3600) + "h ago";
    } else {
        // More than 1 day
        return Math.floor(timeDifference / 86400) + "d ago";
    }
}
</script>







<!-- <script>

var pusher = new Pusher('9e6ca00123db2a01c7da', {
      cluster: 'ap1'
});
// Subscribe to the second channel and bind the second event
var geofenceChannel = pusher.subscribe('pusher-geofenceAlert');
geofenceChannel.bind('geofence-alert', function (data) {
    alert(JSON.stringify(data.message));
    // toastr.success(JSON.stringify(data.fenceAlert));
});
</script>
<script>
    var pusher = new Pusher('19d862060fa8ad877f39', {
      cluster: 'ap1'
    });

    var speedChannel = pusher.subscribe('pusher-speedAlert');
    speedChannel.bind('speed-alert', function(data) {
      alert(JSON.stringify(data.message));
    });
</script> -->