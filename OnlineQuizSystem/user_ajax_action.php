<?php
include('class/Examination.php');
$exam = new Examination;


if (isset($_REQUEST)){
    // print_r($_REQUEST);

    


    if($_REQUEST['action'] == "fetch_exam")
    {
        $exam->query = "
        SELECT * FROM online_exam_table 
        WHERE online_exam_id = '".$_REQUEST['exam_id']."'
        ";

        $result = $exam->query_result();

        $output = '
        <div class="card">
        <br>
            <div class="card-title titlee">Exam Details</div>
            <div class="card-content">
                <table class="striped responsive-table">
        ';
        foreach($result as $row)
        {
            $output .= '
            <tr>
                <td><b>Exam Title</b></td>
                <td>'.$row["online_exam_title"].'</td>
            </tr>
            <tr>
                <td><b>Exam Date & Time</b></td>
                <td>'.$row["online_exam_datetime"].'</td>
            </tr>
            <tr>
                <td><b>Exam Duration</b></td>
                <td>'.$row["online_exam_duration"].' Minute</td>
            </tr>
            <tr>
                <td><b>Exam Total Question</b></td>
                <td>'.$row["total_question"].' </td>
            </tr>
            <tr>
                <td><b>Marks Per Right Answer</b></td>
                <td>'.$row["marks_per_right_answer"].' Mark</td>
            </tr>
            <tr>
                <td><b>Marks Per Wrong Answer</b></td>' ;

                if ($row["marks_per_wrong_answer"] == 0){
                    $output .= '<td>'.$row["marks_per_wrong_answer"].' Mark</td>';
                } else {
                    $output .= '<td>-'.$row["marks_per_wrong_answer"].' Mark</td>';

                }
                
            $output .= '</tr>';
            if($exam->If_user_already_enroll_exam($_REQUEST['exam_id'], $_SESSION['user_id']))
            {
                $enroll_button = '
                <tr>
                    <td colspan="2" align="center">
                        <button type="button" name="enroll_button" class="btn orange" disabled>You\'re Already Enrolled</button>
                        <a  class="btn blue" href="enroll_exam.php">View Enrolled Activities</a>
                    </td>
                </tr>
                ';
            }
            else
            {
                $enroll_button = '
                <tr>
                    <td colspan="2" align="center">
                        <button type="button" name="enroll_button" id="enroll_button" class="btn green" data-exam_id="'.$row['online_exam_id'].'">Enroll it</button>
                    </td>
                </tr>
                ';
            }
            $output .= $enroll_button;
        }
        $output .= '</table>';
        echo $output;
    }



    if($_REQUEST['action'] == 'enroll_exam')
    {
        $exam->data = array(
            ':user_id'		=>	$_SESSION['user_id'],
            ':exam_id'		=>	$_POST['exam_id']
        );

        $exam->query = "
        INSERT INTO user_exam_enroll_table 
        (user_id, exam_id) 
        VALUES (:user_id, :exam_id)
        ";

        $exam->execute_query();

        $exam->data = array(
            ':exam_id'		=>	$_POST['exam_id']
        );
        // randomize the questions we will associate with each user
        $exam->query = "
        SELECT question_id FROM question_table 
        WHERE online_exam_id = :exam_id
        ORDER BY RAND();
        ";
        

        $result = $exam->query_result();
        $count = 0  ; 
        
        foreach($result as $row)
        {
            $exam->data = array(
                ':user_id'				=>	$_SESSION['user_id'],
                ':exam_id'				=>	$_POST['exam_id'],
                ':question_id'			=>	$row['question_id'],
                ':user_answer_option'	=>	'0',
                ':marks'				=>	'0'	
            );
            // print_r("qid: " . $exam->data[':question_id']);

            $exam->query = "
            INSERT INTO user_exam_question_answer 
            (user_id, exam_id, question_id, user_answer_option, marks) 
            VALUES (:user_id, :exam_id, :question_id, :user_answer_option, :marks)
            ";
            $exam->execute_query();
            // $count = $count + 1 ; 
            // if ($count == 3){

            //     break;
            // }
        
    // print_r($_POST);
    }

}
}

?>