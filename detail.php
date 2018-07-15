<?php 
  //引入数据库封装函数
   require_once __DIR__."/function.php";
  //1 获取参数
   $post_id = isset($_GET['post_id'])?(int)$_GET['post_id']:0;
   //2 连接数据库
   $link = connect();
   //3 写sql语句,查询数据
   $sql = "SELECT t1.*,t2.cat_name,t3.nickname,
   (SELECT count(*) FROM comments WHERE post_id = t1.post_id) as commentsCount
    FROM posts t1
   LEFT JOIN category t2 on t1.cat_id = t2.cat_id
   LEFT JOIN users t3 on t1.user_id = t3.user_id
   WHERE t1.post_id = $post_id"; 
   $postDetail = opa($link,$sql);
   $infoData = $postDetail[0];
   error_reporting(0); //消除一些非语法错误,如未定义的变量
   // print_r($infoData);die;

 
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
  <!-- 引入公共的 php文件 -->
   <?php include_once __DIR__."/aside.php"; ?>
    <div class="content">
      <div class="article">
        <div class="breadcrumb">
          <dl>
            <dt>当前位置：</dt>
            <dd><a href="javascript:;"><?php echo $infoData['cat_name']; ?></a></dd>
            <dd><?php echo $infoData['title']; ?></dd>
          </dl>
        </div>
        <h2 class="title">
          <a href="javascript:;"><?php echo $infoData['title']; ?></a>
        </h2>
        <div class="meta">
          <span><?php echo $infoData['nickname']; ?> 发布于 <?php echo $infoData['created']; ?></span>
          <div class="content-detail"><?php echo $infoData['content']; ?></div>
          
          <span>分类: <a href="javascript:;"><?php echo $infoData['cat_name']; ?></a></span>
          <span>阅读: (<?php echo $infoData['views']; ?>)</span>
          <span>评论: (<?php echo $infoData['commentsCount']; ?>)</span>
		     <a href="javascript:;"  class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $infoData['likes']; ?>)</span>
          </a>
        </div>
      </div>
      <!-- 1112222 -->
      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_2.jpg" alt="">
              <span>星球大战:原力觉醒视频演示 电影票68</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_3.jpg" alt="">
              <span>你敢骑吗？全球第一辆全功能3D打印摩托车亮相</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_4.jpg" alt="">
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_5.jpg" alt="">
              <span>实在太邪恶！照亮妹纸绝对领域与私处</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
</body>
</html>
<script type="text/javascript" src="/static/assets/vendors/jquery/jquery.min.js"></script>
<script type="text/javascript">
   $(function(){
      $('.like').on('click',function(){
         //用一个变量存储当前的对象，为了在内部函数可以引用
         var _self = $(this);
         //获取到文章的post_id
         var post_id = "<?php echo $_GET['post_id']; ?>";
         // console.log(post_id);
         $.post("./api/clickLikes.php",{"post_id":post_id},function(res){
               // console.log(res);
               if(res.code == 200){
                  //把新的点赞数量，直接赋值给对应的位置
                  _self.children('span').html("赞("+res.newLikes+")");
               }
         },"json");
      })
   })
</script>