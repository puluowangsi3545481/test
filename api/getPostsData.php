<?php 
  include_once "../function.php";
  //1连接数据库
  $link = connect();
  //2 因为刚加载文章数据的时候没有带page参数，给设置一个默认为1
  $page = isset($_GET['page'])?$_GET['page']:1;
  //2.1 接受筛选的参数
   $cat_id = isset($_GET['cat_id'])?$_GET['cat_id']:"all";
   $status = isset($_GET['status'])?$_GET['status']:"all";
  //2.2 组装where 查询条件
  //where后面没有条件就会报错，where后面拼接1=1，就避免这种出错,这种语句一般用在搜索中，主要防止没有查询条件而报错
  $where = '1+1';
   if($cat_id != 'all'){
   	 $where .= " and t1.cat_id = $cat_id"; 
   }
   if($status != 'all'){
   	 $where .= " and t1.status = '$status'";
   }
  //3 编写sql语句
  $pageSize = 10; //每页显示的条数
  $offset = ($page-1) * $pageSize;// 定义limit查询的起始位置
  $sql1 = "SELECT t1.*,t2.cat_name,t3.nickname 
  FROM posts t1 
  LEFT JOIN category t2 ON t1.cat_id = t2.cat_id
  LEFT JOIN users t3 ON t1.user_id = t3.user_id
  where $where
  order by t1.post_id desc
  LIMIT $offset, $pageSize";
  //4 执行sql语句获得数据
  $data = opa($link,$sql1);
  //5 定义文章总数,取出文章的总数,算出分页总页码数
  $sql2 = "SELECT count(*) postsCount FROM posts t1 where $where"; 
  //6 执行sql语句,获得文章总数
  $data2 = opa($link,$sql2);
  // <pre/>array(1) {
	  // [0]=>
	  // array(1) {
	  //   ["postsCount"]=>
	  //   string(4) "1004"
	    // }
	 // }
  $postsCount = $data2[0]['postsCount'];
  $pageCount = ceil($postsCount/$pageSize);  //ceil向上取整
  //7 响应json格式数据给前台页面进行渲染
   if($data){
   	  //成功
   	  $response =['code'=> 200,'msg'=>'获取数据成功','data'=>$data,'pageCount'=>$pageCount];
   }else{
   	   //失败
   	  $response =['code'=> -1,'msg'=>'获取数据失败','data'=>[]];
   }
 echo json_encode($response);
 ?>