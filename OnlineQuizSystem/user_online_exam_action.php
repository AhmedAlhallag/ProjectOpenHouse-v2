<?php 
include('class/Examination.php');


$exam = new Examination;

$row_id  = "" ;  


if($_POST['page'] == 'view_exam')
{
    if($_POST['action'] == 'load_question')
    {
        if($_POST['row_id'] == '')
        {
            // print_r($_SESSION['user_id']);
            // GET ONE ROW OF A RANDOM QUESTION

            // $exam->query = "
            // SELECT * FROM question_table 
            // WHERE online_exam_id = '".$_POST["exam_id"]."' 
            // ORDER BY question_id ASC 
            // LIMIT 1
            // ";
            // this is to get the row id? WHY:
            // to inject it into the next and previous buttons (and use it in their logic/query)
            $exam->query = "SELECT user_exam_question_answer_id FROM `user_exam_question_answer`
            where exam_id ='" .$_POST["exam_id"]."' AND user_id ='" . $_SESSION['user_id'] . "'
            LIMIT 1";

            $row_id =  $exam->query_result()[0]['user_exam_question_answer_id'];
            // print_r($row_id);

            $exam->query = "SELECT * FROM question_table WHERE
            question_id = (SELECT question_id FROM `user_exam_question_answer`
            where exam_id ='" .$_POST["exam_id"]."' AND user_id ='" . $_SESSION['user_id'] . "'
            LIMIT 1);";

        }
        else
        {
            // this is coming from a next or a previous
            // $exam->query = "
            // SELECT * FROM question_table 
            // WHERE question_id = '".$_POST["question_id"]."' 
            // ";
            $row_id =  $_POST['row_id'];
            // print_r("from button: " . $row_id) ; 

            $exam->query = "SELECT * FROM question_table WHERE
            question_id = (SELECT question_id FROM `user_exam_question_answer`
            where exam_id ='" . $_POST["exam_id"] . "'
            AND user_id = '" .$_SESSION['user_id']. "'
            AND user_exam_question_answer_id = '" . $row_id . "');"; 
        }

        $result = $exam->query_result();

        $output = '';

        // $exam->query = "
        // SELECT * FROM user_exam_question_answer 
        // WHERE question_id = '".$row['question_id']."'
        // ";

        $exam->query = "
        SELECT * FROM user_exam_question_answer 
        WHERE question_id = (SELECT question_id FROM question_table WHERE
        question_id = (SELECT question_id FROM `user_exam_question_answer`
        where exam_id ='" . $_POST["exam_id"] . "' 
        AND user_id = '" .$_SESSION['user_id']. "'
        AND user_exam_question_answer_id = '" . $row_id . "'))
        ;";

        $answer_result = $exam->query_result();
        
        foreach($answer_result as $row)
        {
            $user_answer_option = $row["user_answer_option"];	
        }

        foreach($result as $row)
        {
            $output .= '
            <h6>'.$row["question_title"].'</h6>
            <hr />
            <br />
            <div class="row">
            ';

            $exam->query = "
            SELECT * FROM option_table 
            WHERE question_id = '".$row['question_id']."'
            ";
            $sub_result = $exam->query_result();

            $count = 1;

            foreach($sub_result as $sub_row)
            {
                $output .= '
                <div class="col m6" style="margin-bottom:32px;">
                    <div class="radio">
                        <label><input type="radio" name="option_1" class=" with-gap answer_option"';
                        if($count == $user_answer_option){
                            $output .= ' checked="checked" ';
                        }
                    
                $output .= '
                        data-question_id="'.$row["question_id"].'" data-id="'.$count.'"/><span>&nbsp;'.$sub_row["option_title"].'</span></label>
                    </div>
                </div>';
        
                // $output .= '
                // <div class="col-md-6" style="margin-bottom:32px;">
                //     <div class="radio">
                    
                        
                //         <label><input type="radio" name="option_1" class="with-gap answer_option" data-question_id="'.$row["question_id"].'" data-id="'.$count.'"/><span>&nbsp;'.$sub_row["option_title"].'</span></label>
                //     </div>
                // </div>
                // ';

                $count = $count + 1;
            }
            $output .= '
            </div>
            ';
            // $exam->query = "
            // SELECT question_id FROM question_table 
            // WHERE question_id < '".$row['question_id']."' 
            // AND online_exam_id = '".$_POST["exam_id"]."' 
            // ORDER BY question_id DESC 
            // LIMIT 1";
            
            //IMPORTANT NOTE: you need to specify order if the comparison operator was funny

            $exam->query  = "SELECT user_exam_question_answer_id FROM `user_exam_question_answer`
            where exam_id= '" . $_POST["exam_id"]. "'
            AND user_exam_question_answer_id < '" .  $row_id . 
            "' AND user_id = '" .$_SESSION['user_id']. "'
            order by user_exam_question_answer_id desc
            LIMIT 1"; 

            $previous_result = $exam->query_result();

            $previous_id = '';
            $next_id = '';

            foreach($previous_result as $previous_row)
            {
                $previous_id = $previous_row['user_exam_question_answer_id'];
            }
            // print_r("prev: " . $previous_id);

            // $exam->query = "
            // SELECT question_id FROM question_table 
            // WHERE question_id > '".$row['question_id']."' 
            // AND online_exam_id = '".$_POST["exam_id"]."' 
            // ORDER BY question_id ASC 
            // LIMIT 1";
            $exam->query  = "SELECT user_exam_question_answer_id FROM `user_exam_question_answer`
            where exam_id= '" . $_POST["exam_id"]. "'
            AND user_exam_question_answer_id > '" .  $row_id . 
            "' AND user_id = '".$_SESSION['user_id']."'
            LIMIT 1";
            // print_r("curr:" . $row_id);
              
              $next_result = $exam->query_result();
              foreach($next_result as $next_row)
              {
                  $next_id = $next_row['user_exam_question_answer_id'];
                }
                // print_r("next: " . $next_id);

            $if_previous_disable = '';
            $if_next_disable = '';

            if($previous_id == "")
            {
                $if_previous_disable = 'disabled';
            }
            
            if($next_id == "")
            {
                $if_next_disable = 'disabled';
            }

            $output .= '
                <br /><br />
                  <div align="center">
                       <button type="button" name="previous" class="btn previous" id="'.$previous_id.'" '.$if_previous_disable.'>Previous</button>
                       <input type="hidden" name="current" class="current" id="'.$row_id.'">
                       <button type="button" name="next" class="btn next" id="'.$next_id.'" '.$if_next_disable.'>Next</button>
                  </div>
                  <br /><br />';
        }
        // $debug = ['output' => $output, 'row_id' => $row_id] ;

        // echo json_encode($debug);
        echo $output;
    }

    if($_POST['action'] == 'question_navigation')
    {
        $exam->query = "
            SELECT question_id FROM question_table 
            WHERE online_exam_id = '".$_POST["exam_id"]."' 
            ORDER BY question_id ASC 
            ";

        $exam->query = "SELECT user_exam_question_answer_id FROM `user_exam_question_answer`
        where exam_id =  '".$_POST["exam_id"]."'
        AND user_id =  '".$_SESSION['user_id']."';";

        $result = $exam->query_result();
        $output = '
        <div class="card">
            <div class="card-title titlee">Question Navigation</div>
            <div class="card-content">
                <div class="row">
        ';
        $count = 1;
        foreach($result as $row)	
        {
            $output .= '
            <div class="col md2" style="margin-bottom:24px;">
                <button type="button" class="btn question_navigation" data-question_id="'.$row["user_exam_question_answer_id"].'">'.$count.'</button>
            </div>
            ';
            $count++;
        }
        $output .= '
            </div>
        </div></div>
        ';
        echo $output;
    }

    if($_POST['action'] == 'answer')
    {
        print_r($_POST);
        // echo "hello"; 
        $exam_right_answer_mark = $exam->Get_question_right_answer_mark($_POST['exam_id']);

        $exam_wrong_answer_mark = $exam->Get_question_wrong_answer_mark($_POST['exam_id']);
        $orignal_answer = $exam->Get_question_answer_option($_POST['question_id']);

        $marks = 0;
        // print_r('USER ANSWER:');
        // print_r($_POST['answer_option']);

        
        if($orignal_answer == $_POST['answer_option'])
        {
            // print_r('USER ANSWER:');
            // print_r($_POST['answer_option']);
            $marks = '+' . $exam_right_answer_mark;
        }
        else
        {
            if (intval($exam_wrong_answer_mark) == 0){
                $marks = $exam_wrong_answer_mark;
            } else {  
            $marks = '-' . $exam_wrong_answer_mark ;
        }
    }


        $exam->data = array(
            ':user_answer_option'	=>	$_POST['answer_option'],
            ':marks'				=>	$marks
        );

        $exam->query = "
        UPDATE user_exam_question_answer 
        SET user_answer_option = :user_answer_option, marks = :marks 
        WHERE user_id = '".$_SESSION["user_id"]."' 
        AND exam_id = '".$_POST['exam_id']."' 
        AND question_id = '".$_POST["question_id"]."'
        ";
        $exam->execute_query();

        // $debug = ['status' => 'success', "details" => 'answer_updated'];
        // echo json_encode($debug);
        // echo "done";
    }
}

?>