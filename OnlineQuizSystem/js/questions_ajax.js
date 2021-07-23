//========== question ===============
var qForm = document.getElementById('question_form') ;
var qClosebtn = document.getElementById('qclosebtn') ; 

$(document).ready(()=>{
    // var code = "<?php echo $_GET['code']; ?>";
    var code = $('#hiddenCode').val() ; 
    
    console.log("CODE:",code);

    // ============================== search and populate in data table ===================
        // ================== add a conditional to check whether a session id (which will be check form a cookie) is set ot not before firing the ajax request
        // check cookies : THIS IS better than mocking an unsused (first row of id 1 for example) row with mocked values (empty) and sending it's data to datatable  
        let current_userId = "" ; 
        let current_cookie = document.cookie;
        let value = current_cookie.split('; ')[1]   ;
        if (value != undefined){
            // someone is logged in 
            value = '{' + value + '}' ; 
            value = value.replace('=',':');
            current_userId = eval(value) ; 
        }
         if (current_userId) {
            var dataTable = $('#question_data_table').DataTable({
                "processing" : true,
                "serverSide" : true,
                "order" : [],
                "ajax" : {
                    url: "question_search_ajax_action.php",
                    method:"POST",
                    data:{action:'fetch', page:'question',code:code}
                },
                "columnDefs":[
                    {
                        "targets":[2],
                        "orderable":false,
                    },
                ]
    
            });
    }
    // ================= open edit an question: FETCH/READ DATA ==================
    // since multiple Elements will be having the same 'edit' name and we want to attach the same onclick to all of them we will do this
    $(document).on('click','.editQ', async(e)=>{
        // first grab the edit button question id to repopulate (READ) data first
        var editQBtn = e.currentTarget;   
        var question_id  = $(editQBtn).attr('id') ; // val will not work here!
        console.log(question_id);   
        reset_question_form('Edit Exam Details','Edit',"");
        // now publish the data retrieved by the ajax from the php action script
        let axioxParams =  {params: {
            questionid:question_id,
            page:"question",
            action: "edit_fetch"
            }} ; 
        await sendDataToPage('/OnlineQuizSystem/edit_question_action_ajax.php',"",axioxParams)
        .then((data)=>{
            // display fetched question data to be  edited 
            console.log(data.data);
            // process the values returned from READ oeprations here
            $('#question_title').val(data.data.question_title);
            $('#option_title_1').val(data.data.option_title_1);
            $('#option_title_2').val(data.data.option_title_2);
            $('#option_title_3').val(data.data.option_title_3);
            $('#option_title_4').val(data.data.option_title_4);
            $('#answer_option').val(data.data.answer_option);
            $('select').formSelect();
            $('#question_id').val(question_id);
        })
    }) 
    //=============================== Edit question ====================================
    
//============================ submit new OR updated exam ====================S
$('#question_form').on('submit', async function(event){
    event.preventDefault();
    $('#question_message_operation').html(''); 

    let title = $('#question_title').val();
    let op1 = $('#option_title_1').val();
    let op2 = $('#option_title_2').val();
    let op3 = $('#option_title_3').val();
    let op4 = $('#option_title_4').val();
    let ans_op = $('#answer_option').val();
    
    if (title && op1 && op2 && op3 && op4 && ans_op ){
        console.log('let"s goooo');
        // all set-> do something         
        //================ Check hidden action value ====================
        // initialize config params
        let axioxParams =  {params: {
            page:"question",
            action: "Edit Question"
            }} ; 

        var sub_str = "edit" ; 
        // action = $('#question_action').val();
        // if (action == "Edit"){
        //     sub_str = "edit";
        //     axioxParams.params.action = "Edit Question" ; 
        // }
        // else if (action =="Add") { 
        //     sub_str = "add"
        // }; 
        await sendDataToPage('/OnlineQuizSystem/' + sub_str + '_question_action_ajax.php',qForm,axioxParams)
        .then( (data)=>{
            console.log(data.data);
            let status = data.data.status ; 
            if (status == "success"){
            $('#message_operation_out').html(`<br><br><span style="color:green">${data.data.details}</span>`);
            dataTable.ajax.reload() ;
            }
        });
        // // WHEN SUBMIT IS SUCCESSFUL ONLY CLOSE Modal
        qclosebtn.click();

    
    } else {
        $('#question_message_operation').html('<span style="color:red">You cant leave any fields empty</span>'); 
        $('#modalEditQ').scrollTop(0);//  scroll to top of modal ifform was sbumitted with empty field!
    }
    
});

    // ================= open delete an exam ==================
    var del_question_id = "" ; 
    $(document).on('click','.deleteQ', (e)=>{
        // first grab the edit button exam id to repopulate (READ) data first
        var delBtn = e.currentTarget;   
         del_question_id  = $(delBtn).attr('id') ; // val will not work here! you need the id attribute
        console.log("To be deleted: ",del_question_id);   
    }) 

        //======================= delete an exam ================
        $('#ok_button').click(async function(){
            let axioxParams =  {params: {
                delquestionid:del_question_id,
                page:"question",
                action: "Delete"
                }} ; 
            await sendDataToPage("/OnlineQuizSystem/edit_question_action_ajax.php","",axioxParams)
            .then(data=>{
                // on successful delete
                console.log(data.data) 
                let status = data.data.status ; 
                if (status == "success"){
                $('#message_operation_out').html(`<br><br><span style="color:green">${data.data.details}</span>`);
                dataTable.ajax.reload() ;
                }
    
            });
        });

});



// ======================= some functions ================
function reset_question_form(title,action,message)
{
    $('#question_modal_title').text(title);
    $('#question_button_action').val(action);
    $('#question_action').val(action);
    $('#question_form')[0].reset();
    $('#question_message_operation').html(message); 
    $('#message_operation_out').html(message);

}


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