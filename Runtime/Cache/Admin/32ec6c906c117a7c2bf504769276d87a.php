<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title><?php echo ($sitename); ?> 后台管理系统 - 您需要登录后才可以使用本功能</title>
    <link href="/Public/Front/newadmin/css/login.css" rel="stylesheet" type="text/css">
    <link href="/Public/Front/css/font-awesome.min.css" rel="stylesheet">
    <script src="/Public/js/jquery.js" type="text/javascript"></script>
    <script src="/Public/Front/newadmin/resource/js/common.js" type="text/javascript"></script>
    <script src="/Public/Front/newadmin/resource/js/jquery.validation.min.js"></script>
    <script src="/Public/Front/newadmin/js/jquery.supersized.min.js" ></script>
    <script src="/Public/Front/newadmin/js/jquery.progressBar.js" type="text/javascript"></script>
    <style>
        .submit{
            position: relative;
        }
    </style>
    <script>
        function getOs()
        {
            var OsObject = "";
            if(navigator.userAgent.indexOf("MSIE")>0) {
                return "MSIE";
            }
            else if(isFirefox=navigator.userAgent.indexOf("Firefox")>0){
                return "Firefox";
            }
            else if(isMozilla=navigator.userAgent.indexOf("Opera")>0){ //这个也被判断为chrome
                return "Opera";
            }
            else if(isFirefox=navigator.userAgent.indexOf("Chrome")>0){
                return "Chrome";
            }
            else if(isSafari=navigator.userAgent.indexOf("Safari")>0) {
                return "Safari";
            }
            else if(isCamino=navigator.userAgent.indexOf("Camino")>0){
                return "Camino";
            }
            else if(isMozilla=navigator.userAgent.indexOf("Gecko/")>0){
                return "Gecko";
            }

        }


    </script>
</head>

<body>
<!--主体-->

<div class="login-layout">
    <div class="top">
        <h5>【<?php echo ($sitename); ?>】 管理平台<em></em></h5>
        <h2>系统管理中心</h2>
    </div>
    <form method="post" class="layui-form" id="form_login" action="<?php echo U('Admin/Login/checkLogin');?>">
        <div class="lock-holder">
            <div class="form-group pull-left input-username">
                <label>账号</label>
                <input name="username" id="user_name" autocomplete="off" type="text" class="input-text" lay-verify="required" value="" required>
            </div>
            <i class="fa fa-ellipsis-h dot-left"></i> <i class="fa fa-ellipsis-h dot-right"></i>
            <div class="form-group pull-right input-password-box">
                <label>密码</label>
                <input name="password" id="password" class="input-text" autocomplete="off" type="password" required lay-verify="required" pattern="[\S]{6}[\S]*" title="密码不少于6个字符">
            </div>
        </div>
        <div class="avatar"><img src="/Public\Front\newadmin\images\login/admin.png" alt=""></div>
        <div class="submit">
            <span>
            <div class="code">
                <div class="arrow"></div>
                <div class="code-img">
                    <img  src="<?php echo U('Admin/Login/verifycode');?>?t=<?php echo time();?>" alt="点击更换" title="点击更换" onclick="changeCode()" class="captcha" name="codeimage" id="codeimage" border="0"/>
                </div>
                <a href="JavaScript:void(0);" id="hide" class="close" title="关闭"><i></i></a><a href="JavaScript:void(0);"  class="change" id="changeCode" onclick="changeCode()" title="看不清,点击更换验证码"><i></i></a>
            </div>
            <input name="verify" required lay-verify="required" type="text" class="input-code" id="captcha" placeholder="输入验证" pattern="[A-z0-9]{4}" title="验证码为4个字符" autocomplete="off" value="" >
            </span>
            <span>
              <input name="" class="input-button btn-submit" type="button" value="登录">
            </span>
        </div>
        <div class="submit2"></div>
    </form>
    <div class="bottom">
    </div>
