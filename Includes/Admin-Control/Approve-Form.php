<?php
    include_once("ConnectDB.php");
    session_start();

    $refNo =$_POST['ref_no']; 
    $signature=$_POST['img_src'];


    //ref_no admin_no date_sign time_sign feedback
    $admin_no=$_SESSION['user_no'];
    $date_today=date('Y-m-d');
    //set time zone
    date_default_timezone_set("Asia/Manila");
    $time_today=date('H:i:s');
    if(!isset($_POST['admin_comment'])||$_POST['admin_comment']==''){
        $comment='NULL';
    }
    else
         $comment="'".$_POST['admin_comment']."'";
    
    //equipments
    if( $_SESSION['adminType']=="AO"||$_SESSION['adminType']=="LMO"){
        if($_POST['facility']){

            $sql="UPDATE reserve_facility SET facility_status=TRUE WHERE ref_no='$refNo'";
            if(!mysqli_query($conn,$sql))
            echo mysqli_error($conn);
            
        }
        else if(!$_POST['facility']){
            $sql="UPDATE reserve_facility SET facility_status=FALSE WHERE ref_no='$refNo'";
            if(!mysqli_query($conn,$sql))
            echo mysqli_error($conn);
        }
        

        if($_POST['equipment']!=''||$_POST['equipment']!=NULL){
            $arrayEquipment=substr($_POST['equipment'],0,-1);
            $arrayEquipment=explode(",",$arrayEquipment);

            
            foreach($arrayEquipment as $equipment)
            {
                $sql="UPDATE reserve_equipments SET equipment_status=TRUE WHERE ref_no='$refNo' AND equipment_type='{$_SESSION['adminType']}' AND equipment_name='$equipment' ";
               if(!mysqli_query($conn,$sql))
                    echo mysqli_error($conn);
             }

        }
        $sql="UPDATE reserve_equipments SET equipment_status=FALSE WHERE ref_no='$refNo' AND equipment_type='{$_SESSION['adminType']}' AND equipment_status is NULL";
        if(!mysqli_query($conn,$sql))
               echo mysqli_error($conn);
    }



    //signature
    $signatureImg="";
    //echo $signature."</br>";
    if(strpos($signature,"data:image/png;base64,")!==false){
         $signatureEncode=explode(',',$signature);
         $signatureImg=addslashes(base64_decode($signatureEncode[1]));
    }
    
    //records of signature

    $sql="INSERT INTO signature_query(ref_no,admin_no,status,date_sign,time_sign,feedback) VALUES('$refNo',$admin_no,'APPROVED','$date_today
    ','$time_today',$comment)";

    if(!mysqli_query($conn,$sql)){
      echo  mysqli_error($conn);
    }



    //send signature data type png
    $sql="UPDATE signature_information SET {$_SESSION['navDb']} = '{$signatureImg}' WHERE ref_no='$refNo'";
    if(!mysqli_query($conn,$sql)){
     echo  mysqli_error($conn);
    }
 


    //send status
    $sql="UPDATE signature_status SET {$_SESSION['adminStatus']} ='APPROVED' WHERE ref_no='$refNo'";
    if(!mysqli_query($conn,$sql)){
        echo  mysqli_error($conn);
       }
    else{
           echo "Please Wait";
    }

    // $_SESSION['nav-form']='APPROVED';
    mysqli_close($conn);
?>