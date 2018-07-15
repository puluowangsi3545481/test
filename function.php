<?php 
  //封装连接数据库的函数,来达到复用
  function connect(){
  	//连接数据库
        $link = mysqli_connect('127.0.0.1','root','root','bx');
        return $link;
  }
  function opa($link,$sql){
       //封装执行select的查询语句
       $res = mysqli_query($link,$sql);
       //获取数据
       $data = [];
       while($row = mysqli_fetch_assoc($res)){
           $data[] = $row; 
      } 
      //返回构造好的数据
	   return $data;
  }
  //调试用的
  function dump($data){
     echo "<pre/>";
     var_dump($data);
     exit;
  }
  //判断用户是否登录
  function isLogin(){
     session_start(); //开启session
     if(!isset($_SESSION['user_id'])|| trim($_SESSION['user_id']) === ''){
        //说明没有session,直接跳转到login.php页面
        echo '请先登录...';
        header("refresh:2;url='login.php'");
        exit;
     } 
  }

 ?>