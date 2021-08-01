

var ForceStartAction = document.getElementById('action');

$(document).ready(function () {

    let current_cookie = document.cookie;
    let current_userId = "";
    let current_type = "";
    current_userId = processCookie(current_cookie)[0];
    current_type = processCookie(current_cookie)[1];
    console.log("current_userID: ", current_userId);
    console.log("current_type: ", current_type);

    if (current_userId != null && current_type == "user") {
        var recievedObj = ""
        var force_start = ""
        var socket = new WebSocket('ws://52.7.174.33:5555');
        // http://52.7.174.33:80

        socket.onmessage = function (e) {
            recievedObj = JSON.parse(e.data);
            // console.log('sjdsjdsk');
            // console.log(recievedObj);
            // console.log(recievedObj);
            if (recievedObj.force_start == false) {
                // dataTable.ajax.reload();
                // console.log(recievedObj.details);
                force_start = "false";
                // ForceStartAction.value = "not-started";
                console.log(recievedObj);
                console.log('sjdsjdsk');
                recievedObj = "done"
            } else if (recievedObj.force_start == true) {
                force_start = "true";
                // ForceStartAction.value = "started";
                location.reload();
                console.log(recievedObj.force_start);
                console.log(recievedObj);
                console.log('sjdsjdsk');
                recievedObj = "done"

            }
        }

        if (recievedObj == "done") {
            socket.close();
            recievedObj = "";
        }


        if (force_start == "true") {
            // get action from html 
            ForceStartAction.value = "started";
            console.log("VALUE:");
            console.log(ForceStartAction.value);
        }
        else if (force_start == "false") {
            console.log("NOO:");


        }
    }
















});

//===================== some functions ====================

function processCookie(current_cookie) {
    let current_userId = null;
    let current_type = null;
    let userValue = "";
    let type = "";
    if (current_cookie.split('; ').length > 2) {
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
        userValue = '{' + userValue + '}';
        userValue = userValue.replace('=', ':');
        console.log(userValue);
        current_userId = eval(userValue);

        // type of logged in member
        type = '{' + type + '}';
        type = type.replace('=', ':');
        let quoted_type = `'${type.slice(type.indexOf(":") + 1, type.indexOf("}"))}'`;
        if (type.indexOf('u') != -1) {
            type = type.replace('user', quoted_type);
        }
        else if (type.indexOf('a') != -1) {
            type = type.replace('admin', quoted_type);
        }
        current_type = eval(type);
        return [current_userId, current_type];
    }
    return [current_userId, current_type];
}

function sendDataToPage(uri, form) {
    // probably a code smell having a default argumengt... REFACTOR
    let formdata = new FormData(form);
    let api = axios.create({ basicurl: 'http://52.7.174.33:80' });
    return api.post(uri, formdata);
}


function sendDataToPage(uri, form, config) {
    var formdata = "";
    if (form) {
        formdata = new FormData(form);
    }
    let api = axios.create({ basicurl: 'http://52.7.174.33:80' });
    return api.post(uri, formdata, config);

}