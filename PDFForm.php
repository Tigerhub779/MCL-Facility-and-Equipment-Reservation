<?php
    include_once('Includes/ConnectDB.php');
    require_once('Includes/FPDF/rotation.php');
    session_start();


    $refNo=$_SESSION['ref_no'];

    $sql="SELECT user.user_name,requester.*,reserve.* FROM requester_information AS requester
            INNER JOIN user_information AS user 
            ON requester.user_no=user.user_no 
            INNER JOIN reservation_information AS reserve
            ON requester.ref_no=reserve.ref_no
            WHERE requester.ref_no='$refNo'
    ";

    $result=mysqli_query($conn,$sql);



    if(mysqli_num_rows($result)>0){
        $row=mysqli_fetch_assoc($result);

        $name=$row['user_name'];
        $dateFill=date_format(date_create($row['date_fill']),'M d, Y');
        $dept=$row['requester_dept'];
        $natureACT=$row['nature_act'];

        $dateStart=date_format(date_create($row['date_start']),'M d, Y');
        $dateEnd=date_format(date_create($row['date_end']),'M d, Y');
        $timeStart=date_format(date_create($row['time_start']),"h:i a");;
        $timeEnd=date_format(date_create($row['time_end']),"h:i a");

        $remarksFacility='None';
        $remarksEquipments='None';

        if($row['facility_remarks']!=NULL)
            $remarksFacility=$row['facility_remarks'];

        if($row['equipment_remarks']!=NULL)
            $remarksEquipments=$row['equipment_remarks'];

        $facility='None';
        $room='None';

        if($row['reserve_type']=='Both'||$row['reserve_type']=='Facility'){
            $sql_facility="SELECT facility, room_no FROM reserve_facility 
                            WHERE ref_no='$refNo' AND facility_status is TRUE";

            $result_facility=mysqli_query($conn,$sql_facility);
            if(mysqli_num_rows($result_facility)>0){
                $row_facility=mysqli_fetch_array($result_facility);
                
                $facility=$row_facility['facility'];
                
                
                
                if($row_facility['room_no']!=NULL){
                    $room=$row_facility['room_no'];
                }
            }
        }

        $arrayOfNames=array();
        $arrayEquipments=array();
        if($row['reserve_type']=='Both'||$row['reserve_type']=='Equipments'){
            $sql_equipments="SELECT equipment_name, equipment_qty FROM reserve_equipments
                            WHERE ref_no='$refNo' AND equipment_status is TRUE";

            $result_equipments=mysqli_query($conn,$sql_equipments);
            if(mysqli_num_rows($result_equipments)>0){
               while($row_equipments=mysqli_fetch_array($result_equipments)){
                   $arrayOfNames[]=$row_equipments['equipment_name'];
                    $arrayEquipments[$row_equipments['equipment_name']]=$row_equipments['equipment_qty'];
               }
            }

        }


    }

    $arrayOfColumns=array('requester_sign','dept_sign','ao_sign','lmo_sign','cdmo_sign');

    $sql="SELECT user.user_name, user.user_status,signQuery.* FROM signature_query AS signQuery
        INNER JOIN user_information AS user
            ON signQuery.admin_no=user.user_no
        WHERE ref_no='$refNo'";

    $result_signatureQuery=mysqli_query($conn,$sql);

    $sql="SELECT * FROM signature_information WHERE ref_no='$refNo'";
    $result_signature=mysqli_query($conn,$sql);
    if(mysqli_num_rows($result_signature)>0)
    {
        $rowSignature=mysqli_fetch_array($result_signature);
    }
   function getColumnEquivalent($userStatus){
        if($userStatus=='Student'||$userStatus=='Employee')
            return "requester_sign";
        else if($userStatus=='Admin-SAO'||$userStatus=='Admin-LMO'||$userStatus=='Admin-AO'||$userStatus=='Admin-CDMO'){
                  $userType=strtolower(explode('-',$userStatus)[1]);
                  return $userType.'_sign';
        }
        else{
            return 'dept_sign';
        }

   }

    $arrayOfSignNames=array($name);
    $arrayOfSignStatus=array(strtoUpper("Requester_signature"));
    $arrayOfSignDate=array(date_format(date_create($row['date_fill']),'m/d/Y'));
    $arrayOfSignatures=array("data:image/png;base64,".base64_encode($rowSignature['requester_sign']));


    if(mysqli_num_rows($result_signatureQuery)>0&&mysqli_num_rows($result_signature)>0){
       while($rowQuery=mysqli_fetch_assoc($result_signatureQuery)){
            $columnSignature=getColumnEquivalent($rowQuery['user_status']);

            $arrayOfSignNames[]=$rowQuery['user_name'];
            $arrayOfSignStatus[]=strtoUpper($columnSignature."ature");
            $arrayOfSignDate[]= date_format(date_create($rowQuery['date_sign']),'m/d/Y');
            $arrayOfSignatures[]="data:image/png;base64,".base64_encode($rowSignature[$columnSignature]);
       }
        
    }



    // print_r($arrayOfSignNames);
    // echo "</br>";
    // print_r($arrayOfSignStatus);
    // echo "</br>";
    // print_r($arrayOfSignDate);

    function setHeader($pdf,$font,$text){
        
        if($_SESSION['isComplete']){
            $pdf->SetTextColor(255,255,255);
            $pdf->setFillColor(38, 53, 96); 
        }
        $pdf->SetFont($font,'B','14');
        $pdf->Cell(200,8,$text,1,1,'C',$_SESSION['isComplete']);


        // back to default
        $pdf->SetTextColor(0,0,0);
    }

    function printEquipments($pdf,$arrayOfNames,$arrayQty,$font){


        if(count($arrayOfNames)>0){
            $pdf->SetFillColor(153, 204, 255);
            $pdf->SetFont($font,'I','12');

            $pdf->Cell(56,10,'Name',1,0,'',TRUE);
            $pdf->Cell(56,10,'Quantity',1,1,'',TRUE);

            $pdf->SetFont($font,'','12');

            foreach($arrayOfNames as $name){
                $pdf->Cell(62,10,'',0,0);
                $pdf->Cell(56,10,$name,1,0,'C');
                $pdf->Cell(56,10,$arrayQty[$name],1,1,'C');
            }
        }
        else{
            $pdf->SetFont($font,'','14');
            $pdf->Cell(56,10,'None',0,1);

        }
        
    }
  
    //date today
    date_default_timezone_set('Asia/Manila');
    $dateToday=date(' F Y h:i:s A');
   
