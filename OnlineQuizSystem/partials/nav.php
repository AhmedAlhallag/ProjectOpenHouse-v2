<?php 

include('class/Examination.php');

$examObj = new Examination();

$current_id = $current_email = $current_username = $type = $current_timezone =  "" ;




if (isset($_SESSION['type'])) {
  $type = $_SESSION['type'];
    if ($type == "admin") {
        $current_id = $_SESSION['admin_id'];
        $current_email = $_SESSION['admin_email_address'];
        $current_timezone =  $_SESSION['timezone'] ; 
      }
  else if ($type == "user") {
      $current_id = $_SESSION['user_id'];
      $current_email = $_SESSION['user_email_address'];
      $current_username =  $_SESSION['username'];
      $current_timezone =  $_SESSION['timezone'] ; 


  }
}
// so that time() would work proberly 
date_default_timezone_set($current_timezone);

// echo "TIMEZONE: "  . $current_timezone;

// echo "Email: $current_email<br>";
// echo "Type: $type<br>"
//     $current_id = $current_email = "" ;
//   }
// }

// echo $current_id;
// echo $_SESSION['admin_id'];

?>


<?php
if (empty($current_id)){ ?>
      <script src="js/removeCookie.js" defer>  </script>
<?php } ?>

<div class="navbar-fixed">
  <nav class="#ff6d00 orange accent-4">
    <div class="container">
      <div class="nav-wrapper">
        <a href="index.php" style='font-size:13px' class="brand-logo heading">Project: OpenHouse v1.3.1</a>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="material-icons">menu</i></a>
         <ul class="right hide-on-med-and-down">

              <li>
                  <a href="index.php">Home</a>
              </li>
              <li>
                  <a href="https://tkh.edu.eg">TKH Webite</a>
              </li>
              <?php if ($type == "admin"){ ?>
                <li>
                    <a href="exam.php">Exam</a>
                </li>
                <li>
                    <a href="display_users.php">Users List</a>
                </li>

              <?php } else if ($type == "user"){?>
                <li>
                    <a href="activity.php">Activity</a>
                </li>
              <?php } else {?>
                <li>
                    <a href="explore.php">Explore</a>
                </li>

              <?php }?>


              
              <?php if ($current_id){ ?>

              <li>
                  Logged in as: (<b><?php echo $current_email ?></b>)
              </li>
                <li>
                  <a href="logout.php">Logout</a>
              </li>
                <?php } else {?>

                <li>
                  <a href="user_register.php">Sign-Up/Login</a>
              </li>
              <?php }?>

        </ul>
      </div>
    </div>
  </nav>
</div>
<ul class="sidenav" id="mobile-demo">
  <li>
      <a href="index.php">Home</a>
  </li>
  <li>
      <a href="https://tkh.edu.eg">TKH Webite</a>
  </li>
                <?php if ($type == "admin"){ ?>
                <li>
                    <a href="exam.php">Exam</a>
                </li>
                <!-- <li>
                    <a href="display_users.php">Users List</a>
                </li> -->
                <li>
                    <a href="display_users.php">Users List</a>
                </li>
                <!-- </li> -->
              <?php } else if ($type == "user"){?>
                <li>
                    <a href="activity.php">Activity</a>
                </li>
              <?php } else {?>
                <li>
                    <a href="explore.php">Explore</a>
                </li>

              <?php }?>

  <?php if ($current_id){ ?>
            <li class="logged_as">
                  Logged in as: (<b><?php echo $current_email ?></b>)
              </li>
              <li>
                  <a href="logout.php">Logout</a>
              </li>
                <?php } else {?>
                <li>
                  <a href="user_register.php">Sign-Up/Login</a>
              </li>
              <?php }?>


</ul>
