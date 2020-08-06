<?php
    session_start();
  include_once("ConnectDB.php");
  $arrayOfForms=array();


  if(!isset($navDb)){
        if($_SESSION['user_status']=="Admin-AO"){
            $navDb="ao_sign";
            $adminType="AO";
        }
        else if($_SESSION['user_status']=="Admin-LMO"){
            $navDb="lmo_sign";
            $adminType="LMO";
        }
        else if($_SESSION['user_status']=="Admin-SAO"){
            $navDb="sao_sign";
            $adminType="SAO";
        }
        else if($_SESSION['user_status']=="Admin-CDMO"){
            $navDb="cdmo_sign";
            $adminType="CDMO";
        }
        else{
            $isDept=true;
            $navDb="dept_sign";
            $adminType=explode("-",$_SESSION['user_status'])[1];
        }
    }
  $template=' 
  <div class="sub-box card shadow">
        <div class="row container my-2">
            <div class="col-5">
                <span class="text-primary"><h5>%s</h5></span>
                <p>%s</p>
            </div>
            <div class="col-5 ml-auto">
                <p></p>
                <p>Date Submitted: %s</p>
            </div>
            <hr>
            <div class="col-5 m-auto">
                <h6>Reference No: %s</h6>
            </div>
            <div class="col-4 m-auto">
                <p>Purpose: %s</p>
            </div>
            <div class="float-right d-flex align-items-center">
                 <button class="btn btn-info button-view" value="%s">View More</button>
            </div>
        </div>
   </div>';

    $sql="SELECT `ref_no`,`{$navDb}` FROM signature_information where {$navDb} is NULL";
    $result=mysqli_query($conn,$sql);

    $inbox_num=0;
    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_assoc($result)){
                if($isDept){
                     $sql="SELECT * FROM requester_information Where ref_no='{$row['ref_no']}' 
                     AND requester_dept='{$adminType}'";
                     $result_Request=mysqli_query($conn,$sql);
                     
                     if(mysqli_num_rows($result_Request)>0){
                         $row_Form=mysqli_fetch_assoc($result_Request);
                         $sql="SELECT * FROM user_information Where user_no={$row_Form['user_no']}";
                         
                         $result_User=mysqli_query($conn,$sql);
                         $row_user=mysqli_fetch_assoc($result_User);

                         echo sprintf($template,$row_user['user_name'],$row_user['user_status']
                        ,$row_Form['date_fill'],$row_Form['ref_no'],$row_Form['nature_act'],$row_Form['ref_no']);

                        $inbox_num++;
                     }
                }
        }
    }

    mysqli_close($conn);
?>