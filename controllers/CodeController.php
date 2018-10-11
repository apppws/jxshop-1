<?php
namespace controllers;

class CodeController extends BaseController
{
    // 生成代码
    public function make()
    {
        // 1. 接收参数（生成代码的表名）
        $tableName = $_GET['name'];

        // 取出这个表中所有的字段信息
        $sql = "SHOW FULL FIELDS FROM $tableName";
        $db = \libs\Db::make();
        // 预处理
        $stmt = $db->prepare($sql);
        // 执行 SQL
        $stmt->execute();
        // 取出数据
        $fields = $stmt->fetchAll( \PDO::FETCH_ASSOC );

        // 收集所有字段的白名单
        $fillable = [];
        foreach($fields as $v)
        {
            if($v['Field'] == 'id' || $v['Field'] == 'created_at')
                continue ;
            $fillable[] = $v['Field'];
        }
        $fillable = implode("','", $fillable);

        $mname = ucfirst($tableName);
        //将这个模块的权限添加的数据库=====START=============================
        $zh_name=$_GET['zh_name'];      //模块中文名称
        $model=new \models\Privilege;
        $pri_top=[
            'pri_name'=>$zh_name.'模块',
            'url_path'=>'',
            'parent_id'=>0,
        ];
        $model->fill($pri_top);
        $model->insert();
        $top_id=$model->data['id'];
        $pri_top=[
            'pri_name'=>$zh_name.'列表',
            'url_path'=>'',
            'parent_id'=>$top_id,
        ];
        $model->fill($pri_top);
        $model->insert();
        $pri_id=$model->data['id'];
        $pri_arr=[
            [
                'pri_name'=>'添加'.$zh_name,
                'url_path'=>$tableName.'/create,'.$tableName.'/insert',
                'parent_id'=>$pri_id, 
            ],
            [
                'pri_name'=>'修改'.$zh_name,
                'url_path'=>$tableName.'/edit,'.$tableName.'/update',
                'parent_id'=>$pri_id, 
            ],
            [
                'pri_name'=>'删除'.$zh_name,
                'url_path'=>$tableName.'/delete',
                'parent_id'=>$pri_id, 
            ]
        ];

        foreach($pri_arr as $v){
            $model->fill($v);
            $model->insert();
        }

        // 2. 生成控制器
        // 拼出控制名的名字
        $cname = ucfirst($tableName).'Controller';
        /*
        加载模板
        */
        ob_start();
        include(ROOT . 'templates/controller.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.'controllers/'.$cname.'.php', "<?php\r\n".$str);

        // 3. 生成模型
        
        ob_start();
        include(ROOT . 'templates/model.php');
        $str = ob_get_clean();
        file_put_contents(ROOT.'models/'.$mname.'.php', "<?php\r\n".$str);

        // 4. 生成视图文件
        // 生成视图目录
        @mkdir(ROOT . 'views/'.$tableName, 0777);

        // echo '<pre>';
        // var_dump( $fields );

        // exit;

        // create.html
        ob_start();
        include(ROOT . 'templates/create.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/create.html', $str);

        // edit.html
        ob_start();
        include(ROOT . 'templates/edit.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/edit.html', $str);

        // index.html
        ob_start();
        include(ROOT . 'templates/index.html');
        $str = ob_get_clean();
        file_put_contents(ROOT.'views/'.$tableName.'/index.html', $str);

    }
}