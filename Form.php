<?php
    session_start();

    // if(!isset($_SESSION['login'])){
    //     header("Location: login.php");
    // }
    if(isset($_SESSION['startSession'])&&isset($_SESSION['msgRequest'])){
        header("Location: FormMsg.php");
    }   
    else
       $_SESSION['startSession']=true;

?>
<?php
  $administriveOffice=array(
         "Jose P. Rizal Hall Lobby"=>false,
        "Jose P. Rizal Airwell"=>false,
        "Track Oval"=>false,
        "Basketball Court"=>false
        ,"Volleyball Court"=>false
        ,"E.T. Yuchengo Lobby"=>false
        ,"Shannon Drive"=>false
        ,"Einstein Drive"=>false
        ,"Lecture Room"=>true
        ,"Other"=>true
    );

  $LMOOffice=array(
      "Auditorium"=>true,
      "Drafting Room"=>true,
      "Photography Studio" =>true,
      "Physics/IE Laboratory" =>true,
      "Food Laboratory" =>true,
      "Cafe Enrique" =>true,
      "Chemistry Lab"=> true,
      "EEC Laboratory" =>true,
      "IT Laboratory" =>true,
      "ETY Hotel Room" =>true,
      "MC Laboratory" =>true,
      "CMET Lab" => true,
      "Other" => true
  );

   $roomsInfo =array(
       "Auditorium" =>array("R504"),
       "Drafting Room" =>array("R505"),
       "Photography Studio"=>array("R506"),
       "Physics/IE Laboratory"=>array("R414"),
       "Food Laboratory"=>array("E205"),
       "Cafe Enrique"=>array("E200"),
       "Chemistry Lab"=>array("R401","R402","R403"),
       "EEC Laboratory"=>array("R404","R405","R410"),
       "IT Laboratory"=>array("R310","R311","R312"),
       "ETY Hotel Room"=>array("Y200","Y201"),
       "MC Laboratory"=>array("R500","R501"),
       "CMET Lab"=>array("E500","E501")

   );


  $template='<div class="my-2 col-md-12 row form-check d-flex align-items-center ">
  <input type="radio" name="venue" value="%s">
  <label class="pl-2 col-md-8">%s</label>
  </div>';

  $modaltemplate="
  <div class='modal-format-info shadow'>
      <div class='pl-3 py-2 modal-format-header'>
        <h4 class='text-dark' id='%s'>Room No: Format</h4>
      </div>
      <ul class='py-2 pr-2'>
        <li>Must contain first capital letter of bldg + 3 digit number</li>
        <li>R = Rizal Bldg, E= Einstein Bldg, Y= ETY Bldg</li>
        <li>Ex: R400, R300, E201, E301</li>
      </ul>
   </div>
  ";
  $template2 ='<div class="my-2 w-100  form-check row d-flex align-items-center ">
                    <div id="%s" class="modal-format-info shadow">
                        <div class="pl-3 py-2 modal-format-header">
                            <h4 class="text-dark">Room No: Format</h4>
                        </div>
                        <ul class="py-2 pr-2">
                            <li>Must contain first capital letter of bldg + 3 digit number</li>
                            <li>R = Rizal Bldg, E= Einstein Bldg, Y= ETY Bldg</li>
                            <li>Ex: R400, R300, E201, E301</li>
                        </ul>
                    </div>
                  <input type="radio" class="col-md-1" name="venue" value="%s">
                  <label class=" col-md-4 mr-2">%s</label>
                    <div class="col-md-6"> 
                        <input type="text" id="%s" class="form-control roomInput" placeholder="%s" readonly>
                        <div class="invalid-feedback">Invalid Format</div>
                   </div>
              </div>';


  $template4='<div class="my-2 col-md-12  form-check row d-flex align-items-center">
  <input type="radio" class="col-md-0" name="venue" value="%s">
  <label class=" col-md-3 mr-2">%s</label>
  <div class="col-md-9"> 
  <input type="text" id="%s" class="form-control"
              readonly>
  <div class="invalid-feedback" id="%s"></div>
  </div>
 </div>';

 $template3='<div class="my-2 w-100 row d-flex align-items-center">
 <input type="radio" class="col-md-1" name="venue" value="%s">
 <label class="col-md-4 ">%s</label>
 <div class="col-md-7"> 
 <select id="%s" class="form-control select-room" readonly>
    %s
 <select>
 </div> </div>';
  $isOther=false;

  function printVenue($Office){
        $template=$GLOBALS["template"];
        $template2=$GLOBALS["template2"];
        $template3=$GLOBALS["template3"];
        $template4=$GLOBALS['template4'];
        $roomsInfo=$GLOBALS['roomsInfo'];
        $administriveOffice=$GLOBALS['administriveOffice'];
        $LMOOffice=$GLOBALS['LMOOffice'];
        $modaltemplate =$GLOBALS["modaltemplate"];

        foreach($Office as $venue=>$val){
            $valueInput=preg_replace("/\s/","-",$venue);
            $valueInput=preg_replace("/[\\(\\)\\/]/","",$valueInput);
            
            if($venue=='Other'){
                if($GLOBALS['isOther'])
                echo sprintf($template4,"LMO-".$valueInput,$venue,"LMO-".$valueInput,"LMO-".$valueInput."Msg");
                else{
                    echo sprintf($template4,"AO-".$valueInput,$venue,"AO-".$valueInput,"AO-".$valueInput."Msg");
                    $GLOBALS['isOther']=true;
                }
            }
            else if($venue=="Lecture Room"){
                echo sprintf($template2,"AO-".$valueInput."ModalHelp","AO-".$valueInput,$venue,"AO-".$valueInput,"Room No: ");
            }
            else if($val){
                if(isset($administriveOffice[$venue])){
                    $add="AO-";
                }
                else if(isset($LMOOffice[$venue])){
                    $add="LMO-";
                }

              echo sprintf($template3,$add.$valueInput,$venue,$add.$valueInput,fillValues($roomsInfo[$venue]));
            }
            else{
                if(isset($administriveOffice[$venue])){
                    $add="AO-";
                }
                else if(isset($LMOOffice[$venue])){
                    $add="LMO-";
                }

                echo sprintf($template,$add.$valueInput,$venue);
            }
        }
  }

  function fillValues($arrayRooms){
        $optionval="";
        foreach($arrayRooms as $rooms){
            $optionval.="<option value='".$rooms."'>".$rooms."</option>";
        }
        return $optionval;
  }
