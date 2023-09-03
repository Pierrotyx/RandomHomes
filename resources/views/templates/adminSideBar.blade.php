<div class="sidebar" data-color="danger" data-background-color="black" data-image="assets/img/sidebar-2.jpg">
      <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
      <div class="logo">
        <a href="/companies.php" class="simple-text logo-normal">
          Fantasy Stocks
        </a>
        <a href="/icons.php" class="simple-text logo-normal">
          Is That Weird?
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item {{ ( $name == 'Dashboard' ) ? 'active' : ''}}">
            <a class="nav-link" href="/dashboard.php">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item  {{ ( $name == 'User Profile' ) ? 'active' : ''}}">
            <a class="nav-link" href="/user.php">
              <i class="material-icons">person</i>
              <p>User Profile</p>
            </a>
          </li>
          <li class="nav-item  {{ ( $name == 'Companies' ) ? 'active' : ''}}">
            <a class="nav-link" href="/companies.php">
              <i class="material-icons">content_paste</i>
              <p>Comapanies</p>
            </a>
          </li>
          <li class="nav-item  {{ ( $name == 'Typography' ) ? 'active' : ''}}">
            <a class="nav-link" href="/typography.php">
              <i class="material-icons">library_books</i>
              <p>Typography</p>
            </a>
          </li>
          <li class="nav-item  {{ ( $name == 'Icons' ) ? 'active' : ''}}">
            <a class="nav-link" href="/icons.php">
              <i class="material-icons">bubble_chart</i>
              <p>Icons</p>
            </a>
          </li>
          <li class="nav-item  {{ ( $name == 'Map' ) ? 'active' : ''}}">
            <a class="nav-link" href="/map.php">
              <i class="material-icons">location_ons</i>
              <p>Maps</p>
            </a>
          </li>
          <li class="nav-item  {{ ( $name == 'Notifications' ) ? 'active' : ''}}">
            <a class="nav-link" href="/notifications.php">
              <i class="material-icons">notifications</i>
              <p>Notifications</p>
            </a>
          </li>
        </ul>
      </div>
    </div>