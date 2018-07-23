<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>注册-API聚合支付</title>
    <link rel="stylesheet" type="text/css" href="css/reset.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <script src="js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
    <script src="/js/layer/layer.min.js"></script>
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js" type="text/javascript" charset="utf-8"></script>
    <![endif]-->

</head>
<body>

<div class="loginWrapper clearfix">
    <div class="banWrapper" style="background: #00a7f2">
        <div class="banContent">
            <a href="index.html" target="_blank" title="Data，Change The World">
                <img src="image/register.png" border="0"/></a>
        </div>
    </div>
    <div class="loginMain">
        <div class="loginWidth">
            <div class="loginLogoDiv"><a class="loginLogo" href="#"><img src="image/logo2.png" /></a></div>
            <form class="formLogin"  data-animate-effect="fadeInRight" id="formlogin" name="formlogin"
                  method="post" role="form" action="<?php echo ($loginUrl); ?>">
                <div class="loginList loginListUser">
                    <label></label>
                    <input type="text" class="loginText" name="username" id="username" value=""
                           placeholder="请输入用户名"/>
                    <span class="errorTips"><i></i><em></em></span>
                </div>


                <div class="loginList loginListPwd">
                    <label></label>
                    <input type="password" class="loginText" name="password" id="password" value="" placeholder="请输入密码"/>
                    <span class="errorTips"><i></i><em></em></span>
                </div>

                <div class="loginList loginListPwd">
                    <label></label>
                    <input type="password" class="loginText" name="password" id="confirmpassword" value="" placeholder="请再次输入密码"/>
                    <span class="errorTips"><i></i><em></em></span>
                </div>


                <div class="loginList loginListPwd">
                    <label></label>
                    <input type="text" class="loginText" name="email" id="email" value="" placeholder="请输入您的Email地址"/>
                    <span class="errorTips"><i></i><em></em></span>
                </div>

                <div class="loginList loginListPwd">
                    <label></label>
                    <input type="text" class="loginText" name="invitecode" id="invitecode" value="" placeholder="必须有邀请码才能注册"/>
                    <span class="errorTips"><i></i><em></em></span>
                </div>





                <div class="sysError" style="display:none"><label></label><span><i></i><em></em></span></div>


                <input class="loginBtn" type="button" id="regBtn" value="注册"/>
                <p class="have">已有账号，<a href="<?php echo U('Login/index');?>">立即登录<i></i></a></p>

            </form>
        </div>
    </div>
</div>



<script>

    function checkEmail(email){

        var myReg=/^[a-zA-Z0-9_-]+@([a-zA-Z0-9]+\.)+(com|cn|net|org)$/;

        if(myReg.test(email)){
            return true;
        }else{
            
            return false;
        }
    }


    $("#regBtn").click(function () {




        var username = $.trim($("#username").val());
        var password =$.trim($("#password").val());
        var confirmpassword =$.trim($("#confirmpassword").val());
        var email = $.trim($("#email").val());
        var invitecode = $.trim($("#invitecode").val());

        if (username.length < 1) {

            layer.msg('请输入正确格式的用户名');
            return false;

        }
        else if (password.length < 6) {
            layer.msg('请输入正确格式的密码');
            return false;

        }
        else if (email==''||!checkEmail(email)) {
            layer.msg('请输入正确的邮箱');
            return false;
        }


        else if (password!=confirmpassword) {
            layer.msg('两次密码输入不一致');
            return false;

        }
       <?php if($siteconfig['invitecode'] == 1): ?>else if (invitecode == '') {
            layer.msg('邀请码不能为空');
            return false;

        }<?php endif; ?>



        if(invitecode!='')
        {




            var checkinvitecode=true;
            $.ajax({
                type:'post',
                async:false,
                url:'user_Login_checkinvitecode.html',
                data: { invitecode: invitecode},
                dataType:'json',
                success:function(result){

                    checkinvitecode=result['valid'];
                }
            })
            if(checkinvitecode==false)
            {
                layer.msg('激活码不正确');
                return false;
            }




        }

        $.ajax({
            type:'post',
            url:'user_Login_checkRegister.html',
            data: { username: username, password: password, confirmpassword: confirmpassword,email:email,invitecode:invitecode},
            dataType:'json',

            success:function(result){
                if(result['errorno'] == 0){

                    if(result['need_activate'] == 1) {
                        var TipsContent="<div style='padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;'>" +
                            "<h3>恭喜您，注册成功！</h3>" +
                            "<p>我们已发送了一封验证邮件到 <strong>"+result['msg']['email']+"</strong>, 请注意查收您的邮箱，点击其中的链接激活您的账号！</p>" +
                            "<p>如果您未收到验证邮件，请联系管理员重新发送验证邮件或手动帮您激活账号。</p>"
                            "<br/><br/><a href='user_Login_index.html' style='color:#fff'>点击这里登录</a></div>";

                        layer.open({
                            type: 1,
                            title:'注册成功',
                            skin: 'layui-layer-rim', //加上边框
                            area: ['500px', '350px'], //宽高
                            content: TipsContent,
                            end: function () {

                                setTimeout(function () {


                                    layer.msg('正在为您跳转到登录页面...');

                                    setTimeout(function() {
                                        window.location.href = "<?php echo ($siteurl); ?>" + "user_Login_index.html";
                                    },3000 );

                                },2000);

                            }
                        });
                    } else {
                        layer.msg('注册成功！正在为您跳转到登录页面...');

                        setTimeout(function() {
                            window.location.href = "<?php echo ($siteurl); ?>" + "user_Login_index.html";
                        },3000 );
                    }
                }else{
                    layer.msg(result['msg']);
                }
            }
        })

    })
</script>


</body>
</html>