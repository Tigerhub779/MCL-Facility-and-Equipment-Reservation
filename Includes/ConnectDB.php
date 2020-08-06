<?php
    $server_name="localhost";
    $db_user="mclreojt_OjtKids";
    $db_pwd="OjtKids777";
    $db_name="mclreojt_mcl_online_reservation";

    $conn=mysqli_connect($server_name, $db_user, $db_pwd, $db_name);

    if(!$conn)
     die("Connection Failed: ".mysqli_connect_error());
    // else
    //     echo "Connected to db successfully!</br>";
?>