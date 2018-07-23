<?php
namespace Admin\Controller;

use Think\Page;

class ContentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 栏目列表
     */
    public function category()
    {
        $cates    = M('Category')->order('id desc')->select();
        $category = list_to_tree($cates);
        $this->assign('category', $category);
        $this->display();
    }

    /**
     * 添加栏目
     */
    public function addCategory()
    {
        $data = M('Category')->where(['pid' => 0])->select();
        $this->assign('cates', $data);
        $this->display();
    }

    /**
     * 保存添加
     */
    public function saveAddCategory()
    {
        if (IS_POST) {
            $pid = I('post.pid');
            $c   = I('post.c');
            if ($pid) {
                $c['pid'] = $pid;
                $res      = M('Category')->add($c);
            } else {
                $res = M('Category')->add($c);
            }
            $this->ajaxReturn(['status' => $res]);
        }
    }

    /**
     * 编辑栏目
     */
    public function editCategory()
    {
        $cid  = I('get.cid');
        $data = M('Category')->where(['id' => $cid])->find();
        $this->assign('c', $data);

        //顶级栏目
        $cate = M('Category')->where(['pid' => 0])->field('id,name')->select();
        $this->assign('cates', $cate);
        $this->display();
    }

    /**
     * 保存编辑
     */
    public function saveEditCategory()
    {
        if (IS_POST) {
            $cid = I('post.cid', 0, 'intval');
            $c   = I('post.c');
            if ($cid) {
                $res = M('Category')->where(['id' => $cid])->save($c);
            }
            $this->ajaxReturn(['status' => $res]);
        }
    }

    /**
     * 删除栏目
     */
    public function delCategory()
    {
        if (IS_POST) {
            $id = I('post.id', 0, 'intval');
            if ($id) {
                $res = M('Category')->where(['id' => $id])->delete();
            }
            $this->ajaxReturn(['status' => $res]);
        }
    }

    /**
     * 文章列表
     */
    public function article()
    {
        $count = M('Article')->count();
        $page  = new Page($count, 15);
        $list  = M('Article')->limit($page->firstRow . ',' . $page->listRows)->order('id desc')->select();
        $this->assign('list', $list);
        $this->assign('page', $page->show());
        $this->assign('groups', C('ARTICLE_GROUP_ID'));
        $this->display();
    }

    /**
     * 保存添加文章
     */
    public function saveAddArticle()
    {
        if (IS_POST) {
            $c = $_POST['c'];
            $c['createtime'] = $c['createtime'] ? strtotime($c['createtime']) : time();
            $res = M('Article')->add($c);
            $this->ajaxReturn(['status' => $res]);
        }
    }

    /**
     * 编辑文章
     */
    public function editArticle()
    {
        $id      = I("get.id", 0, 'intval');
        $article = M('Article')->where(['id' => $id])->find();
        $this->assign("a", $article);
        //栏目
        $cate     = M('Category')->select();
        $category = list_to_tree($cate);
        $this->assign('category', $category);
        $this->display();
    }

    /**
     *  保存编辑文章
     */
    public function saveEditArticle()
    {
        if (IS_POST) {
            $id = I('post.id', 0, 'intval');
            $c  = $_POST['c'];
            if ($id) {
                unset($c['createtime']);
                $c['updatetime'] = time();
                $res             = M('Article')->where(['id' => $id])->save($c);
                $this->ajaxReturn(['status' => $res]);
            }
        }
    }
    /**
     * 删除文章
     */
    public function delArticle()
    {
        if (IS_POST) {
            $id = I("post.id");
            if ($id) {
                $res = M("Article")->where(['id' => $id])->delete();
            }
            $this->ajaxReturn(['status' => $res]);
        }
    }

    /**
     * 添加文章
     */

    public function addArticle()
    {
        //栏目
        $cate     = M('Category')->select();
        $category = list_to_tree($cate);
        $this->assign('category', $category);
        $this->display();
    }

    /**
     * 预览文章
     */
    public function show()
    {
        $id      = I('get.id', 0, 'intval');
        $article = M('Article')->where(['id' => $id])->find();
        $this->assign('a', $article);
        $this->display();
    }
}
