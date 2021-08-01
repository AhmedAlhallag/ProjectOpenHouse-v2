<?php

// using OOP

?> 

<!DOCTYPE html>
<html lang="en">
<head>
        <link rel="icon" href="data:;base64,iVBORw0KGgo=">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <!--Import Google Icon Font-->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Staatliches&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <!-- <script src="../js/parsley.min.js"> </script> -->
    <script src="http://parsleyjs.org/dist/parsley.js"></script>
    <link rel="stylesheet" href="css/admin_signup.css">

</head>
<body>
    <?php 
    // include('../partials/nav.php');
    include('partials/nav.php');
    
    $examObj->admin_session_public();  

    ?>

    
          <div class="fixed-action-btn click-to-toggle">
            <a class="btn-floating btn-large red">
              <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
              <li><a class="btn-floating red circ" href="user_login.php">Login</a></li>
              <li><a class="btn-floating green circ" href="https://tkh.edu.eg">TKH</a></li>
              <li><a class="btn-floating blue circ" href="index.php">Home</a></li>
            </ul>
          </div>

    <div class="container">
    <h5>Admin Login</h5>
    <br><br>

    <!-- Add nav here -->

    <!-- =================== Body ====================== -->
    <!-- action not needed if sending is via ajax???? -->
    <form id="admin_login_form" method="post" action="">

    <!-- <input type="text" name="admin_email" id="admin_email"  data-parsley-checkemail data-parsley-checkemail-message="Email Already exists"> -->
    <label for="admin_email"> Email: </label>
    <input type="text" name="admin_email" id="admin_email" class=""  >
    <div> 
    <p style="color:grey"></p>
    </div>

    <label for="admin_pass"> Password: </label>
    <input type="password" name="admin_pass" id="admin_pass" >
    <div class="pass"> 
    <p style="color:red"></p>
    </div>


    <!-- these hidden will be used in ajax -->
    <input type="hidden" name="action" value="login" >
    <br><br>

    
    <input class="btn teal"type="submit" id="admin_login" name="admin_login" value="Login">

    <span style="margin-left: 20px; margin-right: 20px">OR</span>
    <a class='btn red' href="register.php">Register</a>
    
</form>



    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script src="js/login_ajax.js"></script>
    <script src="js/hammenu.js"></script>
          <script src="js/clickFAB.js" type="text/javascript">      </script>

    <script src="js/validate_admin_secret.js"></script>


</body>
</html>




