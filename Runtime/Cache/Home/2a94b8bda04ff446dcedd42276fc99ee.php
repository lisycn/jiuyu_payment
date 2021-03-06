<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title><?php echo ($sitename); ?> - 登录</title>
    <link href="<?php echo ($siteurl); ?>Public/Front/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ($siteurl); ?>Public/Front/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link rel="stylesheet" src="<?php echo ($siteurl); ?>Public/Front/bootstrapvalidator/css/bootstrapValidator.min.css">
    <link href="<?php echo ($siteurl); ?>Public/Front/css/animate.css" rel="stylesheet">
 
    <link rel="stylesheet" href="<?php echo ($siteurl); ?>Public/Front/login/css/style.css">
    <script src="<?php echo ($siteurl); ?>Public/Front/login/js/modernizr-2.6.2.min.js"></script>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>
</head>
<body class="style-3">

        <div class="container">
            <div class="row col-hidden-xs">
                <div class="mt-50"></div>
            </div>
            <div class="row">
                <div class="col-md-4 col-md-push-8">
                    

                    <!-- Start Sign In Form -->
                    <form class="fh5co-form animate-box form-horizontal" data-animate-effect="fadeInRight" id="formlogin" method="post" role="form" action="<?php echo U("login");?>">
                       <div class="form-group"><h2>账号登录</h2>  </div>
                        <div class="form-group">
                            <label for="username" class="sr-only">用户名</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="用户名" required="" minlength="2" aria-required="true" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">密码</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="密码" required="" aria-required="true" placeholder="Password" autocomplete="off">
                        </div>
                         <div class="form-group">
                             <span class="col-sm-4 mp-nm"><input type="text" class="form-control" id="verification" name="varification"  placeholder="验证码" required=""  aria-required="true" placeholder="Password" autocomplete="off" ajaxurl="<?php echo U("checkverify");?>"></span>
                            <label for="userverification" class="userverification col-sm-8"><img class="verifyimg" alt="点击刷新验证码" src="<?php echo U('verifycode');?>" style="cursor:pointer;" onclick='javascript:$(".verifyimg").attr("src","<?php echo U('verifycode');?>?a="+(Math.random()*100))' title="点击刷新验证码"></label>
                        
                        </div>

                        <div class="form-group">
                            <label for="remember"><input type="checkbox" id="remember"> 记住我</label>
                        </div>
                        <div class="form-group">
                            <p>还不是会员? 点击 <a href="<?php echo U("reg");?>">注册账号</a> | <a href="javascript:alert('请联系客服')">忘记密码?</a></p>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary">点击登录</button>
                        </div>
                    </form>
                    <!-- END Sign In Form -->


                </div>
            </div>
            <div class="row" style="padding-top: 60px; clear: both;">
                <div class="col-md-12 text-center"><p><small>&copy; <?php echo ($sitename); ?> All Rights Reserved.  </small></p></div>
            </div>
        </div>

    <!-- Main JS -->
<script src="<?php echo ($siteurl); ?>Public/Front/js/jquery.min.js"></script>
<script src="<?php echo ($siteurl); ?>Public/Front/js/bootstrap.min.js"></script>
<script src="<?php echo ($siteurl); ?>Public/Front/bootstrapvalidator/js/bootstrapValidator.min.js"></script>
<script src="<?php echo ($siteurl); ?>Public/Front/js/plugins/layer/layer.min.js"></script>
<script src="<?php echo ($siteurl); ?>Public/Front/js/login.js" type="text/javascript"></script>
<?php echo tongji(0);?>
</body>
</html>