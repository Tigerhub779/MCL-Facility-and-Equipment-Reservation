<?php
    session_start();

    if(!isset($_SESSION['login'])){
        header("Location: login.php");
    }
    if(strpos($_SESSION['user_status'],"Admin")===false){
        header("Location: Index.php");
    }

    if(!isset( $_SESSION['navDb'], $_SESSION['adminType'], $_SESSION['adminStatus'])){
        if($_SESSION['user_status']=="Admin-AO"){
            $_SESSION['navDb']="ao_sign";
            $_SESSION['adminType']="AO";
            $_SESSION['adminStatus']="ao_status";
        }
        else if($_SESSION['user_status']=="Admin-LMO"){
            $_SESSION['navDb']="lmo_sign";
            $_SESSION['adminType']="LMO";
            $_SESSION['adminStatus']="lmo_status";
        }
        else if($_SESSION['user_status']=="Admin-SAO"){
            $_SESSION['navDb']="sao_sign";
            $_SESSION['adminType']="SAO";
            $_SESSION['adminStatus']="sao_status";
        }
        else if($_SESSION['user_status']=="Admin-CDMO"){
            $_SESSION['navDb']="cdmo_sign";
            $_SESSION['adminType']="CDMO";
            $_SESSION['adminStatus']="cdmo_status";
        }
        else{
            $isDept=true;
            $_SESSION['navDb']="dept_sign";
            $_SESSION['adminType']=explode("-",$_SESSION['user_status'])[1];
            $_SESSION['adminStatus']="dept_status";
        }
    }

?>
<?php
include_once("Includes/Admin-Control/Arrange-Forms.php");

