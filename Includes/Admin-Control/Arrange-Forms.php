<?php
    include_once("ConnectDB.php");
    // session_start();
    if(!isset($_SESSION['sort-date'])){
        $_SESSION['sort-date']='ASC';
    }
  

    $template=' 
    <div class="sub-box %s card shadow">
          <div class="row container my-2">
              <div class="col-5">
                  <span class="%s"><h5>%s</h5></span>
                  <p>%s</p>
              </div>
              <div class="col-5 ml-auto">
                  <p></p>
                  <p>Date Submitted:  <span class="ml-2">%s</span></p>
              </div>
              <div class="col-1 h-100">
                <div class="mt-3">
                    <button class="trash" value="%s">
                        <img src="Images\icons (admin)\delete_resized.png" width="21" height="21" >
                    </button>
                </div>
              </div>
              <hr>
              <div class="col-5 m-auto">
                  <h6>Reference No: %s</h6>
              </div>
              <div class="col-4 m-auto">
                  <p>Purpose: %s</p>
              </div>
              <div class="float-right d-flex align-items-center">
                   <button class="btn %s button-view" value="%s">View More</button>
              </div>
          </div>
     </div>';
      
     $trash_template=' <div class="sub-box %s card shadow">
            <div class="row container my-2">
                <div class="col-5">
                    <span class="%s"><h5>%s</h5></span>
                    <p>%s</p>
                </div>
                <div class="col-5 ml-auto">
                    <p></p>
                    <p>Date Submitted: <span class="ml-2">%s</span></p>
                </div>
                <hr>
                <div class="col-5 m-auto">
                    <h6>Reference No: %s</h6>
                </div>
                <div class="col-4 m-auto">
                    <p>Purpose: %s</p>
                </div>
                <div class="mt-2">
                    <h5>Cannot View</h5>
                </div>
            </div>
        </div>';
    //pending
    // $sql="SELECT `ref_no`,`{$_SESSION['navDb']}` FROM signature_information";
    // $result_=mysqli_query($conn,$sql);

    //nums

    $inbox_Pending=0;
    $inbox_Approved=0;
    $inbox_Rejected=0;
    $inbox_Trashed=0;
    
    //arrays
    $forms_Pending=array();
    $forms_Approved=array();
    $forms_Rejected=array();
    $forms_Trashed=array();


    $dependSql="";
    if( $_SESSION['navDb']=="dept_sign"){
        $dependSql="WHERE requester.requester_dept='{$_SESSION['adminType']}'";
    }
    else if($_SESSION['navDb']=='ao_sign'){
        $dependSql="INNER JOIN reserve_facility AS facility ON requester.ref_no=facility.ref_no WHERE facility.facility_type='{$_SESSION['adminType']}'";
    }
    
    $isOrg=false;
    if($_SESSION['adminType']=='SAO'){
            $sql_primary="SELECT requester.ref_no, requester.date_fill, org.org_name, org.org_activity, signstatus.{$_SESSION['adminStatus']} 
                    FROM `requester_information` AS requester
                    INNER JOIN organization_information AS org
                         ON requester.is_Org is TRUE AND requester.ref_no=org.ref_no
                    INNER JOIN signature_status AS signstatus
                        ON requester.ref_no=signstatus.ref_no
                    ORDER BY DATE(requester.date_fill) {$_SESSION['sort-date']};   ";
            $isOrg=true;
    }
    else if($_SESSION['adminType']=='LMO'||$_SESSION['adminType']=='AO'){
        $sql_primary=" SELECT  requester.requester_dept, requester.ref_no ,requester.date_fill, requester.user_no, user.user_name, user.user_status,
        requester.nature_act, signstatus.{$_SESSION['adminStatus']}
            FROM `requester_information` AS requester
        INNER JOIN `user_information` AS user 
            ON requester.user_no=user.user_no
        INNER JOIN `signature_status` AS signstatus
            ON requester.ref_no = signstatus.ref_no
        WHERE requester.ref_no IN(
            SELECT ref_no
            FROM reserve_facility
            WHERE
              facility_type='{$_SESSION['adminType']}'

        ) OR requester.ref_no IN(
            SELECT ref_no
            FROM reserve_equipments
            WHERE
                equipment_type='{$_SESSION['adminType']}'
        )
        ORDER BY DATE(requester.date_fill) {$_SESSION['sort-date']};
        ";
    }
    else{
        $sql_primary=" SELECT  requester.requester_dept, requester.ref_no ,requester.date_fill, requester.user_no, user.user_name, user.user_status,
        requester.nature_act, signstatus.{$_SESSION['adminStatus']}
            FROM `requester_information` AS requester
        INNER JOIN `user_information` AS user 
            ON requester.user_no=user.user_no
        INNER JOIN `signature_status` AS signstatus
            ON requester.ref_no = signstatus.ref_no
        $dependSql
        ORDER BY DATE(requester.date_fill) {$_SESSION['sort-date']};
        ";
    }

     $result=mysqli_query($conn,$sql_primary);
    if(!$result)
        echo mysqli_error($conn);
    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_assoc($result)){

            if($isOrg){
                $nameHeader=$row['org_name'];
                $statusHeader='Organization';
                $refNo=$row['ref_no'];
                $activity=$row['org_activity'];
            }
            else{
                $nameHeader=$row['user_name'];
                $statusHeader=$row['user_status'];
                $refNo=$row['ref_no'];
                $activity=$row['nature_act'];
            }


            $dateFormat=date_format(date_create($row['date_fill']),"M d, Y");

            if($row[$_SESSION['adminStatus']]=="PENDING"){
                $forms_Pending[]=sprintf($template,"border-pending","text-primary",$nameHeader,$statusHeader
                    ,$dateFormat,$refNo,$refNo,$activity,"btn-primary",$refNo);
                $inbox_Pending++;
            }
            else if($row[$_SESSION['adminStatus']]=="APPROVED"){
                $forms_Approved[]=sprintf($template,"border-approved","text-success",$nameHeader,$statusHeader
                ,$dateFormat,$refNo,$refNo,$activity,"btn-success",$refNo);
                $inbox_Approved++;
            }
            else if($row[$_SESSION['adminStatus']]=="REJECTED"){
                $forms_Rejected[]=sprintf($template,"border-rejected","text-danger",$nameHeader,$statusHeader
                ,$dateFormat,$refNo,$refNo,$activity,"btn-danger",$refNo);         
                 $inbox_Rejected++;
            }
            else if($row[$_SESSION['adminStatus']]=="TRASHED"){
               $forms_Trashed[]=sprintf($trash_template,"border-trashed","text-secondary",$nameHeader,$statusHeader
                ,$dateFormat,$refNo,$activity);
                $inbox_Trashed++;
            }
        }
    }


?>