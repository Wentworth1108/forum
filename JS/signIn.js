$(document).ready(function(){
    $('#loginBtn').click(function(){
        var data = $('#loginForm').serializeArray();
        $.post("../forum/signIn.php",data,function(error){
            if(error == ""){
                window.location.href = "../forum/index.php";
            }else {
                var waring = $('#loginWaring');
                waring.show();
                waring.append('<span class="sr-only">Error:</span>'+error);
            }
        })
    });

    $('#registerBtn').click(function(){
        var data = $('#registerForm').serializeArray();
        $.post("../forum/createAccount.php",data,function(error){
            if(error == ""){
                window.location.href = "../forum/index.php";
            }else {
                var waring = $('#registerWaring');
                waring.show();
                waring.append('<span class="sr-only">Error:</span>'+error);
            }
        })
    })
});