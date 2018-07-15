<?php 
  include_once "../function.php";
  //获得传过来的参数
  $nickname = $_POST['nickname'];
  $bio = $_POST['bio'];
  $avatar = $_POST['avatar'];
  //获得session中的user_id
   session_start();  //开启session
  $user_id = $_SESSION['user_id'];  
 //1 连接数据库
  $link = connect();
 //2 编写sql语句
  $sql = "update users set nickname='$nickname',bio=' $bio',avatar='$avatar' where user_id = $user_id";
 //3 执行sql
  $res = mysqli_query($link,$sql);
 //4 判断,响应json格式数据
  if(mysqli_affected_rows($link)){
  	 //更新成功
  	 $response =['code' => 200,'msg' =>'更新成功'];
  }else{
  	//更新失败
  	 $response =['code' => -1,'msg' =>'更新失败'];
  }
 echo json_encode($response);
 ?>