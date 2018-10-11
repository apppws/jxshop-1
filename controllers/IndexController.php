<?php
namespace controllers;

class IndexController extends BaseController
{
    public function index()
    {
        view('index/index');
    }
    public function top()
    {
        view('index/top');
    }
    public function menu()
    {
        $model = new \models\Privilege;
        $data = $model->tree();
        // echo "<pre>";
        // var_dump($data);
        view('index/menu',[
            'data'=>$data
        ]);
    }
    public function main()
    {
        view('index/main');

    }
}