if(!isset($_SESSION['nav-form']))
     $_SESSION['nav-form']="PENDING";

 if($_SESSION['nav-form']=="PENDING"){
    $inbox_current=$inbox_Pending;
    $forms_current=$forms_Pending;
 }
 else if($_SESSION['nav-form']=="APPROVED"){
     $inbox_current=$inbox_Approved;
     $forms_current=$forms_Approved;
 }
 else if($_SESSION['nav-form']=="REJECTED"){
     $inbox_current=$inbox_Rejected;
     $forms_current=$forms_Rejected;
 }
 else if($_SESSION['nav-form']=="TRASHED"){
     $inbox_current=$inbox_Trashed;
     $forms_current=$forms_Trashed;
 }
   
 $inbox_title=ucfirst(strtolower($_SESSION['nav-form']));
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <link rel="icon" href="Images/Mcl_logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="Css/Admin_Style.css">
</head>
<body>
    <header class="d-flex justify-content-between" id="main-header">
        <div class="logo-head d-flex align-items-center">
            <img src="Images/Mcl_logo.png" id="logo" alt="mcl logo">
            <h3>MCL Facilities & Equipment Reservation</h3>
        </div>
        <nav class="profile-nav">
            <ul class="d-flex align-items-center profile-item">
                <li><img src="Images/icon(footer)/profile-black.png" style="cursor:auto;" height="52px" width="52px" alt="profile icon"></li>
                <li>
                    <h6><?php echo $_SESSION['user_name'];?></h6>
                    <p><?php echo $_SESSION['user_status'];?></p>
                </li>
                <li class="profile dropdown">
                    <img src="Images/icon(footer)/triangle.png" alt="inverted triangle icon" id="dropdown-img">
                      <div class="dropdown-content">
                         <p class="user-name"><?php echo $_SESSION['user_no'];?></p>
                         <p class="status">Admin</p>
                         <a href="Includes/Logout.php"><button class="btn btn-danger">Log-out</button></a>
                      </div>
                </li>
                <img src="Images/menu.png" id="menu" alt="menu">
            </ul>
        </nav>
    </header>
    <section class="bg-sub-header">
            <!-- <div class="search-form row">
                
                <input type="text" class="form-control col-md-3" placeholder="Search">
            </div> -->
            <div class="space"></div>
            <div class="text-white nav-details">
                <p><span class="font-big">Home</span> > Inbox > Read</p>
            </div>

            <section id="cards" class="row d-flex justify-content-center">
                <div class="col-md-2 bg-white card p-2">
                    <div class="row">
                        <div class="col-8 pr-0">
                            <span class="font-big2">Pending</span>
                            <p class="m-0 ml-2">Total No. of Forms</p>
                            <span class="font-big ml-2"><?php echo $inbox_Pending;?></span>
                        </div>
                        <div class="img-icon col-4 p-0 d-flex align-items-center">
                             <img src="Images/icons (admin)/inbox_resized.png">
                        </div>
                        <!-- <div class="col-6">
                            <span class="font-small"><p>2000 unread</p></span>
                        </div>
                        <div class="col-6">
                            <span  class="font-small"><p>2000 unread</p></span>
                        </div> -->
                        <div class="col-12">
                            <span class="font-small"><p>(Forms that need approval)</p></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 bg-white card p-2 ">
                    <div class="row">
                        <div class="col-8 pr-0">
                            <span class="font-big2">Approved</span>
                            <p class="m-0 ml-2">Total No. of Forms</p>
                            <span class="font-big ml-2"><?php echo $inbox_Approved; ?></span>
                        </div>
                        <div class="img-icon col-4 p-0 d-flex align-items-center">
                             <img src="Images/icons (admin)/approved_resized.png">
                        </div>
                        <div class="col-12">
                            <span class="font-small"><p>(Forms that has been signed)</p></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 bg-white card p-2 ">
                    <div class="row">
                        <div class="col-8 pr-0">
                            <span class="font-big2">Denied</span>
                            <p class="m-0 ml-2">Total No. of Forms</p>
                            <span class="font-big ml-2"><?php echo $inbox_Rejected; ?></span>
                        </div>
                        <div class="img-icon col-4 p-0 d-flex align-items-center">
                             <img src="Images/icons (admin)/disapproved_resized.png">
                        </div>
                        <div class="col-12">
                            <span class="font-small"><p>(Forms that has been rejected)</p></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 bg-white card p-2 ">
                    <div class="row">
                        <div class="col-8 pr-0">
                            <span class="font-big2">Trash</span>
                            <p class="m-0 ml-2">Total No. of Forms</p>
                            <span class="font-big ml-2"><?php echo $inbox_Trashed; ?></span>
                        </div>
                        <div class="img-icon col-4 p-0 d-flex align-items-center">
                             <img src="Images/icons (admin)/trash_resized.png">
                        </div>
                        <div class="col-12">
                            <span class="font-small"><p>(Forms that has been deleted)</p></span>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-2 bg-white card p-2 ">
                </div> -->
               
            </section>
 
        </section>
    <div class="loading-overlay" id="loading">
        <div class="loading-container">
            <img src="Images/loading.gif" alt="Loading">
            <div class='mt-2 '>
                <h5>Loading...</h5>
            </div>
        </div>
    </div>
    <div class="overlay-2">
        <section id="finalSignatureBox">
            <div class="modal-flex">
                 <div class="box-custom">
                    <div class="bg-theme py-2 rounded-top"></div>
                    <div class="d-flex justify-content-end mr-3">
                       <span class="closeModal" id="close-signature">&times;</span>
                    </div>
                    <div class="border-container">
                             <!-- <div class="bg-theme py-2 rounded-top">
                            </div> -->
                        <div class="bg-secondary rounded-top text-center">
                             <h3><span style="color: white;">Your Signature</span></h3>
                        </div>
                            <div class="signature d-flex align-items-center">
                                <div class="signatureBox rounded">
                                    <image id="signatureId" src="<?php echo isset($_SESSION['admin_signature'])?$_SESSION['admin_signature']:"";?>" alt="">
                                </div>
                            </div>
                            <div>
                                <div class="name-container d-flex justify-content-center">
                                    <input type="text" id="signatureName" value="<?php echo strtoupper($_SESSION['user_name']);?>" class="form-control-signature"  readonly>
                                </div>
                                <label class="label-signature">Signature Over Printed Name</label>
                            </div>
                           
                        </div>
                        <div class="my-3" id="modify-signature">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-end">
                                            <button class="btn btn-info" id="btn-draw-sign" >Draw Signature</button>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5>Or</h5>
                                    </div>
                                    <div class="col-md-5">
                                        <input class="btn" id="uploadSignature" type="file" name="signature-Img"
                                        accept="image/png" value="UPLOAD AS PNG">
                                    </div>
                                 </div>
                            </div>
                        <div class="px-3 py-3">
                            <div id="signature-warning">
                                <div class="my-2 py-2 px-2 card w-50 m-auto bg-warning-error"> 
                                    <div class="d-flex align-items-center text-danger">
                                        <img src="Images/Form/warning.png">
                                        <span class="pl-2">
                                            <p>* Signature is required to approve a form.</p>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div id="approved-sign-container">
                                <div class="d-flex justify-content-center mt-2 ml-3">
                                    <button class="btn btn-success btn-lg" id="approve-form-sign">Sign this Form</button>
                                </div>
                            </div>
                        </div>
                 </div>
            </div>
        </section>
        <!-- <div class="button-sign-form">
            <button class="btn btn-success btn-lg">Sign this Form</button>
        </div> -->
    </div>
    <div class="overlay z-3" id="signature-canvas-box">
        <div class="modal-sign">
             <div class="modal-flex">
                <div class="signatureCanva">
                    <div class="d-flex justify-content-end px-3">
                        <span class="closeModal" id="button-canva-close">&times;</span>
                    </div>
                    <div class="pl-3 my-2">
                        <p><span style="font-weight: bold;">Note: To draw hold down or mouse down while moving.</span></p>
                    </div>
                    <div id="sign-canvas" class="box-canvas text-align-center">
                        <div class="noticeCanvas">
                            <h1>Draw Here</h1>
                        </div>
                        <!--  Important -->
                        <canvas id="canvas">
                        </canvas>
                    </div>

                    <div class="w-100 py-3">
                        <div class="w-25 m-auto">
                            <button class="btn btn-success" id="btn-sign-save">Save Signature</button>
                            <button class="btn btn-danger" id="btn-clear">Clear</button>
                        </div> 
                    </div>
                    <div class="pb-2"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="overlay z-2" id="reject-confirm">
        <div class=" modal-flex ">
            <div class="message-box shadow">
                <div >
                    <h5>Are you sure you want to <span class="text-danger">reject</span> this form?</h5>
                    <p>Note: Comments are required to reject a form as a justification</p>
                </div>
                <div class="d-flex justify-content-end mr-2">
                    <button class="btn btn-danger mx-2" id="button-reject-yes">Yes</button>
                    <button class="btn btn-secondary" id="button-reject-no">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="overlay z-2" id="trash-confirm">
        <div class=" modal-flex ">
            <div class="message-box shadow">
                <div >
                    <h5>Are you sure you want to delete Form <span id="trash-formNo" class="text-danger">#000000000</span>? </h5>
                    <p>Note: Once a form is deleted, it cannot be retrieved the data from the database.</p>
                </div>
                <div class="d-flex justify-content-end mr-2">
                    <button class="btn btn-danger mx-2" id="button-trash-yes">Yes</button>
                    <button class="btn btn-secondary" id="button-trash-no">No</button>
                </div>
            </div>
        </div>
    </div>
    <div class="overlay z-2" id="reject-error">
        <div class=" modal-flex ">
            <div class="message-box shadow">
                <div >
                    <h5 id="reject-msg">Please provide a reason for rejection in the comment section</h5>
                    <p id="reject-note">Note: Comments are required to reject a form as a justification</p>
                </div>
                <div class="d-flex justify-content-end mr-2">
                    <button class="btn btn-secondary mx-2" id="button-okay">Okay</button>
                </div>
            </div>
        </div>
    </div>
    <div class="overlay z-2" id="member-sign-view">
        <div class=" modal-flex ">
            <div class="card" id="show-member-sign">
                <img src="" alt="Image might be corrupted">
            </div>
        </div>
    </div>
    <div class="overlay" id="details-overlay" >
        <div class="modal-flex"> 
            <div id="modal-content">
                <div class="d-flex justify-content-end">
                <span id="btn-close" class="close">&times;</span>
                </div>
                <div id="details-content" class="row">
                    <div class="col-md-6">
                        <div id="form-content" >
                            <div class="py-2">
                            <h4>Form Information</h4>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Sender</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>James Michael E. Paz</p>
                                </div>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Reference No:</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>#ZQ345KEO</p>
                                </div>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Department</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>SHS</p>
                                </div>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Date of Filling</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>May 6, 2020</p>
                                </div>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Nature of Activity/Purposes</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>MCL ACM Gen Meeting</p>
                                </div>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Date of Use</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>May 12, 2020 - May 13, 2020</p>
                                </div>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Time of Use</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>9:00 AM - 9:00 PM</p>
                                </div>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Facility</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>Lecture Room</p>
                                </div>
                            </div>
                            <hr>
                            <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Specified Room</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>R306</p>
                                </div>
                            </div>
                            <hr>
                            <div class=" bg-theme text-white rounded">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="pl-3">Equipments</h6>
                                    </div>
                                    <div class="text-right pr-2">
                                    <span class="plus-sign" id="equipment-slider">&#43;</span>
                                    </div>
                                </div>
                                <div  id="equipment-slider-box" class="pl-2">
                                    <div class="row ml-2">
                                        <div class="col-5 text-center">
                                            <p>AO</p>
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="mr-2">
                                                <label>Table - 2</label>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="mr-2" disabled>
                                                <label class="w-100 text-left">Nintendo 3ds - 2</label>
                                            </div>
                                        </div>
                                        <div class="col-5 text-center">
                                            <p>LMO</p>
                                            <div class="d-flex align-items-center">
                                                <input type="checkbox" class="mr-2">
                                                <label>Block Chairs - 12</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class=" bg-theme text-white rounded">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="pl-3">Remarks</h6>
                                    </div>
                                    <div class="text-right pr-2">
                                    <span class="plus-sign" id="remarks-slider">&#43;</span>
                                    </div>
                                </div>
                                <div id="remarks-slider-box">
                                    <div class="my-2 ml-auto" style="width: 95%;">
                                        <div class="d-flex align-items-center justify-content-between shadow rounded">
                                            <div>
                                                <h6 class="pl-3">Facility</h6>
                                            </div>
                                            <div class="text-right pr-2">
                                            <span class="plus-sign" id="facility-slider">&#43;</span>
                                            </div>
                                        </div>
                                        <div id="facility-slider-box" class="pt-1 pl-2 text-justify">
                                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.</p>
                                        </div>
                                    </div> 
                                    <div class="my-2 ml-auto" style="width: 95%;">
                                        <div class="d-flex align-items-center justify-content-between shadow rounded">
                                            <div>
                                                <h6 class="pl-3">Equipments</h6>
                                            </div>
                                            <div class="text-right pr-2">
                                            <span class="plus-sign" id="equipmentR-slider">&#43;</span>
                                            </div>
                                        </div>
                                        <div id="equipmentR-slider-box" class="pt-1 pl-2 text-justify">
                                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="ml-4 mt-2 row">
                                <div class="col-md-6">
                                    <h6>Remarks</h6>
                                </div>
                                <div class="col-md-6">
                                    <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. </p>
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div  class="col-md-6 content-slide">
                        <div id="admin-content" >
                            <div class="card shadow-lg box-2">
                                <div class="bg-theme py-2 rounded-top"></div>
                                <div class="p-2">
                                <h5>Status Information</h5>
                                    
                                </div>
                            </div>
                            <div class="card shadow-lg mb-2">
                                <div class="bg-theme py-2 rounded-top"></div>
                                <div class="p-2">
                                    <h5>Comments</h5>
                                    <textarea id="comment" class="form-control min-textarea" placeholder=" Add comments here ..."></textarea>
                                </div>
                            </div>
                            <div id="approval-box">
                                <button class="btn btn-success" id="button-approve">Approve</button>
                                <button class="btn btn-danger" id="button-reject">Reject</button>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <section class="row outer-box">
        <div class="col-md-4">
            <div id="menu-nav" class="text-center pt-4">
                <p><span class="text-primary font-big">Menu</span></p>
                <div class="m-auto">
                    <div class="mb-3 d-flex justify-content-center">
                         <button class="btn-menu d-flex align-items-center btn-pending" value="PENDING">
                             <img src="Images/icons (admin)/pending_resized.png" class="col-4">
                             <p> P e n d i n g</p>
                        </button>
                    </div>
                    <div  class="mb-3 d-flex justify-content-center">
                        <button class="btn-menu d-flex align-items-center btn-menu btn-signed" value="APPROVED">
                             <img src="Images/icons (admin)/signed_resized.png" class="col-4">
                             <p> S i g n e d</p>
                        </button>
                    </div>
                    <div class="mb-3 d-flex justify-content-center">
                        <button class="btn-menu d-flex align-items-center btn-menu btn-denied" value="REJECTED">
                             <img src="Images/icons (admin)/declined_resized.png" class="col-4">
                             <p> R e j e c t e d</p>
                        </button>
                    </div>
                    <div class="mb-3 d-flex justify-content-center">
                        <button class="btn-menu d-flex align-items-center btn-menu btn-trash" value="TRASHED">
                             <img src="Images/icons (admin)/delete_resized.png" class="col-4">
                             <p> T r a s h</p>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 box">
          <div id="form-nav" class="card shadow-lg">
             <div class="bg-secondary"></div>
               <div class="row m-2 mt-4">
                <div class="col-1" style="margin-top: -15px; margin-right: 10px;">
                    <img src="Images/icons (admin)/read_resized.png">
                </div>
                <div class="col-4"><h4>Inbox: <span id="nav-text"><?php echo $inbox_title;?></span></h4></div>
                    <div class="col-4 ml-auto">
                        <select id="sort-forms-date" class="form-control">
                            <option value="Old-First">Date: Oldest to Latest</option> 
                            <option value="Recent-First">Date: Latest to Oldest</option> 
                        </select>
                    </div>
               </div>
               <div id="loading-box">
                    <div class="loading-inner">
                            <img src="Images/loading.gif">
                            <h5>Loading...</h5>
                    </div>
               </div>
               <div id="content-box">
                    <?php
                        if($inbox_current<=0){
                            $template3='<div class="jumbotron jumbotron-fluid card rounded">
                            <div class="container">
                              <h1 class="display-4">None</h1>
                              <p class="lead">There is 0 forms corresponding in this inbox.</p>
                            </div>
                          </div>';
                          echo $template3;
                        }
                        else
                            foreach($forms_current as $form)
                                echo $form;
                    ?>
                </div>
                
          </div>
        </div>
    </section>
    <footer class="space">
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.0.js" integrity="sha256-r/AaFHrszJtwpe+tHyNi/XCfMxYpbsRg2Uqn0x3s2zc=" crossorigin="anonymous"></script>
    <script src="Script/Admin.js"></script>
</body>
</html>