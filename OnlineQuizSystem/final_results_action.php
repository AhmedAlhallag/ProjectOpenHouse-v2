<?php

include('class/Examination.php');
$exam = new Examination;


if($_POST['page'] == 'exam_result')
{
    if($_POST['action'] == 'fetch')
    {
        $output = array();
        $exam_id = $exam->Get_exam_id($_POST["code"]);
        $exam->data = array(
            ":exam_id" =>  $exam_id
        ) ; 
        $exam->query = "
        SELECT user_table.user_id, user_table.user_name, sum(user_exam_question_answer.marks) as total_mark
        FROM user_exam_question_answer
        INNER JOIN user_table
        ON user_table.user_id = user_exam_question_answer.user_id
        WHERE user_exam_question_answer.exam_id = :exam_id
        AND (
        ";

        if(isset($_POST["search"]["value"]))
        {
            $exam->query .= 'user_table.user_name LIKE "%'.$_POST["search"]["value"].'%" ';
        }

        $exam->query .= '
        )
        GROUP BY user_exam_question_answer.user_id
        ';

        if(isset($_POST["order"]))
        {
            $colNo = intval($_POST['order'][0]['column'])+1; 

            $exam->query .= 'ORDER BY '.$colNo.' '.$_POST['order']['0']['dir'].' ';
        }
        else
        {
            $exam->query .= 'ORDER BY total_mark DESC ';
        }

        $extra_query = '';

        if($_POST["length"] != -1)
        {
            $extra_query = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $filtered_rows = $exam->total_rows();

        $exam->query .= $extra_query;

        $result = $exam->query_result();

        $exam->data = array(
            ":exam_id" =>  $exam_id
        ) ; 
        $exam->query = "
        SELECT user_table.user_name, sum(user_exam_question_answer.marks) as total_mark
        FROM user_exam_question_answer
        INNER JOIN user_table
        ON user_table.user_id = user_exam_question_answer.user_id
        WHERE user_exam_question_answer.exam_id = :exam_id
        GROUP BY user_exam_question_answer.user_id
        ORDER BY total_mark DESC
        ";

        $total_rows = $exam->total_rows();

        $data = array();

        // get exam total marks        
        $exam->data = array(
            ':exam_id'		=>	$exam_id
        );

        $exam->query = "
        SELECT exam_total_mark FROM online_exam_table 
        WHERE online_exam_id = :exam_id
        ";

        $res = $exam->query_result();

        foreach($result as $row)
        {
            $sub_array = array();
            $sub_array[] = $row["user_name"];
            $sub_array[] = $exam->Get_user_exam_status($exam_id, $row["user_id"]);
            $sub_array[] = '<mark style="color:red"> <b>'. $row["total_mark"].'</b> </mark style="color:red"> /' . $res[0]["exam_total_mark"];
            $data[] = $sub_array;
        }

        $output = array(
            "draw"				=>	intval($_POST["draw"]),
            "recordsTotal"		=>	$total_rows,
            "recordsFiltered"	=>	$filtered_rows,
            "data"				=>	$data
        );

        echo json_encode($output);
    }
}

?>