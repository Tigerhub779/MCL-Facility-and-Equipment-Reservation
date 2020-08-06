<?php
    session_start();

    if(!isset($_SESSION['login'])){
        header("Location: login.php");
    }

    $splitName= explode(" ",$_SESSION['user_name']);
    
?>
<?php
    include_once('Includes/ConnectDB.php');
    $sql="SELECT reserve.*, facility.facility, facility.room_no FROM reservation_information AS reserve
            INNER JOIN reserve_facility AS facility
            ON  reserve.ref_no=facility.ref_no
     WHERE reserve.reserve_type='Facility' OR reserve.reserve_type='Both'
     ORDER BY DATE(reserve.date_start) ASC
    ";

    $result =mysqli_query($conn,$sql);

    
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
    <h1 class="display-4">Facility Log</h1>
    <p class="lead">See all Reserved Facilities</p>
  </div>
    </div>
    <div class="container">
        <div class="my-2 d-flex justify-content-end ">
            <button id="btn-load" class="btn btn-primary">Load Table</button>
        </div>
    </div>
    <div class='container facility-table shadow'>
        
     <table class="table table-striped ">
        <thead class='text-white' style='background-color: rgb(19, 28, 51);'>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Facility</th>
            <th scope="col">Room No:</th>
            <th scope="col">Date Start</th>
            <th scope="col">Date End</th>
            <th scope="col">Time Start</th>
            <th scope="col">Time End</th>
        </tr>
        <tbody id="facility-table2">
            <?php
                if(mysqli_num_rows($result)>0){
                    $count=1;
                    while($row = mysqli_fetch_assoc($result)){
                        $dateStart =date_format(date_create($row['date_start']),'M d, Y');
                        $dateEnd =date_format(date_create($row['date_end']),'M d, Y');
                        $timeStart =date_format(date_create($row['time_start']),'h:i a');
                        $timeEnd =date_format(date_create($row['time_end']),'h:i a');

                        $room="None";
                        if($row['room_no']!=Null)
                            $room=$row['room_no'];
                        $template="<tr><th scope='row'>$count</th>
                        <td>{$row['facility']}</td>
                        <td>$room</td>
                        <td>$dateStart </td>
                        <td>$dateEnd </td>
                        <td>$timeStart</td>
                        <td>$timeEnd</td>
                        </tr>";

                        echo $template;
                        $count++;
                    }
                }
                else{
                    echo '<tr><div class="jumbotron">
                    <h1 class="display-4">0 Facility Reservation</h1>
                    <p class="lead">There are zero correspoing reservation regarding the facilities. </p>
                  </div></tr>';
                }
            
            ?>
        </tbody>

     </table>

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

    <script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
    <script src="Script/Home.js"></script>
    <script src='Script/FacilityLog.js'></script>
</body>
</html>