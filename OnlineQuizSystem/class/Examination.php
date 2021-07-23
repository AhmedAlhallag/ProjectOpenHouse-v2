<?php 
class Examination{

    var $host; 
    var $username; 
    var $password; 
    var $database; 
    var $connect; 
    var $home_page; 
    var $query; 
    var $data; 
    var $statement; 
    var $filedata; 


    function __construct($sessionflag = true)
    {
		$this->startSesssion = $sessionflag; 
        $this->host = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->database = 'online_assessment';
        $this->home_page = 'http://localhost/OnlineQuizSystem/';
        $this->connect= new PDO("mysql:host=$this->host; dbname=$this->database", $this->username, $this->password);
        if ($sessionflag){
		session_start(); 
		}
        
        
    }

    function execute_query(){
        // prepared statement: binding all placeholders with variables using the prepare method
        $this->statement = $this->connect->prepare($this->query);// NOTE: statemnet is a PDO object (connect object), you can use 
        /// prepare on that object to prepare the query whether it's static or dynamuc (has placeholders)
        // this you have an execute; usually these 2 step happen consequentially
        // ; we just put the 2 of them in one method
        // in the execute, you send the actual arguments
        $this->statement->execute($this->data);// data variable is what we will be subsituting wil the query place holder when it 'executes'
    }

    function total_rows(){
        $this->execute_query();// this running, executes the query which gets stored and assigned into the statment variable 
        // thus this var will contain the returnning result from the query/table, we can use on it a built in PFO method called rowCount to count no of rows
        return $this->statement->rowCount();

    }

    function query_result()
	{
		$this->execute_query();
		return $this->statement->fetchAll();
	}

	function redirect($page)
	{
		header('location:'.$page.'');
		exit();
	}

	function admin_session_private()
	{
		// not used
		if(!isset($_SESSION['admin_id']))
		{
			$this->redirect('login.php');
            exit() ;

		}
	}

