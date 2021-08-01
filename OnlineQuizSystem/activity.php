<!DOCTYPE html>
<html lang="en">
<head>
        <link rel="icon" href="data:;base64,iVBORw0KGgo=">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Staatliches&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@600&display=swap" rel="stylesheet">

    <!-- <script src="https://code.jquery.com/jquery-3.1.0.min.js"></script> -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css"> -->
<title>Document</title>
    <style>

    .header { 
        justify-content:left;

    }
    .dropdown-menu {
        max-height: 100px;
        overflow: scroll;
    }
    .badges{
        position: relative;
        top: 11px;
    } 

    /* custom Modal box css  */
    .readonly-class{
         background-color: rgba(224, 224, 224, 0.49)
       }
    .box {
        background-color: rgba(78, 78, 78, 0.4);
        position: fixed;
        overflow: hidden;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        z-index: 1
    }
    .content{
        margin: 11% auto;
        padding: 20px

    }
    .box .content p{
        font-size: 60px;
        font-family: "Lato";
        text-align: center;
        font-weight: 600 ;
        color:#fff;
        width: 80%;
        padding-left: 10%
    }
    .close-icon{
        color: #fff;
        font-size: 40px;
    }

    .box .content .close{
        float: right;
        padding-right: 5%
    }
    .blur {
        filter: blur(3px) !important;
    }
    .box .content .close:hover {
        cursor: pointer;
    }
    .displayBlock{
        display: block;
    }
    .datepicker-calendar-container {
        transform: scale(0.8);
    }

    .titlee{
        padding-left:20px !important
    }
    </style>
</head>
<body>
<?php 
    include('partials/nav.php');
    // $examObj->admin_session_public();  
    $newExamObj = new Examination(false);
?> 


          <div class="fixed-action-btn click-to-toggle">
            <a class="btn-floating btn-large red">
              <i class="large material-icons">mode_edit</i>
            </a>
            <ul>
            <li><a class="btn-floating red circ" href="user_login.php">Login</a></li>
              <li><a class="btn-floating green circ" href="https://tkh.edu.eg">TKH</a></li>
              <li><a class="btn-floating blue circ" href="index.php">Home</a></li>
            </ul>
          </div>

<!-- <script>

function sendDataToPage(uri, form, config) {
    var formdata = "";
    if (form) {
        formdata = new FormData(form);
    }
    let api = axios.create({ basicurl: 'http://52.7.174.33:80' });
    return api.post(uri, formdata, config);
}

    (async function () {


        var curr_datetime = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');

        let axioxParams = {
            params: {
                action: 'getDate',
                curr_datetime: curr_datetime
            }
        };

        await sendDataToPage('/rec_date.php', "", axioxParams)
            .then(data => {
                let status = data.data.status;
                if (status == "success") {
                    console.log(data.data);
                    // document.cookie = `timestamp=${data.data.date}`;
                    console.log('done');

                    // document.getElementById('datee').value = data.data.date;
                }
            })
            .catch(err => {
                console.log(err);
            })

    })();
    
</script> -->


<?php

    // $date = new DateTime("now", new DateTimeZone($current_timezone));
    // $curr_date_time = $date->format('Y-m-d H:i:s');
    $examObj->Check_exam_status($current_timezone);
 

?>    


<?php  if ($type == "user"){ ?>
    <div id="container" class="container">
        <br><br>
        <h4>Pick the available activity:</h4>
        <br> <br>
        <div class="row">
            <div class="col m3"></div>
            <div class="col m6"></div>
            <select name="exam_list" id="exam_list">
                <option value="" selected disabled>Select Activity</option>
                <?php 
                echo $examObj->Fill_exam_list();
                ?>
            </select>
            <br> <br>
            <span id="exam_details"></span>
        </div>

        <a  href="enroll_exam.php">View Enrolled Activities</a>


    </div>

    <?php } else if ($type == "admin") { ?>

            <div id="container" class="container">
                <br><br>
                <span>Admins cannot enroll into activities.</span> <br>
            </div>

    <?php } else { ?>
        
        <div id="box" class="box displayNone">
            <div class="content">
                <span id="close" class="close" > <i class="fas fa-times close-icon"></i>  </span>
                <p> You Need to be logged in first!</p>
            </div>
        </div>
        <div id="container" class="container">
       </div>

    <?php } ?>




<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


    <script src="js/modalBox.js">  </script>
      <script src="js/hammenu.js">  </script>
            <script src="js/clickFAB.js" type="text/javascript">      </script>

      <!-- <script src="js/user_exam.js"></script> -->

      <script>

    $(document).ready(()=>{

        $('select').formSelect();
            var exam_id = "" ; 

        $('#exam_list').change(async()=>{
            exam_id = $('#exam_list').val();
            $('#exam_list').attr('required',"required");
            let axioxParams =  {params: {
                page:"activity",
                action: "fetch_exam",
                exam_id:exam_id
                }} ; 

            await sendDataToPage('/user_ajax_action.php',"",axioxParams)
            .then(data=>{
                // console.log(data.data);
                $("#exam_details").html(data.data)
            });
            
        })

        // enroll action
        $(document).on('click', '#enroll_button', function(){
				exam_id = $('#enroll_button').data('exam_id');
				$.ajax({
					url:"user_ajax_action.php",
					method:"POST",
					data:{action:'enroll_exam', page:'activity', exam_id:exam_id},
					beforeSend:function()
					{
						$('#enroll_button').attr('disabled', 'disabled');
						$('#enroll_button').text('please wait');
					},
					success:function()
					{
						$('#enroll_button').attr('disabled', false);
						$('#enroll_button').removeClass('btn-warning');
						$('#enroll_button').addClass('btn-success');
						$('#enroll_button').text('Enroll success');
                        // $('#enroll_button').attr('id', "success");
                        setTimeout(()=>{
    						$('#enroll_button').attr('disabled', true);
                            $('#enroll_button').after(' &nbsp; <a  class="btn blue" href="enroll_exam.php">View Enrolled Activities</a>');
                           
                        },100);


					}
				});
			});



    });

//===================== Some functions =================

        function sendDataToPage(uri,form){
                // probably a code smell having a default argumengt... REFACTOR
                let formdata = new FormData(form); 
                let api = axios.create({basicurl:'http://52.7.174.33:80'});
                return api.post(uri,formdata);  
            }


        function sendDataToPage(uri,form,config){
            var formdata = "";  
            if (form){
                formdata = new FormData(form); 
            }
            let api = axios.create({basicurl:'http://52.7.174.33:80'});
            return api.post(uri,formdata,config );  

        }

      </script>

      </body>
</html>