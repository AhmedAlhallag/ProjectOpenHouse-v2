<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/navStyle.css">
    <!-- <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css"> -->
<title>Document</title>
    <style>

    .header { 
        justify-content:left;

    }
    .dropdown-menu {
        max-height: 100px;
        overflow: scroll;
    }
    .badges{
        position: relative;
        top: 11px;
    } 

    /* custom Modal box css  */
    .readonly-class{
         background-color: rgba(224, 224, 224, 0.49)
       }
    .box {
        background-color: rgba(78, 78, 78, 0.4);
        position: fixed;
        overflow: hidden;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        z-index: 1
    }
    .content{
        margin: 11% auto;
        padding: 20px

    }
    .box .content p{
        font-size: 60px;
        font-family: "Lato";
        text-align: center;
        font-weight: 600 ;
        color:#fff;
        width: 80%;
        padding-left: 10%
    }
    .close-icon{
        color: #fff;
        font-size: 40px;
    }

    .box .content .close{
        float: right;
        padding-right: 5%
    }
    .blur {
        filter: blur(3px) !important;
    }
    .box .content .close:hover {
        cursor: pointer;
    }
    .displayBlock{
        display: block;
    }
    .datepicker-calendar-container {
        transform: scale(0.8);
    }

    .titlee{
        padding-left:20px !important
    }
    .not-started{
        line-height: 1px !important;
    }
    </style>
</head>
<body>
<?php 
    include('partials/nav.php');
    // updates any changes to the exam status in the student view
    $examObj->Check_exam_status();
    $checkEnrolled = new Examination(false);
?>  

<?php  if ($type == "user"){ ?>
    <?php if ( intval($checkEnrolled->If_user_already_enroll_exam_in_any_exam($current_id)) == 1){  ?>
    <div id="container" class="container">
    <div class="row">
        <div class="add-exam">
        <br> <br>
        <h2 class="header">Enrolled Activities </h2>    
			<table id="exam_data_table" class="responsive-table centered">
				<thead>
					<tr>
						<th>Exam Title</th>
						<th>Date & Time</th>    
						<th>Duration</th>
						<th>Total Question</th>
						<th>Right Answer Mark</th>
						<th>Wrong Answer Mark</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>
        </div>
    </div>
                
        <?php } else { ?>
            <div id="container" class="container">
                <br><br>
                <span>You haven't no enrolled activities yet.</span> <br>
                <span><u><a href="activity.php"> You Can Enroll From Here</a></u></span>
            </div>
        <?php } ?>


        

    <?php } else if ($type == 'admin'){ ?>
        <div id="container" class="container">
                <br><br>
                <span>Admins cannot enroll into activities.</span> <br>
            </div>
    <?php } else { ?>
        
        <div id="box" class="box displayNone">
            <div class="content">
                <span id="close" class="close" > <i class="fas fa-times close-icon"></i>  </span>
                <p> You Need to be logged in first!</p>
            </div>
        </div>
        <div id="container" class="container">
       </div>

    <?php } ?>




<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


    <script src="js/modalBox.js">  </script>
      <script src="js/hammenu.js">  </script>
      <!-- <script src="js/user_exam.js"></script> -->

      <script src="js/enroll_exam_ajax.js"> </script>

      <!-- Create a socket to recieve messages here  -->
      <script>






      </script>

      

      </body>
</html>