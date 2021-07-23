<?php

include('class/Examination.php');


$exam = new Examination;


	if($_POST['page'] == 'view_enrolled_students')
	{
		if($_POST['action'] == 'fetch')
		{
			$output = array();

			$exam_id = $exam->Get_exam_id($_POST['code']);
            $exam->data = array(
                ':exam_id' => $exam_id
            );

			$exam->query = "
			SELECT * FROM user_exam_enroll_table
			INNER JOIN user_table
			ON user_table.user_id = user_exam_enroll_table.user_id
			WHERE user_exam_enroll_table.exam_id = :exam_id
			AND (
			";

			if(isset($_POST['search']['value']))
			{
				$exam->query .= '
				user_table.user_name LIKE "%'.$_POST["search"]["value"].'%"
				';
			}
			$exam->query .= ') ';

			if(isset($_POST['order']))
			{
                $colNo = intval($_POST['order'][0]['column'])+1; 

				$exam->query .= 'ORDER BY '. $colNo.' '.$_POST['order']['0']['dir'].' ';
			}
			else
			{
				$exam->query .= 'ORDER BY user_exam_enroll_table.user_id ASC ';
			}

			$extra_query = '';

			if($_POST['length'] != -1)
			{
				$extra_query = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
			}

			$filtered_rows = $exam->total_rows();

			$exam->query .= $extra_query;

			$result = $exam->query_result();

            $exam->data = array(
                ':exam_id' => $exam_id
            );

			$exam->query = "
			SELECT * FROM user_exam_enroll_table
			INNER JOIN user_table
			ON user_table.user_id = user_exam_enroll_table.user_id
			WHERE user_exam_enroll_table.exam_id = :exam_id
			";

			$total_rows = $exam->total_rows();

			$data = array();

			foreach($result as $row)
			{
				$sub_array = array();
				$sub_array[] = $row["user_name"];
				$sub_array[] = $row["user_email_address"];

				$result = '';

				if($exam->Check_exam_status_v2($exam_id) == 'completed')
				{
					$result = '<a href="result_per_user_list.php?code='.$_POST['code'].'&id='.$row['user_id'].'" class="btn-small" target="_blank">Result</a>';
				}
				$sub_array[] = $result;

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