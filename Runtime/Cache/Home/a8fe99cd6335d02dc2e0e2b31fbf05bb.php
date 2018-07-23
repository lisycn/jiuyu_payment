<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>

    <meta charset="UTF-8">
    <title>注册 - <?php echo ($sitename); ?></title>
    <meta content="width=device-width,initial-scale=1.0" name="viewport"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="telephone=no" name="format-detection"/>
    <meta content="address=no" name="format-detection"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="<?php echo ($style); ?>css/common.css">
    <link rel="stylesheet" type="text/css" href="<?php echo ($style); ?>css/index.css">

    <link rel="stylesheet" type="text/css" href="<?php echo ($style); ?>css/header.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo ($style); ?>css/footer.css"/>
    <script type="text/javascript" src="<?php echo ($style); ?>js/jquery.js"></script>
  
    <!-- <link rel="stylesheet" type="text/css" href="<?php echo ($style); ?>css/test_index_requireFromJS.css"/> -->
    <!-- <link rel="stylesheet" media="screen" title="no title" href="<?php echo ($style); ?>css/ysbformvalidate.css"/> -->

     <script type="text/javascript" src="<?php echo ($style); ?>js/layer/layer.min.js"></script>
    <!-- <script type="text/javascript" src="<?php echo ($style); ?>js/CFCASIPInput.min.js"></script> -->
    
    <script type="text/javascript">




     

    </script>
    <script type="text/javascript">

 
           function submitForm(loginUrl,formId){
				

                $.ajax({
                    url:loginUrl,
                    type:'post',
                    data:$('#'+formId).serialize(),
                    dataType:'json',
                    success:function(result){
                        if(result['errorno'] != 0){
                           layer.msg(result['msg']); 
                        }else{
                            if(result['need_activate'] == 1) {
                                var data = result.msg;
                                layer.open({
                                    type: 1
                                    ,title: false
                                    ,closeBtn: false
                                    ,area: '300px;'
                                    ,shade: 0.8
                                    ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                                    ,resize: false

                                    ,btnAlign: 'c'
                                    ,moveType: 1 //拖拽模式，0或者1
                                    ,content: '<div style="padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">' +
                                    '<h3>恭喜您，注册成功！</h3>' +
                                    '<p>我们已发送了一封验证邮件到 <strong>'+data.email+'</strong>, 请注意查收您的邮箱，点击其中的链接激活您的账号！</p>' +
                                    '<p><a href="' + data.mail + '" _blank style="color: #f7f7f7;display: block; background-color: #00a0e8; width: 70px;height: 30px;text-align: center;padding-top: 4px;">立刻激活</a></p>'

                                    +'</div>',
                                });
                            } else {
                                layer.msg('注册成功！正在为您跳转到登录页面...');

                                setTimeout(function() {
                                    window.location.href = "<?php echo ($siteurl); ?>" + "user_Login_index.html";
                                },3000 );
                            }

                        }
                    }
                });
				return false;
           }


    </script>
</head>

<body>
<!-- <script src="header.js" charset="utf-8"></script> -->


<header class="header" style="border-bottom: 1px solid #e4e4e4;">
    <div class="special-header clearfix">
        <a href="" class="alipay-logo"><img src="<?php echo ($logo); ?>"/>  </a>
        <ul class="menu-list fn-right nav navbar-nav">
            <li>
                <a class="index" href="index.html" title="首页">首页</a>
            </li>
            <li>
                <a class="transfer" href="<?php echo ($demo); ?>">SDK下载</a>
            </li>
            <li>
                <a class="transfer" href="<?php echo ($faq); ?>">常见问题</a>
            </li>
            <li>
                <a class="lifeservice" href="<?php echo ($safe); ?>">安全无忧</a>
            </li>
            <li>
                <a class="transfer" href="<?php echo ($contact); ?>">联系我们</a>
            </li>
            <li style="margin-left:-3px;"></li>
            <li class="enterprise" style="margin-left:-5px;">
                <a href="<?php echo ($user_login); ?>">登录</a><span class="fenge">|</span> <a href="<?php echo ($agent_login); ?>">代理登录</a> <span class="fenge">|</span> <a href="<?php echo ($register); ?>">注册</a>
            </li>
        </ul>
    </div>
</header>

