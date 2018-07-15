<?php 
  include_once "../function.php";
  //接受传过来的post_id的参数
   $post_id = $_POST['post_id'];
   // print($post_id);
   //1 连接数据库
   $link = connect();
   //2 编写sql语句
   $sql1 = "select likes from posts where post_id = $post_id";
   //3 执行sql语句,获得数据
   $result = opa($link,$sql1);
   $oldLikes = $result[0]['likes'];
   //把以前的数据库数量进行更新，加1 
   //4更新数量的sql语句,执行sql语句
   $sql2 = "update posts set likes = likes +1 where post_id = $post_id";
   $res = mysqli_query($link,$sql2);
   if($res){
   	 //成功,返回点赞的总数量
   	  $response = ['code' => 200, 'msg' => '成功','newLikes' => $oldLikes+1];
   }else{
   	   //成功,返回点赞的总数量
   	  $response = ['code' => -1, 'msg' => '失败'];
   }
   //响应json格式的数据
   echo json_encode($response);
    
    


 ?>