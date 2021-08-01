var time = "";
var time_formatted = "";
var datetime = "";
var form = document.getElementById('exam_form');
var closebtn = document.getElementById('closebtn');
var edit = document.getElementsByName('edit')
//========== question ===============
var qForm = document.getElementById('question_form');
var qClosebtn = document.getElementById('qclosebtn');




$(document).ready(() => {




    // ============================== search and populate in data table ===================
    // ================== add a conditional to check whether a session id (which will be check form a cookie) is set ot not before firing the ajax request
    // check cookies : THIS IS better than mocking an unsused (first row of id 1 for example) row with mocked values (empty) and sending it's data to datatable  
    // javascirpt is on the client side, so there's no way for it to check sessions (because it is on the server side duhh)
    // that's why we save the session when user is logged in as a cookie and retrieve it using js at the client side  
    let current_cookie = document.cookie;
    let current_userId = "";
    let current_type = "";
    current_userId = processCookie(current_cookie)[0];
    current_type = processCookie(current_cookie)[1];
    console.log("current_userID: ", current_userId);
    console.log("current_type: ", current_type);

    if (current_userId != null && current_type == "admin") {
        // open WS connection conditionally  
        // AND 
        //============= Socket =============================
        var socket = new WebSocket('ws://52.7.174.33:5555');
        console.log("ready: ", socket.readyState);
        //  if (WebSocket.readyState){
        //  }
        var msgSent = "";
        function transmitMessage(msg) {
            socket.send(msg);
            msgSent = "done";
        }
        if (msgSent == "done") {
            socket.close();
            msgSent = "";
            // console.log("connection closed from js");
        }
        //======================================================
        var curr_datetime = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
        var timezone_name = Intl.DateTimeFormat().resolvedOptions().timeZone;

        var dataTable = $('#exam_data_table').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "exam_search_ajax_action.php",
                method: "POST",
                data: { curr_datetime: curr_datetime, action: 'fetch', page: 'exam', timezone: timezone_name }
            },
            "columnDefs": [
                {
                    "targets": [7, 8, 9],
                    "orderable": false,
                },
            ]

        });
    }


    // (async function () {


    //     var curr_datetime = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');

    //     let axioxParams = {
    //         params: {
    //             action: 'getDate',
    //             curr_datetime: curr_datetime
    //         }
    //     };

    //     await sendDataToPage('/rec_date.php', "", axioxParams)
    //         .then(data => {
    //             let status = data.data.status;
    //             if (status == "success") {
    //                 console.log(data.data);
    //                 // document.cookie = `timestamp=${data.data.date}`;
    //                 console.log('done');

    //                 document.getElementById('datee').value = data.data.date;
    //             }
    //         })
    //         .catch(err => {
    //             console.log(err);
    //         })

    // })();



    // ================= open add an exam ==================
    $('#add_button').on('click', async function (event) {
        reset_form('Add Exam Details', 'Add', "");
        $('#online_exam_id').val("");
        $('select').formSelect();

    });
    //============================ submit new OR updated exam ====================S
    $('#exam_form').on('submit', async function (event) {
        event.preventDefault();
        $('#message_operation').html('');

        let title = $('#online_exam_title').val();
        let some_date = $('#online_exam_date').val();
        let some_time = $('#online_exam_time').val();
        let dur = $('#online_exam_duration').val();
        let question = $('#total_question').val();
        let right_ans = $('#marks_per_right_answer').val();
        let wrong_ans = $('#marks_per_wrong_answer').val();
        if (title && some_date && some_time && dur && question && right_ans && wrong_ans) {
            // all set-> do something 
            time = $('#online_exam_time').val();
            console.log('time:', time);
            date = $('#online_exam_date').val();
            console.log('date:', date);

            time_formatted = convertTime12to24(time) + ":00";
            console.log('time formatted:', time_formatted);
            datetime = date + " " + time_formatted;
            console.log('dattime:', datetime);
            $('#datetime').val(datetime);

            //================ Check hidden action value ====================
            // initialize config params
            var curr_datetime = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
            var timezone_name = Intl.DateTimeFormat().resolvedOptions().timeZone;


            let axioxParams = {
                params: {
                    page: "exam.php",
                    action: "Add",
                    curr_datetime: curr_datetime,
                    timezone: timezone_name


                }
            };

            var sub_str = "";
            action = $('#action').val();
            if (action == "Edit") {
                sub_str = "edit";
                axioxParams.params.action = "Edit";
            }
            else if (action == "Add") {
                sub_str = "add"
            };
            await sendDataToPage('/' + sub_str + '_exam_action_ajax.php', form, axioxParams)
                .then((data) => {
                    console.log(data.data);
                    let status = data.data.status;
                    if (status == "success") {
                        $('#message_operation_out').html(`<br><br><span style="color:green">${data.data.details}</span>`);
                        // reset_form('Add Exam Details', 'Add',"");
                        console.log(data.data.after_edit_check);
                        dataTable.ajax.reload();
                    }
                });
            // wHEN SUBMIT IS SUCCESSFUL ONLY CLOSE Modal
            closebtn.click();


        } else {
            $('#message_operation').html('<span style="color:red">You cant leave any fields empty</span>');
            $('#modal1').scrollTop(0);//  scroll to top of modal ifform was sbumitted with empty field!
        }
    });

    // ================= open edit an exam ==================
    // since multiple Elements will be having the same 'edit' name and we want to attach the same onclick to all of them we will do this
    $(document).on('click', '.edit', async (e) => {
        // first grab the edit button exam id to repopulate (READ) data first
        var editBtn = e.currentTarget;
        var exam_id = $(editBtn).attr('id'); // val will not work here!
        console.log(exam_id);
        reset_form('Edit Exam Details', 'Edit', "");
        // now publish the data retrieved by the ajax from the php action script
        let axioxParams = {
            params: {
                examid: exam_id,
                page: "exam.php",
                action: "edit_fetch"
            }
        };
        await sendDataToPage('/edit_exam_action_ajax.php', "", axioxParams)
            .then((data) => {
                // process the values returned from READ oeprations here
                $('#online_exam_title').val(data.data.online_exam_title);
                $('#online_exam_time').val(data.data.time);
                $('#online_exam_date').val(data.data.date);
                console.log(data.data.online_exam_duration);
                console.log(data.data.total_question);
                $('#online_exam_duration').val(data.data.online_exam_duration);
                $('#total_question').val(data.data.total_question);
                $('#marks_per_right_answer').val(data.data.marks_per_right_answer);
                $('#marks_per_wrong_answer').val(data.data.marks_per_wrong_answer);
                $('#online_exam_id').val(exam_id);
                $('select').formSelect();




            })


    })


    // ================= Make availabe ==================
    $(document).on('click', '.make_available', async (e) => {


        var makeAvailBtn = e.currentTarget;
        var exam_id = $(makeAvailBtn).attr('id'); // val will not work here!
        console.log(exam_id);
        console.log(e);

        //get current date time  to update current time (and make available status..)
        var curr_datetime = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
        var timezone_name = Intl.DateTimeFormat().resolvedOptions().timeZone;

        let axioxParams = {
            params: {
                examid: exam_id,
                page: "exam.php",
                action: "make_available",
                curr_datetime: curr_datetime,
                timezone: timezone_name
            }
        };
        await sendDataToPage('/edit_exam_action_ajax.php', "", axioxParams)
            .then((data) => {
                console.log(data.data);
                let status = data.data.status;
                if (status == "success") {
                    // Send message via socket connection
                    var title = data.data.title;
                    var details = `Exam: ${title} is Now available`;
                    var action = "reload";
                    var socketObj = {
                        details: details,
                        action: action
                    }
                    // console.log(socketObj);
                    transmitMessage(JSON.stringify(socketObj));


                    // $('#online_exam_date').val(curr_datetime);// not working 
                    // console.log("TITLE: ",  $('#online_exam_title').val());
                    // console.log("MAKE AVAIL BUTTON: ", data.data.make_avail); 
                    // $(e.target).html(data.data.make_avail);
                    // $(e.target).html(data.data.force_start);
                    dataTable.ajax.reload();
                }
            })


    })

    // ================= Reset Exam ==================
    $(document).on('click', '.reset', async (e) => {


        var resetBtn = e.currentTarget;
        var exam_id = $(resetBtn).attr('id'); // val will not work here!
        console.log(exam_id);
        console.log(e);
        // get current date and add days to it ;

        var curr_datetime = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
        var timezone_name = Intl.DateTimeFormat().resolvedOptions().timeZone;

        var reset_datetime = getDateTimeInSQLFormat(1);
        // console.log(curr_datetime);

        let axioxParams = {
            params: {
                examid: exam_id,
                page: "exam.php",
                action: "reset_exam",
                reset_datetime: reset_datetime,
                curr_datetime: curr_datetime,
                timezone: timezone_name

            }
        };
        await sendDataToPage('/edit_exam_action_ajax.php', "", axioxParams)
            .then((data) => {
                console.log(data.data);
                let status = data.data.status;
                if (status == "success") {
                    // Send message via socket connection
                    var title = data.data.title;
                    var details = `Exam: ${title} is Now available`;
                    var action = "reload";
                    var socketObj = {
                        details: details,
                        action: action
                    }
                    transmitMessage(JSON.stringify(socketObj));
                    dataTable.ajax.reload();
                }
            })


    })

    // ================= force start ==================
    $(document).on('click', '.force_start', async (e) => {
        var forceBtn = e.currentTarget;
        var exam_id = $(forceBtn).attr('id');
        // console.log(exam_id);
        // console.log(e);
        // get current date and add days to it ;
        // var curr_datetime = getDateTimeInSQLFormat(1);
        // // console.log(curr_datetime);
        var curr_datetime = (new Date((new Date((new Date(new Date())).toISOString())).getTime() - ((new Date()).getTimezoneOffset() * 60000))).toISOString().slice(0, 19).replace('T', ' ');
        var timezone_name = Intl.DateTimeFormat().resolvedOptions().timeZone;

        let axioxParams = {
            params: {
                examid: exam_id,
                page: "exam.php",
                action: "force_start",
                curr_datetime: curr_datetime,
                timezone: timezone_name
            }
        };

        await sendDataToPage('/edit_exam_action_ajax.php', "", axioxParams)
            .then((data) => {
                //     console.log(data.data);
                let status = data.data.status;
                if (status == "success") {
                    // Send message via socket connection
                    var socketObj = {
                        force_start: true,
                    }
                    transmitMessage(JSON.stringify(socketObj));
                }
                location.reload();
            })


    })

    // ================= open delete an exam ==================
    var del_exam_id = "";
    $(document).on('click', '.delete', (e) => {
        // first grab the edit button exam id to repopulate (READ) data first
        var delBtn = e.currentTarget;
        del_exam_id = $(delBtn).attr('id'); // val will not work here! you need the id attribute
        console.log("To be deleted: ", del_exam_id);
    })

    //======================= delete an exam ================
    $('#ok_button').click(async function () {
        let axioxParams = {
            params: {
                delexamid: del_exam_id,
                page: "exam.php",
                action: "Delete"
            }
        };
        await sendDataToPage("/edit_exam_action_ajax.php", "", axioxParams)
            .then(data => {
                // on successful delete 
                let status = data.data.status;
                if (status == "success") {
                    $('#message_operation_out').html(`<br><br><span style="color:green">${data.data.details}</span>`);
                    dataTable.ajax.reload();
                    // location.reload();
                }

            });
    });
    //======================= open add question =================
    var add_q_for_exam_id = "";
    $(document).on('click', '.add_question', function (e) {
        reset_question_form("Add Questions", 'Add', '');
        var addQBtn = e.currentTarget;
        add_q_for_exam_id = $(addQBtn).attr('id'); // val will not work here! you need the id 
        // publish the exam id into the hidden online exam id input field
        $('#hidden_online_exam_id').val(add_q_for_exam_id);
        console.log('exam that will be added question to it: ', add_q_for_exam_id);
    });

    // =================== Add question  ===================

    //============================ submit new OR updated exam ====================S
    $('#question_form').on('submit', async function (event) {
        event.preventDefault();
        $('#question_message_operation').html('');

        let title = $('#question_title').val();
        let op1 = $('#option_title_1').val();
        let op2 = $('#option_title_2').val();
        let op3 = $('#option_title_3').val();
        let op4 = $('#option_title_4').val();
        let ans_op = $('#answer_option').val();

        if (title && op1 && op2 && op3 && op4 && ans_op) {
            console.log('let"s goooo');
            // all set-> do something         
            //================ Check hidden action value ====================
            // initialize config params
            let axioxParams = {
                params: {
                    page: "question",
                    action: "Add Question"
                }
            };

            var sub_str = "add";
            // action = $('#question_action').val();
            // if (action == "Edit"){
            //     sub_str = "edit";
            //     axioxParams.params.action = "Edit Question" ; 
            // }
            // else if (action =="Add") { 
            //     sub_str = "add"
            // }; 
            await sendDataToPage('/' + sub_str + '_question_action_ajax.php', qForm, axioxParams)
                .then((data) => {
                    console.log(data.data);
                    let status = data.data.status;
                    let exam_status = data.data.exam_status;
                    if (status == "success") {
                        if (exam_status == "updated") {
                            $('#message_operation_out').html(`<br><br><span style="color:green">${data.data.details}</span><br><br><span style="color:green">Exam Marked as Created.</span>`);
                            dataTable.ajax.reload();
                        } else if (exam_status == "unchanged") {
                            $('#message_operation_out').html(`<br><br><span style="color:green">${data.data.details}</span>`);
                            dataTable.ajax.reload();
                        }
                    } else if (status = "failure") {
                        $('#message_operation_out').html(`<br><br><span style="color:red">${data.data.details}</span>`);
                        dataTable.ajax.reload();

                    }
                });
            // wHEN SUBMIT IS SUCCESSFUL ONLY CLOSE Modal
            qclosebtn.click();


        } else {
            $('#question_message_operation').html('<span style="color:red">You cant leave any fields empty</span>');
            $('#questionModal').scrollTop(0);//  scroll to top of modal ifform was sbumitted with empty field!
        }

    });


});

