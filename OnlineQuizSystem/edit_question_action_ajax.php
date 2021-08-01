<?php

include('class/Examination.php');
$exam = new Examination;

$output = array() ; 
if (isset($_POST)){
    if ($_REQUEST['page'] == 'question'){
        if ($_REQUEST['action'] == 'edit_fetch'){
        // print_r($_REQUEST);
        // print_r($_POST);
			$exam->query = "
			SELECT * FROM question_table
			WHERE question_id = '".$_REQUEST["questionid"]."'
			";

			$result = $exam->query_result();

			foreach($result as $row)
			{
				$output['question_title'] = html_entity_decode($row['question_title']);

				$output['answer_option'] = $row['answer_option'];

				for($count = 1; $count <= 4; $count++)
				{
					$exam->query = "
					SELECT option_title FROM option_table
					WHERE question_id = '".$_REQUEST["questionid"]."'
					AND option_number = '".$count."'
					";

					$sub_result = $exam->query_result();

					foreach($sub_result as $sub_row)
					{
						$output["option_title_" . $count] = html_entity_decode($sub_row["option_title"]);
					}
				}
			}

			echo json_encode($output);
		}
        if($_REQUEST['action'] == 'Edit Question')
		{
            
                $exam->data = array(
                    ':question_title'		=>	$_POST['question_title'],
                    ':answer_option'		=>	$_POST['answer_option'],
                    ':question_id'			=>	$_POST['question_id']
                );
    
                $exam->query = "
                UPDATE question_table
                SET question_title = :question_title, answer_option = :answer_option
                WHERE question_id = :question_id
                ";
    
                $exam->execute_query();
    
                for($count = 1; $count <= 4; $count++)
                {
                    $exam->data = array(
                        ':question_id'		=>	$_POST['question_id'],
                        ':option_number'	=>	$count,
                        ':option_title'		=>	$_POST['option_title_' . $count]
                    );
    
                    $exam->query = "
                    UPDATE option_table
                    SET option_title = :option_title
                    WHERE question_id = :question_id
                    AND option_number = :option_number
                    ";
                    $exam->execute_query();
                }
    

            $debug = ["status" => "success", 'details' => "Question Updated."]; 

    
                echo json_encode($debug);
            
    }
    if($_REQUEST['action'] == 'Delete'){
        $exam->data = [':question_id'	=>	$_REQUEST['delquestionid'] ];

        $exam->query = "
        DELETE FROM question_table
        WHERE question_id = :question_id
        ";

        $exam->execute_query();

        // $output = array(
        //     'success'	=>	'Exam Details has been removed'
        // );
        $debug = ["status" => "success", 'details' => "Question Deleted."]; 

        echo json_encode($debug);
        }
    }
    
}
?>