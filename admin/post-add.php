<?php 
include_once "../function.php"  ;
//检测是否有登录
isLogin();
//session_start(); //开启session会话
//用一个变量存储当前访问的页面
$visitor = 'getBooks';
//1 连接数据库
$link =  connect();
//2编写sql语句
$sql ="SELECT * FROM category";
//3执行sql
$catDatas = opa($link,$sql);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <!--下面的是引入富文本编辑器的css样式-->
  <link rel="stylesheet" type="text/css" href="/static/plugins/layedit/src/css/layui.css"> 
  
</head>
<body>
  <div class="main">
   <!-- 引入公共头部样式 navbar.php文件 -->
     <?php include_once "./navbar.php" ?> 
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" id="form">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content"  name="content" cols="30" rows="10" placeholder="内容"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php foreach($catDatas as $cat): ?>
              <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_name']; ?></option>
            <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="text">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="button" id="addPost">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

 <!-- 引入公共侧边栏样式 neum-nav.php -->
  <?php include_once "./neum-nav.php"; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/plugins/laydate/laydate.js"></script> <!--引入日期插件-->
  <script src="/static/plugins/layedit/src/layui.js"></script><!--引入富文本编辑器-->
  <script type="text/javascript" charset="utf-8" src="/static/plugins/layer/layer.js"></script>

</body>
<script>
  //1 ajax上传文件，给文件域绑定change事件
  var url =''; //1.1先定义一个变量用于存储上传文件的路径
  $('#feature').change(function(){
      var file = this.files[0];
      // console.log(file);
      if(!file){
         return false; //说明没有文件上传,让代码停止向下运行
      }
     //1.3创建一个表单对象,追加数据
     var formData = new FormData();
     formData.append('file',file);
     //1.4发送ajax请求
     $.ajax({
        "type":"post",
        "url":"../api/uploadImg.php",
        "data":formData,
        "contentType": false,
        "processData": false,
        "dataType": "json",
        "success":function(res){
           // console.log(res);
            if(res.code == 200){
              //设置图片预览效果
              url = res.url;
             $('.help-block').show().attr('src',res.url); 
           }
        }
     }); 
  });

  //2引入日期插件,对id=created进行时间的初始化
  //执行一个laydate实例
   laydate.render({
    elem: '#created', //指定元素
    type:'datetime' //指定日期的类型
  });
   //3 引入富文本编辑器
     layui.use('layedit', function(){
    var layedit = layui.layedit;
    var index = layedit.build('content', {
      //hideTool: ['image']
      uploadImage: {
        url: '/layui/upload.php'
        ,type: 'post'
      }
        ,tool: [
          //数组留空显示所有
          'strong' //加粗
          ,'italic' //斜体
          ,'underline' //下划线
          ,'del' //删除线
         ,'|' //分割线
         ,'left' //左对齐
          ,'center' //居中对齐
        ,'right' //右对齐
         ,'link' //超链接
        ,'unlink' //清除链接
        ,'face' //表情
        //,'image' //插入图片
        //,'help' //帮助
          ]   
        //,height: 100
     });
  });

 var downIndex; //定义一个变量用于弹层的关闭    
 //3. ajax实现文章的添加
  $('#addPost').on('click',function(){
       var param = $('#form').serialize();//表单元素必须还有name属性
       param += "&url="+url; //拼接图片的路径
       console.log(param);
       downIndex = layer.msg('入库中...',{
            shade:[0.6,'#ccc'],
            shadeClose:false
       })
      //发送ajax请求
      $.get("../api/addPostData.php",param,function(res){
          layer.close(downIndex);
          if(res.code == 200){
             layer.msg(res.msg);
              //跳转到文章列表页
             location.href = "./posts.php"; 
          }else{
              layer.msg(res.msg);
          }
      },'json'); 
  })
  

</script>
</html>