</div>


<script>
    // 定义全局JS变量
    var GV = {
        current_controller: "admin//",
        base_url: "/Public/Front"
    };
</script>

    <script src="/Public/Front/js/plugins/layui/layui.js"></script>

<!--页面JS脚本-->

    <script>
        function changeCode() {

            $("#codeimage").attr("src",'<?php echo U("Admin/Login/verifycode");?>?t='+parseInt(40*Math.random()));
        };
	layui.use(['laydate', 'layer', 'element'], function(){
  var laydate = layui.laydate //日期
  ,layer = layui.layer //弹层
  ,element = layui.element; //元素操作
  });
        $(function(){
            $.supersized({
                // 功能
                slide_interval     : 4000,
                transition         : 1,
                transition_speed   : 1000,
                performance        : 1,
                // 大小和位置
                min_width          : 0,
                min_height         : 0,
                vertical_center    : 1,
                horizontal_center  : 1,
                fit_always         : 0,
                fit_portrait       : 1,
                fit_landscape      : 0,
                // 组件
                slide_links        : 'blank',
                slides             : [
                    {image : '/Public/Front/newadmin/images/login/1.jpg'},
                    {image : '/Public/Front/newadmin/images/login/2.jpg'},
                    {image : '/Public/Front/newadmin/images/login/3.jpg'},
                    {image : '/Public/Front/newadmin/images/login/4.jpg'},
                    {image : '/Public/Front/newadmin/images/login/5.jpg'}
                ]
            });
            //显示隐藏验证码
            $("#hide").click(function(){
                $(".code").fadeOut("slow");
            });
            $("#captcha").focus(function(){

                $(".code").fadeIn("fast");
                if(getOs()=="Firefox"){
                    $(".code").css("top","-85px");
                }
            });
            //跳出框架在主窗口登录
            if(top.location!=this.location)	top.location=this.location;
            $('#user_name').focus();
            $("#captcha").nc_placeholder();
            //动画登录
            $('.btn-submit').click(function(data){
                $('.input-username,dot-left').addClass('animated fadeOutRight');
                $('.input-password-box,dot-right').addClass('animated fadeOutLeft');
                $('.btn-submit').addClass('animated fadeOutUp');
                setTimeout(function () {
                        $('.avatar').addClass('avatar-top');
                        $('.submit').hide();
                        $('.submit2').html('<div class="progress"><div class="progress-bar progress-bar-success" aria-valuetransitiongoal="100"></div></div>');
                        $('.progress .progress-bar').progressbar({
                            done : function() {
                                $.ajax({
                                    url: $("#form_login").attr("action"),
                                    type: $("#form_login").attr("method"),
                                    data:$("#form_login").serialize(),
                                    success: function (info) {
										if(!info.status){
											setTimeout(function () {
												location.href = info.url;
											}, 1000);
											layer.msg(info.msg);
										}else{
											layer.msg(info.msg,{icon:5});
											return false;
										}
                                    }
                                });
                            }
                        });
                    },
                    300);

            });
            // 回车提交表单
            $('#form_login').keydown(function(event){
                if (event.keyCode == 13) {
                    $('.btn-submit').click();
                }
            });
            // 定义全局JS变量
            var GV = {
                current_controller: "admin//"
            };
        });
    </script>

<script>
    //固定层移动
    $(function(){
        //管理显示与隐藏
        $("#admin-manager-btn").click(function () {
            if ($(".manager-menu").css("display") == "none") {
                $(".manager-menu").css('display', 'block');
                $("#admin-manager-btn").attr("title","关闭快捷管理");
                $("#admin-manager-btn").removeClass().addClass("arrow-close");
            }
            else {
                $(".manager-menu").css('display', 'none');
                $("#admin-manager-btn").attr("title","显示快捷管理");
                $("#admin-manager-btn").removeClass().addClass("arrow");
            }
        });
    });
</script>
</body>
</html>