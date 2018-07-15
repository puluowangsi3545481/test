<?php 
  // echo __DIR__;exit;
  // print_r($_GET);exit;
  //0  调用封装函数的PHP文件
   include_once  __DIR__.'/function.php';  
  //1 接受参数cat_id -----把字符串转化整形 0 +0 = 0 
   $cat_id = isset($_GET['cat_id'])?(int)$_GET['cat_id']:0; //高版本的用+0会报错,可以用类型转换来实现,(int)可以强制转换为数字类型
   // echo $cat_id;die;
  // echo 11111;
  // var_dump($cat_id); die;
  //2 连接数据库
  $link = connect();
  // print_r($link);
  //3 设置sql语句
  $sql = "SELECT t1.*,t2.cat_name,t3.nickname,(select count(*) from comments where post_id = t1.post_id) commentCount  from 
posts t1 
left join category t2 on t1.cat_id = t2.cat_id 
left join users t3 on t1.user_id = t3.user_id
where t1.cat_id = $cat_id
order by t1.post_id desc
limit 3";
//4 执行sql语句,获得数据
$postsData = opa($link,$sql);
// print_r($postsData);die;  
 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  <link rel="stylesheet" href="/static/assets/css/style.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
</head>
<body>
  <div class="wrapper">
    <div class="topnav">
      <ul>
        <li><a href="javascript:;"><i class="fa fa-glass"></i>奇趣事</a></li>
        <li><a href="javascript:;"><i class="fa fa-phone"></i>潮科技</a></li>
        <li><a href="javascript:;"><i class="fa fa-fire"></i>会生活</a></li>
        <li><a href="javascript:;"><i class="fa fa-gift"></i>美奇迹</a></li>
      </ul>
    </div>
     <!-- 引入公共的 aside.php文件 -->
   <!-- __DIR__ === E:\baixiu -->
   <?php include_once __DIR__.'/aside.php'; ?>
    <div class="content">
      <div class="panel new">
        <!-- 获取出下标为0的元素，取出下标cat_name的值 -->
        <h3><?php echo isset($postsData[0]['cat_name'])?$postsData[0]['cat_name']:''; ?></h3>
          <?php  foreach ($postsData as $post) {  ?>

        <div class="entry">
          <div class="head">
            <a href="/detail.php?post_id=<?php echo $post['post_id']; ?>"><?php echo $post['title']; ?></a>
          </div>
          <div class="main">
            <p class="info"><?php echo $post['nickname']; ?> 发表于 <?php echo $post['created']; ?></p>
            <p class="brief"><?php echo $post['content']; ?></p>
            <p class="extra">
              <span class="reading">阅读(<?php echo $post['views']; ?>)</span>
              <span class="comment">评论(<?php echo $post['commentCount']; ?>)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $post['likes']; ?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><?php echo $post['cat_name']; ?></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?php echo $post['feature']; ?>" alt="">
            </a>
          </div>
        </div> 
        <?php $lastPostId = $post['post_id']; ?>        
        <?php } ?>
        <!-- 点击加载更多功能 -->
        <div class="loadmore">
          <button class="btn">加载更多</button>
        </div>
      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
<script type="text/javascript" src="/static/assets/vendors/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/static/assets/vendors/art-template/template-web.js"></script>
<!-- 定义一个文章的html模板引擎 -->
<script type="text/template" id="temp">
  {{each data as value}}
    <div class="entry">
      <div class="head">
          <a href="/detail.php?post_id={{value.post_id}}">{{value.title}}</a>
      </div>
      <div class="main">
          <p class="info">{{value.nickname}} 发表于 {{value.created}}</p>
          <p class="brief">{{value.content}}</p>
          <p class="extra">
            <span class="reading">阅读({{value.views}})</span>
            <span class="comment">评论({{value.commentCount}})</span>
            <a href="javascript:;" class="like">
              <i class="fa fa-thumbs-up"></i>
              <span>赞({{value.likes}})</span>
            </a>
            <a href="javascript:;" class="tags">
              分类：<span>{{value.cat_name}}</span>
            </a>
          </p>
          <a href="javascript:;" class="thumb">
            <img src="{{value.feature}}" alt="">
          </a>
      </div>
    </div>
  {{/each}}  
</script>
<script type="text/javascript">
     $(function(){
         //获得最后的一篇文章的id
         var lastPostId = "<?php echo $lastPostId; ?>";
        $('.loadmore').on('click',function(){
            //获得当前分类的cat_id
            var cat_id = "<?php echo $_GET['cat_id']; ?>";
            // alert(lastPostId);
            //用变量保存当前this
             var _self = $(this);
             // console.log(_self);
            //防止用户频繁的点击，可以让按钮禁用，且给加载的提示 
             _self.children('button').prop('disabled',true).html('正在加载...');
            //ajax的调用
            $.get('./api/loadMorePost.php',{'cat_id':cat_id,'lastPostId':lastPostId},function(res){
                 // console.log(res);
                 //判断是否有数据
                 if(res.code == 200){
                     var data = res.data;
                     //动态构建结构
                     var html = template("temp",{"data":data});
                     //获取数组的最后一个元素索引
                      var lastIndex = data.length -1;
                     // 通过索引取出post_id 
                      // lastPostId = res.data[lastIndex].post_id;
                      lastPostId = data[lastIndex].post_id;
                       // console.log(lastPostId);  
                      //把html动态追加到加载更多按钮前面即可
                      $('.loadmore').before(html);
                 }
                //恢复按钮,让它可以重新被点击
                _self.children('button').prop('disabled',false).html('加载更多');

            },'json');
        })
     });
</script>


</body>
</html>
