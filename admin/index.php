<?php 
   include_once "../function.php";
   //检查是否有登录
   isLogin();
   // session_start();
   //用一个变量存储当前访问的页面
   $visitor = 'index';
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>

  <div class="main">
    <!-- 引入公共头部样式 navbar.php文件 -->
     <?php include_once "./navbar.php" ?> 
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group" id="count">
             
            </ul>
          </div>
        </div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
  <!-- 引入公共样式 neum-nav.php -->
  <?php include_once "./neum-nav.php"; ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/art-template/template-web.js"></script><!--引入模板引擎-->
</body>
  <script type="text/template" id="tmpl">
       <li class="list-group-item"><strong>{{res.postsCount}}</strong>篇文章（<strong>{{res.draftedCount}}</strong>篇草稿）</li>
       <li class="list-group-item"><strong>{{res.catsCount}}</strong>个分类</li>
       <li class="list-group-item"><strong>{{res.commentsCount}}</strong>条评论（<strong>{{res.heldCount}}</strong>条待审核）</li>
  </script>
  <script type="text/javascript">
      //发送之前给加载的提示....
      $('#count').html('正在加载中...'); 
      //ajax获取统计总数
      $.get('../api/getCountData.php','',function(res){
          // console.log(res);
          //调用模板引擎渲染页面
          var html = template('tmpl',{'res':res});
          $('#count').html(html);
      },'json');
  </script>
</html>
