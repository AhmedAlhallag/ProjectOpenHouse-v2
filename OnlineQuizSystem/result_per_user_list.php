<!DOCTYPE html>
<html lang="en">
<head>
        <link rel="icon" href="data:;base64,iVBORw0KGgo=">

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

    $code = $_GET['code'] ?? ""; 
    $exam_title="" ; 

    if (!empty($code)){
        $exam_id = $examObj->Get_exam_id($code);
        $examObj->data = array(
            ":online_exam_id" => $exam_id  
        );
        $examObj->query = "SELECT * FROM online_exam_table WHERE online_exam_id = :online_exam_id ;";
        $result  = $examObj->query_result();

        $exam_title = $result[0]['online_exam_title'];

        $examObj->data = array(
            ":exam_id" => $exam_id,
            ":user_id" => $_GET['id']

        );

        $examObj->query = "
	    SELECT * FROM question_table 
	    INNER JOIN user_exam_question_answer 
	    ON user_exam_question_answer.question_id = question_table.question_id 
	    WHERE question_table.online_exam_id = :exam_id 
	    AND user_exam_question_answer.user_id = :user_id
        ";

        $result = $examObj->query_result();

    }

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

<?php  if ($type != 'admin'){ ?>
       <div id="box" class="box displayNone">
         <div class="content">
           <span id="close" class="close" > <i class="fas fa-times close-icon"></i>  </span>
           <p> You Need to be logged in as an Admin first!</p>
         </div>
       </div>
     <?php } ?>

    <div id="container" class="container">
<?php if (empty($code)){  ?> 
    <h4>No Exam was selected.</h2>
<?php } else { ?>

        <div class="card">
		<div class="card-title">Online Exam Result</div>
		<div class="card-content">
			<div>
				<table class="responsive-table highlight striped centered">
					<tr>
						<th>Question</th>
						<th>Op 1</th>
						<th>Op 2</th>
						<th>Op 3</th>
						<th>Op 4</th>
						<th>Student Answer</th>
						<th>Answer</th>
						<th>Result</th>
						<th>Marks</th>
					</tr>
					<?php
					$total_mark = 0;

					foreach($result as $row)
					{
                        $examObj->data = array(
                            ":question_id" => $row["question_id"]
                        );
						$examObj->query = "
						SELECT * 
						FROM option_table 
						WHERE question_id = :question_id
						";

						$sub_result = $examObj->query_result();

						$user_answer = '';
						$orignal_answer = '';
						$question_result = '';

						if($row["marks"] == '0')
						{
							$question_result = '<h4  style="font-size:12px; padding-bottom:5px; padding-top:3px; position:relative;top:-3px;color:white" class="#f44336 red-text"><i class="fas fa-times-circle"></i></h4>';

							// $question_result = '<span class="new badge badges">Not Attend</span>';
						}

						if($row["marks"] > '0')
						{
							// $question_result = '<span class="new badge badges">Right</span>';
							$question_result = '<h4 style="font-size:12px; padding-bottom:5px; padding-top:3px; position:relative;top:-3px; color:white"  class="#00e676 green-text text-accent-3"><i class="fas fa-check-circle"></i></h4>';

						}

						if($row["marks"] < '0')
						{
							$question_result = '<span class="new badge badges">Wrong</span>';
						}

						echo '
						<tr>
							<td>'.$row["question_title"].'</td>
						';

						foreach($sub_result as $sub_row)
						{
							echo '<td>'.$sub_row["option_title"].'</td>';

							if($sub_row["option_number"] == $row["user_answer_option"])
							{
								$user_answer = $sub_row["option_title"];
							}

							if($sub_row["option_number"] == $row["answer_option"])
							{
								$orignal_answer = $sub_row["option_title"];
							}
						}

						echo '
						<td>'.$user_answer.'</td>
						<td>'.$orignal_answer.'</td>
						<td>'.$question_result.'</td>
						<td>'.$row["marks"].'</td>
					</tr>
						';
						
					}

                    $examObj->data = array(
                        ":user_id" => $_GET['id'],
                        ":exam_id" => $exam_id
                    );

					$examObj->query = "
					SELECT SUM(marks) as total_mark FROM user_exam_question_answer 
					WHERE user_id = :user_id 
					AND exam_id = :exam_id
					";

					$marks_result = $examObj->query_result();

                    // get exam total marks        
                    $examObj->data = array(
                        ':exam_id'		=>	$exam_id
                    );

                    $examObj->query = "
                    SELECT exam_total_mark FROM online_exam_table 
                    WHERE online_exam_id = :exam_id
                    ";

                    $res = $examObj->query_result();

					foreach($marks_result as $row)
					{
						echo '
						<tr>
                        <td colspan="8"><b>Total Mark:</b></td>
						<td><mark style="color:red"> <b>'. $row["total_mark"].'</b> </mark style="color:red"> /' . $res[0]["exam_total_mark"] . '</td>
						</tr>
						';
					}
					?>
				</table>
			</div>
		</div>
	</div>    

<?php } ?>

</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


    <script src="js/modalBox.js">  </script>
      <script src="js/hammenu.js">  </script>
            <script src="js/clickFAB.js" type="text/javascript">      </script>

      <script src="js/view_enrolled_students.js">  </script>


</body>
</html>