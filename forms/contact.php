<?php
$conn=mysqli_connect('localhost','id17777862_darsh_user','vdn|Bz%)=2qj1Bjc');
$name=$_POST['name'];
$email=$_POST['email'];
$subject=$_POST['subject'];
$message=$_POST['message'];

mysqli_select_db($conn,'id17777862_darsh_db');

    if(!empty($name)&&!empty($email)&&!empty($subject)&&!empty($message)){
         $sql= "INSERT INTO contact (name,email,subject,message) VALUE ('$name','$email','$subject','$message')";
    }

    $query=mysqli_query($conn,$sql);
   if($query){
    echo "Form Submit successfully";

   }


?>
