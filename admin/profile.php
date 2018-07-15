<?php 
include_once "../function.php"  ;
//检测是否有登录
isLogin();
session_start(); //开启session会话
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="..//static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="..//static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="..//static/assets/css/admin.css">
</head>
<body>

  <div class="main">
    <!-- 引入公共头部样式 navbar.php文件 -->
     <?php include_once "./navbar.php" ?> 
    <div class="container-fluid">
      <div class="page-title">
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <!-- 有成功信息时展示 -->
      <div class="alert alert-success" style="display: none">
        <strong>成功</strong>
      </div>
      <form class="form-horizontal">
        <div class="form-group">
          <!-- 定义一个隐藏域 -->
          <input type="hidden" id="avatar_path">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img src="/static/assets/img/default.png" id ="imgSrc">
              <i class="mask fa fa-upload"></i>
            </label>

          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="w@zce.me" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="汪磊" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" placeholder="Bio" cols="30" rows="6">MAKE IT BETTER!</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <span type="submit" class="btn btn-primary" id="updUser">更新</span>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>

<!-- 引入公共侧边栏样式 neum-nav.php -->
  <?php include_once "./neum-nav.php"; ?>

  <script src="..//static/assets/vendors/jquery/jquery.js"></script>
  <script src="..//static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script type="text/javascript">
    //1给id为avatar(文件上传)，绑定change事件
    $('#avatar').on('change',function(){
        //需要获取到文件的上传信息
        var file = this.files[0];
        // console.log(file);
        //html5的一个特性，利用formData表单对象，可以用来传递二进制数据（文件流）和字符串数据
        var formdata = new FormData();
        formdata.append("file",file);
        if(file){
           //有文件上传，发送ajax请求，通过php帮助我们处理上传文件
           $.ajax({
             "url":"../api/uploadImg.php",
             "type":"post", //上传文件只能是post传递
             "data": formdata,
             "contentType": false, //上传文件不可以指定数据类型
             "processData": false, //对数据不进行数据的序列化
             "dataType":"json",
             "success": function(res){
                // console.log(res);
                 if(res.code == 200){
                    //把图片的路径设置img的src属性中用于预览
                    $('#imgSrc').attr('src',res.url);
                    //把上传成功的图片路径赋值给隐藏域,用于带到后台去
                    $("#avatar_path").val(res.url);
                 }
             } 
           });
        }
    })
    //2 ajax更新用户的信息
    $("#updUser").on('click',function(){
        //获得数据
        var nickname = $.trim($('#nickname').val());
        var bio = $('#bio').val();
        var avatar = $('#avatar_path').val();
        //验证数据
        if(nickname === ''){
          //提示错误信息
            $('.alert-danger').show().html("<strong>昵称不能为空！</strong>");
            return false;
        }
        //隐藏错误信息
        $('.alert-danger').slideUp(800);
       //发送ajax请求,在后台进行数据的更新
       var param = {"nickname":nickname,"bio":bio,"avatar":avatar};
       $.post("../api/updUser.php",param,function(res){
          // console.log(res);
           if(res.code == 200){
             //更新成功
             $('.alert-success').slideDown(800).html("<strong>"+res.msg+"</strong>").fadeOut(2000);
              //渲染成功,跳转到后台首页
             // location.href = "./index.php";

           }else{
              //更新失败
             $('.alert-danger').slideDown(800).html("<strong>"+res.msg+"</strong>").fadeOut(2000);
           }
       },"json");  
    }) 
    //3 页面加载完毕获取当前用户的个人信息展示在表单中
    $.get('../api/getUser.php','',function(res){
       console.log(res);
       //给表单赋值
       $('#imgSrc').attr('src',res.data.avatar);
       $('#nickname').val(res.data.nickname);
       $('#bio').val(res.data.bio);
       $('#email').val(res.data.email);
    },'json');

  </script>
</body>
</html>
