let user_email_elem = document.getElementById('user_email') ; 
let user_name_elem = document.getElementById('user_name') ; 
let user_pass_elem  = document.getElementById('user_pass');
let confirm_user_pass_elem  = document.getElementById('comfirm_user_pass');
let form = document.getElementById('user_register_form'); 
let action = document.getElementsByName('action')[0]; 
var divElem = document.getElementById('msg') ;
var button = document.getElementById('user_register'); 

var pass_matching = {value: false}; 
var email_valid = {value: false}; 
var username_valid = {value: false}; 

$(document).ready(()=>{

    $('#user_register_form').on('submit', async (e) =>{
        e.preventDefault();
        // =============== check if all fields are not empty ==================
        let msg = "You can't leave this field empty";
        if (checkEmpty(user_email_elem,msg),checkEmpty(user_name_elem,msg),checkEmpty(user_pass_elem,msg),checkEmpty(confirm_user_pass_elem,msg)){
            console.log('all good')
            // no empty values 
            // ========== check for indiividual fields
            setTimeout(async () => {
                if (username_valid.value && email_valid.value && pass_matching.value){
                    console.log('all valid')
                    // ============== if all is valid let's change the action of the form to be 'register'
                    action.value = "register";
                    // console.log(action)// now action has the value of register which we can differentiate the POST reuests recieved in php with
                
            
            // setTimeout(  async()=>{

                await sendDataToPage(form)
                .then( (data)=>{
                console.log(data.data);
                console.log(`STATUS: ${data.data.status}`);
                let status = data.data.status;
                let elem = data.data.elem;
                if (status == "exists"){
                    if (elem == "email"){
                    // call function to highlight error
                    showError(user_email_elem); 
                    showEmailErrorMsg(user_email_elem,'Email already exists.',email_valid);
                } else if (elem == "username"){
                    showError(user_name_elem); 
                    showUsernameErrorMsg(user_name_elem,'Username already exists.',username_valid);
                }
                else if (elem =="email_and_username"){
                    showError(user_email_elem); 
                    showEmailErrorMsg(user_email_elem,'Email already exists.',email_valid);
                    showError(user_name_elem); 
                    showUsernameErrorMsg(user_name_elem,'Username already exists.',username_valid);
                }
                } else if (status == 'registered'){
                    // var div = document.createElement('div');
                    // form.after(div);
                    button.disabled=true;
                    setTimeout(()=>{
                        $("<div id='msg' class='working'> Your account has been created. <br> You are being Logged in.<br> Redirecting to Home page in 1 sec...</div>").insertAfter(form);
                        divElem = document.getElementById('msg') ;
                        hideAndRedirect(form,divElem);
                    },500)
                }
            }) 
               
        }
        },300)
              
        }

    
    });

//============= check unique email ===================
    let timeout = null ; 
    user_email_elem.addEventListener('keyup',  function (e) {   
        action.value = "email_check";

        displayLoading(user_email_elem.nextElementSibling.firstElementChild,"");


        clearTimeout(timeout);
        if (this.value.trim() != ""){
            timeout = setTimeout(async () => {

                await sendDataToPage(form)
                .then( (data)=>{
                    console.log(data.data);
                console.log(data.data.status);
        
                let status = data.data.status;
                if (status == "available"){
                    // call function to highlight valid
                    showValid(user_email_elem); 
                    hideEmailErrorMsg(user_email_elem,'Available',email_valid);
        
        
                }else if (status == "exists"){
                    // call function to highlight error
                    showError(user_email_elem); 
                    showEmailErrorMsg(user_email_elem,'Email already exists.',email_valid)
                }
                })
            }, 300);
    }
  
    });


    
//============= check unique user name ===================
let timeout3 = null ; 
user_name_elem.addEventListener('keyup',  function (e) {
    action.value = "username_check";

    displayLoading(user_name_elem.nextElementSibling.firstElementChild,"");


    clearTimeout(timeout3);

    if (this.value.trim() != ""){

        timeout3 = setTimeout(async () => {

            await sendDataToPage(form)
            .then( (data)=>{
                console.log(data.data);
            console.log(data.data.status);

            let status = data.data.status;
            if (status == "available"){
                // call function to highlight valid
                showValid(user_name_elem); 
                hideUsernameErrorMsg(user_name_elem,'Available',username_valid);


            }else if (status == "exists"){
                // call function to highlight error
                showError(user_name_elem); 
                showUsernameErrorMsg(user_name_elem,'Username already exists.',username_valid)
            }
            })
        }, 300);
    }

});

    //==================== Check password eqyaulity ====================
    let timeout2 = null ; 
    let divs =  document.getElementsByClassName('pass') ;

    [user_pass_elem, confirm_user_pass_elem].forEach((element,i)=>{
    element.addEventListener('keyup',  function (e) {
        divs[i].firstElementChild.innerText = "";
        clearTimeout(timeout2);
        timeout2 = setTimeout(() => {
            checkPassEmpty(user_pass_elem,confirm_user_pass_elem,'You cant leave this field empty!')
        }, 200);
  
    });
    });




    
    
    const sendDataToPage = async (form) =>{
        let formdata = new FormData(form)

        let api = axios.create({basicurl:'http://localhost:80'})
        return  api.post("/OnlineQuizSystem/user_register_action.php",formdata)  
    }


    });  