	function admin_session_public()
	{

		// works for both admin and user
		if(isset($_SESSION['admin_id']) or isset($_SESSION['user_id'])  )
		{
			$this->redirect('index.php');
            // header('location: '. $route) ; 

		}
	}

    
	function Is_exam_is_not_started($online_exam_id)
	{
		$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

		$exam_datetime = '';

		$this->data = array(
			':online_exam_id' => $online_exam_id
		);

		$this->query = "
		SELECT online_exam_datetime FROM online_exam_table
		WHERE online_exam_id = :online_exam_id
		";

		$result = $this->query_result();

		foreach($result as $row)
		{
			$exam_datetime = $row['online_exam_datetime'];
		}

		if($exam_datetime > $current_datetime)
		{
			return true;
		}
		return false;
	}

    
	function Is_allowed_add_question($exam_id)
	{
		$exam_question_limit = $this->Get_exam_question_limit($exam_id);

		$exam_total_question = $this->Get_exam_total_question($exam_id);

		if($exam_total_question >= $exam_question_limit)
		{
			// false is inside
			return false;
		}
		return true;
	}

    
	function Get_exam_question_limit($exam_id)
	{
		$this->data = array(
			':exam_id' => $exam_id
		) ; 
		$this->query = "
		SELECT total_question FROM online_exam_table
		WHERE online_exam_id = :exam_id";

		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['total_question'];
		}
	}

    
	function Get_exam_total_question($exam_id)
	{
		$this->data = array(
			':exam_id' => $exam_id
		) ; 
		$this->query = "
		SELECT question_id FROM question_table
		WHERE online_exam_id = :exam_id";

		return $this->total_rows();
	}

    function clean_data($data)
	{
	 	$data = trim($data);
	  	$data = stripslashes($data);
	  	$data = htmlspecialchars($data);
	  	return $data;
	}

	function execute_question_with_last_id()
	{
		$this->statement = $this->connect->prepare($this->query);

		$this->statement->execute($this->data);

		return $this->connect->lastInsertId();
	}

	function Get_exam_id($exam_code)
	{
		$this->data = array(
			":online_exam_code" => $exam_code
		);
		$this->query = "
		SELECT online_exam_id FROM online_exam_table
		WHERE online_exam_code = :online_exam_code
		";
		$result = $this->query_result();

		foreach($result as $row)
		{
			return $row['online_exam_id'];
		}
	}

	function user_session_private()
	{
		if(!isset($_SESSION['user_id']))
		{
			$this->redirect('user_login.php');
		}
	}

	function user_session_public()
	{
		if(isset($_SESSION['user_id']))
		{
			$this->redirect('index.php');
		}
	}

	function Fill_exam_list()
	{

		$this->query = "
		SELECT online_exam_id, online_exam_title
			FROM online_exam_table
			WHERE online_exam_status = 'Created'
			OR online_exam_status = 'Not Started Yet'
			OR online_exam_status = 'Available'
			ORDER BY online_exam_title ASC
		";
		$result = $this->query_result();
		$output = '';
		foreach($result as $row)
		{
			$output .= '<option value="'.$row["online_exam_id"].'">'.$row["online_exam_title"].'</option>';
		}
		return $output;
	}

	function If_user_already_enroll_exam($exam_id, $user_id)
	{

		$this->data = array(
			':exam_id' => $exam_id,
			':user_id' => $user_id
		);

		$this->query = "
		SELECT * FROM user_exam_enroll_table
		WHERE exam_id = :exam_id
		AND user_id = :user_id
		";
		if($this->total_rows() > 0)
		{
			return true;
		}
		return false;
	}

	function If_user_already_enroll_exam_in_any_exam($user_id)
	{
		$this->data = array(
			':user_id' => $user_id
		);

		$this->query = "
		SELECT * FROM user_exam_enroll_table
		WHERE user_id = :user_id";
		if($this->total_rows() > 0)
		{
			return true;
		}
		return false;
	}


	function Check_exam_status()
	{
		// $this->data = array(); 

		$ret_val = "unchanged"; 

		$this->query = "SELECT * FROM online_exam_table";

		$result = $this->query_result();

		$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

		foreach($result as $row)
		{
			$exam_start_time = $row["online_exam_datetime"];

			$duration = $row["online_exam_duration"] . ' minute';

			$exam_end_time = strtotime($exam_start_time . '+' . $duration);

			$exam_end_time = date('Y-m-d H:i:s', $exam_end_time);

			$view_exam = '';

			if($current_datetime >= $exam_start_time && $current_datetime <= $exam_end_time)
			{ // this should be checked periodically on exam.php (admin side) and user_enroll.php (user side)
				

				// if it has started then don't update anything 
				$this->data = array(
					':exam_id' => $row['online_exam_id']
				);

				$this->query = "
				SELECT online_exam_status
				FROM online_exam_table
				WHERE online_exam_id = :exam_id;";

				$result = $this->query_result();
				$exam_status = $result[0]['online_exam_status'];

				if ($exam_status == "Started"){
					// $ret_val = "Started";
					continue;
				}
				// ================ continue as useual ==============

				$this->data = array(
					':online_exam_status'	=>	'Available now',
					':exam_id' => $row['online_exam_id']
				);

				$this->query = "
				UPDATE online_exam_table
				SET online_exam_status = :online_exam_status
				WHERE online_exam_id = :exam_id";

				$this->execute_query();
				// $this->date = "" ; 
				$ret_val = "now_available";

			}
			else
			{
				if($current_datetime > $exam_end_time)
				{
					//exam completed
					$this->data = array(
						':online_exam_status'	=>	'Completed',
						':exam_id' => $row['online_exam_id']

					);

					$this->query = "
					UPDATE online_exam_table
					SET online_exam_status = :online_exam_status
					WHERE online_exam_id = :exam_id";

					$this->execute_query();
					$this->date = "" ; 

				} else if ($current_datetime < $exam_end_time) {

					// TO BE COMMENTED: This snippet just to test that the reset is disbaled while the exam has started 
						// if it has started then don't update anything 
						// $this->data = array(
						// 	':exam_id' => $row['online_exam_id']
						// );

						// $this->query = "
						// SELECT online_exam_status
						// FROM online_exam_table
						// WHERE online_exam_id = :exam_id;";

						// $result = $this->query_result();
						// $exam_status = $result[0]['online_exam_status'];

						// if ($exam_status == "Started"){
						// 	// $ret_val = "Started";
						// 	continue;
						// }

					//--------------------------------------------------------------
					// check if exam time is yet to come AND qeuestion are fully populated 
						if (!$this->Is_allowed_add_question(intval($row['online_exam_id']))){
						$this->data = array(
							':online_exam_status'	=>	'Not Started Yet',
							':online_exam_id' => $row['online_exam_id']
						);
						$this->query = "
						UPDATE online_exam_table
						SET online_exam_status = :online_exam_status
						WHERE online_exam_id = :online_exam_id
						";
	
						$this->execute_query();

					} else{
						$this->data = array(
							':online_exam_status'	=>	'Pending',
							':online_exam_id' => $row['online_exam_id']

						);
						$this->query = "
						UPDATE online_exam_table
						SET online_exam_status = :online_exam_status
						WHERE online_exam_id = :online_exam_id
						";
	
						$this->execute_query();


					}
				}
			}
		}
		return $ret_val;
	}

	function execute_query_with_last_id()
	{
		$this->statement = $this->connect->prepare($this->query);

		$this->statement->execute($this->data);

		return $this->connect->lastInsertId();
	}

	
	function Check_exam_status_v2($exam_id)
	{
		$ret_val = "unchanged"; 

		$this->data = array(
			':exam_id' => $exam_id

		) ;
		$this->query = "SELECT * FROM online_exam_table WHERE online_exam_id = :exam_id;";

		$result = $this->query_result();

		$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));

			$exam_start_time = $result[0]["online_exam_datetime"];

			$duration = $result[0]["online_exam_duration"] . ' minute';

			$exam_end_time = strtotime($exam_start_time . '+' . $duration);

			$exam_end_time = date('Y-m-d H:i:s', $exam_end_time);

			$view_exam = '';

			if($current_datetime >= $exam_start_time && $current_datetime <= $exam_end_time)
			{ // this should be checked periodically on exam.php (admin side) and user_enroll.php (user side)
				
				//check if exam was exam started; which will only be doable via pressing the button
				// if it has started then don't update anything 
				$this->data = array(
					':exam_id' => $exam_id
				);

				$this->query = "
				SELECT online_exam_status
				FROM online_exam_table
				WHERE online_exam_id = :exam_id;";

				$result = $this->query_result();
				$exam_status = $result[0]['online_exam_status'];

				if ($exam_status == "Started"){
					$ret_val = "Started";
					return $ret_val;
				}
				//==================== continue the usual check ==============
				$this->data = array(
					':online_exam_status'	=>	'Available now',
					':exam_id' => $exam_id
				);

				$this->query = "
				UPDATE online_exam_table
				SET online_exam_status = :online_exam_status
				WHERE online_exam_id = :exam_id;";

				$this->execute_query();

				$ret_val = "now_available";
			}
			else
			{
				if($current_datetime > $exam_end_time)
				{

					
					//exam completed
					$this->data = array(
						':online_exam_status'	=>	'Completed',
						':exam_id' => $exam_id

					);

					$this->query = "
					UPDATE online_exam_table
					SET online_exam_status = :online_exam_status
					WHERE online_exam_id = :exam_id;";

					$ret_val = "completed";


					$this->execute_query();
				} else if ($current_datetime < $exam_end_time) {

						// TO BE COMMENTED: This snippet just to test that the reset is disbaled while the exam has started 
						// $this->data = array(
						// 	':exam_id' => $exam_id
						// );

						// $this->query = "
						// SELECT online_exam_status
						// FROM online_exam_table
						// WHERE online_exam_id = :exam_id;";

						// $result = $this->query_result();
						// $exam_status = $result[0]['online_exam_status'];

						// if ($exam_status == "Started"){
						// 	$ret_val = "Started";
						// 	return $ret_val;
						// }

					//--------------------------------------------------------------


					// check if exam time is yet to come AND qeuestion are fully populated 
						if (!$this->Is_allowed_add_question(intval($exam_id))){
							// print_r($row['online_exam_title']);
						$this->data = array(
							':online_exam_status'	=>	'Not Started Yet',
							':online_exam_id' => $exam_id
						);
						$this->query = "
						UPDATE online_exam_table
						SET online_exam_status = :online_exam_status
						WHERE online_exam_id = :online_exam_id
						";
	
						$this->execute_query();
						$ret_val = "not_started";


					} else{
						$this->data = array(
							':online_exam_status'	=>	'Pending',
							':online_exam_id' => $exam_id

						);
						$this->query = "
						UPDATE online_exam_table
						SET online_exam_status = :online_exam_status
						WHERE online_exam_id = :online_exam_id
						";
	
						$this->execute_query();
						$ret_val = "pending";

					}
				}
			}

			return $ret_val;

		}

		function update_Avail_Start_button($exam_id){
			// NOTE: Reset should only reset the datetime time 
			// and it should work if the exam was available now, not started yet/created
			$ret_val = "" ; 
			$ret_val = $this->Check_exam_status_v2( $exam_id ) ;

			if  ($ret_val == "not_started") {
				return array('<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $exam_id . '" name="make_available" class="waves-effect waves-light btn  pink make_available">Make Available</a><br>',
				'<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $exam_id . '" name="force_start" class="waves-effect waves-light btn  pink force_start"  disabled>Force Start</a><br>', $ret_val,
				'<a  id="' . $exam_id . '" name="reset" class="waves-effect waves-light btn  #b0bec5 blue-grey lighten-3 reset">Reset</a><br>'); 
			
			} else if ($ret_val == "now_available") {
				return array('<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $exam_id . '" name="make_available" class="waves-effect waves-light btn  pink make_available"  disabled>Make Available</a><br>',
				'<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $exam_id . '" name="force_start" class="waves-effect waves-light btn  pink force_start">Force Start</a><br>', $ret_val, 
				'<a  id="' . $exam_id . '" name="reset" class="waves-effect waves-light btn  #b0bec5 blue-grey lighten-3 reset">Reset</a><br>'); 
				
			} else {
				
				return array('<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $exam_id . '" name="make_available" class="waves-effect waves-light btn pink make_available"  disabled>Make Available</a><br>', 
				'<a style="font-size:10px; padding-top:10px; padding-bottom:5px; line-height:15px" id="' . $exam_id . '" name="force_start" class="waves-effect waves-light btn pink force_start"  disabled>Force Start</a><br>',$ret_val,
				'<a  id="' . $exam_id . '" name="reset" class="waves-effect waves-light btn  #b0bec5 blue-grey lighten-3 reset disabled">Reset</a><br>'); 
			}

			return $ret_val; 

		}


		function Get_user_question_option($question_id, $user_id)
		{
			$this->query = "
			SELECT user_answer_option FROM user_exam_question_answer
			WHERE question_id = '".$question_id."'
			AND user_id = '".$user_id."'
			";
			$result = $this->query_result();
			foreach($result as $row)
			{
				return $row["user_answer_option"];
			}
		}
	
		function Get_question_right_answer_mark($exam_id)
		{
			$this->query = "
			SELECT marks_per_right_answer FROM online_exam_table
			WHERE online_exam_id = '".$exam_id."'
			";
	
			$result = $this->query_result();
	
			foreach($result as $row)
			{
				return $row['marks_per_right_answer'];
			}
		}
	
		function Get_question_wrong_answer_mark($exam_id)
		{
			$this->query = "
			SELECT marks_per_wrong_answer FROM online_exam_table
			WHERE online_exam_id = '".$exam_id."'
			";
	
			$result = $this->query_result();
	
			foreach($result as $row)
			{
				return $row['marks_per_wrong_answer'];
			}
		}
	
		function Get_question_answer_option($question_id)
		{
			$this->query = "
			SELECT answer_option FROM question_table
			WHERE question_id = '".$question_id."'
			";
	
			$result = $this->query_result();
	
			foreach($result as $row)
			{
				return $row['answer_option'];
			}
		}

		function Get_user_exam_status($exam_id, $user_id)
		{
			$this->data = array(
				":exam_id" => $exam_id,
				":user_id" => $user_id
			);
			$this->query = "
			SELECT attendance_status
			FROM user_exam_enroll_table
			WHERE exam_id = :exam_id
			AND user_id = :user_id
			";
			$result = $this->query_result();
			foreach($result as $row)
			{
				return $row["attendance_status"];
			}
		}
};
?>
