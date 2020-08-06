<?php
    session_start();

    if(!isset($_SESSION['login'])){
        header("Location: login.php");
    }

    $splitName= explode(" ",$_SESSION['user_name']);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCL Online Facilities/Equipment Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="Css/Style3.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="icon" href="Images/Mcl_logo.png">
</head>
<body>
    <div class="main-header">
        <header>
            <div class="logo-head d-flex align-items-center">
                <img src="Images/Mcl_logo.png" id="logo" alt="mcl logo">
                <h1>MCL Facilities & Equipment Reservation</h1>
            </div>
            <nav>
                <ul class="d-flex align-items-center user-items">
                    <li><img src="Images/icon(footer)/profile-black.png" style="cursor:auto;" height="52px" width="52px" alt="profile icon"></li>
                    <li ><?php echo $splitName[0]." ".$splitName[count($splitName)-1]?></li>
                    <li class="profile dropdown">
                        <img src="Images/icon(footer)/triangle.png" alt="inverted triangle icon" id="dropdown-img">
                          <div class="dropdown-content">
                             <p class="user-name"><?php echo $_SESSION["user_no"]; ?></p>
                             <p class="status"><?php echo $_SESSION["user_status"]; ?></p>
                             <a href="Includes/Logout.php"><button>Log-out</button></a>
                          </div>
                    </li>
                    <img src="Images/menu.png" id="menu" alt="menu">
                </ul>
            </nav>
        </header>
    
        <section class="sub-menu">
            <div class="profile-display">
                <img src="Images/icon(footer)/profile-white.png"  alt="profile icon">
                <p class="user-name"><?php echo $_SESSION["user_no"]?></p>
                <p class="status"><?php echo $_SESSION["user_status"]?></p>
                <a href="Includes\Logout.php"><button>Log-out</button></a>
            </div>
            <div class="line-menu"></div>
            <ul>
                <a href="index.php"><li>Home</li></a>
                <a href="ViewStatus.php"><li>View Status</li></a>
                <a href="PendingOrganization.php"><li>View Status</li></a>
                 <a href="FacilityLog.php"><li>Signature Request</li></a>
            </ul>

        </section>
        <nav>
            <ul  class="nav-bar">
                <li>
                    <div class="nav-items">
                             <img src="Images/Nav-Icon/home_resized.png">
                        <a href="index.php" style="color: white"><p class="nav-text">Home</p></a>
                    </div>
                </li>
                <li> 
                    <div class="nav-items">
                        <div class="m-auto">
                             <img src="Images/Nav-Icon/status_resized.png" >
                        </div>
                        <a href="ViewStatus.php" style="color: white"><p class="nav-text">View Status</p></a>
                    </div>
                </li>
                <li> 
                    <div class="nav-items">
                        <div class="m-auto">
                            <img src="Images/Nav-Icon/org_resized.png" >
                        </div>
                        <a href="PendingOrganization.php" style="color: white"><p class="nav-text">Pending Organization</p></a>
                    </div>
                </li>
                <li>
                  <div class="nav-items">
                        <div class="m-auto">
                            <img src="Images/Nav-Icon/facility_resized.png" >
                        </div>
                        <a href="FacilityLog.php" style="color: white"><p class="nav-text">Facility Log</p></a>
                    </div>
                </li>
            </ul>
         </nav>
    </div>

    <img src="Images/icon(footer)/up.png" width='64' height='64' alt="arrow-up icon" id="up-button">
    
    <div >
        <section class="take-form">
            <div class="head-details">
                <h2>Reservation is now Available Online!</h2>
                <div class="mt-4">
                    <p>Welcome to the newly functional online facilities and equipments reservation especially made for students and </br>employees to make reservation quick and easy!</p>
                </div>
                <p>Reserve Now!</p>
            </div>
            <div class="button-details">
            <a href="Form.php"><button id="take-form">Take a Form</button></a>
            </div>
        </section>

        <section class="instruction">
                <h2>How to Reserve? </h2>
                <div class="line"></div>
                <div class="card-group-custom">
                <div class="card-custom">
                        <div class="card-header">
                            <p>Step 1: Read</p>
                        </div>
                        <div class="card-img">
                            <img src="Images/read_unresized.png" class="img-fluid" alt="test image only not final">
                        </div>
                        <div class="card-details">
                            <p>Take time to read the data privacy statements, statements of consent and instructions regarding the permit to use. This step is necessary for safety and security purposes.</p>
                        </div>
                </div>
                <div class="card-custom">
                        <div class="card-header">
                            <p>Step 2: Fill</p>
                        </div>
                        <div class="card-img">
                            <img src="Images/fill_unresized.png" alt="test image only not final">
                        </div>
                        <div class="card-details">
                        <p>Fill all required fields with valid information. There will be a mark optional for fields that are not required. Take time and fill up the form with correct information as possible.</p>
                        </div>
                    </div>
                    <div class="card-custom">
                        <div class="card-header">
                            <p>Step 3. Submit </p>
                        </div>
                        <div class="card-img">
                            <img src="Images/submit_unresized.png" alt="test image only not final">
                        </div>
                        <div class="card-details">
                            <p>After filling all required fields, save and submit the form. We are requiring eveyone to submit their signature also. 
                                Remember to Keep track of the completion in "View Status" section.</p>
                        </div>
                    </div>
                    <button class="more-info">More Info</button>
                </div>
        </section>

        <section class="razor" >
            <div class="details pt-4">
                <h1 class='mb-2'>Track your Progress !</h1>
                <p class='text-justify'>In View Status section, you will be able to track the status of your request, view form submission details, reveiwing canceled request and list of forms submitted
                    . When the all admin successfully verified it and sign the form requested. There will be a button to download the completed form with signature as a pdf copy. So be sure to always checked the requested form
                    in the view status.
                </p>
                <p>To access the view status page, click it on the navigation menu or click it on this button below.</p>
                <a href="ViewStatus.php"><button id="view-status"> Go to View Status</button></a>
            </div>
            <div class="img">

            </div>
        </section>

        <section class="finished">
                <h2>After Verification Process</h2>
                <img src="Images/facilities.jpg" alt="facilities">
                <h2> The <span style="color: rgb(223, 45, 45);">Autogenerated Copy</span>  will serve as a proof of the Reservation</h2>
        </section>
    </div>
    <footer>
        <p class='text-center pt-3'>Arlene Rioflorido | James Paz | Simon Asino</p>
        <div class="footer-main">
            <div class="contact">
                <img src="Images/icon(footer)/mail_resized.png">
                <p>Malayan12@outlook.com</p>
                <img src="Images/icon(footer)/contact_resized.png">
                <p>+63978163212</p>
                <img src="Images/icon(footer)/location_resized.png">
                <p>Pulo diezmo road Cabuyao City, Laguna</p>
            </div>
            <div class="about text-justify">
                <div class="text-center">
                <h2>About this website</h2>
                </div>
                <p> This a unoffical site, for education purposes only. The online reservation portal is designed to help students and employees in having a quick, easy and effective transaction 
                    on regards to the facilities and equipments of MCL campus. It delivers an overview of submissions, Progress tracker, guidelines and a
                complete automated reservation form.</p>
            </div>
        </div>
        <div class="lower-details">
          <p>Malayan College Laguna Online Facilities & Equipment Reservation</p>
          <p>	&copy; <?php echo date('Y')?> All rights and reserve</p>
       </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
    <script src="Script/Home.js"></script>
</body>
</html>