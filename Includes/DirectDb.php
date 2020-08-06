<?php
   require_once('ConnectDB.php');

    session_start();

    function generateRefNo(){
        $size=8;
        //Ascii
        //65-90 
        //97-122
        //0-9
        $max=array(65=>90,97=>122,48=>57);
        $min=array(48,48,65,65,97,48,48,48);

        $refNo="#";
        for($i=0; $i<=$size; $i++){
            $idMin=$min[rand(0,count($min)-1)];
            $refNo.=chr(rand($idMin,$max[$idMin]));
        }
        return $refNo;
    }
    function checkRefNo($conn,$refNo){
        $sql_ref="SELECT ref_no FROM requester_information WHERE ref_no='$refNo'";
        $result_ref=mysqli_query($conn,$sql_ref);
        if(mysqli_num_rows($result_ref)>0)
            return false;
        return true;
    }

    $_SESSION['natureActivity']=$_POST['nature-activity'];
    
    $ref_No=generateRefNo();
    while(!checkRefNo($conn,$ref_No)){
        $ref_No=generateRefNo();
    }

    $_SESSION['refNo']=$ref_No;

?>
<?php

                // important reservation variables
        $start=true;

        //profile
        $requesterName=$_POST['Requester-name'];
        $dateFill=$_POST['date-filling'];
        $department = $_POST['department'];
        $natureActvity=$_POST['nature-activity'];

        //reservation
        $dateStart= $_POST['date-Of-UseStart'];
        $dateEnd= $_POST['date-Of-UseEnd'];
        $timeStart= $_POST['time-of-UseStart'];
        $timeEnd= $_POST['time-of-UseEnd'];

        //facility
        $facilityType="";
        $facility= setFacility($_POST['Proposed-Facility']);
        $room= $_POST['Proposed-room']=="none"?'NULL':"'".$_POST['Proposed-room']."'";
        $venueRemarks= ($_POST['venueRemarks']=="None")?
        "NULL":"'".$_POST['venueRemarks']."'";

        //equipments
        $equipmentsAO=$_POST['list-Of-Equipments-AO'];
        $equipmentsLMO=$_POST['list-Of-Equipments-LMO'];
        $equipmentsRemarks= ($_POST['equipmentsRemarks']=="None")?"NULL":"'".$_POST['equipmentsRemarks']."'";
        $equipmentsListNameAO=array(); // equipments name
        $equipmentsListQtyAO=array(); // equipments qty
        $equipmentsListNameLMO=array(); // equipments
        $equipmentsListQtyLMO=array(); // equipments
        $equipmentsStatus=array();
        decodeEquipments($equipmentsAO,"AO");
        decodeEquipments($equipmentsLMO,"LMO");

        //reservation type
        $reserveType =setReserveType();
        $facility_Type=$facilityType;
        $equipmentType="";
        if(count($equipmentsListNameAO)>0&&count($equipmentsListNameLMO)>0){
            $equipmentType="'Both'";
        }
        else if(count($equipmentsListNameAO)>0){
            $equipmentType="'AO'";
        }
        else if(count($equipmentsListNameLMO)>0){
            $equipmentType="'LMO'";
        }
        else
            $equipmentType="NULL";


        //signature
        $signature=$_POST['signature'];


        //organization
        $isOrg=$_POST['radio-review'];
        $orgType=false;
        if($isOrg=="Yes"){
            $orgType=true;
            $isOrg="TRUE";
        }
        else{
            $isOrg="FALSE";
        }
       

        
        //insert profile sql
        $sql_Profile = "INSERT INTO requester_information(ref_no,user_no,date_fill,requester_dept,nature_act,is_org)
                VALUES('{$_SESSION["refNo"]}',{$_SESSION["user_no"]},'{$dateFill}','{$department}'
                ,'{$natureActvity}',{$isOrg})";

       runQuery($conn,$sql_Profile);

     if($orgType){
        if(checkValidOrganization()){
            //full name
            $orgName=$_POST['org-Full-Name'];
            //members
            $presName=$_POST['pres-name'];
            $secName=$_POST['sec-name'];
            $tresName=$_POST['tres-name'];

            //details of activity
            $orgActvityName=preg_replace("/'/",'',$_POST['org-activity-name']);
            $orgDetails=preg_replace("/'/",'',$_POST['org-details']);
            $orgObjective=preg_replace("/'/",'',$_POST['org-objective']);

            $sql_orgMembers="INSERT INTO organization_members (ref_no,president,secretary,treasurer)
                VALUES('{$_SESSION["refNo"]}','{$presName}','{$secName}','{$tresName}')";

            runQuery($conn,$sql_orgMembers);

            $sql_orgInfo="INSERT INTO organization_information (ref_no,org_name,org_activity,org_obj,org_details)
                            VALUES('{$_SESSION["refNo"]}','{$orgName}','{$orgActvityName}',
                            '{$orgObjective}','{$orgDetails}')";
            runQuery($conn,$sql_orgInfo);

            $sql_orgSign="INSERT INTO organization_signature(ref_no,president,secretary,treasurer)
                        VALUES('{$_SESSION['refNo']}',NULL,NULL,NULL)";
            runQuery($conn,$sql_orgSign);
        }
        
        //echo "For Organization</br>";
    }
    else{
      //  echo "Not Organization</br>";
    }


