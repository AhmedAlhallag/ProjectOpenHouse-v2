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
        <link rel="stylesheet" href="css/TimeCircles.css">
        <link rel="stylesheet" href="css/view_exam_css.css">
        

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

    [type="radio"]+span:before, [type="radio"]+span:after{
        margin: 6px !important;
    }

    .titlee{
        padding-left:20px !important
    }


    </style>
</head>
<body>
    <form action="">
        <input type="hidden" name="action" id="action" value="not-started">
    </form>
<?php 
    include('partials/nav.php');
    // $examObj->admin_session_public(); 
    $examObj->Check_exam_status();
    
    // ============================================ 
    $exam_id = '';
    $exam_status = '';
    $remaining_minutes = '';
    $exam_title = "" ; 

    if(isset($_GET['code']))
    {
        $exam_id = $examObj->Get_exam_id($_GET["code"]);
        // make a flag that we will recieve from the socket =============


        $examObj-> data = array(
            ":online_exam_id" => $exam_id

        );
        $examObj->query = "
        SELECT online_exam_title, online_exam_status, online_exam_datetime, online_exam_duration FROM online_exam_table 
        WHERE online_exam_id = :online_exam_id";

        $result = $examObj->query_result();





        foreach($result as $row)
        {
            $exam_title = $row['online_exam_title'];  
            $exam_status = $row['online_exam_status'];
            $exam_star_time = $row['online_exam_datetime'];
            $duration = $row['online_exam_duration'] . ' minute';
            // print_r($exam_star_time . "<br>");
            // print_r($duration . "<br>");
            $exam_end_time = strtotime($exam_star_time . '+' . $duration);
            // print_r($exam_end_time . "<br>");
            
            $exam_end_time = date('Y-m-d H:i:s', $exam_end_time);
            // print_r($exam_end_time . "<br>");
            $remaining_minutes = strtotime($exam_end_time) - time();
            // print_r($remaining_minutes . "<br>");
        }

        // get all question (rows' ids) for this user
        $examObj-> data = array(
            ":exam_id" => $exam_id,
            ":user_id" => $current_id
        );

        $examObj->query ="
        SELECT user_exam_question_answer_id FROM `user_exam_question_answer` WHERE 
        user_id = :user_id
        AND 
        exam_id = :exam_id
        " ;
        $questions_rows_array = $examObj->query_result();
        $questions_rows_ids_array = [] ; 
        foreach($questions_rows_array as $row){
            $questions_rows_ids_array[] =  intval($row[0]);
        }
        // print_r($questions_rows_ids_array);
        // echo '<br>';
        // print_r($questions_rows_array[0][0]);
    }
    else
    { ?>
    <script>

        window.location.href = "enroll_exam.php";
    </script>

<?php 
    }
?>   

