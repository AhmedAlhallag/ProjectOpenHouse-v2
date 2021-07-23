<?php 
include('class/Examination.php');
$exam = new Examination;
$exam_status = 'unchanged' ;
$output = array() ; 
if (isset($_POST)){


    if($_REQUEST['page'] == 'question')
    {
        if($_REQUEST['action'] == 'Add Question') {

            // before adding any question, check if it's possible or not, if not, 
            // then update this exam status in the database 
            $exam_id = $_POST['online_exam_id'] ; 
            $exam->data = array(
                ':online_exam_id'		=>	$exam_id
            );
            $exam->query = "SELECT COUNT(*) as count from question_table WHERE online_exam_id = :online_exam_id;";
            $res = $exam->query_result(); 
            $curr_count = $res[0]['count'] ;
            // get this exam's total quetsions
            // $exam->data = array(
            //     ':online_exam_id'		=>	$exam_id
            // );
            $exam->query = "SELECT total_question FROM online_exam_table WHERE online_exam_id = :online_exam_id" ; 
            $res = $exam->query_result();
            $total_questions = $res[0]['total_question'];
            // print_r($total_questions);
            // print_r($curr_count);
            if ( $curr_count >= $total_questions ){
                // decline question adding 
                $debug = ["status" => "failure", 'details' => "Exam question limit is $total_questions.", "exam_status" => $exam_status]; 
                echo json_encode($debug);
    
            } else {

            $exam->data = array(
                ':online_exam_id'		=>	$exam_id,
                ':question_title'		=>	$exam->clean_data($_POST['question_title']),
                ':answer_option'		=>	$_POST['answer_option']
            );

            $exam->query = "
            INSERT INTO question_table
            (online_exam_id, question_title, answer_option)
            VALUES (:online_exam_id, :question_title, :answer_option)
            ";

            $question_id = $exam->execute_question_with_last_id($exam->data);

            for($count = 1; $count <= 4; $count++)
            {
                $exam->data = array(
                    ':question_id'		=>	$question_id,
                    ':option_number'	=>	$count,
                    ':option_title'		=>	$exam->clean_data($_POST['option_title_' . $count])
                );

                $exam->query = "
                INSERT INTO option_table
                (question_id, option_number, option_title)
                VALUES (:question_id, :option_number, :option_title)
                ";

                $exam->execute_query($exam->data);
            }

            // if an exam is added, get the recent questions number
            // if it reached the total question number of the exam, update the status of exam to created 
            $exam->data = array(
                ':online_exam_id'		=>	$exam_id
            );
            $exam->query = "SELECT COUNT(*) as count from question_table WHERE online_exam_id = :online_exam_id;";
            $res = $exam->query_result(); 
            $curr_count = $res[0]['count'] ;
            if ( $curr_count == $total_questions ){
                // mark the exam as created 
                // $exam->data = array(
                //     ':online_exam_id'		=>	$exam_id
                // );
                $exam->query = "UPDATE online_exam_table SET online_exam_status = 'Created' WHERE online_exam_id = :online_exam_id;";
                $exam->query_result();
                $exam_status = "updated"; 


            }



            $debug = ["status" => "success", 'details' => "Question Added.","exam_status" => $exam_status]; 

            echo json_encode($debug);
    }
}
}
}


?>