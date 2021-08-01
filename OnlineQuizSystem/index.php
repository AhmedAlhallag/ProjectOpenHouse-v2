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

<title>Computing School - Open House</title>

 <style>
        .slider  {
             /* width: 58%;  */
             /* margin: 0 auto; */
        }

        .slider .slides li .img{
            height:670px;

            max-width: 100%;
            display: block;
        }
        .slider .slides{
          height: 670px !important

        }

        h5,h2{
            text-align: left;
        }
        h5{
          font-size: 23px !important
        }
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
        h5{
          font-size: 16px !important
        }

        .slider .slides li .img{
            height:790px;

            max-width: 100%;
            display: block;
        }
        .slider .slides{
          height: 790px !important

        }
}

      </style>

</head>
<body>
<?php 
    include('partials/nav.php');
    // include('../partials/nav.php');
?>

<!-- Following snippet is to remove the userId cookie only if the current id (set by session) was found empty -->
<!-- meaning that the user has logged out -->


<!-- Make sure the first thing to execute in this page is an IFFIE that removes any cookies -->

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


      <section class="slider">
        <ul class="slides">


            <li>
                <img class="img" src="img/img3.jpg">
                <div class="caption center-align">
                    <h2 class='make-smaller'>BSc Ethical Hacking and Cybersecurity</h2>
                    <h5 class="light grey-text text-lighten-3">
                        With high profile incidents like the recent WannaCry and Petya ransomware attacks making headlines,
                        there is an increasing demand for highly trained individuals who can advise businesses and organisations,
                        government and law enforcement agencies alike on the best ways to protect their computer networks and the valuable,
                        commercially sensitive information stored within them.
                    </h5>


          </h5>
              </div>
            </li>

            <li>
                <img class="img" src="img/img2.jpg">
                <div class="caption center-align">
                    <h2>BSc Computer Science</h2>
                    <h5 class="light grey-text text-lighten-3">
                      Problem-solving lies at the heart of Computer Science, making this one of the most exciting and forward-thinking courses you can study.


                </div>
            </li>

            <li>
                <img class="img" src="img/img1.jpg">
                <div class="caption center-align">
                    <h2>BSc Computing</h2>
                    <h5 class="light grey-text text-lighten-3">
                        Computing, from the internet to mobile computing, smart devices and beyond, has changed the world – and continues to do so: next-generation robots, driverless cars, and digital currencies are just three examples of what is already on the way.
                    </h5>

              </div>
            </li>


          </ul>

      </section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
      <script src="js/hammenu.js">  </script>
            <script src="js/clickFAB.js" type="text/javascript">      </script>

      <script>

          // Slider
const slider = document.querySelector('.slider');


// $('.slider').on('click',(e)=>{

M.Slider.init(slider, {
  indicators: false,
  height: 500,
  transition: 500,
  interval: 6000
});

          
</script>

</body>
</html>