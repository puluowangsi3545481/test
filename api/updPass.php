<?php 
  include_once "../function.php";
  //1 接收参数
   $old = $_POST['old'];
   $password = $_POST['password'];
   $confirm = $_POST['confirm'];
  //2 开启session,获得user_id
   session_start();
  $user_id = $_SESSION['user_id'];  
  //3 连接数据库
  $link = connect();
  //4 编写sql语句
  $sql1 = "select * from users where password = '$old' and user_id =$user_id"; 
  //4.1 执行sql获得结果
  $reslut = opa($link,$sql1);
  //4.2判断是否一致
  if(!$reslut){
  	 //说明没有在数据库匹配到密码
  	 $response = ['code' => -1,'msg' => '旧密码输入错误'];
  	 echo json_encode($response);exit;
  }
  //5 开始更新用户的密码为新密码
  //5.1 编写更新的sql语句
  $sql2 = "update users set password ='$password' where user_id = $user_id";
  //5.2 执行sql语句
  $res = mysqli_query($link,$sql2);
  //5.3判断是否修改成功(判断受影响的行数)
  if(mysqli_affected_rows($link)){
  	 //更新成功
  	 $response = ['code' =>200,'msg'=>'密码修改成功'];
  }else{
  	  //更新失败
  	 $response = ['code' =>-2,'msg'=>'密码修改失败'];
  }
  //6 响应json数据格式数据给前台
  echo json_encode($response);
 ?>