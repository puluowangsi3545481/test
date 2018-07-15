<?php 
   //引入封装的数据库函数
   include_once "../function.php";
   //绝对路径 dirname();获取目录的名称
  //include_once dirname(__DIR__)."/function.php";
  //1接收传过来的参数
   $cat_id = $_GET['cat_id'];
   $lastPostId = $_GET['lastPostId'];
   //2 连接数据库
   $link = connect();
   //3 sql语句
   // sleep(2);
   $sql = "SELECT t1.*,t2.cat_name,t3.nickname,
   (SELECT count(*) FROM comments WHERE post_id = t1.post_id) commentsCount
    FROM posts t1
   LEFT JOIN category t2 on t1.cat_id = t2.cat_id
   LEFT JOIN users t3 on t1.user_id = t3.user_id
   WHERE t2.cat_id = $cat_id AND t1.post_id < $lastPostId
   ORDER BY t1.post_id DESC
   LIMIT 5";
   //4 执行sql语句,获得数据
   $moreData = opa($link,$sql);
    //5、返回前端json数据 一般前后台数据的返回格式有个约定
    //数据格式：{code,message,data}
    if($moreData){
    	//成功
    	$res = ['code' => 200,'msg' => '加载数据成功..','data' => $moreData];
    }else{
    	//失败
    	$res = ['code' => -1,'msg' => '加载数据失败..','data' => [] ];
    }
   //6 输出json格式的数据
   echo json_encode($res); 

 ?>