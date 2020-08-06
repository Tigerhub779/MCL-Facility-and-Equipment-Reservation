<?php
    include_once('ConnectDB.php');
    session_start();

    $refNo=$_POST['refNo'];

    $_SESSION['ref_no']="$refNo";
    $_SESSION['isComplete']=false;

    $sql="SELECT requester.date_fill, signstatus.*,signinfo.* FROM  requester_information AS requester  
    INNER JOIN signature_status AS signstatus ON 
      requester.ref_no = signstatus.ref_no
      INNER JOIN signature_information AS signinfo ON requester.ref_no = signinfo.ref_no
      WHERE requester.ref_no='$refNo' ";

    $result =mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)){
        $row=mysqli_fetch_assoc($result);

        $total=0;
        $pending = 0;
        $approved = 0;
        $rejected = 0;
        $arrayColumn = array('dept_status', 'ao_status', 'lmo_status', 'cdmo_status');
        $status='';
        foreach ($arrayColumn as $column){
            if($row[$column]=='PENDING'){
                $pending++;
            }
            else if($row[$column]=='APPROVED')
                $approved++;
            else if($row[$column]=='REJECTED'){
                $rejected++;
            }
            else if($row[$column]=='TRASHED'){
                $nameColumnSign=str_replace('status', 'sign', $column);
                if($row[$nameColumnSign]!=NULL){
                            $approved++;
                }
                else{
                    $rejected++;
                }
            }
       
            if($row[$column]!=NULL)
                $total++;

        }

        $textBtn='Review Details (PDF File)';
        $colorBtn="btn-secondary";
        if($rejected>0){
            $indicatorSrc='8.png';
            $status='DENIED';
        }
        else if($total==$approved){
            $textBtn='Download Form (PDF File)';
            $colorBtn="btn-success";
            $indicatorSrc='7.png';
            $status='COMPLETED';
            $_SESSION['isComplete']=true;
        }
       else if($approved>0){
            $indicatorSrc='6.png';
            $status='IN PROGRESS';
       }
        else{
            $indicatorSrc='5.png';
            $status = 'PENDING';
            $color = '#FFD700';
        }

        $date=date_create($row['date_fill']);
        $dateExpected1=date_create($row['date_fill']);
        $dateExpected2=date_create($row['date_fill']);
        $dateFill=date_format($date,'d M Y');
        
        //add some dates
        date_add($dateExpected1,date_interval_create_from_date_string("1 days"));
        date_add($dateExpected2,date_interval_create_from_date_string("3 days"));
        $dateExpected1=date_format($dateExpected1,'d M Y');
        $dateExpected2=date_format($dateExpected2,'d M Y');


        $template="
                <div class='bg-colorTheme rounded-top px-3'>
                    <div class='d-flex justify-content-between'>
                         <h6>Reference No: $refNo</h6>
                        <span id='search-close' class='close text-white'>&times;</span>
                    </div>
                </div>
                <div class='row bgcolorTheme2'>
                    <div class='col-md-4 text-center'>
                        <p class='mb-0'>DATE SUBMITTED </p>
                        <p class='font-weight-bolder'>$dateFill</p>
                    </div>
                    <div class='col-md-4 text-center'>
                        <p class='mb-0'>STATUS </p>
                        <p class='font-weight-bolder'>$status</p>
                    </div>
                    <div class='col-md-4 text-center'>
                        <p class='mb-0'>EXPECTED DATE</p>
                        <p class='font-weight-bolder'>$dateExpected1 - $dateExpected2 </p>
                    </div>
                </div>
                <div class='mt-3 d-flex justify-content-center'>
                <img src='Images\icons (view status)\status (indicator)\with words/$indicatorSrc' id='img-indicator' class='img-fluid'>
        </div>
        <div class='mt-3 d-flex justify-content-center'>
               <a href='PDFForm.php' target='_blank'><button class='btn $colorBtn'>$textBtn</button></a>
        </div>
            
                <span class='mt-2'>
                  <p class='text-center'>$approved of $total signatures Signed</p>
                </span>

                <div class='container'>
                    <div>
                        <h5>History</h5>
                    </div>
                    <table class='table table-stripped'>
                        <thead class='bgcolorTheme2'>
                            <tr class='text-center'>
                            <th scope='col'>Date</th>
                            <th scope='col'>Time</th>
                            <th scope='col'>Recepient</th>
                            <th scope='col'>Status</th>
                            </tr>
                        </thead>
                        <tbody class='text-center'>
                           %s
                        </tbody>
                    </table>
                </div>
            ";

        $sql="SELECT sign.*, user.user_name, user.user_status FROM signature_query AS sign 
                INNER JOIN user_information AS user
                ON sign.admin_no=user.user_no
                WHERE sign.ref_no='$refNo'";
        $result=mysqli_query($conn,$sql);
      $tableData='';
        if(mysqli_num_rows($result)>0){
            while($row=mysqli_fetch_assoc($result)){
                $date=date_create($row['date_sign']);
                $dateSign=date_format($date,'M d, Y');
                $timeSign=date_format(date_create($row['time_sign']),'h:i a');

                $btn="";
                if($row['feedback']!=NULL){
                    $btn="<button class='btn  btn-comment m-0 ml-3' value='%s'><img src='Images\icons (view status)\comment1_resized.png' width='32px' height='32px'></button>";
                    $btn=sprintf($btn,$row['feedback']);
                }
                $textColor="text-danger";

                if($row['status']=='APPROVED')
                    $textColor="text-success";
                if($row['status']=='TRASHED'){
                        if($row['user_status']=='Admin-AO'||$row['user_status']=='Admin-LMO'||$row['user_status']=='Admin-CDMO'){
                                $statusType=explode('-',$row['user_status']);
                                $statusType=strtolower($statusType[0]).'_sign';
                        }
                        else
                            $statusType='dept_sign';

                            // echo $statusType;
                       $sql_secondary="SELECT $statusType FROM signature_information WHERE ref_no='$refNo'";
                       $result2=mysqli_query($conn,$sql_secondary);
                       
                       if($result2){
                       $row_secondary=mysqli_fetch_assoc($result2);

                       if($row_secondary[$statusType]!=NULL)
                       {
                        $textColor="text-success";
                         $status='APPROVED';
                       }
                        else{
                            $status='REJECTED';
                        }
                       }
                       else{
                           $status=$row['status'];;
                       }
                }
                else{
                    $status=$row['status'];
                }


                $template2="<tr>
                            <td>$dateSign</td>
                            <td>$timeSign</td>
                            <td><div>
                                <p>{$row['user_name']}</p>
                                <span class='font-weight-bold'>({$row['user_status']})</span>
                            </div></td>
                            <td>
                            <div class='d-flex justify-content-center align-content-center'>
                            <p class='mx-1 font-weight-bolder $textColor'>$status</p>
                              $btn
                            </div>
                            </td>
                            </tr>";
                $tableData.= $template2;
            }
        }
    echo sprintf($template,$tableData);
    }
    else{
        echo $sql;
    }
?>