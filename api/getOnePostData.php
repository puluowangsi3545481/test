<?php 
 include_once "../function.php";
 //1 接收参数
 $post_id = $_GET['post_id'];
 //2 连接数据库
 $link = connect();
 //3 编写sql语句,执行sql
 $sql = "SELECT * FROM posts WHERE post_id = $post_id";
 $res = opa($link,$sql);
 // dump($res);
 //4 判断是否成功,返回json格式数据给前台
  if($res){
  	 //成功
  	 $response =['code'=>200,'msg'=>'ok','data'=>$res[0]];
  }else{
  	 //失败
  	 $response =['code'=>-1,'msg'=>'fail'];
  }
 echo json_encode($response);
 ?>