<?php 
  // 清除用户登录成功时设置的session
  //1 先开启session
  // echo 123344;exit;
   session_start();
   //2 删除session信息
   unset($_SESSION['user_id']);
   unset($_SESSION['nickname']);
   unset($_SESSION['avatar']);
   // print_r($_SESSION);
   // 3 跳转到登录页面
    header("location:./login.php");

 ?>