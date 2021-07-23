<?php
include('class/Examination.php');

$examObj = new Examination() ;

if ($_POST['action'] == 'login'){
    $email = $_POST['admin_email'];
    $examObj->query = "SELECT * FROM admin_table WHERE admin_email_address='$email';";
    $rowCount = $examObj->total_rows();

    if ($rowCount>0){
        // existant:success
        # get result 
        $row = $examObj->query_result();
        // print_r($row);
        // print_r($row[0]['admin_id']);
        $pass = $row[0]['admin_password'];
        
        # check user password
        if (password_verify($_POST['admin_pass'],$pass)){
            // if correct: log him in 
            $debug = ['status'=>'success', 'error' =>""];
            // save session id, email and type 
            $_SESSION['admin_id'] = $row[0]['admin_id'];
            $_SESSION['type'] = $row[0]['type'];
            setcookie("userId", $_SESSION['admin_id'], time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie("type", $_SESSION['type'], time() + (86400 * 30), "/"); // 86400 = 1 day

            $_SESSION['admin_email_address'] = $row[0]['admin_email_address'];
            echo json_encode($debug);
            

        } else {
            // password is incorrect 
            $debug = ['status'=>'failure', 'error' =>"password"];
            echo json_encode($debug);

        }



        // password_verify()
        

    } else {
        // not registered: faild
        $debug = ['status'=>'failure', 'error' => "not registered"];
        echo json_encode($debug);


    }


}



?>