?>
<?php 
    $itemsAdminOff= array(
        "Projector",
        "Speaker",
        "Microphone",
        "Chairs",
        "Tables",
        "Panel Board"
    );

    $itemsLabManOff=array(
        "VideoCam",
        "Computer",
        "Round Table",
        "CockTail"
    );

    function getItems($items,$type){
        $templateItem1='<div class=" my-2 row d-flex align-items-center">
        <input name="equipments" type="checkbox" class="col-md-0 mx-3" value="%s">
        <label id="%s" class="col-md-4">%s</label>
        <div class="col-md-6">
        <input  id="%s" type="number"  placeholder="Quantity" class="form-control w-100 qty-input" min="1" max="100" readonly>
        <div class="invalid-feedback">Please provide a number greater than 0</div>
        </div>
        </div>';

        foreach($items as $equipments){
            $val=preg_replace("/\s/","-",$equipments);
            echo sprintf($templateItem1,$type.$val,$type.$equipments,$equipments,$type.$val."-Qty");
        }
    }
?>
<!DOCTYPE html>
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
    <div class="loading-overlay" id="loading">
        <div class="loading-container">
            <img src="Images/loading.gif" alt="Loading">
            <p>Loading...</p>
        </div>
    </div>
    <div class="box" id="starter">
               <div class="line-design bg-danger rounded-top"></div>
               <div class="jumbotron py-4 rounded-lg text-dark">
                   <h2 class="display-4" id="progressStatus" style="text-align: center;">Your Progress</h2>
                   <hr class="my-4 bg-dark">
                   <img src="Images/Indicator/P1.png" class="img-fluid" id="img-indicator">
                   <ul class="row justify-content-between details-indicators" >
                       <li class="col-xl-2 col-lg-2 col-md-2  col-sm-2">Terms and Conditions</li>
                       <li class="col-xl-2 col-lg-2 col-md-2  col-sm-2">Profile Information</li>
                       <li class="col-xl-2 col-lg-2 col-md-2  col-sm-2">Facilities</li>
                       <li class="col-xl-2 col-lg-2 col-md-2  col-sm-2">Equipments</li>
                       <li class="col-xl-2 col-lg-2 col-md-2  col-sm-2">Review</li>
                   </ul>
               </div>
     </div>

    <section class="box" id="terms-conditionBox">
                   <div class="line-design rounded-top"></div>
                   <div class="container-form text-dark">
                    <div class="container-text">
                        <h2 class="my-3">Data Privacy Statement</h2>
                        <p>MALAYAN COLLEGES LAGUNA, INC. commits compliance to the Republic Act No. 10173, otherwise known as the Data Privacy Act of 2012. MCL recognizes its responsibilities under the same Act and upholds its commitment to protect the integrity and security of the data being collected, recorded, organized, updated, used, consolidated or destructed from its students, applicants, and other external clients. Please be assured that any personal data obtained from this online admissions portal will be generated and stored in secured systems as warranted by the DPA. MCL warrants that it has organizational, technical and physical security measures to ensure the protection of the gathered data, and the treatment of the same with utmost confidentiality. Furthermore, the information collected and stored in the portal shall only be used for the admissions application of students interested in studying at MCL, and for the Admissions Office to contact applicants for advisories, announcements, and/or release of results pertinent to the admissions process of the Institute.</p>

                        <h2 class="my-3">Statement of Consent</h2>
                        <p>By accessing this portal, filling out the form, and submitting the data online, the user hereby confirms that MCL’s Data Privacy Statement has been read and understood; and as a result, the user’s consent is given to collect, record, organize, update or modify, retrieve, consult, use, consolidate, block, erase or destruct the personal data they have submitted for admissions purposes. The user hereby affirms their right to be informed, object to processing, access and rectify, suspend or withdraw their personal data, and be indemnified in case of damages, pursuant to the provisions of the Republic Act No. 10173 of the Philippines, Data Privacy Act of 2012 and its corresponding Implementing Rules and Regulations.</p>

                        
                        <h2 class="my-3">Permit to use</h2>
                        <ol>
                            <li>THIS FORM MUST BE FILLED OUT AND MUST BE DULY APPROVED PRIOR TO THE USE OF ANY OF THE FACILITIES OF MCL.</li>
                            <li>THE USE OF SUCH EQUIPMENT AND/OR FACILITIES ARE PRIVILEDGED AND SUBJECT TO THE DISCRETION OF MCL.</li>
                            <li>THE REQUESTER SHALL BE HELD ACCOUNTABLE IN CASE OF BREAKAGE AND/OR LOSSES DURING THE TIME OF USE. THE REQUESTER AGREES TO REPLACE THE ITEM(S) WITH THE SAME BRAND OR ITS EQUIVALENT.</li>
                            <li>FOR STUDENT ORGANIZATIONS, PLEASE ATTACH APPROVED STUDENT ACTIVITY FORM FROM THE OFFICE FOR STUDENT SERVICES.</li>
                            <li>FOR CO-CURRICULAR ACTIVITES/EVENTS, ONLY INSTRUCTORS/PROFESSORS SHALL BE ALLOWED TO USE THE FACILITIES OR EQUIPMENT. </li>
                            <li>REQUESTER AGREES TO RETURN BORROWED ITEM(S) ON THE DATE INDICATED IN THIS FORM. </li>
                            <li>A GATEPASS FORM SHALL BE SECURED AT THE SECURITY OFFICE (ATTACHED WITH THIS FORM) FOR ANY BORROWED ITEMS THAT WILL BE USED OUTSIDE MCL.</li>
                        </ol>
                    </div>
                </div>
                <!-- form -->
                   <div>
                       <div class="mb-4 py-2 upper-border rounded-bottom">
                            <input type="checkbox" id="terms-condition" class="ml-4">
                            <label class="form-txt">I have read and agree to the terms and conditions.</label>
                            <label class="invalid-feedback ml-4" id="terms-conditionMsg">*Require to Agree on terms and Conditions to continue</label>
                        <div>
                   </div>
                   <!-- form -->
    </section>
    <section class="box profileBox " id="profileForm">
        <div class="line-design rounded-top"></div>
        <!-- Form -->
        <div class="p-3">
            <div class="form-group row">
                <label class="col-12">Requester's Name</label>
                <div class="col-md-12">
                     <input type="text" id="requesterFNameId" pattern="[a-zA-Z]" name="important" class="form-control" 
                     value="<?php echo $_SESSION['user_name'];?>" readonly>
                     <div class="invalid-feedback" id="requesterFNameIdMsg">Please provide your First Name</div>
                </div>
                <!-- <div class="col-lg-6">
                  <input type="text" id="requesterLNameId"  name="important" class="form-control" value="Paz" readonly>
                  <div class="invalid-feedback" id="requesterLNameIdMsg">Please provide your Last Name</div>
                </div> -->
            </div>
            <div class="form-group">
                <label>Date of Filling</label>
                <input type="date" id="dateTodayFilling" name="important" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label>Office College Department</label>
                <select class="form-control" id="departmentSelect">
                    <option>Choose a Department</option>
                    <option>SHS</option>
                    <option>CAS</option>
                    <option>CMET</option>
                    <option>CCIS</option>
                    <option>ETY</option>
                    <option>MITL</option>
                </select>
                <div class="invalid-feedback" >Please choose a department</div>
            </div>
          
        </div>
    </section>
    <section class="box profileBox">
         <div class="line-design rounded-top"></div>
         <!-- Form -->
         <div class="mx-3 mt-3">
            <div class="form-group">
                    <label>Nature of Activity/Purpose</label>
                    <input type="text" id="natureActivity" class="form-control" placeholder="Event/Lecture/Co-Curricular" >
                    <div class="invalid-feedback" id="natureActivityMsg">Please input the Activity/Purpose</div>
                </div>
                <div class="form-group">
                    <label>Is this form for Organization Purposes?</label>
                    <div class="form-check form-check-inline radio">
                        <input class="form-check-input" type="radio" name="orgRadio" value="Yes"> 
                        <label class="form-check-label">Yes</label>
                    </div>
                    <div class="form-check form-check-inline radio">
                        <input class="form-check-input" type="radio" name="orgRadio" value="No">
                        <label class="form-check-label">No</label>
                    </div>
                    <div class="invalid-feedback" id="org-Msg">*Require to select an Option</div>
                </div>
                <div class="form-group">
                    <label>I am reserving for an </label>
                    <div class=" ml-2 form-check form-check-inline ">
                        <input class="form-check-input" type="checkbox" name="reserve-type" value="facility" >
                        <label class="form-check-label">Facility</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="reserve-type" value="equipment">
                        <label class="form-check-label">Equipments</label>
                    </div>
                    <div class="invalid-feedback" id="reserve-Msg">*Require to atleast check one</div>
                </div>
         </div>   
    </section>
    <section class="box orgBox ">
     <div class="line-design rounded-top"></div>
     <!-- form -->
     <div class="px-3 py-4">
        <div class="row">
            <label class="col-md-5">Official Full Name of Organization</label>
            <div class="col-md-12">
                 <input type="text" id="orgName" class="form-control" placeholder="Name of the Org">
                 <div class="invalid-feedback" id="orgNameMsg"></div>
            </div>
                <label class="col-md-1 mt-3">President: </label>
                <input type="text" id="presName" class="col-md-6 form-control ml-md-5 mt-md-3" placeholder="Full Name">
                <div class="invalid-feedback" id="presNameMsg"></div>
        </div>
        <div class="row">
          <label class="col-md-1 mt-3">Secretary: </label>
          <input type="text" id="secName" class=" col-md-6 form-control ml-md-5 mt-md-3" placeholder="Full Name">
          <div class="ml-2 invalid-feedback" id="secNameMsg"></div>
        </div>
        <div class="row">
          <label class="col-md-1 mt-3">Treasurer: </label>
          <input type="text" id="tresName" class=" col-md-6 form-control ml-md-5 mt-md-3" placeholder="Full Name">
          <div class="ml-2 invalid-feedback" id="tresNameMsg"></div>
        </div>
     </div>
     <!-- end form -->
    </section>
    <section class="box orgBox ">
      <div class="line-design rounded-top"></div>
      <!-- form -->
      <div class="p-3">
        <div>
            <label>Proposed Activity</label>
            <input type="text" id="activityName" class="form-control" placeholder="Name of your Org Activity">
            <div class="invalid-feedback" id="activityNameMsg"></div>
        </div>
        <div class="mt-3">
            <label>Objective of Activity</label>
            <textarea class="form-control" id="orgObjectives" placeholder="Type here your Objectives"></textarea>
            <div class="invalid-feedback" id="orgObjectivesMsg"></div>
        </div>
        <div class="mt-3">
            <label>Details of Activity</label>
            <textarea class="form-control" id="orgDetails" placeholder="Insert here the Specific Details"></textarea>
            <div class="invalid-feedback" id="orgDetailsMsg"></div>
        </div>
      </div>
      <!-- end form -->
    </section>

    <!-- ajax -->
    <section class="box venueBox2 d-none" id="venueRef">
        <div class="line-design rounded-top"></div>
       
        <div class="p-2">
            <h3>Venue Log</h3>
            <table class="table table-hover">
                <thead>
                    <th>Venue</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                </thead>
                <tbody>
                    <tr>
                        <td>JP Rizal Bldg.[R302]</td>
                        <td>MCL ACM Meeting</td>
                        <td>14/01/21</td>
                        <td>04:00 PM - 0:5:30 PM</td>
                    </tr>
                    <tr>
                        <td>_</td>
                        <td>_</td>
                        <td>_</td>
                        <td>_</td>
                    </tr>
                    <tr>
                        <td>_</td>
                        <td>_</td>
                        <td>_</td>
                        <td>_</td>
                    </tr>
                    <tr>
                        <td>_</td>
                        <td>_</td>
                        <td>_</td>
                        <td>_</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>


    <section class="box dateBox" id="dateTimeForm">
        <div class="line-design rounded-top"></div>
        <!-- form -->
        <div class="p-3">
           
            <div class="form-group row">
                <label class="col-md-12">Date of Use</label>
                <div class="col-md-6 d-flex">
                     <label class="mx-2">Start:</label>
                     <div class="w-100">
                          <input type="date" id="dateStart" class="form-control" >
                          <div id="dateStartMsg" class="ml-4 invalid-feedback"></div>
                     </div>
                </div>
                <div class="col-md-6 d-flex ">
                    <label class="mx-2">End:</label>
                    <div class="w-100">
                         <input type="date" id="dateEnd" class="form-control" >
                         <div id="dateEndMsg" class="ml-4 invalid-feedback"></div>
                    </div>
                          
                </div> 
                <!-- <div id="dateStartMsg" class="col-md-12 mt-2 align-center invalid-feedback">Hello this is warning</div> -->
            </div>
            <div class="form-group row">
                <label class="col-md-12">Time of Use</label>
                <div class="col-md-6 d-flex">
                     <label class="mx-2">Start:</label>
                     <div class="w-100">
                        <input type="time" id="startTime" class="form-control" >
                        <div class="invalid-feedback" id="startTimeMsg"></div>
                     </div>
                </div>
                <div class="col-md-6 d-flex">
                    <label class="mx-2">End:</label>
                    <div class="w-100">
                        <input type="time" id="endTime" class="form-control" >
                        <div class="invalid-feedback" id="endTimeMsg"></div>
                    </div>
                </div> 
            </div>
        </div>
        <!-- end form -->
    </section>
    <section class="box venueBox" id="venues">
            <div class="line-design rounded-top p-2 text-white">
                 <h2>Select a Facility</h2>
            </div>
            <!-- form -->
            <div id="venueForm">
                <div class="p-2  venueColorContainer">
                    <div class="row m-2 mt-3" >
                        <div class="col-md-6 designVenue rounded">
                                <h3 class="pb-3">Administrative Office</h3>
                            <div class="row venue-container w-100 rounded">
                                <?php
                                printVenue($administriveOffice);
                                ?>
                            </div>
                        </div>
                        <div class="col-md-6 designVenue w-100 rounded">
                            <h3  class="pb-3">Laboratory Management Office</h3>
                            <div class="row venue-container w-100 rounded" >
                                <?php
                                    printVenue($LMOOffice)
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end form -->
    </section>
    <section class="box minH-auto mb-4 venueBox" id="venueBoxRemarks">
      <div class="line-design rounded-top"></div>
      <div class="m-2">
      <!-- form -->
        <div class="pb-2">
            <div class="d-flex justify-content-between">
               <h4>Remarks/Special Instruction for Facilities (Optional)</h4>
               <img id="venueRemarksIndicator" onclick="remarksVenues()" src="Images\Form\plus.png">
           </div>
           <textarea class="form-control" id="venueRemarks" placeholder="Note: Writing your Remarks here for your chosen Venue....."></textarea>
        </div>
        <!-- end form -->
      </div>
    </section>
    <section class="box equipmentForm">
      <div class="line-design rounded-top p-2 text-white">
          <h3>Select Equipments</h3>
      </div>
      <!-- form -->
      <div>
        <div class="m-3">
            <div class="equipments row m-3">
                <div class="col-md-6">
                  <h3 class="pb-3">Administrative Office</h3>
                    <div class="item-container rounded" id="AO-itemAdd">
                        <?php
                            getItems($itemsAdminOff,"AO-");
                        ?>
                    </div>
                    <div class="my-4 w-25 addBtnItem ">
                      <button class="btn btn-info" id="AO-itemAdd-btn">Add(+)</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <h3  class="pb-3">Laboratory Management Office</h3>
                    <div class="item-container rounded" id="LMO-itemAdd">
                        <?php
                            getItems($itemsLabManOff,"LMO-");
                        ?>
                    </div>
                    <div class="my-4 w-25 addBtnItem ">
                      <button class="btn btn-info" id="LMO-itemAdd-btn" >Add(+)</button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    <!-- end form -->
    </section>
    <section class="box minH-auto equipmentForm my-2"  id="equipmentBoxRemarks">
      <div class="line-design rounded-top"></div>
      <div class="m-2">
      <!-- form -->
        <div class="pb-2">
            <div class="d-flex justify-content-between">
               <h4>Remarks/Special Instruction for Equipments (Optional)</h4>
               <img id="equipmentRemarksIndicator" onclick="remarksEquipment()"  src="Images\Form\plus.png">
           </div>
           <textarea class="form-control" id="equipmentRemarks" placeholder="Note: Writing your Remarks here for your chosen Equipments....."></textarea>
      </div>
      <!-- end form -->
    </section>

    <!-- form -->
    <form  action="Includes/DirectDb.php" method="post" enctype="multipart/form-data">
    <section class="box finalBox" id="main-form">
        <div class="line-design rounded-top p-2"></div>
        <div class="m-2">
            <h2>Review of Information</h2>
            <div class="nav-tab rounded-top px-2 pt-2">
                <h4 id="review-reservation" class="review-nav-tab on-review-tab">Reservation</h4>
                <h4 id="review-org" class="review-nav-tab">Organization</h4>
            </div>
                <div id="Reservation-Form">
                    <div class="bg-colorTheme rounded text-white" >
                        <h3 class="mx-2">Profile Information</h3>
                    </div>
                    <div class="px-3">
                        <div class="row">
                            <label class="col-md-3">Requester's Name :</label>
                            <div class="col-md-6">
                                <input type="text" id="review-name" name="Requester-name" class="form-control important" value="" readonly>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label class="col-md-3">Date of Filling :</label>
                            <div class="col-md-6">
                                <input type="date" id="review-date-filling" name="date-filling" class="form-control important" readonly>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label class="col-md-4">Office College Department :</label>
                            <div class="col-md-6">
                                <input type="text" id="review-department" name="department" class="form-control important" value="" readonly>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label class="col-md-3">Nature of Activity :</label>
                            <div class="col-md-7">
                                <input type="text" id="review-natureActivity" name="nature-activity" class="form-control important" value="" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Is this form for Organization Purposes?</label>
                            <div class="form-check form-check-inline radio">
                                <input class="form-check-input" type="radio" name="radio-review" value="Yes" disabled> 
                                <label class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check form-check-inline radio">
                                <input class="form-check-input" type="radio" name="radio-review" value="No" disabled>
                                <label class="form-check-label">No</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>I am reserving for an </label>
                            <div class=" ml-2 form-check form-check-inline ">
                                <input class="form-check-input" type="checkbox" name="review-reserve-type" value="facility" disabled>
                                <label class="form-check-label">Facility</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" name="review-reserve-type" value="equipment"  disabled>
                                <label class="form-check-label">Equipments</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-colorTheme rounded text-white" >
                        <h3 class="mx-2">Reservation Information</h3>
                    </div>
                    <div class="px-3">
                        <div class="row my-3">
                            <label class="col-md-3">Date of Use:</label>
                            <input type="date" id="review-date-useStart" name="date-Of-UseStart" class="form-control col-md-4 important" readonly>
                            <label class="col-md-1 text-center">to</label>
                            <input type="date" id="review-date-useEnd" name="date-Of-UseEnd" class="form-control col-md-4 important" readonly>
                        </div>
                        <div class="row my-3">
                            <label class="col-md-3">Time of Use:</label>
                            <input type="time" id="review-time-useStart"  name="time-of-UseStart" class="form-control col-md-4 important" readonly>
                            <label class="col-md-1 text-center">to</label>
                            <input type="time" id="review-time-useEnd"  name="time-of-UseEnd" class="form-control col-md-4 important" readonly>
                        </div>
                        <div class="row my-3">
                            <label class="col-md-3">Proposed Facility :</label>
                            <div class="col-md-6">
                                <input type="text" id="review-facility" name="Proposed-Facility" class="form-control important"  readonly>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label class="col-md-3">Proposed Room :</label>
                            <div class="col-md-6">
                                <input type="text" id="review-room"  name="Proposed-room" class="form-control important" readonly>
                            </div>
                        </div>
                        <label>List of Equipments :</label>
                        <div class="row my-3">
                            <div class="col-md-6">
                                <h5 class="text-center">Administrive Office</h5>
                                <textarea class="form-control important" id="review-equipments-AO" name="list-Of-Equipments-AO" readonly></textarea>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-center">Laboratory Management Office</h5>
                                <textarea class="form-control important" id="review-equipments-LMO" name="list-Of-Equipments-LMO" readonly></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-colorTheme rounded text-white" >
                        <h3 class="mx-2">Remarks</h3>
                    </div>
                    <div class="px-3">
                        <div class="row my-3">
                            <div class="col-md-6">
                            <h4 class="text-center">Facility</h4>
                            <textarea class="form-control  m-auto important" id="review-venue-remarks" name="venueRemarks"  readonly></textarea>
                            </div>
                            <div class="col-md-6">
                            <h4 class="text-center">Equipments</h4>
                            <textarea class="form-control m-auto important" id="review-equipments-remarks" name="equipmentsRemarks"  readonly></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="Organization-Form" >
                    <div class="bg-colorTheme rounded text-white" >
                        <h3 class="mx-2">Organization Details</h3>
                    </div>
                    <div class="jumbotron" id="organization-none">
                        <h3>None of the Information was recorded</h3>
                    </div>
                    <div class="px-3" id="organization-review-form">
                        <div class="row">
                            <label class="col-md-5">Official Full Name of Organization</label>
                            <div class="col-md-12">
                                <input type="text" name="org-Full-Name" id="review-orgFullName" class="form-control important"  readonly>
                            </div>
                                <label class="col-md-1 mt-3">President: </label>
                                <input type="text" name="pres-name" id="review-pres-name"  class="col-md-6 form-control ml-md-5 mt-md-3 important" readonly>
                        </div>
                        <div class="row">
                            <label class="col-md-1 mt-3">Secretary: </label>
                            <input type="text" name="sec-name" id="review-sec-name" class="col-md-6 form-control ml-md-5 mt-md-3 important" readonly>  
                        </div>
                        <div class="row">
                            <label class="col-md-1 mt-3">Treasurer: </label>
                            <input type="text" name="tres-name" id="review-tres-name" class=" col-md-6 form-control ml-md-5 mt-md-3 important" readonly>
                        </div>
                        <div>
                        <label>Proposed Activity</label>
                            <input type="text" name="org-activity-name" id="review-actvity" class="form-control important" readonly>
                        </div>
                        <div class="mt-3">
                            <label>Objective of Activity</label>
                            <textarea class="form-control important" id="review-objective" name="org-objective" readonly></textarea>
                        </div>
                        <div class="mt-3">
                            <label>Details of Activity</label>
                            <textarea class="form-control important" id="review-details" name="org-details" readonly></textarea>
                        </div>
                    </div>
                </div>
            <!-- <div class="p-1 bg-dark"></div>
            <br /> -->
        </div>
        </section>
        <section class="box p-3" id="finalSignatureBox">
            <div class="border-container">
                <div class="line-design rounded-top text-white">
                    <h3 style="text-align: center;">Requester's Signature</h3>
                </div>
                <div class="signature d-flex align-items-center">
                    <div class="signatureBox rounded">
                        <image id="signatureId" src="" alt="">
                        <input type="text" name="signature" id="signatureImg" style="display:none;" readonly>
                    </div>
                </div>
                <div>
                    <div class="name-container d-flex justify-content-center">
                        <input type="text" id="signatureName" class="form-control-signature"  readonly>
                    </div>
                    <label class="label-signature">Signature Over Printed Name</label>
                </div>
            </div>
            <div class="mt-3 w-100">
                <div class="row d-flex justify-content-center signature-Input">
                    <div class="col-md-4 d-md-flex justify-content-end">
                        <button class="btn btn-info" id="btn-sign">WRITE SIGNATURE</button>
                    </div>
                    <div class="col-md-2  d-md-flex justify-content-center align-items-center">
                        <h4 >Or</h4>
                    </div>
                    <div class="col-md-4">
                        <input class="btn" id="uploadSignature" type="file" name="signature-Img"
                        accept="image/png" value="UPLOAD AS PNG">
                    </div>
                </div>
            </div>
        </section>
        <section id="validationBox">
            <div class="p-3 row">
                <div class="col-md-1">
                <img src="Images\Form\warning.png">
                </div>
                <div class="col-md-7">
                    <ul id="errorsMsg"> 
                    </ul>
                </div>
            </div>
        </section>
        <div class="buttons my-3">
            <button class="btn btn-secondary" id="prev-btn">Prev</button>
            <button class="btn btn-success" id="submit-btn">Submit</button>
            <button class="btn btn-primary" id="next-btn">Next</button>
        </div>
    </form>
    <div class="modal-sign">
        <div class="signatureCanva">
            <div class="d-flex justify-content-end px-3">
                <span class="closeModal">&times;</span>
            </div>
            <div class="pl-3 my-2">
                <p><span style="font-weight: bold;">Note: To draw hold down or mouse down while moving.</span></p>
            </div>
            <div class="box-canvas text-align-center">
                  <div class="noticeCanvas">
                         <h1>Draw Here</h1>
                    </div>
                <canvas id="canvas">
                </canvas>
            </div>
            <div class="w-100 mt-3">
                <div class="w-25 m-auto">
                  <button class="btn btn-info" id="btn-sign-save">Save Signature</button>
                  <button class="btn btn-danger" id="btn-clear">Clear</button>
                </div>
            </div>
        </div>
    </div>
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
    <script src="Script/Form.js"></script>
    <script src="Script/ValidateForm.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script> -->
</body>
</html>