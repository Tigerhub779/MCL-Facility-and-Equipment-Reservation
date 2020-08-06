<?php
session_start();
include_once('ConnectDB.php');
 $sql = "SELECT * FROM  requester_information AS requester  INNER JOIN signature_status AS signstatus ON 
         requester.ref_no = signstatus.ref_no
          INNER JOIN signature_information AS signinfo ON requester.ref_no = signinfo.ref_no
          INNER JOIN reservation_information AS resinfo ON requester.ref_no = resinfo.ref_no
          WHERE requester.user_no = {$_SESSION['user_no']} 
          ORDER BY DATE(requester.date_fill) DESC
          ";


$result =mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){
    if(!isset($_POST['reset'])){
    echo'<button class="btn btn-primary" id="cancel-search">Cancel Search</button>';
    }    
    while($row=mysqli_fetch_assoc($result)){
            
            $total=0;
            $pending = 0;
            $approved = 0;
            $rejected = 0;
            $arrayColumn = array('dept_status', 'ao_status', 'lmo_status', 'sao_status', 'cdmo_status');
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
            if($rejected > 0) {
            $status = 'DENIED';
            $color = '#FF0000';
            }elseif($total==$approved){
                $status = 'COMPLETE';
                $color = '#008000';
            } else if($approved>0){
                $status = 'IN PROGRESS';
                $color = '#FFA500';
        }else{
            $status = 'PENDING';
            $color = '#FFD700';
            }
            $template = '
            <div class="container my-4 ">
            <div class="card bg-design">
                <div class="card-body">
                    <div class="row" name="uno">
                        <div class="col-5"><h5 class="reference">Reference No. - <strong>%s</strong></h5><p class="activity"><strong>Activity/Purpose: %s</strong></p></div>
                        <div class="col-3"><h4><span class="badge text-light" style="background-color:%s;">%s</span></h4></div>
                        <div class="col-4"><p class="submitted"><i>Date submitted: %s</i></p><p class="signature"> Total pending signature: %s</p></div>
                    </div>
                    <hr class="m-0">
                    <div class="row mt-3">
                        <div class="col-4"><p class="mt-2 dateStart">Date of Use:  %s - %s </p></div>
                        <div class="col-4"><p class="mt-2 dateEnd">Time of Use:  %s - %s</p></div>
                        <div class="col-4 d-flex justify-content-around">
                        <button class="btn-view btn  btn-primary text-light mt-0" value="%s" >view more</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        ';



        echo sprintf($template,$row['ref_no'], $row['nature_act'], $color, $status, date_format(date_create($row['date_fill']),'M d, Y'), $pending,date_format(date_create($row['date_start']),'M d, Y'),
        date_format(date_create($row['date_end']),'M d, Y'),date_format(date_create($row['time_start']),'h:i A') ,date_format(date_create($row['time_end']),'h:i A'),$row['ref_no'],$row['ref_no']
        ,$row['ref_no']);  
        
    }
}
else{
    echo '<div class="jumbotron jumbotron-fluid">
    <div class="container">
      <h1 class="display-4">You have not requested a form yet.</h1>
      <p class="lead">To take a Form Go to Home Page and Click Button Take a form.</p>
    </div>
  </div>';
}
?>