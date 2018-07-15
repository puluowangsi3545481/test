<?php 
  include_once "../function.php";
  //1 接收参数 cat_id
   $cat_id = $_GET['cat_id'];
   //2 连接数据库
   $link = connect();
   //3 编写sql语句,执行sql语句
   $sql = "DELETE FROM category WHERE cat_id = '$cat_id'"; 
   $reslut = mysqli_query($link,$sql);
   //4 判断是否删除成功(判断受影响的函数)
    if(mysqli_affected_rows($link)){
    	//删除成功
    	$response = ['code' => 200,'msg' => '删除成功'];  
    }else{
    	//删除成功
    	$response = ['code' => -1,'msg' => '删除失败'];  
    }
   //响应给客户端json格式的数据
    echo json_encode($response);
 

 ?>