<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none">
        <strong>错误！</strong> 用户名或密码错误！
      </div>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" class="form-control" placeholder="密码">
      </div>
      <a class="btn btn-primary btn-block" href="javascript:;" id="userLogin">登 录</a>
    </form>
  </div>
</body>
<script type="text/javascript" src="/static/assets/vendors/jquery/jquery.min.js"></script>
<script>
    //ajax实现登录
     $("#userLogin").on('click',function(){
       //获取邮箱和密码的值
       var email = $('#email').val(); 
       var password = $('#password').val(); 
       //进行正则匹配
       var reg = /^\w+[@](?:[0-9a-zA-Z]+\.)+[a-zA-Z]{2,6}$/;
       var pwd = /^(?![a-zA-z]+$)(?!\d+$)(?![!@#$%^&*]+$)[a-zA-Z\d!@#$%^&*]+$/;
       if(email === '' || password === ''){
         //提示错误信息
         // alert(123);
        $('.alert-danger').fadeIn(600).html("<strong>邮箱或密码不能为空</strong>");
        return false;
       }
       if(reg.test(email) == false){
           //邮箱正则不满足，提示错误信息
          $('.alert-danger').fadeIn(800).html("<strong>请填入正确的邮箱格式</strong>");
          return false;
       }
       if(pwd.test(password)== false){
          //密码正则不满足，提示错误信息
          $('.alert-danger').fadeIn(800).html("<strong>密码需要字母+数字</strong>");
          return false;
       }
       //清除错误信息
       $('.alert-danger').fadeOut(800);
       //发送ajax请求，匹配邮箱和密码是否匹配
       $.post("../api/checkUser.php",{'email':email,'password':password},function(res){
          // console.log(res);
           if(res.code == 200){
              //登录成功,跳转到后台首页
              location.href = "./index.php";
           }else{
               //登录失败，提示错误的信息
              $('.alert-danger').fadeIn(800).delay(2000).fadeOut(800).html("<strong>"+res.msg+"</strong>");
           }
       },'json');
       
     })
</script>
</html>
