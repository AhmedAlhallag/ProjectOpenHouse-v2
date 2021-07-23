let admin_email_elem = document.getElementById('admin_email') ; 
let admin_pass_elem  = document.getElementById('admin_pass');
let form = document.getElementById('admin_login_form'); 
let action = document.getElementsByName('action')[0]; 
var divElem = document.getElementById('msg') ;
var button = document.getElementById('admin_login'); 

var pass_valid = {value: false}; 
var email_valid = {value: false}; 

$(document).ready(()=>{

    $('#admin_login_form').on('submit', async (e) =>{
        e.preventDefault();
        removeStyles(admin_email_elem);
        removeStyles(admin_pass_elem);
        hideErrorMsg(admin_email_elem);
        hideErrorMsg(admin_pass_elem);

       
                await sendDataToPage(form)
                .then( (data)=>{
                console.log(data.data);
                if (data.data.status == "success"){
                    window.location.href = "index.php"; 
                } else if (data.data.status == "failure"){
                    if (data.data.error == "not registered") {
                        // show error msg
                        showError(admin_email_elem);
                        showErrorMsg(admin_email_elem,"You need to be registered first!");
                    }
                    else if (data.data.error == "password"){
                        //show error msg
                        showError(admin_pass_elem);
                        showErrorMsg(admin_pass_elem,"Password is incorrect!");

                    }

                }

            }) 
                
              
        });

    
    });


    
    const sendDataToPage = async (form) =>{
        let formdata = new FormData(form)
        let api = axios.create({basicurl:'http://localhost:80'})
        return  api.post("/OnlineQuizSystem/login_ajax_action.php",formdata)  
    }

    //=================== functions ======================
    function removeStyles(elem){
        elem.classList.remove('highlight-error');
        elem.classList.remove('highlight-valid');
    }
    function showError(elem){
        removeStyles(elem);
        elem.classList.add('highlight-error');
    
    }

    function displayLoading(loadingElem, msg, color='grey'){
        loadingElem.style.color = color;
        loadingElem.innerText = msg;
    
    
    }
    function showErrorMsg(elem,msg){
        var div = elem.nextElementSibling;
        var loadingElem = div.firstElementChild;
        displayLoading(loadingElem,msg,'red') ;
    
    }

    function hideErrorMsg(elem){
        var div = elem.nextElementSibling ;
        var loadingElem = div.firstElementChild;
            displayLoading(loadingElem,"",'grey') ;
        
      }