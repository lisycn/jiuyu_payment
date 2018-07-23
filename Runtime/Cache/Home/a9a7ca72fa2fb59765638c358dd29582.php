<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>

    <meta charset="UTF-8">
    <title>登录注册 - <?php echo ($sitename); ?></title>
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
    <link rel="stylesheet" type="text/css" href="<?php echo ($style); ?>css/test_index_requireFromJS.css"/>
    <link rel="stylesheet" media="screen" title="no title" href="<?php echo ($style); ?>css/ysbformvalidate.css"/>

    <script type="text/javascript" src="<?php echo ($style); ?>js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo ($style); ?>js/CFCASIPInput.min.js"></script>

    <script type="text/javascript">
        //document.getElementById("mobileVericode").style.display="none";
        var wait = 60;

        function getVerificationCode() {

            if ($(username).val() == '') {
                alert("请输入手机号账户名！！！");
                return false;
            }

        }


        function time(o) {
            if (wait == 0) {
                o.removeAttribute("disabled");
                o.value = "免费获取验证码";
                wait = 60;
            } else {

                o.setAttribute("disabled", true);
                o.value = "(" + wait + ")秒后，获取验证码";
                wait--;
                setTimeout(function () {
                        time(o)
                    },
                    1000)
            }
        }

    </script>
    <script type="text/javascript">


        var sr = null;



        var sip;
        var sipRandom = {};
        var matchRegex = "^[0-9a-zA-Z\x21-\x7e]{6,20}$";


        function clearCookie() {
            var keys = document.cookie.match(/[^ =;]+(?=\=)/g);
            if (keys) {
                for (var i = keys.length; i--;)
                    document.cookie = keys[i] + '=0;expires=' + new Date(0).toUTCString()
            }
        }

        function getEncrypt(sipboxId) {
            var encryptedInputValue = sip.getEncryptedInputValue(sipboxId);
            $("#logon_person_passPerson").val(encryptedInputValue);
            var encryptedClientRandom = sip.getEncryptedClientRandom(sipboxId);
            $("#cr").val(encryptedClientRandom);
        }

        function showKeyboard(inputid) {

            sip = SoftControllerInit(inputid, matchRegex, sr);
            sip.showKeyboard(inputid, sipRandom[inputid]);
        }

        function validate(id) {
            var errorId = id + "_error";
            //验证规则
            var canShow = true;
            var rule = $("#" + errorId).attr("validType");
            if (rule == 'mobile-email') {
                //验证手机或者邮箱
                canShow = isMobileOrEmail($("#" + id).val());
            } else if (rule == 'pwd') {
                //验证密码
                if (checkRegex("password", matchRegex)) {
                    canShow = false;
                }
            } else {
                //非空
                if ($("#" + id).val() != '') {
                    canShow = false;
                }
            }
            if (canShow == true) {
                $("#" + errorId).closest("div").show();
            } else {
                $("#" + errorId).closest("div").hide();
            }
        }

        function isMobileOrEmail(text) {
            var re = /^((1(3|8)[0-9]{9})|(14(5|7)[0-9]{8})|(15[012356789][0-9]{8})|(17[0-9]{9}))$/;
            var re_email = /\w@\w*\.\w/;
            if (text != '' && re.test(text)) {
                return false;
            }
            if (text != '' && re_email.test(text)) {
                return false;
            }
            return true;
        }
           function submitForm(loginUrl,formId){
           
                $.ajax({
                    type:'post',
                    url:loginUrl,
                    data:$('#'+formId).serialize(),
                    dataType:'json',
                    success:function(result){

                        if(result['status'] == 0){
                            layer.msg(result['info']); 
                        }else{
                            window.location.href = result['url'];
                        }
                    }
                })
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
            <img src="<?php echo ($style); ?>/image/A1tu.png" alt=""/>
        </div>
        <div class="login_right">
            <h1>登录商户中心</h1>
            <form onsubmit="return false" name="frmLogon" action="/#" id="defaultForm" method="post"
                  class="form-horizontal ysbformvalidate" style="padding: 25px 37px">
                <!-- <input type="hidden" id="logon_atype" name="logon_atype" value="A"/> -->
                <!-- <input type="hidden" name="logon_corp_operator" value=""/> -->
                <!-- <input type="hidden" id="from" name="from" value="information_main_failure"/> -->
                <div class="form-group has-feedback">
                    <div class="" style="height: 75px;">
                        <input type="username" name="username"  class="form-control"  id="username"
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
                    <!-- <input name="cr" id="cr" type="hidden" value=""> -->
                    <div class="" style="height: 75px;">
                        <input type="password" style="display: none;">
                        <input type="hidden" id="logon_person_passPerson" name="password" value=""/>
                        <input type="password" name="password" id="password"  value="" oncopy="return false;"
                               placeholder="请输入登录密码"
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


                <div class="form-group">
                    <div class="" style="height: 75px;">
                        <input type="text"  name="varification" id="checkCode" class="form-control"
                               style="width: 154px; height: 44px; float: left;line-height: 17px;" placeholder="请输入验证码"/>
                        <span style="margin-left: 10px; width: 100px; height: 43px; line-height: 43px; vertical-align: middle;">
                            <img style="width: 165px;" id="logon_sys_vericode_image0" name="logon_sys_vericode_image0"
                                 src="/agent_Login_verifycode.html?t=<?php echo time();?>" onclick="this.src='/agent_Login_verifycode.html?d='+Math.random();" class="yzmtu">
                        </span>
                       
                        <div class="errorholder" style="float: left; display: none;margin-left:10px;">
                            <img src="style/A2.png" style="margin-left: 5px;margin-top:8px;"/>
                            <small class="help-block" id="checkCode_error"
                                   style="margin-top: -16px; margin-left: 30px;"> 验证码错误
                            </small>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="button_btn">
                        <button id="btnSubmit" onclick="submitForm('<?php echo ($user_checklogin); ?>','defaultForm')" class="btn btn-primary" name="signup2"
                                value="Sign up 2"
                                style="margin: 0px 0 13px 0;">登录
                        </button>
                        <a href="reg.html" title="注册" style="display:none">
                            <em class="btn btn-primary2" value="Sign up 2" style="padding-top: 5px;">注册</em>
                        </a>
                    </div>
                </div>
            </form>
            <div class="login_text"
                 style="height: 60px; line-height: 25px; border-top: 1px solid #e4e4e4;">
                <!-- <em class="login_one"><a href="#">帐号激活</a></em> 
                <em class="login_three"><a href="#">忘记密码？</a></em>-->
            </div>
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