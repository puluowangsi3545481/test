<?php 
   //1 引入数据库文件
   include_once "../function.php";
   //2 连接数据库
    $link = connect();
   //3编写sql语句
   $sql = "SELECT * FROM category ORDER BY cat_id";
   //4 获得数据
   $catsData = opa($link,$sql); 
   isLogin();
   //用一个变量存储当前访问的页面
   $visitor = 'categories';
   //session_start(); //开启session

 ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none">
        <strong>错误！</strong>
      </div>
       <!-- 有成功信息时展示 -->
      <div class="alert alert-success" style="display: none">
        <strong>成功!</strong>
      </div>
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="cat_name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">类名</label>
              <input id="slug" class="form-control" name="classname" type="text" placeholder="classname">
            </div>
            <div class="form-group">
              <span class="btn btn-primary" id="addCat" type="submit">添加</span>
              <span class="btn btn-primary" id="upd" type="submit" style="display:none">确认修改</span>
              <span class="btn btn-primary" id="cancelUpd" type="submit" style="display:none">取消修改</span>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" id="batchDelButton" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input id="batchDel" type="checkbox"></th>
                <th>名称</th>
                <th>类名</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($catsData as $cat){ ?>
                  <tr>
                    <td class="text-center"><input type="checkbox" value="<?php echo $cat['cat_id'];?>"></td>
                    <td><?php echo $cat['cat_name'];?></td>
                    <td><?php echo $cat['classname'];?></td>
                    <td class="text-center">
                      <a href="javascript:;" class="updCat btn btn-info btn-xs">编辑</a>
                      <a href="javascript:;" class="delCat btn btn-danger btn-xs">删除</a>
                    </td>
                  </tr>
            <?php  } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 引入公共样式 neum-nav.php -->
  <?php include_once "./neum-nav.php"; ?>

<script src="/static/assets/vendors/jquery/jquery.js"></script>
<script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
<script src="/static/assets/vendors/art-template/template-web.js"></script>
<!-- 定义一个tr模板 -->
<script type="text/template" id="temp">
    <tr>
      <td class="text-center"><input type="checkbox" value="{{insert_id}}"></td>
      <td>{{cat_name}}</td>
      <td>{{classname}}</td>
      <td class="text-center">
        <a href="javascript:;" class="updCat btn btn-info btn-xs">编辑</a>
        <a href="javascript:;" class="delCat btn btn-danger btn-xs">删除</a>
      </td>
    </tr>
