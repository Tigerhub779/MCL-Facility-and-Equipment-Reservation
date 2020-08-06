<?php
 include_once("ConnectDB.php");
 session_start();


 include_once("Arrange-Forms.php");


 if($_POST['menu']=="PENDING"){
    $inbox_current=$inbox_Pending;
    $forms_current=$forms_Pending;
    
 }
 else if($_POST['menu']=="APPROVED"){
     $inbox_current=$inbox_Approved;
     $forms_current=$forms_Approved;
 }
 else if($_POST['menu']=="REJECTED"){
     $inbox_current=$inbox_Rejected;
     $forms_current=$forms_Rejected;
 }
 else if($_POST['menu']=="TRASHED"){
     $inbox_current=$inbox_Trashed;
     $forms_current=$forms_Trashed;
 }

 $_SESSION['nav-form']=$_POST['menu'];

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
        foreach($forms_current as $form){
            echo $form;
        }
}
?>