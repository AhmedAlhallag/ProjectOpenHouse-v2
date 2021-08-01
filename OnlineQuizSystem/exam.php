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
       /* .datepicker-modal {
     transform: scale(0.9) !important;
        }
        
 */
    .datepicker-calendar-container {
        transform: scale(0.8);
    }

    </style>
</head>
<body>


<?php 
    include('partials/nav.php');
    // $examObj->admin_session_public(); 
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

    // $currDT = $examObj->get_current_date_time($current_id, $type) ; 
    // $curr_date_time = $currDT[0]['curr_date_time']; 

    // $date = new DateTime("now", new DateTimeZone($current_timezone));
    // $curr_date_time = $date->format('Y-m-d H:i:s');
    $examObj->Check_exam_status($current_timezone);
?>    

<?php  if ($type != 'admin'){ ?>
       <div id="box" class="box displayNone">
         <div class="content">
           <span id="close" class="close" > <i class="fas fa-times close-icon"></i>  </span>
           <p> You Need to be logged in as an Admin first!</p>
         </div>
       </div>
     <?php } ?>

<div id="container" class="container">
    <div class="row">
        <div class="add-exam">
            <div class="col-md-9">
                <!-- ALEERTING MESSAGE For Added and Edited Exams -->
                <div id="message_operation_out"></div>

                <h3 class="panel-title">Online Exam List</h3>
			</div>
            
			<div class="col s12 m5">

            <!-- Modal Trigger -->
            <a id="add_button" class="waves-effect waves-light btn modal-trigger red" href="#modal1">Add Exam</a>
