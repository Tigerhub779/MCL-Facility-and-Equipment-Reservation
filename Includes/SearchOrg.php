<?php

include_once('ConnectDB.php');
session_start();

if(isset($_POST['reset'])){
    $sql="SELECT org.*,sign.*,requester.date_fill,requester.user_no,signature.sao_sign FROM requester_information AS requester
	INNER JOIN organization_information AS org
    	ON requester.ref_no=org.ref_no
    INNER JOIN organization_signature AS sign
       ON requester.ref_no=sign.ref_no
    INNER JOIN signature_information AS signature
        ON requester.ref_no=signature.ref_no
WHERE requester.is_Org is TRUE AND requester.user_no={$_SESSION['user_no']}";
}
else{
    $searchRefNo=$_POST['refNo'];

    $sql="SELECT org.*,sign.*,requester.date_fill,requester.user_no,signature.sao_sign FROM requester_information AS requester
    INNER JOIN organization_information AS org
        ON requester.ref_no=org.ref_no
    INNER JOIN organization_signature AS sign
    ON requester.ref_no=sign.ref_no
    INNER JOIN signature_information AS signature
        ON requester.ref_no=signature.ref_no
    WHERE requester.is_Org is TRUE 
        AND requester.ref_no='$searchRefNo'";

}

$result =mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){
    while($row=mysqli_fetch_assoc($result)){
        $status='';
        if($row['president']==NULL||$row['secretary']!=NULL||$row['treasurer']!=NULL){
            $status='<span class="text-primary">Pending</span>';
        }
        else if($row['sao_sign']==NULL)
        {
            $status='<span class="text-warning">In Progress</span>';
        }
        else{
            $status='<span class="text-success">Completed</span>';
        }

        $dateFill=date_format(date_create($row['date_fill']),'M d, Y');
        $template="<div class='org-card my-4 shadow'>
                          <div class='row '>
                             <div class='col-md-6 d-flex align-items-center'>
                                  <h6>Reference No: {$row['ref_no']}</h6>
                               </div>
                          <div class='col-md-3 text-center  d-flex align-items-center'>
                                  <p>{$row['org_name']}</p>
                          </div>
                         <div class='col-md-3 text-center  d-flex align-items-center'>
                                   <p>Date Filled: $dateFill</p>
                          </div>
                         </div>
                        <div style=' width: 90%;'class='m-auto'>
                           <hr class='bg-secondary'>
                        </div>
                        <div class='row'>
                            <div class='col-md-5'>
                                <p>Proposed Activity: {$row['org_activity']} </p>
                            </div>
                            <div class='col-md-3 text-center'>
                                <p>$status</p>
                            </div>
                            <div class='col-md-4 d-flex justify-content-end'>
                                <button class='btn-view btn btn-primary mt-0' value='{$row['ref_no']}'> View More</button>
                            </div>
                        </div>
                     </div>
         ";

          echo $template;
    }
    if(!isset($_POST['reset']))
    echo "<button class='btn btn-primary' id='cancel-search'>Cancel Search</button>";
}
else{
    if(!isset($searchRefNo)){
            echo '<div class="jumbotron jumbotron-fluid">
            <div class="container">
              <h1 class="display-4">You have not requested a Organization form yet.</h1>
              <p class="lead">To take a Form Go to Home Page and Click Button Take a form.</p>
            </div>
          </div>';
        
    }
    else{
        $template2="<div class='jumbotron jumbotron-fluid'>
        <div class='container'>
        <h1 class='display-4'>No reference number $searchRefNo</h1>
        <p class='lead'>It is either it is not a organization form or the ref No does not exist.</p>
        <button class='btn btn-primary' id='cancel-search'>Cancel Search</button>
        </div>
    </div>";
    echo $template2;
    }
}

?>