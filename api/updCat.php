<?php 
  include_once "../function.php";
  //1 获取传过来的参数
   $cat_id = $_GET['cat_id'];
   $cat_name = $_GET['cat_name'];
   $classname = $_GET['classname'];
   // dump($cat_id,$cat_name, $classname);
   //2 判断是否有类名重复
   $link = connect();
   $sql = "select * from category where cat_name=' $cat_name' and cat_id !=$cat_id";
   $reslut = opa($link,$sql);
   if($reslut){
   	  //说明有重名的
   	  $response = ['code' => -1,'msg' => '分类名称重复'];
   	  exit;
   }
   //编辑数据库对应的数据
  $sql2 = "update category set cat_name = ' $cat_name',classname='$classname' where cat_id =$cat_id";
  $res = mysqli_query($link,$sql2);
  //判断编辑受影响的行
  if(mysqli_affected_rows($link)){
  	 //编辑成功
  	 $response = ['code' => 200,'msg' => '修改成功'];
  }else{
  	  //编辑失败
  	 $response = ['code' => -2,'msg' => '修改失败'];
  } 
 //返回json格式数据给前端
  echo json_encode($response);
 ?>