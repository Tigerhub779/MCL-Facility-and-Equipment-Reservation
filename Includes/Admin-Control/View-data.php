<?php
    include_once("ConnectDB.php");
    session_start();
    $ref_no=$_POST['ref_no'];


    function getDateFormat($date){
        $dateSplit=preg_split('/-/',$date);
        $arrayOfMonths=array("January","Febuary","March","April","May","June","July","August","September","October","November","December");
        $month=$arrayOfMonths[$dateSplit[1]-1];

        return $month." ".$dateSplit[2].", ".$dateSplit[0];
    }

    function getTimeFormat($time){
        $timeSplit = preg_split('/:/',$time);

        $time_indicator="";

        if((int)$timeSplit[0]>11){
            $time_indicator="PM";
        }
        else{
            $time_indicator="AM";
        }

        $hour=(int)$time%12;

        if($hour==0){
            $hour=12;
        }
        return $hour.":".$timeSplit[1]." $time_indicator";
    }

    

    function getEquipments($conn,$equipmentType,$ref_no){
        $sql_Equipments="SELECT * FROM reserve_equipments WHERE equipment_type='$equipmentType' AND ref_no='$ref_no'";
        $result_equipments=mysqli_query($conn,$sql_Equipments);

        if(mysqli_num_rows($result_equipments)>0){
        
            $equipments="";
            $template="
            <div class='d-flex align-items-center'>
                <input type='checkbox' class='mr-2' %s %s>
                <label>%s - %d</label>
            </div>
            ";

            $isDisabled ="disabled";
             
            $isChecked="checked";
           while($row_equipments=mysqli_fetch_assoc($result_equipments)){
               if(!$row_equipments['equipment_status']){
                 $isChecked="";
               }
               else{
                   $isChecked="checked";
               }


                $equipments.=sprintf($template,$isChecked,$isDisabled,
                $row_equipments['equipment_name'],$row_equipments['equipment_qty']);
           }

        }
        else{
            return "None";
        }

     return $equipments;
}
?>

