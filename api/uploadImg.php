<?php 
  include_once "../function.php";
   // dump($_FILES);
   //1 获取图片路径和临时路径
   $name = $_FILES['file']['name'];
   $tmp_name = $_FILES['file']['tmp_name'];
   //2获取文件的后缀
   $ext = strrchr($name,'.');
   //3 拼接文件的名称
   $fileName = time().rand(10000,99999).$ext;
   // print_r($fileName);
   //4 上传文件路径
    // $uploadPath = $fileName;
   //5 开始文件上传
   if(move_uploaded_file($tmp_name,"../uploads/".$fileName)){
   	  //上传成功,返回文件的完整的路径
   	  $response = ['code' => 200, 'msg' => '文件上传成功','url' =>'../uploads/'.$fileName];
   }else{
   	   //上传失败
   	  $response = ['code' => -1, 'msg' => '文件上传失败','url' =>''];
   }  
  //6 响应json格式的数据给前台
   echo json_encode($response);

 ?>