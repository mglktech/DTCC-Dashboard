<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Downtown Cab Co. Dashboard</title>
    <link rel="stylesheet" href="/include/components/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Allerta">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Archivo+Black">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Charmonman:400,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Indie+Flower">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
    <link rel="stylesheet" href="/include/components/assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="/include/components/assets/css/styles.css">
    <script src="https://kit.fontawesome.com/f98d92a3e6.js" crossorigin="anonymous"></script>


</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md navigation-clean navbar-dark" style="background: linear-gradient(rgb(34,76,123) 0%, rgba(255,255,255,0));">
        <div class="container"><a class="navbar-brand text-center" href="/home.php" style="font-family: 'Indie Flower', cursive;color: rgb(217,217,217);"><img class="d-md-flex" src="/include/components/assets/img/logo.png" style="margin-right: 8px;height: 49px;">Dashboard</a><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1" style="border-style: none;"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navcol-1">
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item"><a class="nav-link" href="/home.php" style="color: rgb(217,217,217);"><i class="icon ion-ios-home-outline" style="margin-right: 4px;"></i>Home</a></li>
                    <li class="nav-item dropdown"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: rgb(217,217,217);"><i class="icon ion-clipboard" style="margin-right: 4px;"></i>Applications</a>
                        <div class="dropdown-menu"><a class="dropdown-item" href="/applications/table_apps.php">Unread</a><a class="dropdown-item" href="/applications/table_apps_archive.php">Archive</a></div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/players/rostor.php" style="color: rgb(217,217,217);"><i class="icon ion-ios-person-outline" style="margin-right: 4px;"></i>Roster</a></li>
                    <li class="nav-item dropdown">
                        <a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="#" style="color: rgb(217,217,217);">
                            <i class="icon ion-ios-folder-outline" style="margin-right: 4px;"></i>Training</a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="/tests/table_needs_theory.php">Theory</a>
                            <a class="dropdown-item" href="/tests/table_needs_practical.php">Practical</a>
                            <a class="dropdown-item" href="/training/day_one/table_day_one.php">Day One</a>
                            <a class="dropdown-item" href="/tests/table_tests_archive.php">Archive</a>
                        </div>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="/shifts/shifts_index.php" style="color: rgb(217,217,217);"><i class="far fa-clock" style="margin-right: 4px;"></i>Shifts</a></li>
                    <li class="nav-item"><a class="nav-link" href="/logout.php" style="color: rgb(217,217,217);">LogOut</a></li>
                </ul>
            </div>
        </div>
    </nav>