<?php
    session_start();
    include_once('ConnectDB.php');
?>

<?php
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




$sql = "SELECT * FROM  requester_information AS requester  INNER JOIN signature_status AS signstatus ON 
        requester.ref_no = signstatus.ref_no
        INNER JOIN signature_information AS signinfo ON requester.ref_no = signinfo.ref_no
        INNER JOIN reservation_information AS resinfo ON requester.ref_no = resinfo.ref_no
        WHERE requester.user_no = {$_SESSION['user_no']} 
        ORDER BY DATE(requester.date_fill) DESC
        ";

$result = mysqli_query($conn, $sql);


