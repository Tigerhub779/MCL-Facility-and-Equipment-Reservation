<?php
    session_start();
    $_SESSION['admin_signature']=$_POST['admin_sign'];
    echo "Save Signature";
?>