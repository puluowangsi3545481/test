<?php 
  include_once "../function.php";
  //1接收参数
  $title = $_GET['title'];
  $content = $_GET['content'];
  $feature = $_GET['img'];
  $created = $_GET['created'];
  $cat_id = $_GET['category'];
  $status = $_GET['status'];
  $post_id = $_GET['post_id'];
  //2连接数据库
  $link = connect();
  //3编写sql语句,执行sql
  $sql = "UPDATE posts SET title='$title',content='$content',feature='$feature',created='$created',cat_id='$cat_id',status='$status' WHERE post_id=$post_id";
  $res = mysqli_query($link,$sql);
  //4 判断是否成功,返回json格式数据
   if(mysqli_affected_rows($link)){ //mysqli_affected_rows($link)判断数据库中受影响的行数
         //成功
         $response =['code'=>200,'msg'=>'修改成功'];
   }else{
   	    //失败
         $response =['code'=>-1,'msg'=>'修改失败'];
   }
  echo json_encode($response);
 ?>