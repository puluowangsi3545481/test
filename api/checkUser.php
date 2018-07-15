<?php 
  include_once "../function.php";
  //1 获取传过来的参数
   $email = $_POST['email'];
   $password = $_POST['password'];
   //2连接数据库
   $link = connect();
   //3 编写sql语句
   $sql = "select * from users where email = '$email' and password = '$password'";
   //4 执行sql语句
   $result = mysqli_query($link,$sql);
   //5 判断查询结果
   if(mysqli_num_rows($result)){
   	  //获取信息
   	  $userInfo = mysqli_fetch_assoc($result);
        // print_r($userInfo);
   	  //匹配成功
   	  $response = ['code' =>200,'msg' => '登录成功'];
   	  //设置session 保存用户信息
   	     session_start(); // 开启session
         //设置需要要信息
         $_SESSION['user_id'] = $userInfo['user_id'];
         $_SESSION['nickname'] = $userInfo['nickname'];
         $_SESSION['avatar'] =  $userInfo['avatar'];
         // print_r($_SESSION);
   }else{
   	  //匹配失败
   	  $response = ['code'=> -1,'msg'=>'用户名或密码验证失败'];
   }
   //响应json数据给前台
   // print_r($_SESSION['user_id']);
   echo json_encode($response);


 ?>