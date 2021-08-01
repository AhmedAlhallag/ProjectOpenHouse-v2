var close = document.getElementById('close') ;
var closeEnrollModal = document.getElementById('closeEnrollModal') ;

var box = document.getElementById('box') ;

var blurit = document.getElementById('container') ;

function showModalBox(){
  if (box !== null){
    box.classList.replace('displayNone',"displayBlock");
    blurit.classList.add("blur");
  }
}
showModalBox();

if (close != null){
close.addEventListener('click', function(){
  window.location.href = "user_register.php";
})
}



