<?php 
  include_once "../function.php";
  //1 获取传过来的参数
  $cat_ids = $_GET['cat_ids'];
  // dump($cat_ids); //<pre/>string(11) "2,3,4,80,81"
  //2 连接数据库
   $link = connect();
  //3 编写sql语句
  $sql = "delete from category where cat_id in($cat_ids)";  
  //4 执行sql语句
  $res = mysqli_query($link,$sql);
  //5 判断是否删除成功
   if(mysqli_affected_rows($link)){
   	  //删除成功
   	  $response = ['code' => 200,'msg' => '批量删除成功'];
   }else{
   	 //删除失败
   	  $response = ['code' => -1,'msg' => '批量删除失败'];
   }
  //6 响应json格式的数据给前台
  echo json_encode($response); 
 ?>