?>
<?php
     $isValid=true;
   function runQuery($conn,$sql){
        if(!mysqli_query($conn,$sql)){
            echo "Error Inserting Database".mysqli_error($conn)."</br>";
            echo $sql."</br>";
            $GLOBALS['isValid']=false;
       }
        else{
        //   echo "Successful Insert Reservation</br>";
       }
        
    }

    function equipmentsQuery($conn,$equipmentsName,$equipmentsQty,$type){
        $length= count($equipmentsName);

        for($i=0; $i<$length; $i++){

            $name=trim(preg_replace('/\s\s+/', ' ', $equipmentsName[$i]));
            $sql_Equipment="INSERT INTO  reserve_equipments(ref_no,equipment_type,equipment_name,equipment_qty,equipment_status)
                VALUES('{$_SESSION["refNo"]}',{$type},'$name'
                ,{$equipmentsQty[$i]},NULL)";


            runQuery($conn,$sql_Equipment);
        }

    }
    function checkValidReservation(){
      
        return isset($_POST['Requester-name'],$_POST['date-filling'],$_POST['department'],$_POST['nature-activity'],$_POST['date-Of-UseStart']
            ,$_POST['date-Of-UseEnd'],$_POST['time-of-UseStart'],$_POST['time-of-UseEnd'],$_POST['Proposed-Facility'],$_POST['list-Of-Equipments-AO'],$_POST['list-Of-Equipments-LMO'],
            $_POST['venueRemarks'],$_POST['equipmentsRemarks'],$_POST['radio-review'],$_POST['signature']);
    }

    function checkValidOrganization(){
        return isset($_POST['org-Full-Name'],$_POST['pres-name'],$_POST['sec-name'],$_POST['tres-name'],$_POST['org-activity-name'],
        $_POST['org-objective'],$_POST['org-details']);
    }
    function printData($data){
        foreach($data as $value)
              echo $value."</br>";
    }

   function seperateData($data){
     $data=str_replace(" - Qty: ","^",$data);
     $array=preg_split('/[,]/',$data,-1,PREG_SPLIT_NO_EMPTY);
     array_pop($array);
    //  print_r($array);
    return $array;
   }

   function setReserveType(){
        //reserve type
        $facilityType=$GLOBALS['facilityType'];
        $equipmentsAO=$GLOBALS['equipmentsAO'];
        $equipmentsLMO=$GLOBALS['equipmentsLMO'];

        if($facilityType!="NULL"&&($equipmentsAO!="None"||$equipmentsLMO!="None")){

           return 'Both';
        }
        else if($facilityType!="NULL"&&($equipmentsAO=="None"&&$equipmentsLMO=="None")){
            return "Facility";
        }
        else if($facilityType=="NULL"&&($equipmentsAO!="None"||$equipmentsLMO!="None")){
           return "Equipments";
        }

   }

   function decodeEquipments($equipments,$type){
        if($equipments!="None"){
            $equipmentsList=seperateData($equipments);
        }
        else return;

            foreach($equipmentsList as $equip){
            //   echo $equip;
            $equip2=substr($equip,1);
            //  echo $equip2;
            $splitarray=explode("^",$equip);
                $GLOBALS['equipmentsListName'.$type][]=$splitarray[0];
                $GLOBALS['equipmentsListQty'.$type][]=$splitarray[1];
            }
   
            // echo "</br>".print_r($GLOBALS['equipmentsListName'.$type]);
    }
    function setFacility($facility){
        if(strpos($facility,"AO-")!==false){
             $GLOBALS['facilityType']="'AO'";
            return substr($facility,3);
        }
        else if(strpos($facility,"LMO-")!==false){
            $GLOBALS['facilityType']="'LMO'";
            return substr($facility,4);
        }
        else{
            $GLOBALS['facilityType']="NULL";
        }
    
    }
