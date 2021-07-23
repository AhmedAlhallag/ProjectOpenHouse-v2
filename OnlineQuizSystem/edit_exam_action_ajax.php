<?php

// $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));
include('class/Examination.php');
$exam = new Examination;
$this_exam_datetime = "" ; 
$this_edited_datetime = "" ; 

$this_exam_duration = "" ; 
$this_edited_duration = "" ; 

$ret_val = "";


$output = array() ; 
if (isset($_POST)){
    if ($_REQUEST['action'] == 'edit_fetch'){
        // print_r($_REQUEST);
        // print_r($_POST);
        $exam ->query = "SELECT * FROM online_exam_table WHERE online_exam_id = '". $_REQUEST['examid'] . "';" ; 
        $result = $exam->query_result();
        // print_r($result[0]['online_exam_datetime']); 
        // $time = new DateTime('19:24:15');
        // $time = $time->format('h:i a') ;

        // $date = new DateTime('2013-09-28');
        // $date = $date -> format('Y-m-d');

        // print_r($time);
        // print_r($date);
        foreach($result as $row)
        {
            $output['online_exam_title'] = $row['online_exam_title'] ;         
            // ========================================================
            $this_exam_datetime  =  $row['online_exam_datetime'] ; 

           $datetime = explode(" ", $row['online_exam_datetime']); 
           // get date 
           $date = new DateTime($datetime[0]);
           $date = $date -> format('Y-m-d');

           // get time 
           $time = new DateTime($datetime[1]);
           $time = $time->format('h:i a') ;
           $output['time'] = $time; 
           $output['time'] = str_replace('pm','PM',$time) ?? str_replace('am','AM',$time) ; 
           $output['date'] = $date; 
            
           $this_exam_duration = $row['online_exam_duration']; 

            $output['online_exam_duration'] = $row['online_exam_duration'];

            $output['total_question'] = $row['total_question'];

            $output['marks_per_right_answer'] = $row['marks_per_right_answer'];

            $output['marks_per_wrong_answer'] = $row['marks_per_wrong_answer'];
        }
        echo json_encode($output);

    }

    if ($_REQUEST['action'] == "make_available"){
        // print_r($_REQUEST);
        $exam-> data = array(
            ":exam_id" => $_REQUEST['examid'],
            ':curr_datetime' => $_REQUEST['curr_datetime']

        );

        $exam -> query = "
        UPDATE online_exam_table
        SET 
        online_exam_datetime = :curr_datetime
        WHERE online_exam_id = :exam_id
        ";

        $exam->execute_query();

        // get the exam title as well 
        $exam-> data = array(
            ":exam_id" => $_REQUEST['examid']
        );

        $exam -> query = "
        SELECT online_exam_title
        FROM online_exam_table
        WHERE online_exam_id = :exam_id
        ";

        $result = $exam->query_result();
        $title = "" ; 


        // update buttons
        $ret_val = "" ;
        $exam_status = "" ;  
        if (!empty($exam->update_Avail_Start_button($_REQUEST['examid']))){
            $ret_val = "update_buttons";
            $exam_status = $exam->update_Avail_Start_button($_REQUEST['examid'])[2];
            $title = $result[0]['online_exam_title'];
            

        }
        // $force_start = $exam->update_Avail_Start_button($_REQUEST['examid'])[1];
        $debug = ["status" => "success", 'details' => "Exam is available now", 'after_edit_check' => $ret_val , "exam_status" => $exam_status, "title" => $title]; 
        // print_r($_POST);
        echo json_encode($debug);





    }

    if ($_REQUEST['action'] == "reset_exam"){
        $exam-> data = array(
            ":exam_id" => $_REQUEST['examid'],
            ':curr_datetime' => $_REQUEST['curr_datetime']

        );

        $exam -> query = "
        UPDATE online_exam_table
        SET 
        online_exam_datetime = :curr_datetime
        WHERE online_exam_id = :exam_id
        ";

        $exam->execute_query();

        // get the exam title as well 
        $exam-> data = array(
            ":exam_id" => $_REQUEST['examid']
        );

        $exam -> query = "
        SELECT online_exam_title
        FROM online_exam_table
        WHERE online_exam_id = :exam_id
        ";

        $result = $exam->query_result();
        $title = "" ; 


        // update buttons
        $ret_val = "" ;
        $exam_status = "" ;  
        if (!empty($exam->update_Avail_Start_button($_REQUEST['examid']))){
            $ret_val = "update_buttons";
            $exam_status = $exam->update_Avail_Start_button($_REQUEST['examid'])[2];
            $title = $result[0]['online_exam_title'];
            

        }
        // $force_start = $exam->update_Avail_Start_button($_REQUEST['examid'])[1];
        $debug = ["status" => "success", 'details' => "Exam is available now", 'after_edit_check' => $ret_val , "exam_status" => $exam_status, "title" => $title]; 
        // $debug = ["status" => "success", 'details' => "Exam is available now", 'after_edit_check' => $ret_val , "exam_status" => $exam_status]; 
        // print_r($_POST);
        echo json_encode($debug);





    }

    if ($_REQUEST['action'] == "force_start"){
        $exam-> data = array(
            ":exam_id" => $_REQUEST['examid'],
            ':curr_datetime' => $_REQUEST['curr_datetime']

        );

        $exam -> query = "
        UPDATE online_exam_table
        SET 
        online_exam_datetime = :curr_datetime,
        online_exam_status = 'Started'
        WHERE online_exam_id = :exam_id
        ";

        $exam->execute_query();

        // update buttons
        $ret_val = "" ;
        $exam_status = "" ;  
        if (!empty($exam->update_Avail_Start_button($_REQUEST['examid']))){
            $ret_val = "update_buttons";
            $exam_status = $exam->update_Avail_Start_button($_REQUEST['examid'])[2];
        }

        // $force_start = $exam->update_Avail_Start_button($_REQUEST['examid'])[1];
        $debug = ["status" => "success", 'details' => "Exam is available now", 'after_edit_check' => $ret_val , "exam_status" => $exam_status]; 
        // $debug = ["status" => "success", 'details' => "Exam is available now", 'after_edit_check' => $ret_val , "exam_status" => $exam_status]; 
        // print_r($_POST);
        echo json_encode($debug);





    }

    if($_REQUEST['action'] == 'Edit')
		{
            $this_edited_datetime = $_POST['datetime'] ; 
            $this_edited_duration = $_POST['online_exam_duration'];

			$exam->data = array(
				':online_exam_title'	=>	$exam->clean_data($_POST['online_exam_title']),
				':online_exam_datetime'	=>	$_POST['datetime'],
				':online_exam_duration'	=>	$_POST['online_exam_duration'],
				':total_question'		=>	$_POST['total_question'],
				':marks_per_right_answer'=>	$_POST['marks_per_right_answer'],
				':marks_per_wrong_answer'=>	$_POST['marks_per_wrong_answer'],
                ':online_exam_id'		=>	$_POST['online_exam_id']


			);

            $exam->query = "
			UPDATE online_exam_table
			SET online_exam_title = :online_exam_title, online_exam_datetime = :online_exam_datetime, online_exam_duration = :online_exam_duration, total_question = :total_question, marks_per_right_answer = :marks_per_right_answer, marks_per_wrong_answer = :marks_per_wrong_answer
			WHERE online_exam_id = :online_exam_id
			";

			$exam->execute_query();
            
            // check if datetime changed (OR duration), or change exam status 
            if ( ($this_edited_datetime != $this_exam_datetime) or ($this_edited_duration != $this_exam_duration) ){
                $ret_val = $exam->Check_exam_status_v2($_POST['online_exam_id']);

            }  
            
            // if ret_val was now_available, send a message via socket to every user to reload the page (so they can see the 'Available' tag)
            $debug = ["status" => "success", 'details' => "Exam Updated.", 'after_edit_check' => $ret_val ]; 
            // print_r($_POST);
            echo json_encode($debug);
    }

    if($_REQUEST['action'] == 'Delete'){
        $exam->data = [':online_exam_id'	=>	$_REQUEST['delexamid'] ];

        $exam->query = "
        DELETE FROM online_exam_table
        WHERE online_exam_id = :online_exam_id
        ";

        $exam->execute_query();

        $debug = ["status" => "success", 'details' => "Exam Deleted.", 'after_edit_check' => $ret_val]; 

        echo json_encode($debug);
    }

    


}
?>