<?php 
 include_once "../function.php";
  //获取要删除的id
  $delId = trim($_GET['delId']);
  //连接数据库 
  $link = connect();
  //编写sql语句
  $sql = "DELETE FROM posts WHERE post_id=$delId";
  $res =  mysqli_query($link,$sql);
  //判断是否删除成功(判断受影响的函数)
  if(mysqli_affected_rows($link)){
  	 //删除成功
  	 $response = ['code'=>200,'msg'=>'文章删除成功!'];
  }else{
  	 //删除失败
  	 $response = ['code'=>-1,'msg'=>'文章删除失败!'];
  }
  //响应给前台json格式数据
  echo json_encode($response);


 ?>