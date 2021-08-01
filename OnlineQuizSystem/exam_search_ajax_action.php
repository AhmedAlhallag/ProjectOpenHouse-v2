<?php 

include('class/Examination.php');


$exam = new Examination;


if ($_POST['action'] == 'fetch'){
    // check the valeu of search 

    // $search_val = $_POST['search']['value'];
    // $out = ['val' => $search_val] ; 
    // echo json_encode($out);

    $search_val = "" ;

    $output = array();

    // if ($_SESSION["admin_id"]){

			$exam->query = "
			SELECT * FROM online_exam_table
			WHERE admin_id = '".$_SESSION["admin_id"]."'
			AND (
			";


			if(isset($_POST['search']['value']))
			{
                $search_val = $_POST['search']['value'];

				$exam->query .= 'online_exam_title LIKE "%'.$_POST["search"]["value"].'%" ';

				$exam->query .= 'OR online_exam_datetime LIKE "%'.$_POST["search"]["value"].'%" ';

				$exam->query .= 'OR online_exam_duration LIKE "%'.$_POST["search"]["value"].'%" ';

				$exam->query .= 'OR total_question LIKE "%'.$_POST["search"]["value"].'%" ';

				$exam->query .= 'OR marks_per_right_answer LIKE "%'.$_POST["search"]["value"].'%" ';

				$exam->query .= 'OR marks_per_wrong_answer LIKE "%'.$_POST["search"]["value"].'%" ';

				$exam->query .= 'OR online_exam_status LIKE "%'.$_POST["search"]["value"].'%" ';
			}

			$exam->query .= ')';

            if(isset($_POST['order']))
			{
				$colNo = intval($_POST['order'][0]['column'])+1; 

				$exam->query .= 'ORDER BY '.$colNo.' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$exam->query .= 'ORDER BY online_exam_id DESC ';
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
			SELECT * FROM online_exam_table
			WHERE admin_id = '".$_SESSION["admin_id"]."'
			";

			$total_rows = $exam->total_rows();

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();
				$sub_array[] = html_entity_decode($row['online_exam_title']);
				$sub_array[] = $row['online_exam_datetime'];
				$sub_array[] = $row['online_exam_duration'] . ' Minute';
				$sub_array[] = $row['total_question'] . ' Question';
				$sub_array[] = $row['marks_per_right_answer'] . ' Mark';
				if ($row['marks_per_wrong_answer'] == 0){
					
					$sub_array[] =  $row['marks_per_wrong_answer'] . ' Mark';
				} else {
				$sub_array[] = '-' . $row['marks_per_wrong_answer'] . ' Mark';
				}
				$status = '';
				$edit_button = '';
				$delete_button = '';
				$question_button = '';
				$result_button = '';

				if($row['online_exam_status'] == 'Pending')
				{
					$status = '<span class="new badge blue badges">Pending</span>';
				}				
				
				if($row['online_exam_status'] == 'Available now')
				{
					$status = '<span class="new badge light-blue badges">Available</span>';
				}

				if($row['online_exam_status'] == 'Created')
				{
					$status = '<span class="new badge pink badges">Created</span>';
				}

				if($row['online_exam_status'] == 'Started')
				{
					$status = '<span class="new badge red badges">Started</span>';
				}

				if($row['online_exam_status'] == 'Completed')
				{
					$status = '<span class="new badge green badges">Completed</span>';
				}

				if($row['online_exam_status'] == 'Not Started Yet')
				{
					$status = '<span style="font-size:11px; line-height:9px !important; padding-top:3px; padding-bottom:22px" class="badge yellow">Not Started Yet</span>';
				}
				// make available should be available only if : it has a status of created or not started yet


				if($exam->Is_exam_is_not_started($row["online_exam_id"]))
				{
					// <a id="add_button" class="waves-effect waves-light btn modal-trigger red" href="#modal1">Add Exam</a>

					$edit_button = 
					'<a id="' . $row['online_exam_id'] . '" name="edit" class="waves-effect waves-light btn-small modal-trigger blue edit" href="#modal1">Edit</a><br>'; 

					// <button type="button" name="edit" class="btn-small teal" id="'.$row['online_exam_id'].'">Edit</button><br>
					// ';

					$delete_button = '<br><a id="' . $row['online_exam_id'] . '" name="delete" class="waves-effect waves-light btn-small modal-trigger red delete" href="#modal2">Delete</a>'; 

				}
				else
				{
					// check status 
					if ($row['online_exam_status'] == "Started"){
					$result_button = '<a href="exam_result.php?code='. $row["online_exam_code"].'" class="btn-small red" disabled>Result</a>';
				} else {
						$result_button = '<a href="final_results.php?code='. $row["online_exam_code"].'" class="btn-small red">Result</a>';

					}
				}

				if($exam->Is_allowed_add_question($row['online_exam_id']))
				{
					// $question_button = '
					// <button type="button" name="add_question" class="btn-small orange" id="'. $row['online_exam_id'].'">Add Question</button>
					// ';
					$question_button = '<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $row['online_exam_id'] . '" name="add_question" class="waves-effect waves-light btn modal-trigger orange add_question" href="#questionModal">Add Question</a><br>'; 

				}
				else
				{
					$question_button = '<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $row['online_exam_id'] . '" name="add_question" class="waves-effect waves-light btn modal-trigger orange add_question" href="#questionModal" disabled>Add Question</a><br>'; 
					// change status on client side and insert into table

				}

				$make_available_button = $exam->update_Avail_Start_button($row['online_exam_id'], $_POST['timezone'])[0];
				$force_start_button = $exam->update_Avail_Start_button($row['online_exam_id'],$_POST['timezone'])[1];
				$reset_button = $exam->update_Avail_Start_button($row['online_exam_id'],$_POST['timezone'])[3];

				// as long as the exam is not started; the reset button will be available
				// if it had started, this it should be disabled 
				

				

				$sub_array[] = $status;

				$sub_array[] = $question_button;

				$sub_array[] = $result_button;

				$sub_array[] = $edit_button . ' ' . $delete_button;
				
				$view_question_button = '<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $row['online_exam_id'] . '" name="view_question" class="waves-effect waves-light btn modal-trigger black view_question" href="question.php?code='. $row['online_exam_code'].'&examid=' . $row['online_exam_id'] . '">View Question</a><br>'; 

				$sub_array[] =  $view_question_button ; 
				$sub_array[] =  $make_available_button; 
				$sub_array[] =  $force_start_button ; 
				$sub_array[] =  $reset_button ; 
				$sub_array[] = '<a href="view_enrolled_students.php?code=' . $row['online_exam_code'] . '" class="btn-small">View</a>'
				; 

				$data[] = $sub_array;
			}

			// $output = array(
			// 	"draw"				=>	intval($_POST["draw"]),
			// 	"recordsTotal"		=>	$total_rows,
			// 	"recordsFiltered"	=>	$filtered_rows,
			// 	"data"				=>	$data
			// );

			// print_r($data);
            $output = array(
				"draw"				=>	intval($_POST["draw"]),
				"recordsTotal"		=>	$total_rows,
				"recordsFiltered"	=>	$filtered_rows,
				"data"				=>	$data,
                "search_val" => $search_val,
                "id" => $_SESSION['admin_id']
			);

			echo json_encode($output);

        // } 
        // else {
        //     $output = array(
		// 		"draw"				=>	intval($_POST["draw"]),
		// 		"recordsTotal"		=>	$total_rows,
		// 		"recordsFiltered"	=>	$filtered_rows,
		// 		"data"				=>	$data,
        //         "search_val" => $search_val,
        //         "id" => $_SESSION['admin_id']
		// 	);

		// 	echo json_encode($output);


        // }



}

?>