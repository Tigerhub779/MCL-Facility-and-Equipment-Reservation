$(document).ready(function () {
    $(".btn").click(function(){

        
    var email=$("#user-email").val();
    var pass=$("#user-pass").val();
       
        if(!validatePattern(email,pass)){    
          event.preventDefault();
        }
    })

});
function validatePattern(email,pass){

    var emailInvalidMsg=$("#EmailMsg");
    var passInvalidMsg=$("#PassMsg");
    var mustEmail="@live.mcl.edu.ph";
    var isValidEmail=false;
    var isValidPass=false;

 
    if(email==""||email==null){
        $("#user-email").removeClass("is-valid");
        $("#user-email").addClass("is-invalid");
        emailInvalidMsg.text("*Please Input your One mcl email");
        isValidEmail=false;
    }
   else if(email.substr(email.length-mustEmail.length).trim()!=mustEmail||email.length-mustEmail.length<5){
        $("#user-email").removeClass("is-valid");
        $("#user-email").addClass("is-invalid");
        emailInvalidMsg.text("* Incorrect One MCL Email");
        isValidEmail=false;
    }
    else {
        $("#user-email").removeClass("is-invalid");
        $("#user-email").addClass("is-valid");
        isValidEmail=true;
    }

    if(pass==""||pass==null){
        $("#user-pass").addClass("is-invalid");
        passInvalidMsg.text("* Please Input your password");
        isValidPass=false;
    }
    else if(pass.length<5){
        $("#user-pass").addClass("is-invalid");
        passInvalidMsg.text("*  Incorrect length of password");
        isValidPass=false;
    }
    else{
        $("#user-pass").removeClass("is-invalid");
        isValidPass=true;
    }

    return isValidEmail&&isValidPass;
}

