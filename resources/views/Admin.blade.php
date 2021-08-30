<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Tabbed IFrames</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{asset('fontend/css/fontawesome.css')}}">

    <link href="{{asset('fontend/css/all.min.css')}}" rel="stylesheet">

    <!-- Theme style -->
    <link href="{{asset('fontend/css/adminlte.min.css')}}" rel="stylesheet">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('fontend/css/OverlayScrollbars.min.css')}}">
</head>
<body class="hold-transition sidebar-mini layout-fixed"
      data-panel-auto-height-mode="height" style="overflow-y: hidden">
<div class="wrapper">
    <nav
        class="main-header navbar navbar-expand navbar-white navbar-light"
        style="height: 40px">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu"
                                    href="#" role="button"><i class="fas fa-bars"></i></a></li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link"> <img  src=""
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Admin</span>
        </a>
        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img
                        src=""
                    class="img-circle elevation-2">
                </div>
                <div class="info">
                    <a href="#" class="d-block"> Tín
                    </a>
                </div>
            </div>
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column"
                    data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item"><a href="{{URL::to('/mockups')}}" class="nav-link" role="button" aria-pressed="true"><i class="nav-icon fas fa-th"></i><p>Mockup</p></a></li>
                    <li class="nav-item"><a href="{{URL::to('/mockup-types')}}" class="nav-link" role="button" aria-pressed="true"><i class="nav-icon fas fa-th"></i><p>Mockup Type</p></a></li>
                    <li class="nav-item"><a href="{{URL::to('/orders')}}" class="nav-link" role="button" aria-pressed="true"><i class="nav-icon fas fa-th"></i><p>Order</p></a></li>

                </ul>
            </nav>
        </div>
    </aside>
    <div class="content-wrapper iframe-mode" data-widget="iframe"
         data-loading-screen="750">
        <div
            class="nav navbar navbar-expand navbar-white navbar-light border-bottom p-0">
            <div class="nav-item dropdown">
                <a class="nav-link bg-danger dropdown-toggle"
                   data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
                   aria-expanded="false">Close</a>
                <div class="dropdown-menu mt-0">
                    <a class="dropdown-item" href="#" data-widget="iframe-close"
                       data-type="all">Close All</a> <a class="dropdown-item" href="#"
                                                        data-widget="iframe-close" data-type="all-other">Close All
                        Other</a>
                </div>
            </div>
            <ul class="navbar-nav overflow-hidden" role="tablist"></ul>
        </div>
        <div class="tab-content">
            <div class="tab-empty">
                <h2 class="display-4">No tab selected!</h2>
            </div>

        </div>
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
</body>
<!-- jQuery -->
<script src="{{asset('fontend/js/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('fontend/js/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Bootstrap 4 -->
<script src="{{asset('fontend/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('fontend/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('fontend/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('fontend/js/demo.js')}}"></script>
</html>
