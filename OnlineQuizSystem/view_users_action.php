<?php 

include('class/Examination.php');


$exam = new Examination;

if($_POST['page'] == 'display_users')
{
    if($_POST['action'] == 'fetch')
    {
        $output = array();

        $exam->query = "
        SELECT * FROM user_table
        WHERE ";

        if(isset($_POST["search"]["value"]))
        {
             $exam->query .= 'user_email_address LIKE "%'.$_POST["search"]["value"].'%" ';
             $exam->query .= 'OR user_name LIKE "%'.$_POST["search"]["value"].'%" ';
        }

        if(isset($_POST["order"]))
        {
            $colNo =  intval($_POST['order'][0]['column'])+1; 
            $exam->query .= 'ORDER BY '.$colNo.' '.$_POST['order'][0]['dir'].' ';
        }
        else
        {
            $exam->query .= 'ORDER BY user_id DESC ';
        }

        $extra_query = '';

        if($_POST["length"] != -1)
        {
             $extra_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
        }

        $filterd_rows = $exam->total_rows();

        $exam->query .= $extra_query;

        $result = $exam->query_result();
        // print_r($result);

        $exam->query = "
        SELECT * FROM user_table";

        $total_rows = $exam->total_rows();

        $data = array();
        
        foreach($result as $row)
        {
            $titles = array() ; 
            $sub_array = array();
            $sub_array[] = $row["user_name"];
            $sub_array[] = $row["user_email_address"];
            // for enrolled in 
            $exam->data = array(
                ":user_id" => $row['user_id']
            );
            // Get the exam each student is enrolled in 
            $exam->query = "SELECT o.online_exam_title FROM user_table as u INNER JOIN user_exam_enroll_table as e ON u.user_id = e.user_id INNER JOIN online_exam_table as o ON e.exam_id = o.online_exam_id WHERE u.user_id = :user_id;";
            $titles_rows = $exam->query_result();
                foreach($titles_rows as $title){
                    // print_r($title[0]['online_exam_title']);
                    $titles[] = $title['online_exam_title'];

            }
            $sub_array[] = $titles;
 
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

?>