<?php
    $facility_name="Facility";

    if( $_SESSION['adminType']=="SAO"){
        $facility_name="Proposed Venue";
        $sql="SELECT * FROM organization_information AS org
                INNER JOIN reservation_information AS reserve
                    ON org.ref_no=reserve.ref_no
                WHERE org.ref_no='$ref_no'";

        $result=mysqli_query($conn,$sql);
        if(!mysqli_num_rows($result))
            echo mysqli_error($conn);

        $row=mysqli_fetch_assoc($result);

        $template_organization="<div class='py-2'>
        <h4>Organization Form </h4>   
        </div>
        <div class='ml-4 mt-2 row'>
        <div class='col-md-6'>
            <h6>Reference No:</h6>
        </div>
        <div class='col-md-6'>
            <p>$ref_no</p>
        </div>
       </div>
        <hr>
        <div class='ml-4 mt-2 row'>
            <div class='col-md-6'>
                <h6>Organization Offical Name:</h6>
            </div>
            <div class='col-md-6'>
                <p>{$row['org_name']}</p>
            </div>
        </div>
        <hr>
        <div class='ml-4 mt-2 row'>
        <div class='col-md-6'>
            <h6>Proposed Activity:</h6>
        </div>
        <div class='col-md-6'>
            <p>{$row['org_activity']}</p>
        </div>
        </div>
        ";

        $template_obj="<div class='bg-theme text-white rounded'>
        <div class='d-flex align-items-center justify-content-between'>
            <div>
                <h6 class='pl-3'>Objectives</h6>
            </div>
            <div class='text-right pr-2'>
            <span class='plus-sign' id='obj-slider'>&#43;</span>
            </div>
        </div>
        <div  id='obj-slider-box' class='pl-2'>
            <div class='row ml-2 mx-2 my-2 text-justify text-indent'>
               {$row['org_obj']}
            </div>
        </div>
    </div>";

        $template_details="<div class='mt-1 mb-2 bg-theme text-white rounded'>
            <div class='d-flex align-items-center justify-content-between'>
                <div>
                    <h6 class='pl-3'>Details</h6>
                </div>
                <div class='text-right pr-2'>
                <span class='plus-sign' id='details-slider'>&#43;</span>
                </div>
            </div>
            <div  id='details-slider-box' class='pl-2'>
                <div class='row mx-2 py-2 text-justify text-indent'>
                {$row['org_details']}
                </div>
            </div>
        </div>";

        
    }
    else{
   
        $sql_primary="SELECT * FROM requester_information AS requester
                INNER JOIN user_information AS user
                    ON requester.user_no =user.user_no
                INNER JOIN reservation_information AS reservation
                    ON requester.ref_no=reservation.ref_no
                WHERE requester.ref_no='$ref_no'";

        $result=mysqli_query($conn,$sql_primary);

        if(mysqli_num_rows($result)>0){
            $row=mysqli_fetch_assoc($result);
        }
        
        $dateFill=date_format(date_create($row['date_fill']),"M d, Y");

        $template_profile = "<div class='py-2'>
                <h4>Form Information</h4>
                </div>
                <hr>
                <div class='ml-4 mt-2 row'>
                    <div class='col-md-6'>
                        <h6>Requester</h6>
                    </div>
                    <div class='col-md-6'>
                        <p>{$row['user_name']}</p>
                    </div>
                </div>
                <hr>
                <div class='ml-4 mt-2 row'>
                    <div class='col-md-6'>
                        <h6>Reference No:</h6>
                    </div>
                    <div class='col-md-6'>
                        <p>$ref_no</p>
                    </div>
                </div>
                <hr>
                <div class='ml-4 mt-2 row'>
                    <div class='col-md-6'>
                        <h6>Department</h6>
                    </div>
                    <div class='col-md-6'>
                        <p>{$row['requester_dept']}</p>
                    </div>
                </div>
                <hr>
                <div class='ml-4 mt-2 row'>
                    <div class='col-md-6'>
                        <h6>Date of Filling</h6>
                    </div>
                    <div class='col-md-6'>
                        <p>$dateFill</p>
                    </div>
                </div>
                <hr>
                <div class='ml-4 mt-2 row'>
                    <div class='col-md-6'>
                        <h6>Nature of Activity/Purposes</h6>
                    </div>
                    <div class='col-md-6'>
                        <p>{$row['nature_act']}</p>
                    </div>
                </div>";
    }
  

        //reservation

        //time and date reservation
        $dateStart = date_format(date_create($row['date_start']),"M d, Y");
        $dateEnd = date_format(date_create($row['date_end']),"M d, Y");
        $timeStart = getTimeFormat($row['time_start']);
        $timeEnd = getTimeFormat($row['time_end']);

        //remarks
        if($_SESSION['adminType']!='SAO'){
            $facilityRemarks=$row['facility_remarks']==null?"None":$row['facility_remarks'];
            $equipmentRemarks=$row['equipment_remarks']==null?"None":$row['equipment_remarks'];
        }

        // facility reservation
        $facilityType=$row['reserve_type'];
        if($facilityType=="Both"||$facilityType=="Facility"){
            $sql_facility="SELECT * FROM reserve_facility WHERE ref_no='$ref_no'";
            $result_facility=mysqli_query($conn,$sql_facility);

            if(mysqli_num_rows($result_facility)>0){
                    $row_facility=mysqli_fetch_assoc($result_facility);
                    $facility=$row_facility['facility'];
                    $room=($row_facility['room_no']==null)?"None":$row_facility['room_no'];
                    $status="";
                    if($row_facility['facility_status']){
                        $status="checked";
                    }
                    $template_facility="<input type='checkbox' $status disabled>";
            }
        }
        else{
            $template_facility="";
            $facility="None";
            $room="None";
        }

        

        //Equipments Reservation
        $template_reservation=
            "<hr>
            <div class='ml-4 mt-2 row'>
                <div class='col-md-6'>
                    <h6>Date of Use</h6>
                </div>
                <div class='col-md-6'>
                    <p>$dateStart - $dateEnd</p>
                </div>
            </div>
            <hr>
            <div class='ml-4 mt-2 row'>
                <div class='col-md-6'>
                    <h6>Time of Use</h6>
                </div>
                <div class='col-md-6'>
                    <p>$timeStart - $timeEnd</p>
                </div>
            </div>
            <hr>
            <div class='ml-4 mt-2 row'>
                <div class='col-md-6'>
                    <h6>$facility_name</h6>
                </div>
                <div class='col-md-6 d-flex align-items-center'>
                    $template_facility
                    <p class='mb-0 ml-2'>$facility</p>
                </div>
            </div>
            <hr>
            <div class='ml-4 mt-2 row'>
                <div class='col-md-6'>
                    <h6>Specified Room</h6>
                </div>
                <div class='col-md-6'>
                    <p>$room</p>
                </div>
            </div>
            <hr>
            <div class='bg-theme text-white rounded'>
                <div class='d-flex align-items-center justify-content-between'>
                    <div>
                        <h6 class='pl-3'>Equipments</h6>
                    </div>
                    <div class='text-right pr-2'>
                    <span class='plus-sign' id='equipment-slider'>&#43;</span>
                    </div>
                </div>
                <div  id='equipment-slider-box' class='pl-2'>
                    <div class='row ml-2'>
                        <div class='col-6 text-center'>
                            <p>AO</p>
                            %s
                        </div>
                        <div class='col-6 text-center'>
                            <p>LMO</p>
                            %s
                        </div>
                    </div>
                </div>
            </div>
            <hr>";

        

        if($row['reserve_type']=="Both"||$row['reserve_type']=="Equipments"){
             $template_reservation=sprintf($template_reservation,getEquipments($conn,"AO",$ref_no),getEquipments($conn,"LMO",$ref_no));
        }
        else{
            $template_reservation=sprintf($template_reservation,"None","None");
        }


    
    $template_remarks =' <div class=" bg-theme text-white rounded">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="pl-3">Remarks</h6>
                    </div>
                    <div class="text-right pr-2">
                    <span class="plus-sign" id="remarks-slider">+</span>
                    </div>
                </div>
                <div id="remarks-slider-box">
                    <div class="my-2 ml-auto width-95">
                        <div class="d-flex align-items-center justify-content-between shadow rounded">
                            <div>
                                <h6 class="pl-3">Facility</h6>
                            </div>
                            <div class="text-right pr-2">
                            <span class="plus-sign" id="facility-slider">&#43;</span>
                            </div>
                        </div>
                        <div id="facility-slider-box" class="pt-1 pl-2 text-justify">
                            <p> %s </p>
                        </div>
                    </div> 
                    <div class="my-2 ml-auto width-95">
                        <div class="d-flex align-items-center justify-content-between shadow rounded">
                            <div>
                                <h6 class="pl-3">Equipments</h6>
                            </div>
                            <div class="text-right pr-2">
                            <span class="plus-sign" id="equipmentR-slider">+</span>
                            </div>
                        </div>
                        <div id="equipmentR-slider-box" class="pt-1 pl-2 text-justify">
                            <p> %s </p>
                        </div>
                    </div>
                </div>
        </div>';


        
    
    
    //final layout
    if($_SESSION['adminType']!='SAO'){
        echo $template_profile;
        echo $template_reservation;
        echo sprintf($template_remarks,$facilityRemarks,$equipmentRemarks);
    }
    else{
        echo $template_organization;
        echo $template_reservation;
        echo $template_obj;
        echo $template_details;
    }
?>