<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="renderer" content="webkit">
<title><?php echo C("WEB_TITLE");?></title>
<link rel="shortcut icon" href="favicon.ico">
<link href="/Public/Front/css/bootstrap.min.css" rel="stylesheet">
<link href="/Public/Front/css/font-awesome.min.css" rel="stylesheet">
<link href="/Public/Front/css/animate.css" rel="stylesheet">
<link href="/Public/Front/css/style.css" rel="stylesheet">
<link href="/Public/Front/js/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<link rel="stylesheet" href="/Public/Front/js/plugins/layui/css/layui.css">
<style>
.layui-form-pane .layui-form-label {width:110px;padding:4px;}
.layui-form-pane .layui-form-switch {width:54px;}
</style>
<body>
    <div class="wrapper wrapper-content animated">
        <div class="row">
            <div class="col-sm-12">
			<form class="layui-form layui-form-pane" action="" id="profile">
			<input type="hidden" name="userid" value="<?php echo ($_GET['uid']); ?>">
			<input type="hidden" name="id" value="<?php echo ($u['id']); ?>">
				<div class="layui-form-item">
				<label class="layui-form-label">应用规则：</label>
				<div class="layui-input-block">
				  <input type="radio" name="u[systemxz]" lay-filter="changeRule" <?php if($u[systemxz] == 0): ?>checked<?php endif; ?> value="0" title="系统规则" checked="">
				  <input type="radio" name="u[systemxz]" lay-filter="changeRule" <?php if($u[systemxz] == 1): ?>checked<?php endif; ?> value="1" title="个人规则">
				</div>
			  </div>
			  <div id="changeRule" style="display:<?php if(!$u[systemxz]): ?>none<?php endif; ?>;">
			  <div class="layui-form-item">
				<label class="layui-form-label">单笔最小金额：</label>
				<div class="layui-input-inline">
				  <input type="number" min="0" name="u[tkzxmoney]" autocomplete="off" value="<?php echo ($u["tkzxmoney"]); ?>" placeholder="" class="layui-input">
				</div>
				<label class="layui-form-label">单笔最大金额：</label>
				<div class="layui-input-inline">
				  <input type="number" min="0" name="u[tkzdmoney]" autocomplete="off" value="<?php echo ($u["tkzdmoney"]); ?>" placeholder="" class="layui-input">
				</div>
			  </div>
			  
			  <div class="layui-form-item">
				<label class="layui-form-label">当日总金额：</label>
				<div class="layui-input-inline">
				  <input type="number" min="0" name="u[dayzdmoney]" autocomplete="off" value="<?php echo ($u["dayzdmoney"]); ?>" placeholder="0.00" class="layui-input">
				</div>
				<label class="layui-form-label">当日总次数：</label>
				<div class="layui-input-inline">
				  <input type="number" min="0" name="u[dayzdnum]" autocomplete="off" value="<?php echo ($u["dayzdnum"]); ?>" placeholder="" class="layui-input">
				</div>
			  </div>
			  
			  <div class="layui-form-item">
				<label class="layui-form-label">结算方式：</label>
				<div class="layui-input-block">
				  <input type="radio" name="u[t1zt]" <?php if($u[t1zt] == 0): ?>checked<?php endif; ?> value="0" title="T+0"
					checked="">
				  <input type="radio" name="u[t1zt]" <?php if($u[t1zt] == 1): ?>checked<?php endif; ?> value="1" title="T+1">
					<input type="radio" name="u[t1zt]" <?php if($u[t1zt] == 7): ?>checked<?php endif; ?> value="7" title="T+7（每周一允许结算）">
					<input type="radio" name="u[t1zt]" <?php if($u[t1zt] == 30): ?>checked<?php endif; ?> value="30" title="T+30（每月第一天允许结算">
				</div>
			  </div>
			  		  
			  <div class="layui-form-item">
				<label class="layui-form-label">手续费类型：</label>
				<div class="layui-input-inline">
				  <select name="u[tktype]" lay-verify="required" lay-search="">
					<option value=""></option>
					<option <?php if($u['tktype'] == 0): ?>selected<?php endif; ?> value="0">按比例计算</option>
					<option <?php if($u['tktype'] == 1): ?>selected<?php endif; ?> value="1">按单笔计算</option>
				  </select>
				</div>
			  </div>
			  <div class="layui-form-item">
				<label class="layui-form-label">单笔提款比例（%）：</label>
				<div class="layui-input-inline">
				  <input type="number" min="0" step="0.1" name="u[sxfrate]" autocomplete="off" value="<?php echo ($u["sxfrate"]); ?>" placeholder="%" class="layui-input">
				</div>
				<label class="layui-form-label">单笔提款收取（元）：</label>
				<div class="layui-input-inline">
				  <input type="number" min="0" name="u[sxffixed]" autocomplete="off" value="<?php echo ($u["sxffixed"]); ?>" placeholder="0.00" class="layui-input">
				</div>
			  </div>
			  <div class="layui-form-item">
				<label class="layui-form-label">提款状态：</label>
				<div class="layui-input-block">
				  <input type="radio" name="u[tkzt]" <?php if($u[tkzt] == 0): ?>checked<?php endif; ?> value="0" title="关闭" checked="">
				  <input type="radio" name="u[tkzt]" <?php if($u[tkzt] == 1): ?>checked<?php endif; ?> value="1" title="开通">
				</div>
			  </div>
			  </div>		  
			  <div class="layui-form-item">
				<div class="layui-input-block">
				  <button class="layui-btn" lay-submit="submit" lay-filter="save">提交保存</button>
				</div>
			  </div>
			</form>
            </div>
        </div>
    </div>

    <script src="/Public/Front/js/jquery.min.js"></script>
    <script src="/Public/Front/js/bootstrap.min.js"></script>
    <script src="/Public/Front/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="/Public/Front/js/content.js"></script>
	<script src="/Public/Front/js/plugins/layui/layui.js" charset="utf-8"></script>

<script>
layui.use(['layer', 'form','laydate'], function(){
  var form = layui.form
  ,laydate = layui.laydate
  ,layer = layui.layer;
  
  //监听radio
  form.on('radio(changeRule)', function(data){
	//console.log(data.elem); //得到radio原始DOM对象
	//console.log(data.value); //被点击的radio的value值
	if(data.value==1){
		$('#changeRule').css('display','');
	}else if(data.value==0){
		$('#changeRule').css('display','none');
	}
  });
  
  //监听提交
  form.on('submit(save)', function(data){
    $.ajax({
		url:"<?php echo U('User/saveWithdrawal');?>",
		type:"post",
		data:$('#profile').serialize(),
		success:function(res){
			if(res.status){
			layer.alert("编辑成功", {icon: 6},function () {
				parent.location.reload();
				var index = parent.layer.getFrameIndex(window.name);
				parent.layer.close(index);
			});
			}
		}
	});
    return false;
  });
});
</script>
<!--统计代码，可删除-->
</body>
</html>