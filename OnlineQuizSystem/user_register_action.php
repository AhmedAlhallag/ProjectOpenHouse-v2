<?php
// examination  class
include('class/Examination.php');

$examObj = new Examination() ;
$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));



    if ($_POST['action'] == 'email_check'){// default action: keyup email ajax request

            $email = trim($_POST['user_email']) ;

            $examObj->query = "SELECT * from user_table WHERE user_email_address= '$email'"; 

            $total_rows = $examObj->total_rows(); // inside of it it has execut_query() and rowCount()

            if ($total_rows == 0){
                // succeess
                // user emaiul is non existant in db; go ahead and create it
                $output = ["status" => "available", 'elemId'=>'user_email'] ; 
                // echo 'nahahaa';
                // json encode adds the assoictaice array to the object returned by the ajax (axios)
                //call (within the data obj (which contains the formData)) to the php page  
                echo json_encode($output);// echo fires it to console
            } else {
                $output = ["status" => "exists", 'elemId'=>'user_email'] ; 
                echo json_encode($output);// echo fires it
            }
        } 
        else if ($_POST['action'] == 'username_check'){// default action: keyup email ajax request

            $user_name = trim($_POST['user_name']) ;
            // echo $email;
            $examObj->query = "SELECT * from user_table WHERE user_name= '$user_name'"; 
            // $examObj->query = "INSERT INTO test (name) VALUES ('Mohamed')"; 
            $total_rows = $examObj->total_rows(); // inside of it it has execut_query() and rowCount()

            if ($total_rows == 0){
                // succeess
                // user emaiul is non existant in db; go ahead and create it
                $output = ["status" => "available", 'elemId'=>'username'] ; 
                // echo 'nahahaa';
                // json encode adds the assoictaice array to the object returned by the ajax (axios)
                //call (within the data obj (which contains the formData)) to the php page  
                echo json_encode($output);// echo fires it to console
            } else {
                $output = ["status" => "exists", 'elemId'=>'username'] ; 
                echo json_encode($output);// echo fires it
            }
        } 
        
        else if ($_POST['action']=="register"){
            // print_r('registering...');

            //============= check email first 
            $user_email_address = $_POST['user_email'];
                    // $email = trim($_POST['user_email']) ;
            $user_name = trim($_POST['user_name']) ;
            // echo $email;
            // check email exists

            $examObj->query = "SELECT * from user_table WHERE user_email_address= '$user_email_address'"; 
            // $examObj->query = "INSERT INTO test (name) VALUES ('Mohamed')"; 
            $total_rows = $examObj->total_rows(); // inside of it it has execut_query() and rowCount()
            
            // check usernam exists
            $examObj->query = "SELECT * from user_table WHERE user_name='$user_name'";
            $total_rows_2 = $examObj->total_rows();



            if ($total_rows == 0 and $total_rows_2 == 0){
                // succeess
                // user emaiul is non existant in db; go ahead and create it
                // json encode adds the assoictaice array to the object returned by the ajax (axios)
                //call (within the data obj (which contains the formData)) to the php page  
                // echo json_encode($output);// echo fires it to console
                //=================== INSERT
                    $examObj->query = "
                    INSERT INTO user_table 
                    (user_email_address, user_password, user_verfication_code, user_name, user_created_on) 
                    VALUES 
                    (:user_email_address, :user_password, :user_verification_code,:user_name, :user_created_on);
                    ";

                    $user_ver_code = md5(rand());
                    // $user_email_address = $_POST['user_email'];
                    // $user_name =  $_POST['user_name'];
                    $user_pass = password_hash($_POST['comfirm_user_pass'],PASSWORD_DEFAULT);
                    $examObj->data = [":user_email_address" => $user_email_address, ":user_password" => $user_pass, ":user_verification_code"=>$user_ver_code, ':user_name' => $user_name ,':user_created_on'=>	$current_datetime] ;
                    
                    // get last inserted row's user id ==> wont work cuz we need the type as well
                    // $lastInserted_id = $examObj->execute_question_with_last_id(); 

                    $examObj->execute_query();
                    $output = ["status" => "registered"] ;
                    // get that row--> to get id
                    $examObj->query = "SELECT * FROM user_table WHERE user_email_address= :user_email_address;";
                    $examObj->data = [":user_email_address" => $user_email_address];
                    
                    $row = $examObj->query_result();
             
                    $_SESSION['user_id'] = $row[0]['user_id'];
                    setcookie("userId", $_SESSION['user_id'], time() + (86400 * 30), "/"); // 86400 = 1 day
                    $_SESSION['type'] = $row[0]['type'];
                    setcookie("type", $_SESSION['type'], time() + (86400 * 30), "/"); // 86400 = 1 day
                    
                    
                    $_SESSION['username'] = $row[0]['user_name'];
                    $_SESSION['user_email_address'] = $user_email_address;
                    echo json_encode($output); 

            } else if ($total_rows != 0) {
                // email exists 
                $output = ["status" => "exists", 'elem'=>'email'] ; 
                echo json_encode($output);// echo fires it
            
            } else if ($total_rows_2 != 0) {
                $output = ["status" => "exists", 'elem'=>'username'] ; 
                echo json_encode($output);// echo fires it

            } else {
                $output = ["status" => "exists", 'elem'=>'email_and_username'] ; 
                echo json_encode($output);// echo fires it

            }
        }


// ajax_action.php

// include('Examination.php');


// $exam = new Examination;


// if(isset($_POST['page']))
// {
// 	if($_POST['page'] == 'register')
// 	{
// 		if($_POST['action'] == 'check_email')
// 		{
// 			$exam->query = "
// 			SELECT * FROM user_table 
// 			WHERE user_email_address = '".trim($_POST["email"])."'
// 			";

// 			$total_row = $exam->total_rows();

// 			if($total_row == 0)
// 			{
// 				$output = array(
// 					'success'	=>	true
// 				);

// 				echo json_encode($output);
// 			}
// 		}
//     }
// }




?>