(()=>{
    let secret = prompt("What is the secret keyword?");
    if (secret != "computinghals"){
        alert('Acess Denied');
        window.location.href = "user_register.php";
    }

})();