<?php 

include('class/Examination.php');
$exam = new Examination;

if (isset($_POST)){
    if ($_POST["page"] == 'enroll_exam')
    {
        if($_POST['action'] == 'fetch')
        {
        // print_r($_POST);
        $output = array();

        $exam->query = "
        SELECT * FROM user_exam_enroll_table 
        INNER JOIN online_exam_table 
        ON online_exam_table.online_exam_id = user_exam_enroll_table.exam_id 
        WHERE user_exam_enroll_table.user_id = '".$_SESSION['user_id']."' 
        AND (";

        if(isset($_POST["search"]["value"]))
        {
             $exam->query .= 'online_exam_table.online_exam_title LIKE "%'.$_POST["search"]["value"].'%" ';
             $exam->query .= 'OR online_exam_table.online_exam_datetime LIKE "%'.$_POST["search"]["value"].'%" ';
             $exam->query .= 'OR online_exam_table.online_exam_duration LIKE "%'.$_POST["search"]["value"].'%" ';
             $exam->query .= 'OR online_exam_table.total_question LIKE "%'.$_POST["search"]["value"].'%" ';
             $exam->query .= 'OR online_exam_table.marks_per_right_answer LIKE "%'.$_POST["search"]["value"].'%" ';
             $exam->query .= 'OR online_exam_table.marks_per_wrong_answer LIKE "%'.$_POST["search"]["value"].'%" ';
             $exam->query .= 'OR online_exam_table.online_exam_status LIKE "%'.$_POST["search"]["value"].'%" ';
        }

        $exam->query .= ')';

        if(isset($_POST["order"]))
        {
            // print_r($_POST['order']);
            $colNo = intval($_POST['order'][0]['column'])+1; 
            $exam->query .= 'ORDER BY ' .$colNo .' '.$_POST['order'][0]['dir'].' ';
        }
        else
        {
            $exam->query .= 'ORDER BY online_exam_table.online_exam_id DESC ';
        }

        $extra_query = '';

        if($_POST["length"] != -1)
        {
             $extra_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $filterd_rows = $exam->total_rows();

        $exam->query .= $extra_query;

        $result = $exam->query_result();

        $exam->query = "
        SELECT * FROM user_exam_enroll_table 
        INNER JOIN online_exam_table 
        ON online_exam_table.online_exam_id = user_exam_enroll_table.exam_id 
        WHERE user_exam_enroll_table.user_id = '".$_SESSION['user_id']."'";

        $total_rows = $exam->total_rows();

        $data = array();

        foreach($result as $row)
        {
            $sub_array = array();
            $sub_array[] = html_entity_decode($row["online_exam_title"]);
            $sub_array[] = $row["online_exam_datetime"];
            $sub_array[] = $row["online_exam_duration"] . ' Minute';
            $sub_array[] = $row["total_question"] . ' Question';
            $sub_array[] = $row["marks_per_right_answer"] . ' Mark';
            if ($row['marks_per_wrong_answer'] == 0){
					
                $sub_array[] =  $row['marks_per_wrong_answer'] . ' Mark';
            } else {

            $sub_array[] = '-' . $row["marks_per_wrong_answer"] . ' Mark';
            }
            $status = '';

            if($row['online_exam_status'] == 'Created')
            {
                $status = '<span class="new badge pink badges">Created</span>';
            }

            if($row['online_exam_status'] == 'Not Started Yet')
            {
                $status = '<span style="font-size:11px; line-height:9px !important; padding-top:3px; padding-bottom:33px" class="badge yellow">Not Started Yet</span>';

            }

            if($row['online_exam_status'] == 'Available now')
            {
                $status = '<span style="font-size:11px; line-height:9px !important; padding-top:5px; padding-bottom:5px; margin-bottom:20px" class="badge light-blue white-text badges">Available</span>';
            }

            if($row['online_exam_status'] == 'Started')
            {
                $status = '<span  style="font-size:11px; line-height:9px !important; padding-top:5px; padding-bottom:5px; margin-bottom:20px"  class="badge red white-text badges">Started</span>';
            }

            if($row['online_exam_status'] == 'Completed')
            {
                $status = '<span class="new badge green badges">Completed</span>';
            }

            $sub_array[] = $status;				
            $view_exam = "" ; 
            if($row["online_exam_status"] == 'Available now')
            {
                $view_exam = '<a href="view_exam.php?code='.$row["online_exam_code"].'" class="btn red btn-small" data-id="'. $row["online_exam_id"].'" >Start</a>';
            } 
      
            else if ($row["online_exam_status"] == 'Completed'){
                // $view_exam = '<a href="view_exam.php?code='.$row["online_exam_code"].'" class="btn blue btn-small">View Results</a>';
                // $view_exam = '<a class="btn blue btn-small">Results</a>';
                $view_exam = '<a href="view_exam.php?code='.$row["online_exam_code"].'" class="btn blue btn-small" data-id="'. $row["online_exam_id"].'" >Results</a>';


            
            }
            else if ($row["online_exam_status"] == 'Started'){
                // $view_exam = '<a href="view_exam.php?code='.$row["online_exam_code"].'" class="btn blue btn-small">View Results</a>';
                // $view_exam = '<a class="btn #ff5722 deep-orange btn-small">Return</a>';
                $view_exam = '<a href="view_exam.php?code='.$row["online_exam_code"].'" class="btn btn-small #ff5722 deep-orange" data-id="'. $row["online_exam_id"].'" >Return</a>';            
            }
            
            else if(($row["online_exam_status"] == 'Created') or ($row["online_exam_status"] == 'Not Started Yet') )
            {
                $view_exam = '<button disabled> Not Started Yet</button>';
            }
            
            $sub_array[] = $view_exam;

            $data[] = $sub_array;
        }

        $output = array(
             "draw"    			=> 	intval($_POST["draw"]),
             "recordsTotal"  	=>  $total_rows,
             "recordsFiltered" 	=> 	$filterd_rows,
             "data"    			=> 	$data
        );
        echo json_encode($output);
    }
}
}

?>