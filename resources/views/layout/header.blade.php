<head>
        
    <meta charset="utf-8" />
    <title>Dashboard | YTA Admin & Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="YTA Admin & Dashboard Page" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('/assets/images/favicon.ico')}}">

    <!-- Bootstrap Css -->
    <link href="{{asset('/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{asset('/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{asset('/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    <link href="{{asset('/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>

</head>

<body data-sidebar="dark">

<!-- <body data-layout="horizontal" data-topbar="dark"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        
        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="/" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="{{asset('/assets/images/logo.svg')}}" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('/assets/images/logo-dark.png')}}" alt="" height="17">
                            </span>
                        </a>

                        <a href="/" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="{{asset('/assets/images/logo-light.svg')}}" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{asset('/assets/images/logo.png')}}" alt="" style="height: 110px; margin-top:90px">
                            </span>
                        </a>
                    </div>

                    <!-- <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn" style="margin-top: 65px; display: flex; align-items: center;">
                        <i class="fa fa-fw fa-bars"></i>
                    </button> -->

                    <!-- App Search-->
                    <!-- <form class="app-search d-none d-lg-block" style="margin-top: 65px">
                        <div class="position-relative">
                            <input type="text" class="form-control" placeholder="Search...">
                            <span class="bx bx-search-alt"></span>
                        </div>
                    </form> -->

                    <div class="dropdown dropdown-mega d-none d-lg-block ms-2" style="margin-top: 65px">
                        <!-- <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                            <span key="t-megamenu">Mega Menu</span>
                            <i class="mdi mdi-chevron-down"></i> 
                        </button> -->
                        <div class="dropdown-menu dropdown-megamenu">
                            <div class="row">
                                <div class="col-sm-8">

                                    <div class="row">
                                        <div class="col-md-4">
                                            <h5 class="font-size-14" key="t-ui-components">UI Components</h5>
                                            <ul class="list-unstyled megamenu-list">
                                                <li>
                                                    <a href="javascript:void(0);" key="t-lightbox">Lightbox</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-range-slider">Range Slider</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-sweet-alert">Sweet Alert</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-rating">Rating</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-forms">Forms</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-tables">Tables</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-charts">Charts</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-md-4">
                                            <h5 class="font-size-14" key="t-applications">Applications</h5>
                                            <ul class="list-unstyled megamenu-list">
                                                <li>
                                                    <a href="javascript:void(0);" key="t-ecommerce">Ecommerce</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-calendar">Calendar</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-email">Email</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-projects">Projects</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-tasks">Tasks</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-contacts">Contacts</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-md-4">
                                            <h5 class="font-size-14" key="t-extra-pages">Extra Pages</h5>
                                            <ul class="list-unstyled megamenu-list">
                                                <li>
                                                    <a href="javascript:void(0);" key="t-light-sidebar">Light Sidebar</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-compact-sidebar">Compact Sidebar</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-horizontal">Horizontal layout</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-maintenance">Maintenance</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-coming-soon">Coming Soon</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-timeline">Timeline</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-faqs">FAQs</a>
                                                </li>
                        
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h5 class="font-size-14" key="t-ui-components">UI Components</h5>
                                            <ul class="list-unstyled megamenu-list">
                                                <li>
                                                    <a href="javascript:void(0);" key="t-lightbox">Lightbox</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-range-slider">Range Slider</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-sweet-alert">Sweet Alert</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-rating">Rating</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-forms">Forms</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-tables">Tables</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" key="t-charts">Charts</a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-sm-5">
                                            <div>
                                                <img src="{{asset('/assets/images/megamenu-img.png')}}" alt="" class="img-fluid mx-auto d-block">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="d-flex">

                    <!-- <div class="dropdown d-inline-block d-lg-none ms-2">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="mdi mdi-magnify"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-search-dropdown">
    
                            <form class="p-3">
                                <div class="form-group m-0">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> -->


                    <!-- <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                            <i class="bx bx-fullscreen"></i>
                        </button>
                    </div> -->

                    <!-- <a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">Logout</span></a> -->

                    <!-- <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center;">
                            <i class="bx bx-bell bx-tada"></i>
                            <span class="badge bg-danger rounded-pill">3</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0" key="t-notifications"> Notifications </h6>
                                    </div>
                                    <div class="col-auto">
                                        <a href="index.html#!" class="small" key="t-view-all"> View All</a>
                                    </div>
                                </div>
                            </div>
                            <div data-simplebar style="max-height: 230px;">
                                <a href="javascript: void(0);" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                <i class="bx bx-cart"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1" key="t-your-order">Your order is placed</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1" key="t-grammer">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">3 min ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript: void(0);" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <img src="{{asset('/assets/images/users/avatar-3.jpg')}}"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">James Lemire</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1" key="t-simplified">It will seem like simplified English.</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-hours-ago">1 hours ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <a href="javascript: void(0);" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                                <i class="bx bx-badge-check"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1" key="t-shipped">Your item is shipped</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1" key="t-grammer">If several languages coalesce the grammar</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-min-ago">3 min ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <a href="javascript: void(0);" class="text-reset notification-item">
                                    <div class="d-flex">
                                        <img src="{{asset('/assets/images/users/avatar-4.jpg')}}"
                                            class="me-3 rounded-circle avatar-xs" alt="user-pic">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Salena Layfield</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1" key="t-occidental">As a skeptical Cambridge friend of mine occidental.</p>
                                                <p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span key="t-hours-ago">1 hours ago</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2 border-top d-grid">
                                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                    <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View More..</span> 
                                </a>
                            </div>
                        </div>
                    </div> -->

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-notifications-dropdown"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="display: flex; align-items: center;">
                            <i class="bx bx-bell bx-tada"></i>
                            <span class="badge bg-danger rounded-pill" id="notification-count"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                            aria-labelledby="page-header-notifications-dropdown">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0" key="t-notifications"> Notifications </h6>
                                    </div>
                                    <!-- <div class="col-auto">
                                        <a href="index.html#!" class="small" key="t-view-all"> View All</a>
                                    </div> -->
                                </div>
                            </div>
                            <div data-simplebar style="max-height: 230px;">
                                <div id="notification-list"></div>
                            </div>
                            <!-- <div class="p-2 border-top d-grid">
                                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                                    <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View More..</span>
                                </a>
                            </div> -->
                        </div>
                    </div>



                    <div class="dropdown d-inline-block">
                        <a href="{{ route('settings') }}" class="btn header-item noti-icon waves-effect" style="display: flex; align-items: center;">
                            <i class="bx bx-cog bx-spin"></i>
                        </a>
                    </div>

                    <div class="dropdown d-inline-block">
                        <a href="{{ route('logout') }}" class="btn header-item noti-icon waves-effect" style="display: flex; align-items: center;">
                            <i class="bx bx-log-out text-danger"></i>
                        </a>
                    </div>

                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu" style="margin-top: 40px">
                        <li class="menu-title" key="t-menu">Menu</li>

                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                {{-- <i class='bx bx-user-circle'></i><span class="badge rounded-pill bg-info float-end">01</span> --}}
                                <span key="t-dashboards">Admin</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <!-- <li><a href="index.html" key="t-default">Dashboard</a></li> -->
                                <li><a href="/admins" key="t-saas">Add Admin</a></li>
                                <!-- <li><a href="dashboard-crypto.html" key="t-crypto">View Admins</a></li> -->
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                {{-- <i class='bx bx-user-circle'></i><span class="badge rounded-pill bg-info float-end">01</span> --}}
                                <span key="t-dashboards">Drivers</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <!-- <li><a href="/driverlists" key="t-default">Dashboard</a></li> -->
                                <li><a href="/driverlists" key="t-crypto">View Drivers</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                {{-- <i class='bx bx-user-circle'></i><span class="badge rounded-pill bg-info float-end">01</span> --}}
                                <span key="t-dashboards">Customers</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <!-- <li><a href="index.html" key="t-default">Dashboard</a></li> -->
                                <li><a href="/customers" key="t-crypto">View Customers</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                {{-- <i class='bx bx-package' ></i><span class="badge rounded-pill bg-info float-end">02</span> --}}
                                <span key="t-dashboards">Shipments</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <!-- <li><a href="index.html" key="t-default">Dashboard</a></li> -->
                                <li><a href="/createShipment" key="t-saas">Add Shipment</a></li>
                                <li><a href="/shipments" key="t-crypto">View Shipments</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                {{-- <i class='bx bx-package' ></i><span class="badge rounded-pill bg-info float-end">02</span> --}}
                                <span key="t-dashboards">Vehicles</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <!-- <li><a href="index.html" key="t-default">Dashboard</a></li> -->
                                <li><a href="/createVehicle" key="t-saas">Add Vehicle</a></li>
                                <li><a href="/vehicles" key="t-crypto">View Vehicles</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="javascript: void(0);" class="waves-effect">
                                {{-- <i class='bx bx-package' ></i><span class="badge rounded-pill bg-info float-end">02</span> --}}
                                <span key="t-dashboards">Reports</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <!-- <li><a href="index.html" key="t-default">Dashboard</a></li> -->
                                <li><a href="/newReport" key="t-saas">Add Report</a></li>
                                <li><a href="/reports" key="t-crypto">View Reports</a></li>
                            </ul>
                        </li>

                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->



        <!-- Add this script at the end of your header.blade.php file -->
        <script>
// Function to display notifications in the dropdown
function displayNotificationInDropdown(notification) {
    var notificationList = $("#notification-list");

    // Create a new notification item
    var notificationItem = '<a href="javascript:void(0);" class="text-reset notification-item">' +
        '<div class="d-flex">' +
        '<div class="avatar-xs me-3">' +
        '<span class="avatar-title bg-primary rounded-circle font-size-16">' +
        '<i class="bx bx-cart"></i>' +
        '</span>' +
        '</div>' +
        '<div class="flex-grow-1">' +
        '<h6 class="mb-1">' + notification + '</h6>' +
        '<div class="font-size-12 text-muted">' +
        '<p class="mb-1">If several languages coalesce the grammar</p>' +
        '<p class="mb-0"><i class="mdi mdi-clock-outline"></i> <span>Just now</span></p>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</a>';

    // Append the notification item to the notification list
    notificationList.prepend(notificationItem);

    // Update the notification count
    var notificationCount = notifications.length;
    $("#notification-count").text(notificationCount);
}
</script>

