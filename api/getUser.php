<?php 
  require_once "../function.php";
  // echo 11222;
  //1 获取user_id，从session中来
   session_start(); //开启session
   $user_id = $_SESSION['user_id'];
   //2 连接数据库
   $link = connect();
   //3 编写sql语句,执行sql
   $sql = "select * from users where user_id = $user_id";
   $result = opa($link,$sql);
   //因为query方法返回的结束是一个二维数组，通过下标0可以取出对应的数据
   $data = $result[0];
    // dump($result[0]);
   //4返回json格式数据 
   $response = ['code'=>200,'msg'=>'获取用户数据成功','data' => $data];
   echo json_encode($response);


 ?>