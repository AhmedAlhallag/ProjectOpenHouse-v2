<?php

// $current_datetime = date("Y-m-d") . ' ' . date("H:i:s", STRTOTIME(date('h:i:sa')));
include('class/Examination.php');
$exam = new Examination;


$output = array() ; 
if (isset($_POST)){
    if ($_REQUEST['action'] == 'getDate'){


        // $exam -> query = "
        // UPDATE date_time
        // SET 
        // online_exam_datetime = :curr_datetime
        // WHERE id = 1
        // ";

        // check user type 
        $type = '' ; 
        $current_id = '' ; 
        if (isset($_SESSION['type'])) {
            $type = $_SESSION['type'];

                if ($type == "admin") {
                    $current_id = $_SESSION['admin_id'];
                }
                else if ($type == "user") {
                    $current_id = $_SESSION['user_id'];

                    }
        } 

        if ($type == 'admin'){

            $exam-> data = array(
                ':admin_id' => $current_id
            );
            $exam -> query = "
            SELECT * FROM `date_time_admins` WHERE adminId = :admin_id
            "; 


            $rowCount = $exam->total_rows();
        // print_r($rowCount);

            if ($rowCount>0){
                // record exists

                $exam-> data = array(
                    ':admin_id' => $current_id,
                    ':curr_date_time' => $_REQUEST['curr_datetime']
                );

                $exam->query = "
                UPDATE date_time_admins
                SET curr_date_time = :curr_date_time
                WHERE adminId = :admin_id
                ";
                $exam->execute_query();
                $output = ['status'=> 'success','details'=>'admin date time updated','date'=> $_REQUEST['curr_datetime']]; 


            } else {
                // record does not exists
                $exam-> data = array(
                ':admin_id' => $current_id,
                ':curr_date_time' => $_REQUEST['curr_datetime']
                );
                // print_r($current_id);
                // print_r($type);
                // print_r($_REQUEST['curr_datetime']);
                $exam->query = "
                INSERT INTO date_time_admins 
                (adminId, curr_date_time) 
                VALUES 
                (:admin_id, :curr_date_time);
                ";

                $exam->execute_query(); 

                $output = ['status'=> 'success','details'=>'admin date time inserted','date'=> $_REQUEST['curr_datetime']]; 

            }
        } else if ($type == "user"){

            $exam-> data = array(
                ':user_id' => $current_id
            );
            $exam -> query = "
            SELECT * FROM `date_time_users` WHERE userId = :user_id
            "; 

            $rowCount = $exam->total_rows();

            if ($rowCount>0){
                // exisst? update

                $exam-> data = array(
                    ':user_id' => $current_id,
                    ':curr_date_time' => $_REQUEST['curr_datetime']
                );

                $exam->query = "
                UPDATE date_time_users 
                SET curr_date_time = :curr_date_time
                WHERE userId = :user_id 
                ";
                $exam->execute_query();
                $output = ['status'=> 'success','details'=>'user date time updated','date'=> $_REQUEST['curr_datetime']]; 



            }
            else {

            // if there's not, then insert 
            
                $exam-> data = array(
                    ':user_id' => $current_id,
                    ':curr_date_time' => $_REQUEST['curr_datetime'],
                );
                $exam->query = "
                INSERT INTO date_time_users
                (userId, curr_date_time) 
                VALUES 
                (:user_id, :curr_date_time);
                ";
                $exam->execute_query();
                $output = ['status'=> 'success','details'=>'user date time inserted','date'=> $_REQUEST['curr_datetime']]; 

            }
        
        }


        echo json_encode($output);

    }

  
}
?>