<!-- Note: in materlize; modal trigger button href should be the same id of the modal div -->
			</div>
            
        </div>
        <br> <br>
        <h2 class="header">Exams List</h2>    
			<table id="exam_data_table" class="responsive-table centered">
				<thead>
					<tr>
						<th>Exam Title</th>
						<th>Date & Time</th>    
						<th>Duration</th>
						<th>Total Question</th>
						<th>Right Answer Mark</th>
						<th>Wrong Answer Mark</th>
						<th>Status</th>
						<th>Add Questions</th>
						<th>Result</th>
						<th>Edit/Delete</th>
						<th>View Questions</th>
						<th>Make Available</th>
						<th>Force Start</th>
						<th>Reset Exam</th>
						<th>View Enrolled Students</th>
					</tr>
				</thead>
			</table>
		</div>


    <!-- Delete Modal Structure -->
    <div id="modal2" class="modal">
        <div class="modal-content">
        <h4>Delete Confirmation</h4>
        <p>Are you sure you want to delete this exam?</p>
        </div>
        <div class="modal-footer">
        <a href="#!" name="ok_button" id="ok_button" class=" modal-action modal-close waves-effect waves-green red btn-small">OK</a>

        <a href="#!" id="closebtn_del" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>
        <!-- Add/Edit Modal Structure -->

    <div id="modal1" class="modal">
    <div class="modal-content">
        <div class="row">
            
            <h4 class="modal-title" id="modal_title">Add Exam Details</h4>
            <!-- <span style="color:green" id="message_operation_success"></span> -->
                    <!-- ALEERTING MESSAGE -->
            <div id="message_operation"></div>



        </div>

            <!-- Modal Header -->
            <form action="" id="exam_form" method="post">
            <!-- Modal Body -->
        <div class="modal-body">
            
                <div class="row">
                    <div class="input-field col s12">
                        <label">Exam Title <span class="red-text">*</span></label>
                        <input type="text" name="online_exam_title" id="online_exam_title" class="" />
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <label">Exam Date <span class="red-text">*</span></label>
                        <input type="text" name="online_exam_date" id="online_exam_date" class="datepicker" readonly />
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <label">Exam Time <span class="red-text">*</span></label>
                        <input type="text" name="online_exam_time" id="online_exam_time" class="timepicker" readonly />
                    </div>
                </div>
                    
                <div class="row">
                        <label>Exam Duration <span class="red-text">*</span></label>
                        <br><br>
                            <div class="input-field col s12">
	                			<select name="online_exam_duration" id="online_exam_duration" class="dropdown">
	                				<option value="" disabled selected>Select</option>
	                				<option value="5">5 Minute</option>
	                				<option value="30">30 Minute</option>
	                				<option value="60">1 Hour</option>
	                				<option value="120">2 Hour</option>
	                				<option value="180">3 Hour</option>
	                			</select>
                            </div>
                    </div>

            			<div class="row">
              				<label>Total Question <span class="red-text">*</span></label>
                              <br><br>
                            <div class="input-field col s12">
	                			<select name="total_question" id="total_question" class="">
	                				<option value="" disabled selected>Select</option>
	                				<option value="5">5 Question</option>
	                				<option value="10">10 Question</option>
	                				<option value="25">25 Question</option>
	                				<option value="50">50 Question</option>
	                				<option value="100">100 Question</option>
	                				<option value="200">200 Question</option>
	                				<option value="300">300 Question</option>
	                			</select>
	                		</div>
            			</div>

            			<div class="row">
              				<label>Marks for Right Answer <span class="red-text">*</span></label>
                            <br><br>
                            <div class="input-field col s12">
	                			<select name="marks_per_right_answer" id="marks_per_right_answer" class="">
	                				<option value="" disabled selected>Select</option>
	                				<option value="1">+1 Mark</option>
	                				<option value="2">+2 Mark</option>
	                				<option value="3">+3 Mark</option>
	                				<option value="4">+4 Mark</option>
	                				<option value="5">+5 Mark</option>
	                			</select>
	                		</div>
            			</div>

            			<div class="row">
              				<label>Marks for Wrong Answer <span class="red-text">*</span></label>
                            <br><br>
                            <div class="input-field col s12">
	                			<select name="marks_per_wrong_answer" id="marks_per_wrong_answer" class="">
	                				<option value="" disabled selected>Select</option>
	                				<option value="0">0 Mark</option>
	                				<option value="1">-1 Mark</option>
	                				<option value="1.25">-1.25 Mark</option>
	                				<option value="1.50">-1.50 Mark</option>
	                				<option value="2">-2 Mark</option>
	                			</select>
	                		</div>
            			</div>




            </div>
            <!-- Modal footer -->

            <div class="modal-footer">
                <input type="hidden" name="datetime" id="datetime" value="">
                <input type="hidden" name="online_exam_id" id="online_exam_id" />
                
                <input type="hidden" name="page" value="exam" />
                
                <input type="hidden" name="action" id="action" value="Add" />
                
                <input type="submit" name="button_action" id="button_action" class="btn btn-small green" value="Add" />
                
                
                <a href="#!" id="closebtn" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>

        </form>
        </div>
    </div>

    <!-- ====================== Add Question Modal =========================== -->
    <!-- NO! DONT DRY! use the same modal and insert modular values -->
    <div id="questionModal" class="modal">
    <div class="modal-content">
        <div class="row">
            
            <h4 class="modal-title" id="question_modal_title">Add Question Details</h4>
            <!-- <span style="color:green" id="message_operation_success"></span> -->
                    <!-- ALEERTING MESSAGE -->
            <div id="question_message_operation"></div>



        </div>

            <!-- Modal Header -->
            <form action="" id="question_form" method="post">
            <!-- Modal Body -->
        <div class="modal-body">
            
                <div class="row">
                    <div class="input-field col s12">
                        <label>Question Title <span class="red-text">*</span></label>
                        <input type="text" name="question_title" id="question_title" class="" />
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <label>Option 1 <span class="red-text">*</span></label>
                        <input type="text"  name="option_title_1" id="option_title_1"   />
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <label>Option 2  <span class="red-text">*</span></label>
                        <input type="text" name="option_title_2" id="option_title_2"/>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <label>Option 3  <span class="red-text">*</span></label>
                        <input type="text" name="option_title_3" id="option_title_3"/>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <label>Option 4  <span class="red-text">*</span></label>
                        <input type="text" name="option_title_4" id="option_title_4"/>
                    </div>
                </div>
                    
                <div class="row">
                        <label>Answer <span class="red-text">*</span></label>
                        <br><br>
                            <div class="input-field col s12">
	                			<select name="answer_option" id="answer_option" class="dropdown">
	                				<option value="" disabled selected>Select</option>
	                				<option value="1">1 Option</option>
	                				<option value="2">2 Option</option>
	                				<option value="3">3 Option</option>
	                				<option value="4">4 Option</option>
	                			</select>
                            </div>
                    </div>           
            </div>
            <!-- Modal footer -->

            <div class="modal-footer">
            <input type="hidden" name="question_id" id="question_id" />

            <input type="hidden" name="online_exam_id" id="hidden_online_exam_id" />

            <input type="hidden" name="page" value="question" />

            <input type="hidden" name="question_action" id="question_action" value="Add"/>

            <input  type="submit" name="question_button_action" id="question_button_action" class="btn btn-small green" value="Add" />
                <a href="#!" id="qclosebtn" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>

        </form>
        </div>
    </div>


    
</div>



<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


    <script src="js/modalBox.js">  </script>
      <script src="js/hammenu.js">  </script>
            <script src="js/clickFAB.js" type="text/javascript">      </script>

      <script src="js/add_exam_ajax.js">  </script>
      <script>
          // for main modal box 
          document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems,{dismissible:false});
            $('select').formSelect();
            // $(".modal-content").animate({ scrollTop: "0px" }, 300)


        });
    // date time picker 
	var date = new Date();

    date.setDate(date.getDate());
    // initialize date  picker 
    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems);
  });

    // initilialize time picker 
    document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.timepicker');
    var instances = M.Timepicker.init(elems);
  });

  var currYear = (new Date()).getFullYear();
  var currMonth = (new Date()).getMonth();
  var currDay = (new Date()).getDay();

$(document).ready(function() {
  $(".datepicker").datepicker({
    defaultDate: new Date(currYear,currMonth,currDay),
    setDefaultDate: new Date(currYear,currMonth,currDay),
    yearRange: [1928, currYear+3],
    format: "yyyy-mm-dd"    
  });







});

                
      </script>
</body>
</html>