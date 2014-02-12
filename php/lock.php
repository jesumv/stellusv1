<?php
session_start();

$user_check=$_SESSION['login_user'];

$ses_sql=mysqli_query($mysqli,"select username from usuarios where username='$user_check' ");
$empre = mysqli_query($mysqli,"select empresa from usuarios where username='$user_check' ");

$row=mysqli_fetch_array($ses_sql);

$login_session=$row['username'];

if(!isset($login_session))
{
   if($empre = 0){
      header("Location: index.php"); 
   }
   
   else {}

}
?>