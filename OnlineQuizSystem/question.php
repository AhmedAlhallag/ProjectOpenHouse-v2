
<!DOCTYPE html>
<html lang="en">
<head>
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
    <title>Questions List</title>
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
    // include('../partials/nav.php');
    include('partials/nav.php');

    // $examObj->admin_session_public();  
    $code = $_GET['code'] ?? ""; 
    $exam_title="" ; 

    if (!empty($code)){
        $examid = $_GET['examid']; 
        $examObj->query = "SELECT * FROM online_exam_table WHERE online_exam_id = $examid;";
        $result  = $examObj->query_result();
        // print_r($result);
        $exam_title = $result[0]['online_exam_title'];
        // print_r($exam_title);

    }

?>    
<!-- 
    <?php  if (empty($current_id)){ ?>
       <div id="box" class="box displayNone">
         <div class="content">
           <span id="close" class="close" > <i class="fas fa-times close-icon"></i>  </span>
           <p> You Need to be an Admin and logged in first!</p>
         </div>
       </div>
     <?php } ?> -->

     <?php  if ($type != 'admin'){ ?>
       <div id="box" class="box displayNone">
         <div class="content">
           <span id="close" class="close" > <i class="fas fa-times close-icon"></i>  </span>
           <p> You Need to be logged in as an Admin first!</p>
         </div>
       </div>
     <?php } ?>

     <nav>
    <div class="nav-wrapper #ef6c00 orange darken-3">
      <div class="col s12">
        <a href="exam.php" class="breadcrumb">Exam List</a>
        <?php if ($exam_title){ ?>
        <a style="font-weight:bold" class="breadcrumb" disabled> <?php echo $exam_title ?> </a>
        <?php } ?>
        <a aria-current="page" class="breadcrumb">Questions List</a>
      </div>
    </div>
  </nav>
        
  <div id="container" class="container">
    <?php if (empty($code)){  ?> 
        <h4>No question was selected.</h2>
    <?php } else { ?>



<div class="row">
            <div class="col-md-9">
                <!-- ALEERTING MESSAGE For Added and Edited Exams -->
                <div id="message_operation_out"></div>

			</div>
            

            <!-- Modal Trigger -->
            <!-- <a id="add_button" class="waves-effect waves-light btn modal-trigger red" href="#modal1">Add Exam</a> -->
            <!-- Note: in materlize; modal trigger button href should be the same id of the modal div -->
            
        <br> <br>
        <h2 class="header">Questions List</h2>    
			<table id="question_data_table" class="responsive-table centered">
				<thead>
					<tr>
                    <th>Question Title</th>
						<th>Right Option</th>
						<th>Action</th>
					</tr>
				</thead>
			</table>
		</div>

        <form id="getCode" action="">
        <input id="hiddenCode" type="hidden" name="hiddenCode" value="<?php echo $code ?>">
        </form>
        <!-- Edit Question Modal -->
            <!-- ====================== Add Question Modal =========================== -->
    <!-- NO! DONT DRY! use the same modal and insert modular values -->
    <div id="modalEditQ" class="modal">
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
                        <label>Question Title <span class="red-text">*</span></label> <br>
                        <input type="text" name="question_title" id="question_title" class="" />
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <label>Option 1 <span class="red-text">*</span></label> <br>
                        <input type="text"  name="option_title_1" id="option_title_1"   />
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <label>Option 2  <span class="red-text">*</span></label> <br>
                        <input type="text" name="option_title_2" id="option_title_2"/>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <label>Option 3  <span class="red-text">*</span></label> <br>
                        <input type="text" name="option_title_3" id="option_title_3"/>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <label>Option 4  <span class="red-text">*</span></label> <br>
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

        <!-- Delete Modal Structure -->
        <div id="modalDelQ" class="modal">
        <div class="modal-content">
        <h4>Delete Confirmation</h4>
        <p>Are you sure you want to delete this question?</p>
        </div>
        <div class="modal-footer">
        <a href="#!" name="ok_button" id="ok_button" class=" modal-action modal-close waves-effect waves-green red btn-small">OK</a>

        <a href="#!" id="closebtn_del" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>

 

        
        <?php } ?>
    </div>




<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


    <script src="js/modalBox.js">  </script>
      <script src="js/hammenu.js">  </script>
      <script src="js/questions_ajax.js">  </script>
      
      <script>
        //   for edit question modal box 
          document.addEventListener('DOMContentLoaded', function() {
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems,{dismissible:false});
            $('select').formSelect();
        });


                
      </script>

</body>
</html>