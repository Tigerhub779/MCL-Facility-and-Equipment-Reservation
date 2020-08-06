<?php
   include_once('Includes/dbt.inc.php');
   $splitName= explode(" ",$_SESSION['user_name']);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>MCL Online Facilities/Equipment Reservation</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="Css/Style3.css">
    <link rel="stylesheet" href="Css/ViewStatus_style.css">
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
    <h1 class="display-4">View Status</h1>
    <p class="lead">See all Your Requested Forms</p>
  </div>
    </div>
    <div class="loading-overlay" id="loading">
        <div class="loading-container">
            <img src="Images/loading.gif" alt="Loading">
            <div class='mt-2 '>
                <h5>Loading...</h5>
            </div>
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
        <div id="view-status-box">
        <?php



              if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)){       
                        $total=0;
                        $pending = 0;
                        $approved = 0;
                        $rejected = 0;
                        $arrayColumn = array('dept_status', 'ao_status', 'lmo_status', 'cdmo_status');
                        foreach ($arrayColumn as $column){
                            if($row[$column]=='PENDING'){
                                $pending++;
                            }
                            else if($row[$column]=='APPROVED')
                                $approved++;
                            else if($row[$column]=='REJECTED'){
                                $rejected++;
                            }
                            else if($row[$column]=='TRASHED'){
                                $nameColumnSign=str_replace('status', 'sign', $column);
                                if($row[$nameColumnSign]!=NULL){
                                            $approved++;
                                }
                                else{
                                    $rejected++;
                                }
                            }
                       
                            if($row[$column]!=NULL)
                                $total++;
                        }
                        if($rejected > 0) {
                          $status = 'DENIED';
                          $color = '#FF0000';
                        }elseif($total==$approved){
                            $status = 'COMPLETE';
                            $color = '#008000';
                        } else if($approved>0){
                            $status = 'IN PROGRESS';
                            $color = '#FFA500';
                       }else{
                          $status = 'PENDING';
                          $color = '#FFD700';
                        }

                          echo sprintf($template,$row['ref_no'], $row['nature_act'], $color, $status, date_format(date_create($row['date_fill']),'M d, Y'), $pending,date_format(date_create($row['date_start']),'M d, Y'),
                          date_format(date_create($row['date_end']),'M d, Y'),date_format(date_create($row['time_start']),'h:i A') ,date_format(date_create($row['time_end']),'h:i A'),$row['ref_no'],$row['ref_no']
                          ,$row['ref_no']);          
                          
                        }
                    }
                    else{
                        echo '<div class="jumbotron jumbotron-fluid">
                        <div class="container">
                          <h1 class="display-4">You have not requested a form yet.</h1>
                          <p class="lead">To take a Form Go to Home Page and Click Button Take a form.</p>
                        </div>
                      </div>';
                    }
              ?>
        </div>
    </div>
    
    <div class="overlay z-4 " id="details-overlay" >
        <div class="modal-flex h-100"> 
            <div id="modal-content" >
                <div class="bg-colorTheme rounded-top px-3">
                    <div class='d-flex justify-content-between'>
                         <h6>Reference No: #00000000</h6>
                        <span id='search-close' class="close text-white">&times;</span>
                    </div>
                </div>
                <div class='row bgcolorTheme2'>
                    <div class='col-md-4 text-center'>
                        <p class='mb-0'>DATE SUBMITTED </p>
                        <p class='font-weight-bolder'>21 May 2020</p>
                    </div>
                    <div class='col-md-4 text-center'>
                        <p class='mb-0'>STATUS </p>
                        <p class='font-weight-bolder'>COMPLETED</p>
                    </div>
                    <div class='col-md-4 text-center'>
                        <p class='mb-0'>EXPECTED DATE</p>
                        <p class='font-weight-bolder'>May 30 2020 - Jun 3 2020</p>
                    </div>
                </div>
                <div class='mt-3 d-flex justify-content-center'>
                        <img src="Images\icons (view status)\status (indicator)\with words\5.png" id="img-indicator" class="img-fluid">
                </div>
                <div class='mt-3 d-flex justify-content-center'>
                        <button class="btn btn-secondary">Get PDF File</button>
                </div>
                <span class="mt-2">
                  <p class="text-center">1 of 4 signatures Signed</p>
                </span>

                <div class="container">
                    <div>
                        <h5>History</h5>
                    </div>
                    <table class="table table-stripped">
                        <thead class="bgcolorTheme2">
                            <tr class='text-center'>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Recepient</th>
                            <th scope="col">Status</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
          </div>
    </div>
    <div class='overlay z-4' id="details-comment" >
        <div class="modal-flex h-100"> 
                
                <div class='comment-card rounded p-3'>
                    <div class='d-flex justify-content-end'>
                    <span class='Close' id="close-comment">&times;</span>
                    </div>
                    <h5>Admin-Comment:</h5>
                    <div class='ml-2'>
                        <textarea class="form-control"id='comment-text' readonly></textarea>
                    </div>
                </div>
        </div>   
    </div>

    <div class='py-3'></div>
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


<!--- Script Source Files -->
<script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
<script src="Script/ViewStatus.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<!--- End of Script Source Files -->

</body>
</html>