<?php
    session_start();

    if(!isset($_SESSION['login'])){
        header("Location: login.php");
    }

    $splitName= explode(" ",$_SESSION['user_name']);
    
?>
<?php
    
    include_once('Includes/ConnectDB.php');

    $sql="SELECT org.*,sign.*,requester.date_fill,requester.user_no,signature.sao_sign FROM requester_information AS requester
	INNER JOIN organization_information AS org
    	ON requester.ref_no=org.ref_no
    INNER JOIN organization_signature AS sign
       ON requester.ref_no=sign.ref_no
    INNER JOIN signature_information AS signature
        ON requester.ref_no=signature.ref_no
WHERE requester.is_Org is TRUE AND requester.user_no={$_SESSION['user_no']}";

    $result =mysqli_query($conn,$sql);

    echo mysqli_error($conn);
 


    
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
    <div class="space-from"></div>

    <div class="jumbotron jumbotron-fluid bg-theme2">
  <div class="container">
    <h1 class="display-4">Pending Organization</h1>
    <p class="lead">See all Your Corresponding Form Organization</p>
  </div>
</div>
    <div class="org-container shadow">
        <div class='org-header rounded-top'>
            <div class="org-input row d-flex justify-content-end">
                <div class="col-md-4">
                    <input type="text" id='ref_no-search' class="form-control" placeholder="Search Reference No:"> 
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary mt-0" id='btn-search'>Search</button>
                </div>
            </div>
        </div>
        <div id="loading-box">
                    <div class="loading-inner">
                            <img src="Images/loading.gif">
                            <h5>Loading...</h5>
                    </div>
            </div>
        <div id="org-box">
            <?php
                if(mysqli_num_rows($result)>0){
                    while($row=mysqli_fetch_assoc($result)){
                        $status='';
                        if($row['president']==NULL||$row['secretary']!=NULL||$row['treasurer']!=NULL){
                            $status='<span class="text-primary">Pending</span>';
                        }
                        else if($row['sao_sign']==NULL)
                        {
                            $status='<span class="text-warning">In Progress</span>';
                        }
                        else{
                            $status='<span class="text-success">Completed</span>';
                        }

                        $dateFill=date_format(date_create($row['date_fill']),'M d, Y');
                        $template="<div class='org-card my-4 shadow'>
                                          <div class='row '>
                                             <div class='col-md-6 d-flex align-items-center'>
                                                  <h6>Reference No: {$row['ref_no']}</h6>
                                               </div>
                                          <div class='col-md-3 text-center  d-flex align-items-center'>
                                                  <p>{$row['org_name']}</p>
                                          </div>
                                         <div class='col-md-3 text-center  d-flex align-items-center'>
                                                   <p>Date Filled: $dateFill</p>
                                          </div>
                                         </div>
                                        <div style=' width: 90%;'class='m-auto'>
                                           <hr class='bg-secondary'>
                                        </div>
                                        <div class='row'>
                                            <div class='col-md-5'>
                                                <p>Proposed Activity: {$row['org_activity']} </p>
                                            </div>
                                            <div class='col-md-3 text-center'>
                                                <p>$status</p>
                                            </div>
                                            <div class='col-md-4 d-flex justify-content-end'>
                                                <button class='btn-view btn btn-primary mt-0' value='{$row['ref_no']}'> View More</button>
                                            </div>
                                        </div>
                                     </div>
                         ";

                          echo $template;
                    }
                }
                else{
                    echo '<div class="jumbotron jumbotron-fluid">
                    <div class="container">
                      <h1 class="display-4">You have not requested a Organization form yet.</h1>
                      <p class="lead">To take a Form Go to Home Page and Click Button Take a form.</p>
                    </div>
                  </div>';
                }
            
            ?>
        </div>
    </div>
                
    <div class="overlay h-100" id="details-overlay" >
        <div class="modal-flex  container m-auto"> 
            <div id="modal-content" style='margin-top: 180px;'>
            <div class="d-flex justify-content-end">
                <span id="btn-close" class="close">&times;</span>
              </div>
            <div class="Card">
                <div class="jumbotron jumbotron-fluid">
                    <div class="container round">
                        <h1 class="display-4 text-danger">Under Maintenance!</h1>
                        <p class="lead">Please come back again later.</p>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class='py-2'></div>
   
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
    <script src='Script/PendingOrganization.js'></script>
</body>
</html>