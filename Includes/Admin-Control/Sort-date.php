<?php
    include_once("ConnectDB.php");
    session_start();

    if($_POST['sortOrder']=='Recent-First'){
         $_SESSION['sort-date']='DESC';
    }
    else if($_POST['sortOrder']=='Old-First')
        $_SESSION['sort-date']='ASC';;

    //important
    include_once("Arrange-Forms.php");


    if(!isset($_SESSION['nav-form']))
     $_SESSION['nav-form']="PENDING";

    if($_SESSION['nav-form']=="PENDING"){
        $inbox_current=$inbox_Pending;
        $forms_current=$forms_Pending;
    }
    else if($_SESSION['nav-form']=="APPROVED"){
        $inbox_current=$inbox_Approved;
        $forms_current=$forms_Approved;
    }
    else if($_SESSION['nav-form']=="REJECTED"){
        $inbox_current=$inbox_Rejected;
        $forms_current=$forms_Rejected;
    }
    else if($_SESSION['nav-form']=="TRASHED"){
        $inbox_current=$inbox_Trashed;
        $forms_current=$forms_Trashed;
    }
   
    if($inbox_current<=0){
        $template3='<div class="jumbotron jumbotron-fluid card rounded">
        <div class="container">
          <h1 class="display-4">None</h1>
          <p class="lead">There is 0 forms corresponding in this inbox.</p>
        </div>
      </div>';
      echo $template3;
    }
    else{
        foreach ($forms_current as $forms){
            echo $forms;
        }
    }
?>