// ====================== styles related stuff ============================
// PasswordEmptineesscheck
function checkPassEmpty(elem1,elem2,msg){
    if (elem1.value.trim() == ""){
        displayLoading(elem1.nextElementSibling.firstElementChild,msg,'red');
        displayLoading(elem2.nextElementSibling.firstElementChild,"");

        showError(elem1); 
    } else if (elem2.value.trim() == ""){
    } else { // check euqal 
        if (checkEqual(elem1.value,elem2.value)){
            showValid(elem2)
            showValid(elem1)
            hidePassErrorMsg(elem2,'Valid',pass_matching);

        } else {
            showError(elem1)
            showError(elem2)
            showPassErrorMsg(elem2,'Passwords are not matching.',pass_matching)


        }

    }
}

function checkEmpty(elem,msg){ 
    if (elem.value.trim() == ""){
        displayLoading(elem.nextElementSibling.firstElementChild,msg,"red");
        return false
    }
    return true
}
function checkEqual(val1,val2){
    return (val1==val2)
}

function displayLoading(loadingElem, msg, color='grey'){
    // loadingElem.innerText = 'Checking Validty...' ;
    loadingElem.style.color = color;
    loadingElem.innerText = msg;


}
function showError(elem){
    removeStyles(elem);
    elem.classList.add('highlight-error');

}

function showValid(elem){
    removeStyles(elem);
    elem.classList.add('highlight-valid');

}

function removeStyles(elem){
    elem.classList.remove('highlight-error');
    elem.classList.remove('highlight-valid');
}

function showEmailErrorMsg(elem,msg,email_valid){
    var div = elem.nextElementSibling;
    var loadingElem = div.firstElementChild;
    displayLoading(loadingElem,'Checking Validty...') ;
    setTimeout(() => {
        // loadingElem.innerText = msg ;  
        displayLoading(loadingElem,msg,'grey') ;
        loadingElem.style.color = "red";
        email_valid.value = false;
    }, 200);

}

function showUsernameErrorMsg(elem,msg,username_valid){
    var div = elem.nextElementSibling;
    var loadingElem = div.firstElementChild;
    displayLoading(loadingElem,'Checking Validty...') ;
    setTimeout(() => {
        // loadingElem.innerText = msg ;  
        displayLoading(loadingElem,msg,'grey') ;
        loadingElem.style.color = "red";
        username_valid.value = false;
    }, 200);

}

function showPassErrorMsg(elem,msg,pass_matching){
    var div = elem.nextElementSibling;
    var loadingElem = div.firstElementChild;
    displayLoading(loadingElem,'Checking Validty...') ;
    setTimeout(() => {
        // loadingElem.innerText = msg ;  
        displayLoading(loadingElem,msg,'grey') ;
        loadingElem.style.color = "red";
        pass_matching.value = false ; 
    }, 200);

}
function hideEmailErrorMsg(elem,msg,email_valid){
    var div = elem.nextElementSibling ;
    var loadingElem = div.firstElementChild;
    displayLoading(loadingElem,'Checking Validty...') ;
    setTimeout(() => {
        // loadingElem.innerText = "Available" ;  
        displayLoading(loadingElem,msg,'grey') ;
        loadingElem.style.color = "green";
        email_valid.value = true;
    }, 200);
    
  }

  function hideUsernameErrorMsg(elem,msg,username_valid){
    var div = elem.nextElementSibling ;
    var loadingElem = div.firstElementChild;
    displayLoading(loadingElem,'Checking Validty...') ;
    setTimeout(() => {
        // loadingElem.innerText = "Available" ;  
        displayLoading(loadingElem,msg,'grey') ;
        loadingElem.style.color = "green";
        username_valid.value = true;
    }, 200);
    
  }

  function hidePassErrorMsg(elem,msg,pass_matching){
    var div = elem.nextElementSibling ;
    var loadingElem = div.firstElementChild;
    displayLoading(loadingElem,'Checking Validty...') ;
    setTimeout(() => {
        // loadingElem.innerText = "Available" ;  
        displayLoading(loadingElem,msg,'grey') ;
        loadingElem.style.color = "green";
        pass_matching.value = true ; 

    }, 200);
    
  }

  function hideAndRedirect(form,divElem) {
    if (divElem != null){
    //   form.className = "displayNone";
      form.style.display = 'none';
      setTimeout(function(){
           window.location.href = "index.php" ;
      },400) ;
    }
  }
  



