<nav class="col-md-2 d-md-block bg-light sidebar collapse navbar-collapse" id="bs-example-navbar-collapse-1">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <!-- https://feathericons.com/ - Use the name of the icon for data-feather -->
                <a class="nav-link" href="/index.php">
                    <span data-feather="home"></span>
                    Home
                </a>
                <a href="#apps_sub" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <span data-feather="clipboard"></span>
                    Applications
                </a>
                <ul class="collapse nav-link" style="list-style: inside;" id="apps_sub">
                    <li><a href="/applications/table_apps.php">Unread</a></li>
                    <li><a href="/applications/table_apps_archive.php">Archive</a></li>
                </ul>
                <a class="nav-link" href="/players.php">
                    <span data-feather="user"></span>
                    Roster <span class="sr-only"></span>
                </a>
                <a href="#tests_sub" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle nav-link">
                    <span data-feather="file-text"></span>
                    Tests
                </a>
                <ul class="collapse nav-link" style="list-style: inside;" id="tests_sub">
                    <li><a href="/tests/table_needs_theory.php">Theory</a></li>
                    <li><a href="/tests/table_needs_practical.php">Practical</a></li>
                    <li><a href="/tests/table_tests_archive.php">Tests Archive</a></li>
                </ul>
                <a class="nav-link" href="/shifts/shifts_index.php">
                    <span data-feather="clock"></span>
                    Shifts <span class="sr-only"></span>
                </a>

            </li>
        </ul>
    </div>
</nav>