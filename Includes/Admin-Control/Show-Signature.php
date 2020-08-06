<?php
    include_once("ConnectDB.php");
    $template='<div class=" modal-flex ">
    <div class="card" id="show-member-sign">
    <div class="d-flex justify-content-end px-3">
        <span class="closeModal closeModal-2 w-100" id="member-close">&times;</span>
    </div>
        <img src="%s" alt="Image might be corrupted">
    </div>
</div>';

    $sql="SELECT {$_POST['member']} FROM organization_signature WHERE ref_no='{$_POST['ref_no']}'";

    $result =mysqli_query($conn,$sql);
    if(!$result)
        echo "Error";

    if(mysqli_num_rows($result)>0)
    {
        $row=mysqli_fetch_assoc($result);

    }

    $signature="data:image/png;base64,".$row[$_POST['member']];

    echo sprintf($template,$signature);
?>