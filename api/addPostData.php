<?php 
 include_once "../function.php";
 //1 接收传过来的参数
  $title = $_GET['title'];
  $content = $_GET['content'];
  $created = $_GET['created'];
  $status = trim($_GET['status']);
  $feature = $_GET['url'];
  $cat_id = $_GET['category'];
 //1.2开启session
  session_start();
  $user_id = $_SESSION['user_id']; 
 //2 连接数据库
 $link = connect();
 //3 编写sql语句
 $sql = "INSERT INTO posts(title,content,created,status,feature,cat_id,user_id) values('$title','$content','$created','$status','$feature','$cat_id','$user_id')"; //注意空格问题!!!!
  // echo $sql;
 //4执行sql,返回json格式数据给前端
 $reslut = mysqli_query($link,$sql);
 if(mysqli_affected_rows($link)){
 	 $response = ['code'=>200,'msg'=>'添加数据成功'];
 }else{
 	 $response = ['code'=>-1,'msg'=>'添加数据失败']; 
 }
echo json_encode($response);
// sleep(3);
 ?>