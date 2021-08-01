(() => {
    let secret = prompt("What is the secret keyword?");
    if (secret != "h") {
        alert('Acess Denied');
        window.location.href = "user_register.php";
    }

})();