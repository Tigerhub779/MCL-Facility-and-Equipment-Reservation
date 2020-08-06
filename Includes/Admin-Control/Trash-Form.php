<?php
    include_once("ConnectDB.php");
    session_start();

    $refNo=$_POST['ref_no'];
    $admin_no=$_SESSION['user_no'];
    $date_today= date("Y-m-d");
    date_default_timezone_set("Asia/Manila");
    $time_today=date('H:i:s');
    



    $sql="SELECT * FROM signature_query WHERE ref_no='$refNo' AND admin_no='$admin_no'";

    $result=mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)>0){
        $sqlQuery="UPDATE signature_query SET status='TRASHED' WHERE ref_no='$refNo' AND
        admin_no=$admin_no";
   
       if(!mysqli_query($conn,$sqlQuery)){
           echo mysqli_query($conn,$sql);
       }       
    }
    else{
        $sql="INSERT INTO signature_query(ref_no,admin_no,status,date_sign,time_sign,feedback) VALUES('$refNo','$admin_no','TRASHED','$date_today
        ','$time_today',NULL)";
        if(!mysqli_query($conn,$sql)){
          echo mysqli_error($conn,$sql);
        }
    }
  
    $sql="UPDATE signature_status SET {$_SESSION['adminStatus']} ='TRASHED' WHERE ref_no='$refNo'";
    if(!mysqli_query($conn,$sql)){
        echo  mysqli_error($conn);
       }
    else{
           echo "Please Wait";
    }

    mysqli_close($conn);
?>