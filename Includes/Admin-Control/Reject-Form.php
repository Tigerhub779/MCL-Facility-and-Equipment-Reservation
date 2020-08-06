<?php
    include_once("ConnectDB.php");
    session_start();

    $refNo =$_POST['ref_no']; 
    $admin_no=$_SESSION['user_no'];
    $comment=$_POST['admin_comment'];
    $date_today= date("Y-m-d");
    date_default_timezone_set("Asia/Manila");
    $time_today=date('H:i:s');
    $navDb=$_SESSION['navDb'];
    $adminType=$_SESSION['adminType'];
    $adminStatus=$_SESSION['adminStatus'];
    


    $sql="INSERT INTO signature_query(ref_no,admin_no,status,date_sign,time_sign,feedback) VALUES('$refNo','$admin_no','REJECTED','$date_today
    ','$time_today','$comment')";

    mysqli_query($conn,$sql);

    if( $_SESSION['adminType']=="AO"||$_SESSION['adminType']=="LMO"){
        if($_POST['facility'] ){
            $sql="UPDATE reserve_facility SET facility_status=FALSE WHERE ref_no='$refNo'
                AND facility_type='{$_SESSION['adminType']}'";
            if(!mysqli_query($conn,$sql))
            echo mysqli_error($conn);
        }

        if($_POST['items']){
            $sql="UPDATE reserve_equipments SET equipment_status=FALSE WHERE ref_no='$refNo' 
                AND equipment_type='{$_SESSION['adminType']}'";
            if(!mysqli_query($conn,$sql))
            echo mysqli_error($conn);
        }
    }

    $sql="UPDATE signature_status SET {$_SESSION['adminStatus']} ='REJECTED' WHERE ref_no='$refNo'";
    if(!mysqli_query($conn,$sql)){
        echo  mysqli_error($conn);
       }
    else{
           echo "Please Wait";
    }

    echo "Please Wait";

?>