<?php
session_start();
include_once("ConnectDB.php");


$colorTheme=array();
$colorTheme['PENDING']="text-primary";
$colorTheme['APPROVED']="text-success";
$colorTheme['REJECTED']="text-danger";
$colorTheme['TRASHED']="text-secondary";
$nav_menu=$_SESSION['nav-form'];
$refNo=$_POST['ref_no'];

function getEquipments($conn,$equipmentType,$ref_no){
    $sql_Equipments="SELECT * FROM reserve_equipments WHERE equipment_type='$equipmentType' AND ref_no='$ref_no'";
    $result_equipments=mysqli_query($conn,$sql_Equipments);

    if(mysqli_num_rows($result_equipments)>0){
    
        $isDisabled ="disabled";
        if($_SESSION['adminType']==$equipmentType&&$_SESSION['nav-form']=='PENDING')
         $isDisabled="";

        $equipments="";
        $template="
        <div class='my-2'>
            <input type='checkbox' name='equip' class='mr-2' value='%s' %s %s $isDisabled>
            <label>%s - %dx</label>
        </div>
        ";

         
        $isChecked="checked";
       while($row_equipments=mysqli_fetch_assoc($result_equipments)){
           if(!$row_equipments['equipment_status']){
             $isChecked="";
           }
           else{
               $isChecked="checked";
           }


            $equipments.=sprintf($template, $row_equipments['equipment_name'],$isChecked,$isDisabled,
            $row_equipments['equipment_name'],$row_equipments['equipment_qty']);
       }

    }
    else{
        return "None";
    }

 return $equipments;
}

function getFacility($conn,$adminType,$refNo){
    $isDisabled='';
    if($_SESSION['nav-form']!='PENDING')
        $isDisabled='disabled';

        $sql="SELECT * FROM reserve_facility WHERE ref_no='$refNo' AND facility_type='$adminType'";
        $result=mysqli_query($conn,$sql);
        if(mysqli_num_rows($result)>0){
            $row=mysqli_fetch_assoc($result);
            $facility=$row['facility'];
            $room_no=$row['room_no']==NUll ? '()' : '('.$row['room_no'].')';

            $isChecked='';
            if($row['facility_status'])
                $isChecked='checked';

            $template="  <div class=' my-2 faci-container d-flex'>
            <input type='checkbox' class='mt-2' id='faci-checker' $isChecked $isDisabled>
            <p class='mb-0 ml-2'>$facility $room_no </p>
        </div>";
            
        }
        else{
            return "<div class=' my-2 faci-container d-flex'>None</div>";
        }
    return $template;
}

if($_SESSION['adminType']=='AO'||$_SESSION['adminType']=='LMO'){
  
    $main_template ="<div class='card shadow-lg box-2'>
    <div class='bg-theme py-2 rounded-top'></div>
    <div class='p-2'>
    <h5>Approval of Reservation for {$_SESSION['adminType']} </h5>
       <div class='ml-3'>Facility Requested: </div>
            %s
        <div class='ml-3'>Equipments Requested: </div>
            <div class='equip-container'>
                <div class='m-auto px-2'>
                    %s
                </div>
            </div>
        %s
       </div>
    </div>
    <div class='card shadow-lg mb-2'>
    <div class='bg-theme py-2 rounded-top'></div>
    ";

    $main_template=sprintf($main_template,getFacility($conn,$_SESSION['adminType'],$refNo),getEquipments($conn,$_SESSION['adminType'],$refNo),"%s");

}
else if($_SESSION['adminType']!="SAO"){
    $sql ="SELECT requester_sign FROM signature_information
            WHERE ref_no='$refNo'";
    $result=mysqli_query($conn,$sql);
    if(!$result){
       echo mysqli_error($conn);
    }
    $row=mysqli_fetch_assoc($result);
    $sender_sign= "data:image/png;base64,".base64_encode($row['requester_sign']);

    
    $main_template ="<div class='card shadow-lg box-2'>
    <div class='bg-theme py-2 rounded-top'></div>
    <div class='p-2'>
    <h5>Status Information</h5>
        <div class='ml-3'>Signed by Requester: </div>
        <div class='sign-container'>
            <div class='d-flex justify-content-center'>
                <img src='{$sender_sign}' class='img-holder' alt='This Image do not load properly!'>
            </div>
        </div>
        %s
    </div>
    </div>
    <div class='card shadow-lg mb-2'>
    <div class='bg-theme py-2 rounded-top'></div>
    ";
}
else{

    $sql="SELECT * FROM organization_members WHERE ref_no='$refNo'";
    $result=mysqli_query($conn,$sql);
    if(!$result){
        echo mysqli_error($conn);
    }
    $row=mysqli_fetch_assoc($result);
    $main_template ="<div class='card shadow-lg box-2'>
        <div class='bg-theme py-2 rounded-top'></div>
        <div class='p-2'>
        <h5>Status Information</h5>
            <div class='ml-3'>Members of the Organization </div>
            <div class='mt-1'>
                <ul id='members-sign'>
                    <li>Signed by: President - <span class='text-success'>{$row['president']}</span>
                        <button name='president' value='$refNo'  class='view-list'><u>(view)</u></button></li>
                    <li>Signed by: Secretary - <span class='text-success'>{$row['secretary']}</span>
                       <button  name='secretary' value='$refNo' class='view-list'><u>(view)</u></button></li>
                    <li>Signed by: Treasurer - <span class='text-success'>{$row['treasurer']}</button>
                        <u><button name='treasurer' value='$refNo 'class='view-list'><u>(view)</u></button></u></li>
                </ul> 
             </div>
            %s
        </div>
        </div>
        <div class='card shadow-lg mb-2'>
        <div class='bg-theme py-2 rounded-top'></div>
        ";
}



$signatureBtn="<div class='my-1 mx-2'>
<hr class='my-2'>
<button class='btn btn-primary' id='signature-btn'>Your Signature</button>
</div>";

$comment="";
if($_SESSION['nav-form']!="PENDING"){
    $sql="SELECT feedback FROM signature_query WHERE ref_no='{$_POST['ref_no']}' AND admin_no={$_SESSION['user_no']}";
    $result_comment=mysqli_query($conn,$sql);
    if(!$result_comment){
        echo mysqli_error($conn)." Please refresh your page.";
    }
    $row_comment=mysqli_fetch_assoc($result_comment);
    if($row_comment['feedback']==null||$row_comment['feedback']==''){
        $comment='No Comment';
    }
    else
         $comment=$row_comment['feedback'];
}


$comment_input='<div class="p-2">
<h5>Comments</h5>
<textarea id="comment" class="form-control min-textarea" placeholder="Add comments here" ></textarea>
</div>
</div>';
$comment_template='<div class="p-2">
<h5>Comments</h5>
<textarea id="comment" class="form-control min-textarea" readonly>%s</textarea>
</div>
</div>';


$pending_Button='<div id="approval-box">
<button class="btn btn-success" id="button-approve">Approve</button>
<button class="btn btn-danger" id="button-reject">Reject</button>
</div>';


$msg="<div class='text-center mt-5'><h5>This form has been <span class='{$colorTheme[$nav_menu]}'>{$nav_menu}</span>.</h5></div>";


if($_SESSION['nav-form']=="PENDING"){
    echo sprintf($main_template,$signatureBtn);
    echo $comment_input;
    echo $pending_Button;
}
else{
    echo sprintf($main_template,"");
    echo sprintf($comment_template,$comment);
    echo $msg;
}

?>