<section class="login">
    <div class="login_box centre clearfix">
        <div class="login_left" style="width: 795px;height: 491px;">
            <img src="<?php echo ($style); ?>image/A1tu.png" alt=""/>
        </div>
        <div class="login_right">
            <h1>商户注册中心</h1>
            <form onsubmit="return false" name="frmLogon"  id="defaultForm" method="post"
                  class="form-horizontal ysbformvalidate" style="padding: 25px 37px">
     
                <div class="form-group has-feedback">
                    <div class="" style="height: 75px;">
                        <input type="username" name="username"  class="form-control" value="" 
                               placeholder="请输入账户名" style="line-height: 17px;"/>
                        <div class="errorholder" style="display: none; height: 25px;margin-left:10px;">
                            <img src="style/A2.png" style="margin-left: 5px;margin-top:8px;"/>
                            <small class="help-block" id="username_error" validType="mobile-email"
                                   style="margin-top: -16px; margin-left: 30px;">请输入账户名
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <div class="" style="height: 75px;">
                        <input type="username" name="email"  class="form-control" value="" 
                               placeholder="请输入您的邮箱地址" style="line-height: 17px;"/>
                        <div class="errorholder" style="display: none; height: 25px;margin-left:10px;">
                            <img src="style/A2.png" style="margin-left: 5px;margin-top:8px;"/>
                            <small class="help-block" id="username_error" validType="mobile-email"
                                   style="margin-top: -16px; margin-left: 30px;">请输入您的邮箱地址
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <div class="" style="height: 75px;">
                        <input type="password" style="display: none;">
                        <input type="password" name="password"   value="" placeholder="请输入登录密码" style="padding: 6px 12px;line-height: 17px;" class="form-control"/>
                        <div class='keyboard' onclick="showKeyboard('password')" id="toggle2"
                             style="cursor: pointer;position: absolute; top: 14px; right: 11px;">
                        </div>
                        <div class="errorholder" style="display: none; height: 25px;margin-left:10px;">
                            <img src="style/A2.png" style="margin-left: 5px;margin-top:8px;"/>
                            <small class="help-block" validType="pwd" id="password_error"
                                   style="margin-top: -16px; margin-left: 30px;">密码格式不正确
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <div class="" style="height: 75px;">
                        <input type="password" style="display: none;">
                        <input type="password" name="confirmpassword"   value="" oncopy="return false;"
                               placeholder="请再次输入登录密码"
                               onpaste="return false;" oncut="return false;" autocomplete="off"
                               style="padding: 6px 12px;line-height: 17px;" class="form-control"/>
                        <div class='keyboard' onclick="showKeyboard('password')" id="toggle2"
                             style="cursor: pointer;position: absolute; top: 14px; right: 11px;">
                        </div>
                        <div class="errorholder" style="display: none; height: 25px;margin-left:10px;">
                            <img src="style/A2.png" style="margin-left: 5px;margin-top:8px;"/>
                            <small class="help-block" validType="pwd" id="password_error"
                                   style="margin-top: -16px; margin-left: 30px;">密码格式不正确
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <div class="" style="height: 75px;">
                        <input type="username" name="invitecode"  class="form-control" value="" 
                               placeholder="请输入邀请码" style="line-height: 17px;"/>
                        <div class="errorholder" style="display: none; height: 25px;margin-left:10px;">
                            <img src="style/A2.png" style="margin-left: 5px;margin-top:8px;"/>
                            <small class="help-block" id="username_error" validType="mobile-email"
                                   style="margin-top: -16px; margin-left: 30px;">请输入您的邮箱地址
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="button_btn">

                      
                        <button  id="btnSubmit" class="btn btn-primary" name="signup2"
                                value="Sign up 2" onclick="submitForm('<?php echo ($register_checkRegister); ?>','defaultForm');"
                                style="margin: 0px 0 13px 0;">注册
                        </button>
                    </div>
                </div>



            </form>
            
        </div>
    </div>
</section>



<style>
    .kexin #kx_verify img {
        z-index: 1;
        border: none;
        width: 126px;
        height: 41px;
        margin-top: -31px;
        position: relative;
        filter: grayscale(100%);
        -webkit-filter: grayscale(100%);
        filter: gray;
        border-radius: 5px;
    }

    .kexin #kx_verify img:hover {
        filter: grayscale(1%);
        -webkit-filter: grayscale(1%);
    }

</style>
<footer>
    <ul class="footer-list" style="padding-top: 15px;">

        <li>上Copyright © baidu.com, All Rights Reserved.</li>
        <li><?php echo ($sitename); ?></li>
    </ul>
    <ul class="footer-list color">
        <li class="first">
            <a href="javascript:void(0);"></a>
        </li>
        <li>
            <a href="javascript:void(0);"></a>
        </li>
        <li>
            <a href="javascript:void(0);"></a>
        </li>
        <li>
            <a href="javascript:void(0);"></a>
        </li>
        <li class="kexin">

            <!--可信网站图片LOGO安装开始-->
            <script src="<?php echo ($style); ?>js/seallogo.dll.js"></script>
            <!-- 可信网站图片LOGO安装结束 -->
        </li>
        <li>
            <a href="javascript:void(0);"></a>
        </li>
    </ul>
</footer>
</body>
</html>
<script type="text/javascript">
   
</script>