<?php 
   include_once "../function.php";
   //1 接受传过来的参数
    $cat_name = trim($_POST['cat_name']);
    $classname = trim($_POST['classname']);
   //2 入库之前，判断分类名称是否重复
   $link = connect();
   //3 编写sql语句
    $sql = "select * from category where cat_name = '$cat_name'"; 
   //4 执行sql语句,获得数据
    $res = opa($link,$sql);
    // dump($res);
     if($res){
     	 $response = ['code' => -1,'msg' => '分类名已存在'];
     	 echo json_encode($response);
     	 exit;
     }
   //5 编写入库的sql语句
   $sql2 = "insert into category(cat_name,classname) values('$cat_name','$classname')";
   $result = mysqli_query($link,$sql2); 
   //6 判断是否添加成功
    if($result){
    	//入库成功，还需要返回给一个新增成功的记录id
    	$response = ['code' => 200,'msg' => '添加分类成功','insert_id' => mysqli_insert_id($link)];
    	
    }else{
    	$response = ['code' => -2,'msg' => '添加分类失败'];
    }
   //7 输入数据给前端页面使用
    echo json_encode($response);




 ?>