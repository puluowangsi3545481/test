<?php
  include_once "../function.php";
  //1 连接数据库
  $link = connect();
  //2编写sql语句
  //2.1 获取文章总数的sql语句
  $sql1 = "SELECT count(*) postsCount FROM posts";
  $result1 = opa($link,$sql1);
  $postsCount = $result1[0]['postsCount'];
  //2.2 获取文章草稿(drafted)总数的sql语句
  $sql2 = "SELECT count(*) draftedCount FROM posts WHERE status ='drafted'";
  $result2 = opa($link,$sql2);
  $draftedCount = $result2[0]['draftedCount'];
  //2.3 获取分类总数的sql语句
  $sql3 = "SELECT count(*) catsCount FROM category";
  $result3 = opa($link,$sql3);
  $catsCount = $result3[0]['catsCount'];
  //2.4 获取所有评论总数的sql语句
  $sql4 = "SELECT count(*) commentsCount FROM comments";
  $result4 = opa($link,$sql4);
  $commentsCount = $result4[0]['commentsCount'];
  //2.5 获取所有待审核（held）评论总数的sql语句
  $sql5 = "SELECT count(*) heldCount FROM comments WHERE status ='held'";
  $result5 = opa($link,$sql5);
  $heldCount = $result5[0]['heldCount'];
  //3 返回所有总数的json格式数据
  $response = [
      'postsCount'=>$postsCount,
      'draftedCount'=>$draftedCount,
      'catsCount'=>$catsCount,
      'commentsCount'=>$commentsCount,
      'heldCount'=>$heldCount
    ]; 
  echo json_encode($response);  

 ?>