<?php
// examination  class
include('class/Examination.php');

$examObj = new Examination() ;
$current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));


    if ($_POST['action'] == 'default'){// default action: keyup email ajax request

            $email = trim($_POST['admin_email']) ;
            // echo $email;
            $examObj->query = "SELECT * from admin_table WHERE admin_email_address= '$email'"; 
            // $examObj->query = "INSERT INTO test (name) VALUES ('Mohamed')"; 
            $total_rows = $examObj->total_rows(); // inside of it it has execut_query() and rowCount()

            if ($total_rows == 0){
                // succeess
                // admin emaiul is non existant in db; go ahead and create it
                $output = ["status" => "available", 'elemId'=>'admin_email'] ; 
                // echo 'nahahaa';
                // json encode adds the assoictaice array to the object returned by the ajax (axios)
                //call (within the data obj (which contains the formData)) to the php page  
                echo json_encode($output);// echo fires it to console
            } else {
                $output = ["status" => "exists", 'elemId'=>'admin_email'] ; 
                echo json_encode($output);// echo fires it
            }
        } 
        
        else if ($_POST['action']=="register"){
            // print_r($_POST);

            // print_r('registering...');

            //============= check email first 
            $email = trim($_POST['admin_email']) ;
            // echo $email;
            $examObj->query = "SELECT * from admin_table WHERE admin_email_address= '$email'"; 
            // $examObj->query = "INSERT INTO test (name) VALUES ('Mohamed')"; 
            $total_rows = $examObj->total_rows(); // inside of it it has execut_query() and rowCount()

            if ($total_rows == 0){
                // succeess
                // admin emaiul is non existant in db; go ahead and create it
                // json encode adds the assoictaice array to the object returned by the ajax (axios)
                //call (within the data obj (which contains the formData)) to the php page  
                // echo json_encode($output);// echo fires it to console
                //=================== INSERT
                    $examObj->query = "
                    INSERT INTO admin_table 
                    (admin_email_address, admin_password, admin_verfication_code, admin_created_on,admin_type) 
                    VALUES 
                    (:admin_email_address, :admin_password, :admin_verification_code, :admin_created_on,:admin_type);
                    ";

                    $admin_ver_code = md5(rand());
                    $admin_email_address = $_POST['admin_email'];
                    $admin_pass = password_hash($_POST['comfirm_admin_pass'],PASSWORD_DEFAULT);
                    $examObj->data = [":admin_email_address" => $admin_email_address, ":admin_password" => $admin_pass, ":admin_verification_code"=>$admin_ver_code, ':admin_created_on'=>	$current_datetime, ':admin_type'=>	'sub_master'] ;


                    $examObj->execute_query();
                    $output = ["status" => "registered"] ;
                    // get that row--> to get id
                    $examObj->query = "SELECT * FROM admin_table WHERE admin_email_address= :admin_email_address;";
                    $examObj->data = [":admin_email_address" => $admin_email_address];
                    
                    $row = $examObj->query_result();
             
                    $_SESSION['admin_id'] = $row[0]['admin_id'];
                    setcookie("userId", $_SESSION['admin_id'], time() + (86400 * 30), "/"); // 86400 = 1 day
                    $_SESSION['type'] = $row[0]['type'];
                    setcookie("type", $_SESSION['type'], time() + (86400 * 30), "/"); // 86400 = 1 day
                    
                    $_SESSION['admin_email_address'] = $admin_email_address;


                    // $timezone_offset_minutes = intval($_REQUEST['timezone']);

                    $timezone_name = $_REQUEST['timezone'];

                    $_SESSION['timezone'] =  $timezone_name ; 

                    echo json_encode($output); 

            } else {
                $output = ["status" => "exists", 'elemId'=>'admin_email'] ; 
                echo json_encode($output);// echo fires it
            }
            //====================

            

            


        }
    



//ajax_action.php

// include('Examination.php');


// $exam = new Examination;


// if(isset($_POST['page']))
// {
// 	if($_POST['page'] == 'register')
// 	{
// 		if($_POST['action'] == 'check_email')
// 		{
// 			$exam->query = "
// 			SELECT * FROM admin_table 
// 			WHERE admin_email_address = '".trim($_POST["email"])."'
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