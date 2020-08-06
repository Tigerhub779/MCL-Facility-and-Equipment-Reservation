$(document).ready(function () {

            var emailAddress=$("#user-email");
            var password=$("#user-pass");
        $("#logIn").on("click", function (e) {
            var format="@live.mcl.edu.ph";
            let valid=true;
            if(emailAddress.val()==""){
                  emailAddress.addClass("is-invalid");
                  $("#EmailMsg").text("Please provide an email address");
                  valid=false;
            }
            else if(emailAddress.val().indexOf(format)+format.length!=emailAddress.val().length){
                  emailAddress.addClass("is-invalid");
                  $("#EmailMsg").text("Wrong Email Format! Must be one mcl");
                  valid=false;
            }

            if(password.val()==""){
                password.addClass("is-invalid");
                valid=false;
            }

            if(!valid)
               e.preventDefault();
        });

        emailAddress.on("keyup",function(){
            var format="@live.mcl.edu.ph";
            if($(this).val()==""){
                emailAddress.addClass("is-invalid");
                $("#EmailMsg").text("Please provide an email address");
            }
            else if($(this).val().indexOf(format)+format.length!=emailAddress.val().length){
                emailAddress.addClass("is-invalid");
                $("#EmailMsg").text("Wrong Email Format! Must be one mcl");
            }
            else if(!$(this).val().includes(format)){
                emailAddress.addClass("is-invalid");
                  $("#EmailMsg").text("Wrong Email Format! Must be one mcl");
            }
            else{
                emailAddress.removeClass("is-invalid");
                emailAddress.addClass("is-valid");
            }
        });

        password.on("keyup", function(){
            if($(this).val()==""){
                password.addClass("is-invalid");
            }
            else{
                password.removeClass("is-invalid");
                password.addClass("is-valid")
            }
        })
});