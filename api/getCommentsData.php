<?php 
 include_once "../function.php";
 //1 接收参数
 $page = $_GET['page'];
 //2 连接数据库
 $link = connect();
 //3 编写sql语句,执行sql limit $offset,$pageSize
 $pageSize = 10; //定义每页显示的文章条数
 $offset = ($page-1) * $pageSize; //查询的起始位置（偏移量）
 $sql1 = "SELECT t1.*,t2.title FROM comments t1 LEFT JOIN posts t2 ON t2.post_id = t1.post_id
   LIMIT $offset,$pageSize";
 $result1 = opa($link,$sql1); 
 //3.2 执行查询总数的sql，获取出评论的总数，进而算出页码数
 $sql2 = "SELECT count(*) as count FROM comments";
 $result2 = opa($link,$sql2);
 $pageCount = ceil($result2[0]['count']/$pageSize); //向上取整一下
 //4 返回json格式数据给前台
  if($result1){
  	 //成功
  	 $response = ['code'=>200,'msg'=>'success','data'=>$result1,'pageCount'=>$pageCount];
  }else{
  	  //失败
  	 $response = ['code'=>-1,'msg'=>'fail'];
  }
 echo json_encode($response);

 ?>