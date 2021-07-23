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
       /* .datepicker-modal {
     transform: scale(0.9) !important;
        }
        
 */
    .datepicker-calendar-container {
        transform: scale(0.8);
    }

    </style>
</head>
<body>
<?php 
    // include('../partials/nav.php');
    include('partials/nav.php');

    // $examObj->admin_session_public();  
    $code = $_GET['code'] ?? ""; 
    $exam_title="" ; 

    if (!empty($code)){
        $examid = $examObj->Get_exam_id($code);
        $examObj->data = array(
            ":online_exam_id" => $examid  
        );
        $examObj->query = "SELECT * FROM online_exam_table WHERE online_exam_id = :online_exam_id ;";
        $result  = $examObj->query_result();
        $exam_title = $result[0]['online_exam_title'];

    }

?> 

<?php  if ($type != 'admin'){ ?>
       <div id="box" class="box displayNone">
         <div class="content">
           <span id="close" class="close" > <i class="fas fa-times close-icon"></i>  </span>
           <p> You Need to be logged in as an Admin first!</p>
         </div>
       </div>
     <?php } ?>

     <nav>
    <div class="nav-wrapper #ef6c00 orange darken-3">
      <div class="col s12">
        <a href="exam.php" class="breadcrumb">Exam List</a>
        <?php if ($exam_title){ ?>
        <a style="font-weight:bold" class="breadcrumb" disabled> <?php echo $exam_title ?> </a>
        <?php } ?>
        <a aria-current="page" class="breadcrumb">Results</a>
      </div>
    </div>
  </nav>

     <div id="container" class="container">
    <?php if (empty($code)){  ?> 
        <h4>No Exam was selected.</h2>
    <?php } else { ?>




<div class="row">
        <h2 class="header">Resutls</h2>    
			<table id="result_table" class="responsive-table highlight striped centered">
				<thead>
					<tr>
                    <th>User Name</th>
                    <th>Attendance Status</th>
                    <th>Total Mark</th>
					</tr>
				</thead>
			</table>
		</div>
        <?php } ?>

        <form id="getCode" action="">
        <input id="hiddenCode" type="hidden" name="hiddenCode" value="<?php echo $code ?>">
        </form>
    </div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


    <script src="js/modalBox.js">  </script>
      <script src="js/hammenu.js">  </script>
      <script src="js/final_results_ajax.js">  </script>


</body>
</html>