<?php  if ($type == "user"){ ?>
    <div id="container" class="container">
        <!-- =================== if exam had started ====================== -->
    <?php
    if($exam_status == 'Started')
    {
        // echo "EXAM status: " .$exam_status . '<br>';  
        $examObj->data = array(
            ':user_id'		=>	$_SESSION['user_id'],
            ':exam_id'		=>	$exam_id,
            ':attendance_status'	=>	'Present'
        );

        $examObj->query = "
        UPDATE user_exam_enroll_table 
        SET attendance_status = :attendance_status 
        WHERE user_id = :user_id 
        AND exam_id = :exam_id
        ";

        $examObj->execute_query();

    ?>
<div class="row flex-s">

	<div class="col s12 m8 l8 box-a">

		<div class="card">
        <div class="card-title titlee"><?php echo $exam_title; ?></div>
            <div class="card-content">
				<div id="single_question_area"></div>
			</div>
		</div>
		<br />
		<div id="question_navigation_area"></div>
	</div>
    <div class="col s12 m4 l4 box-b">

    <div>
        <div id="exam_timer" data-timer="<?php echo $remaining_minutes; ?>" style="max-width:400px; width: 100%; height: 200px;"></div>
    </div>
    </div>

</div>
</div>

<?php
}
else if($exam_status == 'Completed')
{
    $examObj->data = array(
        ':user_id'		=>	$_SESSION['user_id'],
        ':exam_id'		=>	$exam_id
    );

	$examObj->query = "
	SELECT * FROM question_table 
	INNER JOIN user_exam_question_answer 
	ON user_exam_question_answer.question_id = question_table.question_id 
	WHERE question_table.online_exam_id = :exam_id 
	AND user_exam_question_answer.user_id = :user_id
	";

	//echo $exam->query;

	$result = $examObj->query_result();
?>
	<div class="card">
		<div class="card-title">
			<div class="row">
				<div class="col m8">Online Exam Result</div>
			</div>
		</div>
		<div class="card-content">
			<div class="responsive-table">
				<table class="table highlight striped centered">
					<tr>
						<th>Question</th>
						<th>Option 1</th>
						<th>Option 2</th>
						<th>Option 3</th>
						<th>Option 4</th>
						<th>Your Answer</th>
						<th>Answer</th>
						<th>Result</th>
						<th>Marks</th>
					</tr>
					<?php
					$total_mark = 0;

					foreach($result as $row)
					{
                        $examObj->data = array(
                            ':question_id'		=>	$row["question_id"]
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

						if($row['marks'] == '0')
						{
							$question_result = '<h4  style="font-size:12px; padding-bottom:5px; padding-top:3px; position:relative;top:-3px;color:white" class="#f44336 red-text"><i class="fas fa-times-circle"></i></h4>';
						}

						if($row['marks'] > '0')
						{
							$question_result = '<h4 style="font-size:12px; padding-bottom:5px; padding-top:3px; position:relative;top:-3px; color:white"  class="#00e676 green-text text-accent-3"><i class="fas fa-check-circle"></i></h4>';
						}

						if($row['marks'] < '0')
						{
							$question_result = '<h4  style="font-size:12px; padding-bottom:5px; padding-top:3px; position:relative;top:-3px;color:white" class="#f44336 red-text"><i class="fas fa-times-circle"></i></h4>';

						}

						echo '
						<tr>
							<td>'.$row['question_title'].'</td>
						';

						foreach($sub_result as $sub_row)
						{
							echo '<td>'.$sub_row["option_title"].'</td>';

							if($sub_row["option_number"] == $row['user_answer_option'])
							{
								$user_answer = $sub_row['option_title'];
							}

							if($sub_row['option_number'] == $row['answer_option'])
							{
								$orignal_answer = $sub_row['option_title'];
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
                        ':user_id'		=>	$_SESSION['user_id'],
                        ':exam_id'		=>	$exam_id
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
					?>
					<tr>
                            <td colspan="8"><b>Total Mark:</b></td>
                            <td><mark style="color:red"> <b><?php echo $row["total_mark"]; ?></b> </mark style="color:red"> /<?php echo $res[0]['exam_total_mark']?></td>
					</tr>
					<?php	
					}

					?>
				</table>
			</div>
		</div>
	</div>


    <?php } else if ($exam_status == "Available now") { 
        // IF and only if it was available, then please wait until the admin starts the exam manually
        ?>

        <div class="content">
            <p style="font-weight:bold; font-size:30px; color:cornflowerblue"> The Admin Will start the exam shortly...</p>
            <p style="font-weight:bold; font-size:30px; color:cornflowerblue"> Please Wait...</p>
        </div>

    <?php } else { ?>
        <script>
            window.location.href = "enroll_exam.php";
        </script>

    <?php }
}  else if ($type == 'admin'){ ?>
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
      <script src="js/TimeCircles.js"></script>
      <script src="js/view_exam.js"></script>

<?php
    if($exam_status == 'Started'){ ?>
      <script>

        var exam_id = "<?php echo $exam_id; ?>";
        var user_id = "<?php echo $current_id; ?>";
        var question_rows_ids_array = "<?php echo json_encode($questions_rows_ids_array); ?>";
        question_rows_ids_array = JSON.parse(question_rows_ids_array);
        // console.log(question_rows_array);
    
        load_question();
        // console.log(row_id);
        question_navigation();
    
        function load_question(row_id = '')
        {
            $.ajax({
                url:"user_online_exam_action.php",
                method:"POST",
                data:{exam_id:exam_id, row_id:row_id, page:'view_exam', action:'load_question'},
                success:function(data)
                {
                    // console.log("DATA");
                    // var data = JSON.parse(data)
                    // console.log(data);
                    $('#single_question_area').html(data);

        }
        
            })
        }
    
        $(document).on('click', '.next', function(){
            var row_id = $(this).attr('id');
            load_question(row_id);
            var elems  = document.querySelectorAll('[data-question_id]');
            var currenId = ""; 
            // currenId = $('input:hidden[name=current]').attr('id');
            currenId = row_id;
            addHover(currenId,elems);


        });
    
        $(document).on('click', '.previous', function(){
            var row_id = $(this).attr('id');
            load_question(row_id);
            var elems  = document.querySelectorAll('[data-question_id]');
            var currenId = ""; 
            // currenId = $('input:hidden[name=current]').attr('id');
            currenId = row_id;

            addHover(currenId,elems);

        });
    
        function question_navigation()
        {
            $.ajax({
                url:"user_online_exam_action.php",
                method:"POST",
                data:{exam_id:exam_id, page:'view_exam', action:'question_navigation'},
                success:function(data)
                {
                    $('#question_navigation_area').html(data);
                    
                    var elems  = document.querySelectorAll('[data-question_id]');
                    var currenId = ""; 
                    currenId = $('input:hidden[name=current]').attr('id');
                    addHover(currenId,elems);


                    // console.log(elems);
                    // elems.forEach((elem)=>console.log(elem));
                    // for (let i = 0 ;  i <elems.length ; i++ ){
                    //     if ( parseInt($(elems[i]).attr('data-question_id')) == parseInt(currenId) ) {
                    //         currenId = parseInt($(elems[i]).attr('data-question_id')) ; 
                    //         break;
                    //     }

                    // }
                    //============ add color ===============
                    // console.log("id:" , currenId);
                    // $("[data-question_id='" + currenId + "']").addClass( "#00796b teal darken-2")
                    // $("p").css("background-color", "yellow");


    	        }
                
            })
        }
    
        $(document).on('click', '.question_navigation', function(){
            var question_id = $(this).data('question_id');
            load_question(question_id);
            var elems  = document.querySelectorAll('[data-question_id]');
            var currenId = ""; 
            currenId = question_id;
            addHover(currenId,elems);
        });

        $("#exam_timer").TimeCircles({ 
            count_past_zero: false,
            time:{
                Days:{
                    show: false
                },
                Hours:{
                    show: false
                }
            }
        })	
        // function countdownComplete(unit, value, total){


        //         // location.reload();
        //     }

            setInterval(function(){
                var remaining_second = $("#exam_timer").TimeCircles().getTime();
                if( (remaining_second < 1) )
                {
                    // alert('Exam time over');
                    location.reload();
                }
            }, 2000);
        
    


    $(document).on('click', '.answer_option', function(){
    var question_id = $(this).data('question_id');

    var answer_option = $(this).data('id');

		$.ajax({
			url:"user_online_exam_action.php",
			method:"POST",
			data:{question_id:question_id, answer_option:answer_option, exam_id:exam_id, page:'view_exam', action:'answer'},
			success:function(data)
			{
                console.log('data:');
                console.log(data);

			}
		})
	});


// ================= some functions ===============

        // set the current one and unset all of the rest 
        function addHover(currentId, elems){
            // currentId = $('input:hidden[name=current]').attr('id');
            $("[data-question_id='" + currentId + "']").addClass( "#00796b teal darken-2");

            for (let i = 0 ;  i <elems.length ; i++ ){
                if ( parseInt($(elems[i]).attr('data-question_id')) != parseInt(currentId) ) {
                    // currenId = parseInt($(elems[i]).attr('data-question_id')) ; 
                    // $("[data-question_id='" + currentId + "']").addClass( "#00796b teal darken-2");
                    $(elems[i]).removeClass("#00796b teal darken-2");
                }

            }
        }
      </script>
      <?php } ?>

      </body>
</html>
