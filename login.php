<?php
    session_start();

    if(isset($_SESSION['login'])){
        header("Location: index.php");
    }
    $msg="";
    $emailCorrect=false;

    if(isset($_POST["user-email"],$_POST["user-password"])){
        require_once("Includes/ConnectDB.php");

        $sql="SELECT * FROM user_information WHERE user_mcl_email='{$_POST["user-email"]}' ";
        $result=mysqli_query($conn,$sql);

        if(mysqli_num_rows($result)>0){
            $row= mysqli_fetch_assoc($result);
            if($row['user_pwd']!=$_POST["user-password"]){
                $msg="Email Address and Password do not match!";
                $emailCorrect=true;
            }
            else{
                $_SESSION['login']=true;
                $_SESSION['user_no']=$row['user_no'];
                $_SESSION['user_name']=$row['user_name'];
                $_SESSION['user_status']=$row['user_status'];
                $_SESSION['user_email']=$row['user_mcl_email'];

                //close
                mysqli_close($conn);
                if($_SESSION['user_status']=="Student"||$_SESSION['user_status']=="Employee"){
                  header("Location: index.php");
                }
                else if(strpos($_SESSION['user_status'],"Admin")!==false){
                    header("Location: Admin.php");
                }
                else{
                    $msg="Authentication of Credentails Error";
                }
            }
        }
        else{
            $msg="No email records were matched!";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCL  LogIn</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="Css/Style.css">
    <link rel="icon" href="Images/Mcl_logo.png">
</head>
<body>
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="container-form rounded bg-white shadow-lg">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="POST">
                <div class="form-header d-flex justify-content-center align-items-center">
                    <img src="Images/Mcl_logo.png" class="img-fluid img-logo" alt="mcl logo">
                    <h1 class="text-white">MCL Online Reservation</h1>
                </div>
                <div class="jumbotron mb-3">
                    <h2>Sign In your One mcl account</h2>
                </div>
                <div class="text-danger text-center my-2">
                     <h4><?php echo $msg; ?></h4>
                </div>
                <div class="form-main">
                    <div class="form-group">
                        <label class="text-dark form-text important-label">Email Address:</label>
                        <input type="text" class="form-control" name="user-email" id="user-email" value="<?php if($emailCorrect)echo $_POST["user-email"]?>"placeholder="ex: someone@live.mcl.edu.ph" require>
                        <div class="invalid-feedback" id="EmailMsg">Please put an email address</div>
                    </div>
                    <div class="form-group mt-4">
                        <label class="text-dark form-text important-label">Password:</label>
                        <input type="password" class="form-control" name="user-password" id="user-pass" placeholder="type your password here" require>
                        <div class="invalid-feedback" id="PassMsg">Please provide your password</div>
                    </div>
                   <label class="form-text mt-4 mb-3">Note: If you can't access your onemcl acc, contact mcl admin</label>
                    <button class="btn btn-primary btn-lg" id="logIn" type="submit">Log In</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="Script/login.js"></script>
   
</body>
</html>