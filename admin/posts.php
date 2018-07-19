<?php 
include_once "../function.php"  ;
//检测是否有登录
isLogin();
//session_start(); //开启session会话
//用一个变量存储当前访问的页面
$visitor = "posts";
//2.1 获取分类
 //连接数据库
 $link= connect();
 //编写sql语句
 $sql ="SELECT * FROM category";
 //执行
 $catDatas = opa($link,$sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>
       <!-- 成功信息时展示 -->
      <div class="alert alert-success" style="display: none">
        <strong>成功！</strong>
      </div>
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="cat_id" class="form-control input-sm">
             <option value="all">所有分类</option>
             <?php foreach($catDatas as $cat){ ?>
                 <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_name']; ?></option>
             <?php } ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted">草稿</option>
            <option value="published">已发布</option>
            <option value="trashed">已作废</option>
          </select>
          <span class="btn btn-default btn-sm" id="search">筛选</span>
        </form>
        <ul class="pagination pagination-sm pull-right">
         <!--  <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li> -->
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <!-- <tr>
            <td class="text-center"><input type="checkbox"></td>
            <td>随便一个名称</td>
            <td>小小</td>
            <td>潮科技</td>
            <td class="text-center">2016/10/07</td>
            <td class="text-center">已发布</td>
            <td class="text-center">
              <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
          </tr> -->
        </tbody>
      </table>
    </div>
  </div>

<!-- 引入公共侧边栏样式 neum-nav.php -->
  <?php include_once "./neum-nav.php"; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/plugins/jquery.twbsPagination.min.js"></script> 
  <script type="text/javascript" src="/static/assets/vendors/art-template/template-web.js"></script>
</body>
 <!-- 创建模板引擎 -->
 <script type="text/templater" id="temp">
     {{each data value}}
       <tr>
          <td class="text-center"><input type="checkbox" value="{{value.post_id}}"></td>
          <td>{{value.title}}</td>
          <td>{{value.nickname}}</td>
          <td>{{value.cat_name}}</td>
          <td class="text-center">{{value.created}}</td>
          <td class="text-center">
            {{if value.status == 'drafted'}}
                草稿
             {{else if value.status == 'published'}}
                已发布
             {{else}}
                已作废  
             {{/if}}    
          </td>
          <td class="text-center">
            <a href="./post-upd.php?post_id={{value.post_id}}" class="btn btn-default btn-xs editPost" edit-id="{{value.post_id}}">编辑</a>
            <a href="javascript:;" class="btn btn-danger btn-xs btnDel" del-id="{{value.post_id}}">删除</a>
          </td>
       </tr>
     {{/each}} 
 </script>
 <script type="text/javascript">
  var pageCount = 0; //定义初始的页面总数
  //ajax加载文章数据
  $.get('../api/getPostsData.php','',function(res){
      // console.log(res);
      if(res.code == 200){
         var data = res.data;
         //把分页的总数赋值给pageCount变量
         pageCount = res.pageCount;
         //调用模板引擎进行渲染页面结构
          var tbody = template('temp',{'data':data});
          $('tbody').html(tbody);
          pageList(); //绘制分页页码 
          // $('.page-link').eq(1).addClass('upPage').html('上一页');
          // $('.page-link').eq(9).addClass('dowPage').html('下一页');
      } 
  },'json');

   function pageList(){
        //把class=pagination渲染出分页页码的html结构
        //重置分页页码
        $('.pagination').empty();
        //删除此插件自带的一个值
        $('.pagination').removeData('twbs-pagination'); 
        //解绑page的事件
        $('.pagination').unbind('page'); 
        $('.pagination').twbsPagination({
            totalPages: pageCount, // 分页页码的总页数
            visiblePages: 7, // 展示的页码数
            initiateStartPageClick:false, // 取消默认初始点击
            onPageClick: function (event, page) {
                // page 当前单击的页码
                // 获取筛选条件
                var cat_id = $("select[name='cat_id']").val();
                var status = $("select[name='status']").val();
                //发送ajax请求,获取页面对应的数据
               $.get("../api/getPostsData.php",{"page":page,"cat_id":cat_id,"status":status},function(res){
                  // console.log(res);
                   var data = res.data;
                   //使用模板引擎来渲染页面
                   var tbody = template('temp',{'data':data});
                   $('tbody').html(tbody);
               },"json");
            }
       });
   }

//ajax 筛选文章数据
  $('#search').on('click',function(){
     //获取分类id和状态
      var cat_id = $("select[name='cat_id']").val();
      var status = $("select[name='status']").val();
     //发送ajax请求获得筛选的指定数据 
     $.get("../api/getPostsData.php",{'cat_id':cat_id,'status':status},function(res){
        // console.log(res);
        if(res.code == 200){
            //赋值给pageCount分页总数
            pageCount = res.pageCount;
            var data = res.data;
            console.log(data)
            //调用模板引擎渲染数据
            var tbody = template('temp',{'data':data});
           //把渲染好的数据写在tbody标签中
            $('tbody').html(tbody); //! 不是append追加,而是html覆盖
            //重置绘制筛选条件后的分页页码结构
            pageList();
        }
     },'json');
  })





 // 1. 给删除按钮注册事件,因为是动态生成的,所以用事件的委托
  $('tbody').on('click','.btnDel',function(){
         var _self = $(this); // 保存起来
         //获取当前按钮的id
         var delId = $(this).attr('del-id');
         // console.log(delId);
         //判断用户是否要删除,防止用户误删
         if(confirm('是否要删除?')){
              //发送ajax请求
            $.get("../api/postsInt.php",{'delId':delId},function(res){
               // console.log(res);
                if(res.code == 200){
                   //删除当前所在的tr行
                   _self.parents('tr').remove();
                   //提示删除成功
                   $('.alert-success').show(600).html("<strong>"+res.msg+"</strong>").hide(600);
                }else{
                   //提示删除失败
                   $('.alert-danger').show(600).html("<strong>"+res.msg+"</strong>").hide(600);
                }
            },'json');
         }  
   });
 //2. 给编辑按钮注册事件,因为是动态生成的,所以用事件的委托
  // $('tbody').on('click','.editPost',function(){
  //      //获取当前按钮的id
  //      var editId = $(this).attr('edit-id');
  //      // console.log(editId);
  // }) 
 </script>
</html>
