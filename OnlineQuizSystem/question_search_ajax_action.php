<?php 
include('class/Examination.php');


$exam = new Examination;

if($_POST['page'] == 'question')
{
    if($_POST['action'] == 'fetch')
    {
        $output = array();
        $exam_id = '';
        if(isset($_POST['code']))
        {
            $exam_id = $exam->Get_exam_id($_POST['code']);
        }
        $exam->query = "
        SELECT * FROM question_table
        WHERE online_exam_id = '".$exam_id."'
        AND (
        ";

        if(isset($_POST['search']['value']))
        {
            $exam->query .= 'question_title LIKE "%'.$_POST["search"]["value"].'%" ';
        }

        $exam->query .= ')';

        if(isset($_POST["order"]))
        {
            $colNo = intval($_POST['order']['0']['column'])+1;
            $exam->query .= '
            ORDER BY '.$colNo.' '.$_POST['order']['0']['dir'].'
            ';
        }
        else
        {
            $exam->query .= 'ORDER BY question_id ASC ';
        }

        $extra_query = '';

        if($_POST['length'] != -1)
        {
            $extra_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $filtered_rows = $exam->total_rows();

        $exam->query .= $extra_query;

        $result = $exam->query_result();

        $exam->query = "
        SELECT * FROM question_table
        WHERE online_exam_id = '".$exam_id."'
        ";

        $total_rows = $exam->total_rows();

        $data = array();

        foreach($result as $row)
        {
            $sub_array = array();

            $sub_array[] = $row['question_title'];

            $sub_array[] = 'Option ' . $row['answer_option'];

            $edit_button = '';
            $delete_button = '';

            if($exam->Is_exam_is_not_started($exam_id))
            {
                $edit_button = '<a name="edit" class="waves-effect waves-light btn-small modal-trigger blue editQ" href="#modalEditQ" id="'.$row['question_id'].'">Edit</a><br>';
                // '<a id="' . $row['online_exam_id'] . '" name="edit" class="waves-effect waves-light btn-small modal-trigger blue edit" href="#modal1">Edit</a><br>'; 
                // $edit_button = '<a name="edit" class="waves-effect waves-light btn-small modal-trigger blue edit" id="'.$row['question_id'].'">Edit</a><br>';

                $delete_button = '<br><a   name="delete" class="waves-effect waves-light btn-small modal-trigger red deleteQ" href="#modalDelQ" id="'.$row['question_id'].'">Delete</a>';
            } else {
                $edit_button = '<a name="edit" class="waves-effect waves-light btn-small modal-trigger blue editQ" href="#modalEditQ" id="'.$row['question_id'].'" disabled>Edit</a><br>';
                $delete_button = '<br><a   name="delete" class="waves-effect waves-light btn-small modal-trigger red deleteQ" href="#modalDelQ" id="'.$row['question_id'].'" disabled>Delete</a>';
                
                
            }

            $sub_array[] = $edit_button . ' ' . $delete_button;

            $data[] = $sub_array;
        }

        $output = array(
            "draw"		=>	intval($_POST["draw"]),
            "recordsTotal"	=>	$total_rows,
            "recordsFiltered"	=>	$filtered_rows,
            "data"		=>	$data
        );

        echo json_encode($output);
    }

}   
?>