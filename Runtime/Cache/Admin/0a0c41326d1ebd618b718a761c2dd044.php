<?php if (!defined('THINK_PATH')) exit();?><html>
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
    <link rel="stylesheet" href="/Public/Front/js/plugins/layui/css/layui.css">
    <style>
        .layui-form-label {width:110px;padding:4px}
        .layui-table td, .layui-table th {padding:6px 10px;}
        .layui-table td input {height:24px;line-height:24px;width:100px;}
        .layui-form-item .layui-form-checkbox[lay-skin="primary"]{margin-top:0;}
    </style>
<body>
<div class="wrapper wrapper-content animated">
    <div class="row">
        <div class="col-sm-12">
            <form class="layui-form" action="" id="product">
                <input type="hidden" name="id" value="<?php echo ($pd["id"]); ?>">
                <div class="layui-form-item">
                    <label class="layui-form-label">拉黑类型：</label>
                    <div class="layui-input-block">
                        <select name="pd[type]" lay-verify="required" id="types" lay-search="">
                            <option value="">直接选择或搜索选择</option>
                            <?php if(is_array($types)): $i = 0; $__LIST__ = $types;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$b): $mod = ($i % 2 );++$i;?><option <?php if($pd[type] == $b[id]): ?>selected<?php endif; ?> value="<?php echo ($b["id"]); ?>"><?php echo ($b["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">姓名或卡号：</label>
                    <div class="layui-input-block">
                        <input type="text" name="pd[value]" autocomplete="off" lay-verify="required" value="<?php echo ($pd[value]); ?>" placeholder="姓名或卡号" class="layui-input">
                    </div>
                </div>
            <div class="layui-form-item">
                <label class="layui-form-label">拉入黑名单：</label>
                <div class="layui-input-block">
                    <input type="radio" name="pd[status]" <?php if(( ! isset($pd[$status])) or ($pd[status] == 1)): ?>checked<?php endif; ?> value="1" title="是" checked="true">
                    <input type="radio" name="pd[status]" <?php if((isset($pd[$status])) and ($pd[status] == 0)): ?>checked<?php endif; ?> value="0" title="否">
                </div>
            </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="submit" lay-filter="add">提交保存</button>
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
<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
<script>
    layui.use(['layer', 'form'], function(){
        var form = layui.form
            ,layer = layui.layer;

        //监听radio
        form.on('radio(polling)', function(data){
            //console.log(data.elem); //得到radio原始DOM对象
            //console.log(data.value); //被点击的radio的value值
            var pty = $('#paytypes').val();
            var html = '';
            if(!pty){
                layer.msg("未选择分类!");
                return false;
            }
            if(data.value == 0){
                $('#selmodel').css('display','');
                $('#pdtable').css('display','none');
                html += '<option value="">直接选择或搜索选择</option>';
                for(var i in channels){
                    if(pty==channels[i].paytype){
                        html += '<option value='+channels[i].id+'>'+channels[i].title+'</option>';
                    }
                }
                $('#channels').html(html);
            }else{
                $('#selmodel').css('display','none');
                $('#pdtable').css('display','');
                for(var i in channels){
                    if(pty == channels[i].paytype){
                        html += '<tr>';
                        html += '<td><input type="checkbox" name="w['+channels[i].id+'][pid]" lay-skin="primary" value="'+channels[i].id+'"></td>';
                        html += '<td>'+channels[i].id+'</td>'
                        html += '<td>'+channels[i].title+'</td>';
                        html += '<td><input type="number" min="0" max="9" name="w['+channels[i]
                            .id+'][weight]" class="layui-input" value=""></td>';
                        html += '</tr>';
                    }
                }
                $('#pdtable > tbody').html(html);
            }
            form.render();
        });

        //全选
        form.on('checkbox(allChoose)', function(data){
            var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
            child.each(function(index, item){
                item.checked = data.elem.checked;
            });
            form.render('checkbox');
        });

        //监听提交
        form.on('submit(add)', function(data){
            $.ajax({
                url:"<?php echo U('Withdrawal/saveBlacklist');?>",
                type:"post",
                data:$('#product').serialize(),
                success:function(res){
                    if(res.status){
                        layer.alert("操作成功", {icon: 6},function () {
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    }else{
                        layer.msg("操作失败!", {icon: 5},function () {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                        return false;
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