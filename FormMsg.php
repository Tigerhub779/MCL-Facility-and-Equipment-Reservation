<?php
  session_start();

    if(!isset($_SESSION['login'])){
        header("Location: login.php");
    }
    
   if(!isset($_SESSION['msgRequest'])){
     $_SESSION['msgRequest']=true;
   }

    if(isset($_SESSION['startSession'])&&$_SESSION['startSession']){
        $_SESSION['startSession']=false;
    }

?>

<!DOCTYPE>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCL Online Reservation form</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="Css/Style2.css">
    <link rel="icon" href="Images/Mcl_logo.png">
    <script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div class="top">
        <img src="Images\Mcl_logo.png" id="mcl-logo" alt="mcl logo">
           <h1>MCL Online Reservation Form</h1>
           <a href="index.php" id="menu"><button class="btn btn-success">Go to Home</button></a>
        </div>
    </header>
    <?php 
        if(!isset($_SESSION['natureActivity'])||$_SESSION['natureActivity']==''){
                echo '<div class="box" id="starter">
                            <div class="line-design bg-danger rounded-top"></div>
                            <div class="jumbotron py-4 rounded-lg text-dark"><h2 class="display-4" style="text-align: center;">Error Request</h2></div>
                            <hr class="my-4 bg-dark">
                            <div class="ml-3">
                                <h4>Alert: Session has expired</h4>
                                <ul class="details-request-msg">
                                     <li>Error Request Please Try Again!</li>
                                </ul>
                            </div>
                      </div>
                      <div class="w-50 m-auto">
                        <a href="index.php">
                            <button type="submit" class="btn btn-danger mt-1">Back to Home</button>
                        </a>
                     </div>
                      ';
        }
        else{
            echo '<div class="box" id="starter">
            <div class="line-design bg-success rounded-top"></div>
            <div class="jumbotron py-4 rounded-lg text-dark">
                 <h2 class="display-4" style="text-align: center;">Send Request Successfully</h2>
                 <hr class="my-4 bg-dark">
                 <h4><span>'.$_SESSION['natureActivity'].' Form</span> Reference No: <span class="text-danger">'.$_SESSION['refNo'].'</span></h4>
                 <div class="ml-3">
                    <h5></h5>
                    <ol class="details-request-msg">
                        <li>This Ref No. shall be an identification number of your Requested form.</li>
                        <li>It will be saved automatically and viewed in View Status webpage.</li>
                        <li><span class="text-danger">For Organization</span>: Please be advised that other members needs a signature to comply in View Status. </li>
                        <li>Click View Status to check the updates regarding your Reservation Form.</li>
                    </ol>
                </div>
            </div>
         </div>
         <div class="w-50 m-auto">
            <a href="ViewStatus.php">
                <button type="submit" class="btn btn-danger mt-1">View Status</button>
            </a>
            <a href="Includes/DirectForm.php">
                <button type="submit" class="btn btn-success mt-1">Submit Another Form</button>
            </a>
       </div>
         
         ';
       
        //  //print data
        //  foreach($reservationInfo as $info){
        //     echo $info."</br>";
        //   }


        //   if(isset($organizationInfo)){
        //     foreach($organizationInfo as $info){
        //         echo $info."</br>";
        //     }
        //   }

        //   echo "<img src='".$signature."' height='200px' width='350px'>";
        }
    ?>
    

    <div style="min-height: 300px;"></div>
     <footer>
          <p class='text-center text-white pt-3'>Arlene Rioflorido | James Paz | Simon Asino</p>
            <div class="container text-white">
                <div class="row">
                    <div class="col-lg-6 col-md-12" id="footer-flex">
                        <div class="row my-4 justify-content-center">
                            <div class="col-lg-1">
                                <img src="Images/icon(footer)/mail_resized.png" alt="contact">
                            </div>
                            <div class="col-lg-6">
                                <p>Malayan@live.mcl.edu.ph</p>
                            </div>
                        </div>
                        <div class="row my-4 justify-content-center">
                            <div class="col-lg-1">
                                <img src="Images/icon(footer)/contact_resized.png" alt="contact">
                            </div>
                            <div class="col-lg-6">
                                <p>+630217491371</p>
                            </div>
                        </div>
                        <div class="row my-4 justify-content-center">
                            <div class="col-lg-1">
                                <img src="Images/icon(footer)/location_resized.png" alt="contact">
                            </div>
                            <div class="col-lg-6">
                                <p>Pulo Diezmo road, Cabuyao City of Laguna</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 border-left pl-5">
                        <h1>About this site</h1>
                        <hr class="bg-dark">
                        <p class="lead">This a unoffical site, for education purposes only. The online reservation portal is designed to help students and employees in having a quick, easy and effective transaction 
                    on regards to the facilities and equipments of MCL campus. It delivers an overview of submissions, Progress tracker, guidelines and a
                complete automated reservation form.</p>
                    </div>
                    <div class="mt-3 text-lg-center w-100">
                        <p>&copy; Malayan Online Reservation for Facilities & Equipments</p>
                    </div>
                </div>
            </div>
    </footer>
</body>
</html>