?>
<?php
    $sqlReservation = "INSERT INTO reservation_information(ref_no,date_start,date_end,
    time_start,time_end,reserve_type,facility_remarks, equipment_remarks) VALUES('{$_SESSION["refNo"]}','{$dateStart}','{$dateEnd}',
    '{$timeStart}','{$timeEnd}','{$reserveType}',{$venueRemarks},{$equipmentsRemarks})";
    
    runQuery($conn,$sqlReservation);



    //facility
    if($reserveType=="Facility"||$reserveType=="Both"){
  
         $sqlFacility="INSERT INTO reserve_facility
         (ref_no,facility_type,facility,room_no,facility_status) VALUES('{$_SESSION["refNo"]}',{$facility_Type},
         '{$facility}',{$room},NULL)";

         runQuery($conn,$sqlFacility);
    }
    
    //equipments
    if($reserveType=="Equipments"||$reserveType=="Both"){
            $equipments_TypeIndi=substr($equipmentType,1,strlen($equipmentType)-2);
            $equipments_TypeLower=strtolower($equipments_TypeIndi);

            if($equipmentType=="'AO'"||$equipmentType=="'Both'"){
                equipmentsQuery($conn,$equipmentsListNameAO,$equipmentsListQtyAO,"'AO'");
            }

            if($equipmentType=="'LMO'"||$equipmentType=="'Both'"){
                equipmentsQuery($conn,$equipmentsListNameLMO,$equipmentsListQtyLMO,"'LMO'");
            }
    }

    $ao_status="NULL";
    $lmo_status="NULL";
    if($facility_Type=="'AO'"||$equipmentType=="'Both'"||$equipmentType=="'AO'"){
        $ao_status="'PENDING'";
    }
 
    if($facility_Type=="'LMO'"||$equipmentType=="'Both'"||$equipmentType=="'LMO'"){
        $lmo_status="'PENDING'";
    }

    $org_status="NULL";
    if($orgType){
        $org_status="'PENDING'";
    }

    //signature status
    $sql_Signature_status="INSERT INTO `signature_status`(`ref_no`, `dept_status`, `ao_status`, `lmo_status`, `sao_status`, `cdmo_status`) 
    VALUES ('{$_SESSION['refNo']}','PENDING',$ao_status,$lmo_status,$org_status,'PENDING')";

    runQuery($conn,$sql_Signature_status);

    //signature
    $signatureImg="";
    //echo $signature."</br>";
    if(strpos($signature,"data:image/png;base64,")!==false){
        $signatureEncode=explode(',',$signature);
         $signatureImg=addslashes(base64_decode($signatureEncode[1]));
    }
    else{
        // //$signatureImg=addslashes($signature);
        $signatureImg= addslashes(file_get_contents($_FILES['signature-Img']['tmp_name']));
    }
   
    $sql_Signature="INSERT INTO signature_information (ref_no,requester_sign,dept_sign,
            ao_sign,lmo_sign,sao_sign,cdmo_sign) 
            VALUES('{$_SESSION["refNo"]}','{$signatureImg}',NULL,NULL,NULL,NULL,NULL)";
    
    runQuery($conn,$sql_Signature);
  
   // end
    mysqli_close($conn);
    if($GLOBALS['isValid'])
        header('Location: ../FormMsg.php');

?>


