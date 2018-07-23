<?php
/**
 * Created by PhpStorm.
 * User: gaoxi
 * Date: 2017-08-22
 * Time: 14:34
 */
namespace User\Controller;

use Intervention\Image\ImageManagerStatic;
use Think\Page;
use Think\Upload;

/**
 *  商家账号相关控制器
 * Class AccountController
 * @package User\Controller
 */

class AccountController extends UserController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 编辑个人资料
     */
    public function profile()
    {

        $list = M("Member")->where(['id' => $this->fans['uid']])->find();
        //查询是否开启短信验证
        $sms_is_open = smsStatus();
        if ($sms_is_open) {
            $this->assign('sendUrl', U('User/Account/profileSend'));
        }
        //查询是否开启代付API
        $df_api = M('websiteconfig')->getField('df_api');
        $this->assign('df_api', $df_api);
        $this->assign('sms_is_open', $sms_is_open);
        $list['agentname'] = '';
        if($this->fans['parentid']>0) {
            $list['agentname'] = M('Member')->where(['id' => $this->fans['parentid']])->getField('username');
        }
        $this->assign("p", $list);
        $this->display();
    }

    /**
     * 发送编辑个人资料验证码信息
     */
    public function profileSend()
    {
        $res = $this->send('saveProfile', $this->fans['mobile'], '编辑个人资料验');
        $this->ajaxReturn(['status' => $res['code']]);
    }

    /**
     * 保存个人资料
     */
    public function saveProfile()
    {
        if (IS_POST) {
            //验证验证码
            $code        = I('request.code');
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                if (session('send.saveProfile') == $code && $this->checkSessionTime('saveProfile', $code)) {
                    session('send', null);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
                }
            }

            $uid           = I('post.id', 0, 'intval');
            $p             = I('post.p');
            $p['parentid'] = $this->fans['parentid'];
            if($p['agentname'] != '') {
                $agent_name = M('Member')->where(['id' => $this->fans['parentid']])->getField('username');
                $new_agent_id = M('Member')->where(['username' => $p['agentname']])->getField('id');
                if(!$new_agent_id) {
                    $this->ajaxReturn(['status' => 0, 'msg' => '代理商不存在']);
                }
                if($new_agent_id>0 && $agent_name != $p['agentname']) {
                    $p['parentid'] = $new_agent_id;
                }
            } else {
                $p['parentid'] = 1;
            }
            unset($p['agentname']);
            $p['birthday'] = strtotime($p['birthday']);
            $res           = M('Member')->where(['id' => $uid])->save($p);
            if(FALSE !== $res) {
                $this->ajaxReturn(['status' => 1, 'msg' => '编辑成功']);
            } else {
                $this->ajaxReturn(['status' => 0, 'msg' => '编辑失败']);
            }

        }
    }

    /**
     *  银行卡列表
     */
    public function bankcard()
    {
        $list = M('Bankcard')
            ->where(['userid' => $this->fans['uid']])
            ->order('id desc')
            ->select();
        $this->assign("list", $list);
        $this->display();
    }

    /**
     *  添加银行卡
     */
    public function addBankcard()
    {

        $banklist = M("Systembank")->select();
        $this->assign("banklist", $banklist);

        if (IS_POST) {
            $id   = I('post.id', 0, 'intval');
            $rows = I('post.b');
            //验证验证码
            $code        = I('post.code');
            $sms_is_open = smsStatus(); //短信开启状态
            if ($sms_is_open) {
                if (session('send.addBankcardSend') == $code && $this->checkSessionTime('addBankcardSend', $code)) {
                    session('send',null);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
                }
            }
            if ($id) {
                $res = M('Bankcard')->where(['id' => $id])->save($rows);
            } else {
                $rows['userid'] = $this->fans['uid'];
                $res            = M('Bankcard')->add($rows);
            }
            $this->ajaxReturn(['status' => $res]);
        } else {
            $id = I('get.id', 0, 'intval');
            //查询是否开启短信验证
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                $this->assign('sendUrl', U('User/Account/addBankcardSend'));
            }
            $this->assign('sms_is_open', $sms_is_open);
            if ($id) {
                $data = M('Bankcard')->where(['id' => $id])->find();
                $this->assign('b', $data);
            }
            $this->display();
        }

    }

    /**
     * 发送申请结算验证码信息
     */
    public function addBankcardSend()
    {
        $res = $this->send('addBankcardSend', $this->fans['mobile'], '申请结算');
        $this->ajaxReturn(['status' => $res['code']]);
    }

    /**
     *绑定手机
     */
    public function bindMobileShow()
    {
        if (IS_POST) {
            //验证验证码
            $code   = I('request.code');
            $mobile = I('request.mobile');

            if (session('send.bindMobile') == $code && $this->checkSessionTime('bindMobile', $code)) {
                $res = M('Member')->where(['id' => $this->fans['uid']])->save(['mobile' => $mobile]);
                $this->ajaxReturn(['status' => $res]);
            }
        } else {
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                $id = I('request.id', '');
                $this->assign('sendUrl', U('User/Account/bindMobile'));
                $this->assign('first_bind_mobile', 1);
                $this->assign('sms_is_open', $sms_is_open);
                $this->display();
            }
        }
    }

    /**
     *修改手机新手机
     */
    public function editMobileShow()
    {
        $sms_is_open = smsStatus();
        if (IS_POST) {
            $code = I('request.code');
            if (session('send.editMobile') == $code && $this->checkSessionTime('editMobile', $code)) {
                session('send.editMobile', null);
                //判断是验证码新手机还是旧手机后的处理
                if (session('editmobile') == '1') {
                    $mobile           = I('request.mobile');
                    $return['status'] = M('Member')->where(['id' => $this->fans['uid']])->save(['mobile' => $mobile]);
                    $return['data']   = 'editNewMobile';
                } else {
                    session('editmobile', 1);
                    $return['status'] = 1;
                }
                $this->ajaxReturn($return);
            } else {
                $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
            }
        } else {
            if ($sms_is_open) {
                //判断是否是获取新手机验证码还是旧手机验证码的视图
                !I('request.editnewmobile', 0) && session('editmobile', 0);
                $this->assign('editmobile', session('editmobile'));
                $this->assign('sms_is_open', $sms_is_open);
                $this->assign('sendUrl', U('User/Account/editMobile'));
                $this->assign('mobile', $this->fans['mobile']);
                $this->display();
            }
        }
    }

    /**
     *  修改默认
     */
    public function editBankStatus()
    {
        if (IS_POST) {
            $id        = I('post.id');
            $isdefault = I('post.isopen');
            if ($id) {
                if ($isdefault) {
                    M('Bankcard')->where(['userid' => $this->fans['uid']])->save(['isdefault' => 0]);
                }
                $res = M('Bankcard')->where(['id' => $id])->save(['isdefault' => $isdefault]);
                $this->ajaxReturn(['status' => $res]);
            }
        }
    }
    /**
     *  删除银行卡
     */
    public function delBankcard()
    {
        if (IS_POST) {
            $id = I('post.id', 0, 'intval');
            if ($id) {
                $res = M('Bankcard')->where(['id' => $id])->delete();
                $this->ajaxReturn(['status' => $res]);
            }
        }
    }
    public function bankcardedit()
    {
        if (IS_POST) {
            $id       = I('post.id');
            $Ip       = new \Org\Net\IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
            $location = $Ip->getlocation(); // 获取某个IP地址所在的位置

            $Bankcard  = M("Bankcard");
            $_formdata = array(
                'userid'       => session("userid"),
                'bankname'     => I('post.bankname'),
                'bankfenname'  => I('post.bankfenname'),
                'bankzhiname'  => I('post.bankzhiname'),
                'banknumber'   => I('post.banknumber'),
                'bankfullname' => I('post.bankfullname'),
                'sheng'        => I('post.sheng'),
                'shi'          => I('post.shi'),
                'kdatetime'    => date("Y-m-d H:i:s"),
                'jdatetime'    => date("Y-m-d H:i:s", time() + 40 * 3600 * 24),
                'ip'           => $location['ip'],
                'ipaddress'    => $location['country'] . "-" . $location['area'],
                'disabled'     => 1,
            );
            if ($id) {
                $result = $Bankcard->where(array('id' => $id))->save($_formdata);
            } else {
                $result = $Bankcard->add($_formdata);
            }
            $Bankcard->getLastSql();
            if ($result) {
                $this->success("银行卡信息修改成功！");
            } else {
                $this->error("银行卡息修改失败！");
            }
        }
    }

    public function loginrecord()
    {
        $maps['userid'] = $this->fans['uid'];
        $maps['type']   = 0;
        $count          = M('Loginrecord')->where($maps)->count();
        $page           = new Page($count, 5);
        $list           = M('Loginrecord')
            ->where($maps)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order('id desc')
            ->select();
        $this->assign("list", $list);
        $this->assign('page', $page->show());
        $this->display();
    }

    /**
     *  商户认证
     */
    public function authorized()
    {
        $authorized = M('Member')->where(['id' => $this->fans['uid']])->getField('authorized');
        $list       = [];
        $list       = M('Attachment')->where(['userid' => $this->fans['uid']])->select();
        $this->assign('list', $list);
        $this->assign('authorized', $authorized);
        $this->display();
    }

    public function upload()
    {
        if (IS_POST) {
            $upload           = new Upload();
            $upload->maxSize  = 2097152;
            $upload->exts     = array('jpg', 'gif', 'png');
            $upload->savePath = '/verifyinfo/';
            $info             = $upload->uploadOne($_FILES['auth']);
            if (!$info) {
                // 上传错误提示错误信息
                $this->error($upload->getError());
            } else {
                $data = [
                    'userid'   => $this->fans['uid'],
                    'filename' => $info['name'],
                    'path'     => 'Uploads' . $info['savepath'] . $info['savename'],
                ];
                $res = M("Attachment")->add($data);
                $this->ajaxReturn($res);
            }
        }
    }

    public function certification()
    {
        M('Member')->where(['id' => $this->fans['uid']])->save(['authorized' => 2]);
        $this->success('已申请认证，请等待审核！');
    }

    /**
     *  修改支付密码
     */
    public function editPaypassword()
    {
        $data = M('Member')->where(['id' => $this->fans['uid']])->find();
        $this->assign('p', $data);
        //查询是否开启短信验证
        $sms_is_open = smsStatus();

        if (IS_POST) {
            //验证验证码
            $code = I('request.code');
            if ($sms_is_open) {
                if (session('send.editPayPassword') == $code && $this->checkSessionTime('editPayPassword', $code)) {
                    session('send', null);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
                }
            }

            $id = I('post.id');
            $p  = I('post.p');
            if (!$p['oldpwd'] || !$p['newpwd'] || !$p['secondpwd'] || $p['newpwd'] != $p['secondpwd'] ||
                $data['paypassword'] != md5($p['oldpwd'])) {
                $this->ajaxReturn(['status' => 0, 'msg' => '输入错误']);
            }
            $res = M('Member')->where(['id' => $id])->save(['paypassword' => md5($p['newpwd'])]);
            $this->ajaxReturn(['status' => $res]);
        } else {
            if ($sms_is_open) {
                $this->assign('sendUrl', U('User/Account/editPayPasswordSend'));
            }
            $this->assign('sms_is_open', $sms_is_open);
            $this->display();
        }
    }

    /**
     * 修改密码
     */
    public function editPassword()
    {
        $data = M('Member')->where(['id' => $this->fans['uid']])->find();
        $this->assign('p', $data);
        //查询是否开启短信验证
        $sms_is_open = smsStatus();

        if (IS_POST) {
            //验证验证码
            $code = I('request.code');
            if ($sms_is_open) {
                if (session('send.editPassword') == $code && $this->checkSessionTime('editPassword', $code)) {
                    session('send', null);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '验证码错误']);
                }
            }

            $salt = $data['salt'];
            $id   = I('post.id');
            $p    = I('post.p');
            if (!$p['oldpwd'] || !$p['newpwd'] || !$p['secondpwd'] || $p['newpwd'] != $p['secondpwd'] || $data['password'] != md5
                ($p['oldpwd'] . $salt)) {
                $this->ajaxReturn(['status' => 0, 'msg' => '输入错误']);
            }
            $res = M('Member')->where(['id' => $id])->save(['password' => md5($p['newpwd'] . $salt)]);
            if($res !== false) {
                if(!$res) {
                    $this->ajaxReturn(['status' => 0, 'msg' => '请勿使用旧密码']);
                } else {
                    $this->ajaxReturn(['status' => 1, 'msg' => '修改密码成功']);
                }
            } else {
                $this->ajaxReturn(['status' => 0, 'msg' => '修改密码失败']);
            }
        } else {
            if ($sms_is_open) {
                $this->assign('sendUrl', U('User/Account/editPasswordSend'));
            }
            $this->assign('sms_is_open', $sms_is_open);
            $this->display();
        }
    }

    /**
     *  资金变动记录
     */
    public function changeRecord()
    {
        //商户支付通道
        $products = M('ProductUser')
            ->join('LEFT JOIN __PRODUCT__ ON __PRODUCT__.id = __PRODUCT_USER__.pid')
            ->where(['pay_product_user.status' => 1, 'pay_product_user.userid' => $this->fans['uid']])
            ->field('pay_product.name,pay_product.id,pay_product.code')
            ->select();
        $this->assign("products", $products);

        $where   = array();
        $orderid = I("get.orderid");
        if ($orderid) {
            $where['transid'] = array('eq', $orderid);
        }
        $tongdao = I("request.tongdao");
        if ($tongdao) {
            $where['tongdao'] = array('eq', $tongdao);
        }
        $bank = I("request.bank", '', 'strip_tags');
        if ($bank) {
            $where['lx'] = array('eq', $bank);
        }
        $createtime = urldecode(I("request.createtime"));
        if ($createtime) {
            list($cstime, $cetime) = explode('|', $createtime);
            $where['datetime']     = ['between', [$cstime, $cetime ? $cetime : date('Y-m-d')]];
        }
        $where['userid'] = $this->fans['uid'];
        $count           = M('Moneychange')->where($where)->count();
        $size            = 15;
        $rows            = I('get.rows', $size);
        if (!$rows) {
            $rows = $size;
        }
        $page = new Page($count, $rows);
        $list = M('Moneychange')
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order('id desc')
            ->select();
        $this->assign('rows', $rows);
        $this->assign('list', $list);
        $this->assign('page', $page->show());
        C('TOKEN_ON', false);
        $this->display();
    }

    /**
     * 资金变动记录导出
     */
    public function exceldownload()
    {
        $where = array();

        $orderid = I("request.orderid");
        if ($orderid) {
            $where['orderid'] = $orderid;
        }
        $tongdao = I("request.tongdao");
        if ($tongdao) {
            $where['tongdao'] = array('eq', $tongdao);
        }
        $bank = I("request.bank", '', 'strip_tags');
        if ($bank) {
            $where['lx'] = array('eq', $bank);
        }
        $createtime = urldecode(I("request.createtime"));
        if ($createtime) {
            list($cstime, $cetime) = explode('|', $createtime);
            $where['datetime']     = ['between', [$cstime, $cetime ? $cetime : date('Y-m-d')]];
        }
        $where['userid'] = $this->fans['uid'];
        $list            = M("Moneychange")->where($where)->select();
        $title           = array('订单号', '用户名', '类型', '提成用户名', '提成级别', '原金额', '变动金额', '变动后金额', '变动时间', '通道', '备注');

        foreach ($list as $key => $value) {
            $data[$key]['transid']    = "\t" . $value["transid"];
            $data[$key]['parentname'] = getParentName($value["tcuserid"], 1);
            switch ($value["lx"]) {
                case 1:
                    $data[$key]['lxstr'] = "付款";
                    break;
                case 3:
                    $data[$key]['lxstr'] = "手动增加";
                    break;
                case 4:
                    $data[$key]['lxstr'] = "手动减少";
                    break;
                case 6:
                    $data[$key]['lxstr'] = "结算";
                    break;
                case 7:
                    $data[$key]['lxstr'] = "冻结";
                    break;
                case 8:
                    $data[$key]['lxstr'] = "解冻";
                    break;
                case 9:
                    $data[$key]['lxstr'] = "提成";
                    break;
                default:
                    $data[$key]['lxstr'] = "未知";
            }
            $data[$key]['tcuserid']   = getParentName($value["tcuserid"], 1);
            $data[$key]['tcdengji']   = $value["tcdengji"];
            $data[$key]['ymoney']     = $value["ymoney"];
            $data[$key]['money']      = $value["money"];
            $data[$key]['gmoney']     = $value["gmoney"];
            $data[$key]['datetime']   = "\t" . $value["datetime"];
            $data[$key]['tongdao']    = getProduct($value["tongdao"]);
            $data[$key]['contentstr'] = $value["contentstr"];
        }
        exportCsv($data, $title);
    }

    /**
     * 收款二维码
     */
    public function qrcode()
    {
        $list = M("Member")->where(['id' => $this->fans['uid']])->find();
        $this->assign("p", $list);

        //生成二维码
        import("Vendor.phpqrcode.phpqrcode", '', ".php");
        $site = ((is_https()) ? 'https' : 'http') . '://' . C("DOMAIN");
        $url  = U('Pay/Charges/index', array('mid' => ($this->fans['uid'] + 10000)));

        $url = urldecode($site . $url);
        $QR  = "Uploads/charges/" . ($this->fans['uid'] + 10000) . ".png"; //已经生成的原始二维码图

        \QRcode::png($url, $QR, "L", 20, 1);

        //生成背景图
        vendor('image.autoLoad');
        ImageManagerStatic::configure(array('driver' => C('imageDriver')));
        $imageQr = ImageManagerStatic::make($QR)->resize(244, 244);
        $image   = ImageManagerStatic::make("Public/images/qrcode_bg.png");
        $image->text($this->fans['receiver'], 320, 560, function ($font) {
            $font->file('Public/Front/fonts/msyh.ttf');
            $font->size(24);
            $font->color('#333');
            $font->align('center');
            $font->valign('top');
        });
        $image->insert($imageQr, "left-top", 198, 300);
        $image->save($QR);

        $this->assign("imageurl", $QR);

        $this->display();
    }

    /**
     * 收款链接
     */
    public function link()
    {
        $site = ((is_https()) ? 'https' : 'http') . '://' . C("DOMAIN");
        $url  = U('Pay/Charges/index', array('mid' => ($this->fans['uid'] + 10000)));
        $url  = urldecode($site . $url);
        $this->assign("url", $url);
        $this->display();
    }

    /**
     * 下载二维码
     */
    public function downQrcode()
    {
        $QR       = "Uploads/charges/" . ($this->fans['uid'] + 10000) . ".png";
        $filename = ($this->fans['uid'] + 10000) . ".png";
        header("Content-type: octet/stream");
        header("Content-disposition:attachment;filename=" . $filename . ";");
        header("Content-Length:" . filesize($QR));
        readfile($QR);
    }
    /**
     * 保存二维码背景
     */
    public function uploadQrcode()
    {
        $config = array(
            'maxSize'  => 3145728,
            'rootPath' => 'Public/images/',
            'savePath' => '',
            'saveName' => 'qrcode_bg',
            'replace'  => true,
            'exts'     => array('jpg', 'gif', 'png', 'jpeg'),
        );
        $upload = new \Think\Upload($config); // 实例化上传类
        // 上传文件
        $info = $upload->upload();
        if (!$info) {
// 上传错误提示错误信息
            $response = ['code' => 1, 'msg' => $upload->getError(), 'data' => ['url' => '']];
            $this->ajaxReturn($response);
        } else {
// 上传成功
            $response = ['code' => 0, 'msg' => '上传成功', 'data' => ['url' => '']];
            $this->ajaxReturn($response);
        }
    }

    /**
     * 保存台卡收款人
     */
    public function saveReceiver()
    {
        $p = I('request.p');
        M("Member")->where(['id' => $this->fans['uid']])->save($p);
        $this->redirect('User/Account/qrcode');
    }

    /**
     * 发送修改登录密码的验证码信息
     */
    public function editPasswordSend()
    {
        $res = $this->send('editPassword', $this->fans['mobile'], '登录密码');
        $this->ajaxReturn(['status' => $res['code']]);
    }
    /**
     * 发送修改支付密码的验证码信息
     */
    public function editPayPasswordSend()
    {
        $res = $this->send('editPayPassword', $this->fans['mobile'], '支付密码');
        $this->ajaxReturn(['status' => $res['code']]);
    }

    /**
     * 绑定手机验证码
     */
    public function bindMobile()
    {
        $mobile = I('request.mobile');
        $res    = $this->send('bindMobile', $mobile, '绑定手机');
        $this->ajaxReturn(['status' => $res['code']]);
    }

    public function editMobile()
    {
        $mobile = I('request.mobile', '');
        if (!$mobile) {
            $mobile = $this->fans['mobile'];
        }
        $res = $this->send('editMobile', $mobile, '修改手机');
        $this->ajaxReturn(['status' => $res['code']]);
    }

    public function channelFinance()
    {

        $Product = M('Product');

        $count = $Product->count();
        $Page  = new Page($count, 15);
        $show  = $Page->show();

        $productList = $Product
            ->field(['id', 'name', 'code'])
            ->limit($Page->firstRow . ',' . $Page->listRows)
            ->select();

        $payMemberid = session('user_auth.uid') + 10000;
        //注意因为很多客户的订单量是很大的，这里要分割查询，我是想不到好的方法 =。=
        $Order      = M('Order');
        $orderCount = $Order->where(['pay_memberid' => $payMemberid])->count();
        $orderList  = [];
        $limit      = 100000;
        for ($i = 0; $i < $orderCount; $i += $limit) {

            $tempList = $Order
                ->field(['pay_bankcode', 'pay_amount', 'pay_poundage', 'pay_actualamount', 'pay_status'])
                ->where(['pay_memberid' => $payMemberid])
                ->limit($i, $limit)
                ->select();

            $orderList = array_merge($orderList, $tempList);
        }

        // dump($orderList);exit;
        //处理查询的数据
        foreach ($productList as $k => $v) {

            $productList[$k]['count']            = 0;
            $productList[$k]['fail_count']       = 0;
            $productList[$k]['success_count']    = 0;
            $productList[$k]['success_rate']     = 0;
            $productList[$k]['pay_amount']       = 0.00;
            $productList[$k]['pay_poundage']     = 0.00;
            $productList[$k]['pay_actualamount'] = 0.00;


            foreach ($orderList as $k1 => $v1) {
                if ($v['id'] == $v1['pay_bankcode']) {
                    $productList[$k]['count']++;
                    if ($v1['pay_status'] != 0) {
                        $productList[$k]['success_count']++;
                        $productList[$k]['pay_amount']       = bcadd($productList[$k]['pay_amount'], $v1['pay_amount'], 2);
                        $productList[$k]['pay_poundage']     = bcadd($productList[$k]['pay_poundage'], $v1['pay_poundage'], 2);
                        $productList[$k]['pay_actualamount'] = bcadd($productList[$k]['pay_actualamount'], $v1['pay_actualamount'], 2);
                    }
                }
            }
            $productList[$k]['fail_count'] = $productList[$k]['count'] - $productList[$k]['success_count'];
            $productList[$k]['success_rate'] = bcdiv($productList[$k]['success_count'],$productList[$k]['count'],4) * 100;
        }
        
        $this->assign('list', $productList);
        $this->display();
    }

    /**
     * 绑定谷歌身份认证
     */
    public function google()
    {
        $ga = new \Org\Util\GoogleAuthenticator();
        if (!IS_POST) {
            $google_token = M('Member')->where(['id'=>$this->fans['uid']])->getField('google_secret_key');
            if($google_token == '') {
                $secret = session('user_google_secret_key') ? session('user_google_secret_key') : $ga->createSecret();
                $qrCodeUrl = $ga->getQRCodeGoogleUrl($_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"], $secret);
                session('user_google_secret_key', $secret);
                $this->assign('secret', $secret);
                $this->assign('qrCodeUrl', $qrCodeUrl);
            }
            //查询是否开启短信验证
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                $this->assign('sendUrl', U('User/Account/profileSend'));
            }
            $this->assign('google_token', $google_token);
            $this->assign('sms_is_open', $sms_is_open);
            $this->display();
        } else {
            //验证短信验证码
            $code        = I('request.code');
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                if (session('send.saveProfile') == $code && $this->checkSessionTime('saveProfile', $code)) {
                    session('send', null);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '短信验证码错误']);
                }
            }
            $googlecode = I('googlecode', '');
            if($googlecode == '') {
                $this->ajaxReturn(["status"=>0, "msg"=>"请输入验证码"]);
            }
            $google_secret_key = session('user_google_secret_key');
            if(!$google_secret_key) {
                $this->error("绑定失败，请刷新页面重试");
            }
            $oneCode = $ga->getCode($google_secret_key);
            if($googlecode !== $oneCode) {
                $this->ajaxReturn(["status"=>0, "msg"=>"谷歌安全码错误"]);
            } else {
                $re = M('Member')->where(array('id'=>$this->fans['uid'],'google_secret_key'=>array('eq','')))->save(['google_secret_key'=>$google_secret_key]);
                if(FALSE !== $re) {
                    session('user_google_auth', $oneCode);
                    $this->ajaxReturn(["status"=>1, "msg"=>"绑定成功"]);
                } else {
                    $this->ajaxReturn(["status"=>0, "msg"=>"绑定失败，请售后重试"]);
                }
            }

        }
    }

    /**
     * 解绑谷歌身份验证
     */
    public function unbindGoogle()
    {
        if(IS_POST) {
            //验证短信验证码
            $code        = I('request.code');
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                if (session('send.unbindGoogle') == $code && $this->checkSessionTime('unbindGoogle', $code)) {
                    session('send', null);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '短信验证码错误']);
                }
            }
            $re = M('Member')->where(array('id'=>$this->fans['uid']))->save(['google_secret_key'=>'']);
            if(FALSE !== $re) {
                session('user_google_auth', null);
                $this->ajaxReturn(["status"=>1, "msg"=>"解绑成功"]);
            } else {
                $this->ajaxReturn(["status"=>0, "msg"=>"解绑失败，请售后重试"]);
            }
        } else {
            //查询是否开启短信验证
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                $this->assign('sendUrl', U('User/Account/unbindGoogleSend'));
            }
            $this->assign('user', $this->fans);
            $this->assign('sms_is_open', $sms_is_open);
            $this->display();
        }
    }

    /**
     * 解绑谷歌身份验证器验证码
     */
    public function unbindGoogleSend()
    {
        $user = M('Member')->where(['id'=>$this->fans['uid']])->find();
        $mobile = I('request.mobile', '');
        if (!$mobile) {
            $mobile = $user['mobile'];
        }
        $res = $this->send('unbindGoogle', $mobile, '解绑谷歌身份验证器');
        $this->ajaxReturn(['status' => $res['code']]);
    }

    /**
     * 保证金明细
     */
    public function complaintsDeposit()
    {

        $where   = array();
        $orderid = I("get.orderid");
        if ($orderid) {
            $where['out_trade_id'] = array('eq', $orderid);
        }
        $status = I("request.status", '');
        if ($status !='') {
            $where['status'] = array('eq', $status);
        }
        $createtime = urldecode(I("request.createtime"));
        if ($createtime) {
            list($cstime, $cetime) = explode('|', $createtime);
            $map['create_at'] = $where['create_at']     = ['between', [strtotime($cstime), strtotime($cetime) ? strtotime($cetime) : time()]];
        }
        $map['user_id'] = $where['user_id'] = $this->fans['uid'];
        $count           = M('complaints_deposit')->where($where)->count();
        $size            = 15;
        $rows            = I('get.rows', $size);
        if (!$rows) {
            $rows = $size;
        }
        $page = new Page($count, $rows);
        $list = M('complaints_deposit')
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order('id desc')
            ->select();
        /* 统计 */
        //总保证金金额
        $stats['all'] = M('complaints_deposit')->where($map)->sum('freeze_money');
        //总已解冻金额
        $map['status'] = 1;
        $stats['freezed'] = M('complaints_deposit')->where($map)->sum('freeze_money');
        //总待解冻金额
        $map['status'] = 0;
        $stats['unfreezed'] = M('complaints_deposit')->where($map)->sum('freeze_money');
        foreach($stats as $k => $v) {
            $stats[$k] = $v+0;
        }
        $this->assign('stats', $stats);
        $this->assign('rows', $rows);
        $this->assign('list', $list);
        $this->assign('page', $page->show());
        C('TOKEN_ON', false);
        $this->display();
    }

    /**
     * 资金冻结明细
     */
    public function frozenMoney()
    {

        $where   = array();
        $status = I("request.status", '');
        if ($status !='') {
            $where['status'] = array('eq', $status);
        }
        $createtime = urldecode(I("request.createtime"));
        if ($createtime) {
            list($cstime, $cetime) = explode('|', $createtime);
            $map['create_at'] = $where['create_at']     = ['between', [strtotime($cstime), strtotime($cetime) ? strtotime($cetime) : time()]];
        }
        $map['user_id'] = $where['user_id'] = $this->fans['uid'];
        $count           = M('autoUnfrozenOrder')->where($where)->count();
        $size            = 15;
        $rows            = I('get.rows', $size);
        if (!$rows) {
            $rows = $size;
        }
        $page = new Page($count, $rows);
        $list = M('autoUnfrozenOrder')
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order('id desc')
            ->select();
        /* 统计 */
        //待解冻金额
        $map['status'] = 0;
        $stats['unfreezed'] = M('autoUnfrozenOrder')->where($map)->sum('freeze_money');
        foreach($stats as $k => $v) {
            $stats[$k] = $v+0;
        }
        $this->assign('stats', $stats);
        $this->assign('rows', $rows);
        $this->assign('list', $list);
        $this->assign('page', $page->show());
        C('TOKEN_ON', false);
        $this->display();
    }

    /**
     * 对账单
     */
    public function reconciliation()
    {
        $date = urldecode(I("request.date", ''));
        if(!$date) {//默认今日
            $date = date('Y-m-d');
        }
        if ($memberid = I('get.memberid', '')) {
            $where['id'] = $memberid - 10000;
        }
        if($date>date('Y-m-d') || strtotime($date)<$this->fans['regdatetime']) {
            $this->error('日期错误');
        }

        $time = M('Member')->where(['id'=>$this->fans['uid']])->getField('regdatetime');
        $time = strtotime(date('Y-m-d', $time));
        $timestamp = strtotime($date);
        $count      = ceil(($timestamp-$time)/86400)+1;
        $page       = new Page($count, 10);
        $xh = $count < 10 ? $count : 10;
        $start_time = $date;
        $offset = ($_GET['p']-1) * $page->listRows-1;
        if($offset>0) {
            $max_date = strtotime("$start_time -$offset day") - 1;
        } else {
            $max_date = strtotime($date);
        }
        $list = array();
        for($i=0; $i<$xh; $i++) {
            $start_time = $max_date-$i*86400;
            if($start_time<$time) {
                break;
            }
            $begin = date('Y-m-d',$start_time).' 00:00:00';
            $end = date('Y-m-d H:i:s',strtotime(date('Y-m-d',$start_time))+86400-1);
            $list[$i] = $this->getDayReconciliation($begin, $end);
        }
        $this->assign('list', $list);
        $this->assign('page', $page->show());
        $this->assign('time',date('Y-m-d',$time));
        $this->assign('date', $date);
        C('TOKEN_ON', false);
        $this->display();
    }

    //获取某天对账单
    private function getDayReconciliation($begin, $end) {

        $date = date('Y-m-d', strtotime($begin));
        $data = M('reconciliation')->where(['userid'=>$this->fans['uid'],'date'=>$date])->find();
        if(empty($data)) {
            $insertFlag = true;
        } else {
            $insertFlag = false;
        }
        if(empty($data) || (!empty($data) && diffBetweenTwoDays(date('Y-m-d'),$date)<=3)) {//3天内账单实时更新数据
            $data['date'] = $date;
            $data['order_total_count'] = M('Order')->where(['pay_memberid'=>$this->fans['memberid'],'pay_applydate' => ['between', [strtotime($begin), strtotime($end)]]])->count();
            //成功订单数
            $data['order_success_count'] = M('Order')->where(['pay_memberid'=>$this->fans['memberid'],'pay_applydate' => ['between', [strtotime($begin), strtotime($end)]], 'pay_status' => ['in', '1,2']])->count();
            //未支付订单数
            $data['order_fail_count'] = M('Order')->where(['pay_memberid'=>$this->fans['memberid'],'pay_applydate' => ['between', [strtotime($begin), strtotime($end)]], 'pay_status' => 0])->count();
            //订单总额
            $data['order_total_amount'] = M('Order')->where(['pay_memberid'=>$this->fans['memberid'],'pay_applydate' => ['between', [strtotime($begin), strtotime($end)]]])->sum('pay_amount');
            //订单实付总额
            $data['order_success_amount'] = M('Order')->where(['pay_memberid'=>$this->fans['memberid'],'pay_successdate' => ['between', [strtotime($begin), strtotime($end)], 'pay_status' => ['in', '1,2']]])->sum('pay_actualamount');
            if($insertFlag) {
                $data['userid'] = $this->fans['uid'];
                $data['ctime'] = time();
                M('reconciliation')->add($data);
            } else {
                M('reconciliation')->where(['id'=>$data['id']])->save($data);
            }
        }
        unset($data['userid']);
        unset($data['ctime']);
        unset($data['id']);
        foreach($data as $k => $v) {
            if($k != 'date') {
                $data[$k] += 0;
                if($k == 'order_total_amount' || $k == 'order_success_amount') {
                    $data[$k] = number_format($data[$k], 2, '.', ',');
                }
            }
        }
        return $data;
    }
}
