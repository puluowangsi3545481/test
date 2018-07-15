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
  <title>Password reset &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>

  <div class="main">
    <!-- 引入公共头部样式 navbar.php文件 -->
     <?php include_once "./navbar.php" ?> 
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>
       <!-- 成功信息时展示 -->
      <div class="alert alert-success" style="display: none">
        <strong>笑出声</strong>
      </div>

      <form class="form-horizontal">
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" type="password" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" type="password" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" type="password" placeholder="确认新密码">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <span type="submit" class="btn btn-primary" id="updPass">修改密码</span>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- 引入公共侧边栏样式 neum-nav.php -->
  <?php include_once "./neum-nav.php"; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
</body>
</html>
<script type="text/javascript">
   //ajax发送请求修改密码
   $('#updPass').on('click',function(){
      //获取旧密码和新密码和确认新密码
      var old = $.trim($('#old').val());
      var password = $.trim($('#password').val());
      var confirm = $.trim($('#confirm').val());
      //1判断数据,不能为空
      if(old === ''){
         $('.alert-danger').show().html("<strong>旧密码能不为空!</strong>");
         return false;
      } 
      if(password === ''){
         $('.alert-danger').show().html("<strong>新密码不能为空!</strong>");
          return false;
      } 
      if(confirm === ''){
         $('.alert-danger').show().html("<strong>确认密码不能为空!</strong>");
          return false;
      } 
     //2 必须是字母开头,密码长度最少6位
     var reg = /^[a-zA-Z]\w{5,}$/;
     if(reg.test(password) == false){
       $('.alert-danger').show().html("<strong>必须是字母开头,密码长度最少6位</strong>");
          return false;
     }
     //3 判断2次密码是否一致
      if(password != confirm){
        $('.alert-danger').show().html("<strong>两次密码不一致</strong>");
          return false;
      }
     //4成功验证之后隐藏错误信息
      $('.alert-danger').fadeOut(800);
     //5 发送ajax请求修改密码
     var param = {'old':old,'password':password,'confirm':confirm}; //先用变量存储起来
      $.post("../api/updPass.php",param,function(res){
         // console.log(res);
         // 判断
         if(res.code == 200){
            //成功
            $('.alert-success').stop().show().html("<strong>"+res.msg+"</strong>").delay(2000).slideUp(800);
         }else{
            //失败
            $('.alert-danger').stop().show().html("<strong>"+res.msg+"</strong>").delay(2000).slideUp(800);
         }
      },"json");
      
   })
</script>
