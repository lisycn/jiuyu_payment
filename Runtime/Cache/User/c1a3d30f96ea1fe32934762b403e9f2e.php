<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?php echo ($sitename); ?>---用户管理中心</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/Public/Front/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Front/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/Front/css/animate.css" rel="stylesheet">
    <link href="/Public/Front/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/Public/Front/js/plugins/layui/css/layui.css">
    <style>
        .layui-form-label {width:110px;padding:4px}
        .layui-form-item .layui-form-checkbox[lay-skin="primary"]{margin-top:0;}
        .layui-form-switch {width:54px;margin-top:0px;}
    </style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated">
<div class="row">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <!--条件查询-->
            <div class="ibox-title">
                <h5>资金变动管理</h5>
                <div class="ibox-tools">
                    <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                       style="cursor:pointer;">ဂ</i>
                </div>
            </div>
            <!--条件查询-->
            <div class="ibox-content">
                <form class="layui-form" action="" method="get" autocomplete="off" id="orderform">
                    <input type="hidden" name="m" value="User">
                    <input type="hidden" name="c" value="Account">
                    <input type="hidden" name="a" value="changeRecord">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <input type="text" name="orderid" autocomplete="off" placeholder="请输入订单号"
                                       class="layui-input" value="<?php echo ($_GET['orderid']); ?>">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" name="createtime" id="createtime"
                                       placeholder="起始时间" value="<?php echo (urldecode($_GET['createtime'])); ?>">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <select name="tongdao">
                                    <option value="">全部通道</option>
                                    <?php if(is_array($products)): $i = 0; $__LIST__ = $products;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($_GET[tongdao] == $vo[id]): ?>selected<?php endif; ?>
                                        value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                            <div class="layui-input-inline">
                                <select name="bank">
                                    <option value="">全部类型</option>
                                    <option <?php if($_GET[bank] == 1): ?>selected<?php endif; ?> value="1">付款</option>
                                    <option <?php if($_GET[bank] == 3): ?>selected<?php endif; ?> value="3">手动增加</option>
                                    <option <?php if($_GET[bank] == 4): ?>selected<?php endif; ?> value="4">手动减少</option>
                                    <option <?php if($_GET[bank] == 6): ?>selected<?php endif; ?> value="6">结算</option>
                                    <option <?php if($_GET[bank] == 7): ?>selected<?php endif; ?> value="7">冻结</option>
                                    <option <?php if($_GET[bank] == 8): ?>selected<?php endif; ?> value="8">解冻</option>
                                    <option <?php if($_GET[bank] == 9): ?>selected<?php endif; ?> value="9">提成</option>
                                </select>
                            </div>

                        </div>

                        <div class="layui-inline">
                            <button type="submit" class="layui-btn"><span
                                    class="glyphicon glyphicon-search"></span> 搜索
                            </button>
                            <a href="javascript:;" id="export" class="layui-btn layui-btn-danger"><span class="glyphicon glyphicon-export"></span> 导出数据</a>
                        </div>
                    </div>
                </form>

                <!--交易列表-->
                <table class="layui-table" lay-data="{width:'100%',limit:<?php echo ($rows); ?>,id:'userData'}">
                    <thead>
                    <tr>
                        <th lay-data="{field:'key',width:60}"></th>
                        <th lay-data="{field:'transid', width:240}">订单号</th>
                        <th lay-data="{field:'userid', width:100,style:'color:#060;'}">用户名</th>
                        <th lay-data="{field:'lx', width:90}">类型</th>
                        <th lay-data="{field:'tcuserid', width:100,style:'color:#060;'}">提成用户名</th>
                        <th lay-data="{field:'tcdengji', width:90}">提成级别</th>
                        <th lay-data="{field:'money', width:100}">变动金额</th>
                        <th lay-data="{field:'datetime', width:180}">变动时间</th>
                        <th lay-data="{field:'tongdao', width:120}">通道</th>
                        <th lay-data="{field:'contentstr', width:180}">备注</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo["id"]); ?></td>
                            <td style="text-align:center;"><?php echo ($vo["transid"]); ?></td>
                            <td style="text-align:center; color:#090;">
                                <?php echo (getParentName($vo["userid"],1)); ?>
                            </td>
                            <td style="text-align:center;">
                                <?php switch($vo["lx"]): case "1": ?>付款<?php break;?>
                                    <?php case "3": ?>手动增加<?php break;?>
                                    <?php case "4": ?>手动减少<?php break;?>
                                    <?php case "6": ?>结算<?php break;?>
                                    <?php case "7": ?>冻结<?php break;?>
                                    <?php case "8": ?>解冻<?php break;?>
                                    <?php case "9": ?>提成<?php break;?>
                                    <?php default: ?>未知<?php endswitch;?>
                            </td>
                            <td style="text-align:center; color:#060">
                                <?php echo (getParentName($vo["tcuserid"],1)); ?>
                            </td>
                            <td style="color:#666"><?php echo ($vo["tcdengji"]); ?>&nbsp;</td>
                            <td>
                                <?php if($vo["money"] < 0): ?><span style="color:#F00">
                                <?php else: ?>
                                    <span style="color:#030"><?php endif; ?>
                                <?php echo ($vo["money"]); ?>
                                </span>
                            </td>
                            <td style="text-align:center;"><?php echo ($vo["datetime"]); ?></td>
                            <td style="text-align:center;"><?php echo (getProduct($vo["tongdao"])); ?></td>
                            <td style="text-align:center;">
                                <?php if($vo["lx"] == 1 or $vo["lx"] == 9): echo huoquddlx($vo.transid);?>
                                    <?php else: ?>
                                    <?php echo ($vo['contentstr']); endif; ?>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <!--交易列表-->
               <div class="page">
                    
                    <form class="layui-form" action="" method="get" id="pageForm" autocomplete="off">     
                        <?php echo ($page); ?>                            
                        <select name="rows" style="height: 32px;" id="pageList" lay-ignore >
                            <option value="">显示条数</option>
                            <option <?php if($_GET[rows] != '' && $_GET[rows] == 15): ?>selected<?php endif; ?> value="15">15条</option>
                            <option <?php if($_GET[rows] == 30): ?>selected<?php endif; ?> value="30">30条</option>
                            <option <?php if($_GET[rows] == 50): ?>selected<?php endif; ?> value="50">50条</option>
                            <option <?php if($_GET[rows] == 80): ?>selected<?php endif; ?> value="80">80条</option>
                            <option <?php if($_GET[rows] == 100): ?>selected<?php endif; ?> value="100">100条</option>
                            <option <?php if($_GET[rows] == 1000): ?>selected<?php endif; ?> value="1000">1000条</option>
                        </select>
                       

                    </form>
                  
                </div> 
            </div>
        </div>
    </div>
</div>
</div>
<script src="/Public/Front/js/jquery.min.js"></script>
<script src="/Public/Front/js/bootstrap.min.js"></script>
<script src="/Public/Front/js/plugins/peity/jquery.peity.min.js"></script>
<script src="/Public/Front/js/content.js"></script>
<script src="/Public/Front/js/plugins/layui/layui.js" charset="utf-8"></script>
<script src="/Public/Front/js/x-layui.js" charset="utf-8"></script>
<script src="/Public/Front/js/Util.js" charset="utf-8"></script>
<script>
    layui.use(['laydate', 'laypage', 'layer', 'table', 'form'], function() {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            ,layer = layui.layer //弹层
            ,form = layui.form //表单
            , table = layui.table; //表格
        //日期时间范围
        laydate.render({
            elem: '#createtime'
            , type: 'datetime'
            ,theme: 'molv'
            , range: '|'
        });
    });
    $('#export').on('click',function(){
        window.location.href
            ="<?php echo U('User/Account/exceldownload',array('orderid'=>$_GET[orderid],'createtime'=>$_GET[createtime],'tongdao'=>$_GET[tongdao],'bank'=>$_GET[bank]));?>";
    });
    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
</script>
</body>
</html>