</script>
<script type="text/javascript"></script>
<script type="text/javascript">
      //1.ajax完成分类的添加
      $('#addCat').on('click',function(){
           //1 获取到分类的名称和分类的类名
           // var cat_name = $("input[name='cat_name']").val();
           var cat_name = $("#name").val();
           var classname = $("#slug").val();
           // var classname = $("input[name='classname']").val();
           //2 验证数据
           if($.trim(cat_name)=='' || $.trim(classname)==''){
              // alert(123);
               //把错误信息展示出来
                // $(".alert-danger").show().html(" <strong>错误！</strong>分类名称或类名不能为空");
              $('.alert-danger').fadeIn(800).html("<strong>错误！</strong>分类名称或类名不能为空");
                return false; // 不发送ajax请求
           }
              //3数据验证成功,要隐藏错误信息
                $('.alert-danger').fadeOut(1000);  
              //4 发送ajax请求
               $.post("../api/addCat.php",{"cat_name":cat_name,"classname":classname},function(res){
                      // console.log(res);
                       if(res.code == 200){
                           //提示成功信息、清空输入的数据，把新增的记录追加在tbody下面
                            $('.alert-success').stop().fadeIn(800).delay(1000).fadeOut(800).html("<strong>"+res.msg+"</strong>");
                            //清空输入的数据
                            $("#name").val('');
                            $("#slug").val('');
                            //拼接tr到tbody的后面去
                             //调用模板引擎进行渲染数据
                             var tr = template("temp",{"insert_id":res.insert_id,"cat_name":cat_name,"classname":classname});
                            //在tbody后面追加一个tr
                           $('tbody').append(tr);
                           $('tr:last').hide().fadeIn(1000);           
                       }else{
                           //失败提示
                            $('.alert-danger').fadeIn(800).html("<strong>"+res.msg+"</strong>");
                            
                       }
           
               },"json");   
             });
       //2.ajax完成分类的删除(采用委托方式)
      $('tbody').on('click','.delCat',function(){
          // alert(123);
          var _self = $(this); // 保存起来
          //获取当前分类的cat_id
          var cat_id = _self.parents('tr').find('input').val();
          //判断用户是否要删除,防止误删
          if(confirm("是否要删除?")){
           //发送ajax请求进行删除
            $.get('../api/delCat.php',{'cat_id':cat_id},function(res){
                // console.log(res);
                if(res.code == 200){
                   //删除当前所在的tr行
                   _self.parents('tr').remove();
                    $('.alert-success').stop().fadeIn(800).delay(1000).fadeOut(800).html("<strong>"+res.msg+"</strong>");
                }else{
                   //提示失败信息
                      $('.alert-danger').fadeIn(800).html("<strong>"+res.msg+"</strong>");
                }
            },'json')   
          }
      })

       //3.ajax完成编辑
      var tr; //存储当前所编辑的tr行
      var cat_id; //保存当前所编辑的分类cat_id
      $('tbody').on('click','.updCat',function(){
         tr = $(this).parents('tr');
         //获取tr中的分类的cat_id
         cat_id = tr.find('input').val();
         //获取tr中的分类的cat_name
         var cat_name = tr.children('td:eq(1)').html();
         //获取tr中的分类的classname
         var classname = tr.children('td:eq(2)').html();
         //把获取到的数据回显到表单中
          $("#name").val(cat_name);
          $("#slug").val(classname);
         //让确定编辑和取消编辑的按钮显示
         $("#upd").show(); 
         $("#cancelUpd").show(); 
         $("#addCat").hide(); //添加按钮隐藏  
      }) 
      //4.取消编辑单击事件
      $("#cancelUpd").on('click',function(){
        //显示添加按钮
         $("#addCat").show();
        //编辑和取消编辑按钮都要隐藏
        $("#upd").hide(); 
        $("#cancelUpd").hide(); 
        //清空表单中的值
        $("#name").val('');
        $("#slug").val('');
      })  
      //5.发送ajax完成编辑数据的入库
      $("#upd").on('click',function(){
            //获取到分类的名称和分类的类名分类的cat_id
            var cat_name =  $("#name").val();
            var classname =  $("#slug").val();
            //发送ajax之前，需要做数据的验证
            cat_name = $.trim(cat_name);  
            classname = $.trim(classname);
            if(cat_name === '' || classname === ''){
                 //提示错误信息
                $('.alert-danger').fadeIn(500).html("<strong>分类名称或类名不能为空</strong>");
                return false; //不发送ajax请求
            } 
            //成功之后隐藏掉错误信息
            $('.alert-danger').fadeOut(1000);  
            //开始发送ajax请求
            $.get('../api/updCat.php',{'cat_id':cat_id,'cat_name':cat_name,'classname':classname},function(res){
               // console.log(res);
               if(res.code == 200){
                 //让编辑的数据回显在之前的tr的td中
                tr.children('td:eq(1)').html( cat_name);
                tr.children('td:eq(2)').html(classname);
                 //让确定和取消的编辑按钮隐藏，添加按钮显示、清空表单中的数据（和取消编辑的逻辑是一样的）
                 $("#cancelUpd").click(); //自执行单击事件（前提是已经绑定了单击事件才会触发）
                 //提示成功信息
                 $('.alert-success').fadeIn(800).delay(1000).fadeOut(800).html("<strong>"+res.msg+"</strong>");
               }else{
                 //提示失败信息
                 $('.alert-danger').fadeIn(800).delay(1000).fadeOut(800).html("<strong>"+res.msg+"</strong>");
               }
            },'json');
       })
      //6.完成全选和全不选操作
      $('#batchDel').on('click',function(){
        //获取到tbody下面的 所有复选框
        $('tbody input').prop("checked",this.checked); //这个是DOM对象的方式
        //让input的checked随#batchDelButton的状态而获得选中或不选中的状态
        $("#batchDelButton").toggle(this.checked);
      }) 
      //7.给tbody下面的input绑定单击事件
      $('tbody').on('click','input',function(){
       //获取到选中的复选框
       var checked = $("tbody input:checked"); 
       // console.log(checked);
       //判断
       checked.length > 0?$("#batchDelButton").slideDown():$("#batchDelButton").slideUp();
      }) 
      //8.ajax实现批量删除操作
      $("#batchDelButton").on('click',function(){
       //获取到选中的复选框
       var checked = $("tbody input:checked"); 
       //获取选中框中的值存储到一个数组中去 cat_id
       var cat_ids = [];
       //循环获得其中的val中保存的id
       $.each(checked,function(){
          cat_ids.push($(this).val());
       })
       cat_ids = cat_ids.join(); //把数据变成以,号为分割的字符串 如"2,3,8"
       // console.log(cat_ids);
       //开始发送ajax请求进行批量删除 
       $.get("../api/batchDel.php",{"cat_ids":cat_ids},function(res){
           // console.log(res);
            if(res.code == 200){
               //删除成功，需要移除每个tr行
               checked.parents("tr").remove();
               //提示成功信息
               $('.alert-success').fadeIn(800).delay(1000).fadeOut(800).html("<strong>"+res.msg+"</strong>");
            }else{
               //提示失败信息
                $('.alert-danger').fadeIn(800).delay(1000).fadeOut(800).html("<strong>"+res.msg+"</strong>");
            }
           //让批量删除按钮隐藏
             $("#batchDelButton").fadeOut(1000);  
       },"json");  
      })
        
</script>
</body>
</html>

