<?php
namespace controllers;

use models\Text2;

class Text2Controller{
    // 列表页
    public function index()
    {
        $model = new Text2;
        $data = $model->findAll();
        view('text2/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        view('text2/create');
    }

    // 处理添加表单
    public function insert()
    {
        $model = new Text2;
        $model->fill($_POST);
        $model->insert();
        redirect('/text2/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Text2;
        $data=$model->findOne($_GET['id']);
        view('text2/edit', [
            'data' => $data,    
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $model = new Text2;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/text2/index');
    }

    // 删除
    public function delete()
    {
        $model = new Text2;
        $model->delete($_GET['id']);
        redirect('/text2/index');
    }
}