//================ some functions ===========================

function reset_form(title, action, message) {
    $('#modal_title').text(title);
    $('#button_action').val(action);
    $('#action').val(action);
    $('#exam_form')[0].reset();
    $('#message_operation').html(message);
    $('#message_operation_out').html(message);

}

function reset_question_form(title, action, message) {
    $('#question_modal_title').text(title);
    $('#question_button_action').val(action);
    $('#question_action').val(action);
    $('#question_form')[0].reset();
    $('#question_message_operation').html(message);
    $('#message_operation_out').html(message);

}





const convertTime12to24 = (time12h) => {
    const [time, modifier] = time12h.split(" ");

    let [hours, minutes] = time.split(":");

    if (hours === "12") {
        hours = "00";
    }

    if (modifier === "PM") {
        hours = parseInt(hours, 10) + 12;
    }

    return `${hours}:${minutes}`;
};


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


Date.prototype.addDays = function (days) {
    var date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}


function getDateTimeInSQLFormat(plus_days = 0) {
    var date = new Date();
    starttime = date.addDays(plus_days);
    var isotime = new Date((new Date(starttime)).toISOString());
    var fixedtime = new Date(isotime.getTime() - (starttime.getTimezoneOffset() * 60000));
    var formatedMysqlString = fixedtime.toISOString().slice(0, 19).replace('T', ' ');
    return formatedMysqlString;
}