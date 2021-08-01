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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>


     <title>Cryptography!</title>

     <style media="screen">
/* .brand-logo{
    font-family: "Roboto Mono";
    /* font-family: "Staatliches"; 
 */
h5,h2{
    text-align: left;
}
.text {
/* width: 70% !important; */
}
.number{
  /* width: 20% !important */
}
.side{
/* float: right; */
/* width: 30% !important */
}
.main{
  float: left;

  width: 60% !important;

}
.cipher{
  margin-top:20px
}
textarea {
  outline: none;
}
.good {
  caret-color: #10b981;
}
h5{
  font-size: 16px !important
}
/* 
.brand-logo {
    max-height: 100% !important;
    width: auto !important;
} */
.circ{
  /* width: 100px !important;
  height: 100px !important */
  font-size: 10px !important;
  text-align: center;
}


@media only screen and (max-width: 682px){
  /* nav .brand-logo {
  left: 50%;
  -webkit-transform: translateX(-50%);
  transform: translateX(-50%);
  font-size: 0.9rem !important;
} */
.make-smaller{
  font-size: 47px !important
}
.number{
  /* width: 90% !important */
}
}

</style>
</head>
<body>
<?php 
    include('partials/nav.php');

include('functions/ceasary_func.php');

$result = $string = '';
$shift  = 0 ;

// ===================== PHP logic
// if (isset($_POST['submit'])){// clicked
// // if(isset($_POST) && !empty($_POST)){
// //   $string = strtoupper($_POST['text']);
// //   $shift =  $_POST['shift'];
// //   $result =  CaesarCipher($string,$shift);
// }

if (isset($_POST) && !empty($_POST) ) {
// echo @$_POST['text'];// this raises a stupid error that is resolved by inserting updated content to page by jquery
//IMPORTANT NOTE: ajax is made to ease the data communication between pages/servers...not to send data back to itlsef on the same page, but rather to send it to another php file that might handle database processing
  $string = strtoupper(@$_POST['text']);
  $shift =  $_POST['shift'];
  $result =  trim(CaesarCipher($string,$shift));


}



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


<div class="container">

     <h2>Explore Ceasar Cipher!</h2>
     <br><br>

     <form id="form" class="" action="ceasar.php" method="post">
       <div class="row">
         <div class='input-field col s12 m5'>
       <!-- <div class=""> -->

       <label for="text"> Enter Some TEXT:</label>
         <input class="text" id="text" type="text" name="text" value="<?php echo $string ?>" length="30">
       </div>
       <!-- <div class=""> -->
       <div class='input-field col s12 m5'>

         <label class="label"for="number"> +Right / -Left Shift:</label>
         <input id="number"class="number"type="number" name="shift" value="<?php echo $shift; ?>" min="-30" max="30" placeholder="0">

       </div>
       <br>

       <input class="cipher btn-large #ff6d00 orange accent-4 " id="submit" type="submit" name="submit" value="Cipher/Decipher"> <br> <br>

        <div class="input-field col s12">
          <label for="textarea1">Output:</label>
          <textarea id="textarea1" class="materialize-textarea" disabled ><?php echo $result;?></textarea>
        </div>
        <!-- </textarea> -->
      </div>

     </form>

   </div>



    
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      <script src="js/hammenu.js">  </script>
            <script src="js/clickFAB.js" type="text/javascript">      </script>
            <script src="js/ajax.js" type="text/javascript"></script>



</body>
</html>