?>
<?php
    class PDF extends PDF_Rotate
    {
        
        function Header()
        {
            //Put the watermark
            $this->SetFont('Arial','B',50);
            $this->SetTextColor(255,192,203);
            if(!$_SESSION['isComplete'])
              $this->RotatedText(25,200,'Do Not Print! For Review Only',45);
        }
    
        function RotatedText($x, $y, $txt, $angle)
        {
            //Text rotated around its origin
            $this->Rotate($angle,$x,$y);
            $this->Text($x,$y,$txt);
            $this->Rotate(0);
        }
    }

    $pdf=new PDF();
    $pdf->SetAutoPageBreak(true,10);
    $font='Arial';


    $pdf->AddPage();

    //water mark
   // $pdf->RotatedText(50,40,'',45);
   
    $pdf->SetFont($font,'B','25');


    //cell[width, height, text, border, end line, [align]]
    $pdf->Image('Images/Mcl_logo2.jpg',10,8,47.8,'PNG');
    $pdf->Cell(200,20,'MCL Online Reservation Form',0,1,'R');

    $pdf->SetFont($font,'','12');
    //for reference
    $pdf->Cell(200,10,'Reference No: '.$refNo,0,1,'R');
    $pdf->Cell(200,10,'PDF Created on'.$dateToday,0,1,'R');

    //set header
        setHeader($pdf,$font,'Profile Details');

    $pdf->SetFont($font,'B','14');
    $pdf->Cell(20,10,'Name:',0);
    $pdf->SetFont($font,'','14');
    $pdf->Cell(66,10,$name,0,1);


    //date of fill
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(30,10,'Date of Fill:');
    $pdf->SetFont($font,'','14');
    $pdf->Cell(66,10,$dateFill,0,1);


    //dept of college
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(40,10,'Dept of College:',0,0);
    $pdf->SetFont($font,'','14');
    $pdf->Cell(66,10,$dept,0,1);

    //nature of activity
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(44,10,'Nature of Activity:',0,0);
    $pdf->SetFont($font,'','14');
    $pdf->Cell(80,10,$natureACT,0,1);

    //facility used
    $pdf->SetFont($font,'B','14');
        setHeader($pdf,$font,'Reservation Details');

    //date of fill
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(35,10,'Date of Use:',0,0);
    $pdf->SetFont($font,'','14');
    $pdf->Cell(110,10,"$dateStart - $dateEnd",0,1);

    //time of use
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(35,10,'Time of Use:',0,0);
    $pdf->SetFont($font,'','14');
    $pdf->Cell(110,10,"$timeStart - $timeEnd",0,1);


    //facility
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(44,10,'Approved Facility:',0,0);
    $pdf->SetFont($font,'','14');
    $pdf->Cell(100,10,$facility,0,1);

    //Specified Room:
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(42,10,'Specified Room:',0,0);
    $pdf->SetFont($font,'','14');
    $pdf->Cell(110,10,$room,0,1);

      //space
      $pdf->Cell(2,2,'',0,1);

    //equipment header
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(62,10,'Approved Equipments:',0,0);

    //equipments quantity Format
        printEquipments($pdf,$arrayOfNames,$arrayEquipments,$font);

    //remarks
    $pdf->SetFont($font,'B','14');
    $pdf->Cell(62,10,'Remarks',0,1);

    $pdf->SetFont($font,'','12');
    $pdf->Cell(22,10,'');
    $pdf->MultiCell(160,10,'Facility: '.$remarksFacility,1,1);
    $pdf->Cell(22,10,'');
    $pdf->MultiCell(160,10,'Equipments: '.$remarksEquipments,1,1);


    //space
    $pdf->Cell(5,5,'',0,1);

    function printSignature($pdf,$font,$arrayOfSignNames,$arrayOfSignStatus,$arrayOfSignDate,$arrayOfSignatures){
        // $pdf->Cell(66,10,"Requester's Signature",1,0,'C');

        $length=count($arrayOfSignNames);

      for($mainCount=0; $mainCount<$length; $mainCount=$mainCount+3){
            $pdf->SetFont($font,'B','14');
            //status
            $pdf->SetFillColor(255, 255, 153);
          
            $lastCell=0;
            for($i=$mainCount; $i<$mainCount+3&&$i<$length; $i++){
                if($i+1>=$mainCount+3||$i+1>=$length)   
                      $lastCell=1;
                $pdf->Cell(66,10,$arrayOfSignStatus[$i],1,$lastCell,'C',TRUE);

               // echo $i.'<br>';
            }


            //signature
            $lastCell=0;
            for($i=$mainCount; $i<$mainCount+3&&$i<$length; $i++){
                if($i+1>=$mainCount+3||$i+1>=$length)   
                    $lastCell=1;

                $pdf->Cell(66,30, $pdf->Image($arrayOfSignatures[$i],$pdf->GetX(),$pdf->GetY(),66,0,'PNG'),1,$lastCell,'C');
            }
            
            $pdf->SetFont($font,'','12');

            //name and date
            $lastCell=0;
            for($i=$mainCount; $i<$mainCount+3&&$i<$length; $i++){
                if($i+1>=$mainCount+3||$i+1>=$length)   
                    $lastCell=1;
                
                $nameParts=explode(' ',$arrayOfSignNames[$i]);
                $nameFilter=$nameParts[0]." ".$nameParts[count($nameParts)-1];
                $pdf->Cell(66,10,$nameFilter." (".$arrayOfSignDate[$i].")",1,$lastCell,'C');
            }

            // space
            $pdf->cell(5,40,'',0,1);
        }

    }

    if($_SESSION['isComplete']){
     printSignature($pdf,$font,$arrayOfSignNames,$arrayOfSignStatus,$arrayOfSignDate,$arrayOfSignatures);
    }

    $pdf->Output();
?>