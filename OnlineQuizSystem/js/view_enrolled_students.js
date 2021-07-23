

$(document).ready(()=>{

        let current_cookie = document.cookie;
        let current_userId = "" ; 
        let current_type = "" ; 
        current_userId = processCookie(current_cookie)[0];
        current_type = processCookie(current_cookie)[1];
        console.log("current_userID: ", current_userId);
        console.log("current_type: ", current_type);
        var code = $('#hiddenCode').val() ; 
    
         if (current_userId != null && current_type =="admin") {
            //======================================================
                var dataTable = $('#view_enrolled').DataTable({
                    "processing" : true,
                    "serverSide" : true,
                    "order" : [],
                    "ajax" : {
                        url: "view_enrolled_students_action.php",
                        method:"POST",
                        data:{action:'fetch', page:'view_enrolled_students',code:code}
                    },
                    "columnDefs":[
                        {
                            //NOTE: target will disable the ordering by column, so make sure what index is selected
                            "orderable":false,
                        },
                    ]
        
                });
        }
    
});

// ======================= some functions ==========================

function sendDataToPage(uri,form){
    // probably a code smell having a default argumengt... REFACTOR
    let formdata = new FormData(form); 
    let api = axios.create({basicurl:'http://localhost:80'});
    return api.post(uri,formdata);  
}


function sendDataToPage(uri,form,config){
    var formdata = "";  
    if (form){
        formdata = new FormData(form); 
    }
    let api = axios.create({basicurl:'http://localhost:80'});
    return api.post(uri,formdata,config );  

}

function processCookie(current_cookie){
    let current_userId = null ; 
    let current_type = null ; 
    let userValue = "" ;
    let type = "" ;
    if (current_cookie.split('; ').length > 2){
        // check who is where..
        if (current_cookie.split('; ')[1].indexOf('u') == 0) {// first element is userId
            userValue = current_cookie.split('; ')[1];
            type = current_cookie.split('; ')[2];
        } else {
            userValue = current_cookie.split('; ')[2];
            type = current_cookie.split('; ')[1];
        }
        // someone is logged in 
        // get user/admin id
        userValue = '{' + userValue + '}' ; 
        userValue = userValue.replace('=',':');
        console.log(userValue);
        current_userId = eval(userValue);

        // type of logged in member
        type = '{' + type + '}' ; 
        type = type.replace('=',':');
        let quoted_type = `'${type.slice(type.indexOf(":") +1, type.indexOf("}"))}'` ;
        if (type.indexOf('u') != -1){
            type = type.replace('user',quoted_type);
        } 
        else if (type.indexOf('a') != -1){
            type = type.replace('admin',quoted_type);
        }
        current_type = eval(type);
        return [current_userId,current_type];
    } 
        return [current_userId,current_type];
}
