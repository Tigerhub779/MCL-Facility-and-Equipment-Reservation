<?php
    session_start();
    unset($_SESSION['startSession'],$_SESSION['msgRequest'], $_SESSION['refNo'],$_SESSION['natureActivity']);
    header('Location: ../Form.php');
?>